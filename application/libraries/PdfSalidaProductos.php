<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    // Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class PdfSalidaProductos extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image('images/logo.jpg',21,16,50);
            $this->Ln('15');

            $this->SetFont('Arial','B',12);
            $this->Cell(75);
            $this->Cell(48,18,'CONTROL DE SALIDA DE REPUESTOS',0,0,'C');
            $this->Ln('5');
            
            $this->SetFont('Arial','B',13);
            $this->Cell(75);
            $this->Cell(120,10,'',0,0,'C');
            $this->Ln(5);
       }
       // El pie del pdf
       public function Footer(){
           $this->SetY(-15);
           $this->SetFont('Arial','I',8);
           $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
      }
    }
?>