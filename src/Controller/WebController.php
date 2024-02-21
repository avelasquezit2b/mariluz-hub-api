<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Section;
use Symfony\Component\HttpFoundation\JsonResponse;

class WebController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/sections/{id}/position', name: 'api_update_element_position')]
    public function updatePosition(Request $request, Section $section): JsonResponse
    {
        // Get the new position from the request body
        $newPosition = $request->request->get('newPosition');

        // Get the current position of the element
        $currentPosition = $section->getPosition();

        // Get all elements with a higher position than the element being updated
        $elementsToUpdate = $this->entityManager->getRepository(Section::class)->findBy(['position' => $currentPosition]);

        // Update the positions of all elements with a higher position
        foreach ($elementsToUpdate as $elementToUpdate) {
            if ($elementToUpdate->getId() !== $section->getId()) {
                $elementToUpdate->setPosition($elementToUpdate->getPosition() + 1);
            } else {
                // Update the position of the element being updated
                $section->setPosition($newPosition);
            }
        }

        // Persist changes to the database
        $this->entityManager->flush();

        return $this->json(['message' => 'Element positions updated successfully']);
    }
}
