<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class PdfSalidas extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image('images/logo.jpg',11,10,72);
            //$this->Image('images/titulo_maquina.jpg',105,14,72);
            $this->Image('images/titulo_salida.jpg',182,21,100);
            $this->Image('images/marco_3.jpg',11,29,270);
            $this->Ln('7');
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