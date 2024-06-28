<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use App\Repository\ClientRepository;
use App\Repository\HotelRepository;
use App\Repository\SupplierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use stdClass;

class CalculateController extends AbstractController
{
    #[Route('/calculate', name: 'app_calculate')]
    public function index(): Response
    {
        return $this->render('calculate/index.html.twig', [
            'controller_name' => 'CalculateController',
        ]);
    }

    #[Route('/calculate_totals', name: 'api_calculate_totals')]
    public function calculateTotals(HotelRepository $hotelRepository, BookingRepository $bookingRepository, ClientRepository $clientRepository, SupplierRepository $supplierRepository): JsonResponse
    {
        $totals = new stdClass();

        $bookingsTotal = $bookingRepository->count([]);
        $totals->bookings = $bookingsTotal;
        $clientsTotal = $clientRepository->count([]);
        $totals->clients = $clientsTotal;
        $suppliersTotal = $supplierRepository->count([]);
        $totals->suppliers = $suppliersTotal;

        // Get all bookings
        $bookings = $bookingRepository->findAll();
        // Initialize total price to 0
        $totalPrice = 0;
        $totalPriceCost = 0;
        $totalsByMonth = [];

        // Iterate through bookings and sum 'totalPrice' fields
        foreach ($bookings as $booking) {
            $totalPrice += floatval($booking->getTotalPrice());
            $totalPriceCost += floatval($booking->getTotalPriceCost());

            // Data by month (Spanish month names)
            setlocale(LC_ALL, "es_ES");
            $bookingMonth = strftime("%B", strtotime($booking->getCreatedAt()));

            if (!isset($totalsByMonth[$bookingMonth])) {
                $totalsByMonth[$bookingMonth] = ['total' => 0, 'totalCost' => 0];
                // $totalsByMonth[$bookingMonth] = ['total' => 0, 'totalCost' => 0, 'bookings' => 0];
            }

            $totalsByMonth[$bookingMonth]['total'] += floatval($booking->getTotalPrice());
            $totalsByMonth[$bookingMonth]['totalCost'] += floatval($booking->getTotalPriceCost());
            // $totalsByMonth[$bookingMonth]['bookings'] += 1;
        }

        $totals->totalPrice = $totalPrice;
        $totals->totalPriceCost = $totalPriceCost;

        $responseDataByMonth = [];

        foreach ($totalsByMonth as $month => $data) {
            $responseDataByMonth[] = [
                'month' => $month,
                'total' => $data['total'],
                'totalCost' => $data['totalCost'],
                // 'bookings' => $data['bookings'],
            ];
        }

        $totals->totalsByMonth = $responseDataByMonth;

        return $this->json(['totals' => $totals]);
    }
}
