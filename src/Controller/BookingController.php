<?php

namespace App\Controller;

use App\Entity\HotelBooking;
use App\Repository\HotelRepository;
use App\Repository\HotelAvailabilityRepository;
use App\Repository\HotelBookingRepository;
use App\Entity\ActivityBooking;
use App\Entity\Bill;
use App\Repository\ActivityRepository;
use App\Repository\ActivityAvailabilityRepository;
use App\Repository\ActivityBookingRepository;
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
            $hotelBooking = $hotelBookingRepository->find($requestData->id);

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

            // TO - DO Generate bill after creating the booking

            $bill = new Bill();
            $bill->setTotalPrice($requestData->totalPrice);
            // $bill->setClient();
            // $bill->setAditionalDescription();
            // $bill->setPricePayed();
            // $bill->setAccountingCode();

            // $bill->setHasAcceptance($requestDecode->hasAcceptance);
            // $activity = $activityRepository->find($requestDecode->activity);
            // $bill->setActivity($activity);
            // $bill->setName($requestDecode->name);
            // $bill->setObservations($requestDecode->observations);
            // $bill->setPaymentMethod($requestDecode->paymentMethod);
            // $bill->setPhone($requestDecode->phone);
            // $bill->setPromoCode($requestDecode->promoCode);
            // $bill->setData($requestDecode->data);
            // $bill->setStatus('preBooked');
            // $bill->setTotalPrice($requestDecode->totalPrice);

            // foreach ($activityAvailabilities as $activityAvailability) {
            //     $activityAvailability->addActivityBooking($activityBooking);
            //     $entityManager->persist($activityAvailability);
            // }

            $entityManager->persist($bill);
            $entityManager->flush();

            //

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

    #[Route('/activity_prebooking', name: 'api_activity_prebooking')]
    public function activity_prebooking(Request $request, EntityManagerInterface $entityManager, ActivityBookingRepository $activityBookingRepository, ActivityAvailabilityRepository $activityAvailabilityRepository, ActivityRepository $activityRepository): Response
    {
        $requestDecode = json_decode($request->getContent());

        try {
            $formattedRooms = [];
            $activityAvailabilities = [];
            foreach ($requestDecode->data as $data) {
                // $formattedRoom = [
                //     'adults' => $room->adults,
                //     'kids' => $room->kids,
                //     'babies' => $room->babies,
                //     'pensionType' => $room->pensionType->pensionType->title,
                //     'cancellationType' => $room->pensionType->cancellationType->title,
                //     'pensionTypePriceId' => $room->pensionType->id,
                //     'price' => $room->pensionType->price,
                //     'availabilities' => $room->roomType->availabilities,
                //     'roomType' => $room->roomType->roomCondition->roomType->title
                // ];
                // array_push($formattedRooms, $formattedRoom);
                // foreach ($room->roomType->availabilities as $availability) {
                    $activityAvailability = $activityAvailabilityRepository->find($data->availableSchedule->id);
                    array_push($activityAvailabilities, $activityAvailability);
                    if ($activityAvailability->quota <= $activityAvailability->getMaxQuota()) {
                        $activityAvailability->setQuota($activityAvailability->getQuota()+1);
                        $entityManager->persist($activityAvailability);
                    } else {
                        throw new BadRequestHttpException('No hay disponibilidad para las fechas seleccionadas'); 
                    }
                // }
            }

            $activityBooking = new ActivityBooking();
            $activityBooking->setCheckIn(new \DateTime($requestDecode->checkIn));
            $activityBooking->setCheckOut(new \DateTime($requestDecode->checkOut));
            $activityBooking->setEmail($requestDecode->email);
            $activityBooking->setHasAcceptance($requestDecode->hasAcceptance);
            $activity = $activityRepository->find($requestDecode->activity);
            $activityBooking->setActivity($activity);
            $activityBooking->setName($requestDecode->name);
            $activityBooking->setObservations($requestDecode->observations);
            $activityBooking->setPaymentMethod($requestDecode->paymentMethod);
            $activityBooking->setPhone($requestDecode->phone);
            $activityBooking->setPromoCode($requestDecode->promoCode);
            $activityBooking->setData($requestDecode->data);
            $activityBooking->setStatus('preBooked');
            $activityBooking->setTotalPrice($requestDecode->totalPrice);

            foreach ($activityAvailabilities as $activityAvailability) {
                $activityAvailability->addActivityBooking($activityBooking);
                $entityManager->persist($activityAvailability);
            }

            $entityManager->persist($activityBooking);
            $entityManager->flush();

            return $this->json([
                'response'  => $activityBooking
            ]);


        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }

    #[Route('/activity_booking', name: 'api_activity_booking')]
    public function activity_booking(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, ActivityBookingRepository $activityBookingRepository, ActivityAvailabilityRepository $activityAvailabilityRepository, ActivityRepository $activityRepository): Response
    {
        $request = file_get_contents('php://input');
        parse_str($request, $output);

        try {
            $requestData = str_replace("?", "", utf8_decode(base64_decode($output['Ds_MerchantParameters'])));
            $requestData = json_decode($requestData, true);
            $activityBooking = $activityBookingRepository->find($requestData->id);

            // $bookingHub->setLocator($bookingOfi->BookingResult->BookingCode);
            if ($requestData['Ds_Response'] < 100) {
                $activityBooking->setStatus('booked');
            } else {
                $activityBooking->setStatus('paymentError - ' . $requestData['Ds_Response']);
                foreach ($activityBooking->getData() as $data) {
                    foreach ($data['availabilities'] as $availability) {
                        $activityAvailability = $activityAvailabilityRepository->find($availability);
                        $activityAvailability->setQuota($activityAvailability->getQuota()-1);
                        $activityAvailability->removeActivityBooking($activityBooking);
                        $entityManager->persist($activityAvailability);
                    }
                }
            }

            $entityManager->persist($activityBooking);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from('adriarias@it2b.es')
                ->to('adriarias@it2b.es')
                ->subject('Gracias por tu reserva')
                ->context([
                    "name" => $activityBooking->getName(),
                    "bookingEmail" => $activityBooking->getEmail(),
                    "phone" => $activityBooking->getPhone(),
                    "id" => $activityBooking->getId(),
                    "product" => $activityBooking->getActivity(),
                    "rooms" => $activityBooking->getData(),
                    "totalPrice" => $activityBooking->getTotalPrice(),
                    "startDate" => date_format($activityBooking->getCheckIn(),"d/m/Y"),
                    "endDate" => date_format($activityBooking->getCheckOut(),"d/m/Y"),
                    "paymentMethod" => $activityBooking->getPaymentMethod(),
                    "date" => date("d-m-Y"),
                ])
                ->htmlTemplate('email/thank_you.html.twig');

            if ($activityBooking->getStatus() == 'booked') {
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
