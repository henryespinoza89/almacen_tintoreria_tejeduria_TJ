<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Reporte_maquinas extends CI_Controller {
 
    public function index()
    {
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfMaquinas');
 
        // Se obtienen los alumnos de la base de datos
        $maquina = $this->model_comercial->listarMaquinaRegistradas();
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfMaquinas();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Máquinas");
        $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 8);
        /*
         * TITULOS DE COLUMNAS
         *
         * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
         */
        /*$this->pdf->Cell(245,9,utf8_decode('LISTA DE PROVEEDORES'),'TBR TBL',0,'C','1');
        $this->pdf->Ln(9);
        */
        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
        $this->pdf->Cell(45,9,utf8_decode('NOMBRE DE MÁQUINA'),'BLTR',0,'C','1');
        $this->pdf->Cell(43,9,'MARCA','BLTR',0,'C','1');
        $this->pdf->Cell(43,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
        $this->pdf->Cell(43,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
        $this->pdf->Cell(30,9,utf8_decode('ESTADO'),'BLTR',0,'C','1');
        //$this->pdf->Cell(50,9,utf8_decode('OBSERVACIÓN'),'BLTR',0,'C','1');
        $this->pdf->Cell(37,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
        $this->pdf->Ln(9);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($maquina as $maq) {
            // se imprime el numero actual y despues se incrementa el valor de $x en uno
            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
            // Se imprimen los datos de cada user
            //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
            $this->pdf->Cell(45,8,$maq->nombre_maquina,'BR BT',0,'C',0);
            $this->pdf->Cell(43,8,$maq->no_marca,'BR BT',0,'C',0);
            $this->pdf->Cell(43,8,$maq->no_modelo,'BR BT',0,'C',0);
            $this->pdf->Cell(43,8,$maq->no_serie,'BR BT',0,'C',0);
            $this->pdf->Cell(30,8,$maq->no_estado_maquina,'BR BT',0,'C',0);
            //$this->pdf->Cell(50,8,$maq->observacion_maq,'BR BT',0,'C',0);
            $this->pdf->Cell(37,8,$maq->fe_registro,'BR BT',0,'C',0);
            //Se agrega un salto de linea
            $this->pdf->Ln(8);
        }
        /*
         * Se manda el pdf al navegador
         *
         * $this->pdf->Output(nombredelarchivo, destino);
         *
         * I = Muestra el pdf en el navegador
         * D = Envia el pdf para descarga
         *
         */
        $this->pdf->Output("Lista de Proveedores.pdf", 'I');
    }
}