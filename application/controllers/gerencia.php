<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerencia extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		//Se controla la variable de Session
		$this->load->model('model_usuario');		
		$this->load->model('model_comercial');
		$this->load->model('model_admin');
		$this->load->model('model_gerencia');
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));		
	}

	public function index(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$this->load->view('gerencia/menu');
			$this->load->view('gerencia/inicio');
		}
	}

	public function redirect_store_staClara(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['estado']= $this->model_comercial->listarEstado();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas();
			$this->load->view('gerencia/menu_reportes');
			$this->load->view('gerencia/gestion_reporte_maquina',$data);
		}
	}

	public function redirect_store_tejeduria(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['estado']= $this->model_comercial->listarEstado();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas_tejeduria();
			$this->load->view('gerencia/menu_reportes_tejeduria');
			$this->load->view('gerencia/gestion_reporte_maquina_tejeduria',$data);
		}
	}

	public function redirect_store_hilos(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['estado']= $this->model_comercial->listarEstado();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas_hilos();
			$this->load->view('gerencia/menu_reportes_hilos');
			$this->load->view('gerencia/gestion_reporte_maquina_hilos',$data);
		}
	}

	public function redirect_store_hilos_producto(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaprocedencia'] = $this->model_comercial->listarProcedencia();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$data['listacategoria'] = $this->model_comercial->listarCategoria();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas_hilos();
			$this->load->view('gerencia/menu_reportes_hilos');
			$this->load->view('gerencia/gestion_reporte_producto_hilos',$data);
		}
	}

	public function redirect_store_staClara_producto(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaprocedencia'] = $this->model_comercial->listarProcedencia();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$data['listacategoria'] = $this->model_comercial->listarCategoria();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas();
			$this->load->view('gerencia/menu_reportes');
			$this->load->view('gerencia/gestion_reporte_producto',$data);
		}
	}

	public function redirect_store_tejeduria_producto(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaprocedencia'] = $this->model_comercial->listarProcedencia();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$data['listacategoria'] = $this->model_comercial->listarCategoria();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas_tejeduria();
			$this->load->view('gerencia/menu_reportes_tejeduria');
			$this->load->view('gerencia/gestion_reporte_producto_tejeduria',$data);
		}
	}

	public function redirect_store_tejeduria_proveedor(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$this->load->view('gerencia/menu_reportes_tejeduria');
			$this->load->view('gerencia/gestion_reporte_proveedor_tejeduria');
		}
	}

	public function redirect_store_hilos_proveedor(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$this->load->view('gerencia/menu_reportes_hilos');
			$this->load->view('gerencia/gestion_reporte_proveedor_hilos');
		}
	}

	public function redirect_store_staClara_proveedor(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$this->load->view('gerencia/menu_reportes');
			$this->load->view('gerencia/gestion_reporte_proveedor');
		}
	}

	public function redirect_store_tejeduria_ingreso(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaagente']= $this->model_gerencia->listaAgenteAduana_tejeduria();
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto_tejeduria();
			$data['listaproveedor']= $this->model_gerencia->listaProveedor_tejeduria();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$this->load->view('gerencia/menu_reportes_tejeduria');
			$this->load->view('gerencia/gestion_reporte_ingreso_tejeduria',$data);
		}
	}

	public function redirect_store_hilos_ingreso(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaagente']= $this->model_gerencia->listaAgenteAduana_hilos();
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto_hilos();
			$data['listaproveedor']= $this->model_gerencia->listaProveedor_hilos();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$this->load->view('gerencia/menu_reportes_hilos');
			$this->load->view('gerencia/gestion_reporte_ingreso_hilos',$data);
		}
	}

	public function redirect_store_hilos_ingreso_otros(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto_hilos();
			$data['listaproveedor']= $this->model_gerencia->listaProveedor_hilos();
			$data['listacomprobante']= $this->model_gerencia->listarComprobantes_hilos();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$this->load->view('gerencia/menu_reportes_hilos');
			$this->load->view('gerencia/gestion_reporte_ingreso_otros_hilos',$data);
		}
	}

	public function redirect_store_tejeduria_ingreso_otros(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto_tejeduria();
			$data['listaproveedor']= $this->model_gerencia->listaProveedor_tejeduria();
			$data['listacomprobante']= $this->model_gerencia->listarComprobantes_tejeduria();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$this->load->view('gerencia/menu_reportes_tejeduria');
			$this->load->view('gerencia/gestion_reporte_ingreso_otros_tejeduria',$data);
		}
	}

	public function redirect_store_staClara_ingreso_otros(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto();
			$data['listaproveedor']= $this->model_gerencia->listaProveedor();
			$data['listacomprobante']= $this->model_gerencia->listarComprobantes_staClara();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$this->load->view('gerencia/menu_reportes');
			$this->load->view('gerencia/gestion_reporte_ingreso_otros_staClara',$data);
		}
	}

	public function redirect_store_staClara_ingreso(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaagente']= $this->model_gerencia->listaAgenteAduana();
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto();
			$data['listaproveedor']= $this->model_gerencia->listaProveedor();
			$data['listasimmon']= $this->model_comercial->listaSimMon();
			$this->load->view('gerencia/menu_reportes');
			$this->load->view('gerencia/gestion_reporte_ingreso',$data);
		}
	}

	public function redirect_store_tejeduria_salida(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaarea']= $this->model_gerencia->listarArea_tejeduria();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas_tejeduria();
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto_tejeduria();
			$this->load->view('gerencia/menu_reportes_tejeduria');
			$this->load->view('gerencia/gestion_reporte_salida_tejeduria',$data);
		}
	}

	public function redirect_store_staClara_salida(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaarea']= $this->model_gerencia->listarArea();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas();
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto();
			$this->load->view('gerencia/menu_reportes');
			$this->load->view('gerencia/gestion_reporte_salida',$data);
		}
	}

	public function redirect_store_hilos_salida(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['listaarea']= $this->model_gerencia->listarArea_hilos();
			$data['listamaquina']= $this->model_gerencia->listarMaquinas_hilos();
			$data['listanombreproducto']= $this->model_gerencia->listaNombreProducto_hilos();
			$this->load->view('gerencia/menu_reportes_hilos');
			$this->load->view('gerencia/gestion_reporte_salida_hilos',$data);
		}
	}

	public function traeMarca()
	{
        $marca = $this->model_gerencia->getMarca();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($marca as $fila)
        {
        	echo '<option value="'.$fila->id_marca_maquina.'">'.$fila->no_marca.'</option>';
	    }
	}

	public function traeMarca_tejeduria()
	{
        $marca = $this->model_gerencia->getMarca_tejeduria();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($marca as $fila)
        {
        	echo '<option value="'.$fila->id_marca_maquina.'">'.$fila->no_marca.'</option>';
	    }
	}

	public function traeMarca_hilos()
	{
        $marca = $this->model_gerencia->getMarca_hilos();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($marca as $fila)
        {
        	echo '<option value="'.$fila->id_marca_maquina.'">'.$fila->no_marca.'</option>';
	    }
	}

	public function traeModelo()
	{
        $modelo = $this->model_gerencia->getModelo();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($modelo as $fila)
        {
        	echo '<option value="'.$fila->id_modelo_maquina.'">'.$fila->no_modelo.'</option>';
	    }
	}

	public function traeModelo_tejeduria()
	{
        $modelo = $this->model_gerencia->getModelo_tejeduria();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($modelo as $fila)
        {
        	echo '<option value="'.$fila->id_modelo_maquina.'">'.$fila->no_modelo.'</option>';
	    }
	}

	public function traeModelo_hilos()
	{
        $modelo = $this->model_gerencia->getModelo_hilos();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($modelo as $fila)
        {
        	echo '<option value="'.$fila->id_modelo_maquina.'">'.$fila->no_modelo.'</option>';
	    }
	}

	public function traeSerie()
	{
        $modelo = $this->model_gerencia->getSerie();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($modelo as $fila)
        {
        	echo '<option value="'.$fila->id_serie_maquina.'">'.$fila->no_serie.'</option>';
	    }
	}

	public function traeSerie_tejeduria()
	{
        $modelo = $this->model_gerencia->getSerie_tejeduria();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($modelo as $fila)
        {
        	echo '<option value="'.$fila->id_serie_maquina.'">'.$fila->no_serie.'</option>';
	    }
	}

	public function traeSerie_hilos()
	{
        $modelo = $this->model_gerencia->getSerie_hilos();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($modelo as $fila)
        {
        	echo '<option value="'.$fila->id_serie_maquina.'">'.$fila->no_serie.'</option>';
	    }
	}

	public function reportemaquinaspdf(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfMaquinas');

	    // Se obtienen los alumnos de la base de datos
	    $maquina = $this->model_gerencia->listarMaquinaFiltroPdf();

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
		    $this->pdf->Cell(35,9,utf8_decode('NOMBRE DE MÁQUINA'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,'MARCA','BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('ESTADO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(50,9,utf8_decode('OBSERVACIÓN'),'BLTR',0,'C','1');
		    $this->pdf->Cell(37,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
		    $this->pdf->Ln(9);
		    // La variable $x se utiliza para mostrar un número consecutivo
		    $x = 1;
		    foreach ($maquina as $maq) {
		        // se imprime el numero actual y despues se incrementa el valor de $x en uno
		        $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
		        // Se imprimen los datos de cada user
		        //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
		        $this->pdf->Cell(35,8,$maq->nombre_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_marca,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_modelo,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_serie,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_estado_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(50,8,$maq->observacion_maq,'BR BT',0,'C',0);
		        $this->pdf->Cell(37,8,$maq->fe_registro,'BR BT',0,'C',0);
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

	public function reportemaquinaspdf_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfMaquinas');

	    // Se obtienen los alumnos de la base de datos
	    $maquina = $this->model_gerencia->listarMaquinaFiltroPdf_tejeduria();

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
		    $this->pdf->Cell(35,9,utf8_decode('NOMBRE DE MÁQUINA'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,'MARCA','BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('ESTADO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(50,9,utf8_decode('OBSERVACIÓN'),'BLTR',0,'C','1');
		    $this->pdf->Cell(37,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
		    $this->pdf->Ln(9);
		    // La variable $x se utiliza para mostrar un número consecutivo
		    $x = 1;
		    foreach ($maquina as $maq) {
		        // se imprime el numero actual y despues se incrementa el valor de $x en uno
		        $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
		        // Se imprimen los datos de cada user
		        //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
		        $this->pdf->Cell(35,8,$maq->nombre_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_marca,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_modelo,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_serie,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_estado_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(50,8,utf8_decode($maq->observacion_maq),'BR BT',0,'C',0);
		        $this->pdf->Cell(37,8,$maq->fe_registro,'BR BT',0,'C',0);
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

	public function reportemaquinaspdf_hilos(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfMaquinas');

	    // Se obtienen los alumnos de la base de datos
	    $maquina = $this->model_gerencia->listarMaquinaFiltroPdf_hilos();

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
		    $this->pdf->Cell(35,9,utf8_decode('NOMBRE DE MÁQUINA'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,'MARCA','BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
		    $this->pdf->Cell(30,9,utf8_decode('ESTADO'),'BLTR',0,'C','1');
		    $this->pdf->Cell(50,9,utf8_decode('OBSERVACIÓN'),'BLTR',0,'C','1');
		    $this->pdf->Cell(37,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
		    $this->pdf->Ln(9);
		    // La variable $x se utiliza para mostrar un número consecutivo
		    $x = 1;
		    foreach ($maquina as $maq) {
		        // se imprime el numero actual y despues se incrementa el valor de $x en uno
		        $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
		        // Se imprimen los datos de cada user
		        //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
		        $this->pdf->Cell(35,8,$maq->nombre_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_marca,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_modelo,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_serie,'BR BT',0,'C',0);
		        $this->pdf->Cell(30,8,$maq->no_estado_maquina,'BR BT',0,'C',0);
		        $this->pdf->Cell(50,8,utf8_decode($maq->observacion_maq),'BR BT',0,'C',0);
		        $this->pdf->Cell(37,8,$maq->fe_registro,'BR BT',0,'C',0);
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
	    $this->pdf->Output("Lista de Máquinas.pdf", 'D');
	}

	public function reporteproductospdf(){
		// Se carga el modelo alumno
        //$this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfProductos');
 
        // Se obtienen los productos de la base de datos
        $productos = $this->model_gerencia->listarProductoFiltro();
        // se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }

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
	        $suma_soles = 0;
	        foreach ($productos as $prod) {
	        	$suma_soles = $suma_soles + ($prod->stock*$prod->precio_unitario);
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
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	        /*$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);*/
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
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
        $this->pdf->Output("Lista de Productos.pdf", 'I');
	}

	public function reporteproductospdf_tejeduria(){
		// Se carga el modelo alumno
        //$this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfProductos');
 
        // Se obtienen los productos de la base de datos
        $productos = $this->model_gerencia->listarProductoFiltro_tejeduria();
        // se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }

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
	        $suma_soles = 0;
	        foreach ($productos as $prod) {
	        	$suma_soles = $suma_soles + ($prod->stock*$prod->precio_unitario);
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
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	        /*$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);*/
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
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
        $this->pdf->Output("Lista de Productos.pdf", 'I');
	}

	public function reporteproductospdf_hilos(){
		// Se carga el modelo alumno
        //$this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfProductos');
 
        // Se obtienen los productos de la base de datos
        $productos_sta_clara = $this->model_gerencia->listarProductoFiltro_hilos();
        // se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }

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
        $existe = count($productos_sta_clara);
  		if($existe > 0){ 
        	$this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(25,9,utf8_decode('ID PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(90,9,utf8_decode('NOMBRE O DESCRIPCIÓN DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('CATEGORIA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('PROCEDENCIA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('UNIDAD MED.'),'BLTR',0,'C','1');
	        //$this->pdf->Cell(25,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(15,9,utf8_decode('STOCK'),'BLTR',0,'C','1');
	        $this->pdf->Cell(19,9,utf8_decode('PRECIO UNI.'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma_soles = 0;
	        foreach ($productos_sta_clara as $prod) {
	        	$suma_soles = $suma_soles + ($prod->stock*$prod->precio_unitario);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada user
	            //$this->pdf->Cell(25,5,$user->id_user,'B',0,'L',0);
	            $this->pdf->Cell(25,8,$prod->id_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(90,8,$prod->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$prod->no_categoria,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$prod->no_procedencia,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$prod->unidad_medida,'BR BT',0,'C',0);
	            //$this->pdf->Cell(25,8,$prod->nombre_maquina,'BR BT',0,'C',0);
	            $this->pdf->Cell(15,8,$prod->stock,'BR BT',0,'C',0);
	            $this->pdf->Cell(19,8,$prod->precio_unitario,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	        /*$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);*/
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
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
        $this->pdf->Output("Lista de Productos.pdf", 'D');
	}


	public function reporteproveedorespdf(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdf');

	    // Se obtienen los alumnos de la base de datos
	    $proveedores = $this->model_gerencia->listarProveedoresFiltroPdf();
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new Pdf();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Proveedores");
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
        $existe = count($proveedores);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(50,9,utf8_decode('RAZÓN SOCIAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,'RUC','BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('PAÍS'),'BLTR',0,'C','1');
	        $this->pdf->Cell(76,9,utf8_decode('DIRECCIÓN'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('TELÉFONO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        foreach ($proveedores as $proveedor) {
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada proveedor
	            //$this->pdf->Cell(25,5,$proveedor->id_proveedor,'B',0,'L',0);
	            $this->pdf->Cell(50,8,$proveedor->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$proveedor->ruc,'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$proveedor->pais,'BR BT',0,'C',0);
	            $this->pdf->Cell(76,8,$proveedor->direccion,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$proveedor->telefono1,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,$proveedor->fe_registro,'BR BT',0,'C',0);
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

	public function reporteproveedorespdf_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdf');

	    // Se obtienen los alumnos de la base de datos
	    $proveedores = $this->model_gerencia->listarProveedoresFiltroPdf_tejeduria();
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new Pdf();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Proveedores");
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
        $existe = count($proveedores);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(50,9,utf8_decode('RAZÓN SOCIAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,'RUC','BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('PAÍS'),'BLTR',0,'C','1');
	        $this->pdf->Cell(76,9,utf8_decode('DIRECCIÓN'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('TELÉFONO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        foreach ($proveedores as $proveedor) {
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada proveedor
	            //$this->pdf->Cell(25,5,$proveedor->id_proveedor,'B',0,'L',0);
	            $this->pdf->Cell(50,8,$proveedor->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$proveedor->ruc,'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$proveedor->pais,'BR BT',0,'C',0);
	            $this->pdf->Cell(76,8,$proveedor->direccion,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$proveedor->telefono1,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,$proveedor->fe_registro,'BR BT',0,'C',0);
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

	public function reporteproveedorespdf_hilos(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdf');

	    // Se obtienen los alumnos de la base de datos
	    $proveedores = $this->model_gerencia->listarProveedoresFiltroPdf_hilos();
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new Pdf();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Proveedores");
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
        $existe = count($proveedores);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(50,9,utf8_decode('RAZÓN SOCIAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,'RUC','BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('PAÍS'),'BLTR',0,'C','1');
	        $this->pdf->Cell(76,9,utf8_decode('DIRECCIÓN'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('TELÉFONO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        foreach ($proveedores as $proveedor) {
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada proveedor
	            //$this->pdf->Cell(25,5,$proveedor->id_proveedor,'B',0,'L',0);
	            $this->pdf->Cell(50,8,utf8_decode($proveedor->razon_social),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$proveedor->ruc,'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,utf8_decode($proveedor->pais),'BR BT',0,'C',0);
	            $this->pdf->Cell(76,8,utf8_decode($proveedor->direccion),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$proveedor->telefono1,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,$proveedor->fe_registro,'BR BT',0,'C',0);
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
        $this->pdf->Output("Lista de Proveedores.pdf", 'D');
	}

	public function reporteingresospdf(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistrosFiltroPdf();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(27,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(34,9,utf8_decode('N° DE COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(53,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(33,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(19,9,utf8_decode('IGV'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('GASTOS'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        // Creo las variables contenedoras de la suma
	        $sum_total = 0;
	        $suma_soles = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->total * $dolar_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'EURO'){
	        		$convert_soles = $reg->total * $euro_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'FRANCO SUIZO'){
	        		$convert_soles = $reg->total * $fr_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else{
	        		$suma_soles = $suma_soles + $reg->total;
	        	}
	        	$gasto_aduana = $reg->total * $reg->gastos;
	        	$sub_total = $reg->total / 1.18;
	        	$igv = $reg->total - $sub_total;
	        	$sum_total = $sum_total + $reg->total;
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            $this->pdf->Cell(27,8,$reg->no_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(34,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(53,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(33,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(19,8,@number_format($igv, 2, '.', ''),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->total,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$gasto_aduana,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	    	$this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}
	        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingresospdf_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistrosFiltroPdf_tejeduria();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(27,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(34,9,utf8_decode('N° DE COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(53,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(33,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(19,9,utf8_decode('IGV'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('GASTOS'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        // Creo las variables contenedoras de la suma
	        $sum_total = 0;
	        $suma_soles = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->total * $dolar_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'EURO'){
	        		$convert_soles = $reg->total * $euro_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'FRANCO SUIZO'){
	        		$convert_soles = $reg->total * $fr_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else{
	        		$suma_soles = $suma_soles + $reg->total;
	        	}
	        	$gasto_aduana = $reg->total * $reg->gastos;
	        	$sub_total = $reg->total / 1.18;
	        	$igv = $reg->total - $sub_total;
	        	$sum_total = $sum_total + $reg->total;
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            $this->pdf->Cell(27,8,$reg->no_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(34,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(53,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(33,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(19,8,@number_format($igv, 2, '.', ''),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->total,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$gasto_aduana,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	    	$this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}
	        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingresospdf_hilos(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');
	    $this->load->library('pdfIngresos_filtro_fecha');
	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistrosFiltroPdf_hilos();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        if($this->input->post('fecharegistro')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else if($this->input->post('fechainicial') && $this->input->post('fechafinal')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else if($this->input->post('moneda')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else if($this->input->post('proveedor')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else{
        	$this->pdf = new PdfIngresos();
        }
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        $existe = count($reg_ingresos);
  		if($existe > 0){
  			if($this->input->post('fecharegistro')){
        		$fecha_registro = $this->input->post('fecharegistro');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(201.5,-24,"POR FECHA: ".$fecha_registro,'',0,'C','0');
  				$this->pdf->Ln(0);
        	}else if($this->input->post('fechainicial') && $this->input->post('fechafinal')){
        		$fecha_inicial = $this->input->post('fechainicial');
        		$fecha_final = $this->input->post('fechafinal');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(216.5,-24,"DEL: ".$fecha_inicial." AL: ".$fecha_final,'',0,'C','0');
  				$this->pdf->Ln(0);
        	}else if($this->input->post('moneda')){
        		$moneda = $this->input->post('moneda');
        		$this->db->select('no_moneda,simbolo_mon');
		        $this->db->where('id_moneda',$moneda);
		        $query = $this->db->get('moneda');
		        foreach($query->result() as $row){
		            $no_moneda = $row->no_moneda;
		            $simbolo_mon = $row->simbolo_mon;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(183.5,-24,"POR MONEDA: ",'',0,'C','0');
  				$this->pdf->Ln(0);
  				$this->pdf->Cell(191,-24,"                                                                                         ".$no_moneda,'','L','L','0');
  				$this->pdf->Ln(0);
  				/*$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);*/
        	}else if($this->input->post('proveedor')){
        		$proveedor = $this->input->post('proveedor');
        		$this->db->select('razon_social');
		        $this->db->where('id_proveedor',$proveedor);
		        $query = $this->db->get('proveedor');
		        foreach($query->result() as $row){
		            $razon_social = $row->razon_social;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(192.5,-24,"POR PROVEEDOR: ",'',0,'C','0');
  				$this->pdf->Ln(0);
  				$this->pdf->Cell(191,-24,"                                                                                                ".$razon_social,'','L','L','0');
  				$this->pdf->Ln(0);
        	}
        	$this->pdf->SetFont('Arial', 'B', 7);
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(27,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(34,9,utf8_decode('N° DE COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(88,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(33,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(19,9,utf8_decode('IGV'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(15,9,utf8_decode('GASTOS'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        // Creo las variables contenedoras de la suma
	        $sum_total = 0;
	        $suma_soles = 0;
	        $suma_dolares = 0;
	        $suma_euros = 0;
	        $suma_libras = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		//$convert_soles = $reg->total * $dolar_venta_fechaR;
	        		//$suma_soles = $suma_soles + $convert_soles;
	        		$suma_dolares = $suma_dolares + $reg->total;
	        	}else if($reg->no_moneda == 'EURO'){
	        		//$convert_soles = $reg->total * $euro_venta_fechaR;
	        		//$suma_soles = $suma_soles + $convert_soles;
	        		$suma_euros = $suma_euros + $reg->total;
	        	}else if($reg->no_moneda == 'FRANCO SUIZO'){
	        		//$convert_soles = $reg->total * $fr_venta_fechaR;
	        		//$suma_soles = $suma_soles + $convert_soles;
	        		$suma_libras = $suma_libras + $reg->total;
	        	}else{
	        		$suma_soles = $suma_soles + $reg->total;
	        	}
	        	$gasto_aduana = $reg->total * $reg->gastos;
	        	$sub_total = $reg->total / 1.18;
	        	$igv = $reg->total - $sub_total;
	        	//$sub_total = $reg->total - $igv;
	        	//$igv = $reg->total - $sub_total;
	        	$sum_total = $sum_total + $reg->total;
	        	//$suma_soles_sinigv = $suma_soles - ($suma_soles*0.18);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,6,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            $this->pdf->Cell(27,6,$reg->no_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(34,6,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(88,6,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(33,6,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,6,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(19,6,@number_format($igv, 2, '.', ''),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,6,$reg->total,'BR BT',0,'C',0);
	            $this->pdf->Cell(15,6,$gasto_aduana,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(6);
	        }
	    	$this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
        	if($this->input->post('fecharegistro')){
        		$fecha_registro = $this->input->post('fecharegistro');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(1.5,-24,"POR FECHA: ".$fecha_registro,'',0,'C','0');
        	}else if($this->input->post('fechainicial') && $this->input->post('fechafinal')){
        		$fecha_inicial = $this->input->post('fechainicial');
        		$fecha_final = $this->input->post('fechafinal');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(17,-24,"DEL: ".$fecha_inicial." AL: ".$fecha_final,'',0,'C','0');
        	}else if($this->input->post('moneda')){
        		$moneda = $this->input->post('moneda');
        		$this->db->select('no_moneda,simbolo_mon');
		        $this->db->where('id_moneda',$moneda);
		        $query = $this->db->get('moneda');
		        foreach($query->result() as $row){
		            $no_moneda = $row->no_moneda;
		            $simbolo_mon = $row->simbolo_mon;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(-16,-24,"POR MONEDA: ",'',0,'C','0');
  				$this->pdf->Ln(0);
  				$this->pdf->Cell(191,-24,"                                                                                         ".$no_moneda,'','L','L','0');
  				$this->pdf->Ln(0);
        	}else if($this->input->post('proveedor')){
        		$proveedor = $this->input->post('proveedor');
        		$this->db->select('razon_social');
		        $this->db->where('id_proveedor',$proveedor);
		        $query = $this->db->get('proveedor');
		        foreach($query->result() as $row){
		            $razon_social = $row->razon_social;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(-7.5,-24,"POR PROVEEDOR: ",'',0,'C','0'); // El segunda parametro permite deslizar el texto de manera vertical (puede ser negativo)
  				$this->pdf->Ln(0);
  				$this->pdf->Cell(191,-24,"                                                                                                ".$razon_social,'','L','L','0');
  				//$this->pdf->Cell(461,-24,$razon_social,'',0,'C','0');
  				$this->pdf->Ln(0);
        	}
  		}
	        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingreso_producto_pdf(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_productoFiltroPdf();
 		// se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(26,9,utf8_decode('N° DE FACTURA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(43,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(30,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(74,9,utf8_decode('NOMBRE DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('UNIDADES'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	$this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->precio * $dolar_venta_fechaR;
	        		$suma = $suma + ($convert_soles * $reg->unidades);
	        	}else if($reg->no_moneda == 'EURO'){
	                $convert_soles = $reg->precio * $euro_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                $convert_soles = $reg->precio * $fr_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else{
	            	$suma = $suma + ($reg->precio * $reg->unidades);	
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(26,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(43,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(30,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(74,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->unidades,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}

        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingreso_producto_pdf_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_productoFiltroPdf_tejeduria();
 		// se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(26,9,utf8_decode('N° DE FACTURA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(43,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(30,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(74,9,utf8_decode('NOMBRE DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('UNIDADES'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	$this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->precio * $dolar_venta_fechaR;
	        		$suma = $suma + ($convert_soles * $reg->unidades);
	        	}else if($reg->no_moneda == 'EURO'){
	                $convert_soles = $reg->precio * $euro_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                $convert_soles = $reg->precio * $fr_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else{
	            	$suma = $suma + ($reg->precio * $reg->unidades);	
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(26,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(43,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(30,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(74,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->unidades,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}

        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingreso_producto_pdf_hilos(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');
	    $this->load->library('pdfIngresos_filtro_fecha');
	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_productoFiltroPdf_hilos();
 		// se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        if($this->input->post('fechainicial_2') && $this->input->post('fechafinal_2')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else if($this->input->post('nomproducto')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else{
        	$this->pdf = new PdfIngresos();
        }
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
  			if($this->input->post('fechainicial_2') && $this->input->post('fechafinal_2')){
  				$fecha_inicial_2 = $this->input->post('fechainicial_2');
        		$fecha_final_2 = $this->input->post('fechafinal_2');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(213.5,-24,"DEL ".$fecha_inicial_2." AL ".$fecha_final_2,'',0,'C','0');
  				$this->pdf->Ln(0);
  			}else if($this->input->post('nomproducto')){
        		$id_detalle_producto = $this->input->post('nomproducto');
        		$this->db->select('no_producto');
		        $this->db->where('id_detalle_producto',$id_detalle_producto);
		        $query = $this->db->get('detalle_producto');
		        foreach($query->result() as $row){
		            $no_producto = $row->no_producto;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(117,-24,"POR PRODUCTO: ",'',0,'R','0');
  				$this->pdf->Ln(0);
  				//$this->pdf->Cell(361,-24,$no_producto,'',0,'C','0');
  				/*$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);*/
  				$this->pdf->Cell(280,-24,"                                                                                             ".$no_producto,'','L','L','0');
  				$this->pdf->Ln(0);
        	}
        	$this->pdf->SetFont('Arial', 'B', 7);
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(25,9,utf8_decode('N° DE FACTURA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(15,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(78,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(30,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(77,9,utf8_decode('NOMBRE DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(16,9,utf8_decode('UNIDADES'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        $suma_soles = 0;
	        $suma_dolares = 0;
	        $suma_euros = 0;
	        $suma_libras = 0;
	        foreach ($reg_ingresos as $reg) {
	        	$this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		//$convert_soles = $reg->precio * $dolar_venta_fechaR;
	        		///$suma = $suma + ($convert_soles * $reg->unidades);
	        		$suma_dolares = $suma_dolares + ($reg->precio * $reg->unidades);
	        	}else if($reg->no_moneda == 'EURO'){
	                //$convert_soles = $reg->precio * $euro_venta_fechaR;
	                //$suma = $suma + ($convert_soles * $reg->unidades);
	                $suma_euros = $suma_euros + ($reg->precio * $reg->unidades);
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                //$convert_soles = $reg->precio * $fr_venta_fechaR;
	                //$suma = $suma + ($convert_soles * $reg->unidades);
	                $suma_libras = $suma_libras + ($reg->precio * $reg->unidades);
	            }else{
	            	//$suma = $suma + ($reg->precio * $reg->unidades);
	            	$suma_soles = $suma_soles + ($reg->precio * $reg->unidades);
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,6,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(25,6,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(15,6,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(78,6,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(30,6,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(77,6,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,6,$reg->precio,'BR BT',0,'C',0);
	            $this->pdf->Cell(16,6,$reg->unidades,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(6);
	        }

	        //$suma_dolares = $suma / $dolar_venta;
	        //$suma_euros = $suma / $euro_venta;
	        //$suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
        	if($this->input->post('fechainicial_2') && $this->input->post('fechafinal_2')){
  				$fecha_inicial_2 = $this->input->post('fechainicial_2');
        		$fecha_final_2 = $this->input->post('fechafinal_2');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(13.5,-24,"DEL ".$fecha_inicial_2." AL ".$fecha_final_2,'',0,'C','0');
  				$this->pdf->Ln(0);
  			}else if($this->input->post('nomproducto')){
        		$id_detalle_producto = $this->input->post('nomproducto');
        		$this->db->select('no_producto');
		        $this->db->where('id_detalle_producto',$id_detalle_producto);
		        $query = $this->db->get('detalle_producto');
		        foreach($query->result() as $row){
		            $no_producto = $row->no_producto;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(17,-24,"POR PRODUCTO: ",'',0,'R','0');
  				$this->pdf->Ln(0);
  				//$this->pdf->Cell(361,-24,$no_producto,'',0,'C','0');
  				/*$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);*/
  				$this->pdf->Cell(280,-24,"                                                                                             ".$no_producto,'','L','L','0');
        	}
  		}
        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingreso_agente_pdf(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_agenteFiltroPdf();
	    // se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(26,9,utf8_decode('N° DE FACTURA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(50,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(32,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(36,9,utf8_decode('AGENTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(26,9,utf8_decode('PORC. ASIGNADO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('GASTOS'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// se obtienen los tipos de cambio del día
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }

	        	$porcentaje = $reg->gastos*100;
	        	$gasto_agente = $reg->total * $reg->gastos;

	        	if($reg->no_moneda == 'DOLARES'){
	                  $convert_soles = $gasto_agente * $dolar_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else if($reg->no_moneda == 'EURO'){
	                  $convert_soles = $gasto_agente * $euro_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                  $convert_soles = $gasto_agente * $fr_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else{
	                  $suma = $suma + $gasto_agente;
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            $this->pdf->Cell(26,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(50,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(32,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->total,'BR BT',0,'C',0);
	            $this->pdf->Cell(36,8,$reg->no_agente,'BR BT',0,'C',0);
	            $this->pdf->Cell(26,8,$porcentaje.'%','BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,@number_format($gasto_agente, 2, '.', ''),'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE GASTOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}

        $this->pdf->Output("Lista de Proveedores.pdf", 'I');
	}

	public function reporteingreso_agente_pdf_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_agenteFiltroPdf_tejeduria();
	    // se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(26,9,utf8_decode('N° DE FACTURA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(50,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(32,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(36,9,utf8_decode('AGENTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(26,9,utf8_decode('PORC. ASIGNADO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('GASTOS'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// se obtienen los tipos de cambio del día
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }

	        	$porcentaje = $reg->gastos*100;
	        	$gasto_agente = $reg->total * $reg->gastos;

	        	if($reg->no_moneda == 'DOLARES'){
	                  $convert_soles = $gasto_agente * $dolar_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else if($reg->no_moneda == 'EURO'){
	                  $convert_soles = $gasto_agente * $euro_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                  $convert_soles = $gasto_agente * $fr_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else{
	                  $suma = $suma + $gasto_agente;
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            $this->pdf->Cell(26,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(50,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(32,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->total,'BR BT',0,'C',0);
	            $this->pdf->Cell(36,8,$reg->no_agente,'BR BT',0,'C',0);
	            $this->pdf->Cell(26,8,$porcentaje.'%','BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,@number_format($gasto_agente, 2, '.', ''),'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE GASTOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}

        $this->pdf->Output("Lista de Proveedores.pdf", 'I');
	}

	public function reporteingreso_agente_pdf_hilos(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');
	    $this->load->library('pdfIngresos_filtro_fecha');
	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_agenteFiltroPdf_hilos();
	    // se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        if($this->input->post('fechainicial_3') && $this->input->post('fechafinal_3')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else if($this->input->post('agente')){
        	$this->pdf = new PdfIngresos_filtro_fecha();
        }else{
        	$this->pdf = new PdfIngresos();
        }
        
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200,200,200);
 
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        $existe = count($reg_ingresos);
  		if($existe > 0){
  			if($this->input->post('fechainicial_3') && $this->input->post('fechafinal_3')){
  				$fecha_inicial_3 = $this->input->post('fechainicial_3');
        		$fecha_final_3 = $this->input->post('fechafinal_3');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(217,-24,"DEL: ".$fecha_inicial_3." AL: ".$fecha_final_3,'',0,'C','0');
  				$this->pdf->Ln(0);
  			}else if($this->input->post('agente')){
        		$id_agente = $this->input->post('agente');
        		$this->db->select('no_agente');
		        $this->db->where('id_agente',$id_agente);
		        $query = $this->db->get('agente_aduana');
		        foreach($query->result() as $row){
		            $no_agente = $row->no_agente;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(109.5,-24,"POR AGENTE: ",'',0,'R','0');
  				$this->pdf->Ln(0);
  				$this->pdf->Cell(280,-24,"                                                                                       ".$no_agente,'','L','L','0');
  				$this->pdf->Ln(0);
        	}

  			$this->pdf->SetFont('Arial', 'B', 7);
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(26,9,utf8_decode('N° DE FACTURA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(82,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(32,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(16,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Cell(36,9,utf8_decode('AGENTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(26,9,utf8_decode('PORC. ASIGNADO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('GASTOS'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// se obtienen los tipos de cambio del día
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }

	        	$porcentaje = $reg->gastos*100;
	        	$gasto_agente = $reg->total * $reg->gastos;

	        	if($reg->no_moneda == 'DOLARES'){
	                  $convert_soles = $gasto_agente * $dolar_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else if($reg->no_moneda == 'EURO'){
	                  $convert_soles = $gasto_agente * $euro_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                  $convert_soles = $gasto_agente * $fr_venta_fechaR;
	                  $suma = $suma + $convert_soles;
	            }else{
	                  $suma = $suma + $gasto_agente;
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            $this->pdf->Cell(26,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(82,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(32,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(16,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->total,'BR BT',0,'C',0);
	            $this->pdf->Cell(36,8,$reg->no_agente,'BR BT',0,'C',0);
	            $this->pdf->Cell(26,8,$porcentaje.'%','BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,@number_format($gasto_agente, 2, '.', ''),'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE GASTOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'Fr. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
        	if($this->input->post('fechainicial_3') && $this->input->post('fechafinal_3')){
  				$fecha_inicial_3 = $this->input->post('fechainicial_3');
        		$fecha_final_3 = $this->input->post('fechafinal_3');
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(16.5,-24,"DEL: ".$fecha_inicial_3." AL: ".$fecha_final_3,'',0,'C','0');
  				$this->pdf->Ln(0);
  			}else if($this->input->post('agente')){
        		$id_agente = $this->input->post('agente');
        		$this->db->select('no_agente');
		        $this->db->where('id_agente',$id_agente);
		        $query = $this->db->get('agente_aduana');
		        foreach($query->result() as $row){
		            $no_agente = $row->no_agente;
		        }
        		$this->pdf->SetFont('Times', 'B', 14);
  				$this->pdf->Cell(9.5,-24,"POR AGENTE: ",'',0,'R','0');
  				$this->pdf->Ln(0);
  				//$this->pdf->Cell(361,-24,$no_producto,'',0,'C','0');
  				/*$this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);*/
  				$this->pdf->Cell(280,-24,"                                                                                       ".$no_agente,'','L','L','0');
  				$this->pdf->Ln(0);
        	}
  		}

        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reportesalidapdf(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfSalidas');

	    // Se obtienen los alumnos de la base de datos
	    $reg_salida = $this->model_gerencia->listaRegistrosSalidaFiltroPdf();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfSalidas();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Salida");
        $this->pdf->SetLeftMargin(13);
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
        $existe = count($reg_salida);
  		if($existe > 0){ 
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(24,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('MARCA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('ÁREA'),'BLTR',0,'C','1');
	        //$this->pdf->Cell(23,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('FECHA SALIDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(80,9,utf8_decode('PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma_soles = 0;
	        foreach ($reg_salida as $reg) {
	        	$suma_soles = $suma_soles + ($reg->cantidad_salida * $reg->precio_unitario);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(23,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(24,8,$reg->nombre_maquina,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->no_marca,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->no_modelo,'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->no_serie,'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->no_area,'BR BT',0,'C',0);
	            //$this->pdf->Cell(23,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(80,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->cantidad_salida,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->precio_unitario,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(140,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(175,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(105,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
        $this->pdf->Output("Lista de Salida de Productos.pdf", 'I');
	}

	public function reportesalidapdf_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfSalidas');

	    // Se obtienen los alumnos de la base de datos
	    $reg_salida = $this->model_gerencia->listaRegistrosSalidaFiltroPdf_tejeduria();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfSalidas();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Salida");
        $this->pdf->SetLeftMargin(13);
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
        $existe = count($reg_salida);
  		if($existe > 0){ 
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(24,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('MARCA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('ÁREA'),'BLTR',0,'C','1');
	        //$this->pdf->Cell(23,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('FECHA SALIDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(80,9,utf8_decode('PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma_soles = 0;
	        foreach ($reg_salida as $reg) {
	        	$suma_soles = $suma_soles + ($reg->cantidad_salida * $reg->precio_unitario);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(23,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(24,8,$reg->nombre_maquina,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->no_marca,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->no_modelo,'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->no_serie,'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->no_area,'BR BT',0,'C',0);
	            //$this->pdf->Cell(23,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(80,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->cantidad_salida,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->precio_unitario,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(140,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(175,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(105,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
        $this->pdf->Output("Lista de Salida de Productos.pdf", 'I');
	}

	public function reportesalidapdf_hilos(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfSalidas');

	    // Se obtienen los alumnos de la base de datos
	    $reg_salida = $this->model_gerencia->listaRegistrosSalidaFiltroPdf_hilos();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfSalidas();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Salida");
        $this->pdf->SetLeftMargin(13);
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
        $existe = count($reg_salida);
  		if($existe > 0){ 
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(24,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('MARCA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('MODELO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(23,9,utf8_decode('ÁREA'),'BLTR',0,'C','1');
	        //$this->pdf->Cell(23,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(24,9,utf8_decode('FECHA SALIDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(80,9,utf8_decode('PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma_soles = 0;
	        foreach ($reg_salida as $reg) {
	        	$suma_soles = $suma_soles + ($reg->cantidad_salida * $reg->precio_unitario);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(23,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(24,8,$reg->nombre_maquina,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->no_marca,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->no_modelo,'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->no_serie,'BR BT',0,'C',0);
	            $this->pdf->Cell(23,8,$reg->no_area,'BR BT',0,'C',0);
	            //$this->pdf->Cell(23,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(24,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(80,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->cantidad_salida,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->precio_unitario,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(140,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(175,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(105,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
        $this->pdf->Output("Lista de Salida de Productos.pdf", 'D');
	}

	public function reportesalida_solicitante_pdf_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfSalidas');

	    // Se obtienen los alumnos de la base de datos
	    $reg_salida = $this->model_gerencia->listaRegistrosSalidaFiltroPdf_tejeduria();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfSalidas();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Salida");
        $this->pdf->SetLeftMargin(13);
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
        $existe = count($reg_salida);
  		if($existe > 0){ 
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(27,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(27,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(34,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(27,9,utf8_decode('ÁREA'),'BLTR',0,'C','1');
	        //$this->pdf->Cell(23,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('FECHA SALIDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(80,9,utf8_decode('PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma_soles = 0;
	        foreach ($reg_salida as $reg) {
	        	$suma_soles = $suma_soles + ($reg->cantidad_salida * $reg->precio_unitario);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(23,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(27,8,$reg->nombre_maquina,'BR BT',0,'C',0);
	            $this->pdf->Cell(27,8,$reg->no_serie,'BR BT',0,'C',0);
	            $this->pdf->Cell(34,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(27,8,$reg->no_area,'BR BT',0,'C',0);
	            //$this->pdf->Cell(23,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(80,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->cantidad_salida,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio_unitario,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(140,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(175,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(105,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
	        $this->pdf->Output("Lista de Salida de Productos.pdf", 'I');
	}

	public function reportesalida_solicitante_pdf_sta_clara(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfSalidas');

	    // Se obtienen los alumnos de la base de datos
	    $reg_salida = $this->model_gerencia->listaRegistrosSalidaFiltroPdf_sta_clara();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfSalidas();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Salida");
        $this->pdf->SetLeftMargin(13);
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
        $existe = count($reg_salida);
  		if($existe > 0){ 
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(27,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(27,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(34,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(27,9,utf8_decode('ÁREA'),'BLTR',0,'C','1');
	        //$this->pdf->Cell(23,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('FECHA SALIDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(80,9,utf8_decode('PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma_soles = 0;
	        foreach ($reg_salida as $reg) {
	        	$suma_soles = $suma_soles + ($reg->cantidad_salida * $reg->precio_unitario);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(23,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(27,8,$reg->nombre_maquina,'BR BT',0,'C',0);
	            $this->pdf->Cell(27,8,$reg->no_serie,'BR BT',0,'C',0);
	            $this->pdf->Cell(34,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(27,8,$reg->no_area,'BR BT',0,'C',0);
	            //$this->pdf->Cell(23,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(80,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->cantidad_salida,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio_unitario,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(140,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(175,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(105,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
	        $this->pdf->Output("Lista de Salida de Productos.pdf", 'I');
	}

	public function reportesalida_solicitante_pdf_hilos(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfSalidas');

	    // Se obtienen los alumnos de la base de datos
	    $reg_salida = $this->model_gerencia->listaRegistrosSalidaFiltroPdf_hilos();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfSalidas();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Salida");
        $this->pdf->SetLeftMargin(13);
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
        $existe = count($reg_salida);
  		if($existe > 0){ 
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(27,9,utf8_decode('MÁQUINA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(27,9,utf8_decode('SERIE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(34,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(27,9,utf8_decode('ÁREA'),'BLTR',0,'C','1');
	        //$this->pdf->Cell(23,9,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('FECHA SALIDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(80,9,utf8_decode('PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma_soles = 0;
	        foreach ($reg_salida as $reg) {
	        	$suma_soles = $suma_soles + ($reg->cantidad_salida * $reg->precio_unitario);
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(23,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(27,8,$reg->nombre_maquina,'BR BT',0,'C',0);
	            $this->pdf->Cell(27,8,$reg->no_serie,'BR BT',0,'C',0);
	            $this->pdf->Cell(34,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(27,8,$reg->no_area,'BR BT',0,'C',0);
	            //$this->pdf->Cell(23,8,$reg->solicitante,'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(80,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->cantidad_salida,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio_unitario,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(140,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(175,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(175,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(105,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
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
	        $this->pdf->Output("Lista de Salida de Productos.pdf", 'D');
	}

	public function reporteingresospdf_otros(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistrosFiltroPdf_otros();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(35,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('N° DE COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(55,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $sum_total = 0;
	        $suma_soles = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->total * $dolar_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'EURO'){
	        		$convert_soles = $reg->total * $euro_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'FRANCO SUIZO'){
	        		$convert_soles = $reg->total * $fr_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else{
	        		$suma_soles = $suma_soles + $reg->total;
	        	}
	        	$sub_total = $reg->total / 1.18;
	        	$sum_total = $sum_total + $reg->total;
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(35,8,utf8_decode($reg->no_comprobante),'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(55,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->total,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	    	$this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}
	        //Se agrega un salto de linea
	        /*
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(163,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,6,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(215,8,'IMPORTE TOTAL DE INGRESOS EN SOLES',0,0,'R',0);
	        $this->pdf->Cell(25,7.5,'S/. '.$sum_total,0,0,'R',0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(218.5,8,'IMPORTE TOTAL DE INGRESOS EN DOLARES',0,0,'R',0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(215.5,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,0,'R',0);
			*/
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

	public function reporteingresospdf_otros_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistrosFiltroPdf_otros_tejeduria();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(35,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('N° DE COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(55,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $sum_total = 0;
	        $suma_soles = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->total * $dolar_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'EURO'){
	        		$convert_soles = $reg->total * $euro_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'FRANCO SUIZO'){
	        		$convert_soles = $reg->total * $fr_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else{
	        		$suma_soles = $suma_soles + $reg->total;
	        	}
	        	$sub_total = $reg->total / 1.18;
	        	$sum_total = $sum_total + $reg->total;
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(35,8,utf8_decode($reg->no_comprobante),'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(55,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->total,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	    	$this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}
	        //Se agrega un salto de linea
	        /*
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(163,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,6,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(215,8,'IMPORTE TOTAL DE INGRESOS EN SOLES',0,0,'R',0);
	        $this->pdf->Cell(25,7.5,'S/. '.$sum_total,0,0,'R',0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(218.5,8,'IMPORTE TOTAL DE INGRESOS EN DOLARES',0,0,'R',0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(215.5,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,0,'R',0);
			*/
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

	public function reporteingresospdf_otros_staClara(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistrosFiltroPdf_otros_staClara();
	    $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(35,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('N° DE COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(55,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(35,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('MONTO TOTAL'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $sum_total = 0;
	        $suma_soles = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }
	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->total * $dolar_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'EURO'){
	        		$convert_soles = $reg->total * $euro_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else if($reg->no_moneda == 'FRANCO SUIZO'){
	        		$convert_soles = $reg->total * $fr_venta_fechaR;
	        		$suma_soles = $suma_soles + $convert_soles;
	        	}else{
	        		$suma_soles = $suma_soles + $reg->total;
	        	}
	        	$sub_total = $reg->total / 1.18;
	        	$sum_total = $sum_total + $reg->total;
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(35,8,utf8_decode($reg->no_comprobante),'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(55,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(35,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->total,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }
	        $suma_dolares = $suma_soles / $dolar_venta;
	        $suma_euros = $suma_soles / $euro_venta;
	        $suma_libras = $suma_soles / $fr_venta;
	    	$this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);
	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma_soles, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN EUROS'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'EUR '.@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('Fr. ').@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);
	    }
        else
        {
        	$this->pdf->Cell(100,8,utf8_decode('NO EXISTEN RESULTADOS PARA EL TIPO DE BÚSQUEDA QUE HA SELECCIONADO'),0,'R','R',0);
  		}
	        //Se agrega un salto de linea
	        /*
	        $this->pdf->Ln(4);
	        $this->pdf->Cell(163,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,6,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(215,8,'IMPORTE TOTAL DE INGRESOS EN SOLES',0,0,'R',0);
	        $this->pdf->Cell(25,7.5,'S/. '.$sum_total,0,0,'R',0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(218.5,8,'IMPORTE TOTAL DE INGRESOS EN DOLARES',0,0,'R',0);
	        $this->pdf->Ln(8);
	        $this->pdf->Cell(215.5,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,0,'R',0);
			*/
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

	public function reporteingreso_producto_pdf_otros(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos_otros');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_productoFiltroPdf_otros();
 		// se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos_otros();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(5);
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(30,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('N° DE COMP.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(43,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(32,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(68,9,utf8_decode('NOMBRE DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('UNIDADES'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }

	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->precio * $dolar_venta_fechaR;
	        		$suma = $suma + ($convert_soles * $reg->unidades);
	        	}else if($reg->no_moneda == 'EURO'){
	                $convert_soles = $reg->precio * $euro_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                $convert_soles = $reg->precio * $fr_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else{
	            	$suma = $suma + ($reg->precio * $reg->unidades);	
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(30,8,utf8_decode($reg->no_comprobante),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(43,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(32,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(68,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->unidades,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'LE. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
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
        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingreso_producto_pdf_otros_tejeduria(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos_otros');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_productoFiltroPdf_otros_tejeduria();
 		// se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos_otros();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(5);
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(30,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('N° DE COMP.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(43,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(32,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(68,9,utf8_decode('NOMBRE DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('UNIDADES'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }

	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->precio * $dolar_venta_fechaR;
	        		$suma = $suma + ($convert_soles * $reg->unidades);
	        	}else if($reg->no_moneda == 'EURO'){
	                $convert_soles = $reg->precio * $euro_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                $convert_soles = $reg->precio * $fr_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else{
	            	$suma = $suma + ($reg->precio * $reg->unidades);	
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(30,8,utf8_decode($reg->no_comprobante),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(43,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(32,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(68,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->unidades,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'LE. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
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
        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function reporteingreso_producto_pdf_otros_staClara(){
		// Se carga el modelo alumno
	    $this->load->model('model_comercial');
	    // Se carga la libreria fpdf
	    $this->load->library('pdfIngresos_otros');

	    // Se obtienen los alumnos de la base de datos
	    $reg_ingresos = $this->model_gerencia->listaRegistros_productoFiltroPdf_otros_staClara();
 		// se obtienen los tipos de cambio del día
        $this->db->select('dolar_venta,euro_venta,fr_venta');
        $this->db->where('fecha_actual',date('Y-m-d'));
        $query = $this->db->get('tipo_cambio');
        foreach($query->result() as $row){
            $dolar_venta = $row->dolar_venta;
            $euro_venta = $row->euro_venta;
            $fr_venta = $row->fr_venta;
        }
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new PdfIngresos_otros();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Lista de Registros de Ingreso");
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(5);
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
        $existe = count($reg_ingresos);
  		if($existe > 0){
	        $this->pdf->Cell(10,9,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
	        //$this->pdf->Cell(25,8,'ID','TB',0,'L','1');
	        $this->pdf->Cell(30,9,utf8_decode('COMPROBANTE'),'BLTR',0,'C','1');
	        $this->pdf->Cell(25,9,utf8_decode('N° DE COMP.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(31,9,utf8_decode('MONEDA'),'BLTR',0,'C','1');
	        $this->pdf->Cell(43,9,utf8_decode('PROVEEDOR'),'BLTR',0,'C','1');
	        $this->pdf->Cell(32,9,utf8_decode('FECHA DE REGISTRO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(68,9,utf8_decode('NOMBRE DEL PRODUCTO'),'BLTR',0,'C','1');
	        $this->pdf->Cell(20,9,utf8_decode('PREC. UNIT.'),'BLTR',0,'C','1');
	        $this->pdf->Cell(18,9,utf8_decode('UNIDADES'),'BLTR',0,'C','1');
	        $this->pdf->Ln(9);
	        // La variable $x se utiliza para mostrar un número consecutivo
	        $x = 1;
	        $suma = 0;
	        foreach ($reg_ingresos as $reg) {
	        	// Obtengo el tipo de cambio del día con el que se registro la factura
		        $this->db->select('dolar_venta,euro_venta,fr_venta');
		        $this->db->where('fecha_actual',$reg->fecha);
		        $query = $this->db->get('tipo_cambio');
		        foreach($query->result() as $row){
		            $dolar_venta_fechaR = $row->dolar_venta;
		            $euro_venta_fechaR = $row->euro_venta;
		            $fr_venta_fechaR = $row->fr_venta;
		        }

	        	if($reg->no_moneda == 'DOLARES'){
	        		$convert_soles = $reg->precio * $dolar_venta_fechaR;
	        		$suma = $suma + ($convert_soles * $reg->unidades);
	        	}else if($reg->no_moneda == 'EURO'){
	                $convert_soles = $reg->precio * $euro_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else if($reg->no_moneda == 'FRANCO SUIZO'){
	                $convert_soles = $reg->precio * $fr_venta_fechaR;
	                $suma = $suma + ($convert_soles * $reg->unidades);
	            }else{
	            	$suma = $suma + ($reg->precio * $reg->unidades);	
	            }
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            $this->pdf->Cell(10,8,$x++,'BR BL BT',0,'C',0);
	            // Se imprimen los datos de cada reg
	            //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
	            $this->pdf->Cell(30,8,utf8_decode($reg->no_comprobante),'BR BT',0,'C',0);
	            $this->pdf->Cell(25,8,$reg->nro_comprobante,'BR BT',0,'C',0);
	            $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
	            $this->pdf->Cell(43,8,$reg->razon_social,'BR BT',0,'C',0);
	            $this->pdf->Cell(32,8,$reg->fecha,'BR BT',0,'C',0);
	            $this->pdf->Cell(68,8,$reg->no_producto,'BR BT',0,'C',0);
	            $this->pdf->Cell(20,8,$reg->precio,'BR BT',0,'C',0);
	            $this->pdf->Cell(18,8,$reg->unidades,'BR BT',0,'C',0);
	            //Se agrega un salto de linea
	            $this->pdf->Ln(8);
	        }

	        $suma_dolares = $suma / $dolar_venta;
	        $suma_euros = $suma / $euro_venta;
	        $suma_libras = $suma / $fr_venta;

	        $this->pdf->Ln(4);
	        $this->pdf->Cell(125,8,'',0,'R',0,0);
	        $this->pdf->Cell(30,5,'RESUMEN DE TOTALES','B','R',0,0);
	        $this->pdf->Ln(6);

	        $this->pdf->Cell(160,8,'Tipo de Cambio al '.date('d-m-Y'),0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN SOLES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'S/. '.@number_format($suma, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Dólar ').$dolar_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN DÓLARES'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'$. '.@number_format($suma_dolares, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Euro ').$euro_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,'IMPORTE TOTAL DE INGRESOS EN EUROS',0,'R','R',0);
	        $this->pdf->Cell(25,8,utf8_decode('E. ').@number_format($suma_euros, 2, '.', ''),0,0,'R',0);
	        $this->pdf->Ln(4);

	        $this->pdf->Cell(160,8,utf8_decode('Venta Fr. Suizo ').$fr_venta,0,'L','R',0);
	        $this->pdf->Cell(65,8,utf8_decode('IMPORTE TOTAL DE INGRESOS EN FR. SUIZO'),0,'R','R',0);
	        $this->pdf->Cell(25,8,'LE. '.@number_format($suma_libras, 2, '.', ''),0,0,'R',0);
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
        $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
	}

	public function redirect_stock(){
		$nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
		$apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
		if($nombre == "" AND $apellido == ""){
			$this->load->view('login');
		}else{
			$data['almacen']= $this->model_admin->listarAlmacen();
			$data['listacategoria'] = $this->model_comercial->listarCategoria();
			$data['producto']= $this->model_gerencia->listarProducto();
			$this->load->view('gerencia/menu_stock');
			$this->load->view('gerencia/registrar_producto_gerencia',$data);
		}
	}


	public function UpdatePassword()
	{
		$this->form_validation->set_rules('password', 'Contraseña Actual', 'trim|required|max_length[12]|xss_clean');
		$this->form_validation->set_rules('datacontrasena', 'Nueva Contraseña', 'trim|required|max_length[12]|xss_clean');
		//Mensajes
		$this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
		$this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
		//Delimitadores de ERROR:
		$this->form_validation->set_error_delimiters('<span>', '</span><br>');

		if($this->form_validation->run() == FALSE){
			if($this->input->post('password') == "" AND $this->input->post('datacontrasena') == ""){
				echo '<span style="color:red"><b>ERROR:</b> Falta completar los campos. Verifique por favor.</span>';
			}else if($this->input->post('password') == ""){
				echo '<span style="color:red"><b>ERROR:</b> Falta completar el campo Contraseña Actual.</span>';
			}else if($this->input->post('datacontrasena') == ""){
				echo '<span style="color:red"><b>ERROR:</b> Falta completar el campo Nueva Contraseña.</span>';
			}
		}else {
			$result = $this->model_gerencia->UpdatePassword();
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





}
?>