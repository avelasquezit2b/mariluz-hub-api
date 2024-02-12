<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use App\Repository\HotelBookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/hotel_booking', name: 'api_hotel_booking')]
    public function booking(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, HotelBookingRepository $hotelBookingRepository, HotelRepository $hotelRepository): Response
    {
        $request = file_get_contents('php://input');
        parse_str($request, $output);

        try {
            $requestData = str_replace("?", "", utf8_decode(base64_decode($output['Ds_MerchantParameters'])));
            $requestData = json_decode($requestData, true);
            $hotelBooking = $hotelBookingRepository->find(intval(ltrim($requestData['Ds_Order'], "0")));

            // $bookingHub->setLocator($bookingOfi->BookingResult->BookingCode);
            if ($requestData['Ds_Response'] < 100) {
                $hotelBooking->setStatus('booked');
            } else {
                $hotelBooking->setStatus('paymentError - ' . $requestData['Ds_Response']);
            }
   
            $entityManager->persist($hotelBooking);
            $entityManager->flush();

            // $hotel = $hotelBooking->getHotel();

            // $email =
            //     (new TemplatedEmail())
            //     ->from(new Address('web@viajeskontiki.es', 'Viajes Kontiki'))
            //     ->to($bookingHub->getData()['bookingEmail'])
            //     ->bcc('adriarias@it2b.es')
            //     ->subject('Gracias por tu reserva')
            //     ->context([
            //         "bookingName" => $bookingHub->getData()['passengerName-0'] . ' ' . $bookingHub->getData()['passengerSurname-0'],
            //         "bookingLocator" => $bookingHub->getLocator(),
            //         "bookingDate" => $bookingHub->getDate()->format('d-m-Y'),
            //         "bookingImage" => 'https://hub.kontiki.api.aititubi.es/' . $bookingHub->getData()['image'],
            //         "bookingCircuit" => $circuitHub->getName(),
            //         "bookingStart" => $bookingHub->getStartDate()->format('d-m-Y'),
            //         "bookingEnd" => $bookingHub->getEndDate()->format('d-m-Y'),
            //         "bookingPassengersRooms" => $bookingHub->getAdults() . $adultsString . ' en ' . $bookingHub->getData()['rooms'] .  $habitacionesString,
            //         "bookingPrice" => $bookingHub->getPrice(),
            //         "bookingCancellationPolicies" => $bookingHub->getData()['cancellationPolicies'],
            //         // "message" => $request->message
            //     ])
            //     ->htmlTemplate('communications/thank_you.html.twig');

            // if ($bookingHub->getStatus() == 'confirmed') {
            //     $mailer->send($email);
            // }

            return $this->json([
                'response'  => $requestData['Ds_Response']
            ]);
        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }
}
