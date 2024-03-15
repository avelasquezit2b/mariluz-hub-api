<?php

namespace App\Controller;

use App\Entity\Section;
use App\Repository\SectionRepository;
use App\Repository\ThemeRepository;
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
    public function updatePosition(Request $request, SectionRepository $sectionRepository): JsonResponse
    {
        $requestDecode = json_decode($request->getContent());

        foreach ($requestDecode->sections as $key=>$section) {
            $section = $sectionRepository->find($section->id);
            $section->setPosition($key);
            $this->entityManager->persist($section);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Element positions updated successfully']);
    }

    #[Route('/themes/update_positions', name: 'api_update_theme_positions')]
    public function updateThemePosition(Request $request, ThemeRepository $themeRepository): JsonResponse
    {
        $requestDecode = json_decode($request->getContent());

        foreach ($requestDecode->themes as $key=>$theme) {
            $theme = $themeRepository->find($theme->id);
            $theme->setPosition($key);
            $this->entityManager->persist($theme);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Element positions updated successfully']);
    }
}
