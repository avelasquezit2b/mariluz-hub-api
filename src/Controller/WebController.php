<?php

namespace App\Controller;

use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class WebController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/sections/update_positions', name: 'api_update_positions')]
    public function updatePosition(Request $request,  SectionRepository $sectionRepository): JsonResponse
    {
        $requestDecode = json_decode($request->getContent());

        foreach ($requestDecode->sections as $key=>$section) {
            $section = $sectionRepository->find($section->id);
            $section->setPosition($key);
            $this->entityManager->persist($section);
        }

    }
}