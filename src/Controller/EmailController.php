<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from('adriarias@it2b.es')
            ->to('adriarias@it2b.es')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Correo de prueba')
            // ->context([
            //     "emailUser" => $request->email,
            //     "name" => $request->name,
            //     "phone" => $request->phone,
            //     "services" => $request->services
            // ])
            ->htmlTemplate('email/index.html.twig');

        $mailer->send($email);

        return $this->json([
            'communication'  => $email
        ]);
    }
}
