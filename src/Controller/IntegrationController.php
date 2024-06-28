<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ChannelHotel;
use App\Entity\Hotel;
use App\Entity\Booking;
use App\Entity\BookingLine;
use App\Entity\Zone;
use App\Entity\MediaObject;
use DateTime;
use App\Entity\Modality;
use App\Entity\Voucher;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ActivityRepository;
use App\Repository\HotelRepository;
use App\Repository\BookingRepository;
use App\Repository\ChannelRepository;
use App\Repository\HotelAvailabilityRepository;
use App\Repository\LocationRepository;
use App\Repository\ZoneRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\BookingController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Knp\Snappy\Pdf;
use App\Repository\VoucherRepository;
use App\Repository\ConfigurationRepository;

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
            //             CURLOPT_POSTFIELDS => '{
            //   "query": "query {\\thotelX { hotels(criteria: {access: \\"' . $access . '\\", destinationCodes: [\"PLYPALM\", \"SACO\", \"ALCU\", \"SPONSA\", \"CBONA\", \"CMILL\", \"PICAF\", \"CBLANC\", \"SANTANYI\", \"ILLET\", \"MAGA\", \"CRAT\", \"PAGUE\", \"MURO\", \"PUIGPUNYEN\", \"CALAD\", \"CBOSCH\", \"FORCAT\"]}, token: \\"\\") { token count edges { node { createdAt updatedAt hotelData { hotelCode hotelName categoryCode chainCode location { address zipCode city country coordinates { latitude longitude } closestDestination { code available texts { text language } type parent } } contact { email telephone fax web } propertyType { propertyCode name } descriptions { type texts { language text } } medias { code url } rooms { edges { node { code roomData { code roomCode allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } } }"
            // }',
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

        $checkIn = strtotime($search->checkIn);
        $checkIn =  \DateTime::createFromFormat('U', (strtotime('+1 day', $checkIn)));
        $checkOut = strtotime($search->checkOut);
        $checkOut =  \DateTime::createFromFormat('U', ((strtotime('+1 day', $checkOut))));
        $hotels = json_encode($search->hotels);

        $output = "[";
        foreach ($search->rooms as $index => $room) {
            $output .= "{ paxes: [";
            foreach ($room->clientTypes as $key => $value) {
                if ($key == 'adults') {
                    for ($i=0; $i < $value->quantity; $i++) { 
                        $output .= "{ age: 30 }";
                        if ($i < $value->quantity - 1) {
                            $output .= ", ";
                        }
                    }
                } else {
                    for ($i=0; $i < $value->quantity; $i++) { 
                        $age = $value->ages[$i];
                        $output .= ", { age: $age }";
                        if ($i < $value->quantity - 1) {
                            $output .= ", ";
                        }
                    }
                }
            }
            $output .= "] }";
        }

        $output .= "]";

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
            // CURLOPT_POSTFIELDS => '{
            //     "query": "query {\thotelX { search( criteria: { checkIn: \"' . $checkIn->format('Y-m-d') . '\", checkOut: \"' . $checkOut->format('Y-m-d') . '\", occupancies: [ { paxes: [ { age: 30 }, { age: 30 } ] } ], hotels: [ \"' . $search->hotel . '\" ], currency: \"EUR\", markets: [ \"ES\" ], language: \"es\", nationality: \"ES\" }, settings: { client: \"it2b\", context: \"' . $search->channelCode . '\", timeout: 25000 }, filterSearch: { access: { includes: [ \"' . $search->access . '\" ] } }) { context errors { code type description } warnings { code type description } options { id accessCode supplierCode hotelCode hotelName boardCode paymentType status occupancies { id paxes { age } } rooms { occupancyRefId code description refundable roomPrice { price { currency binding net gross exchange { currency rate } } breakdown { start end price { currency binding net gross exchange { currency rate } minimumSellingPrice } } } beds { type count } ratePlans { start end code name } } price { currency binding net gross exchange { currency rate } minimumSellingPrice markups { channel currency binding net gross exchange { currency rate } rules { id name type value } } } supplements { start end code name description supplementType chargeType mandatory durationType quantity unit resort { code name description } price { currency binding net gross exchange { currency rate } } } surcharges { code chargeType description mandatory price { currency binding net gross exchange { currency rate } markups { channel currency binding net gross exchange { currency rate } } } } rateRules cancelPolicy { refundable cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } remarks } }\t}}"
            //   }',
            // CURLOPT_POSTFIELDS => '{
            //     "query": "query {\thotelX { search( criteria: { checkIn: \"' . $checkIn->format('Y-m-d') . '\", checkOut: \"' . $checkOut->format('Y-m-d') . '\", occupancies: '. $output .', hotels: '. str_replace('"', '\"', $hotels) .', currency: \"EUR\", markets: [ \"ES\" ], language: \"es\", nationality: \"ES\" }, settings: { client: \"client_demo\", context: \"' . $search->channelCode . '\",testMode: true, timeout: 25000 }, filterSearch: { access: { includes: [ \"' . $search->access . '\" ] } }) { context errors { code type description } warnings { code type description } options { id accessCode supplierCode hotelCode hotelName boardCode paymentType status occupancies { id paxes { age } } rooms { occupancyRefId code description refundable roomPrice { price { currency binding net gross exchange { currency rate } } breakdown { start end price { currency binding net gross exchange { currency rate } minimumSellingPrice } } } beds { type count } ratePlans { start end code name } } price { currency binding net gross exchange { currency rate } minimumSellingPrice markups { channel currency binding net gross exchange { currency rate } rules { id name type value } } } supplements { start end code name description supplementType chargeType mandatory durationType quantity unit resort { code name description } price { currency binding net gross exchange { currency rate } } } surcharges { code chargeType description mandatory price { currency binding net gross exchange { currency rate } markups { channel currency binding net gross exchange { currency rate } } } } rateRules cancelPolicy { refundable cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } remarks } }\t}}"
            //   }',
            CURLOPT_POSTFIELDS => '{
                "query": "query {\thotelX { search( criteria: { checkIn: \"' . $checkIn->format('Y-m-d') . '\", checkOut: \"' . $checkOut->format('Y-m-d') . '\", occupancies: '. $output .', hotels: '. str_replace('"', '\"', $hotels) .', currency: \"EUR\", markets: [ \"ES\" ], language: \"es\", nationality: \"ES\" }, settings: { client: \"it2b\", context: \"' . $search->channelCode . '\",testMode: false, timeout: 25000 }, filterSearch: { access: { includes: [ \"' . $search->access . '\" ] } }) { context errors { code type description } warnings { code type description } options { id accessCode supplierCode hotelCode hotelName boardCode paymentType status occupancies { id paxes { age } } rooms { occupancyRefId code description refundable roomPrice { price { currency binding net gross exchange { currency rate } } breakdown { start end price { currency binding net gross exchange { currency rate } minimumSellingPrice } } } beds { type count } ratePlans { start end code name } } price { currency binding net gross exchange { currency rate } minimumSellingPrice markups { channel currency binding net gross exchange { currency rate } rules { id name type value } } } supplements { start end code name description supplementType chargeType mandatory durationType quantity unit resort { code name description } price { currency binding net gross exchange { currency rate } } } surcharges { code chargeType description mandatory price { currency binding net gross exchange { currency rate } markups { channel currency binding net gross exchange { currency rate } } } } rateRules cancelPolicy { refundable cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } remarks } }\t}}"
              }',
            //   Apikey 4794442a-a4dc-4660-5083-64360879e063'
            // Apikey test0000-0000-0000-0000-000000000000
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

    #[Route('/integration_quote_hotels', name: 'app_integration_quote_hotels')]
    public function integrationPrebookingHotels(Request $request): Response
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
            "query": "query {\\thotelX { quote(criteria: { optionRefId: \\"' . $dataDecode->id . '\\" }, settings: { client: \\"it2b\\", context: \\"' . $dataDecode->channelCode . '\\", timeout: 5000, testMode: false }) { errors { code type description } warnings { code type description } optionQuote { optionRefId status price { currency binding net gross exchange { currency rate } minimumSellingPrice } surcharges { chargeType price { currency binding net gross exchange { currency rate } minimumSellingPrice } description } cancelPolicy { refundable description cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } paymentType cardTypes remarks } }\\t}}"
            }',
            // CURLOPT_POSTFIELDS => '{
            //     "query": "query {\\thotelX { quote(criteria: { optionRefId: \\"' . $dataDecode->id . '\\" }, settings: { client: \\"client_demo\\", context: \\"' . $dataDecode->channelCode . '\\", timeout: 5000, testMode: true }) { errors { code type description } warnings { code type description } optionQuote { optionRefId status price { currency binding net gross exchange { currency rate } minimumSellingPrice } surcharges { chargeType price { currency binding net gross exchange { currency rate } minimumSellingPrice } description } cancelPolicy { refundable description cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } paymentType cardTypes remarks } }\\t}}"
            // }',
            // client_demo
            // test0000-0000-0000-0000-000000000000
            CURLOPT_HTTPHEADER => array(
                'Authorization: Apikey 4794442a-a4dc-4660-5083-64360879e063',
                // 'Authorization: Apikey test0000-0000-0000-0000-000000000000',
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

    #[Route('/hotel_integration_prebooking', name: 'api_hotel_integration_prebooking')]
    public function prebooking(Request $request, EntityManagerInterface $entityManager, BookingRepository $bookingRepository, HotelRepository $hotelRepository, MailerInterface $mailer, ConfigurationRepository $configurationRepository, VoucherRepository $voucherRepository, Pdf $pdf): Response
    {
        $requestDecode = json_decode($request->getContent());

        try {
            $formattedRooms = [];
            $hotelAvailabilities = [];
            $hotel = $hotelRepository->find($requestDecode->hotel);

            foreach ($requestDecode->data[0]->roomSelected->rooms as $index => $room) {
                $formattedRoom = [
                    'clientTypes' => [],
                    'pensionType' => [
                        'name' => $requestDecode->data[0]->rooms[0]->pensionType->title,
                        'code' => $requestDecode->data[0]->rooms[0]->pensionType->code
                        // 'id' => $room->pensionType->id
                    ],
                    'cancellationType' => [
                        'date' => $requestDecode->data[0]->roomSelected->cancelPolicy->cancelPenalties[0]->deadline,
                        'penaltyType' => $requestDecode->data[0]->roomSelected->cancelPolicy->cancelPenalties[0]->penaltyType,
                        'import' => $requestDecode->data[0]->roomSelected->cancelPolicy->cancelPenalties[0]->value
                        // 'id' => $room->pensionType->cancellationType->id
                    ],
                    'roomType' => [
                        'name' => $room->description,
                        'code' => $room->code,
                        // 'id' => $room->roomType->id
                    ],
                    'nights' => $requestDecode->data[0]->rooms[0]->nights,
                    // 'supplementMinNight' => [
                    //     'number' => $room->supplementMinNight->number,
                    //     'supplement' => $room->supplementMinNight->supplement,
                    //     'supplementType' => $room->supplementMinNight->supplementType,
                    // ],
                    'totalPrice' => $room->roomPrice->price->gross,
                    'totalPriceCost' => $room->roomPrice->price->net,
                    'clientName' => $requestDecode->data[0]->rooms[$index]->clientName,
                    // 'availabilities' => $room->roomType->availabilities               
                ];

                if (isset($requestDecode->data[0]->refId)) {
                    $formattedRoom['refId'] = $requestDecode->data[0]->refId;
                }

                foreach ($requestDecode->data[0]->rooms[$index]->clientTypes as $key => $value) {
                    $formattedRoom['clientTypes'][$key] = [
                        'quantity' => $value->quantity,
                        // 'price' => $value->price,
                        // 'priceCost' => $value->priceCost,
                        // 'discount' => $value->discount,
                        // 'discountCost' => $value->discountCost,
                        'clientType' => $value->clientType,
                        'ages' => $value->ages,
                        // 'supplement' => $value->supplement,
                        // 'supplementCost' => $value->supplementCost
                    ];

                    // if (isset($value->supplementIndividual)) {
                    //     $formattedRoom['clientTypes'][$key]['supplementIndividual'] = $value->supplementIndividual;        
                    // }

                    // if (isset($value->discountNumber)) {
                    //     $discountNumbers = [];
                    //     foreach ($value->discountNumber as $discountNumber) {
                    //         $currentDiscountNumber = [
                    //             'active' => true,
                    //             'number' => $discountNumber->number,  
                    //             'percentage' => $discountNumber->percentage
                    //         ];
                    //         array_push($discountNumbers, $currentDiscountNumber);
                    //     }
                    //     $formattedRoom['clientTypes'][$key]['discountNumber'] = $discountNumbers;
                    // }

                    // if (isset($value->ranges)) {
                    //     $formattedRoom['clientTypes'][$key]['ranges'] = [];
                    //     foreach ($value->ranges as $key2 => $value) {
                    //         $formattedRoom['clientTypes'][$key]['ranges'][$key2] = [
                    //             'quantity' => $value->quantity,
                    //             'discountNumber' => []
                    //         ];

                    //         $discountNumbers = [];
                    //         foreach ($value->discountNumber as $discountNumber) {
                    //             $currentDiscountNumber = [
                    //                 'active' => $discountNumber->active,
                    //                 'number' => $discountNumber->number,  
                    //                 'percentage' => $discountNumber->percentage
                    //             ];
                    //             array_push($discountNumbers, $currentDiscountNumber);
                    //         }
                    //         $formattedRoom['clientTypes'][$key]['ranges'][$key2]['discountNumber'] = $discountNumbers;
                    //     }
                    // }
                }
                array_push($formattedRooms, $formattedRoom);
                // foreach ($room->roomType->availabilities as $availability) {
                //     $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                //     array_push($hotelAvailabilities, $hotelAvailability);
                //     if (!$hotel->isIsOnRequest()) {
                //         if ($hotelAvailability->quota > 0) {
                //             $hotelAvailability->setQuota($hotelAvailability->getQuota() - 1);
                //             $hotelAvailability->setTotalBookings($hotelAvailability->getTotalBookings() + 1);
                //             $entityManager->persist($hotelAvailability);
                //         } else {
                //             throw new BadRequestHttpException('No hay disponibilidad para las fechas seleccionadas');
                //         }
                //     }
                // }
            }

            $hotelBooking = new Booking();
            $hotelBookingLine = new BookingLine();

            $hotelBooking->setEmail($requestDecode->email);
            $hotelBooking->setHasAcceptance($requestDecode->hasAcceptance);
            $hotelBooking->setName($requestDecode->name);
            $hotelBooking->setObservations($requestDecode->observations);
            $hotelBooking->setPaymentMethod($requestDecode->paymentMethod);
            $hotelBooking->setPhone($requestDecode->phone);
            $hotelBooking->setPromoCode($requestDecode->promoCode);
            if ($requestDecode->paymentMethod == 'R' && !$hotel->isIsOnRequest()) {
                $hotelBooking->setStatus('booked');
            } else {
                $hotelBooking->setStatus($hotel->isIsOnRequest() ? 'onRequest' : 'preBooked');
            }
            $hotelBooking->setPaymentStatus('pending');
            $hotelBooking->setTotalPrice($requestDecode->totalPrice);
            $hotelBooking->setTotalPriceCost($requestDecode->totalPriceCost);
            $entityManager->persist($hotelBooking);

            $hotelBookingLine->setCheckIn(new \DateTime($requestDecode->checkIn));
            $hotelBookingLine->setCheckOut(new \DateTime($requestDecode->checkOut));
            $hotelBookingLine->setData($formattedRooms);
            $hotelBookingLine->setTotalPrice($requestDecode->totalPrice);
            $hotelBookingLine->setTotalPriceCost($requestDecode->totalPriceCost);
            $hotelBookingLine->setHotel($hotel);
            // $hotelBookingLine->setBooking($hotelBooking);
            $entityManager->persist($hotelBookingLine);
            $hotelBooking->addBookingLine($hotelBookingLine);
            $entityManager->flush();

            if ($requestDecode->paymentMethod == 'R' && !$hotel->isIsOnRequest()) {
                $newVoucher = new Voucher();
                $newVoucher->setToBePaidBy('A-MARILUZ TRAVEL TOUR S.L.');
                $newVoucher->setBooking($hotelBooking);

                $entityManager->persist($newVoucher);
                $entityManager->flush();

                $this->send_voucher($hotelBooking->getId(), 'supplier', $mailer, $pdf, $voucherRepository, $configurationRepository, $entityManager);
                $this->sendTransfer($hotelBooking->getId(), $mailer, $bookingRepository, $configurationRepository);
            }

            // foreach ($hotelAvailabilities as $hotelAvailability) {
            //     $hotelAvailability->addHotelBooking($hotelBooking);
            //     $entityManager->persist($hotelAvailability);
            // }


            return $this->json([
                'response'  => $hotelBooking
            ]);
        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }

    #[Route('/integration_hotel_booking', name: 'app_integration_hotel_booking')]
    public function integrationHotelBooking(Request $request, BookingRepository $bookingRepository, HotelAvailabilityRepository $hotelAvailabilityRepository, EntityManagerInterface $entityManager, MailerInterface $mailer, Pdf $pdf, VoucherRepository $voucherRepository, ConfigurationRepository $configurationRepository,): Response
    {
        // return $this->json([
        //     'response'  => true
        // ]);
        // TGX Book
        try {

            $request = file_get_contents('php://input');
            parse_str($request, $output);
            $requestData = str_replace("?", "", utf8_decode(base64_decode($output['Ds_MerchantParameters'])));
            $requestData = json_decode($requestData, true);
            // $hotelBooking = $bookingRepository->find(413);
            $hotelBooking = $bookingRepository->find(intval(ltrim($requestData['Ds_Order'], "0")));

            $output = "[";
            foreach ($hotelBooking->getBookingLines()[0]->getData() as $index => $room) {
                $currentRoom = $index+1;
                $output .= "{ occupancyRefId: $currentRoom, paxes: [";
                foreach ($room['clientTypes'] as $key => $value) {
                    if ($key == 'adults') {
                        for ($i=0; $i < $value['quantity']; $i++) { 
                            $output .= '{ name: \"Mariluz\", surname: \"Travel\", age: 30 }';
                            if ($i < $value['quantity'] - 1) {
                                $output .= ", ";
                            }
                        }
                    } else {
                        for ($i=0; $i < $value['quantity']; $i++) { 
                            $age = $value['ages'][$i];
                            $output .= ', {name: \"Mariluz\", surname: \"Travel\", age: '.$age.' }';
                            if ($i < $value['quantity'] - 1) {
                                $output .= ", ";
                            }
                        }
                    }
                }
                $output .= "] }";
            }

            $output .= "]";

            if ($requestData['Ds_Response'] < 100) {
                $hotelBooking->setPaymentStatus('paid');
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
                    // deltaPrice: {amount: 10, percent: 10, applyBoth: true}
                    CURLOPT_POSTFIELDS =>  '{
                        "query": "mutation { hotelX { book( input: {optionRefId: \"' . $hotelBooking->getBookingLines()[0]->getData()[0]['refId'] . '\", clientReference: \"'. $hotelBooking->getId() .'\", deltaPrice: {amount: '. $hotelBooking->getTotalPrice() .', applyBoth: false}, holder: {name: \"'. $hotelBooking->getName() .'\", surname: \"'.$hotelBooking->getName().'\"}, remarks: \"'. $hotelBooking->getObservations() .'\", rooms: '. $output .'} settings: {client: \"client_demo\", auditTransactions: true, context: \"'. $hotelBooking->getBookingLines()[0]->getHotel()->getChannelHotels()[0]->getChannel()->getCode() .'\", testMode: true, timeout: 60000} ) { errors { code type description } warnings { code type description } booking { status price { currency binding net gross exchange { currency rate } } reference { bookingID client supplier hotel } holder { name surname } cancelPolicy { refundable cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } remarks hotel { hotelCode hotelName bookingDate start end boardCode occupancies { id paxes { age } } rooms { code description occupancyRefId price { currency binding net gross exchange { currency rate } } } } } } }}"
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Apikey test0000-0000-0000-0000-000000000000',
                        'TGX-Content-Type: graphqlx/json',
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $response = json_decode($response, true);

                if ($response['data']['hotelX']['book']['booking']['status'] == 'OK') {
                    $hotelBooking->setStatus('booked');
                } else {
                    if (count($response['data']['hotelX']['book']['errors']) > 0) {
                        $hotelBooking->setStatus('iError-'.$response['data']['hotelX']['book']['errors'][0]['code'].'|'.$response['data']['hotelX']['book']['errors'][0]['type']);
                    } else {
                        $hotelBooking->setStatus('integration-'.$response['data']['hotelX']['book']['booking']['status']);
                    }
                }
            }
            else {
                $hotelBooking->setStatus('error');
                $hotelBooking->setPaymentStatus($requestData['Ds_Response']);
            }

            // Create system Booking

            // $bookingController = new BookingController();

            // $bookingHub->setLocator($bookingOfi->BookingResult->BookingCode);
            // if ($requestData['Ds_Response'] < 100) {
                // $hotelBooking->setStatus('booked');
                // $hotelBooking->setPaymentStatus('paid');
            // } else {
            //     $hotelBooking->setStatus('error');
            //     $hotelBooking->setPaymentStatus($requestData['Ds_Response']);
            // }

            $entityManager->persist($hotelBooking);
            $entityManager->flush();

            // Generate a Voucher based on the booking data

            if ($hotelBooking->getStatus() == 'booked') {
                $newVoucher = new Voucher();
                $newVoucher->setToBePaidBy('H-MARILUZ TRAVEL TOUR S.L.');
                $newVoucher->setBooking($hotelBooking);

                $entityManager->persist($newVoucher);
                $entityManager->flush();

                // $bookingController->send_voucher($newVoucher->getId(), 'all', $mailer, $pdf, $voucherRepository, $configurationRepository, $entityManager);
            }


            return $this->json([
                'response'  => $response
            ]);
        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }

    #[Route('/integration_availabilities', name: 'app_integration_availabilities')]
    public function integrationAvailabilities(Request $request): Response
    {
        $search = json_decode($request->getContent());

        $checkIn = strtotime($search->checkIn);
        $checkIn =  \DateTime::createFromFormat('U', (strtotime('+1 day', $checkIn)));
        $checkOut = strtotime($search->checkOut);
        $checkOut =  \DateTime::createFromFormat('U', ((strtotime('+1 day', $checkOut))));
        $hotelsIds = [];
        foreach ($search->integrationHotels as $key => $value) {
            $hotels = json_encode($value->code);
            $output = "[";
        // foreach ($search->rooms as $index => $room) {
            $output .= "{ paxes: [";
            // foreach ($room->clientTypes as $key => $value) {
                // if ($key == 'adults') {
                    for ($i=0; $i < $search->adults; $i++) { 
                        $output .= "{ age: 30 }";
                        if ($i < $search->adults - 1) {
                            $output .= ", ";
                        }
                    }
                // } else {
                    for ($i=0; $i < $search->kids; $i++) { 
                        $age = $value->ages[$i];
                        // $output .= ", { age: $age }";
                        $output .= ", { age: 7 }";
                        if ($i < $search->kids - 1) {
                            $output .= ", ";
                        }
                    }
                // }
            // }
            $output .= "] }";
            // }

            $output .= "]";

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
                // CURLOPT_POSTFIELDS => '{
                //     "query": "query {\thotelX { search( criteria: { checkIn: \"' . $checkIn->format('Y-m-d') . '\", checkOut: \"' . $checkOut->format('Y-m-d') . '\", occupancies: '. $output .', hotels: '. str_replace('"', '\"', $hotels) .', currency: \"EUR\", markets: [ \"ES\" ], language: \"es\", nationality: \"ES\" }, settings: { client: \"client_demo\", context: \"' . $key . '\",testMode: true, timeout: 25000 }, filterSearch: { access: { includes: [ \"' . $value->access . '\" ] } }) { context errors { code type description } warnings { code type description } options { id accessCode supplierCode hotelCode hotelName boardCode paymentType status occupancies { id paxes { age } } rooms { occupancyRefId code description refundable roomPrice { price { currency binding net gross exchange { currency rate } } breakdown { start end price { currency binding net gross exchange { currency rate } minimumSellingPrice } } } beds { type count } ratePlans { start end code name } } price { currency binding net gross exchange { currency rate } minimumSellingPrice markups { channel currency binding net gross exchange { currency rate } rules { id name type value } } } supplements { start end code name description supplementType chargeType mandatory durationType quantity unit resort { code name description } price { currency binding net gross exchange { currency rate } } } surcharges { code chargeType description mandatory price { currency binding net gross exchange { currency rate } markups { channel currency binding net gross exchange { currency rate } } } } rateRules cancelPolicy { refundable cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } remarks } }\t}}"
                // }',
                CURLOPT_POSTFIELDS => '{
                    "query": "query {\thotelX { search( criteria: { checkIn: \"' . $checkIn->format('Y-m-d') . '\", checkOut: \"' . $checkOut->format('Y-m-d') . '\", occupancies: '. $output .', hotels: '. str_replace('"', '\"', $hotels) .', currency: \"EUR\", markets: [ \"ES\" ], language: \"es\", nationality: \"ES\" }, settings: { client: \"it2b\", context: \"' . $key . '\",testMode: false, timeout: 25000 }, filterSearch: { access: { includes: [ \"' . $value->access . '\" ] } }) { context errors { code type description } warnings { code type description } options { id accessCode supplierCode hotelCode hotelName boardCode paymentType status occupancies { id paxes { age } } rooms { occupancyRefId code description refundable roomPrice { price { currency binding net gross exchange { currency rate } } breakdown { start end price { currency binding net gross exchange { currency rate } minimumSellingPrice } } } beds { type count } ratePlans { start end code name } } price { currency binding net gross exchange { currency rate } minimumSellingPrice markups { channel currency binding net gross exchange { currency rate } rules { id name type value } } } supplements { start end code name description supplementType chargeType mandatory durationType quantity unit resort { code name description } price { currency binding net gross exchange { currency rate } } } surcharges { code chargeType description mandatory price { currency binding net gross exchange { currency rate } markups { channel currency binding net gross exchange { currency rate } } } } rateRules cancelPolicy { refundable cancelPenalties { deadline isCalculatedDeadline penaltyType currency value } } remarks } }\t}}"
                }',
                //   Apikey 4794442a-a4dc-4660-5083-64360879e063'
                // test0000-0000-0000-0000-000000000000
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
            if ($response['data']['hotelX']['search']['options']) {
                foreach ($response['data']['hotelX']['search']['options'] as $availability) {
                    if (!in_array($availability['hotelCode'], $hotelsIds)) {
                        array_push($hotelsIds, $availability['hotelCode']);
                    }
                };
            }
        }

        return $this->json([
            'hotels'  => $hotelsIds
        ]);
    }
}
