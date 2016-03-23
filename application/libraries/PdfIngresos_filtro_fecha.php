<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class PdfIngresos_filtro_fecha extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image('images/logo.jpg',11,6,72);
            //$this->Image('images/titulo_maquina.jpg',105,14,72);
            $this->Image('images/titulo_ingreso.jpg',87.5,10,100); // el ultimo valor me sirve para cambiar el tamaño por la izquierda
            //$this->Image('images/titulo_ingreso_por_fecha.jpg',180,18,35); // esta linea esta comentada porque agrege la imagen como texto
            //el ultimo parametro me sirve para agrandar o achicar la imagen
            //el parametro del medio permite mover la imagen de la manera vertical
            //el primer parametro permite mover la imagen de manera horizontal
            $this->Image('images/marco_3.jpg',11,25,273); //el valor del medio me sirve para cambiar el tamaño por la derecha
            $this->Ln('3'); // me sirve para que el margen de la cabecera con la tabla se pueda modificar
            /*
            $this->SetFont('Arial','B',11);
            $this->Cell(75);
            $this->Cell(120,0,'LISTADO DE MAQUINARIAS',0,0,'C');
            $this->Ln('0');
            */

            $this->SetFont('Arial','B',13);
            $this->Cell(75);
            $this->Cell(120,10,'',0,0,'C');
            $this->Ln(20);
       }
       // El pie del pdf
       public function Footer(){
           $this->SetY(-15);
           $this->SetFont('Arial','I',8);
           $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
      }
    }
?>