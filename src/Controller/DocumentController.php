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

    #[Route('/print_voucher')]
    public function pdfVoucherAction(Pdf $pdf)
    {
        $html = $this->renderView('document/voucher.html.twig', [
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
