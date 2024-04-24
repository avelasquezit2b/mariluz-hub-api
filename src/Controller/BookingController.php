<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\BookingLine;
use App\Repository\HotelRepository;
use App\Repository\HotelAvailabilityRepository;
use App\Repository\BookingRepository;
use App\Entity\Bill;
use App\Entity\Voucher;
use App\Repository\ActivityRepository;
use App\Repository\ActivityAvailabilityRepository;
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
use App\Controller\DocumentController;
use App\Repository\ConfigurationRepository;
use App\Repository\VoucherRepository;
use Knp\Snappy\Pdf;
use phpDocumentor\Reflection\Types\Integer;

class BookingController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/hotel_prebooking', name: 'api_hotel_prebooking')]
    public function prebooking(Request $request, EntityManagerInterface $entityManager, BookingRepository $bookingRepository, HotelAvailabilityRepository $hotelAvailabilityRepository, HotelRepository $hotelRepository): Response
    {
        $requestDecode = json_decode($request->getContent());

        try {
            $formattedRooms = [];
            $hotelAvailabilities = [];
            foreach ($requestDecode->data as $room) {
                $formattedRoom = [
                    'clientTypes' => [],
                    'pensionType' => [
                        'name' => $room->pensionType->pensionType->title,
                        'code' => $room->pensionType->pensionType->code,
                        'price' => $room->pensionType->price,
                        // 'id' => $room->pensionType->id
                    ],
                    'cancellationType' => [
                        'name' => $room->pensionType->cancellationType->title,
                        'code' => $room->pensionType->cancellationType->code,
                        // 'id' => $room->pensionType->cancellationType->id
                    ],
                    'roomType' => [
                        'name' => $room->roomType->roomCondition->roomType->title,
                        'code' => $room->roomType->roomCondition->roomType->maxAdultsCapacity . '+' . $room->roomType->roomCondition->roomType->maxKidsCapacity,
                        // 'id' => $room->roomType->id
                    ],
                    'availabilities' => $room->roomType->availabilities
                ];

                foreach ($room->clientTypes as $key => $value) {
                    $formattedRoom['clientTypes'][$key] = [
                        'quantity' => $value->quantity,
                        'price' => $value->price,
                        'discount' => $value->discount,
                        'clientType' => $value->clientType
                    ];
                }
                array_push($formattedRooms, $formattedRoom);
                foreach ($room->roomType->availabilities as $availability) {
                    $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                    array_push($hotelAvailabilities, $hotelAvailability);
                    if ($hotelAvailability->quota > 0) {
                        $hotelAvailability->setQuota($hotelAvailability->getQuota() - 1);
                        $hotelAvailability->setTotalBookings($hotelAvailability->getTotalBookings() + 1);
                        $entityManager->persist($hotelAvailability);
                    } else {
                        throw new BadRequestHttpException('No hay disponibilidad para las fechas seleccionadas');
                    }
                }
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
            $hotelBooking->setStatus('preBooked');
            $hotelBooking->setTotalPrice($requestDecode->totalPrice);
            $entityManager->persist($hotelBooking);

            $hotelBookingLine->setCheckIn(new \DateTime($requestDecode->checkIn));
            $hotelBookingLine->setCheckOut(new \DateTime($requestDecode->checkOut));
            $hotelBookingLine->setData($formattedRooms);
            $hotelBookingLine->setTotalPrice($requestDecode->totalPrice);
            $hotel = $hotelRepository->find($requestDecode->hotel);
            $hotelBookingLine->setHotel($hotel);
            $hotelBookingLine->setBooking($hotelBooking);
            $entityManager->persist($hotelBookingLine);

            // foreach ($hotelAvailabilities as $hotelAvailability) {
            //     $hotelAvailability->addHotelBooking($hotelBooking);
            //     $entityManager->persist($hotelAvailability);
            // }

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
    public function booking(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, BookingRepository $bookingRepository, HotelAvailabilityRepository $hotelAvailabilityRepository, HotelRepository $hotelRepository, DocumentController $documentController): Response
    {
        $request = file_get_contents('php://input');
        parse_str($request, $output);

        try {
            $requestData = str_replace("?", "", utf8_decode(base64_decode($output['Ds_MerchantParameters'])));
            $requestData = json_decode($requestData, true);
            $hotelBooking = $bookingRepository->find($requestData->id);

            // $bookingHub->setLocator($bookingOfi->BookingResult->BookingCode);
            if ($requestData['Ds_Response'] < 100) {
                $hotelBooking->setStatus('booked');
            } else {
                $hotelBooking->setStatus('paymentError - ' . $requestData['Ds_Response']);
                foreach ($hotelBooking->getRooms() as $room) {
                    foreach ($room['availabilities'] as $availability) {
                        $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                        $hotelAvailability->setQuota($hotelAvailability->getQuota() + 1);
                        $hotelAvailability->setTotalBookings($hotelAvailability->getTotalBookings() - 1);
                        $hotelAvailability->removeHotelBooking($hotelBooking);
                        $entityManager->persist($hotelAvailability);
                    }
                }
            }

            $entityManager->persist($hotelBooking);
            $entityManager->flush();

            // TO - DO Generar un Voucher a partir de los datos de la reserva (recibes los datos del cliente de la reserva)

            $newVoucher = new Voucher();
            $newVoucher->setToBePaidBy('H-MARILUZ TRAVEL TOUR S.L.');
            $newVoucher->setBooking($hotelBooking);

            $entityManager->persist($newVoucher);
            $entityManager->flush();

            //

            $pdf = new Pdf();
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
                    "startDate" => date_format($hotelBooking->getCheckIn(), "d/m/Y"),
                    "endDate" => date_format($hotelBooking->getCheckOut(), "d/m/Y"),
                    "paymentMethod" => $hotelBooking->getPaymentMethod(),
                    "date" => date("d-m-Y"),
                ])
                ->attach($documentController->pdfVoucherAction($pdf))
                ->htmlTemplate('email/hotel_thank_you.html.twig');

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
    public function activity_prebooking(Request $request, EntityManagerInterface $entityManager, BookingRepository $bookingRepository, ActivityAvailabilityRepository $activityAvailabilityRepository, ActivityRepository $activityRepository): Response
    {
        $requestDecode = json_decode($request->getContent());

        try {
            $formattedRooms = [];
            $activityAvailabilities = [];
            foreach ($requestDecode->data as $data) {
                $formattedActivity = [
                    'availability' => $data->availableSchedule->id,
                    'clientTypes' => []
                ];
                $activityAvailability = $activityAvailabilityRepository->find($data->availableSchedule->id);
                array_push($activityAvailabilities, $activityAvailability);

                foreach ($data->clientTypes as $key => $value) {
                    $formattedActivity['clientTypes'][$key] = [
                        'quantity' => $value->quantity,
                        'price' => $value->price,
                        'clientType' => $value->clientType
                    ];
                    if ($value->quantity <= $activityAvailability->getQuota()) {
                        $activityAvailability->setQuota($activityAvailability->getQuota() - $value->quantity);
                        $activityAvailability->setTotalBookings($activityAvailability->getTotalBookings() + $value->quantity);
                        $entityManager->persist($activityAvailability);
                    } else {
                        throw new BadRequestHttpException('No hay disponibilidad para las fechas seleccionadas');
                    }
                }
                // array_push($formattedRooms, $formattedRoom);
                // foreach ($room->roomType->availabilities as $availability) {


                // }
            }

            $activityBooking = new Booking();
            $activityBookingLine = new BookingLine();

            $activityBooking->setEmail($requestDecode->email);
            $activityBooking->setHasAcceptance($requestDecode->hasAcceptance);
            $activityBooking->setName($requestDecode->name);
            $activityBooking->setObservations($requestDecode->observations);
            $activityBooking->setPaymentMethod($requestDecode->paymentMethod);
            $activityBooking->setPhone($requestDecode->phone);
            $activityBooking->setPromoCode($requestDecode->promoCode);
            $activityBooking->setStatus('preBooked');
            $activityBooking->setTotalPrice($requestDecode->totalPrice);
            $entityManager->persist($activityBooking);

            $activityBookingLine->setCheckIn(new \DateTime($requestDecode->checkIn));
            $activityBookingLine->setCheckOut(new \DateTime($requestDecode->checkOut));
            $activityBookingLine->setData($formattedActivity);
            $activityBookingLine->setTotalPrice($requestDecode->totalPrice);
            $activity = $activityRepository->find($requestDecode->activity);
            $activityBookingLine->setActivity($activity);
            $activityBookingLine->setBooking($activityBooking);
            $entityManager->persist($activityBookingLine);

            $entityManager->flush();

            // foreach ($activityAvailabilities as $activityAvailability) {
            //     $activityAvailability->addActivityBooking($activityBooking);
            //     $entityManager->persist($activityAvailability);
            // }

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
    public function activity_booking(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, BookingRepository $bookingRepository, ActivityAvailabilityRepository $activityAvailabilityRepository, VoucherRepository $voucherRepository, Pdf $pdf): Response
    {
        $request = file_get_contents('php://input');
        parse_str($request, $output);

        try {
            $requestData = str_replace("?", "", utf8_decode(base64_decode($output['Ds_MerchantParameters'])));
            $requestData = json_decode($requestData, true);
            $activityBooking = $bookingRepository->find(intval(ltrim($requestData['Ds_Order'], "0")));
            // $activityBooking = $bookingRepository->find(135);

            // $bookingHub->setLocator($bookingOfi->BookingResult->BookingCode);

            if ($requestData['Ds_Response'] < 100) {
                $activityBooking->setStatus('booked');
            } else {
                $activityBooking->setStatus('paymentError - ' . $requestData['Ds_Response']);
                foreach ($activityBooking->getData() as $data) {
                    foreach ($data['availabilities'] as $availability) {
                        $activityAvailability = $activityAvailabilityRepository->find($availability);
                        $activityAvailability->setQuota($activityAvailability->getQuota() + 1);
                        $activityAvailability->setTotalBookings($activityAvailability->getTotalBookings() - 1);
                        $activityAvailability->removeActivityBooking($activityBooking);
                        $entityManager->persist($activityAvailability);
                    }
                }
            }

            $entityManager->persist($activityBooking);
            $entityManager->flush();

            // Generate a Voucher based on the booking data

            $newVoucher = new Voucher();
            $newVoucher->setToBePaidBy('A-MARILUZ TRAVEL TOUR S.L.');
            $newVoucher->setBooking($activityBooking);

            $entityManager->persist($newVoucher);
            $entityManager->flush();

            //

            $this->send_voucher($newVoucher->getId(), $mailer, $pdf, $voucherRepository);

            // $html = $this->renderView('document/voucher.html.twig', [
            //     // Add any data needed for rendering the Twig template
            // ]);

            // $email = (new TemplatedEmail())
            //     ->from('adriarias@it2b.es')
            //     ->to('adriarias@it2b.es')
            //     ->subject('Gracias por tu reserva')
            //     ->context([
            //         "name" => $activityBooking->getName(),
            //         "bookingEmail" => $activityBooking->getEmail(),
            //         "phone" => $activityBooking->getPhone(),
            //         "id" => $activityBooking->getId(),
            //         "product" => $activityBooking->getBookingLines()[0]->getActivity(),
            //         "totalPrice" => $activityBooking->getTotalPrice(),
            //         "paymentMethod" => $activityBooking->getPaymentMethod(),
            //         "date" => date("d-m-Y"),
            //     ])
            //     ->attach($pdf->getOutputFromHtml($html), 'Bono_Actividad.pdf', 'application/pdf') 
            //     ->htmlTemplate('email/activity_thank_you.html.twig');

            // if ($activityBooking->getStatus() == 'booked') {
            //     $mailer->send($email);
            // }

            // return $this->json([
            //     'response'  => $email
            // ]);
        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }

    #[Route('/send_thank_you_email/{id}', name: 'api_send_thank_you_email')]
    public function send_voucher(int $id, MailerInterface $mailer, Pdf $pdf, VoucherRepository $voucherRepository, ConfigurationRepository $configurationRepository): Response
    {
        $voucher = $voucherRepository->find($id);

        $booking = $voucher->getBooking();
        $company = $configurationRepository->find(1);

        // Checking what type of product it is so the email fits accordingly
        
        // HOTEL
        if ($booking->getBookingLines()[0]->getHotel() != null) {

            // Creating the email content

            $context = [
                "name" => $booking->getName(),
                "bookingEmail" => $booking->getEmail(),
                "phone" => $booking->getPhone(),
                "id" => $booking->getId(),
                "product" => $booking->getBookingLines()[0]->getHotel(),
                "totalPrice" => $booking->getTotalPrice(),
                "paymentMethod" => $booking->getPaymentMethod(),
                "date" => date("d-m-Y"),
            ];

            $fileName = 'Bono_Hotel.pdf';

            // Creating the twig for the PDF with the booking data

            $html = $this->renderView('document/voucher.html.twig', [
                'to_be_paid_by' => $voucher->getToBePaidBy(),
                'productTitle' => $voucher->getBooking()->getBookingLines()[0]->get
            ]);

            // Sending the email with the voucher attachment

            $email = (new TemplatedEmail())
                ->from('adriarias@it2b.es')
                ->to('adriarias@it2b.es')
                ->subject('Gracias por tu reserva')
                ->context($context)
                ->attach($pdf->getOutputFromHtml($html), $fileName, 'application/pdf')
                ->htmlTemplate('email/activity_thank_you.html.twig');

            if ($booking->getStatus() == 'booked') {
                $mailer->send($email);
            }

            return $this->json([
                'response'  => $email
            ]);

        // ACTIVITY
        } else if ($booking->getBookingLines()[0]->getActivity() != null) {

            // Creating the email content

            $context = [
                "name" => $booking->getName(),
                "bookingEmail" => $booking->getEmail(),
                "phone" => $booking->getPhone(),
                "id" => $booking->getId(),
                "product" => $booking->getBookingLines()[0]->getActivity(),
                "totalPrice" => $booking->getTotalPrice(),
                "paymentMethod" => $booking->getPaymentMethod(),
                "date" => date("d-m-Y"),
            ];
            $fileName = 'Bono_Actividad.pdf';

            // Creating the twig for the PDF with the booking data

            $html = $this->renderView('document/voucher.html.twig', [
                'to_be_paid_by' => $voucher->getToBePaidBy(),
                'productTitle' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getTitle(),
                'productZone' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getZones()[0]->getName(),
                'productLocation' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getLocation()->getName(),
                'bookingId' => $voucher->getBooking()->getId(),
                'bookingDate' => date("d-m-Y"),
                'clientName' => $voucher->getBooking()->getClient()->getName(),
                'checkIn' => $voucher->getBooking()->getBookingLines()[0]->getCheckIn()->format('d/m/Y'),
                'checkOut' => $voucher->getBooking()->getBookingLines()[0]->getCheckOut()->format('d/m/Y'),
                'companyName' => $company->getTitle(),
                'companyCif' => $company->getCif(),
                'companyAddress' => $company->getTitle(),
                'companyPostalCode' => $company->getPostalCode(),
                'companyCity' => $company->getCity(),
                'companyProvince' => $company->getProvince(),
                'companyCountry' => $company->getCountry(),
                'companyPhone' => $company->getPhone(),
            ]);

            // Sending the email with the voucher attachment

            $email = (new TemplatedEmail())
                ->from('adriarias@it2b.es')
                ->to('adriarias@it2b.es')
                ->subject('Gracias por tu reserva')
                ->context($context)
                ->attach($pdf->getOutputFromHtml($html), $fileName, 'application/pdf')
                ->htmlTemplate('email/activity_thank_you.html.twig');

            if ($booking->getStatus() == 'booked') {
                $mailer->send($email);
            }

            return $this->json([
                'response'  => $email
            ]);
        }
    }
}
