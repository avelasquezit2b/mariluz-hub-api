<?php

namespace App\Controller;

use App\Entity\ActivityAvailability;
use App\Entity\HotelAvailability;
use App\Repository\ActivityRepository;
use App\Repository\ActivityScheduleRepository;
use App\Repository\HotelRepository;
use App\Repository\RoomConditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AvailabilityController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/generate_activity_availability', name: 'app_generate_activity_availability')]
    public function generateActivityAvailability(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, ActivityRepository $activityRepository, ActivityScheduleRepository $activityScheduleRepository): Response
    {
        $requestDecode = json_decode($request->getContent());
        $activitySchedule = $activityScheduleRepository->find($requestDecode->schedule->id);

        foreach ($requestDecode->season->ranges as $range) {
            $currentDate = strtotime($range->startDate);
            // $currentDate = strtotime('+1 day', $currentDate);

            $endDate = strtotime($range->endDate);
            // $endDate = strtotime('+1 day', $endDate);

            while ($currentDate <= $endDate) {
                $currentDayOfWeek = date('N', $currentDate);
        
                if (in_array($currentDayOfWeek, $requestDecode->schedule->weekDays)) {
                    $activityAvailability = new ActivityAvailability();
                    $activityAvailability->setDate(\DateTime::createFromFormat('U', ($currentDate)));
                    $activityAvailability->setQuota($requestDecode->schedule->quota);
                    // $activityAvailability->setMaxQuota($requestDecode->schedule->quota);
                    $activityAvailability->setActivitySchedule($activitySchedule);

                    $entityManager->persist($activityAvailability);
                    $entityManager->flush();

                    $dates[] = date('d-m-Y', $currentDate);
                }
        
                $currentDate = strtotime('+1 day', $currentDate);
            }
        }

        return $this->json([
            'dates'  => $dates
        ]);
    }

    #[Route('/generate_hotel_availability', name: 'app_generate_hotel_availability')]
    public function generateHotelAvailability(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, HotelRepository $hotelRepository, RoomConditionRepository $roomConditionRepository): Response
    {
        $requestDecode = json_decode($request->getContent());
        $roomCondition = $roomConditionRepository->find($requestDecode->room->id);

        foreach ($requestDecode->season->ranges as $range) {
            $currentDate = strtotime($range->startDate);
            // $currentDate = strtotime('+1 day', $currentDate);

            $endDate = strtotime($range->endDate);
            // $endDate = strtotime('+1 day', $endDate);

            while ($currentDate <= $endDate) {        
                $hotelAvailability = new HotelAvailability();
                $hotelAvailability->setDate(\DateTime::createFromFormat('U', ($currentDate)));
                $hotelAvailability->setQuota($requestDecode->room->quota);
                $hotelAvailability->setMaxQuota($requestDecode->room->quota);
                $hotelAvailability->setRoomCondition($roomCondition);

                $entityManager->persist($hotelAvailability);
                $entityManager->flush();

                $dates[] = date('d-m-Y', $currentDate);
        
                $currentDate = strtotime('+1 day', $currentDate);
            }
        }

        return $this->json([
            'dates'  => $dates
        ]);
    }

    function generateDatesInRange($startDate, $endDate, $weekdays) {
        $dates = [];
    
        $currentDate = strtotime($startDate);
    
        while ($currentDate <= strtotime($endDate)) {
            $currentDayOfWeek = date('N', $currentDate);
    
            if (in_array($currentDayOfWeek, $weekdays)) {
                $dates[] = date('Y-m-d', $currentDate);
            }
    
            $currentDate = strtotime('+1 day', $currentDate);
        }
    
        return $dates;
    }
}
