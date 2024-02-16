<?php

namespace App\Controller;

use App\Entity\HotelBooking;
use App\Repository\HotelRepository;
use App\Repository\HotelAvailabilityRepository;
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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class BookingController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/hotel_prebooking', name: 'api_hotel_prebooking')]
    public function prebooking(Request $request, EntityManagerInterface $entityManager, HotelBookingRepository $hotelBookingRepository, HotelAvailabilityRepository $hotelAvailabilityRepository, HotelRepository $hotelRepository): Response
    {
        $requestDecode = json_decode($request->getContent());

        try {
            $formattedRooms = [];
            $hotelAvailabilities = [];
            foreach ($requestDecode->rooms as $room) {
                $formattedRoom = [
                    'adults' => $room->adults,
                    'kids' => $room->kids,
                    'babies' => $room->babies,
                    'pensionType' => $room->pensionType->pensionType->title,
                    'cancellationType' => $room->pensionType->cancellationType->title,
                    'pensionTypePriceId' => $room->pensionType->id,
                    'price' => $room->pensionType->price,
                    'availabilities' => $room->roomType->availabilities,
                    'roomType' => $room->roomType->roomCondition->roomType->title
                ];
                array_push($formattedRooms, $formattedRoom);
                foreach ($room->roomType->availabilities as $availability) {
                    $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                    array_push($hotelAvailabilities, $hotelAvailability);
                    if ($hotelAvailability->quota > 0) {
                        $hotelAvailability->setQuota($hotelAvailability->getQuota()-1);
                        $entityManager->persist($hotelAvailability);
                    } else {
                        throw new BadRequestHttpException('No hay disponibilidad para las fechas seleccionadas'); 
                    }
                }
            }

            $hotelBooking = new HotelBooking();
            $hotelBooking->setCheckIn(new \DateTime($requestDecode->checkIn));
            $hotelBooking->setCheckOut(new \DateTime($requestDecode->checkOut));
            $hotelBooking->setEmail($requestDecode->email);
            $hotelBooking->setHasAcceptance($requestDecode->hasAcceptance);
            $hotel = $hotelRepository->find($requestDecode->hotel);
            $hotelBooking->setHotel($hotel);
            $hotelBooking->setName($requestDecode->name);
            $hotelBooking->setObservations($requestDecode->observations);
            $hotelBooking->setPaymentMethod($requestDecode->paymentMethod);
            $hotelBooking->setPaymentMethod($requestDecode->paymentMethod);
            $hotelBooking->setPhone($requestDecode->phone);
            $hotelBooking->setPromoCode($requestDecode->promoCode);
            $hotelBooking->setRooms($formattedRooms);
            $hotelBooking->setStatus('preBooked');
            $hotelBooking->setTotalPrice($requestDecode->totalPrice);

            foreach ($hotelAvailabilities as $hotelAvailability) {
                $hotelAvailability->addHotelBooking($hotelBooking);
                $entityManager->persist($hotelAvailability);
            }

            $entityManager->persist($hotelBooking);
            $entityManager->flush();

            return $this->json([
                'response'  => $hotelBooking
            ]);


        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }

    #[Route('/hotel_booking', name: 'api_hotel_booking')]
    public function booking(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, HotelBookingRepository $hotelBookingRepository, HotelAvailabilityRepository $hotelAvailabilityRepository, HotelRepository $hotelRepository): Response
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
                foreach ($hotelBooking->getRooms() as $room) {
                    foreach ($room['availabilities'] as $availability) {
                        $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                        $hotelAvailability->setQuota($hotelAvailability->getQuota()+1);
                        $hotelAvailability->removeHotelBooking($hotelBooking);
                        $entityManager->persist($hotelAvailability);
                    }
                }
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
