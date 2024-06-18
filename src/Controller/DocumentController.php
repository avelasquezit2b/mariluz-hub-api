<?php

namespace App\Controller;

use App\Repository\VoucherRepository;
use App\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;

class DocumentController extends AbstractController
{

    #[Route('/document', name: 'app_document')]
    public function index(): Response
    {
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }

    #[Route('/print_voucher/{id}')]
    public function pdfVoucherAction(Pdf $pdf, VoucherRepository $bookingVoucherRepository, ConfigurationRepository $configurationRepository, string $id)
    {
        $bookingVoucher = $bookingVoucherRepository->find($id);
        $booking = $bookingVoucher->getBooking();
        $company = $configurationRepository->find(1);
        $supplier = $booking->getBookingLines()[0]->getHotel() ? $booking->getBookingLines()[0]->getHotel()->getSupplier() : $booking->getBookingLines()[0]->getActivity()->getSupplier();
        $product = $booking->getBookingLines()[0]->getHotel() ? $booking->getBookingLines()[0]->getHotel() : $booking->getBookingLines()[0]->getActivity();

        if ($booking->getBookingLines()[0]->getHotel()) {
            $html = $this->renderView('document/voucher.html.twig', [
                'to_be_paid_by' => $bookingVoucher->getToBePaidBy(),
                'hotel' => $bookingVoucher->getHotel(),
                'booking' => $booking,
                'productTitle' => $product->getTitle(),
                'productAddress' => $product->getAddress(),
                'productZone' => $product->getZones()[0]->getName(),
                'productLocation' => $product->getLocation()->getName(),
                'bookingId' => $booking->getId(),
                'bookingDate' => $booking->getCreatedAt(),
                'clientName' => $booking->getClient()->getName(),
                'checkIn' => $booking->getBookingLines()[0]->getCheckIn(),
                'checkOut' => $booking->getBookingLines()[0]->getCheckOut(),
                "rooms" => $booking->getBookingLines()[0]->getData(),
                "observations" => $booking->getObservations(),
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
        } else if ($booking->getBookingLines()[0]->getActivity()) {
            $quantity = 0;
            foreach ($booking->getBookingLines()[0]->getData()['clientTypes'] as $key => $value) {
                $quantity += $value['quantity'];
            }
    
            $html = $this->renderView('document/activity_voucher.html.twig', [
                'to_be_paid_by' => $bookingVoucher->getToBePaidBy(),
                'productTitle' => $booking->getBookingLines()[0]->getActivity()->getTitle(),
                'productZone' => $booking->getBookingLines()[0]->getActivity()->getZones()[0]->getName(),
                'productLocation' => $booking->getBookingLines()[0]->getActivity()->getLocation()->getName(),
                'bookingId' => $booking->getId(),
                'bookingDate' => date("d-m-Y"),
                'clientName' => $booking->getClient()->getName(),
                'modality' => $booking->getBookingLines()[0]->getData()['modality'],
                'schedule' => $booking->getBookingLines()[0]->getData()['schedule'],
                'quantity' => $quantity,
                "observations" => $booking->getObservations(),
                'checkIn' => $booking->getBookingLines()[0]->getCheckIn(),
                'checkOut' => $booking->getBookingLines()[0]->getCheckOut(),
                'companyName' => $company->getTitle(),
                'companyCif' => $company->getCif(),
                'companyAddress' => $company->getTitle(),
                'companyPostalCode' => $company->getPostalCode(),
                'companyCity' => $company->getCity(),
                'companyProvince' => $company->getProvince(),
                'companyCountry' => $company->getCountry(),
                'companyPhone' => $company->getPhone(),
                'supplierTitle' => $supplier->getName()
            ]);
        }

        $fileName = 'bono_reserva_'.$booking->getId().'.pdf';

        return new Response(
            $pdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    #[Route('/print_bill')]
    public function pdfBillAction(Pdf $pdf)
    {
        $html = $this->renderView('document/bill.html.twig', [
            // Add any data needed for rendering the Twig template
        ]);

        $filename = 'pdf.pdf';

        return new Response(
            $pdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }
}
