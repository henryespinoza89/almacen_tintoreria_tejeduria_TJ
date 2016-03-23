<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Reporte_productos extends CI_Controller {
 
    public function index()
    {
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfProductos');
 
        // Se obtienen los alumnos de la base de datos
        $productos = $this->model_comercial->listarProductoGeneral();
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfProductos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Productos");
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
        $this->pdf->Cell(25,9,utf8_decode('ID PRODUCTO'),'BLTR',0,'C','1');
        $this->pdf->Cell(90,9,utf8_decode('NOMBRE O DESCRIPCIÓN DEL PRODUCTO'),'BLTR',0,'C','1');
        $this->pdf->Cell(24,9,utf8_decode('CATEGORIA'),'BLTR',0,'C','1');
        $this->pdf->Cell(24,9,utf8_decode('PROCEDENCIA'),'BLTR',0,'C','1');
        $this->pdf->Cell(20,9,utf8_decode('UNIDAD MED.'),'BLTR',0,'C','1');
        $this->pdf->Cell(25,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
        $this->pdf->Cell(15,9,utf8_decode('STOCK'),'BLTR',0,'C','1');
        $this->pdf->Cell(19,9,utf8_decode('PRECIO UNI.'),'BLTR',0,'C','1');
        $this->pdf->Ln(9);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($productos as $prod) {
            // se imprime el numero actual y despues se incrementa el valor de $x en uno
            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
            // Se imprimen los datos de cada user
            //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
            $this->pdf->Cell(25,8,$prod->id_producto,'BR BT',0,'C',0);
            $this->pdf->Cell(90,8,$prod->no_producto,'BR BT',0,'C',0);
            $this->pdf->Cell(24,8,$prod->no_categoria,'BR BT',0,'C',0);
            $this->pdf->Cell(24,8,$prod->no_procedencia,'BR BT',0,'C',0);
            $this->pdf->Cell(20,8,$prod->unidad_medida,'BR BT',0,'C',0);
            $this->pdf->Cell(25,8,$prod->nombre_maquina,'BR BT',0,'C',0);
            $this->pdf->Cell(15,8,$prod->stock,'BR BT',0,'C',0);
            $this->pdf->Cell(19,8,$prod->precio_unitario,'BR BT',0,'C',0);
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
        $this->pdf->Output("Lista de Productos.pdf", 'I');
    }

}