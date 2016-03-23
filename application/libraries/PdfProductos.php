<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class PdfProductos extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image('images/logo.jpg',11,6,72);
            $this->Image('images/titulo_producto.jpg',100,10,70);
            $this->Image('images/marco_3.jpg',11,25,268);
            $this->Ln('3');
            /*
            $this->Image('images/pdf_productos.jpg',25,10,72);
            $this->Image('images/marco.jpg',24,31,252);
            $this->Ln('9');
            /*
            $this->SetFont('Arial','B',13);
            $this->Cell(75);
            $this->Cell(120,10,'TEJIDOS JORGITO SRL',0,0,'C');
            $this->Ln('5');
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