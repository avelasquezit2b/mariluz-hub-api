<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\SupplierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        $request = json_decode($request->getContent());

        $email = (new TemplatedEmail())
            ->from('adriarias@it2b.es')
            ->to('adriarias@it2b.es')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Correo de prueba')
            ->context([
                "userEmail" => $request->email,
                "name" => $request->name,
                "phone" => $request->phone,
                "message" => $request->message,
                "productName" => $request->product,
                "route" => $request->route
            ])
            ->htmlTemplate('email/index.html.twig');

        $mailer->send($email);

        return $this->json([
            'email'  => $email
        ]);
    }

    #[Route('/bookingEmail')]
    public function sendBookingEmail(Request $request, MailerInterface $mailer, SupplierRepository $supplierRepository, ClientRepository $clientRepository): Response
    {
        $request = json_decode($request->getContent());

        if ($request->type == 'suppliers') {
            $supplier = $supplierRepository->find(str_replace("/suppliers/", '', $request->specificId));
            $name = $supplier->getName();
            $filename = 'supplier_';
        } else if ($request->type == 'clients') {
            $client = $clientRepository->find(str_replace("/clients/", '', $request->specificId));
            $name = $client->getName();
            $filename = 'client_';
        } else {
            $filename = '';
        }

        $email = (new TemplatedEmail())
            ->from('adriarias@it2b.es')
            ->to(implode(',', $request->recipients))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($request->subject)
            ->context([
                "name" => $name,
                "message" => $request->message
            ])
            ->htmlTemplate('communications/' . $filename . 'booking_' . $request->status . '.html.twig');

        $mailer->send($email);

        return $this->json([
            'email'  => $email
        ]);
    }
}
