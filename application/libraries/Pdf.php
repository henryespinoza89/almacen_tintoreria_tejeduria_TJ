<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            //$this->Image('images/logo.jpg',24,12,72);

            $this->Image('images/logo.jpg',21,10,72);
            $this->Image('images/titulo_proveedor.jpg',205,22,72);
            //$this->Image('images/pdf_proveedores.jpg',205,8,72);
            $this->Image('images/marco_3.jpg',21,29,256);
            $this->Ln('7');
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