<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ChannelHotel;
use App\Entity\Hotel;
use App\Entity\Location;
use App\Entity\Zone;
use App\Entity\Language;
use App\Entity\MediaObject;
use DateTime;
use App\Entity\Modality;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ActivityRepository;
use App\Repository\ChannelRepository;
use App\Repository\LocationRepository;
use App\Repository\ZoneRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductTagRepository;
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
    public function listHotels(Request $request): Response
    {
        $access = json_decode($request->getContent(), true);

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
  "query": "query {\\thotelX { hotels(criteria: {access: \\"' . $access . '\\", destinationCodes: [\"PLYPALM\", \"SACO\", \"ALCU\", \"SPONSA\", \"CBONA\", \"CMILL\", \"PICAF\", \"CBLANC\", \"SANTANYI\", \"ILLET\", \"MAGA\", \"CRAT\", \"PAGUE\", \"MURO\", \"PUIGPUNYEN\", \"CALAD\", \"CBOSCH\", \"FORCAT\"]}, token: \\"\\") { token count edges { node { createdAt updatedAt hotelData { hotelCode hotelName categoryCode chainCode location { address zipCode city country coordinates { latitude longitude } closestDestination { code available texts { text language } type parent } } contact { email telephone fax web } propertyType { propertyCode name } descriptions { type texts { language text } } medias { code url } rooms { edges { node { code roomData { code roomCode allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } } }"
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
    public function importHotels(EntityManagerInterface $entityManager, Request $request, ChannelRepository $channelRepository, ProductTagRepository $productTagRepository): Response
    {
        $dataDecode = json_decode($request->getContent(), true);

        $hotels = $dataDecode['hotels'];
        $channelId = $dataDecode['channel'];

        $hotelProductTag = $productTagRepository->find(1);
        $channel = $channelRepository->find($channelId);

        foreach ($hotels as $hotel) {
            $newHotel = new Hotel();

            $newHotel->setTitle(ucwords(strtolower($hotel['title'])));
            $newHotel->setSlug($this->slugify($hotel['title']));
            $newHotel->setProductTag($hotelProductTag);
            $newHotel->setRating($hotel['stars']);
            $newHotel->setExtendedDescription($hotel['description']);
            $newHotel->setAddress($hotel['zone']);

            foreach ($hotel['medias'] as $image) {
                $newMediaObject = new MediaObject();
                $newMediaObject->setExternalUrl($image);
                $newMediaObject->setType('img');
                $newMediaObject->setHotel($newHotel);
                $entityManager->persist($newMediaObject);
            }

            $newChannelHotel = new ChannelHotel();

            $newChannelHotel->setHotel($newHotel);
            $newChannelHotel->setChannel($channel);

            $newChannelHotel->setCode($hotel['id']);
            // $newChannelHotel->setChannelCode();


            $entityManager->persist($newChannelHotel);


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

    public static function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    #[Route('/search_hotels', name: 'app_search_hotels')]
    public function searchHotels(Request $request): Response
    {
        $search = json_decode($request->getContent());

        $checkIn = new DateTime($search->checkIn);
        $checkOut = new DateTime($search->checkOut);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.travelgatex.com',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "query": "query {\thotelX { search( criteria: { checkIn: \"' . $checkIn->format('Y-m-d') . '\", checkOut: \"' . $checkOut->format('Y-m-d') . '\", occupancies: [ { paxes: [ { age: 30 }, { age: 30 } ] } ], hotels: [ \"' . $search->hotel . '\" ], currency: \"EUR\", markets: [ \"ES\" ], language: \"es\", nationality: \"ES\" }, settings: { client: \"it2b\", context: \"' . $search->channelCode . '\", timeout: 25000 }, filterSearch: { access: { includes: [ \"' . $search->access . '\" ] } }) { context errors { code type description } warnings { code type description } options { id accessCode supplierCode hotelCode hotelName boardCode paymentType status occupancies { id paxes { age } } rooms { occupancyRefId code description refundable roomPrice { price { currency binding net gross exchange { currency rate } } breakdown { start end price { currency binding net gross exchange { currency rate } minimumSellingPrice } } } beds { type count } ratePlans { start end code name } } price { currency binding net gross exchange { currency rate } minimumSellingPrice markups { channel currency binding net gross exchange { currency rate } rules { id name type value } } } supplements { start end code name description supplementType chargeType mandatory durationType quantity unit resort { code name description } price { currency binding net gross exchange { currency rate } } } surcharges { code chargeType description mandatory price { currency binding net gross exchange { currency rate } markups { channel currency binding net gross exchange { currency rate } } } } rateRules cancelPolicy { refundable cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } remarks } }\t}}"
              }',
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Connection: keep-alive",
                'Authorization: Apikey 4794442a-a4dc-4660-5083-64360879e063',
                'Content-Type: application/json'
            ),
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        $resp = $response['data']['hotelX']['search']['options'];

        return $this->json([
            'hotels'  => $resp
        ]);
    }

    #[Route('/prebooking_hotels', name: 'app_prebooking_hotels')]
    public function prebookingHotels(Request $request): Response
    {
        $dataDecode = json_decode($request->getContent());

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
  "query": "query {\\thotelX { quote(criteria: { optionRefId: \\"'. $dataDecode->id .'\\" }, settings: { client: \\"it2b\\", context: \\"' . $dataDecode->channelCode . '\\", timeout: 5000 }) { errors { code type description } warnings { code type description } optionQuote { optionRefId status price { currency binding net gross exchange { currency rate } minimumSellingPrice } surcharges { chargeType price { currency binding net gross exchange { currency rate } minimumSellingPrice } description } cancelPolicy { refundable description cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } paymentType cardTypes remarks } }\\t}}"
}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Apikey 4794442a-a4dc-4660-5083-64360879e063',
                'TGX-Content-Type: graphqlx/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        // $resp = $response['data']['hotelX']['quote']['options'];

        return $this->json([
            'quote'  => $response
        ]);
    }
}
