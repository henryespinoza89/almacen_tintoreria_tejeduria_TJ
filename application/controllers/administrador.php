<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrador extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		//Se controla la variable de Session
		$this->load->model('model_usuario');		
		$this->load->model('model_comercial');
		$this->load->model('model_admin');
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));		
	}

	public function index(){
		if($this->model_comercial->existeTipoCambio() == TRUE){
			$data['tipocambio'] = 0;
		}else{
			$data['tipocambio'] = 1;
		}
		$data['almacen']= $this->model_admin->listarAlmacen();
		$data['listacategoria'] = $this->model_comercial->listarCategoria();
		$data['producto']= $this->model_admin->listarProducto();
		$this->load->view('administrador/menu');
		$this->load->view('administrador/registrar_producto_admin',$data);
	}

	public function gestionproductos_admin(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['almacen']= $this->model_admin->listarAlmacen();
			$data['listacategoria'] = $this->model_comercial->listarCategoria();
			$data['producto']= $this->model_admin->listarProducto();
			$this->load->view('administrador/menu');
			$this->load->view('administrador/registrar_producto_admin',$data);
		}
	}

	public function gestionmaquinas_admin(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['maquina']= $this->model_admin->listarMaquinaRegistradas();
			$data['almacen']= $this->model_admin->listarAlmacen();
			$data['estado']= $this->model_admin->listarEstado();
			$this->load->view('administrador/menu');
			$this->load->view('administrador/registrar_maquina_admin',$data);
		}
	}

	public function gestionproveedores_admin(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['almacen']= $this->model_admin->listarAlmacen();
			$data['proveedor']= $this->model_admin->listarProveedores();
			$this->load->view('administrador/menu');
			$this->load->view('administrador/registrar_proveedor_admin',$data);
		}
	}

	public function gestionusuarios_admin(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['almacen']= $this->model_comercial->listarAlmacen();
			$data['listatipousuarios']= $this->model_comercial->listarTipoUsuario();
			$data['usuario']= $this->model_admin->listarUsuario_admin();
			$this->load->view('administrador/menu');
			$this->load->view('administrador/registrar_usuario_admin',$data);
		}
	}

	public function reporte_maquina_admin(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfMaquinas_admin');

	    // Se obtienen los alumnos de la base de datos
	    $maquina = $this->model_admin->listarMaquina_admin();

	    // Creacion del PDF

	    /*
	     * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
	     * heredó todos las variables y métodos de fpdf
	     */
	    $this->pdf = new PdfMaquinas_admin();
	    // Agregamos una página
	    $this->pdf->AddPage();
	    // Define el alias para el número de página que se imprimirá en el pie
	    $this->pdf->AliasNbPages();

	    /* Se define el titulo, márgenes izquierdo, derecho y
	     * el color de relleno predeterminado
	     */
	    $this->pdf->SetTitle("Lista de Máquinas");
	    $this->pdf->SetLeftMargin(16);
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
	    $existe = count($maquina);
  		if($existe > 0){
		    $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
		    //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
		    $this->pdf->Cell(30,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,'MARCA','BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('ESTADO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(32,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(25,9,utf8_decode('ALMACÉN'),'BLTR',0,'C','1');
		    $this->pdf->Cell(50,9,utf8_decode('OBSERVACIÓN'),'BLTR',0,'C','1');
		    $this->pdf->Ln(9);
		    // La variable $x se utiliza para mostrar un número consecutivo
		    $x = 1;
		    foreach ($maquina as $maq) {
		        // se imprime el numero actual y despues se incrementa el valor de $x en uno
		        $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
		        // Se imprimen los datos de cada user
		        //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
		        $this->pdf->Cell(30,8,$maq->nombre_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_marca,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_modelo,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_serie,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_estado_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(32,8,$maq->fe_registro,'BR BT',0,'C',0);
		        $this->pdf->Cell(25,8,$maq->no_almacen,'BR BT',0,'C',0);
		        $this->pdf->Cell(50,8,$maq->observacion_maq,'BR BT',0,'C',0);
		        //Se agrega un salto de linea
		        $this->pdf->Ln(8);
		    }
		}
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
	    $this->pdf->Output("Lista de Máquinas.pdf", 'I');
	}

	public function ad_exportar_producto_excel(){
		//$id_categoria_producto = $this->security->xss_clean($this->uri->segment(3));
		$data['producto'] = $this->model_admin->listarProductos_report_excel();
		$this->load->view('administrador/reportes/report_excel_producto',$data);
	}

	public function reporte_producto_admin(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfProductos');

	    // Se obtienen los alumnos de la base de datos
	    $productos = $this->model_admin->listarProducto_admin();

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
	    $existe = count($productos);
  		if($existe > 0){
  			if($this->input->post('categoria_2') AND $this->input->post('almacen_2')){
	    		$categoria = $this->input->post('categoria_2');
	    		$this->db->select('no_categoria');
		        $this->db->where('id_categoria',$categoria);
		        $query = $this->db->get('categoria');
		        foreach($query->result() as $row){
		            $no_categoria = $row->no_categoria;
		        }
		        $almacen = $this->input->post('almacen_2');
	    		$this->db->select('no_almacen');
		        $this->db->where('id_almacen',$almacen);
		        $query = $this->db->get('almacen');
		        foreach($query->result() as $row){
		            $no_almacen = $row->no_almacen;
		        }
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(121.4,-24,utf8_decode("POR CATEGORIA: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                                 ".$no_categoria,'',0,'L','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(197,-24,utf8_decode("POR ALMACÉN: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                                                                                              ".$no_almacen,'',0,'L','0');
				$this->pdf->Ln(0);
    		}else if($this->input->post('almacen_2')){
    		$almacen = $this->input->post('almacen_2');
    		$this->db->select('no_almacen');
	        $this->db->where('id_almacen',$almacen);
	        $query = $this->db->get('almacen');
	        foreach($query->result() as $row){
	            $no_almacen = $row->no_almacen;
	        }
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(116.4,-24,utf8_decode("POR ALMACÉN: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                             ".$no_almacen,'',0,'L','0');
				$this->pdf->Ln(0);
    		}else if($this->input->post('categoria_2')){
    		$categoria = $this->input->post('categoria_2');
    		$this->db->select('no_categoria');
	        $this->db->where('id_categoria',$categoria);
	        $query = $this->db->get('categoria');
	        foreach($query->result() as $row){
	            $no_categoria = $row->no_categoria;
	        }
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(121.4,-24,utf8_decode("POR CATEGORIA: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                                 ".$no_categoria,'',0,'L','0');
				$this->pdf->Ln(0);
    		}else{
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(125.4,-24,utf8_decode("LISTADO GENERAL "),'',0,'R','0');
				$this->pdf->Ln(0);
    		}

        	$this->pdf->SetFont('Arial', 'B', 7);
        	$this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(25,9,utf8_decode('ID PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(89,9,utf8_decode('NOMBRE O DESCRIPCIÓN DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('CATEGORIA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('PROCEDENCIA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('UNIDAD MED.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('STOCK'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('ALMACÉN'),'BLTR',0,'C','1');
		    $this->pdf->Ln(9);
		    // La variable $x se utiliza para mostrar un número consecutivo
		    $x = 1;
		    foreach ($productos as $prod) {
		        // se imprime el numero actual y despues se incrementa el valor de $x en uno
		        $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
		        // Se imprimen los datos de cada user
		        //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
		        $this->pdf->Cell(25,8,$prod->id_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(89,8,$prod->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,$prod->no_categoria,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,$prod->no_procedencia,'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$prod->unidad_medida,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$prod->stock,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$prod->no_almacen,'BR BT',0,'C',0);
		        //Se agrega un salto de linea
		        $this->pdf->Ln(8);
		    }
		}
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);

        	if($this->input->post('categoria_2') AND $this->input->post('almacen_2')){
	    		$categoria = $this->input->post('categoria_2');
	    		$this->db->select('no_categoria');
		        $this->db->where('id_categoria',$categoria);
		        $query = $this->db->get('categoria');
		        foreach($query->result() as $row){
		            $no_categoria = $row->no_categoria;
		        }
		        $almacen = $this->input->post('almacen_2');
	    		$this->db->select('no_almacen');
		        $this->db->where('id_almacen',$almacen);
		        $query = $this->db->get('almacen');
		        foreach($query->result() as $row){
		            $no_almacen = $row->no_almacen;
		        }
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(21.4,-24,utf8_decode("POR CATEGORIA: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                                 ".$no_categoria,'',0,'L','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(197,-24,utf8_decode("POR ALMACÉN: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                                                                                              ".$no_almacen,'',0,'L','0');
				$this->pdf->Ln(0);
    		}else if($this->input->post('almacen_2')){
    		$almacen = $this->input->post('almacen_2');
    		$this->db->select('no_almacen');
	        $this->db->where('id_almacen',$almacen);
	        $query = $this->db->get('almacen');
	        foreach($query->result() as $row){
	            $no_almacen = $row->no_almacen;
	        }
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(16.4,-24,utf8_decode("POR ALMACÉN: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                             ".$no_almacen,'',0,'L','0');
				$this->pdf->Ln(0);
    		}else if($this->input->post('categoria_2')){
    		$categoria = $this->input->post('categoria_2');
    		$this->db->select('no_categoria');
	        $this->db->where('id_categoria',$categoria);
	        $query = $this->db->get('categoria');
	        foreach($query->result() as $row){
	            $no_categoria = $row->no_categoria;
	        }
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(21.4,-24,utf8_decode("POR CATEGORIA: "),'',0,'R','0');
				$this->pdf->Ln(0);
				$this->pdf->Cell(10,-24,"                                                                                                 ".$no_categoria,'',0,'L','0');
				$this->pdf->Ln(0);
    		}else{
    			$this->pdf->SetFont('Times', 'B', 14);
				$this->pdf->Cell(25.4,-24,utf8_decode("LISTADO GENERAL "),'',0,'R','0');
				$this->pdf->Ln(0);
    		}

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
	    $this->pdf->Output("Lista de Productos.pdf", 'D');
	}

	public function reporte_proveedor_admin(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdf_proveedores');

	    // Se obtienen los alumnos de la base de datos
	    $proveedores = $this->model_admin->listarProveedores();
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new Pdf_proveedores();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Proveedores");
        $this->pdf->SetLeftMargin(22);
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
        $existe = count($proveedores);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(41,9,utf8_decode('RAZÓN SOCIAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(26,9,'RUC','BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('PAÍS'),'BLTR',0,'C','1');
	        $this->pdf->Cell(66,9,utf8_decode('DIRECCIÓN'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('TELÉFONO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(26,9,utf8_decode('ALMACÉN'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        foreach ($proveedores as $proveedor) {
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada proveedor
	            //$this->pdf->Cell(25,5,$proveedor->id_proveedor,'B',0,'L',0);
	            $this->pdf->Cell(41,8,$proveedor->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(26,8,$proveedor->ruc,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($proveedor->pais),'BR BT',0,'C',0);
	            $this->pdf->Cell(66,8,$proveedor->direccion,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$proveedor->telefono1,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,$proveedor->fe_registro,'BR BT',0,'C',0);
	            $this->pdf->Cell(26,8,$proveedor->no_almacen,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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

	public function reporte_usuario_admin(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfUsuarios');

	    // Se obtienen los alumnos de la base de datos
	    $usuarios = $this->model_admin->listarUsuario_admin();
 
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
        $existe = count($usuarios);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
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
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
        $this->pdf->Output("Lista de Usuarios.pdf", 'I');
	}

	public function registrarusuario()
    {
        $this->form_validation->set_rules('nombreusu', 'Nombre del Usuario', 'trim|required|min_length[1]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('apellidousu', 'Apellido del Usuario', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('datacontrasena', 'Contraseña', 'trim|required|min_length[1]|max_length[12]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 15 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_admin->saveUsuario();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Este Usuario ya se encuentra registrado.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function editarusuario(){
		$data['almacen']= $this->model_comercial->listarAlmacen();
		$data['listatipo']= $this->model_comercial->listarTipoUsuario();
		$data['datosuser']= $this->model_comercial->getUserEditar();
		$this->load->view('administrador/actualizar_usuario_admin', $data);
	}

	public function actualizarusuario()
	{
		$this->form_validation->set_rules('editnombres', 'Nombre de Usuario', 'trim|required|min_length[1]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('editapellido', 'Apellido de Usuario', 'trim|required|xss_clean');
		$this->form_validation->set_rules('editusuario', 'Usuario del Usuario', 'trim|required|xss_clean');
		//Mensajes
		$this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
		$this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
		$this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 50 dígitos como máximo.');
		//Delimitadores de ERROR:
		$this->form_validation->set_error_delimiters('<span>', '</span><br>');

		if($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
	        $result = $this->model_admin->actualizaUsuario();
	        // Verificamos que existan resultados
	        if(!$result){
	            //Sí no se encotnraron datos.
	            echo '<span style="color:red"><b>ERROR:</b> Este Usuario no se encuentra registrado.</span>';
	        }else{
	        	//Registramos la sesion del usuario
	        	echo '1';
	        }
		}
	}

	public function UpdatePassword()
	{
		$this->form_validation->set_rules('password', 'Contraseña Actual', 'trim|required|max_length[12]|xss_clean');
		$this->form_validation->set_rules('datacontrasena_actualizar', 'Nueva Contraseña', 'trim|required|max_length[12]|xss_clean');
		//Mensajes
		$this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
		$this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 12 dígitos como máximo.');
		//Delimitadores de ERROR:
		$this->form_validation->set_error_delimiters('<span>', '</span><br>');

		if($this->form_validation->run() == FALSE){
			if($this->input->post('password') == "" AND $this->input->post('datacontrasena_actualizar') == ""){
				echo '<span style="color:red"><b>ERROR:</b> Falta completar los campos. Verifique por favor.</span>';
			}else if($this->input->post('password') == ""){
				echo '<span style="color:red"><b>ERROR:</b> Falta completar el campo Contraseña Actual.</span>';
			}else if($this->input->post('datacontrasena_actualizar') == ""){
				echo '<span style="color:red"><b>ERROR:</b> Falta completar el campo Nueva Contraseña.</span>';
			}
		}else {
			$result = $this->model_admin->UpdatePassword();
	        // Verificamos que existan resultados
	        if(!$result){
	            //Sí no se encotnraron datos.
	            echo '<span style="color:red"><b>ERROR: </b>Validación Incorrecta de Contraseña. Su Contraseña Actual no Coincide.</span>';
	        }else{
	        	//Registramos la sesion del usuario
	        	echo '1';
	        }
		}
	}


	public function gestion_ingreso_producto(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_ingreso_producto');
	}

	public function export_tabla_ingreso_producto(){
		$data['t_ingreso_producto'] = $this->model_admin->getTablaIngresoProducto();
		$this->load->view('administrador/mantenimiento/html_tabla_ingreso_producto',$data);
	}



	public function gestion_detalle_ingreso_producto(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_detalle_ingreso_producto');
	}

	public function export_tabla_detalle_ingreso_producto(){
		$data['t_detalle_ingreso_producto'] = $this->model_admin->getTablaDetalleIngresoProducto();
		$this->load->view('administrador/mantenimiento/html_tabla_detalle_ingreso_producto',$data);
	}


	public function gestion_tipo_cambio(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_tipo_cambio');
	}

	public function export_tabla_tipo_cambio(){
		$data['t_tipo_cambio'] = $this->model_admin->getTablaTipoCambio();
		$this->load->view('administrador/mantenimiento/html_tabla_tipo_cambio',$data);
	}

	/*  SALDOS INICIALES */
	public function gestion_saldos_iniciales(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_saldos_iniciales');
	}

	public function export_tabla_saldos_iniciales(){
		$data['t_saldos_iniciales'] = $this->model_admin->getTablaSaldosIniciales();
		$this->load->view('administrador/mantenimiento/html_tabla_saldos_iniciales',$data);
	}

	/*  SALIDA PRODUCTO */
	public function gestion_salida_producto(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_salida_producto');
	}

	public function export_tabla_salida_producto(){
		$data['t_salida_producto'] = $this->model_admin->getTablaSalidaProducto();
		$this->load->view('administrador/mantenimiento/html_tabla_salida_producto',$data);
	}

	/*  KARDEX PRODUCTO */
	public function gestion_kardex_producto(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_kardex_producto');
	}

	public function export_tabla_kardex_producto(){
		$data['t_kardex_producto'] = $this->model_admin->getTablaKardexProducto();
		$this->load->view('administrador/mantenimiento/html_tabla_kardex_producto',$data);
	}

	/*  DETALLE PRODUCTO */
	public function gestion_detalle_producto(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_detalle_producto');
	}

	public function export_tabla_detalle_producto(){
		$data['t_detalle_producto'] = $this->model_admin->getTablaDetalleProducto();
		$this->load->view('administrador/mantenimiento/html_tabla_detalle_producto',$data);
	}

	/*  DETALLE PRODUCTO */
	public function gestion_producto(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_producto');
	}

	public function export_tabla_producto(){
		$data['t_producto'] = $this->model_admin->getTablaProducto();
		$this->load->view('administrador/mantenimiento/html_tabla_producto',$data);
	}

	/* PROVEEDORES */
	public function gestion_proveedor(){
		$this->load->view('administrador/menu');
		$this->load->view('administrador/mantenimiento/tabla_proveedor');
	}

	public function export_tabla_proveedor(){
		$data['t_proveedor'] = $this->model_admin->getTablaProveedor();
		$this->load->view('administrador/mantenimiento/html_tabla_proveedor',$data);
	}











	public function insert_tabla_detalle_ingreso_producto(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_detalle_ing_prod = trim($datos[0]);
				$unidades = trim($datos[1]);
				$id_detalle_producto = trim($datos[2]);
				$precio = trim($datos[3]);
				$id_ingreso_producto = trim($datos[4]);
	            /* ------------------------------------------ */
				$a_data = array(
								'id_detalle_ing_prod'=>$id_detalle_ing_prod,
								'unidades'=>$unidades,
								'id_detalle_producto'=>$id_detalle_producto,
								'precio'=>$precio,
								'id_ingreso_producto'=>$id_ingreso_producto,
								);
				$id_insert = $this->model_admin->insert_tabla_detalle_ingreso_producto($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_detalle_ingreso_producto', $data);
		}
	}


	public function insert_tabla_ingreso_producto(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_ingreso_producto = trim($datos[0]);
				$id_comprobante = trim($datos[1]);
				$nro_comprobante = trim($datos[2]);
				$fecha = trim($datos[3]);
				$id_moneda = trim($datos[4]);
				$id_proveedor = trim($datos[5]);
				$total = trim($datos[6]);
				$gastos = trim($datos[7]);
				$id_almacen = trim($datos[8]);
				$id_agente = trim($datos[9]);
				$cs_igv = trim($datos[10]);
				$serie_comprobante = trim($datos[11]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_ingreso_producto'=>$id_ingreso_producto,
								'id_comprobante'=>$id_comprobante,
								'nro_comprobante'=>$nro_comprobante,
								'fecha'=>$fecha,
								'id_moneda'=>$id_moneda,
								'id_proveedor'=>$id_proveedor,
								'total'=>$total,
								'gastos'=>$gastos,
								'id_almacen'=>$id_almacen,
								'id_agente'=>$id_agente,
								'cs_igv'=>$cs_igv,
								'serie_comprobante'=>$serie_comprobante,
								);
				$id_insert = $this->model_admin->insert_tabla_ingreso_producto($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_ingreso_producto', $data);
		}
	}

	public function insert_tabla_tipo_cambio(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_tipo_cambio = trim($datos[0]);
				$fecha_actual = trim($datos[1]);
				$dolar_compra = trim($datos[2]);
				$dolar_venta = trim($datos[3]);
				$euro_compra = trim($datos[4]);
				$euro_venta = trim($datos[5]);
				$fr_compra = trim($datos[6]);
				$fr_venta = trim($datos[7]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_tipo_cambio'=>$id_tipo_cambio,
								'fecha_actual'=>$fecha_actual,
								'dolar_compra'=>$dolar_compra,
								'dolar_venta'=>$dolar_venta,
								'euro_compra'=>$euro_compra,
								'euro_venta'=>$euro_venta,
								'fr_compra'=>$fr_compra,
								'fr_venta'=>$fr_venta,
								);
				$id_insert = $this->model_admin->insert_tabla_tipo_cambio($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_tipo_cambio', $data);
		}
	}

	public function insert_tabla_saldos_iniciales(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_saldos_iniciales = trim($datos[0]);
				$id_pro = trim($datos[1]);
				$fecha_cierre = trim($datos[2]);
				$stock_inicial = trim($datos[3]);
				$precio_uni_inicial = trim($datos[4]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_saldos_iniciales'=>$id_saldos_iniciales,
								'id_pro'=>$id_pro,
								'fecha_cierre'=>$fecha_cierre,
								'stock_inicial'=>$stock_inicial,
								'precio_uni_inicial'=>$precio_uni_inicial,
								);
				$id_insert = $this->model_admin->insert_tabla_saldos_iniciales($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_saldos_iniciales', $data);
		}
	}

	public function insert_tabla_salida_producto(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_salida_producto = trim($datos[0]);
				$id_area = trim($datos[1]);
				$solicitante = trim($datos[2]);
				$fecha = trim($datos[3]);
				$id_detalle_producto = trim($datos[4]);
				$cantidad_salida = trim($datos[5]);
				$id_almacen = trim($datos[6]);
				$p_u_salida = trim($datos[7]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_salida_producto'=>$id_salida_producto,
								'id_area'=>$id_area,
								'solicitante'=>$solicitante,
								'fecha'=>$fecha,
								'id_detalle_producto'=>$id_detalle_producto,
								'cantidad_salida'=>$cantidad_salida,
								'id_almacen'=>$id_almacen,
								'p_u_salida'=>$p_u_salida,
								);
				$id_insert = $this->model_admin->insert_tabla_salida_producto($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_salida_producto', $data);
		}
	}

	public function insert_tabla_kardex_producto(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_kardex_producto = trim($datos[0]);
				$descripcion = trim($datos[1]);
				$id_detalle_producto = trim($datos[2]);
				$stock_anterior = trim($datos[3]);
				if($datos[4] == ""){
					$precio_unitario_anterior = 0;
				}else{
					$precio_unitario_anterior = trim($datos[4]);
				}
				if($datos[5] == ""){
					$cantidad_salida = 0;
				}else{
					$cantidad_salida = trim($datos[5]);
				}
				$stock_actual = trim($datos[6]);
				$precio_unitario_actual = trim($datos[7]);
				$fecha_registro = trim($datos[8]);
				if($datos[9] == ""){
					$cantidad_ingreso = 0;
				}else{
					$cantidad_ingreso = trim($datos[9]);
				}
				if($datos[10] == ""){
					$precio_unitario_actual_promedio = 0;
				}else{
					$precio_unitario_actual_promedio = trim($datos[10]);
				}
				$serie_comprobante = trim($datos[11]);
				$num_comprobante = trim($datos[12]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_kardex_producto'=>$id_kardex_producto,
								'descripcion'=>$descripcion,
								'id_detalle_producto'=>$id_detalle_producto,
								'stock_anterior'=>$stock_anterior,
								'precio_unitario_anterior'=>$precio_unitario_anterior,
								'cantidad_salida'=>$cantidad_salida,
								'stock_actual'=>$stock_actual,
								'precio_unitario_actual'=>$precio_unitario_actual,
								'fecha_registro'=>$fecha_registro,
								'cantidad_ingreso'=>$cantidad_ingreso,
								'precio_unitario_actual_promedio'=>$precio_unitario_actual_promedio,
								'serie_comprobante'=>$serie_comprobante,
								'num_comprobante'=>$num_comprobante,
								);
				$id_insert = $this->model_admin->insert_tabla_kardex_producto($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_salida_producto', $data);
		}
	}


	public function insert_tabla_detalle_producto(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_detalle_producto = trim($datos[0]);
				$no_producto = utf8_decode(trim($datos[1]));
				$stock = trim($datos[2]);
				$precio_unitario = trim($datos[3]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_detalle_producto'=>$id_detalle_producto,
								'no_producto'=>$no_producto,
								'stock'=>$stock,
								'precio_unitario'=>$precio_unitario,
								);
				$id_insert = $this->model_admin->insert_tabla_detalle_producto($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_detalle_producto', $data);
		}
	}

	
	public function insert_tabla_producto(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_pro = trim($datos[0]);
				$id_producto = trim($datos[1]);
				$observacion = trim($datos[2]);
				$id_almacen = trim($datos[3]);
				$id_procedencia = trim($datos[4]);
				$id_categoria = trim($datos[5]);
				$id_detalle_producto = trim($datos[6]);
				$id_tipo_producto = trim($datos[7]);
				$id_unidad_medida = trim($datos[8]);
				$estado = trim($datos[9]);
				$column_temp = trim($datos[10]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_pro'=>$id_pro,
								'id_producto'=>$id_producto,
								'observacion'=>$observacion,
								'id_almacen'=>$id_almacen,
								'id_procedencia'=>$id_procedencia,
								'id_categoria'=>$id_categoria,
								'id_detalle_producto'=>$id_detalle_producto,
								'id_tipo_producto'=>$id_tipo_producto,
								'id_unidad_medida'=>$id_unidad_medida,
								'estado'=>$estado,
								'column_temp'=>$column_temp,
								);
				$id_insert = $this->model_admin->insert_tabla_producto($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_producto', $data);
		}
	}

	public function insert_tabla_proveedor(){
		$y = 0;
		/* Obtengo las variables generales de la factura */
		$filename = $_FILES['file']['tmp_name'];
		if(($gestor = fopen($filename, "r")) !== FALSE){
			while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
				// Obtener los valores de la hoja de excel
				$id_proveedor = trim($datos[0]);
				$razon_social = utf8_decode(trim($datos[1]));
				$ruc = trim($datos[2]);
				$pais = utf8_decode(trim($datos[3]));
				$departamento = utf8_decode(trim($datos[4]));
				$provincia = utf8_decode(trim($datos[5]));
				$distrito = utf8_decode(trim($datos[6]));
				$direccion = utf8_decode(trim($datos[7]));
				$referencia = utf8_decode(trim($datos[8]));
				$contacto = utf8_decode(trim($datos[9]));
				$cargo = utf8_decode(trim($datos[10]));
				$email = utf8_decode(trim($datos[11]));
				$telefono1 = trim($datos[12]);
				$telefono2 = trim($datos[13]);
				$fax = trim($datos[14]);
				$web = trim($datos[15]);
				$id_almacen = trim($datos[16]);
				$fe_registro = trim($datos[17]);

	            /* ------------------------------------------ */
				$a_data = array(
								'id_proveedor'=>$id_proveedor,
								'razon_social'=>$razon_social,
								'ruc'=>$ruc,
								'pais'=>$pais,
								'departamento'=>$departamento,
								'provincia'=>$provincia,
								'distrito'=>$distrito,
								'direccion'=>$direccion,
								'referencia'=>$referencia,
								'contacto'=>$contacto,
								'cargo'=>$cargo,
								'email'=>$email,
								'telefono1'=>$telefono1,
								'telefono2'=>$telefono2,
								'fax'=>$fax,
								'web'=>$web,
								'id_almacen'=>$id_almacen,
								'fe_registro'=>$fe_registro,
								);
				$id_insert = $this->model_admin->insert_tabla_proveedor($a_data);
				if($id_insert == true){
					$y = $y + 1;
				}
			}
		}
		

		if($y != 0){
			$data['respuesta_registro_satisfactorio'] = $y;
			$this->load->view('administrador/menu');
			$this->load->view('administrador/mantenimiento/tabla_producto', $data);
		}
	}





}
?>