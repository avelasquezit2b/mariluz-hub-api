<?php

namespace App\Controller;

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

    #[Route('/print_pdf')]
    public function pdfAction(Pdf $pdf)
    {
        $html = $this->renderView('document/index.html.twig', [
            // Add any data you want to include in the PDF
            'name' => 'John Doe',
            // Add more data as needed
        ]);

        // Generate the PDF
        $pdfContent = $pdf->getOutputFromHtml($html);

        // Return the PDF as a response
        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="example.pdf"'
            ]
        );
    }
}
