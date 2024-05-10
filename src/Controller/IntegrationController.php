<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Hotel;
use App\Entity\Location;
use App\Entity\Zone;
use App\Entity\Language;
use App\Entity\MediaObject;
use App\Entity\Modality;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ActivityRepository;
use App\Repository\LocationRepository;
use App\Repository\ZoneRepository;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntegrationController extends AbstractController
{
    #[Route('/integration', name: 'app_integration')]
    public function index(): Response
    {
        return $this->render('integration/index.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }

    #[Route('/list_hotels', name: 'app_list_hotels')]
    public function listHotels(): Response
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.travelgatex.com/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
  "query": "query {\\thotelX { hotels(criteria: {access: \\"26465\\", destinationCodes: [\"PLYPALM\", \"SACO\", \"ALCU\", \"SPONSA\", \"CBONA\", \"CMILL\", \"PICAF\", \"CBLANC\", \"SANTANYI\", \"ILLET\", \"MAGA\", \"CRAT\", \"PAGUE\", \"MURO\", \"PUIGPUNYEN\", \"CALAD\", \"CBOSCH\", \"FORCAT\"]}, token: \\"\\") { token count edges { node { createdAt updatedAt hotelData { hotelCode hotelName categoryCode chainCode location { address zipCode city country coordinates { latitude longitude } closestDestination { code available texts { text language } type parent } } contact { email telephone fax web } propertyType { propertyCode name } descriptions { type texts { language text } } medias { code url } rooms { edges { node { code roomData { code roomCode allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } } }"
}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Apikey 4794442a-a4dc-4660-5083-64360879e063',
                'TGX-Content-Type: graphqlx/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);

        curl_close($curl);

        return $this->json([
            'response'  => $response
        ]);
    }

    #[Route('/import_hotels', name: 'app_import_hotels')]
    public function importHotels(EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $hotel) {
            $newHotel = new Hotel();

            $newHotel->setTitle($hotel['title']);
            // $newHotel->setLocation($hotel['destiny']);
            // $newHotel->setTravelgateId($hotel['id']);
            // $newHotel->addMedias($hotel['medias']);
            $newHotel->setRating($hotel['stars']);
            $newHotel->setExtendedDescription($hotel['description']);
            $newHotel->setAddress($hotel['zone']);

            // foreach ($product['images'] as $image) {
            //     $mediaObject = new MediaObject();
            //     $mediaObject->setExternalUrl($image['extra_large']);
            //     $mediaObject->setType('img');
            //     $mediaObject->setPosition($i);
            //     $mediaObject->setActivity($activity);
            //     $entityManager->persist($mediaObject);
            //     $i++;
            // }

            foreach ($hotel['medias'] as $image) {
                $newMediaObject = new MediaObject();
                $newMediaObject->setExternalUrl($image);
                $newMediaObject->setType('img');
                $newMediaObject->setHotel($newHotel);
                $entityManager->persist($newMediaObject);
            }

            $entityManager->persist($newHotel);
        }

        $entityManager->flush();

        return $this->json([
            'response'  => $newHotel
        ]);
    }

    #[Route('/import_products', name: 'app_import_products')]
    public function importProducts(EntityManagerInterface $entityManager, ActivityRepository $activityRepository, LocationRepository $locationRepository, ZoneRepository $zoneRepository, LanguageRepository $languageRepository): Response
    {
        $products = [];
        foreach ([1214, 65915, 263579, 975, 263711, 270927, 65953, 94294, 66096, 261731, 175249, 65991, 268832] as $city) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.tiqets.com/v2/products?city_id=' . $city . '&page_size=100&lang=es',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer kf6uciPXPueQhVvGNuO4qys2OiX91zg2',
                    'Content-Type: application/json'
                ),
                CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"
            ));

            $response = curl_exec($curl);
            $response = json_decode($response, true);

            curl_close($curl);

            foreach ($response['products'] as $product) {
                if (in_array($product['id'], $products)) {
                    continue;
                } else {
                    array_push($products, $product['id']);
                }
                $isUpdate = true;
                $activity = $activityRepository->findOneBy(array('tiqetsId' => $product['id']));
                if (!$activity) {
                    $isUpdate = false;
                    $activity = new Activity();
                }
                $activity->setTitle($product['title']);
                $activity->setSlug($product['product_slug']);
                $activity->setShortDescription($product['tagline']);
                $activity->setExtendedDescription($product['summary']);
                $activity->setHighlights($product['highlights']);
                $activity->setIncludes($product['whats_included']);
                $activity->setNotIncludes($product['whats_excluded']);
                $activity->setHighlights($product['highlights']);
                $activity->setLongitude($product['geolocation']['lng']);
                $activity->setLatitude($product['geolocation']['lat']);
                $activity->setTiqetsId($product['id']);

                if ($product['cancellation']['policy'] == 'before_date') {
                    if ($product['cancellation']['window'] == 0) {
                        $activity->setCancelationConditions('La cancelación es posible hasta las 23:59 del día antes de la visita.');
                    } else {
                        $activity->setCancelationConditions('La cancelación es posible hasta ' . $product['cancellation']['window'] . ' horas antes de la fecha de visita.');
                    }
                }

                if ($product['cancellation']['policy'] == 'before_timeslot') {
                    if ($product['cancellation']['window'] == 0) {
                        $activity->setCancelationConditions('La cancelación es posible hasta la franja horaria seleccionada.');
                    } else {
                        $activity->setCancelationConditions('La cancelación es posible hasta ' . $product['cancellation']['window'] . ' horas antes de la franja horaria seleccionada.');
                    }
                }

                if ($product['cancellation']['policy'] == 'never') {
                    $activity->setCancelationConditions('No reembolsable.');
                }

                if (!$isUpdate) {
                    $zone = $zoneRepository->findOneBy(array('name' => $product['city_name']));
                    $location = $locationRepository->findOneBy(array('name' => 'Mallorca'));

                    $activity->setLocation($location);

                    $modality = new Modality();
                    $modality->setTitle($product['title']);
                    $modality->setDuration($product['duration']);

                    $modality->setPrice($product['price']);
                    foreach ($product['languages'] as $languageCode) {
                        $language = $languageRepository->findOneBy(array('shortName' => $languageCode));
                        if ($language) {
                            $modality->addLanguage($language);
                        }
                    }

                    $activity->addModality($modality);

                    if ($zone) {
                        $activity->addZone($zone);
                    } else {
                        $zone = new Zone();
                        $zone->setName($product['city_name']);
                        $zone->setLocation($location);
                        $entityManager->persist($zone);
                        $activity->addZone($zone);
                    }

                    $i = 0;
                    foreach ($product['images'] as $image) {
                        $mediaObject = new MediaObject();
                        $mediaObject->setExternalUrl($image['extra_large']);
                        $mediaObject->setType('img');
                        $mediaObject->setPosition($i);
                        $mediaObject->setActivity($activity);
                        $entityManager->persist($mediaObject);
                        $i++;
                    }
                } else {
                    $modality = $activity->getModalities()[0];
                    $modality->setDuration($product['duration']);

                    $modality->setPrice($product['price']);
                    $entityManager->persist($modality);
                }

                $entityManager->persist($activity);
                $entityManager->flush();
            }
        }


        return $this->json([
            'response'  => true
        ]);
    }
}
