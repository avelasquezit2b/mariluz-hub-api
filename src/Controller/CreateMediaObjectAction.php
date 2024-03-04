<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Repository\ActivityRepository;
use App\Repository\HotelRepository;
use App\Repository\RoomTypeRepository;
use App\Repository\ThemeRepository;
use App\Repository\HeroSlideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request, ActivityRepository $activityRepository, HotelRepository $hotelRepository, RoomTypeRepository $roomTypeRepository, ThemeRepository $themeRepository, HeroSlideRepository $heroSlideRepository): MediaObject
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
        if ($request->request->get('theme')) {
            $theme = $themeRepository->find($request->request->get('theme'));
            $mediaObject->setTheme($theme);
        }
        if ($request->request->get('heroSlide')) {
            $heroSlide = $heroSlideRepository->find($request->request->get('heroSlide'));
            $mediaObject->setHeroSlide($heroSlide);
        }

        return $mediaObject;
    }
}