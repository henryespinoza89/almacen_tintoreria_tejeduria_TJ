<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    // Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class PdfTraslado extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image('images/logo.jpg',21,16,50);
            $this->Ln('3');

            $this->SetFont('Arial','B',10);
            $this->Cell(75);
            $this->Cell(60,18,'GUIA DE SALIDA ARTICULOS VARIOS',0,0,'C');
            $this->Ln('5');
            
            $this->SetFont('Arial','B',13);
            $this->Cell(75);
            $this->Cell(120,10,'',0,0,'C');
            $this->Ln(10);
       }
       // El pie del pdf
       public function Footer(){
           $this->SetY(-15);
           $this->SetFont('Arial','I',8);
           $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
      }
    }
?>