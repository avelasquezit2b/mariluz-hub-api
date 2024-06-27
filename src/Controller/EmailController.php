<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\BookingRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\SupplierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

class EmailController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(Request $request, MailerInterface $mailer, ConfigurationRepository $configurationRepository): Response
    {
        $request = json_decode($request->getContent());
        $company = $configurationRepository->find(1);

        if ($request->product) {
            $emailSubject = ($request->isDatesRequest ? 'Petición fechas en: ' : 'Dudas sobre: ') . $request->product;
            $customerEmailSubject = $emailSubject;
        } else {
            $emailSubject = 'Hemos recibido un mensaje!';
            $customerEmailSubject = 'Hemos recibido tu mensaje!';
        }

        $customerEmail = (new TemplatedEmail())
            ->from($company->getBookingEmail())
            ->to($request->email)
            ->subject($customerEmailSubject)
            ->context([
                "userEmail" => $request->email,
                "name" => $request->name,
                "phone" => $request->phone,
                "message" => $request->message,
                "product" => $request->product
            ])
            ->htmlTemplate('email/on_request_to_client.html.twig');

        $email = (new TemplatedEmail())
            ->from($company->getBookingEmail())
            ->to('adriarias@it2b.es')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($emailSubject)
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
        $mailer->send($customerEmail);

        return $this->json([
            'email'  => $email
        ]);
    }

    #[Route('/bookingEmail')]
    public function sendBookingEmail(Request $request, MailerInterface $mailer, SupplierRepository $supplierRepository, ClientRepository $clientRepository): Response
    {
        $request = json_decode($request->getContent());

        // if ($request->type == 'suppliers') {
        //     $supplier = $supplierRepository->find(str_replace("/suppliers/", '', $request->specificId));
        //     $name = $supplier->getName();
        //     $filename = 'supplier_';
        // } else if ($request->type == 'clients') {
        //     $client = $clientRepository->find(str_replace("/clients/", '', $request->specificId));
        //     $name = $client->getName();
        //     $filename = 'client_';
        // } else {
        $filename = '';
        // }

        $email = (new TemplatedEmail())
            ->from('adriarias@it2b.es')
            ->to(implode(',', ['avelasquez@it2b.es']))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($request->subject)
            ->context([
                "name" => '$name',
                "message" => '$request->message'
            ])
            ->htmlTemplate('communications/' . $filename . 'booking_' . 'confirmed' . '.html');

        $mailer->send($email);

        return $this->json([
            'email'  => $email
        ]);
    }

    #[Route('/qrBookingEmail/{id}')]
    public function sendTestEmail(String $id, MailerInterface $mailer, BookingRepository $bookingRepository): Response
    {
        $qrId = '{"id": '.$id.'}';
        $booking = $bookingRepository->find($id);

        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($qrId)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->size(300)
            ->margin(10)
            ->backgroundColor(new Color(255, 255, 255))
            ->build();

        //$qrCode->saveToFile('./media/qr/qrcode-' . 144 . '.png');

        $dataUri = $qrCode->getDataUri();

        $tempQrFile = tempnam(sys_get_temp_dir(), 'qr_code');
        file_put_contents($tempQrFile, base64_decode(substr($dataUri, strpos($dataUri, ',') + 1)));
    
        $email = (new TemplatedEmail())
            ->from('dev@it2b.es')
            ->to($booking->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Entradas | ¡Gracias por reservar en Jardines de Alfabia!')
            ->context([
                "userEmail" => $booking->getEmail(),
                "name" => $booking->getName(),
                "message" => 'Mensaje de prueba',
            ])
            ->htmlTemplate('email/qr_booking_email.html.twig')->embedFromPath($tempQrFile, 'qrcode');

        $mailer->send($email);

        return $this->json([
            'email'  => $email
        ]);
    }
}
