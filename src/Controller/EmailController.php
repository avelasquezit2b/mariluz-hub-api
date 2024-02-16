<?php

namespace App\Controller;

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
}
