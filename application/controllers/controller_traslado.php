<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_traslado extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		// Se controla la variable de Session
		$this->load->model('model_comercial');
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));		
	}

	public function exportar_doc_traslado(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfTraslado');

	    // Obtener las variables
	    $data = $this->security->xss_clean($this->uri->segment(3));
	    $data = json_decode($data, true);
	    $almacen_partida = $data[0];
	    $almacen_llegada = $data[1];
	    $fecharegistro = $data[2];

	    /* Formato para la fecha inicial */
        $elementos = explode("-", $fecharegistro);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        $array = array($dia, $mes, $anio);
        $fecharegistro = implode("-", $array);

	    // Creacion del PDF

	    /*
	     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
	     * heredó todos las variables y métodos de fpdf
	    */
        $this->pdf = new PdfTraslado();
	    // Agregamos una página
	    $this->pdf->AddPage();
	    // Define el alias para el número de página que se imprimirá en el pie
	    $this->pdf->AliasNbPages();

	    /* Se define el titulo, márgenes izquierdo, derecho y
	     * el color de relleno predeterminado
	    */
	    $this->pdf->SetTitle("Documento de Traslado");
	    $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);

	    // Se define el formato de fuente: Arial, negritas, tamaño 9
	    $this->pdf->SetFont('Arial', 'B', 7);

	    // Obtener el ultimo id del registro de traslados
	    $this->db->select('id_traslado');
        $this->db->order_by("id_traslado", "asc");
        $query = $this->db->get('traslado');
        if(count($query->result()) > 0){
        	foreach($query->result() as $row){
        		$id_traslado = $row->id_traslado;
        	}
        }
        $id_traslado++;

        // Obtener los nombres del punto de partida y llegada
        if($almacen_partida == 1 && $almacen_llegada == 2){
        	$almacen_partida = "SANTA CLARA";
        	$almacen_llegada = "SANTA ANITA";
        }else if($almacen_partida == 2 && $almacen_llegada == 1){
        	$almacen_partida = "SANTA ANITA";
        	$almacen_llegada = "SANTA CLARA";
        }

	    /*
	     	TITULOS DE COLUMNAS - EJEMPLO
	     	$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
	     	$this->pdf->Cell(245,9,utf8_decode('LISTA DE PROVEEDORES'),'TBR TBL',0,'C','1');
	    */

	    $this->pdf->SetFont('Arial','B',9);
	    $this->pdf->Cell(270,9,utf8_decode('GUIA N° 01 - 000'.$id_traslado),0,0,'C');
	    $this->pdf->Ln(9);
	    
	    // Datos generales del documento de traslado
	    $this->pdf->SetFont('Arial','B',8);
	    $this->pdf->Cell(12,20,utf8_decode("FECHA                : "),'',0,'L','0');
	    $this->pdf->Cell(55,20,"                  ".$fecharegistro,'',0,'L','0');
	    $this->pdf->Ln(8);
	    $this->pdf->Cell(12,20,utf8_decode("SALIDA               : "),'',0,'L','0');
	    $this->pdf->Cell(55,20,"                  "."PLANTA DE ".$almacen_partida,'',0,'L','0');
	    $this->pdf->Ln(8);
	    $this->pdf->Cell(12,20,utf8_decode("DESTINO            : "),'',0,'L','0');
	    $this->pdf->Cell(55,20,"                  "."ALMACEN DE ".$almacen_llegada,'',0,'L','0');
	    $this->pdf->Ln(8);
	    $this->pdf->Cell(12,20,utf8_decode("MOTIVO              : "),'',0,'L','0');
	    $this->pdf->Cell(55,20,"                  "."TRASLADO DEFINITIVO",'',0,'L','0');
	    $this->pdf->Ln(8);
	    $this->pdf->Cell(12,20,utf8_decode("AUTORIZADO    : "),'',0,'L','0');
	    $this->pdf->Cell(55,20,"                  "."SR. RODOLFO QUICHIZ",'',0,'L','0');
		$this->pdf->Ln(20);

		$this->pdf->Cell(10,7,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
        $this->pdf->Cell(85,7,utf8_decode('NOMBRE O DESCRIPCIÓN DEL PRODUCTO'),'BLTR',0,'C','1');
        $this->pdf->Cell(28,7,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
        $this->pdf->Cell(28,7,utf8_decode('UNIDAD MED.'),'BLTR',0,'C','1');
	    $this->pdf->Ln(7);
	    $x = 1;
	    // Obtengo variables de la libreria Cart
		$carrito = $this->cart->contents();
		$this->pdf->SetFont('Arial','B',7);
		foreach ($carrito as $item) {
			$no_producto = $item['name'];
			$id_detalle_producto = $item['id'];
			$cantidad = $item['qty'];

			// Obtener la unidad de medida
	        $this->db->select('id_unidad_medida');
	        $this->db->where('id_detalle_producto',$id_detalle_producto);
	        $query = $this->db->get('producto');
        	foreach($query->result() as $row){
        		$id_unidad_medida = $row->id_unidad_medida;
        	}

        	$this->db->select('nom_uni_med');
	        $this->db->where('id_unidad_medida',$id_unidad_medida);
	        $query = $this->db->get('unidad_medida');
        	foreach($query->result() as $row){
        		$nom_uni_med = $row->nom_uni_med;
        	}

			$this->pdf->Cell(10,7,$x++,'BR BL BT',0,'C',0);
			$this->pdf->Cell(85,7,utf8_decode($no_producto),'BR BT',0,'C',0);
			$this->pdf->Cell(28,7,$cantidad,'BR BT',0,'C',0);
			$this->pdf->Cell(28,7,$nom_uni_med,'BR BT',0,'C',0);
			$this->pdf->Ln(7);
		}

		// Firma de Conformidad
		$this->pdf->Ln(15);
		$this->pdf->Cell(96,20,utf8_decode(" "),'',0,'L','0');
		$this->pdf->Cell(100,20,utf8_decode("........................................................................."),'',0,'L','0');
		$this->pdf->Ln(4);
		$this->pdf->Cell(110,20,utf8_decode(" "),'',0,'L','0');
		$this->pdf->Cell(100,20,utf8_decode("RECIBI CONFORME"),'',0,'L','0');
	    /*
	     * Se manda el pdf al navegador
	     *
	     * $this->pdf->Output(nombredelarchivo, destino);
	     *
	     * I = Muestra el pdf en el navegador
	     * D = Envia el pdf para descarga
	     *
	     */
	    $this->pdf->Output("Documento de Traslado.pdf", 'D');
	}







}
?>