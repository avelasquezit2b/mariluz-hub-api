<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\BookingLine;
use App\Repository\HotelRepository;
use App\Repository\HotelAvailabilityRepository;
use App\Repository\BookingRepository;
use App\Entity\Voucher;
use App\Repository\ActivityRepository;
use App\Repository\ActivityAvailabilityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Controller\DocumentController;
use App\Repository\ConfigurationRepository;
use App\Repository\VoucherRepository;
use Knp\Snappy\Pdf;

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
            $hotel = $hotelRepository->find($requestDecode->hotel);

            foreach ($requestDecode->data as $room) {
                $formattedRoom = [
                    'clientTypes' => [],
                    'pensionType' => [
                        'name' => $room->pensionType->pensionType->title,
                        'code' => $room->pensionType->pensionType->code,
                        'price' => $room->pensionType->price,
                        'cost' => $room->pensionType->cost
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
                    'nights' => $room->nights,
                    'supplementMinNight' => [
                        'number' => $room->supplementMinNight->number,
                        'supplement' => $room->supplementMinNight->supplement,
                        'supplementType' => $room->supplementMinNight->supplementType,
                    ],
                    'totalPrice' => $room->totalPrice,
                    'totalPriceCost' => $room->totalPriceCost,
                    'clientName' => $room->clientName,
                    'availabilities' => $room->roomType->availabilities
                ];

                foreach ($room->clientTypes as $key => $value) {
                    $formattedRoom['clientTypes'][$key] = [
                        'quantity' => $value->quantity,
                        'price' => $value->price,
                        'priceCost' => $value->priceCost,
                        'discount' => $value->discount,
                        'discountCost' => $value->discountCost,
                        'clientType' => $value->clientType,
                        'ages' => $value->ages,
                        'supplement' => $value->supplement,
                        'supplementCost' => $value->supplementCost
                    ];

                    if (isset($value->supplementIndividual)) {
                        $formattedRoom['clientTypes'][$key]['supplementIndividual'] = $value->supplementIndividual;        
                    }

                    if (isset($value->discountNumber)) {
                        $discountNumbers = [];
                        foreach ($value->discountNumber as $discountNumber) {
                            $currentDiscountNumber = [
                                'active' => true,
                                'number' => $discountNumber->number,  
                                'percentage' => $discountNumber->percentage
                            ];
                            array_push($discountNumbers, $currentDiscountNumber);
                        }
                        $formattedRoom['clientTypes'][$key]['discountNumber'] = $discountNumbers;
                    }

                    if (isset($value->ranges)) {
                        $formattedRoom['clientTypes'][$key]['ranges'] = [];
                        foreach ($value->ranges as $key2 => $value) {
                            $formattedRoom['clientTypes'][$key]['ranges'][$key2] = [
                                'quantity' => $value->quantity,
                                'discountNumber' => []
                            ];

                            $discountNumbers = [];
                            foreach ($value->discountNumber as $discountNumber) {
                                $currentDiscountNumber = [
                                    'active' => $discountNumber->active,
                                    'number' => $discountNumber->number,  
                                    'percentage' => $discountNumber->percentage
                                ];
                                array_push($discountNumbers, $currentDiscountNumber);
                            }
                            $formattedRoom['clientTypes'][$key]['ranges'][$key2]['discountNumber'] = $discountNumbers;
                        }
                    }
                }
                array_push($formattedRooms, $formattedRoom);
                foreach ($room->roomType->availabilities as $availability) {
                    $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                    array_push($hotelAvailabilities, $hotelAvailability);
                    if (!$hotel->isIsOnRequest()) {
                        if ($hotelAvailability->quota > 0) {
                            $hotelAvailability->setQuota($hotelAvailability->getQuota() - 1);
                            $hotelAvailability->setTotalBookings($hotelAvailability->getTotalBookings() + 1);
                            $entityManager->persist($hotelAvailability);
                        } else {
                            throw new BadRequestHttpException('No hay disponibilidad para las fechas seleccionadas');
                        }
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
    public function booking(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, BookingRepository $bookingRepository, HotelAvailabilityRepository $hotelAvailabilityRepository, HotelRepository $hotelRepository, DocumentController $documentController, VoucherRepository $voucherRepository, Pdf $pdf,ConfigurationRepository $configurationRepository): Response
    {
        $request = file_get_contents('php://input');
        parse_str($request, $output);

        try {
            $requestData = str_replace("?", "", utf8_decode(base64_decode($output['Ds_MerchantParameters'])));
            $requestData = json_decode($requestData, true);
            $hotelBooking = $bookingRepository->find(intval(ltrim($requestData['Ds_Order'], "0")));

            // $bookingHub->setLocator($bookingOfi->BookingResult->BookingCode);
            if ($requestData['Ds_Response'] < 100) {
                $hotelBooking->setStatus('booked');
                $hotelBooking->setPaymentStatus('paid');
            } else {
                $hotelBooking->setStatus('error');
                $hotelBooking->setPaymentStatus($requestData['Ds_Response']);
                foreach ($hotelBooking->getBookingLines()[0]->getData() as $room) {
                    foreach ($room['availabilities'] as $availability) {
                        $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                        $hotelAvailability->setQuota($hotelAvailability->getQuota() + 1);
                        $hotelAvailability->setTotalBookings($hotelAvailability->getTotalBookings() - 1);
                        $entityManager->persist($hotelAvailability);
                    }
                }
            }

            $entityManager->persist($hotelBooking);
            $entityManager->flush();

            // Generate a Voucher based on the booking data

            if ($hotelBooking->getStatus() == 'booked') {
                $newVoucher = new Voucher();
                $newVoucher->setToBePaidBy('H-MARILUZ TRAVEL TOUR S.L.');
                $newVoucher->setBooking($hotelBooking);
    
                $entityManager->persist($newVoucher);
                $entityManager->flush();
    
                $this->send_voucher($newVoucher->getId(), 'all', $mailer, $pdf, $voucherRepository, $configurationRepository, $entityManager);
            }
            

            return $this->json([
                'response'  => $hotelBooking
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
            $activity = $activityRepository->find($requestDecode->activity);

            foreach ($requestDecode->data as $data) {
                $formattedActivity = [
                    'availability' => $data->availableSchedule->id,
                    'schedule' => $data->availableSchedule->activitySchedule->startTime,
                    'modality' => $data->availableSchedule->activitySchedule->activitySeason->activityFee->modality->title,
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
                    if (!$activity->isIsOnRequest()) {
                        if ($value->quantity <= $activityAvailability->getQuota()) {
                            $activityAvailability->setQuota($activityAvailability->getQuota() - $value->quantity);
                            $activityAvailability->setTotalBookings($activityAvailability->getTotalBookings() + $value->quantity);
                            $entityManager->persist($activityAvailability);
                        } else {
                            throw new BadRequestHttpException('No hay disponibilidad para las fechas seleccionadas');
                        }
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
            $activityBooking->setStatus($activity->isIsOnRequest() ? 'onRequest' : 'preBooked');
            $activityBooking->setPaymentStatus('pending');
            $activityBooking->setTotalPrice($requestDecode->totalPrice);
            $entityManager->persist($activityBooking);

            $activityBookingLine->setCheckIn(new \DateTime($requestDecode->checkIn));
            $activityBookingLine->setCheckOut(new \DateTime($requestDecode->checkOut));
            $activityBookingLine->setData($formattedActivity);
            $activityBookingLine->setTotalPrice($requestDecode->totalPrice);
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
    public function activity_booking(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, BookingRepository $bookingRepository, ActivityAvailabilityRepository $activityAvailabilityRepository, VoucherRepository $voucherRepository, Pdf $pdf, ConfigurationRepository $configurationRepository): Response
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
                $activityBooking->setPaymentStatus('paid');
            } else {
                $activityBooking->setStatus('error');
                $activityBooking->setPaymentStatus($requestData['Ds_Response']);
                foreach ($activityBooking->getData() as $data) {
                    foreach ($data['availabilities'] as $availability) {
                        $activityAvailability = $activityAvailabilityRepository->find($availability);
                        $activityAvailability->setQuota($activityAvailability->getQuota() + 1);
                        $activityAvailability->setTotalBookings($activityAvailability->getTotalBookings() - 1);
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

            $this->send_voucher($newVoucher->getId(), 'all', $mailer, $pdf, $voucherRepository, $configurationRepository, $entityManager);


        } catch (SoapFault $e) {
            return $this->json([
                'response'  => $e
            ]);
        }
    }

    #[Route('/change_to_prebooked/{id}', name: 'api_change_to_prebooked')]
    public function change_to_prebooked(EntityManagerInterface $entityManager, HotelAvailabilityRepository $hotelAvailabilityRepository, BookingRepository $bookingRepository, string $id): Response
    {
        $booking = $bookingRepository->find($id);

        if ($booking->getStatus() == 'onRequest') {
            foreach ($booking->getBookingLines()[0]->getData() as $room) {
                foreach ($room['availabilities'] as $availability) {
                    $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                    $hotelAvailability->setQuota($hotelAvailability->getQuota() - 1);
                    $hotelAvailability->setTotalBookings($hotelAvailability->getTotalBookings() + 1);
                    $entityManager->persist($hotelAvailability);
                }    
            }

            $booking->setStatus('preBooked');
        }

        $entityManager->persist($booking);
        $entityManager->flush();

        return $this->json([
            'response'  => $booking
        ]); 
    }

    #[Route('/change_to_cancelled/{id}', name: 'api_change_to_cancelled')]
    public function change_to_cancelled(EntityManagerInterface $entityManager, HotelAvailabilityRepository $hotelAvailabilityRepository, BookingRepository $bookingRepository, string $id): Response
    {
        $booking = $bookingRepository->find($id);

        if ($booking->getStatus() == 'preBooked' || $booking->getStatus() == 'booked') {
            foreach ($booking->getBookingLines()[0]->getData() as $room) {
                foreach ($room['availabilities'] as $availability) {
                    $hotelAvailability = $hotelAvailabilityRepository->find($availability);
                    $hotelAvailability->setQuota($hotelAvailability->getQuota() + 1);
                    $hotelAvailability->setTotalBookings($hotelAvailability->getTotalBookings() - 1);
                    $entityManager->persist($hotelAvailability);
                }    
            }

            $booking->setStatus('cancelled');
        }

        $entityManager->persist($booking);
        $entityManager->flush();

        return $this->json([
            'response'  => $booking
        ]); 
    }

    #[Route('/send_thank_you_email/{id}/{destinatary}', name: 'api_send_thank_you_email')]
    public function send_voucher(int $id, string $destinatary, MailerInterface $mailer, Pdf $pdf, VoucherRepository $voucherRepository, ConfigurationRepository $configurationRepository, EntityManagerInterface $entityManager): Response
    {
        if ($destinatary == 'all') {
            $voucher = $voucherRepository->find($id);
            $booking = $voucher->getBooking();
            $company = $configurationRepository->find(1);
            $supplier = $booking->getBookingLines()[0]->getHotel() ? $booking->getBookingLines()[0]->getHotel()->getSupplier() : $booking->getBookingLines()[0]->getActivity()->getSupplier();

            if ($booking->getBookingLines()[0]->getHotel()->isHasSendEmailClient() || $booking->getBookingLines()[0]->getActivity()->isHasSendEmailClient()) {
                $this->send_client_voucher($voucher, $booking, $company, $supplier, $mailer, $pdf, $entityManager);
            }
            if ($booking->getBookingLines()[0]->getHotel()->isHasSendEmailSupplier() || $booking->getBookingLines()[0]->getActivity()->isHasSendEmailClient()) {
                $this->send_supplier_voucher($voucher, $booking, $company, $supplier, $mailer, $pdf, $entityManager);
            }

            return $this->json([
                'response'  => 'success'
            ]);
        } else {
            $voucher = $voucherRepository->findOneBy(array('booking' => $id));
            $booking = $voucher->getBooking();
            $company = $configurationRepository->find(1);
            $supplier = $booking->getBookingLines()[0]->getHotel() ? $booking->getBookingLines()[0]->getHotel()->getSupplier() : $booking->getBookingLines()[0]->getActivity()->getSupplier();
            if ($destinatary == 'client') {
                return $this->send_client_voucher($voucher, $booking, $company, $supplier, $mailer, $pdf, $entityManager);
            } else if ($destinatary == 'supplier') {
                return $this->send_supplier_voucher($voucher, $booking, $company, $supplier, $mailer, $pdf, $entityManager);
            }
        }
    }

    public function send_client_voucher($voucher, $booking, $company, $supplier, MailerInterface $mailer, Pdf $pdf, EntityManagerInterface $entityManager): Response
    {
        if ($booking->getBookingLines()[0]->getHotel() != null) {

            // Creating the email content

            $context = [
                "name" => $booking->getName(),
                "bookingEmail" => $booking->getEmail(),
                "phone" => $booking->getPhone(),
                "id" => $booking->getId(),
                "product" => $booking->getBookingLines()[0]->getHotel(),
                "rooms" => $booking->getBookingLines()[0]->getData(),
                "totalPrice" => $booking->getTotalPrice(),
                "paymentMethod" => $booking->getPaymentMethod(),
                "date" => $booking->getCreatedAt(),
                "startDate" => $booking->getBookingLines()[0]->getCheckIn(),
                "endDate" => $booking->getBookingLines()[0]->getCheckOut(),
            ];

            $fileName = 'Bono_Hotel.pdf';

            // Creating the twig for the PDF with the booking data

            $html = $this->renderView('document/voucher.html.twig', [
                'to_be_paid_by' => $voucher->getToBePaidBy(),
                'productTitle' => $booking->getBookingLines()[0]->getHotel()->getTitle(),
                'productAddress' => $booking->getBookingLines()[0]->getHotel()->getAddress(),
                'productZone' => $booking->getBookingLines()[0]->getHotel()->getZones()[0]->getName(),
                'productLocation' => $booking->getBookingLines()[0]->getHotel()->getLocation()->getName(),
                'bookingId' => $booking->getId(),
                'bookingDate' => $booking->getCreatedAt(),
                'clientName' => $booking->getClient()->getName(),
                'checkIn' => $booking->getBookingLines()[0]->getCheckIn(),
                'checkOut' => $booking->getBookingLines()[0]->getCheckOut(),
                "rooms" => $booking->getBookingLines()[0]->getData(),
                "observations" => $voucher->getObservations(),
                'companyName' => $company->getTitle(),
                'companyCif' => $company->getCif(),
                'companyAddress' => $company->getTitle(),
                'companyPostalCode' => $company->getPostalCode(),
                'companyCity' => $company->getCity(),
                'companyProvince' => $company->getProvince(),
                'companyCountry' => $company->getCountry(),
                'companyPhone' => $company->getPhone(),
                'supplierPhone' => $supplier->getBookingPhone(),
                'supplierTitle' => $supplier->getName()
            ]);

            // Sending the email with the voucher attachment

            $email = (new TemplatedEmail())
                ->from($company->getBookingEmail())
                ->to($booking->getClient()->getEmail())
                ->subject('Gracias por tu reserva')
                ->context($context)
                ->attach($pdf->getOutputFromHtml($html), $fileName, 'application/pdf')
                ->htmlTemplate('email/hotel_thank_you.html.twig');

            if ($booking->getStatus() == 'booked') {
                $mailer->send($email);
                $booking->setClientConfirmationSent(true);
                $entityManager->persist($booking);
                $entityManager->flush();
            }

            return $this->json([
                'response'  => 'send'
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
                "startDate" => $booking->getBookingLines()[0]->getCheckIn(),
                "endDate" => $booking->getBookingLines()[0]->getCheckOut(),
            ];
            $fileName = 'Bono_Actividad.pdf';

            // Creating the twig for the PDF with the booking data

            $html = $this->renderView('document/voucher.html.twig', [
                'to_be_paid_by' => $voucher->getToBePaidBy(),
                'productTitle' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getTitle(),
                'productAddress' => $booking->getBookingLines()[0]->getActivity()->getAddress(),
                'productZone' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getZones()[0]->getName(),
                'productLocation' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getLocation()->getName(),
                'bookingId' => $voucher->getBooking()->getId(),
                'bookingDate' => date("d-m-Y"),
                'clientName' => $voucher->getBooking()->getClient()->getName(),
                'checkIn' => $voucher->getBooking()->getBookingLines()[0]->getCheckIn(),
                'checkOut' => $voucher->getBooking()->getBookingLines()[0]->getCheckOut(),
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
                ->from($company->getBookingEmail())
                ->to($booking->getClient()->getEmail())
                ->subject('Gracias por tu reserva')
                ->context($context)
                ->attach($pdf->getOutputFromHtml($html), $fileName, 'application/pdf')
                ->htmlTemplate('email/activity_thank_you.html.twig');

            if ($booking->getStatus() == 'booked') {
                $mailer->send($email);
                $booking->setClientConfirmationSent(true);
                $entityManager->persist($booking);
                $entityManager->flush();
            }

            return $this->json([
                'response'  => 'send'
            ]);
        }
    }

    public function send_supplier_voucher($voucher, $booking, $company, $supplier, MailerInterface $mailer, Pdf $pdf, EntityManagerInterface $entityManager): Response
    {
        if ($booking->getBookingLines()[0]->getHotel() != null) {

            // Creating the email content

            $context = [
                "name" => $booking->getName(),
                "bookingEmail" => $booking->getEmail(),
                "phone" => $booking->getPhone(),
                "id" => $booking->getId(),
                "product" => $booking->getBookingLines()[0]->getHotel(),
                "rooms" => $booking->getBookingLines()[0]->getData(),
                "totalPrice" => $booking->getTotalPrice(),
                "paymentMethod" => $booking->getPaymentMethod(),
                "date" => $booking->getCreatedAt(),
                "startDate" => $booking->getBookingLines()[0]->getCheckIn(),
                "endDate" => $booking->getBookingLines()[0]->getCheckOut(),
                "message" => 'Reserva confirmada'
            ];

            $fileName = 'confirmacion_reserva_'.$booking->getId().'.pdf';

            // Creating the twig for the PDF with the booking data

            $html = $this->renderView('document/voucher_supplier.html.twig', [
                'to_be_paid_by' => $voucher->getToBePaidBy(),
                'productTitle' => $booking->getBookingLines()[0]->getHotel()->getTitle(),
                'productZone' => $booking->getBookingLines()[0]->getHotel()->getZones()[0]->getName(),
                'productLocation' => $booking->getBookingLines()[0]->getHotel()->getLocation()->getName(),
                'bookingId' => $booking->getId(),
                'bookingDate' => $booking->getCreatedAt(),
                'clientName' => $booking->getClient()->getName(),
                'checkIn' => $booking->getBookingLines()[0]->getCheckIn(),
                'checkOut' => $booking->getBookingLines()[0]->getCheckOut(),
                "rooms" => $booking->getBookingLines()[0]->getData(),
                "totalPriceCost" => $booking->getTotalPriceCost(),
                "observations" => $voucher->getObservations(),
                'companyName' => $company->getTitle(),
                'companyCif' => $company->getCif(),
                'companyAddress' => $company->getTitle(),
                'companyPostalCode' => $company->getPostalCode(),
                'companyCity' => $company->getCity(),
                'companyProvince' => $company->getProvince(),
                'companyCountry' => $company->getCountry(),
                'companyPhone' => $company->getPhone(),
                'supplierPhone' => $supplier->getBookingPhone(),
                'supplierTitle' => $supplier->getName()
            ]);

            // Sending the email with the voucher attachment

            $email = (new TemplatedEmail())
                ->from($company->getBookingEmail())
                ->to($booking->getBookingLines()[0]->getHotel()->getBookingEmail() ? $booking->getBookingLines()[0]->getHotel()->getBookingEmail() : $supplier->getBookingEmail())
                ->subject('Confirmación de reserva')
                ->context($context)
                ->attach($pdf->getOutputFromHtml($html), $fileName, 'application/pdf')
                ->htmlTemplate('communications/supplier_booking_confirmed.html.twig');

            if ($booking->getStatus() == 'booked') {
                $mailer->send($email);
                $booking->setSupplierConfirmationSent(true);
                $entityManager->persist($booking);
                $entityManager->flush();
            }

            return $this->json([
                'response'  => 'send'
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
                "startDate" => $booking->getBookingLines()[0]->getCheckIn(),
                "endDate" => $booking->getBookingLines()[0]->getCheckOut(),
                "message" => 'Reserva confirmada'
            ];
            $fileName = 'confirmacion_reserva_'.$booking->getId().'.pdf';

            // Creating the twig for the PDF with the booking data

            $html = $this->renderView('document/voucher_supplier.html.twig', [
                'to_be_paid_by' => $voucher->getToBePaidBy(),
                'productTitle' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getTitle(),
                'productZone' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getZones()[0]->getName(),
                'productLocation' => $voucher->getBooking()->getBookingLines()[0]->getActivity()->getLocation()->getName(),
                'bookingId' => $voucher->getBooking()->getId(),
                'bookingDate' => date("d-m-Y"),
                'clientName' => $voucher->getBooking()->getClient()->getName(),
                'checkIn' => $voucher->getBooking()->getBookingLines()[0]->getCheckIn(),
                'checkOut' => $voucher->getBooking()->getBookingLines()[0]->getCheckOut(),
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
                ->from($company->getBookingEmail())
                ->to($booking->getBookingLines()[0]->getActivity()->getBookingEmail() ? $booking->getBookingLines()[0]->getActivity()->getBookingEmail() : $supplier->getBookingEmail())
                ->subject('Confirmación de reserva')
                ->context($context)
                ->attach($pdf->getOutputFromHtml($html), $fileName, 'application/pdf')
                ->htmlTemplate('communications/supplier_booking_confirmed.html.twig');

            if ($booking->getStatus() == 'booked') {
                $mailer->send($email);
                $booking->setSupplierConfirmationSent(true);
                $entityManager->persist($booking);
                $entityManager->flush();
            }

            return $this->json([
                'response'  => 'send'
            ]);
        }
    }

    #[Route('/get_voucher_by_booking/{id}', name: 'api_get_voucher_by_booking')]
    public function getVoucherByBookingId(String $id, VoucherRepository $voucherRepository): Response
    {
        $voucher = $voucherRepository->findOneBy(array('booking' => $id));

        return $this->json([
            'response'  => $voucher
        ]);
    }    

}
