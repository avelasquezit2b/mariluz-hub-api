<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\EscposImage\ImagePrintBuffer;
use Mike42\Escpos\GdEscposImage;

class PrintController extends AbstractController
{


    #[Route('/printTicket', name: 'print', methods: ['POST'])]
    public function print(Request $request): Response
    {

        $request = json_decode($request->getContent());

        // dd($request[0]->id);

        // $request = array_slice($request, 0, 1);


        try {

            foreach ($request as $ticket) {
                $this->printToPrinter($ticket);
            }

            return new Response('Impresión realizada', Response::HTTP_OK);
        } catch (\Throwable $th) {

            throw $th;
            return new JsonResponse(['error' => 'Impresión: no ha funcionado, intentalo de nuevo'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }





        // Obtener los datos del request
        // $data = json_decode($request->getContent(), true);

        // Lógica de impresión
        // $this->printToPrinter($request);


    }

    private function printToPrinter(object $data)
    {



        try {
            // $ip_impresora = 'IPP://192.168.1.56';
            $connector = new NetworkPrintConnector("192.168.1.100", 9100);

            // Crear una instancia del conector de impresión de red
            // $connector = new NetworkPrintConnector($ip_impresora);

            // Crear una instancia del objeto Printer
            $printer = new Printer($connector);

            /* Very large text */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(4, 4);
            $printer->text("Jardines\nde Alfabia\n");

            /* default text size */
            $printer->setTextSize(1, 1);

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------\n");
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text("Ticket #" . $data->id . " | " . $data->productName . " - " . $data->passType . "\n");
            $printer->feed(1);
            /* Printer mode emphasis */
            $printer->text("Precio: " . $data->price . " EUROS\n");
            $printer->text("Fecha actividad: " . $data->booking->bookingLines[0]->checkIn . "\n");
            $printer->selectPrintMode(); // Reset
            // $printer->text("Email cliente: " . $ticketData['customer_email'] . "\n");
            $printer->text("Comprado en: " . $data->booking->name . "\n");
            // $printer->text("Observaciones: " . $data->booking->observations . "\n");
            $printer->setTextSize(1, 1);
            $printer->feed(1);
            $printer->text("----------------------------------------------\n");

            // Corta el papel
            $printer->cut();

            // Cierra la conexión con la impresora
            $printer->close();

            // echo "¡Impresión exitosa!";
        } catch (\Exception $e) {
            echo "Error al imprimir: " . $e->getMessage();
        }

        return new JsonResponse(['error' => 'Impresión: No se pudo imprimir'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);


        // Aquí iría la lógica para imprimir en la impresora física
        // Esto podría involucrar comandos de impresora específicos, por ejemplo, usando escpos-php
    }

    function title(Printer $printer, $text)
    {
        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer->text("\n" . $text);
        $printer->selectPrintMode(); // Reset
    }

}
