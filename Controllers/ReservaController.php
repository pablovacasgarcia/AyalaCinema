<?php

namespace Controllers;

use Lib\Pages;
use Models\Butaca;
use Models\Reserva;
use Utils\Utils;
use Utils\FPDF;

/**
 * Controlador para la gestión de reservas de butacas en el cine.
 */
class ReservaController
{
    /** @var Pages Instancia de la clase Pages para la gestión de páginas. */
    private $pages;

    /**
     * Constructor de la clase ReservaController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Renderiza la página de reserva.
     */
    public function reservar(){
        $this->pages->render("/reserva/reservar");
    }

    /**
     * Gestiona la selección de butacas durante el proceso de reserva.
     */
    public function seleccionar(){
        if (isset($_GET['butaca'])){
            if (isset($_SESSION['seleccionadas'])){
                $seleccionadas=unserialize($_SESSION['seleccionadas']);
            } else {
                $seleccionadas=[];
            }

            $posicion = array_search($_GET['butaca'], $seleccionadas);
            $reservadas = [];
            if (isset($_SESSION['login'])){
                $reserva=new Reserva([], $_SESSION['login']->email);
                $reservadas=$reserva->getReservas();
            }


            if ($posicion!==false){
                unset($seleccionadas[$posicion]);
            } else {
                if (count($seleccionadas)+count($reservadas)>=5){
                    $errores="No puedes seleccionar más de 5 butacas";
                } else {
                    $seleccionadas[]=$_GET['butaca'];
                }
            }

            $seleccionadasSerial=serialize($seleccionadas);
            $_SESSION['seleccionadas'] = $seleccionadasSerial;

            if (isset($errores)){
                $_SESSION['errores'] = $errores;
            }

            header("location: ".BASE_URL."reserva/reservar/");
        }
    }


    /**
     * Guarda la reserva realizada por el usuario.
     */
    public function guardar(){
        if (isset($_SESSION['login'])){
            if (isset($_SESSION['seleccionadas'])){
                $butacas=unserialize($_SESSION['seleccionadas']);
                $reserva=new Reserva([], $_SESSION['login']->email);
                $reservadas=$reserva->getReservas();

                if (count($butacas)+count($reservadas)>5){
                    $errores="No puedes reservar más de 5 butacas";
                } else {
                    $reserva=new Reserva($butacas, $_SESSION['login']->email);
                    $reserva->guardarReserva();
                }

                if (isset($errores)){
                    $_SESSION['errores'] = $errores;
                } else {
                    $_SESSION['mensaje']="Gracias por reservar en nuestra página.";
                }

                Utils::deleteSession('seleccionadas');
                header("location: ".BASE_URL."reserva/verReservas/");
            }

        } else {
            $_SESSION['errores'] = "Debes iniciar sesión antes para hacer una reserva";
            header("location: ".BASE_URL.'usuario/login/');
        }
    }

    /**
     * Muestra las reservas realizadas por el usuario.
     */
    public function verReservas(){
        if (isset($_SESSION['login'])){
            $reserva=new Reserva([], $_SESSION['login']->email);
            $reservas=$reserva->getReservas();
            $this->pages->render("/reserva/verReservas", ['reservas'=>$reservas]);
        } else {
            header("location: ".BASE_URL.'usuario/login/');
        }
    }

    /**
     * Anula una reserva de butaca específica.
     */
    public function anularReserva(){
        if (isset($_GET['butaca'])){
            $butaca = $_GET['butaca'];

            $reserva=new Reserva([], $_SESSION['login']->email);
            $reserva->anularReserva($butaca);
            $_SESSION['mensaje']="Reserva anulada correctamente";
            header("location: ".BASE_URL.'reserva/verReservas/');
        }
    }

    /**
     * Genera un archivo PDF con un resumen de las reservas del usuario.
     */
    public function generarPdf(){
        $reserva = new Reserva([], $_SESSION['login']->email);
        $reservas = $reserva->getReservas();

        // Crear una objeto de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFillColor(64, 64, 64); // Color de fondo del encabezado
        $pdf->SetTextColor(255, 255, 255); // Color de texto del encabezado
        $pdf->SetFont('Arial', 'B', 20); // Tipo de letra, negrita y tamaño para el título
        $pdf->Cell(0, 15, 'Resumen de Reservas', 0, 1, 'C', true);


        $pdf->SetTextColor(0, 0, 0); // Restablecer color de texto
        // Información de la película
        $pdf->SetFont('Arial', 'BU', 17);
        $pdf->Ln(8);
        $pdf->Cell(0, 10, 'Lo Imposible', 0, 1, 'C');
        $pdf->SetFont('Arial', 'I', 14);
        $pdf->Cell(0, 10, 'Sala: 4 - Hora: 18:30', 0, 1, 'C');
        $pdf->Ln(10); // Espaciado después de la información de la película

        // Contenido del PDF
        $pdf->SetFont('Arial', 'B', 12,);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->setX(30);

        // Encabezados de columnas
        $pdf->Cell(40, 10, 'Butaca', 1, 0, 'C', 'gray');
        $pdf->Cell(40, 10, 'Fila', 1, 0, 'C', 'gray');
        $pdf->Cell(40, 10, 'Asiento', 1, 0, 'C', 'gray');
        $pdf->Cell(40, 10, 'Precio', 1, 1, 'C', 'gray');

        $pdf->SetFont('Arial', '', 12); // Tipo de letra normal para el contenido
        $pdf->SetTextColor(0, 0, 0); // Restablecer color de texto

        $precio=4.99;
        $total=0;

        // Contenido de las reservas
        foreach ($reservas as $reserva) {
            $pdf->setX(30);
            $pdf->Cell(40, 10, $reserva['id'], 1, 0, 'C', false);
            $pdf->Cell(40, 10, $reserva['fila'], 1, 0, 'C', false);
            $pdf->Cell(40, 10, $reserva['columna'], 1, 0, 'C', false);
            $pdf->Cell(40, 10, $precio."$", 1, 1, 'C', false);
            $total+=$precio;
        }

        $pdf->SetFont('Arial', 'B', 15); // Tipo de letra normal para el contenido
        $pdf->SetTextColor(255, 20, 20); // Restablecer color de texto
        $pdf->setX(30);
        $pdf->Cell(160, 10, "Total: ".$total."$", 1, 1, 'C', false);

        // Pie de página
        $pdf->SetTextColor(0, 0, 0); // Restablecer color de texto
        $pdf->SetY(150); // Posicionar el cursor al final de la página
        $pdf->SetFont('Arial', 'BI', 15);
        $pdf->Cell(0, 10, 'Ayala Cinema - Disfruta del cine al mejor precio!!!', 0, 0, 'C');

        // Enviar el PDF al navegador
        $pdf->Output();
    }

}