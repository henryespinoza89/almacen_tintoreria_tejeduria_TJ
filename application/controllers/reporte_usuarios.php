<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Reporte_usuarios extends CI_Controller {
 
    public function index()
    {
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfUsuarios');
 
        // Se obtienen los alumnos de la base de datos
        $usuarios = $this->model_comercial->listarUsuario();
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfUsuarios();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Usuarios");
        $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);
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
        $this->pdf->Cell(35,9,utf8_decode('NOMBRE'),'BLTR',0,'C','1');
        $this->pdf->Cell(35,9,'APELLIDO','BLTR',0,'C','1');
        $this->pdf->Cell(30,9,utf8_decode('ESTADO'),'BLTR',0,'C','1');
        $this->pdf->Cell(30,9,utf8_decode('USUARIO'),'BLTR',0,'C','1');
        $this->pdf->Cell(40,9,utf8_decode('TIPO DE USUARIO'),'BLTR',0,'C','1');
        $this->pdf->Cell(35,9,utf8_decode('ALMACÉN'),'BLTR',0,'C','1');
        $this->pdf->Cell(37,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
        $this->pdf->Ln(9);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($usuarios as $user) {
            // se imprime el numero actual y despues se incrementa el valor de $x en uno
            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
            // Se imprimen los datos de cada user
            //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
            $this->pdf->Cell(35,8,$user->no_usuario,'BR BT',0,'C',0);
            $this->pdf->Cell(35,8,$user->ape_paterno,'BR BT',0,'C',0);
            $this->pdf->Cell(30,8,$user->fl_estado,'BR BT',0,'C',0);
            $this->pdf->Cell(30,8,$user->tx_usuario,'BR BT',0,'C',0);
            $this->pdf->Cell(40,8,$user->no_tipo_usuario,'BR BT',0,'C',0);
            $this->pdf->Cell(35,8,$user->no_almacen,'BR BT',0,'C',0);
            $this->pdf->Cell(37,8,$user->fe_registro,'BR BT',0,'C',0);
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