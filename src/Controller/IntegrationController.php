<?php

namespace App\Controller;

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
  "query": "query {\\thotelX { hotels(criteria: {access: \\"2\\",maxSize: 25}, token: \\"\\") { token count edges { node { createdAt updatedAt hotelData { hotelCode hotelName categoryCode chainCode location { address zipCode city country coordinates { latitude longitude } closestDestination { code available texts { text language } type parent } } contact { email telephone fax web } propertyType { propertyCode name } descriptions { type texts { language text } } medias { code url } rooms { edges { node { code roomData { code roomCode allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } allAmenities { edges { node { amenityData { code amenityCode } } } } } } } } } }"
}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Apikey 4794442a-a4dc-4660-5083-64360879e063',
                'TGX-Content-Type: graphqlx/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $this->json([
            'response'  => $response
        ]);
    }
}
