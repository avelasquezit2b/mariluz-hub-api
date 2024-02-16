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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


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
            $hotelBooking = $hotelBookingRepository->find($requestData->id);

            // $bookingHub->setLocator($bookingOfi->BookingResult->BookingCode);
            if ($requestData['Ds_Response'] < 100) {
                $hotelBooking->setStatus('booked');
            } else {
                $hotelBooking->setStatus('paymentError - ' . $requestData['Ds_Response']);
            }

            $entityManager->persist($hotelBooking);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from('adriarias@it2b.es')
                ->to('adriarias@it2b.es')
                ->subject('Gracias por tu reserva')
                ->context([
                    "name" => $hotelBooking->getName(),
                    "bookingEmail" => $hotelBooking->getEmail(),
                    "phone" => $hotelBooking->getPhone(),
                    "id" => $hotelBooking->getId(),
                    "product" => $hotelBooking->getHotel(),
                    "rooms" => $hotelBooking->getRooms(),
                    "totalPrice" => $hotelBooking->getTotalPrice(),
                    "startDate" => date_format($hotelBooking->getCheckIn(),"d/m/Y"),
                    "endDate" => date_format($hotelBooking->getCheckOut(),"d/m/Y"),
                    "paymentMethod" => $hotelBooking->getPaymentMethod(),
                    "date" => date("d-m-Y"),
                ])
                ->htmlTemplate('email/thank_you.html.twig');

            if ($hotelBooking->getStatus() == 'booked') {
                $mailer->send($email);
            }

            return $this->json([
                'response'  => $email
            ]);


        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }
}
