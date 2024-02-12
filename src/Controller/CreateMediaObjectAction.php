<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Repository\ActivityRepository;
use App\Repository\HotelRepository;
use App\Repository\RoomTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request, ActivityRepository $activityRepository, HotelRepository $hotelRepository, RoomTypeRepository $roomTypeRepository): MediaObject
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;
        $mediaObject->setType($request->request->get('type'));
        $mediaObject->setPosition($request->request->get('position'));
        if ($request->request->get('activity')) {
            $activity = $activityRepository->find($request->request->get('activity'));
            $mediaObject->setActivity($activity);
        }
        if ($request->request->get('hotel')) {
            $hotel = $hotelRepository->find($request->request->get('hotel'));
            $mediaObject->setHotel($hotel);
        }
        if ($request->request->get('roomType')) {
            $roomType = $roomTypeRepository->find($request->request->get('roomType'));
            $mediaObject->setRoomType($roomType);
        } 

        return $mediaObject;
    }
}