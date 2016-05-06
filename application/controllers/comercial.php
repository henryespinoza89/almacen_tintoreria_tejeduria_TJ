<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comercial extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct(){
        parent::__construct();  
        //Se controla la variable de Session
        $this->load->model('model_usuario');        
        $this->load->model('model_comercial');
        $this->load->library('session');
        $this->load->library('table');
        //$this->cart->validar_caracteres();
        $this->load->helper(array('form', 'url'));
        /*
        if($this->session->userdata('session') == 1 ){
            if (!($this->session->userdata('tipo') == 1)){redirect($this->session->userdata('ruta'));}
        }else{
            redirect('login');
        }
        */          
    }

    public function index(){
        if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
        }else{
            $data['tipocambio'] = 1;
        }
        $data['almacen']= $this->model_comercial->listarAlmacen();
        $data['producto']= $this->model_comercial->listarProducto();
        $data['listacategoria'] = $this->model_comercial->listarCategoria();
        $data['lista_tipo_producto'] = $this->model_comercial->listarTipoProducto();
        $data['listaprocedencia'] = $this->model_comercial->listarProcedencia();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/productos/registrar_producto',$data);
    }

    public function gestionproductos(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['almacen']= $this->model_comercial->listarAlmacen();
            $data['producto']= $this->model_comercial->listarProducto();
            $data['listacategoria'] = $this->model_comercial->listarCategoria();
            $data['lista_tipo_producto'] = $this->model_comercial->listarTipoProducto();
            $data['listaprocedencia'] = $this->model_comercial->listarProcedencia();
            /*$this->load->view('comercial/menu');*/
            $this->load->view('comercial/menu');
            $this->load->view('comercial/productos/registrar_producto',$data);
        }
    }

    public function get_all_productos(){
        $data = $this->model_comercial->listarProducto();
        echo json_encode(['result'=>$data]);
    }

    public function get_all_productos_insert(){
        $nombre_producto = strtoupper($this->security->xss_clean($this->input->post('nombrepro')));
        $data = $this->model_comercial->get_producto_insert_last($nombre_producto);
        echo json_encode(['result'=>$data]);
    }

    public function logout()
    {   
        $this->cart->destroy();
        //$this->session->unset_userdata('session');
        $this->session->sess_destroy();
        redirect('');
    }

    public function gestioninventario(){
        if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
        }else{
            $data['tipocambio'] = 1;
        }
        $this->load->view('comercial/menu_script');
        $this->load->view('comercial/menu_cabecera');
        $this->load->view('comercial/view_inventario');
    }

    public function gestioninventarioalmacen(){
        if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
        }else{
            $data['tipocambio'] = 1;
        }
        $this->load->view('comercial/menu_script');
        $this->load->view('comercial/menu_cabecera');
        $this->load->view('comercial/inventario_almacen');
    }

    public function guardar_informacion_productos(){
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $filename = $_FILES['file']['tmp_name'];
        if(($gestor = fopen($filename, "r")) !== FALSE){
            while (($datos = fgetcsv($gestor,1000,";")) !== FALSE){
                
                // Insert Tabla detalle_producto
                $nombre_producto = trim($datos[1]);
                $stock = trim($datos[3]);
                $precio_unitario = trim($datos[4]);
                // Array
                $a_data_detalle = array(
                                        'no_producto'=>utf8_decode($nombre_producto),
                                        'stock'=>utf8_decode($stock),
                                        'precio_unitario'=>utf8_decode($precio_unitario),
                                        );
                $id_insert_detalle = $this->model_comercial->save_detalle_producto($a_data_detalle);
                // Fin - insert

                // Insert Tabla Productos
                $id_producto = trim($datos[0]);
                $id_unidad_medida = trim($datos[2]);
                $id_procedencia = trim($datos[7]);
                $id_categoria = trim($datos[5]);
                $id_tipo_producto = trim($datos[6]);
                // Array
                $a_data_producto = array(
                                        'id_producto'=>trim($id_producto),
                                        'id_almacen'=>trim($id_almacen),
                                        'id_procedencia'=>trim($id_procedencia),
                                        'id_categoria'=>trim($id_categoria),
                                        'id_detalle_producto'=>trim($id_insert_detalle),
                                        'id_tipo_producto'=>trim($id_tipo_producto),
                                        'id_unidad_medida'=>trim($id_unidad_medida),
                                        );
                $this->model_comercial->save_producto($a_data_producto);
                // Fin - insert
            }   
        }
    }

    public function guardar_informacion_factura_importada(){
        $this->form_validation->set_rules('comprobante', 'Comprobante', 'trim|required|xss_clean');
        $this->form_validation->set_rules('numcomprobante', 'Nro. de Comprobante', 'trim|required|xss_clean');
        $this->form_validation->set_rules('seriecomprobante', 'Serie de Comprobante', 'trim|required|xss_clean');
        $this->form_validation->set_rules('nombre_proveedor', 'Proveedor', 'trim|required|xss_clean');
        $this->form_validation->set_rules('fecharegistro', 'Fecha de Registro', 'trim|required|xss_clean');
        $this->form_validation->set_rules('moneda', 'Moneda', 'trim|required|xss_clean');
        $this->form_validation->set_rules('agente', 'Agente de Aduana', 'trim|required|xss_clean');
        if($this->input->post('comprobante') == 2){
            $this->form_validation->set_rules('total_factura_contabilidad', 'Agente de Aduana', 'trim|required|xss_clean');
        }
        /* Mensajes */
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        /* Delimitadores de ERROR: */
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');
        if($this->form_validation->run() == FALSE)
        {
            $nombre = $this->security->xss_clean($this->session->userdata('nombre'));
            $apellido = $this->security->xss_clean($this->session->userdata('apaterno'));
            if($nombre == "" AND $apellido == ""){
                $this->load->view('login');
            }else{
                if($this->input->post('comprobante') == "" AND $this->input->post('numcomprobante') == "" AND $this->input->post('seriecomprobante') == "" AND $this->input->post('fecharegistro') == "" AND $this->input->post('moneda') == "" AND $this->input->post('nombre_proveedor') == "" ){
                    $data['respuesta_general'] = '<span style="color:red"><b>ERROR:</b> Falta completar los campos.</span>';
                }else if($this->input->post('comprobante') == ""){
                    $data['respuesta_compro_seleccion'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo N° de Comprobante.</span>';
                }else if($this->input->post('numcomprobante') == ""){
                    $data['respuesta_compro'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo N° de Comprobante.</span>';
                }else if($this->input->post('seriecomprobante') == ""){
                    $data['respuesta_serie'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo N° de Comprobante.</span>';
                }else if($this->input->post('nombre_proveedor') == ""){
                    $data['respuesta_prov'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Proveedor.</span>';
                }else if($this->input->post('fecharegistro') == ""){
                    $data['respuesta_fe'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Fecha de Registro.</span>';
                }else if($this->input->post('moneda') == ""){
                    $data['respuesta_moneda'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Moneda.</span>';
                }else if($this->input->post('agente') == ""){
                    $data['respuesta_agente'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Agente.</span>';
                }else if($this->input->post('comprobante') == 2 AND $this->input->post('total_factura_contabilidad') == ""){
                    $data['respuesta_total_factura'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Total Factura.</span>';
                }
                $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                $data['listaproveedor']= $this->model_comercial->listaProveedor();
                $data['listasimmon']= $this->model_comercial->listaSimMon();
                $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
                $this->load->view('comercial/menu_script');
                $this->load->view('comercial/menu_cabecera');
                $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
            }
        }else{
            /* Inicio del proceso - transacción */
            $this->db->trans_begin();

            $i = 1;
            $cont_area = 1;
            $y = 0;
            $suma_parciales_factura = 0;
            $indicador = TRUE;
            $indicador_area = TRUE;
            /* Obtengo las variables generales de la factura */
            $id_comprobante = $this->security->xss_clean($this->input->post('comprobante'));
            $seriecomprobante = $this->security->xss_clean($this->input->post('seriecomprobante'));
            $numcomprobante = $this->security->xss_clean($this->input->post('numcomprobante'));
            $id_moneda = $this->security->xss_clean($this->input->post('moneda'));
            $nombre_proveedor = $this->security->xss_clean($this->input->post("nombre_proveedor"));
            $fecharegistro = $this->security->xss_clean($this->input->post('fecharegistro'));
            $id_agente = $this->security->xss_clean($this->input->post('agente'));
            $total_factura_contabilidad = $this->security->xss_clean($this->input->post('total_factura_contabilidad'));
            $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
            if($total_factura_contabilidad == ""){
                $total_factura_contabilidad = 0;
            }
            /* Obtener el id del proveedor */
            $this->db->select('id_proveedor');
            $this->db->where('razon_social',$nombre_proveedor);
            $query = $this->db->get('proveedor');
            foreach($query->result() as $row){
                $id_proveedor = $row->id_proveedor;
            }

            // validacion de contenido
            // Validar si existe un error en los productos a registrar
            $filename = $_FILES['file']['tmp_name'];
            if(($gestor = fopen($filename, "r")) !== FALSE){
                while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                    // Obtener los valores de la hoja de excel
                    $codigo_producto = trim($datos[0]);
                    $cantidad_ingreso = trim($datos[1]);
                    $precio_ingreso = trim($datos[2]);
                    /* ------------------------------------------ */
                    $this->db->select('id_detalle_producto');
                    $this->db->where('id_pro',$codigo_producto);
                    $query = $this->db->get('producto');
                    if($query->num_rows() > 0){
                        $i = $i + 1;
                    }else{
                        $indicador = FALSE;
                        $data['respuesta_validacion_facturas_importadas'] = $i;
                        $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                        $data['listaproveedor']= $this->model_comercial->listaProveedor();
                        $data['listasimmon']= $this->model_comercial->listaSimMon();
                        $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
                        $this->load->view('comercial/menu_script');
                        $this->load->view('comercial/menu_cabecera');
                        $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
                        break;
                    }
                }
            }else{
                echo "No se cargo el archivo CSV";
                die();
            }

            if( $indicador == TRUE ){
                // Guardo los datos generales de la factura
                // Agregamos el registro_ingreso a la bd
                $datos = array(
                    "id_comprobante" => $id_comprobante,
                    "serie_comprobante" => $seriecomprobante,
                    "nro_comprobante" => $numcomprobante,
                    "fecha" => $fecharegistro,
                    "id_moneda" => $id_moneda,
                    "id_proveedor" => $id_proveedor,
                    "total" => $total_factura_contabilidad,
                    "id_almacen" => $almacen,
                    "cs_igv" => "FALSE",
                    "id_agente" => $id_agente
                );
                $id_ingreso_producto = $this->model_comercial->agrega_ingreso($datos, $seriecomprobante, $numcomprobante, $id_proveedor, $fecharegistro);

                if($id_ingreso_producto == FALSE){
                    $data['validacion_no_existe_tipo_cambio'] = '<span style="color:red"><b>ERROR:</b> no_se_encontro_factura_importada </span>';
                    $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                    $data['listaproveedor']= $this->model_comercial->listaProveedor();
                    $data['listasimmon']= $this->model_comercial->listaSimMon();
                    $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
                    $this->load->view('comercial/menu_script');
                    $this->load->view('comercial/menu_cabecera');
                    $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
                }else if($id_ingreso_producto == 'actualizacion_registro'){
                    // var_dump('actualizacion_registro');
                    $filename = $_FILES['file']['tmp_name'];
                    // Sumo el sub-total de la factura, sin considerar los gastos de importacion
                    if(($gestor = fopen($filename, "r")) !== FALSE){
                        while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                            $codigo_producto = trim($datos[0]);
                            $cantidad_ingreso = trim($datos[1]);
                            $precio_ingreso = trim($datos[2]);

                            $suma_parciales_factura = $suma_parciales_factura + ($cantidad_ingreso * $precio_ingreso);
                        }
                    }else{
                        echo "No se cargo el archivo CSV";
                        die();
                    }

                    if(($gestor = fopen($filename, "r")) !== FALSE){
                        while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                            // Obtener los valores de la hoja de excel
                            $codigo_producto = trim($datos[0]);
                            $cantidad_ingreso = trim($datos[1]);
                            $precio_ingreso = trim($datos[2]);
                            // Obtener los ID de Clientes, Tejidos y Color
                            // ------------------------------------------
                            $this->db->select('id_detalle_producto');
                            $this->db->where('id_pro',$codigo_producto);
                            $query = $this->db->get('producto');
                            foreach($query->result() as $row){
                                $id_detalle_producto = $row->id_detalle_producto;
                            }
                            $id_insert = $this->model_comercial->actualizar_detalle_kardex_importado($id_proveedor,$id_comprobante,$suma_parciales_factura,$id_detalle_producto,$cantidad_ingreso,$precio_ingreso,$fecharegistro,$seriecomprobante,$numcomprobante,$total_factura_contabilidad,$almacen);
                            if($id_insert == true){
                                $y = $y + 1;
                            }else if($id_insert == 'no_se_encontro_factura_importada'){
                                $data['respuesta_validacion_actualizacion_importadas'] = '<span style="color:red"><b>ERROR:</b> no_se_encontro_factura_importada </span>';
                                $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                                $data['listaproveedor']= $this->model_comercial->listaProveedor();
                                $data['listasimmon']= $this->model_comercial->listaSimMon();
                                $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
                                $this->load->view('comercial/menu_script');
                                $this->load->view('comercial/menu_cabecera');
                                $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
                                break;
                            }
                            // Limpio la variable porque si no encuentra uno de los id que busco, se queda con el ultimo que encontro y lo registra
                            $id_detalle_producto = "";
                        }
                    }else{
                        echo "No se cargo el archivo CSV";
                        die();
                    }

                    if($y != 0){
                        $data['respuesta_registro_satisfactorio'] = $y;
                        $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                        $data['listaproveedor']= $this->model_comercial->listaProveedor();
                        $data['listasimmon']= $this->model_comercial->listaSimMon();
                        $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
                        $this->load->view('comercial/menu_script');
                        $this->load->view('comercial/menu_cabecera');
                        $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
                    }
                }else{
                    if($id_comprobante == 4){
                        $filename = $_FILES['file']['tmp_name'];
                        if(($gestor = fopen($filename, "r")) !== FALSE){
                            while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                                $codigo_producto = trim($datos[0]);
                                $cantidad_ingreso = trim($datos[1]);
                                $precio_ingreso = trim($datos[2]);
                                $suma_parciales_factura = 0;
                            }
                        }else{
                            echo "No se cargo el archivo CSV";
                            die();
                        }

                        if(($gestor = fopen($filename, "r")) !== FALSE){
                            while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                                // Obtener los valores de la hoja de excel
                                $codigo_producto = trim($datos[0]);
                                $cantidad_ingreso = trim($datos[1]);
                                $precio_ingreso = trim($datos[2]);
                                /* ------------------------------------------ */
                                $this->db->select('id_detalle_producto');
                                $this->db->where('id_pro',$codigo_producto);
                                $query = $this->db->get('producto');
                                foreach($query->result() as $row){
                                    $id_detalle_producto = $row->id_detalle_producto;
                                }
                                /* ------------------------------------------ */
                                $a_data = array(
                                                'unidades'=>$cantidad_ingreso,
                                                'id_detalle_producto'=>$id_detalle_producto,
                                                'precio'=>$precio_ingreso,
                                                'id_ingreso_producto'=>$id_ingreso_producto,
                                                );
                                $id_insert = $this->model_comercial->inserta_factura_masiva($id_comprobante,$suma_parciales_factura,$a_data,$id_detalle_producto,$cantidad_ingreso,$precio_ingreso,$fecharegistro,$seriecomprobante,$numcomprobante,0,$almacen);
                                if($id_insert == true){
                                    $y = $y + 1;
                                }
                                /* Limpio la variable porque si no encuentra uno de los id que busco, se queda con el ultimo que encontro y lo registra */
                                $id_detalle_producto = "";
                            }
                        }else{
                            echo "No se cargo el archivo CSV";
                            die();
                        }

                        if($y != 0){
                            $data['respuesta_registro_satisfactorio'] = $y;
                            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                            $data['listaproveedor']= $this->model_comercial->listaProveedor();
                            $data['listasimmon']= $this->model_comercial->listaSimMon();
                            $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
                            $this->load->view('comercial/menu_script');
                            $this->load->view('comercial/menu_cabecera');
                            $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
                        }
                    }else if($id_comprobante == 2){
                        $filename = $_FILES['file']['tmp_name'];
                        if(($gestor = fopen($filename, "r")) !== FALSE){
                            while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                                $codigo_producto = trim($datos[0]);
                                $cantidad_ingreso = trim($datos[1]);
                                $precio_ingreso = trim($datos[2]);

                                $suma_parciales_factura = $suma_parciales_factura + ($cantidad_ingreso * $precio_ingreso);
                            }
                        }else{
                            echo "No se cargo el archivo CSV";
                            die();
                        }

                        if(($gestor = fopen($filename, "r")) !== FALSE){
                            while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                                // Obtener los valores de la hoja de excel
                                $codigo_producto = trim($datos[0]);
                                $cantidad_ingreso = trim($datos[1]);
                                $precio_ingreso = trim($datos[2]);
                                /* ------------------------------------------ */
                                $this->db->select('id_detalle_producto');
                                $this->db->where('id_pro',$codigo_producto);
                                $query = $this->db->get('producto');
                                foreach($query->result() as $row){
                                    $id_detalle_producto = $row->id_detalle_producto;
                                }
                                /* ------------------------------------------ */
                                $a_data = array(
                                                'unidades'=>$cantidad_ingreso,
                                                'id_detalle_producto'=>$id_detalle_producto,
                                                'precio'=>$precio_ingreso,
                                                'id_ingreso_producto'=>$id_ingreso_producto,
                                                );
                                $id_insert = $this->model_comercial->inserta_factura_masiva($id_comprobante,$suma_parciales_factura,$a_data,$id_detalle_producto,$cantidad_ingreso,$precio_ingreso,$fecharegistro,$seriecomprobante,$numcomprobante,$total_factura_contabilidad,$almacen);
                                if($id_insert == true){
                                    $y = $y + 1;
                                }
                                /* Limpio la variable porque si no encuentra uno de los id que busco, se queda con el ultimo que encontro y lo registra */
                                $id_detalle_producto = "";
                            }
                        }else{
                            echo "No se cargo el archivo CSV";
                            die();
                        }

                        if($y != 0){
                            $data['respuesta_registro_satisfactorio'] = $y;
                            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                            $data['listaproveedor']= $this->model_comercial->listaProveedor();
                            $data['listasimmon']= $this->model_comercial->listaSimMon();
                            $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
                            $this->load->view('comercial/menu_script');
                            $this->load->view('comercial/menu_cabecera');
                            $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
                        }
                    }
                }
            }

            /* Fin del proceso - transacción */
            $this->db->trans_complete();
        }
    }

    public function test_factura_importada(){
        print_r("entro al test");
        $i = 0;
        $suma_parciales_factura = 0;
        /* Obtengo las variables generales de la factura */

        $id_comprobante = $this->security->xss_clean($this->input->post('id_comprobante'));
        /*
        $seriecomprobante = $this->security->xss_clean($this->input->post('seriecomprobante'));
        $numcomprobante = $this->security->xss_clean($this->input->post('numcomprobante'));
        $id_moneda = $this->security->xss_clean($this->input->post('moneda'));
        $id_proveedor = $this->security->xss_clean($this->input->post('proveedor'));
        $fecharegistro = $this->security->xss_clean($this->input->post('fecharegistro'));
        $id_agente = $this->security->xss_clean($this->input->post('agente'));
        $total_factura_contabilidad = $this->security->xss_clean($this->input->post('total_factura_contabilidad'));
        */
        $file = $this->security->xss_clean($this->input->post('documento_file'));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));



        $filename = $_FILES[$file]['tmp_name'];
        if(($gestor = fopen($filename, "r")) !== FALSE){
            while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                // Obtener los valores de la hoja de excel
                $codigo_producto = trim($datos[0]);
                $cantidad_ingreso = trim($datos[1]);
                $precio_ingreso = trim($datos[2]);
                $suma_parciales_factura = $suma_parciales_factura + ($cantidad_ingreso * $precio_ingreso);
            }
        }

        print_r("Se registro satisfactoriamente: ".$suma_parciales_factura." registros".$id_comprobante);
    }

    public function gestioncategoriaproductos(){
        $data['categoriaproducto']= $this->model_comercial->listarCategoriaProductos();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/productos/categoria_producto', $data);
    }

    public function gestion_ubicacion_productos(){
        $data['ubicacion_producto_data']= $this->model_comercial->listarUbicacionProductos();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/productos/ubicacion_producto', $data);
    }

    public function save_ubicacion_producto(){
        $result = $this->model_comercial->save_ubicacion_producto();
        if(!$result){
            echo '!La ubicacion del producto ya se encuentra registrada. Verificar!';
        }else{
            echo '1';
        }
    }

    public function editar_ubicacion_producto(){
        $data['ubicacion_producto_data']= $this->model_comercial->getUbicacionProducto();
        $this->load->view('comercial/productos/actualizar_ubicacion_producto', $data);
    }

    public function eliminar_ubicacion_producto(){
        $id_ubicacion = $this->security->xss_clean($this->input->post('id_ubicacion'));
        $result = $this->model_comercial->eliminar_ubicacion_producto($id_ubicacion);
        if(!$result){
            echo 'dont_delete';
        }else{
            echo 'ok';
        }
    }

    public function update_ubicacion_producto(){
        $edit_ubicacion = strtoupper($this->security->xss_clean($this->input->post('edit_ubicacion')));
        /* Creación del array con los datos del codigo del producto para insertarlo en la BD */
        $actualizar_data = array('nombre_ubicacion' => $edit_ubicacion,);
        $result = $this->model_comercial->updateUbicacion($actualizar_data, $edit_ubicacion);
        // Verificamos que existan resultados
        if(!$result){
            /* Enviamos parametro */
            echo '!La ubicacion del producto ya se encuentra registrada. Verificar!';
        }else{
            /* Enviamos parametro */
            echo 'ok';
        }
    }

    public function gestionfacturasmasivas(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre'));
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno'));
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
            $data['listaproveedor']= $this->model_comercial->listaProveedor();
            $data['listasimmon']= $this->model_comercial->listaSimMon();
            $data['listacomprobante']= $this->model_comercial->listaComprobante_importado();
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/comprobantes/facturas_opcion_masiva', $data);
        }
    }

    public function registrarcategoriaproducto()
    {
        $this->form_validation->set_rules('nombre', 'Nombre de la Categoría del Producto', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 30 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            $data['categoriaproducto']= $this->model_comercial->listarCategoriaProductos();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/productos/categoria_producto', $data);
        }
        else
        {
            $result = $this->model_comercial->saveCetegoriaProducto();
            // Verificamos que existan resultados
            if(!$result){
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Esta Categoría de Producto ya se encuentra registrado.</span>';
                $data['categoriaproducto']= $this->model_comercial->listarCategoriaProductos();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/productos/categoria_producto', $data);
            }else{
                redirect('comercial/gestioncategoriaproductos');
            }
        }
    }

    public function editarcategoriaproducto(){
        $data['datoscatprod']= $this->model_comercial->getCatProdEditar();
        $this->load->view('comercial/productos/actualizar_categoria_producto', $data);
    }

    public function actualizarcategoriaproducto()
    {
        $this->form_validation->set_rules('editcatprod', 'Categoría de Producto', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaCategoriaProducto();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Esta Categoría de Producto ya se encuentra registrada.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function eliminarcategoriaproducto()
    {
        $idcategoriaproducto = $this->input->get('eliminar');
        $this->model_comercial->eliminarCategoriaProducto($idcategoriaproducto);
    }

    public function registrartipoproducto()
    {
        $this->form_validation->set_rules('nombre', 'Tipo de Producto', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 30 dígitos como máximo.');
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            $data['listatipoproducto']= $this->model_comercial->listarTipoProd();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/productos/tipo_producto', $data);
        }
        else
        {
            $result = $this->model_comercial->saveTipoProducto();           
            if(!$result){
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Este Tipo de Producto ya se encuentra registrada.</span>';
                $data['listatipoproducto']= $this->model_comercial->listarTipoProd();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/productos/tipo_producto', $data);
            }else{
                redirect('comercial/gestiontipoproductos');
            
            }
        }
    }

    public function gestiontipoproductos(){
        $data['listatipoproducto']= $this->model_comercial->listarTipoProd();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/productos/tipo_producto', $data);
    }

    public function editartipoproducto(){
        $data['datostipprod']= $this->model_comercial->getTipoProdEditar();
        $this->load->view('comercial/productos/actualizar_tipo_producto', $data);
    }

    public function actualizartipoproducto(){
        $this->form_validation->set_rules('edittipprod', 'Tipo de Producto', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaTipoProducto();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b>El Tipo de Producto ya existe.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function eliminartipoproducto(){
        $id_tipo_producto = $this->input->get('eliminar');
        $this->model_comercial->eliminarTipoProducto($id_tipo_producto);
    }

    public function traeTipo(){
        $tipo = $this->model_comercial->getTipo();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($tipo as $fila)
        {
            echo '<option value="'.$fila->id_tipo_producto.'">'.$fila->no_tipo_producto.'</option>';
        }
    }

    public function get_parte_maquina(){
        $tipo = $this->model_comercial->get_parte_maquina();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($tipo as $fila){
            echo '<option value="'.$fila->id_parte_maquina.'">'.$fila->nombre_parte_maquina.'</option>';
        }
    }

    public function traer_unidad_medida_autocomplete() {
        $termino = strtoupper($this->input->post('q'));
        $resultado = $this->model_comercial->get_unidad_medida_autocomplete($termino);

        $array = array("label" => "No se encontraron resultados");

        if ($resultado != null) {

            $data = array();

            foreach ($resultado as $datos) {
                $array = array(
                    "label" => $datos['nom_uni_med'],
                    "nom_uni_med" => $datos['nom_uni_med']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function traer_ubicacion_producto_autocomplete() {
        $termino = strtoupper($this->input->post('q'));
        $resultado = $this->model_comercial->get_ubicacion_producto_autocomplete($termino);

        $array = array("label" => "No se encontraron resultados");

        if ($resultado != null) {

            $data = array();

            foreach ($resultado as $datos) {
                $array = array(
                    "label" => $datos['nombre_ubicacion'],
                    "nombre_ubicacion" => $datos['nombre_ubicacion']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function gestionproveedores(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['proveedor']= $this->model_comercial->listarProveedores();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/proveedores/gestionar_proveedores',$data);
        }
    }

    public function gestionsalida(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['listaarea']= $this->model_comercial->listarArea();
            $data['listamaquina']= $this->model_comercial->listarMaquinas();
            $data['lista_parte_maquina']= $this->model_comercial->listar_parte_Maquinas();
            // $data['salidaproducto']= $this->model_comercial->listaSalidaProducto_2013();
            $data['areas_salidas']= $this->model_comercial->listar_areas_salidas();
            // $data['salidaproducto']= $this->model_comercial->listaSalidaProducto();
            /*
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            */
            $this->load->view('comercial/menu');
            $this->load->view('comercial/salida_almacen/registro_salida', $data);
        }
    }

    public function agregar_detalle_producto_ajax(){
        $this->cart->total();

        $id_maquina = $this->input->post('maquina');
        $id_parte_maquina = $this->input->post('parte_maquina');
        $nombre_producto = $this->input->post('nombre_producto');

        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
        }

        if($id_parte_maquina != ""){
            $this->db->select('nombre_parte_maquina');
            $this->db->where('id_parte_maquina',$id_parte_maquina);
            $query = $this->db->get('parte_maquina');
            foreach($query->result() as $row){
                $nombre_parte_maquina = $row->nombre_parte_maquina;
            }
            
            // Seleccionar el id de la parte de maquina
            $this->db->select('id_parte_maquina');
            $this->db->where('nombre_parte_maquina',$nombre_parte_maquina);
            $this->db->where('id_maquina',$id_maquina);
            $query = $this->db->get('parte_maquina');
            foreach($query->result() as $row){
                $id_parte_maquina = $row->id_parte_maquina;
            }

            $datasession_data_maquina = array(
                'id_maquina' => $this->input->post('maquina')
            );
            $this->session->set_userdata($datasession_data_maquina);
        }else{
            $id_parte_maquina = 99999999;
            $nombre_parte_maquina = "";
        }

        $this->db->select('nombre_maquina');
        $this->db->where('id_maquina',$id_maquina);
        $query = $this->db->get('maquina');
        foreach($query->result() as $row){
            $nombre_maquina = $row->nombre_maquina;
        }

        // $arr1 = explode(" ", $nombre_maquina);
        // $arr2 = explode(" ", $nombre_parte_maquina);
        
        $data = array(
            'id' => $id_detalle_producto.'-'.$id_parte_maquina,
            'qty' => $this->input->post('cantidad'),
            'price' => $id_parte_maquina,
            'name'=> $nombre_producto,
            'options'=> array( 0 => $nombre_maquina, 1 => $nombre_parte_maquina)
        );
        
        $this->cart->insert($data);

        echo 'successfull';
    }

    public function gestion_devolucion_producto(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['listaarea']= $this->model_comercial->listarArea();
            $data['listamaquina']= $this->model_comercial->listarMaquinas();
            $data['lista_parte_maquina']= $this->model_comercial->listar_parte_Maquinas();
            // $data['salidaproducto']= $this->model_comercial->listaSalidaProducto_2013();
            $data['areas_salidas']= $this->model_comercial->listar_areas_salidas();
            $data['salidaproducto']= $this->model_comercial->listaSalidaProducto();
            /*
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            */
            $this->load->view('comercial/menu');
            $this->load->view('comercial/salida_almacen/devolucion_productos', $data);
        }
    }

    public function exportar_doc_salida(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfSalidaproducto');
        // Obtener las variables
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $id_salida_producto = $data[0];
        // Creacion del PDF
        // Se crea un objeto de la clase Pdf, recuerda que la clase Pdf heredó todos las variables y métodos de fpdf
        $this->pdf = new PdfSalidaproducto();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
        // Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
        $this->pdf->SetTitle("Documento de Salida");
        $this->pdf->SetLeftMargin(20);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        /*
            TITULOS DE COLUMNAS - EJEMPLO
            $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
            $this->pdf->Cell(245,9,utf8_decode('LISTA DE PROVEEDORES'),'TBR TBL',0,'C','1');
        */

        $result = $this->model_comercial->getSalidasProductos_print_cabecera($id_salida_producto);
        foreach ($result as $row){
            // variable de observacion
            $observacion = $row->observacion;
            $solicitante = $row->solicitante;
            $id_salida_producto = $row->id_salida_producto;
            /* Formato para la fecha inicial */
            $elementos = explode("-", $row->fecha);
            $anio = $elementos[0];
            $mes = $elementos[1];
            $dia = $elementos[2];
            $array = array($dia, $mes, $anio);
            $fecharegistro = implode("/", $array);

            $this->pdf->SetFont('Arial','B',7);
            $this->pdf->Cell(25,9,utf8_decode('GENERADO EL: '.date('d-m-Y')),0,0,'C');
            $this->pdf->Cell(240,9,utf8_decode('NOTA DE SALIDA 00000'.$id_salida_producto),0,0,'C');
            $this->pdf->Ln(9);
            $this->pdf->SetFont('Arial','B',12);
            $this->pdf->Cell(155,9,utf8_decode('TEJIDOS JORGITO SAC'),0,0,'C');
            $this->pdf->Ln(4);
            $this->pdf->SetFont('Arial','B',7);

            $this->pdf->Cell(30,20,utf8_decode("ALMACEN : "),'',0,'R','0');
            $this->pdf->Cell(53,20,"ALMACEN DE REPUESTOS",'',0,'L','0');
            $this->pdf->Cell(30,20,utf8_decode("MOTIVO : "),'',0,'R','0');
            $this->pdf->Cell(35,20,"DESPACHO DE REPUESTOS",'',0,'L','0');

            $this->pdf->Ln(4);
            $this->pdf->Cell(30,20,utf8_decode("EMPLEADO : "),'',0,'R','0');
            $this->pdf->Cell(53,20,utf8_decode($solicitante),'',0,'L','0');
            $this->pdf->Cell(30,20,utf8_decode("MÁQUINA : "),'',0,'R','0');
            $this->pdf->Cell(35,20,utf8_decode($row->nombre_maquina),'',0,'L','0');

            $this->pdf->Ln(4);
            $this->pdf->Cell(30,20,utf8_decode("APROBADO POR : "),'',0,'R','0');
            $this->pdf->Cell(53,20,"EDIN MONTES",'',0,'L','0');
            $this->pdf->Cell(30,20,utf8_decode("PARTE : "),'',0,'R','0');
            $this->pdf->Cell(35,20,utf8_decode($row->nombre_parte_maquina),'',0,'L','0');

            $this->pdf->Ln(4);
            $this->pdf->Cell(30,20,utf8_decode("FECHA DE EMISION : "),'',0,'R','0');
            $this->pdf->Cell(53,20,$fecharegistro,'',0,'L','0');
            $this->pdf->Cell(30,20,utf8_decode("ÁREA : "),'',0,'R','0');
            $this->pdf->Cell(35,20,utf8_decode($row->no_area),'',0,'L','0');
        }

        $this->pdf->Ln(4);
        $this->pdf->Cell(30,20,utf8_decode("OBSERVACIÓN : "),'',0,'R','0');
        $this->pdf->Cell(53,20,$observacion,'',0,'L','0');

        $this->pdf->Ln(18);
        $this->pdf->Cell(8,6,utf8_decode('N°'),'BLTR',0,'C','0'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
        $this->pdf->Cell(14,6,utf8_decode('CÓDIGO'),'BLTR',0,'C','0');
        $this->pdf->Cell(100,6,utf8_decode('NOMBRE O DESCRIPCIÓN'),'BLTR',0,'C','0');
        $this->pdf->Cell(18,6,utf8_decode('UBICACIÓN'),'BLTR',0,'C','0');
        $this->pdf->Cell(15,6,utf8_decode('MED.'),'BLTR',0,'C','0');
        $this->pdf->Cell(15,6,utf8_decode('CANT.'),'BLTR',0,'C','0');
        // $this->pdf->Cell(40,6,utf8_decode('OBSERVACIÓN'),'BLTR',0,'C','0');
        $this->pdf->Ln(6);


        $result_detalle_salida = $this->model_comercial->getSalidasProductos_print_detalle_salida($id_salida_producto);
        
        $x = 1;
        $this->pdf->SetFont('Arial','B',6);
        foreach ($result_detalle_salida as $item) {
            $this->pdf->Cell(8,6,$x++,'BLTR',0,'C',0);
            $this->pdf->Cell(14,6,utf8_decode('PRD'.$item->id_pro),'BLTR',0,'C',0);
            $this->pdf->Cell(100,6,utf8_decode($item->no_producto),'BLTR',0,'C',0);
            $this->pdf->Cell(18,6,utf8_decode($item->nombre_ubicacion),'BLTR',0,'C',0);
            $this->pdf->Cell(15,6,utf8_decode($item->nom_uni_med),'BLTR',0,'C',0);
            $this->pdf->Cell(15,6,utf8_decode($item->cantidad_salida),'BLTR',0,'C',0);
            // $this->pdf->Cell(40,6,utf8_decode($observacion),'BLTR',0,'C',0);
            $this->pdf->Ln(6);
        }
        $this->pdf->Cell(20,20,utf8_decode('OBSERVACIONES'),'',0,'C','0');
        $this->pdf->Ln(6);
        $this->pdf->Cell(10,20,utf8_decode("__________________________________________________________________________________________________________________________________________"),'',0,'L','0');
        // Firma de Conformidad
        $this->pdf->Ln(15);
        $this->pdf->Cell(20,20,utf8_decode(" "),'',0,'L','0');
        $this->pdf->Cell(10,20,utf8_decode("______________________________________________"),'',0,'L','0');
        $this->pdf->Cell(66,20,utf8_decode(" "),'',0,'L','0');
        $this->pdf->Cell(60,20,utf8_decode("______________________________________________"),'',0,'L','0');
        $this->pdf->Ln(4);
        $this->pdf->Cell(30,20,utf8_decode(" "),'',0,'L','0');
        $this->pdf->Cell(35,20,utf8_decode('EDIN MONTES'),'',0,'C','0');
        $this->pdf->Cell(45,20,utf8_decode(" "),'',0,'L','0');
        $this->pdf->Cell(30,20,utf8_decode($solicitante),'',0,'C','0');
        
        /*
         * Se manda el pdf al navegador
         *
         * $this->pdf->Output(nombredelarchivo, destino);
         *
         * I = Muestra el pdf en el navegador
         * D = Envia el pdf para descarga
         *
         */
        $this->pdf->Output("Documento de Salida $id_salida_producto.pdf", 'D');
    }

    public function gestiontipocambio(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['tipoCambio']= $this->model_comercial->listarTipoCambio();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/tipo_cambio/tipo_cambio', $data);
        }
    }

    public function obtener_datos_salida(){
        $id_salida_producto = $this->input->post('id_salida_producto');
        $id_detalle_producto = $this->input->post('id_detalle_producto');
        $resultado = $this->model_comercial->get_datos_detalle_pedido($id_salida_producto,$id_detalle_producto);
        if (count($resultado) > 0){
            foreach ($resultado as $data) {
                $array = array(
                    "id_area" => $data['id_area'],
                    "no_producto" => $data['no_producto'],
                    "cantidad" => $data['cantidad_salida'],
                    "stock" => $data['stock'],
                    "solicitante" => $data['solicitante'],
                    "id_maquina" => $data['id_maquina'],
                    "id_parte_maquina" => $data['id_parte_maquina'],
                    "fecha" => $data['fecha'],
                    "nom_uni_med" => $data['nom_uni_med'],
                    "id_salida_producto" => $data['id_salida_producto'],
                );
            }
            echo '' . json_encode($array) . '';
        }else{
            echo 'vacio';
        }
    }

    public function gestioningreso(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['listaarea']= $this->model_comercial->listarArea();
            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
            $data['listacomprobante']= $this->model_comercial->listaComprobante();
            $data['listaproveedor']= $this->model_comercial->listaProveedor();
            $data['listasimmon']= $this->model_comercial->listaSimMon();
            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/comprobantes/registro_ingreso', $data);
        }
    }

    public function gestioncuadreinventario(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['listaarea']= $this->model_comercial->listarArea();
            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/view_cuadre_inventario', $data);
        }
    }
    
    public function gestionreportkardexproducto(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/view_kardex_producto');
        }
    }

    public function gestionreportsunat(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/view_kardex_sunat');
        }
    }

    public function gestionreportentrada(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/view_report_entrada');
        }
    }

    public function gestionreportmensual(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/view_report_mensual');
        }
    }

    public function gestionreportsalida(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/view_report_salida');
        }
    }

    public function gestionreportkardexgeneral(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['indice']= $this->model_comercial->ComboIndice();
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/view_kardex_general',$data);
        }
    }

    public function traer_producto_autocomplete_traslado() {
        
        $termino = strtoupper($this->input->post('q'));
        $id_area = strtoupper($this->input->post('a'));
        $resultado = $this->model_comercial->get_nombre_producto_autocomplete_traslado($termino, $id_area);

        $array = array( "label" => "no se encontraron resultados" );

        if ($resultado != null) {
            $data = array();
            foreach ($resultado as $producto) {
                $array = array(
                    "label" => $producto['no_producto'],
                    "nombre_producto" => $producto['no_producto'],
                    "id_detalle_producto" => $producto['id_detalle_producto'],
                    "stock_sta_anita" => $producto['stock_area_sta_anita'],
                    "stock_sta_clara" => $producto['stock_area_sta_clara'],
                    "column_temp" => $producto['column_temp']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function traer_producto_autocomplete() {
        $termino = strtoupper($this->input->post('q'));
        $resultado = $this->model_comercial->get_nombre_producto_autocomplete($termino);

        $array = array( "label" => "no se encontraron resultados" );

        if ($resultado != null) {
            $data = array();
            foreach ($resultado as $producto) {
                $array = array(
                    "label" => $producto['no_producto'],
                    "nombre_producto" => $producto['no_producto']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function traer_producto_autocomplete_consultar_salidas() {
        $termino = strtoupper($this->input->post('q'));
        $resultado = $this->model_comercial->get_nombre_producto_autocomplete_consultar_salidas($termino);

        $array = array( "label" => "no se encontraron resultados" );

        if ($resultado != null) {
            $data = array();
            foreach ($resultado as $producto) {
                $array = array(
                    "label" => $producto['no_producto'],
                    "nombre_producto" => $producto['no_producto']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function traer_proveedor_autocomplete() {
        
        $termino = strtoupper($this->input->post('q'));
        $resultado = $this->model_comercial->get_nombre_proveedor_autocomplete($termino);

        $array = array( "label" => "no se encontraron resultados" );

        if ($resultado != null) {
            $data = array();
            foreach ($resultado as $proveedor) {
                $array = array(
                    "label" => $proveedor['razon_social'],
                    "razon_social" => $proveedor['razon_social']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function traer_producto_autocomplete_salida() {
        
        $termino = strtoupper($this->input->post('q'));
        $id_area = strtoupper($this->input->post('a'));
        $resultado = $this->model_comercial->get_nombre_producto_autocomplete_salida($termino);

        $array = array( "label" => "no se encontraron resultados" );

        if ($resultado != null) {
            $data = array();
            foreach ($resultado as $producto) {
                $array = array(
                    "label" => $producto['no_producto'],
                    "nombre_producto" => $producto['no_producto']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function traer_producto_autocomplete_with_id() {
        
        $termino = strtoupper($this->input->post('q'));
        $resultado = $this->model_comercial->get_nombre_producto_autocomplete_with_id($termino);

        $array = array( "label" => "no se encontraron resultados" );

        if ($resultado != null) {
            $data = array();
            foreach ($resultado as $producto) {
                $array = array(
                    "label" => $producto['no_producto'],
                    "nombre_producto" => $producto['no_producto'],
                    "id_detalle_producto" => $producto['id_detalle_producto']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    public function traerUnidadMedida_Autocompletado()
    {
        $nombre_producto = $this->input->get('nombre_producto');
        $variable = $this->model_comercial->getDataUnidadMedida($nombre_producto);
        foreach($variable as $fila){
            echo $fila->nom_uni_med;
        }
    }

    public function buscar_nombre_producto_autocompletar(){
        $nombre_producto = $this->model_comercial->getNombreProducto_autocompletar();
        if( $nombre_producto != ""){
            foreach($nombre_producto as $dato){
                echo "<li>".$dato->no_producto."</li>";
            }
        }
    }

    public function traerStock_Autocompletado()
    {
        $nombre_producto = $this->input->get('nombre_producto');
        $variable = $this->model_comercial->getDataStock($nombre_producto);
        foreach($variable as $fila){
            echo $fila->stock;
        }
    }

    public function traer_stock_general_cuadre()
    {
        $nombre_producto = $this->input->get('nombre_producto');
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $variable = $this->model_comercial->getDataStock_general_cuadre($nombre_producto);
        if($id_almacen == 1){
            foreach($variable as $fila){
                echo $fila->stock_sta_clara;
            }
        }else if($id_almacen == 2){
            foreach($variable as $fila){
                echo $fila->stock;
            }
        }
    }
    
    public function gestionreporteproducto(){
        $data['listaprocedencia'] = $this->model_comercial->listarProcedencia();
        $data['listasimmon']= $this->model_comercial->listaSimMon();
        $data['listacategoria'] = $this->model_comercial->listarCategoria();
        $data['listamaquina']= $this->model_comercial->listarMaquinas();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestion_reporte_producto',$data);
    }

    public function gestionreporteingreso(){
        $data['listaagente']= $this->model_comercial->listaAgenteAduana();
        $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
        $data['listaproveedor']= $this->model_comercial->listaProveedor();
        $data['listasimmon']= $this->model_comercial->listaSimMon();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestion_reporte_ingreso',$data);
    }

    public function gestionreporteingreso_otros(){
        $data['listacomprobante']= $this->model_comercial->listarComprobantes();
        $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
        $data['listaproveedor']= $this->model_comercial->listaProveedor();
        $data['listasimmon']= $this->model_comercial->listaSimMon();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestion_reporte_ingreso_otros',$data);
    }

    public function gestionreportesalida(){
        $data['listaarea']= $this->model_comercial->listarArea();
        $data['listamaquina']= $this->model_comercial->listarMaquinas();
        $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestion_reporte_salida',$data);
    }
    
    public function gestionreportemaquina(){
        $data['estado']= $this->model_comercial->listarEstado();
        $data['listamaquina']= $this->model_comercial->listarMaquinas();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestion_reporte_maquina',$data);
    }

    public function gestionreporteproveedor(){
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestion_reporte_proveedor');
    }

    public function gestionmaquinas(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['estado']= $this->model_comercial->listarEstado();
            $data['maquina']= $this->model_comercial->listarMaquinaRegistradas();
            $data['parte_maquina']= $this->model_comercial->listarParteMaquinaRegistradas();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/maquinas/registrar_maquinas',$data);
        }
    }

    public function gestion_parte_maquina(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['lista_maquina']= $this->model_comercial->listarMaquinaCombo();
            $data['parte_maquina']= $this->model_comercial->listarParteMaquinaRegistradas();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/maquinas/registrar_parte_maquinas',$data);
        }
    }

    public function registrarproveedor(){
        $this->load->view('comercial/menu');
        $this->load->view('comercial/registrar_proveedor');
    }

    public function cierre_almacen(){
        $fecha_cierre = $this->security->xss_clean($this->input->post('fecha_cierre'));
        /* Procedimiento de validación */
        $result_validacion = $this->model_comercial->validacion_cierre_almacen_model($fecha_cierre);
        /* Fin */
        if($result_validacion == 'validacion_conforme'){
            $result = $this->model_comercial->cierre_almacen_model($fecha_cierre); /* Guarda el detalle del cierre en la tabla saldos_iniciales */
            if(!$result){
                echo '<span style="color:red">ERROR: No se puede guardar</span>';
            }else{
                $result_monto = $this->model_comercial->cierre_almacen_montos_model($fecha_cierre); /* guarda el monto de cierre del mes en la tabla monto_cierre */
                if(!$result_monto){
                    echo 'error_validacion_monto';
                }else{
                    echo 'ok';
                }
            }
        }else if($result_validacion == 'error_validacion'){
            echo 'error_validacion';
        }
    }
    
    public function gestioncierrealmacen(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['monto']= $this->model_comercial->listarMontoCierre();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/view_cierre_almacen', $data);
        }
    }

    public function nuevonombremaquina(){
        $data['nombremaquinas']= $this->model_comercial->listarNombreMaquinas();
        //$data['listamaquina']= $this->model_comercial->listarMaquinas();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/maquinas/nuevo_nombre_maquina', $data);
    }

    public function marcamaquina(){
        //$data['nombremaquinas']= $this->model_comercial->listarNombreMaquinas();
        //$data['listamaquina']= $this->model_comercial->listarMaquinas();
        $data['marcamaquinas']= $this->model_comercial->listarMarcaMaquinas();
        $data['listamaquina']= $this->model_comercial->listarMaquinas();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/maquinas/marca_maquina', $data);
    }

    public function modelomaquina(){
        $data['modelomaquinas']= $this->model_comercial->listarModeloMaquinas();
        $data['listamarca']= $this->model_comercial->listarMarca();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/maquinas/modelo_maquina',$data);
    }

    public function seriemaquina(){
        $data['seriemaquinas']= $this->model_comercial->listarSerieMaquinas();
        $data['listamodelo']= $this->model_comercial->listarModelo();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/maquinas/serie_maquina',$data);
    }

    public function gestionmoneda(){
        $data['listamoneda']= $this->model_comercial->listarMoneda();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestionar_moneda',$data);
    }

    public function gestionaduana(){
        $data['aduana']= $this->model_comercial->listarAduana();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/agente_aduana/gestionar_agente_aduana',$data);
    }

    public function gestioncomprobante(){
        $data['comprobante']= $this->model_comercial->listarComprobantes_lista();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/gestionar_comprobante',$data);
    }

    public function gestionconsultarRegistros(){
        $data['registros']= $this->model_comercial->listaRegistros();
        $data['listaproveedor']= $this->model_comercial->listaProveedor();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/comprobantes/consulta_registros_ingreso', $data);
    }

    public function gestionconsultarRegistros_optionsAdvanced(){
        $data['registros']= $this->model_comercial->listaRegistros();
        $data['listaproveedor']= $this->model_comercial->listaProveedor();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/comprobantes/consulta_registros_ingreso_optionsAdvanced', $data);
    }

    public function gestiontraslados(){
        if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
        }else{
            $data['tipocambio'] = 1;
        }
        $data['listaarea']= $this->model_comercial->listarArea();
        $data['listaalmacen_partida']= $this->model_comercial->listaAlmacenCombo_traslado_inicio();
        $data['listaalmacen_llegada']= $this->model_comercial->listaAlmacenCombo_traslado_llegada();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/traslados', $data);
    }

    public function agregar_detalle_producto_traslado_ajax(){
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $nombre_producto = $this->input->post('nombre_producto');
        $cantidad = $this->input->post('cantidad');
        $id_area = $this->input->post('id_area');
        /* Get data product */
        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
        }
        // Obtengo los datos del producto
        $this->db->select('id_pro');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
        }
        // Datos del area
        $this->db->select('no_area');
        $this->db->where('id_area',$id_area);
        $query = $this->db->get('area');
        foreach($query->result() as $row){
            $no_area = $row->no_area;
        }
        $arr1 = explode(" ", $no_area);

        $this->db->select('id_detalle_producto_area,stock_area_sta_anita,stock_area_sta_clara');
        $this->db->where('id_pro',$id_pro);
        $this->db->where('id_area',$id_area);
        $query = $this->db->get('detalle_producto_area');
        if(count($query->result()) > 0){
            foreach($query->result() as $row){
                $id_detalle_producto_area = $row->id_detalle_producto_area;
                $stock_area_sta_anita = $row->stock_area_sta_anita;
                $stock_area_sta_clara = $row->stock_area_sta_clara;
            }
            if($id_almacen == 1){ // Sta. Clara
                if($cantidad > $stock_area_sta_clara){
                    echo 'stock_insuficiente';
                }else{
                    $data = array(
                        'id' => $id_detalle_producto,
                        'qty' => $cantidad,
                        'price' => 5,
                        'name'=> $nombre_producto,
                        'options'=> $arr1
                    );
                    $this->cart->insert($data);
                    echo 'successfull';
                }
            }if($id_almacen == 2){ // Sta. Anita
                if($cantidad > $stock_area_sta_anita){
                    echo 'stock_insuficiente';
                }else{
                    $data = array(
                        'id' => $id_detalle_producto,
                        'qty' => $cantidad,
                        'price' => 5,
                        'name'=> $nombre_producto,
                        'options'=> $arr1
                    );
                    $this->cart->insert($data);
                    echo 'successfull';
                }
            }
        }else{
            echo 'error_get_data';
        }
    }

    public function finalizar_registro_traslado()
    {
        $this->db->trans_begin();
        // Obtengo variables
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $id_almacen_partida = $this->security->xss_clean($this->input->post("id_almacen_partida"));
        $id_almacen_llegada = $this->security->xss_clean($this->input->post("id_almacen_llegada"));
        $fecharegistro = $this->security->xss_clean($this->input->post("fecharegistro"));
        // Obtengo variables de la libreria Cart
        $carrito = $this->cart->contents();
        
        $datos = array(
            "id_almacen_partida" => $id_almacen_partida,
            "id_almacen_llegada" => $id_almacen_llegada,
            "fecha_traslado" => $fecharegistro
        );
        $id_ingreso_traslado = $this->model_comercial->agrega_ingreso_traslado($datos);
        if(!$id_ingreso_traslado){
            echo '3';
        }else{
            // Agregamos el detalle del comprobante
            $result = $this->model_comercial->agregar_detalle_ingreso_traslado($carrito, $id_ingreso_traslado, $fecharegistro, $id_almacen);
            if(!$result){
                echo '2';
            }else{
                $this->cart->destroy();
                echo '1';
            }
        }
        $this->db->trans_complete();
    }

    function remove_traslados($rowid){
        $this->cart->update(array(
            'rowid' => $rowid,
            'qty' => 0
        ));
        redirect('comercial/gestiontraslados');
    }

    function vaciar_listado_traslado(){
        $this->cart->destroy();
        redirect('comercial/gestiontraslados');
    }

    public function gestionconsultarSalidaRegistros(){
        $data['salidaproducto']= $this->model_comercial->listaSalidaProducto();
        $data['listaarea']= $this->model_comercial->listarArea();
        $data['listamaquina']= $this->model_comercial->listarMaquinas();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/salida_almacen/consulta_registros_salida', $data);
    }

    public function consultar_traslado_productos(){
        $data['trasladoproducto']= $this->model_comercial->listaTrasladoProducto();
        // $data['listaarea']= $this->model_comercial->listarArea();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/consultar_traslado_productos', $data);
    }

    public function gestionconsultarRegistros_otros(){
        $data['registros']= $this->model_comercial->listaRegistros_otros();
        $data['listaproveedor']= $this->model_comercial->listaProveedor();
        $data['listacomprobante']= $this->model_comercial->listarComprobantes();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/consulta_registros_ingreso_otros', $data);
    }

    public function gestionotrosDoc(){
        //$this->cart->destroy();
        $data['listaagente']= $this->model_comercial->listaAgenteAduana();
        $data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
        $data['listacomprobante']= $this->model_comercial->listarComprobantes();
        $data['listaproveedor']= $this->model_comercial->listaProveedor();
        $data['listasimmon']= $this->model_comercial->listaSimMon();
        $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/registro_ingreso_otros', $data);
    }

    public function gestionarea(){
        $data['listaarea']= $this->model_comercial->listarAreaE();
        //$data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
        $this->load->view('comercial/menu');
        $this->load->view('comercial/area_responsable', $data);
    }

    public function registrartipocambio()
    {
        $this->form_validation->set_rules('fecha_registro', 'Fecha de Registro', 'trim|required|min_length[1]|max_length[10]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 10 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->saveTipoCambio_vista();          
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo 'Ya existe Tipo de Cambio para la fecha seleccionada. Verificar!';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function registrarproducto()
    {
        $result = $this->model_comercial->saveProducto();      
        // Verificamos que existan resultados
        if($result =='registro_correcto'){
            echo '1';
        }else if($result =='unidad_no_existe'){
            echo 'unidad_no_existe';
        }else if($result =='nombre_producto'){
            echo 'nombre_producto';
        }else if($result =='ubicacion_no_existe'){
            echo 'ubicacion_no_existe';
        }else if($result =='error_registro'){
            echo 'error_registro';
        }
    }

    public function registrarnombremaquina()
    {
        $this->form_validation->set_rules('nombre', 'Nombre de Nueva Máquina', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 30 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            //$data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Debe Ingresar un Nombre de Máquina.</span>';
            $data['nombremaquinas']= $this->model_comercial->listarNombreMaquinas();
            $data['listamaquina']= $this->model_comercial->listarMaquinas();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/maquinas/nuevo_nombre_maquina', $data);
        }
        else
        {
            $result = $this->model_comercial->saveNombreMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Este Tipo de Máquina ya se encuentra registrado.</span>';
                $data['nombremaquinas']= $this->model_comercial->listarNombreMaquinas();
                $data['listamaquina']= $this->model_comercial->listarMaquinas();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/maquinas/nuevo_nombre_maquina', $data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/nuevonombremaquina');
            
            }
        }
    }

    public function registrarmarcamaquina()
    {
        $this->form_validation->set_rules('nombre', 'Nombre de Nueva Máquina', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('maquina', 'Tipo de Máquina', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 30 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            //$data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Debe Ingresar un Nombre de Máquina.</span>';
            $data['marcamaquinas']= $this->model_comercial->listarMarcaMaquinas();
            $data['listamaquina']= $this->model_comercial->listarMaquinas();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/maquinas/marca_maquina', $data);
        }
        else
        {
            $result = $this->model_comercial->saveMarcaMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Esta Marca de Máquina ya se encuentra registrada.</span>';
                $data['marcamaquinas']= $this->model_comercial->listarMarcaMaquinas();
                $data['listamaquina']= $this->model_comercial->listarMaquinas();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/maquinas/marca_maquina', $data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/marcamaquina');
            
            }
        }
    }

    public function registrararea()
    {
        $this->form_validation->set_rules('area', 'Área', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('nombre', 'Responsable', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 30 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            if($this->input->post('nombre') == "" AND $this->input->post('area') == ""){
                $data['respuesta_ambos'] = '<span style="color:red"><b>ERROR:</b> Falta completar los campos.</span>';
            }else if($this->input->post('area') == ""){
                $data['respuesta_area'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Área.</span>';
            }else if($this->input->post('nombre') == ""){
                $data['respuesta_responsable'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Responsable.</span>';
            }
            $data['listaarea']= $this->model_comercial->listarAreaE();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/area_responsable', $data);
        }
        else
        {
            $result = $this->model_comercial->saveArea();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                $data['listaarea']= $this->model_comercial->listarAreaE();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/area_responsable', $data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/gestionarea');
            
            }
        }
    }

        public function registrarmodelomaquina()
    {
        $this->form_validation->set_rules('nombre', 'Modelo de la Marca', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('marca', 'Marca de la Máquina', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 30 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            //$data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Debe Ingresar un Nombre de Máquina.</span>';
            $data['modelomaquinas']= $this->model_comercial->listarModeloMaquinas();
            $data['listamarca']= $this->model_comercial->listarMarca();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/maquinas/modelo_maquina',$data);
        }
        else
        {
            $result = $this->model_comercial->saveModeloMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Este Modelo de Máquina ya se encuentra registrado.</span>';
                $data['marcamaquinas']= $this->model_comercial->listarMarcaMaquinas();
                $data['modelomaquinas']= $this->model_comercial->listarModeloMaquinas();
                $data['listamarca']= $this->model_comercial->listarMarca();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/maquinas/modelo_maquina',$data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/modelomaquina');
            
            }
        }
    }

        public function registrarseriemaquina()
    {
        $this->form_validation->set_rules('serie', 'Serie del Modelo', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('modelo', 'Modelo de la Máquina', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 30 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            //$data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Debe Ingresar un Nombre de Máquina.</span>';
            $data['seriemaquinas']= $this->model_comercial->listarSerieMaquinas();
            $data['listamodelo']= $this->model_comercial->listarModelo();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/maquinas/serie_maquina',$data);
        }
        else
        {
            $result = $this->model_comercial->saveSerieMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Esta Serie de Máquina ya se encuentra registrado.</span>';
                $data['seriemaquinas']= $this->model_comercial->listarSerieMaquinas();
                $data['listamodelo']= $this->model_comercial->listarModelo();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/maquinas/serie_maquina',$data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/seriemaquina');
            
            }
        }
    }

    public function registrarmoneda()
    {
        $this->form_validation->set_rules('nombre', 'Nombre de Moneda', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('simbolo', 'Símbolo de Moneda', 'trim|required|min_length[1]|max_length[10]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 10 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            //$data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Debe Ingresar un Nombre de Máquina.</span>';
            $data['listamoneda']= $this->model_comercial->listarMoneda();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/gestionar_moneda',$data);
        }
        else
        {
            $result = $this->model_comercial->saveMoneda();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Este Nombre de Moneda ya se encuentra registrado.</span>';
                $data['listamoneda']= $this->model_comercial->listarMoneda();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/gestionar_moneda',$data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/gestionmoneda');
            
            }
        }
    }

    public function registraraduana()
    {
        $this->form_validation->set_rules('nombre', 'Nombre del Agente Aduananero', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 10 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            $data['error']= validation_errors();
            //$data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Debe Ingresar un Nombre de Máquina.</span>';
            $data['aduana']= $this->model_comercial->listarAduana();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/agente_aduana/gestionar_agente_aduana',$data);
        }
        else
        {
            $result = $this->model_comercial->saveAgente();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Este Agente Aduanero ya se encuentra registrado.</span>';
                $data['aduana']= $this->model_comercial->listarAduana();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/agente_aduana/gestionar_agente_aduana',$data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/gestionaduana');
            
            }
        }
    }

    public function registrarcomprobante()
    {
        $this->form_validation->set_rules('nombre', 'Nombre del Tipo de Comprobante', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 10 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            $data['error']= validation_errors();
            //$data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Debe Ingresar un Nombre de Máquina.</span>';
            $data['comprobante']= $this->model_comercial->listarComprobantes_lista();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/gestionar_comprobante',$data);
        }
        else
        {
            $result = $this->model_comercial->saveComprobante();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //echo "<script languaje='javascript'>alert('Hola')</script>";
                //echo '<span style="color:red"><b>ERROR:</b> Este Nombre de Máquina ya se encuentra registrado.</span>';
                //echo “<script languaje=’javascript’>alert(‘Material en depósito : “.$descripcion_material.”‘)</script>”;
                $data['respuesta'] = '<span style="color:red"><b>ERROR:</b> Este Tipo de Comprobante ya se encuentra registrado.</span>';
                $data['comprobante']= $this->model_comercial->listarComprobantes_lista();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/gestionar_comprobante',$data);
            }else{
                //Registramos la sesion del usuario
                redirect('comercial/gestioncomprobante');
            
            }
        }
    }

    public function registrarmaquina(){
        $result = $this->model_comercial->saveMaquina();
        if($result == 'duplicidad'){
            echo 'Esta Máquina ya se encuentra registrada. Verificar!';
        }else if($result == 'successfull'){
            echo '1';
        }
    }

    public function nuevo_proveedor(){

        // $this->form_validation->set_rules('ruc', 'RUC', 'trim|required|min_length[11]|max_length[11]|xss_clean');
        $this->form_validation->set_rules('rz', 'Razón Social', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        $this->form_validation->set_rules('pais', 'País', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        $this->form_validation->set_rules('direccion', 'Direccion', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('telefono1', 'Teléfono 1', 'trim|min_length[7]|max_length[20]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->saveProveedor();
            if(!$result){
                echo '<span style="color:red"><b>ERROR:</b> El RUC ya se encuentra registrado.</span>';
                
            }else{
                redirect('comercial/gestionproveedores');
            }
        }
    }

    public function registroingresoprod(){

        $this->form_validation->set_rules('cantidad', 'Cantidad', 'trim|required|min_length[1]|max_length[10]|xss_clean');
        $this->form_validation->set_rules('pt', 'Precio Total', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        $this->form_validation->set_rules('numcomprobante', 'N° de Comprobante', 'trim|required|min_length[1]|max_length[40]|xss_clean');
        $this->form_validation->set_rules('fecharegistro', 'Fecha de Registro', 'trim|required|min_length[1]|max_length[40]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->saveRegistroIngreso();
            //$data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                //$this->form_validation->set_error_delimiters('<span style="color:red;font-size:12px"><b>ERROR:</b> El RUC ya se encuentra registrado.', '</span><br>');
                echo '<span style="color:red"><b>ERROR:</b> El Producto ya se encuentra registrado con este N° de Comprobante.</span>';
            }else{
                $data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
                $data['listacomprobante']= $this->model_comercial->listaComprobante();
                $data['listaproveedor']= $this->model_comercial->listaProveedor();
                $data['listasimmon']= $this->model_comercial->listaSimMon();
                $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                $data['listaarea']= $this->model_comercial->listarArea();
                $this->load->view('comercial/menu');
                $this->load->view('comercial/comprobantes/registro_ingreso', $data);
            }
        }
    }
/*
    public function listaregistroingreso(){
        $this->form_validation->set_rules('numcomprobante', 'N° de Comprobante', 'trim|required|min_length[1]|max_length[40]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','<b>ERROR:</b> El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','<b>ERROR:</b> El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            //$data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
            echo "hola";
        }
    }
*/

    public function datosRucExiste()
    {
        $datos = $this->model_comercial->getDatosRucExiste();
        echo '<table width="750" border="0" cellspacing="1" cellpadding="1">
              <tr class="tituloTable">
                <td width="100">RUC</td>
                <td width="300">RAZON SOCIAL</td>
                <td width="300" >DIRECCIÓN</td>
              </tr>';
        foreach($datos as $fila)
        {
            echo '<tr class="contentTable">
                    <td>'.$fila->ruc.'</td>
                    <td>'.$fila->razon_social.'</td>
                    <td>'.$fila->direccion.'</td>
                  </tr>';
        }
        echo '</table>';
    }

    public function existeRuc()
    {
        $existe = $this->model_comercial->existeRuc();
        if($existe){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function editarmaquina(){
        $data['editestado']= $this->model_comercial->listarEstado();
        $data['datosmaq']= $this->model_comercial->getMaqEditar();
        $this->load->view('comercial/maquinas/actualizar_maquina', $data);
    }

    public function editar_parte_maquina(){
        $data['lista_maquina']= $this->model_comercial->listarMaquinaCombo();
        $data['datos_parte_maq']= $this->model_comercial->getParteMaqEditar();
        $this->load->view('comercial/maquinas/actualizar_parte_maquina', $data);
    }

    public function editarnombremaquina(){
        $data['datosnommaq']= $this->model_comercial->getNomMaqEditar();
        $this->load->view('comercial/maquinas/actualizar_nombre_maquina', $data);
    }

    public function editararea(){
        $data['datosarea']= $this->model_comercial->getDatosArea();
        $this->load->view('comercial/actualizar_area', $data);
    }

    public function editarseriemaquina(){
        $data['listamodelo']= $this->model_comercial->listarModelo();
        $data['datossermaq']= $this->model_comercial->getSerMaqEditar();
        $this->load->view('comercial/maquinas/actualizar_serie_maquina', $data);
    }

    public function editarmarcamaquina(){
        $data['listamaquina']= $this->model_comercial->listarMaquinas();
        $data['datosmarmaq']= $this->model_comercial->getMarMaqEditar();
        $this->load->view('comercial/maquinas/actualizar_marca_maquina', $data);
    }

    public function editarmodelomaquina(){
        $data['listamarca']= $this->model_comercial->listarMarca();
        $data['datosmodmaq']= $this->model_comercial->getModMaqEditar();
        $this->load->view('comercial/maquinas/actualizar_modelo_maquina', $data);
    }

    public function editarmoneda(){
        $data['datosmoneda']= $this->model_comercial->getDatosMoneda();
        $this->load->view('comercial/actualizar_moneda', $data);
    }

    public function editaragente(){
        $data['agente']= $this->model_comercial->getDatosAgente();
        $this->load->view('comercial/agente_aduana/actualizar_agente', $data);
    }

    public function editarcomprobante(){
        $data['comprobante']= $this->model_comercial->getDatosComprobante();
        $this->load->view('comercial/actualizar_comprobante', $data);
    }

    public function editarproducto(){
        $id_pro = $this->security->xss_clean($this->uri->segment(3));
        $data['listacat'] = $this->model_comercial->listarCategoria();
        $data['listatipop'] = $this->model_comercial->listarTipoProdCombo($id_pro);
        $data['listaunimed'] = $this->model_comercial->listarUnidadMedidaCombo();
        $data['listaproce'] = $this->model_comercial->listarProcedencia();
        $data['datosprod']= $this->model_comercial->getProdEditar();
        $this->load->view('comercial/productos/actualizar_producto', $data);
    }

    public function editartipocambio(){
        $data['datosTC']= $this->model_comercial->getTCEditar();
        $this->load->view('comercial/tipo_cambio/actualizar_tipo_cambio', $data);   
    }

    public function mostrardetalle(){
        $data['detFac']= $this->model_comercial->getDetalleFactura();
        $data['detProd']= $this->model_comercial->getDetalleProd();
        $this->load->view('comercial/comprobantes/mostrar_detalle', $data);
    }

    public function mostrarDetalleSalidas(){
        $id_area = $this->security->xss_clean($this->uri->segment(3));
        $fecha_actual = date('Y-m-d');
        $data['dataSalidas']= $this->model_comercial->getSalidasProductos($id_area, $fecha_actual);
        $this->load->view('comercial/salida_almacen/mostrar_detalle_salidas', $data);
    }

    public function print_pdf_salidas_area(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfSalidaProductos');
        // Obtener las variables
        $id_area = $this->security->xss_clean($this->uri->segment(3));
        $fecha_actual = date('Y-m-d');

        /* Formato para la fecha inicial */
        $elementos = explode("-", $fecha_actual);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        $array = array($dia, $mes, $anio);
        $fecha_formateada = implode("-", $array);

        // Creacion del PDF

        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
        */
        $this->pdf = new PdfSalidaProductos();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
        */
        $this->pdf->SetTitle("Documento de Salida");
        $this->pdf->SetLeftMargin(25);
        $this->pdf->SetRightMargin(25);
        $this->pdf->SetFillColor(200,200,200);

        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 7);

        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));

        if($almacen == 1){
            $nombre_almacen = "SANTA CLARA";
            $nombre_encargado = "GUILLERMO SANCHEZ FLORES";
        }else if($almacen == 2){
            $nombre_almacen = "SANTA ANITA";
            $nombre_encargado = "ASCENCIO OSORIO AMADOR";
        }

        // Obtener el nombre del almacen
        $this->db->select('no_area');
        $this->db->where('id_area',$id_area);
        $query = $this->db->get('area');
        if(count($query->result()) > 0){
            foreach($query->result() as $row){
                $no_area = $row->no_area;
            }
        }

        $this->pdf->SetFont('Arial','B',11);
        $this->pdf->Cell(30,20,utf8_decode($nombre_almacen),0,0,'C');
        $this->pdf->Cell(243,20,utf8_decode('N° - 000'.$almacen),0,0,'C');
        $this->pdf->Ln(13);

        $this->pdf->SetFont('Arial','B',8);
        $this->pdf->Cell(30,20,utf8_decode("FECHA                          : "),'',0,'L','0');
        $this->pdf->Cell(10,20,"    ",'',0,'c','0');
        $this->pdf->Cell(10,20,"     ".$dia,'',0,'c','0');
        $this->pdf->Cell(10,20," -   ".$mes,'',0,'c','0');
        $this->pdf->Cell(10,20," -   ".$anio,'',0,'c','0');
        $this->pdf->Ln(8);
        $this->pdf->Cell(30,20,utf8_decode("DESPACHADO POR    : "),'',0,'L','0');
        $this->pdf->Cell(40,20,"                  ".$nombre_encargado,'',0,'L','0');
        $this->pdf->Ln(8);
        $this->pdf->Cell(30,20,utf8_decode("AREA                            : "),'',0,'L','0');
        $this->pdf->Cell(40,20,"                  ".$no_area,'',0,'L','0');
        $this->pdf->Ln(20);

        $this->pdf->SetFont('Arial','B',7);
        $this->pdf->Cell(10,7,utf8_decode('N°'),'BLTR',0,'C','1'); //La letra "C" indica la alineación del texto dentro del campo de la tabla: Center, Left L, Rigth R
        $this->pdf->Cell(70,7,utf8_decode('NOMBRE O DESCRIPCIÓN DEL PRODUCTO'),'BLTR',0,'C','1');
        $this->pdf->Cell(20,7,utf8_decode('CANTIDAD'),'BLTR',0,'C','1');
        $this->pdf->Cell(25,7,utf8_decode('UNIDAD MED.'),'BLTR',0,'C','1');
        $this->pdf->Cell(35,7,utf8_decode('SOLICITANTE'),'BLTR',0,'C','1');
        $this->pdf->Ln(7);
        $x = 1;

        $result = $this->model_comercial->getSalidasProductos($id_area, $fecha_actual);
        $existe = count($result);
        if($existe > 0){
            foreach ($result as $row) {
                $this->pdf->Cell(10,7,$x++,'BR BL BT',0,'C',0);
                $this->pdf->Cell(70,7,utf8_decode($row->no_producto),'BR BT',0,'C',0);
                $this->pdf->Cell(20,7,$row->cantidad_salida,'BR BT',0,'C',0);
                $this->pdf->Cell(25,7,$row->nom_uni_med,'BR BT',0,'C',0);
                $this->pdf->Cell(35,7,$row->solicitante,'BR BT',0,'C',0);
                $this->pdf->Ln(7);
            }
        }

        // Firma de Conformidad
        $this->pdf->Ln(15);
        $this->pdf->Cell(20,20,utf8_decode("........................................................................."),'',0,'L','0');
        $this->pdf->Cell(88,20,utf8_decode(" "),'',0,'L','0');
        $this->pdf->Cell(100,20,utf8_decode("........................................................................."),'',0,'L','0');
        $this->pdf->Ln(4);
        $this->pdf->Cell(18,20,utf8_decode("    "),'',0,'L','0');
        $this->pdf->Cell(20,20,utf8_decode("B° V° JEFE"),'',0,'L','0');
        $this->pdf->Cell(87,20,utf8_decode("    "),'',0,'L','0');
        $this->pdf->Cell(110,20,utf8_decode("FIRMA Y SELLO"),'',0,'L','0');
            

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

    public function editarproveedor(){
        $data['datosprov']= $this->model_comercial->getProvEditar();
        $this->load->view('comercial/proveedores/actualizar_proveedor', $data);
    }

    public function eliminarnombremaquina(){
        $idnombremaquina = $this->input->get('eliminar');
        $this->model_comercial->eliminarNombreMaquina($idnombremaquina);
    }

    public function eliminararea()
    {
        $idarea = $this->input->get('eliminar');
        $this->model_comercial->eliminarArea($idarea);
    }

    public function eliminarseriemaquina()
    {
        $idseriemaquina = $this->input->get('eliminar');
        $this->model_comercial->eliminarSerieMaquina($idseriemaquina);
    }

    public function eliminarmarcamaquina()
    {
        $idmarcamaquina = $this->input->get('eliminar');
        $this->model_comercial->eliminarMarcaMaquina($idmarcamaquina);
    }

    public function eliminarmodelomaquina()
    {
        $idmodelomaquina = $this->input->get('eliminar');
        $this->model_comercial->eliminarModeloMaquina($idmodelomaquina);
    }

    public function eliminarmoneda()
    {
        $idmoneda = $this->input->get('eliminar');
        $this->model_comercial->eliminarMoneda($idmoneda);
    }

    public function eliminarproducto()
    {
        $id_pro = $this->input->get('eliminar');
        $result = $this->model_comercial->eliminarProducto($id_pro);
        if($result == 'producto_factura'){
            echo '<b>--> Este Producto está asociado a una Factura.</b>';
        }else if($result == 'producto_saldo_inicial'){
            echo '<b>--> Este Producto está asociado a un Cierre de Almacén.</b>';
        }else if($result == 'eliminacion_correcta'){
            echo '1';
        }
    }

    public function eliminaragente()
    {
        $idagente = $this->input->get('eliminar');
        $this->model_comercial->eliminarAgente($idagente);
    }

    public function eliminarcomprobante()
    {
        $idcomprobante = $this->input->get('eliminar');
        $this->model_comercial->eliminarComprobante($idcomprobante);
    }

    public function eliminarproveedor()
    {
        $idproveedor = $this->input->get('eliminar');
        $result = $this->model_comercial->eliminarProveedor($idproveedor);
        if(!$result){
            echo '<b>--> Este Proveedor está asociado a una Factura.</b><br><b>--> Para eliminar este Proveedor, primero deberá eliminar la Factura a la que esta asociada.</b>';
        }else{
            echo '1';
        }
    }

    public function eliminarmaquina()
    {
        $idmaquina = $this->input->get('eliminar');
        $this->model_comercial->eliminarMaquina($idmaquina);
    }

    public function eliminar_parte_maquina()
    {
        $id_parte_maquina = $this->input->get('eliminar');
        $this->model_comercial->eliminar_parte_Maquina($id_parte_maquina);
    }

    public function actualizarnombremaquina()
    {
        $this->form_validation->set_rules('editnombremaq', 'Nombre Máquina', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaNombreMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Esta Máquina ya se encuentra registrada.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizararea()
    {
        $this->form_validation->set_rules('editarea', 'Área', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('nombre_encargado', 'Responsable', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaArea();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Esta Área ya se encuentra registrada.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizarseriemaquina()
    {
        $this->form_validation->set_rules('editmodelomaq', 'Modelo de Máquina', 'trim|required|xss_clean');
        $this->form_validation->set_rules('editseriemaq', 'Serie de Máquina', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaSerieMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Esta Serie asociado a este Modelo ya existe.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizarmarcamaquina()
    {
        $this->form_validation->set_rules('editnombremaq', 'Tipo de Máquina', 'trim|required|xss_clean');
        $this->form_validation->set_rules('editmarcamaq', 'Marca del Tipo de Máquina', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaMarcaMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Esta Marca asociado a este Tipo de Máquina ya existe.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizarmodelomaquina()
    {
        $this->form_validation->set_rules('editmarcamaq', 'Marca de Máquina', 'trim|required|xss_clean');
        $this->form_validation->set_rules('editmodelomaq', 'Modelo del Tipo de Máquina', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaModeloMaquina();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Esta Modelo asociado a esta Marca ya existe.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizaragente()
    {
        $this->form_validation->set_rules('editnombreagente', 'Nombre del Agente Aduanero', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaAgente();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Este Agente Aduanero ya se encuentra registrado.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizarcomprobante()
    {
        $this->form_validation->set_rules('editnombrecomprobante', 'Nombre del Tipo de Comprobante', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaComprobante();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Este Tipo de Comprobante ya se encuentra registrado.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizarmoneda()
    {
        $this->form_validation->set_rules('editnombremon', 'Nombre de Moneda', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('editsimbolomon', 'Símbolo de Moneda', 'trim|required|min_length[1]|max_length[10]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 10 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaMoneda();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Este Tipo de Moneda ya se encuentra registrado.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function actualizarproducto(){
        $this->form_validation->set_rules('editnombreprod', 'Descripción', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        $this->form_validation->set_rules('editobser', 'Observación', 'trim|max_length[50]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 200 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');
        if($this->form_validation->run() == FALSE){
            echo validation_errors();
        }
        else{
            $result = $this->model_comercial->actualizaProducto();
            if($result == 'no_existe_ubicacion'){
                echo '<span style="color:red"><b>ERROR:</b> No Existe la Ubicación del producto ingresada. Verificar!</span>';
            }else if($result == 'successfull'){
                echo '1';
            }
        }
    }

    public function actualizarmaquina()
    {
        $result = $this->model_comercial->actualizaMaquina();
        if(!$result){
            echo '<span style="color:red"><b>ERROR:</b> Esta Máquina no se encuentra registrada.</span>';
        }else{
            echo '1';
        }
    }

    public function actualizar_parte_maquina()
    {
        $result = $this->model_comercial->actualiza_parte_Maquina();
        if($result == 'duplicidad'){
            echo 'Esta Parte de Máquina ya se encuentra registrada. Verificar!';
        }else if($result == 'successfull'){
            echo '1';
        }
    }

    public function registrar_parte_maquina(){
        $result = $this->model_comercial->save_parte_Maquina();
        if($result == 'duplicidad'){
            echo 'Esta Parte de Máquina ya se encuentra registrada. Verificar!';
        }else if($result == 'successfull'){
            echo '1';
        }
    }

    public function actualizartipocambio()
    {
        $this->form_validation->set_rules('edit_dolar_compra', 'Valor de Compra Dólar', 'trim|required|min_length[1]|max_length[5]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 5 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaTipoCambio();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> Tipo de Cambio ya registrado.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function registrar_ubicacion_masiva(){
        $cont = 1;
        $mensaje_registro = 1;
        // Validar si los datos de los productos y areas corresponde a los de la BD
        // Validando el nombre del producto
        $filename = $_FILES['file']['tmp_name'];
        if(($gestor = fopen($filename, "r")) !== FALSE){
            while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                // Obtener los valores del numero de partida
                $nombre_ubicacion = utf8_decode(trim($datos[0]));
                // ------------------------------------------
                $this->db->select('id_ubicacion');
                $this->db->where('nombre_ubicacion',$nombre_ubicacion);
                $query = $this->db->get('ubicacion');
                if($query->num_rows() > 0){
                    var_dump('Ubicacion repetida: '.$nombre_ubicacion);
                }else{
                    $datos = array(
                        "nombre_ubicacion" => $nombre_ubicacion
                    );
                    $this->model_comercial->insert_ubicacion_producto($datos);
                    $cont = $cont + 1;
                }
            }
        }

        if($mensaje_registro == 1){
            $cont = $cont - 1;
            $data['respuesta_validacion_producto_invalido'] = $cont;
            $data['ubicacion_producto_data']= $this->model_comercial->listarUbicacionProductos();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/productos/ubicacion_producto', $data);
        }
    }

    public function registrar_productos_opcion_masiva(){
        $cont = 1;
        $mensaje_registro = 1;
        // Validar si los datos de los productos y areas corresponde a los de la BD
        // Validando el nombre del producto
        $filename = $_FILES['file']['tmp_name'];
        if(($gestor = fopen($filename, "r")) !== FALSE){
            while (($datos = fgetcsv($gestor,1000,",")) !== FALSE){
                // Obtener los valores del numero de partida
                $nombre_producto = utf8_decode(trim($datos[0]));
                $nombre_ubicacion = utf8_decode(trim($datos[1]));
                $tipo_producto = utf8_decode(trim($datos[2]));
                // ------------------------------------------
                // ubicacion
                $this->db->select('id_ubicacion');
                $this->db->where('nombre_ubicacion',$nombre_ubicacion);
                $query = $this->db->get('ubicacion');
                if($query->result() > 0){
                    foreach($query->result() as $dato){
                        $id_ubicacion = $dato->id_ubicacion;
                    }
                }else{
                    var_dump($nombre_ubicacion);
                }
                // tipo de producto
                $this->db->select('id_tipo_producto');
                $this->db->where('no_tipo_producto',$tipo_producto);
                $query_t_p = $this->db->get('tipo_producto');
                if($query_t_p->result() > 0){
                    foreach($query_t_p->result() as $row){
                        $id_tipo_producto = $row->id_tipo_producto;
                    }
                }else{
                    var_dump($tipo_producto);
                }
                // ------------------------------------------
                $datos = array(
                    "no_producto" => $nombre_producto
                );
                $this->db->insert('detalle_producto', $datos);

                $this->db->select('id_detalle_producto');
                $this->db->where('no_producto',$nombre_producto);
                $query_d_p = $this->db->get('detalle_producto');
                foreach($query_d_p->result() as $row){
                    $id_dp = $row->id_detalle_producto;
                }

                if($id_dp != ""){
                    $registro = array(
                        'id_categoria'=> 1,
                        'id_procedencia'=> 1,
                        'id_almacen'=> 2,
                        'id_detalle_producto'=> $id_dp,
                        'id_unidad_medida'=> 7,
                        'id_tipo_producto'=> $id_tipo_producto,
                        'indice'=> 1,
                        'id_ubicacion'=> $id_ubicacion
                    );
                    $this->db->insert('producto', $registro);
                }
                $cont = $cont + 1;
            }
        }

        if($mensaje_registro == 1){
            $cont = $cont - 1;
            $data['respuesta_validacion_producto_invalido'] = $cont;
            $data['ubicacion_producto_data']= $this->model_comercial->listarUbicacionProductos();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/productos/ubicacion_producto', $data);
        }
    }

    public function actualizarproveedor()
    {
        $this->form_validation->set_rules('edit_rz', 'Razón Social', 'trim|required|xss_clean');
        $this->form_validation->set_rules('edit_ruc', 'RUC', 'min_length[11]|max_length[11]|trim|required|xss_clean');
        $this->form_validation->set_rules('edit_direc', 'Dirección', 'max_length[100]|trim|required|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 11 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 50 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
        else
        {
            $result = $this->model_comercial->actualizaProveedor();
            // Verificamos que existan resultados
            if(!$result){
                //Sí no se encotnraron datos.
                echo '<span style="color:red"><b>ERROR:</b> El RUC de este Proveedor ya se encuentra registrado.</span>';
            }else{
                //Registramos la sesion del usuario
                echo '1';
            }
        }
    }

    public function traerFacturasImportadas(){
        $resultado = $this->model_comercial->get_datos_factura_importada();
        if (count($resultado) == 0) {
            echo '0';
        }else{
            echo '<table width="570" border="0" cellspacing="1" cellpadding="1" style="margin-bottom: 20px;margin-left: 25px;">
                    <tr>
                        <td colspan="4" class="title-formulate-selected"><strong>Facturas Importadas</strong></td>
                    </tr>
                    <tr class="tituloTable-fact-import">
                        <td width="100">Fecha</td>
                        <td width="270">Proveedor</td>
                        <td width="75">Serie</td>
                        <td width="115">Correlativo</td>
                        <td width="20"> </td>
                    </tr>';
                    foreach($resultado as $key => $row){
                        echo '<tr class="contentTable-fact-import" style="font-size: 11px;">
                                <td>'.$row->fecha.'</td>
                                <td>'.$row->razon_social.'</td>
                                <td>'.$row->serie_comprobante.'</td>
                                <td>'.$row->nro_comprobante.'</td>
                                <td> <a href="#" onClick="gestionar_factura_importada(event, \''.$row->id_ingreso_producto.'\')" ><i class="fa fa-pencil-square-o" title="Actualizar"></i></a></td>
                             </tr>';
                    }
            echo '</table>';
        }       
    }

    public function traerStock()
    {
        $stock = $this->model_comercial->getStock();
        foreach($stock as $dato){
            echo $dato->stock;
        }
    }

    public function traerEncargado()
    {
        $encar = $this->model_comercial->getEncargado();
        foreach($encar as $dato){
            echo $dato->encargado;
        }
    }

    public function traerUnidadMedida()
    {
        $unidad_medida = $this->model_comercial->getUnidadMedida();
        foreach($unidad_medida as $dato){
            echo $dato->unidad_medida;
        }
    }

    public function agregarcarrito(){
        //print_r($_POST);
        $this->form_validation->set_rules('nombre_producto', 'Nombre del Producto', 'trim|required|xss_clean');
        $this->form_validation->set_rules('cantidad', 'Cantidad', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pu', 'Precio Unitario', 'trim|required|xss_clean');
        $this->form_validation->set_rules('csigv', 'Con/Sin IGV', 'trim|required|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            //$data['error']= validation_errors();
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            if($this->input->post('nombre_producto') == "" AND $this->input->post('cantidad') == "" AND $this->input->post('pu') == ""){
                $data['respuesta_general_carrito'] = '<span style="color:red"><b>ERROR:</b> Falta completar los campos.</span>';
            }else if($this->input->post('nombre_producto') == ""){
                $data['respuesta_carrito_prod'] = '<span style="color:red"><b>ERROR:</b> Falta seleccionar el campo Producto.</span>';
            }else if($this->input->post('cantidad') == ""){
                $data['respuesta_carrito_qty'] = '<span style="color:red"><b>ERROR:</b> Falta seleccionar el campo Cantidad.</span>';
            }else if($this->input->post('pu') == ""){
                $data['respuesta_carrito_pu'] = '<span style="color:red"><b>ERROR:</b> Falta seleccionar el campo Precio Unitario.</span>';
            }else if($this->input->post('csigv') == ""){
                $data['respuesta_csigv'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Agente de Aduana.</span>';
            }
            $data['listaarea']= $this->model_comercial->listarArea();
            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
            $data['listacomprobante']= $this->model_comercial->listaComprobante();
            $data['listaproveedor']= $this->model_comercial->listaProveedor();
            $data['listasimmon']= $this->model_comercial->listaSimMon();
            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/comprobantes/registro_ingreso', $data);
        }else{
            $this->cart->total();

            $datasession_igv = array(
                'csigv' => $this->input->post('csigv')
            );
            $this->session->set_userdata($datasession_igv);

            $nombre_producto = $this->input->post('nombre_producto');

            $this->db->select('id_detalle_producto');
            $this->db->where('no_producto',$nombre_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $id_detalle_producto = $row->id_detalle_producto;
            }

            $this->db->select('id_pro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('producto');
            foreach($query->result() as $row){
                $id_pro = $row->id_pro;
            }

            $data = array(
                'id' => $id_pro,
                'qty' => $this->input->post('cantidad'),
                'price' => $this->input->post('pu'),
                'name'=> $nombre_producto
            );
            $this->cart->insert($data);

            redirect('comercial/gestioningreso');
        }
    }

    public function actualizar_carrito_descuento(){
        $descuento_porcentaje = $this->input->post('descuento_porcentaje');
        $valor_porcentaje = $descuento_porcentaje / 100;

        $monto_total_factura = $this->cart->total();
        $carrito = $this->cart->contents();
        $data = array();
        foreach ($carrito as $item) {
            $no_producto = $item['name'];
            $unidades = $item['qty'];
            $precio = $item['price'];
            // valor del descuento
            $valor_descuento = $item['price'] * $valor_porcentaje;
            // nuevo precio unitario
            $new_precio_unitario = $item['price'] - $valor_descuento;

            $this->db->select('id_detalle_producto');
            $this->db->where('no_producto',$no_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $id_detalle_producto = $row->id_detalle_producto;
            }

            $this->db->select('id_pro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('producto');
            foreach($query->result() as $row){
                $id_pro = $row->id_pro;
            }

            $array = array(
                'id' => $id_pro,
                'qty' => $unidades,
                'price' => $new_precio_unitario,
                'name'=> $no_producto
            );
            array_push($data, $array);
            
            /*
            // Guardamos este nuevo registro en una array
            $data_actualizada = array(
                'id' => $cont_id,
                'qty' => $unidades,
                'price' => $new_precio_unitario,
                'name'=> $no_producto
            );
            // $this->cart->insert($data_actualizada);
            */
        }
        // Eliminas los datos de la libreria anterior
        $this->cart->destroy();
        // Cargamos nuevamente la libreria
        $this->cart->insert($data);
        /*
        print_r($data);
        die();
        */
        echo '1';
    }

    public function agregarcarrito_otros(){
        //print_r($_POST);
        $this->form_validation->set_rules('nomproducto', 'Nombre del Producto', 'trim|required|xss_clean');
        $this->form_validation->set_rules('cantidad', 'Cantidad', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pu', 'Precio Unitario', 'trim|required|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            $data['error']= validation_errors();
            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
            $data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
            $data['listacomprobante']= $this->model_comercial->listarComprobantes();
            $data['listaproveedor']= $this->model_comercial->listaProveedor();
            $data['listasimmon']= $this->model_comercial->listaSimMon();
            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
            $this->load->view('comercial/registro_ingreso_otros', $data);
        }else{
            $this->cart->total();
        
            $id_detalle_producto = $this->input->post('nomproducto');

            $this->db->select('id_producto');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('producto');
            foreach($query->result() as $row){
                $id_producto = $row->id_producto;
            }

            $this->db->select('no_producto');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $no_producto = $row->no_producto;
            }

            $data = array(
                'id' => $id_producto,
                'qty' => $this->input->post('cantidad'),
                'price' => $this->input->post('pu'),
                'name'=> $no_producto
            );
            $this->cart->insert($data);
            
            /*
            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
            $data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
            $data['listacomprobante']= $this->model_comercial->listarComprobantes();
            $data['listaproveedor']= $this->model_comercial->listaProveedor();
            $data['listasimmon']= $this->model_comercial->listaSimMon();
            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
            $this->load->view('comercial/registro_ingreso_otros', $data);
            */
            redirect('comercial/gestionotrosDoc');
        }
    }

    function actualizar_carrito(){
        // print_r($_POST);
        $data = $this->input->post();
        $this->cart->update($data);
        redirect('comercial/gestioningreso');
    }

    
    function remove($rowid){
        $this->cart->update(array(
            'rowid' => $rowid,
            'qty' => 0
        ));
        redirect('comercial/gestioningreso');
    }

    function remove_salida($rowid){
        $this->cart->update(array(
            'rowid' => $rowid,
            'qty' => 0
        ));
        redirect('comercial/gestionsalida');
    }

    function remove_otros($rowid){
        $this->cart->update(array(
            'rowid' => $rowid,
            'qty' => 0
        ));
        redirect('comercial/gestionotrosDoc');
    }

    function vaciar_listado(){
        $this->cart->destroy();
        $this->session->unset_userdata('csigv');
        redirect('comercial/gestionsalida');
    }

    function vaciar_listado_ingresos(){
        $this->cart->destroy();
        $this->session->unset_userdata('csigv');
        redirect('comercial/gestioningreso');
    }

    function vaciar_listado_otros(){
        $this->cart->destroy();
        redirect('comercial/gestionotrosDoc');
    }

    public function traer_solicitante_autocomplete() {
        
        $termino = strtoupper($this->input->post('q'));
        $resultado = $this->model_comercial->get_nombre_solicitante_autocomplete_salida($termino);
        $array = array( "label" => "no se encontraron resultados" );
        if ($resultado != null) {
            $data = array();
            foreach ($resultado as $producto) {
                $array = array(
                    "label" => $producto['solicitante'],
                    "nombre_solicitante" => $producto['solicitante']
                );
                array_push($data, $array);
            }
        }
        print(json_encode($data));
    }

    function actualizar_carrito_otros(){
        $data = $this->input->post();
        $this->cart->update($data);
        redirect('comercial/gestionotrosDoc');
    }

    function mostrar(){
        print_r($_POST);
        //echo 1;
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
            $result = $this->model_comercial->UpdatePassword();
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

    public function agregar_indice(){
        $result = $this->model_comercial->updateIndice();
        // Verificamos que existan resultados
        if(!$result){
            //Sí no se encotnraron datos.
            echo '<span style="color:red"><b>ERROR: </b>ERROR</span>';
        }else{
            //Registramos la sesion del usuario
            echo '1';
        }
    }

    public function finalizar_registro()
    {
        $this->form_validation->set_rules('numcomprobante', 'Nro. de Comprobante', 'trim|required|min_length[1]|max_length[12]|xss_clean');
        $this->form_validation->set_rules('fecharegistro', 'Fecha de Registro', 'trim|required|xss_clean');
        $this->form_validation->set_rules('moneda', 'Moneda', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('proveedor', 'Proveedor', 'trim|required|xss_clean');
        $this->form_validation->set_rules('id_agente', 'Agente de Aduana', 'trim|required|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            //$data['error']= validation_errors();
            if($this->model_comercial->existeTipoCambio() == TRUE){
                $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            if($this->input->post('numcomprobante') == "" AND $this->input->post('fecharegistro') == "" AND $this->input->post('moneda') == "" AND $this->input->post('proveedor') == "" AND $this->input->post('id_agente') == ""){
                $data['respuesta_general'] = '<span style="color:red"><b>ERROR:</b> Falta completar los campos.</span>';
            }else if($this->input->post('numcomprobante') == ""){
                $data['respuesta_compro'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo N° de Comprobante.</span>';
            }else if($this->input->post('moneda') == ""){
                $data['respuesta_moneda'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Moneda.</span>';
            }else if($this->input->post('nombre_proveedor') == ""){
                $data['respuesta_prov'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Proveedor.</span>';
            }else if($this->input->post('fecharegistro') == ""){
                $data['respuesta_fe'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Fecha de Registro.</span>';
            }else if($this->input->post('id_agente') == ""){
                $data['respuesta_agente'] = '<span style="color:red"><b>ERROR:</b> Falta completar el campo Agente de Aduana.</span>';
            }
            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
            $data['listaarea']= $this->model_comercial->listarArea();
            $data['listacomprobante']= $this->model_comercial->listaComprobante();
            $data['listaproveedor']= $this->model_comercial->listaProveedor();
            $data['listasimmon']= $this->model_comercial->listaSimMon();
            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
            $this->load->view('comercial/menu_script');
            $this->load->view('comercial/menu_cabecera');
            $this->load->view('comercial/comprobantes/registro_ingreso', $data);
        }else{
            if(($this->input->post('id_agente') != 2 AND $this->input->post('id_agente') != 3 AND $this->input->post('id_agente') != 4) AND ($this->input->post('porcent') == 0)){
                    if($this->model_comercial->existeTipoCambio() == TRUE){
                        $data['tipocambio'] = 0;
                    }else{
                        $data['tipocambio'] = 1;
                    }
                    $data['error_porcentaje'] = '<span style="color:red"><b>ERROR:</b> Ingresar el Porcentaje de Gastos asignado a la Factura.</span>';
                    $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                    $data['listaarea']= $this->model_comercial->listarArea();
                    $data['listacomprobante']= $this->model_comercial->listaComprobante();
                    $data['listaproveedor']= $this->model_comercial->listaProveedor();
                    $data['listasimmon']= $this->model_comercial->listaSimMon();
                    $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                    $this->load->view('comercial/menu_script');
                    $this->load->view('comercial/menu_cabecera');
                    $this->load->view('comercial/comprobantes/registro_ingreso', $data);
            }else{
                $existe = $this->cart->total_items();
                if($existe <= 0){
                    if($this->model_comercial->existeTipoCambio() == TRUE){
                        $data['tipocambio'] = 0;
                    }else{
                        $data['tipocambio'] = 1;
                    }
                    $data['error'] = '<span style="color:red"><b>ERROR:</b> Debe Registrar un Productos como mínimo a la Factura.</span>';
                    $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                    $data['listaarea']= $this->model_comercial->listarArea();
                    $data['listacomprobante']= $this->model_comercial->listaComprobante();
                    $data['listaproveedor']= $this->model_comercial->listaProveedor();
                    $data['listasimmon']= $this->model_comercial->listaSimMon();
                    $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                    $this->load->view('comercial/menu_script');
                    $this->load->view('comercial/menu_cabecera');
                    $this->load->view('comercial/comprobantes/registro_ingreso', $data);
                }else{
                    // Realizar la inserción a la BD
                    $tipo_comprobante = $this->security->xss_clean($this->input->post("comprobante"));
                    $numcomprobante = $this->security->xss_clean($this->input->post("numcomprobante"));
                    $seriecomprobante = $this->security->xss_clean($this->input->post("seriecomprobante"));
                    $moneda = $this->security->xss_clean($this->input->post("moneda"));
                    $nombre_proveedor = $this->security->xss_clean($this->input->post("nombre_proveedor"));
                    $fecharegistro = $this->security->xss_clean($this->input->post("fecharegistro"));
                    $porcentaje = $this->security->xss_clean($this->input->post("porcent"));
                    $id_agente = $this->security->xss_clean($this->input->post("id_agente"));
                    $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
                    $csigv = $this->security->xss_clean($this->session->userdata('csigv'));
                    if ($this->session->userdata('csigv') == "true"){
                        $total = $this->cart->total();
                    }else if ($this->session->userdata('csigv') == "false"){
                        $total = $this->cart->total()+($this->cart->total()*0.18);
                    }
                    // Contenido de la libreria cart
                    $carrito = $this->cart->contents();
                    // Validar si el ingreso esta en un periodo que ya cerro
                    $result_cierre = $this->model_comercial->validarRegistroCierre($fecharegistro);
                    if($result_cierre == 'periodo_cerrado'){
                        if($this->model_comercial->existeTipoCambio() == TRUE){
                            $data['tipocambio'] = 0;
                        }else{
                            $data['tipocambio'] = 1;
                        }
                        $data['error_periodo_cerrado'] = '<span style="color:red"><b>!No se puede realizar el registro!</b><br><b>La Fecha seleccionada corresponde a un Periodo de Cierre Anterior</b></span>';
                        $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                        $data['listaarea']= $this->model_comercial->listarArea();
                        $data['listacomprobante']= $this->model_comercial->listaComprobante();
                        $data['listaproveedor']= $this->model_comercial->listaProveedor();
                        $data['listasimmon']= $this->model_comercial->listaSimMon();
                        $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                        $this->load->view('comercial/menu_script');
                                    $this->load->view('comercial/menu_cabecera');
                        $this->load->view('comercial/comprobantes/registro_ingreso', $data);
                    }else if($result_cierre == 'successfull'){
                        /* Iniciar variable */
                        $id_proveedor = "";
                        $this->db->select('id_proveedor');
                        $this->db->where('razon_social',$nombre_proveedor);
                        $query = $this->db->get('proveedor');
                        foreach($query->result() as $row){
                            $id_proveedor = $row->id_proveedor;
                        }
                        if($id_proveedor == ""){
                            if($this->model_comercial->existeTipoCambio() == TRUE){
                                $data['tipocambio'] = 0;
                            }else{
                                $data['tipocambio'] = 1;
                            }
                            $data['error_nombreProveedor'] = '<span style="color:red"><b>El Proveedor no existe en la Base de Datos</b></span>';
                            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                            $data['listaarea']= $this->model_comercial->listarArea();
                            $data['listacomprobante']= $this->model_comercial->listaComprobante();
                            $data['listaproveedor']= $this->model_comercial->listaProveedor();
                            $data['listasimmon']= $this->model_comercial->listaSimMon();
                            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                            $this->load->view('comercial/menu_script');
                                    $this->load->view('comercial/menu_cabecera');
                            $this->load->view('comercial/comprobantes/registro_ingreso', $data);
                        }else{
                            // Agregamos le registro_ingreso a la bd
                            $datos = array(
                                "id_comprobante" => $tipo_comprobante,
                                "serie_comprobante" => $seriecomprobante,
                                "nro_comprobante" => $numcomprobante,
                                "fecha" => $fecharegistro,
                                "id_moneda" => $moneda,
                                "id_proveedor" => $id_proveedor,
                                "total" => $total,
                                "gastos" => $porcentaje,
                                "id_almacen" => $almacen,
                                "cs_igv" => $csigv
                            );

                            $id_ingreso_producto = $this->model_comercial->agrega_ingreso($datos, $seriecomprobante, $numcomprobante, $id_proveedor, $fecharegistro);

                            if(!$id_ingreso_producto){
                                // Si no se encotnraron datos.
                                if($this->model_comercial->existeTipoCambio() == TRUE){
                                    $data['tipocambio'] = 0;
                                }else{
                                    $data['tipocambio'] = 1;
                                }
                                $data['error_tipo_cambio'] = '<span style="color:red"><b>ERROR:</b> No existe un Tipo de Cambio para el día con el que se Registra la Factura.</span>';
                                $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                                $data['listaarea']= $this->model_comercial->listarArea();
                                $data['listacomprobante']= $this->model_comercial->listaComprobante();
                                $data['listaproveedor']= $this->model_comercial->listaProveedor();
                                $data['listasimmon']= $this->model_comercial->listaSimMon();
                                $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                                $this->load->view('comercial/menu_script');
                                    $this->load->view('comercial/menu_cabecera');
                                $this->load->view('comercial/comprobantes/registro_ingreso', $data);
                            }else{
                                // Agregamos el detalle del comprobante
                                $result = $this->model_comercial->agregar_detalle_ingreso($carrito, $id_ingreso_producto, $fecharegistro, $numcomprobante, $seriecomprobante, $porcentaje, $almacen);

                                if(!$result){
                                    // Si no se encotnraron datos.
                                    if($this->model_comercial->existeTipoCambio() == TRUE){
                                        $data['tipocambio'] = 0;
                                    }else{
                                        $data['tipocambio'] = 1;
                                    }
                                    $data['error_tipo_cambio'] = '<span style="color:red"><b>ERROR:</b> No existe un Tipo de Cambio para el día con el que se Registra la Factura.</span>';
                                    $data['listaarea']= $this->model_comercial->listarArea();
                                    $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                                    $data['listacomprobante']= $this->model_comercial->listaComprobante();
                                    $data['listaproveedor']= $this->model_comercial->listaProveedor();
                                    $data['listasimmon']= $this->model_comercial->listaSimMon();
                                    $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                                    $this->load->view('comercial/menu_script');
                                    $this->load->view('comercial/menu_cabecera');
                                    $this->load->view('comercial/comprobantes/registro_ingreso', $data);
                                }else{
                                    $this->cart->destroy();
                                    $this->session->unset_userdata('csigv');
                                    /* Mensaje de confirmacion */
                                    if($this->model_comercial->existeTipoCambio() == TRUE){
                                        $data['tipocambio'] = 0;
                                    }else{
                                        $data['tipocambio'] = 1;
                                    }
                                    $data['mensaje_registro_correcto'] = '<span style="color:red"><b>ERROR:</b> No existe un Tipo de Cambio para el día con el que se Registra la Factura.</span>';
                                    $data['listaarea']= $this->model_comercial->listarArea();
                                    $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                                    $data['listacomprobante']= $this->model_comercial->listaComprobante();
                                    $data['listaproveedor']= $this->model_comercial->listaProveedor();
                                    $data['listasimmon']= $this->model_comercial->listaSimMon();
                                    $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                                    $this->load->view('comercial/menu_script');
                                    $this->load->view('comercial/menu_cabecera');
                                    $this->load->view('comercial/comprobantes/registro_ingreso', $data);
                                    /*
                                    $this->cart->destroy();
                                    $this->session->unset_userdata('csigv');
                                    redirect('comercial/gestioningreso');
                                    */
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function cuadrar_orden_ingreso()
    {
        // Realizar la inserción a la BD
        $nombre_producto = $this->security->xss_clean($this->input->post("nombre_producto"));
        $stockactual = $this->security->xss_clean($this->input->post("stockactual"));
        $cantidad = $this->security->xss_clean($this->input->post("cantidad"));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        // Obtener el id_detalle_producto
        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        if($query->num_rows()>0){
            foreach($query->result() as $row){
                $id_detalle_producto = $row->id_detalle_producto;
            }
        }
        // Agregamos le registro_ingreso a la bd
        $cantidad_ingreso = $cantidad - $stockactual;
        if($cantidad_ingreso > 0){
            $datos = array(
                "id_detalle_producto" => $id_detalle_producto,
                "cantidad_ingreso" => $cantidad_ingreso,
                "fecha_registro" => date('Y-m-d'),
                "id_almacen" => $almacen
            );
            $id_ingreso_producto = $this->model_comercial->insert_orden_ingreso($datos);

            if($id_ingreso_producto == 'error_inesperado'){
                echo 'error_inesperado';
            }else{
                // Agregamos el detalle del comprobante
                $result = $this->model_comercial->kardex_orden_ingreso($id_ingreso_producto, $id_detalle_producto, $cantidad_ingreso, $almacen);

                if($result == 'registro_correcto'){
                    echo '1';
                }else{
                    echo 'error_kardex';
                }
            }
        }else{
            echo 'cantidad_negativa';
        }
    }

    public function cuadrar_producto_almacen(){
        $aux_parametro_cuadre = 0;
        $auxiliar_last_kardex = 0;
        $auxiliar_last_salida = 0;
        $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
        $area = $this->security->xss_clean($this->input->post('area'));
        $cantidad = $this->security->xss_clean($this->input->post('cantidad'));
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        // Obtengo los datos del producto
        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
        }
        // Obtengo los datos del producto
        $this->db->select('id_pro');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
        }
        // Generar el ciclo
        do{
            // Obtener stock del producto - de acuerdo al almacen
            $this->db->select('stock,precio_unitario,stock_sta_clara');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $stockactual = $row->stock; // Sta. anita
                $stock_sta_clara = $row->stock_sta_clara; // Sta. clara
                $precio_unitario = $row->precio_unitario;
            }
            // Obtener la ultima salida del producto de la tabla salida_producto y kardex_producto
            // kardex_producto
            $this->db->select('id_kardex_producto,cantidad_salida,descripcion,fecha_registro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->order_by("id_kardex_producto", "asc");
            $query = $this->db->get('kardex_producto');
            if(count($query->result()) > 0){
                foreach($query->result() as $row){
                    $auxiliar_last_kardex = $row->id_kardex_producto;
                    $cantidad_salida_kardex = $row->cantidad_salida;
                    $descripcion = $row->descripcion;
                    $fecha_registro = $row->fecha_registro;
                }
            }
            // salida_producto
            $this->db->select('id_salida_producto,cantidad_salida,fecha');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->order_by("id_salida_producto", "asc");
            $query = $this->db->get('salida_producto');
            if(count($query->result()) > 0){
                foreach($query->result() as $row){
                    $auxiliar_last_salida = $row->id_salida_producto;
                    $cantidad_salida_table_salida = $row->cantidad_salida;
                }
            }
            // Validar a que almacen pertenece
            if($id_almacen == 2){
                // El stock del sistema supera al stock fisico
                if($stockactual > $cantidad){
                    $unidad_base_salida = $stockactual - $cantidad;
                    // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                    // tabla salida_producto
                    $a_data = array('id_area' => $area,
                                    'fecha' => date('Y-m-d'),
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'cantidad_salida' => $unidad_base_salida,
                                    'id_almacen' => $id_almacen,
                                    'p_u_salida' => $precio_unitario,
                                    );
                    $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                    // tabla kardex
                    $new_stock = ($stockactual + $stock_sta_clara) - $unidad_base_salida;
                    $stock_general = $stockactual + $stock_sta_clara;
                    $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                            'descripcion' => "SALIDA",
                                            'id_detalle_producto' => $id_detalle_producto,
                                            'stock_anterior' => $stock_general,
                                            'precio_unitario_anterior' => $precio_unitario,
                                            'cantidad_salida' => $unidad_base_salida,
                                            'stock_actual' => $new_stock,
                                            'precio_unitario_actual' => $precio_unitario,
                                            'num_comprobante' => $result_insert,
                                            );
                    $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                    // Actualizar stock de acuerdo al cuadre
                    // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                    $this->db->select('stock');
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $query = $this->db->get('detalle_producto');
                    foreach($query->result() as $row){
                        $stock_final = $row->stock;
                    }
                    // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                    $this->model_comercial->descontarStock($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                    // Enviar parametro para terminar bucle
                    $aux_parametro_cuadre = 1;
                    echo '1';
                }else if($cantidad_salida_kardex == $cantidad_salida_table_salida && $descripcion == 'SALIDA'){ // Validacion de cantidad de salida 
                    // El stock fisico supera el stock del sistema
                    if($stockactual < $cantidad){
                        // Eliminar las salidas necesarias para recuperar el stock del producto
                        // Validando que no se pase del stock que se necesita como cuadre
                        $stock_actualizado = $stockactual + $cantidad_salida_kardex; // unidades final del producto
                        if($stock_actualizado == $cantidad){
                            // Eliminar salida // registro del kardex // actualizar stock
                            $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actualizado,$id_almacen);
                            $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                            $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                            $aux_parametro_cuadre = 1;
                            echo '1';
                        }else if($stock_actualizado > $cantidad){
                            $unidad_base_salida = $cantidad - $stockactual;
                            $unidad_base_salida = $cantidad_salida_kardex - $unidad_base_salida;
                            // Eliminar salida // registro del kardex // actualizar stock
                            $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actualizado,$id_almacen);
                            $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                            $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                            // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                            // tabla salida_producto
                            $a_data = array('id_area' => $area,
                                            'fecha' => date('Y-m-d'),
                                            'id_detalle_producto' => $id_detalle_producto,
                                            'cantidad_salida' => $unidad_base_salida,
                                            'id_almacen' => $id_almacen,
                                            'p_u_salida' => $precio_unitario,
                                            );
                            $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                            // tabla kardex
                            $new_stock = ($stock_actualizado + $stock_sta_clara) - $unidad_base_salida;
                            $stock_general = $stock_actualizado + $stock_sta_clara;
                            $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                                    'descripcion' => "SALIDA",
                                                    'id_detalle_producto' => $id_detalle_producto,
                                                    'stock_anterior' => $stock_general,
                                                    'precio_unitario_anterior' => $precio_unitario,
                                                    'cantidad_salida' => $unidad_base_salida,
                                                    'stock_actual' => $new_stock,
                                                    'precio_unitario_actual' => $precio_unitario,
                                                    'num_comprobante' => $result_insert,
                                                    );
                            $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                            // Actualizar stock de acuerdo al cuadre
                            // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                            $this->db->select('stock');
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $query = $this->db->get('detalle_producto');
                            foreach($query->result() as $row){
                                $stock_final = $row->stock;
                            }
                            // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                            $this->model_comercial->descontarStock($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                            // Enviar parametro para terminar bucle
                            $aux_parametro_cuadre = 1;
                            echo '1';
                        }else if($stock_actualizado < $cantidad){
                            // Eliminar salida // registro del kardex // actualizar stock
                            $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actualizado,$id_almacen);
                            $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                            $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                        }
                    }
                }else{
                    echo 'cantidad_erronea_salidas';
                    die();
                }
            }else if($id_almacen == 1){
                // El stock del sistema supera al stock fisico
                if($stock_sta_clara > $cantidad){
                    $unidad_base_salida = $stock_sta_clara - $cantidad;
                    // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                    // tabla salida_producto
                    $a_data = array('id_area' => $area,
                                    'fecha' => date('Y-m-d'),
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'cantidad_salida' => $unidad_base_salida,
                                    'id_almacen' => $id_almacen,
                                    'p_u_salida' => $precio_unitario,
                                    );
                    $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                    // tabla kardex
                    $new_stock = ($stockactual + $stock_sta_clara) - $unidad_base_salida;
                    $stock_general = $stockactual + $stock_sta_clara;
                    $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                            'descripcion' => "SALIDA",
                                            'id_detalle_producto' => $id_detalle_producto,
                                            'stock_anterior' => $stock_general,
                                            'precio_unitario_anterior' => $precio_unitario,
                                            'cantidad_salida' => $unidad_base_salida,
                                            'stock_actual' => $new_stock,
                                            'precio_unitario_actual' => $precio_unitario,
                                            'num_comprobante' => $result_insert,
                                            );
                    $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                    // Actualizar stock de acuerdo al cuadre
                    // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                    $this->db->select('stock_sta_clara');
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $query = $this->db->get('detalle_producto');
                    foreach($query->result() as $row){
                        $stock_final = $row->stock_sta_clara;
                    }
                    // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                    $this->model_comercial->descontarStock($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                    // Enviar parametro para terminar bucle
                    $aux_parametro_cuadre = 1;
                    echo '1';
                }else if($cantidad_salida_kardex == $cantidad_salida_table_salida && $descripcion == 'SALIDA'){ // Validacion de cantidad de salida 
                    // El stock fisico supera el stock del sistema
                    if($stock_sta_clara < $cantidad){
                        // Eliminar las salidas necesarias para recuperar el stock del producto
                        // Validando que no se pase del stock que se necesita como cuadre
                        $stock_actualizado = $stock_sta_clara + $cantidad_salida_kardex; // unidades final del producto
                        if($stock_actualizado == $cantidad){
                            // Eliminar salida // registro del kardex // actualizar stock
                            $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actualizado,$id_almacen);
                            $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                            $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                            //$this->model_comercial->actualizar_saldos_iniciales_cuadre($fecha_registro,$id_pro,$stock_actualizado,$id_almacen);
                            $aux_parametro_cuadre = 1;
                            echo '1';
                        }else if($stock_actualizado > $cantidad){
                            $unidad_base_salida = $cantidad - $stock_sta_clara;
                            $unidad_base_salida = $cantidad_salida_kardex - $unidad_base_salida;
                            //$stock_para_cuadre = $cantidad_salida_table_salida - $unidad_base_salida;
                            // Eliminar salida // registro del kardex // actualizar stock
                            $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actualizado,$id_almacen);
                            $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                            $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                            //$this->model_comercial->actualizar_saldos_iniciales_cuadre($fecha_registro,$id_pro,$cantidad,$id_almacen);
                            // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                            // tabla salida_producto
                            $a_data = array('id_area' => $area,
                                            'fecha' => date('Y-m-d'),
                                            'id_detalle_producto' => $id_detalle_producto,
                                            'cantidad_salida' => $unidad_base_salida,
                                            'id_almacen' => $id_almacen,
                                            'p_u_salida' => $precio_unitario,
                                            );
                            $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                            // tabla kardex
                            $new_stock = ($stock_actualizado + $stockactual) - $unidad_base_salida;
                            $stock_general = $stock_actualizado + $stockactual;
                            $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                                    'descripcion' => "SALIDA",
                                                    'id_detalle_producto' => $id_detalle_producto,
                                                    'stock_anterior' => $stock_general,
                                                    'precio_unitario_anterior' => $precio_unitario,
                                                    'cantidad_salida' => $unidad_base_salida,
                                                    'stock_actual' => $new_stock,
                                                    'precio_unitario_actual' => $precio_unitario,
                                                    'num_comprobante' => $result_insert,
                                                    );
                            $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                            // Actualizar stock de acuerdo al cuadre
                            // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                            $this->db->select('stock,stock_sta_clara');
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $query = $this->db->get('detalle_producto');
                            foreach($query->result() as $row){
                                $stock_final = $row->stock_sta_clara;
                            }
                            // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                            $this->model_comercial->descontarStock($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                            // Enviar parametro para terminar bucle
                            $aux_parametro_cuadre = 1;
                            echo '1';
                        }else if($stock_actualizado < $cantidad){
                            // Eliminar salida // registro del kardex // actualizar stock
                            $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actualizado,$id_almacen);
                            $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                            $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                            //$this->model_comercial->actualizar_saldos_iniciales_cuadre($fecha_registro,$id_pro,$stock_actualizado,$id_almacen);
                        }
                    }
                }else{
                    echo 'cantidad_erronea_salidas';
                    die();
                }
            }
        }while($aux_parametro_cuadre == 0);

    }

    public function cuadrar_producto_area_almacen(){
        $aux_parametro_cuadre = 0;
        $auxiliar_last_kardex = 0;
        $auxiliar_last_salida = 0;
        $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
        $area = $this->security->xss_clean($this->input->post('area'));
        $cantidad = $this->security->xss_clean($this->input->post('cantidad'));
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        // Obtengo los datos del producto
        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
        }
        // Obtengo los datos del producto
        $this->db->select('id_pro');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
        }
        // Generar el ciclo
        do{
            $suma_stock_producto_areas = 0;
            // Obtener stock del general del producto - de acuerdo al almacen
            $this->db->select('stock,precio_unitario,stock_sta_clara');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $stockactual = $row->stock; // Sta. anita
                $stock_sta_clara = $row->stock_sta_clara; // Sta. clara
                $precio_unitario = $row->precio_unitario;
            }
            // Obtener la ultima salida del producto de la tabla salida_producto y kardex_producto
            // kardex_producto
            $this->db->select('id_kardex_producto,cantidad_salida,descripcion,fecha_registro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->order_by("id_kardex_producto", "asc");
            $query = $this->db->get('kardex_producto');
            if(count($query->result()) > 0){
                foreach($query->result() as $row){
                    $auxiliar_last_kardex = $row->id_kardex_producto;
                    $cantidad_salida_kardex = $row->cantidad_salida;
                    $descripcion = $row->descripcion;
                    $fecha_registro = $row->fecha_registro;
                }
            }
            // salida_producto
            $this->db->select('id_salida_producto,cantidad_salida,fecha');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->order_by("id_salida_producto", "asc");
            $query = $this->db->get('salida_producto');
            if(count($query->result()) > 0){
                foreach($query->result() as $row){
                    $auxiliar_last_salida = $row->id_salida_producto;
                    $cantidad_salida_table_salida = $row->cantidad_salida;
                }
            }else{
                $auxiliar_last_salida = "";
                $cantidad_salida_table_salida = "";
            }
            // Validar a que almacen pertenece
            if($id_almacen == 1){
                // Actualizar stock del producto por area
                $result_update = $this->model_comercial->actualizar_stock_producto_area($id_pro,$area,$id_almacen,$cantidad);
                // Obtener la suma total de stock del producto distribuido en areas ya actualizado
                $this->db->select('stock_area_sta_clara');
                $this->db->where('id_pro',$id_pro);
                $query = $this->db->get('detalle_producto_area');
                foreach($query->result() as $row){
                    $suma_stock_producto_areas = $suma_stock_producto_areas + $row->stock_area_sta_clara;
                }
                // Hasta aca solo se ha actualizado el stock del producto por area
                if($result_update){
                    // El stock del sistema supera al stock fisico
                    if($stock_sta_clara == $suma_stock_producto_areas){
                        $aux_parametro_cuadre = 1;
                        echo '1';
                    }else if($stock_sta_clara > $suma_stock_producto_areas){
                        $unidad_base_salida = $stock_sta_clara - $suma_stock_producto_areas;
                        // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                        // tabla salida_producto
                        $a_data = array('id_area' => $area,
                                        'fecha' => date('Y-m-d'),
                                        'id_detalle_producto' => $id_detalle_producto,
                                        'cantidad_salida' => $unidad_base_salida,
                                        'id_almacen' => $id_almacen,
                                        'p_u_salida' => $precio_unitario,
                                        );
                        $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                        // tabla kardex
                        $new_stock = ($stockactual + $stock_sta_clara) - $unidad_base_salida;
                        $stock_general = $stockactual + $stock_sta_clara;
                        $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                                'descripcion' => "SALIDA",
                                                'id_detalle_producto' => $id_detalle_producto,
                                                'stock_anterior' => $stock_general,
                                                'precio_unitario_anterior' => $precio_unitario,
                                                'cantidad_salida' => $unidad_base_salida,
                                                'stock_actual' => $new_stock,
                                                'precio_unitario_actual' => $precio_unitario,
                                                'num_comprobante' => $result_insert,
                                                );
                        $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                        // Actualizar stock de acuerdo al cuadre
                        // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                        $this->db->select('stock_sta_clara');
                        $this->db->where('id_detalle_producto',$id_detalle_producto);
                        $query = $this->db->get('detalle_producto');
                        foreach($query->result() as $row){
                            $stock_final = $row->stock_sta_clara;
                        }
                        // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                        $this->model_comercial->descontarStock_general($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                        // Enviar parametro para terminar bucle
                        $aux_parametro_cuadre = 1;
                        echo '1';
                    }else if($cantidad_salida_kardex == $cantidad_salida_table_salida && $descripcion == 'SALIDA'){ // Validacion de cantidad de salida
                        // El stock fisico supera el stock del sistema
                        if($stock_sta_clara < $suma_stock_producto_areas){
                            // Eliminar las salidas necesarias para recuperar el stock del producto
                            // Validando que no se pase del stock que se necesita como cuadre
                            $stock_actualizado = $stock_sta_clara + $cantidad_salida_kardex; // unidades final del producto
                            if($stock_actualizado == $suma_stock_producto_areas){
                                // Eliminar salida // registro del kardex // actualizar stock
                                $this->model_comercial->update_stock_general_cuadre($id_detalle_producto,$stock_actualizado,$id_almacen);
                                $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                                $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                                $aux_parametro_cuadre = 1;
                                echo '1';
                            }else if($stock_actualizado > $suma_stock_producto_areas){
                                $unidad_base_salida = $stock_actualizado - $suma_stock_producto_areas;
                                //$unidad_base_salida = $cantidad_salida_kardex - $unidad_base_salida;
                                // Eliminar salida // registro del kardex // actualizar stock
                                $this->model_comercial->update_stock_general_cuadre($id_detalle_producto,$stock_actualizado,$id_almacen);
                                $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                                $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                                // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                                // tabla salida_producto
                                $a_data = array('id_area' => $area,
                                                'fecha' => date('Y-m-d'),
                                                'id_detalle_producto' => $id_detalle_producto,
                                                'cantidad_salida' => $unidad_base_salida,
                                                'id_almacen' => $id_almacen,
                                                'p_u_salida' => $precio_unitario,
                                                );
                                $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                                // tabla kardex
                                $new_stock = ($stock_actualizado + $stockactual) - $unidad_base_salida;
                                $stock_general = $stock_actualizado + $stockactual;
                                $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                                        'descripcion' => "SALIDA",
                                                        'id_detalle_producto' => $id_detalle_producto,
                                                        'stock_anterior' => $stock_general,
                                                        'precio_unitario_anterior' => $precio_unitario,
                                                        'cantidad_salida' => $unidad_base_salida,
                                                        'stock_actual' => $new_stock,
                                                        'precio_unitario_actual' => $precio_unitario,
                                                        'num_comprobante' => $result_insert,
                                                        );
                                $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                                // Actualizar stock de acuerdo al cuadre
                                // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                                $this->db->select('stock_sta_clara');
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $query = $this->db->get('detalle_producto');
                                foreach($query->result() as $row){
                                    $stock_final = $row->stock_sta_clara;
                                }
                                // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                                $this->model_comercial->descontarStock_general($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                                // Enviar parametro para terminar bucle
                                $aux_parametro_cuadre = 1;
                                echo '1';
                            }else if($stock_actualizado < $suma_stock_producto_areas){
                                // Eliminar salida // registro del kardex // actualizar stock
                                $this->model_comercial->update_stock_general_cuadre($id_detalle_producto,$stock_actualizado,$id_almacen);
                                $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                                $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                            }
                        }else{
                            echo 'cantidad_erronea_salidas';
                            $aux_parametro_cuadre = 1;
                        }
                    }else if(($descripcion == 'ENTRADA') && ($stock_sta_clara < $suma_stock_producto_areas)){
                        $cantidad_ingreso = $suma_stock_producto_areas - $stock_sta_clara;
                        if($cantidad_ingreso > 0){
                            $datos = array(
                                "id_detalle_producto" => $id_detalle_producto,
                                "cantidad_ingreso" => $cantidad_ingreso,
                                "fecha_registro" => date('Y-m-d'),
                                "id_almacen" => $id_almacen
                            );
                            $id_ingreso_producto = $this->model_comercial->insert_orden_ingreso($datos);
                            if($id_ingreso_producto == 'error_inesperado'){
                                echo 'error_inesperado';
                                $aux_parametro_cuadre = 1;
                            }else{
                                // Agregamos el detalle del comprobante
                                $result = $this->model_comercial->kardex_orden_ingreso($id_ingreso_producto, $id_detalle_producto, $cantidad_ingreso, $id_almacen);
                                if($result == 'registro_correcto'){
                                    $aux_parametro_cuadre = 1;
                                    echo '1';
                                }else{
                                    echo 'error_kardex';
                                    $aux_parametro_cuadre = 1;
                                }
                            }
                        }else{
                            echo 'cantidad_negativa';
                            $aux_parametro_cuadre = 1;
                        }
                    }
                }
            }else if($id_almacen == 2){
                // Actualizar stock del producto por area
                $result_update = $this->model_comercial->actualizar_stock_producto_area($id_pro,$area,$id_almacen,$cantidad);
                // Obtener la suma total de stock del producto distribuido en areas ya actualizado
                $this->db->select('stock_area_sta_anita');
                $this->db->where('id_pro',$id_pro);
                $query = $this->db->get('detalle_producto_area');
                foreach($query->result() as $row){
                    $suma_stock_producto_areas = $suma_stock_producto_areas + $row->stock_area_sta_anita;
                }
                // Hasta aca solo se ha actualizado el stock del producto por area
                if($result_update){
                    // El stock del sistema supera al stock fisico
                    if($stockactual > $suma_stock_producto_areas){
                        $unidad_base_salida = $stockactual - $suma_stock_producto_areas;
                        // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                        // tabla salida_producto
                        $a_data = array('id_area' => $area,
                                        'fecha' => date('Y-m-d'),
                                        'id_detalle_producto' => $id_detalle_producto,
                                        'cantidad_salida' => $unidad_base_salida,
                                        'id_almacen' => $id_almacen,
                                        'p_u_salida' => $precio_unitario,
                                        );
                        $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                        // tabla kardex
                        $new_stock = ($stockactual + $stock_sta_clara) - $unidad_base_salida;
                        $stock_general = $stockactual + $stock_sta_clara;
                        $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                                'descripcion' => "SALIDA",
                                                'id_detalle_producto' => $id_detalle_producto,
                                                'stock_anterior' => $stock_general,
                                                'precio_unitario_anterior' => $precio_unitario,
                                                'cantidad_salida' => $unidad_base_salida,
                                                'stock_actual' => $new_stock,
                                                'precio_unitario_actual' => $precio_unitario,
                                                'num_comprobante' => $result_insert,
                                                );
                        $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                        // Actualizar stock de acuerdo al cuadre
                        // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                        $this->db->select('stock');
                        $this->db->where('id_detalle_producto',$id_detalle_producto);
                        $query = $this->db->get('detalle_producto');
                        foreach($query->result() as $row){
                            $stock_final = $row->stock;
                        }
                        // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                        $this->model_comercial->descontarStock_general($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                        // Enviar parametro para terminar bucle
                        $aux_parametro_cuadre = 1;
                        echo '1';
                    }else if($cantidad_salida_kardex == $cantidad_salida_table_salida && $descripcion == 'SALIDA'){ // Validacion de cantidad de salida
                        // El stock fisico supera el stock del sistema
                        if($stockactual < $suma_stock_producto_areas){
                            // Eliminar las salidas necesarias para recuperar el stock del producto
                            // Validando que no se pase del stock que se necesita como cuadre
                            $stock_actualizado = $stockactual + $cantidad_salida_kardex; // unidades final del producto
                            if($stock_actualizado == $suma_stock_producto_areas){
                                // Eliminar salida // registro del kardex // actualizar stock
                                $this->model_comercial->update_stock_general_cuadre($id_detalle_producto,$stock_actualizado,$id_almacen);
                                $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                                $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                                $aux_parametro_cuadre = 1;
                                echo '1';
                            }else if($stock_actualizado > $suma_stock_producto_areas){
                                $unidad_base_salida = $stock_actualizado - $suma_stock_producto_areas;
                                //$unidad_base_salida = $cantidad_salida_kardex - $unidad_base_salida;
                                // Eliminar salida // registro del kardex // actualizar stock
                                $this->model_comercial->update_stock_general_cuadre($id_detalle_producto,$stock_actualizado,$id_almacen);
                                $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                                $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                                // Realizar la salida con la cantidad necesaria para cuadrar el producto en el almacen
                                // tabla salida_producto
                                $a_data = array('id_area' => $area,
                                                'fecha' => date('Y-m-d'),
                                                'id_detalle_producto' => $id_detalle_producto,
                                                'cantidad_salida' => $unidad_base_salida,
                                                'id_almacen' => $id_almacen,
                                                'p_u_salida' => $precio_unitario,
                                                );
                                $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                                // tabla kardex
                                $new_stock = ($stock_actualizado + $stock_sta_clara) - $unidad_base_salida;
                                $stock_general = $stock_actualizado + $stock_sta_clara;
                                $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                                                        'descripcion' => "SALIDA",
                                                        'id_detalle_producto' => $id_detalle_producto,
                                                        'stock_anterior' => $stock_general,
                                                        'precio_unitario_anterior' => $precio_unitario,
                                                        'cantidad_salida' => $unidad_base_salida,
                                                        'stock_actual' => $new_stock,
                                                        'precio_unitario_actual' => $precio_unitario,
                                                        'num_comprobante' => $result_insert,
                                                        );
                                $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                                // Actualizar stock de acuerdo al cuadre
                                // Vuelvo a traer el stock porque lineas arriba ya lo actualice
                                $this->db->select('stock');
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $query = $this->db->get('detalle_producto');
                                foreach($query->result() as $row){
                                    $stock_final = $row->stock;
                                }
                                // Descontar stock - el nuevo stock debe ser de acuerdo al valor de cuadre
                                $this->model_comercial->descontarStock_general($id_detalle_producto,$unidad_base_salida,$stock_final,$id_almacen);
                                // Enviar parametro para terminar bucle
                                $aux_parametro_cuadre = 1;
                                echo '1';
                            }else if($stock_actualizado < $suma_stock_producto_areas){
                                // Eliminar salida // registro del kardex // actualizar stock
                                $this->model_comercial->update_stock_general_cuadre($id_detalle_producto,$stock_actualizado,$id_almacen);
                                $this->model_comercial->eliminar_insert_kardex($auxiliar_last_kardex);
                                $this->model_comercial->eliminar_insert_salida($auxiliar_last_salida);
                            }
                        }else{
                            echo 'cantidad_erronea_salidas';
                            $aux_parametro_cuadre = 1;
                        }
                    }else if(($descripcion == 'ENTRADA') && ($stockactual < $suma_stock_producto_areas)){
                        $cantidad_ingreso = $suma_stock_producto_areas - $stockactual;
                        if($cantidad_ingreso > 0){
                            $datos = array(
                                "id_detalle_producto" => $id_detalle_producto,
                                "cantidad_ingreso" => $cantidad_ingreso,
                                "fecha_registro" => date('Y-m-d'),
                                "id_almacen" => $id_almacen
                            );
                            $id_ingreso_producto = $this->model_comercial->insert_orden_ingreso($datos);
                            if($id_ingreso_producto == 'error_inesperado'){
                                echo 'error_inesperado';
                                $aux_parametro_cuadre = 1;
                            }else{
                                // Agregamos el detalle del comprobante
                                $result = $this->model_comercial->kardex_orden_ingreso($id_ingreso_producto, $id_detalle_producto, $cantidad_ingreso, $id_almacen);
                                if($result == 'registro_correcto'){
                                    $aux_parametro_cuadre = 1;
                                    echo '1';
                                }else{
                                    echo 'error_kardex';
                                    $aux_parametro_cuadre = 1;
                                }
                            }
                        }else{
                            echo 'cantidad_negativa';
                            $aux_parametro_cuadre = 1;
                        }
                    }else{
                        $aux_parametro_cuadre = 1;
                        echo '1';
                    }
                }
            }
        }while($aux_parametro_cuadre == 0);
    }

    function actualizar_tabla_detalle_salida(){
        $result_insert = $this->model_comercial->get_all_salidas_producto();
        foreach ($result_insert as $item) {
            $id_salida_producto = $item->id_salida_producto;
            $id_detalle_producto = $item->id_detalle_producto;
            $cantidad_salida = $item->cantidad_salida;
            $id_maquina = $item->id_maquina;
            $id_parte_maquina = $item->id_parte_maquina;
            $p_u_salida = $item->p_u_salida;

            $a_data_detalle = array('id_detalle_producto' => $id_detalle_producto,
                                    'cantidad_salida' => $cantidad_salida,
                                    'id_parte_maquina' => $id_parte_maquina,
                                    'id_maquina' => $id_maquina,
                                    'id_salida_producto' => $id_salida_producto,
                                    'p_u_salida' => $p_u_salida
                                    );
            $this->model_comercial->save_salida_detalle_producto($a_data_detalle,true);
        }


    }

    function procesar_detalle_productos_salida(){
        $this->db->trans_begin();

        $auxiliar = '';
        $count = 0;

        $id_area = $this->security->xss_clean($this->input->post('id_area'));
        $solicitante = strtoupper($this->security->xss_clean($this->input->post('solicitante')));
        $fecharegistro = $this->security->xss_clean($this->input->post('fecharegistro'));
        $observacion = $this->security->xss_clean($this->input->post('observacion'));
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));

        // registrar los datos generales de salida
        $a_data = array('id_area' => $id_area,
                        'fecha' => $fecharegistro,
                        'id_almacen' => $id_almacen,
                        'solicitante' => $solicitante,
                        'observacion' => $observacion
                        );
        $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);

        $detalle_producto_salida = $this->cart->contents();
        foreach ($detalle_producto_salida as $item) {
            $no_producto = $item['name'];
            $cantidad_salida = $item['qty'];
            if($this->cart->has_options($item['rowid']) === TRUE){
                $array = $this->cart->product_options($item['rowid']);
                $nombre_maquina = $array[0];
                $nombre_parte_maquina = $array[1];
            }
            // Obtener los id de la maquina y parte de maquina
            $this->db->select('id_maquina');
            $this->db->where('nombre_maquina',$nombre_maquina);
            $query = $this->db->get('maquina');
            foreach($query->result() as $row){
                $id_maquina = $row->id_maquina;
            }

            if($nombre_parte_maquina != ""){
                $this->db->select('id_parte_maquina');
                $this->db->where('nombre_parte_maquina',$nombre_parte_maquina);
                $query = $this->db->get('parte_maquina');
                foreach($query->result() as $row){
                    $id_parte_maquina = $row->id_parte_maquina;
                }
            }else{
                $id_parte_maquina = '';
            }

            // Enviar informacion a funcion que realizara el registro de salida de productos
            $result = $this->model_comercial->finalizar_salida_before_13($result_insert, $id_area, $solicitante,$fecharegistro,$observacion,$id_maquina,$id_parte_maquina,$no_producto,$cantidad_salida);
            
            if($result != '1' && $count == 0){
                $auxiliar = $result;
                $count++;
            }
        }
        
        if($auxiliar == ''){
            $this->cart->destroy();
            echo '1';
        }else {
            echo $auxiliar;
            die();
        }

        // Eliminar las variables de sesion de la maquina
        $this->session->unset_userdata('id_maquina');

        $this->db->trans_complete();
    }

    function registrar_devolucion()
    {
        /* Inicio del proceso - transacción */
        $this->db->trans_begin();

        $aux_bucle_saldos_ini = 0;
        // VALIDACION DE CAMPOS DE STOCK POR AREAS NO TODOS LOS PRODUCTOS TIENEN ESTA INNFORMACION
        $stock_area_sta_clara = "";
        $stock_area_sta_anita = "";
        $id_detalle_producto_area = "";

        $auxiliar = 0;
        $auxiliar_2 = 0;
        $auxiliar_3 = 0;
        $auxiliar_contador = 0;
        $validar_stock = "";
        $validar_stock_paso_2 = "";
        $auxiliar_stock_negatiVo = "";

        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $id_salida_producto = $this->security->xss_clean($this->input->post('id_salida_producto'));
        $id_detalle_producto = $this->security->xss_clean($this->input->post('id_detalle_producto'));
        $unidades_devolucion = $this->security->xss_clean($this->input->post('unidades_devolucion'));

        // Obtengo los datos del salida del producto
        $this->db->select('id_salida_producto,id_area,solicitante,fecha');
        $this->db->where('id_salida_producto',$id_salida_producto);
        $query = $this->db->get('salida_producto');
        foreach($query->result() as $row){
            $id_salida_producto = $row->id_salida_producto;
            $id_area = $row->id_area;
            $solicitante = $row->solicitante;
            $fecharegistro = $row->fecha;
        }

        // obtener los datos del detalle de la salida
        $this->db->select('cantidad_salida');
        $this->db->where('id_salida_producto',$id_salida_producto);
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('detalle_salida_producto');
        foreach($query->result() as $row){
            $cantidad_salida = $row->cantidad_salida;
        }

        // Obtengo los datos del producto antes de actualizarlos. Stock y Precio Unitario anterior
        $this->db->select('id_detalle_producto,stock,precio_unitario');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $stock_actual_sta_anita = $row->stock;
            $precio_unitario = $row->precio_unitario;
        }

        // Seleccion el id de la tabla producto
        $this->db->select('id_pro');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
        }

        // Actualizar el stock del producto por ser una devolucion
        $stock_actualizado = $stock_actual_sta_anita + $unidades_devolucion;
        $actualizar_stock = array(
            'stock'=> $stock_actualizado
        );
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $this->db->update('detalle_producto', $actualizar_stock);

        // Actualizar el registro de salida
        $unidades_salida_final = $cantidad_salida - $unidades_devolucion;
        $actualizar_stock_salida = array(
            'cantidad_salida'=> $unidades_salida_final
        );
        $this->db->where('id_salida_producto',$id_salida_producto);
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $this->db->update('detalle_salida_producto', $actualizar_stock_salida);

        // Obtener los datos del registro de la salida en el kardex para su actualizacion
        $this->db->select('id_kardex_producto,stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,cantidad_salida');
        $this->db->where('num_comprobante',$id_salida_producto);
        $this->db->where('descripcion',"SALIDA");
        $query = $this->db->get('kardex_producto');
        foreach($query->result() as $row){
            $cantidad_salida = $row->cantidad_salida;
            $stock_actual = $row->stock_actual;
            $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
            $precio_unitario_anterior = $row->precio_unitario_anterior;
            $descripcion = $row->descripcion;
            $id_kardex_producto = $row->id_kardex_producto;
        }

        $cantidad_salida_kardex = $cantidad_salida - $unidades_devolucion;
        $stock_actual_kardex = $stock_actual + $unidades_devolucion;

        // Actualizar el registro de la salida del kardex        
        $actualizar_kardex = array(
            'stock_actual'=> $stock_actual_kardex,
            'cantidad_salida'=> $cantidad_salida_kardex
        );
        $this->db->where('num_comprobante',$id_salida_producto);
        $this->db->update('kardex_producto', $actualizar_kardex);

        // $id_kardex_producto = "";
        $auxiliar_contador = 0;
        $this->db->select('id_kardex_producto');
        $this->db->where('fecha_registro >=',$fecharegistro);
        $this->db->where('id_kardex_producto >',$id_kardex_producto);
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $this->db->order_by("fecha_registro", "asc");
        $this->db->order_by("id_kardex_producto", "asc");
        $query_2 = $this->db->get('kardex_producto');
        if(count($query_2->result()) > 0){
            foreach($query_2->result() as $row_2){
                $id_kardex_producto = $row_2->id_kardex_producto; /* ID del movimiento en el kardex */
                /* Obtener los datos del movimiento del kardex */
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual,id_detalle_producto');
                $this->db->where('id_kardex_producto',$id_kardex_producto);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row_2){
                    $id_detalle_producto = $row_2->id_detalle_producto;
                    $stock_actual_act = $row_2->stock_actual;
                    $precio_unitario_actual_promedio_act = $row_2->precio_unitario_actual_promedio;
                    $precio_unitario_anterior_act = $row_2->precio_unitario_anterior;
                    $descripcion_act = $row_2->descripcion;
                    $stock_anterior_act = $row_2->stock_anterior;
                    $cantidad_salida_act = $row_2->cantidad_salida;
                    $cantidad_ingreso_act = $row_2->cantidad_ingreso;
                    $precio_unitario_actual_act = $row_2->precio_unitario_actual;

                    /* Actualización del registro */
                    if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                        if($auxiliar_contador == 0){
                            /* El stock anterior viene a ser el stock actual del movimiento anterior */
                            $new_stock_anterior_act = $stock_actual_kardex; // stock_anterior
                            $new_precio_unitario_anterior_act = $precio_unitario_anterior; // precio_unitario_anterior
                            $auxiliar_contador++;
                        }
                        /* Actualizar los datos para una entrada */
                        $stock_actual_final = $new_stock_anterior_act + $cantidad_ingreso_act;
                        $precio_unitario_actual_promedio_final = (($new_stock_anterior_act*$new_precio_unitario_anterior_act)+($cantidad_ingreso_act*$precio_unitario_actual_act))/($new_stock_anterior_act+$cantidad_ingreso_act);
                        /* Actualizar BD */
                        $actualizar = array(
                            'stock_anterior'=> $new_stock_anterior_act,
                            'precio_unitario_anterior'=> $new_precio_unitario_anterior_act,
                            'stock_actual'=> $stock_actual_final,
                            'precio_unitario_actual_promedio'=> $precio_unitario_actual_promedio_final
                        );
                        $this->db->where('id_kardex_producto',$id_kardex_producto);
                        $this->db->update('kardex_producto', $actualizar);
                        /* fin de actualizar */
                        /* Actualizar el precio unitario del producto */
                        $actualizar_p_u_2 = array(
                            'precio_unitario'=> $precio_unitario_actual_promedio_final
                        );
                        $this->db->where('id_detalle_producto',$id_detalle_producto);
                        $this->db->update('detalle_producto', $actualizar_p_u_2);
                    }else if($descripcion_act == 'SALIDA'){
                        if($auxiliar_contador == 0){
                            /* El stock anterior viene a ser el stock actual del movimiento anterior */
                            $new_stock_anterior_act = $stock_actual_kardex; // stock_anterior
                            $new_precio_unitario_anterior_act = $precio_unitario_anterior; // precio_unitario_anterior
                            $auxiliar_contador++;
                        }
                        /* Actualizar los datos para una salida */
                        $stock_actual_final = $new_stock_anterior_act - $cantidad_salida_act;
                        $precio_unitario_actual_final = $new_precio_unitario_anterior_act;
                        $precio_unitario_anterior_final = $new_precio_unitario_anterior_act;
                        /* Actualizar BD */
                        $actualizar = array(
                            'stock_anterior'=> $new_stock_anterior_act,
                            'precio_unitario_anterior'=> $precio_unitario_anterior_final,
                            'stock_actual'=> $stock_actual_final,
                            'precio_unitario_actual'=> $precio_unitario_actual_final
                        );
                        $this->db->where('id_kardex_producto',$id_kardex_producto);
                        $this->db->update('kardex_producto', $actualizar);
                        /* fin de actualizar */
                    }else if($descripcion_act == 'IMPORTACION'){
                        if($auxiliar_contador == 0){
                            /* El stock anterior viene a ser el stock actual del movimiento anterior */
                            $new_stock_anterior_act = $stock_actual_kardex; // stock_anterior
                            $new_precio_unitario_anterior_act = $precio_unitario_anterior; // precio_unitario_anterior
                            $auxiliar_contador++;
                        }
                        /* Actualizar los datos para una entrada */
                        $stock_actual_final = $new_stock_anterior_act + $cantidad_ingreso_act;
                        $precio_unitario_actual_promedio_final = 0;
                        /* Actualizar BD */
                        $actualizar = array(
                            'stock_anterior'=> $new_stock_anterior_act,
                            'precio_unitario_anterior'=> $new_precio_unitario_anterior_act,
                            'stock_actual'=> $stock_actual_final,
                            'precio_unitario_actual_promedio'=> $precio_unitario_actual_promedio_final
                        );
                        $this->db->where('id_kardex_producto',$id_kardex_producto);
                        $this->db->update('kardex_producto', $actualizar);
                        /* fin de actualizar */
                        /* Actualizar el precio unitario del producto */
                        $actualizar_p_u_2 = array(
                            'precio_unitario'=> $precio_unitario_actual_promedio_final,
                            'stock' => $stock_actual_final
                        );
                        $this->db->where('id_detalle_producto',$id_detalle_producto);
                        $this->db->update('detalle_producto', $actualizar_p_u_2);
                    }
                    /* Dejar variables con el ultimo registro del stock y precio unitario obtenido */
                    /* Este paso se realizo en la linea 4277 pero solo sirvio para un recorrido */
                    $new_stock_anterior_act = $stock_actual_final;
                    if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                        $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                    }else if($descripcion_act == 'SALIDA'){
                        $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                    }else if($descripcion_act == 'IMPORTACION'){
                        $new_precio_unitario_anterior_act = 0;
                    }
                }
            }
        }
            
        echo '1';
            
        /* Fin del proceso - transacción */
        $this->db->trans_complete();
    }

    function finalizar_salida_cuadre_contabilidad(){
        /* Inicio del proceso - transacción */
        $this->db->trans_begin();

        $validar_stock = "";

        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $fecharegistro = $this->security->xss_clean($this->input->post('fecharegistro'));
        $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
        $cantidad = $this->security->xss_clean($this->input->post('cantidad'));
        $id_area = $this->security->xss_clean($this->input->post('id_area'));

        // Obtengo los datos del producto antes de actualizarlos. Stock y Precio Unitario anterior
        $this->db->select('id_detalle_producto,stock,precio_unitario,stock_sta_clara');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $stock_actual_sta_anita = $row->stock;
            $precio_unitario = $row->precio_unitario;
            $stock_actual_sta_clara = $row->stock_sta_clara;
        }

        // Seleccion el id de la tabla producto
        $this->db->select('id_pro');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
        }

        if($id_almacen == 2){
            /* Validar si la salida esta en un periodo que ya cerro */
            $result_cierre = $this->model_comercial->validarRegistroCierre($fecharegistro);

            if($result_cierre == 'periodo_cerrado'){
                
                // Gestión de kardex
                // Obtener el ultimo id de registro para la fecha
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro <=',$fecharegistro);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                    }
                    // Obtener los datos del ultimo registro de la fecha
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual');
                    $this->db->where('id_kardex_producto',$auxiliar);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual = $row->stock_actual;
                        $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                        $precio_unitario_anterior = $row->precio_unitario_anterior;
                        $descripcion = $row->descripcion;
                        $precio_unitario_actual = $row->precio_unitario_actual;
                    }
                    if($descripcion == 'SALIDA'){
                        $precio_unitario_anterior_especial = $precio_unitario_anterior;
                    }else if($descripcion == 'ENTRADA'){
                        $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                    }

                    // Realizo en este punto el registro de la salida ya que obtengo el ultimo precio unitario correcto
                    // realizar el registro de la salida
                    $a_data = array('id_area' => $id_area,
                                    'fecha' => $fecharegistro,
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'cantidad_salida' => $cantidad,
                                    'id_almacen' => $id_almacen,
                                    'p_u_salida' => $precio_unitario_anterior_especial,
                                    );
                    $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);

                    // restar la cantidad de salida al stock actual
                    $new_stock = $stock_actual - $cantidad;
                    // realizar el registro del movimiento en el kardex
                    if($new_stock >= 0){
                        // Realizar el registro en el kardex
                        $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                        'descripcion' => "SALIDA",
                                        'id_detalle_producto' => $id_detalle_producto,
                                        'stock_anterior' => $stock_actual,
                                        'precio_unitario_anterior' => $precio_unitario_anterior_especial,
                                        'cantidad_salida' => $cantidad,
                                        'stock_actual' => $new_stock,
                                        'precio_unitario_actual' => $precio_unitario_anterior_especial,
                                        'num_comprobante' => $result_insert,
                                        );
                        $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                    }else{
                        $this->model_comercial->eliminar_insert_salida($result_insert);
                        $validar_stock = 'no_existe_stock_disponible';
                    }
                }else{
                    echo 'se considera hacer salidas que tengan movimientos anteriores';
                }

                if($validar_stock != 'no_existe_stock_disponible'){
                    // Considero los registro en el kardex de la salida como el ultimo movimiento
                    // Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización
                    $id_kardex_producto = "";
                    $auxiliar_contador = 0;
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro >',$fecharegistro);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row){
                            // Procedimiento
                            $id_kardex_producto = $row->id_kardex_producto; // ID del movimiento en el kardex
                            // Obtener los datos del movimiento del kardex
                            $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual,id_detalle_producto,fecha_registro');
                            $this->db->where('id_kardex_producto',$id_kardex_producto);
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row_2){
                                $id_detalle_producto = $row_2->id_detalle_producto;
                                $stock_actual_act = $row_2->stock_actual;
                                $precio_unitario_actual_promedio_act = $row_2->precio_unitario_actual_promedio;
                                $precio_unitario_anterior_act = $row_2->precio_unitario_anterior;
                                $descripcion_act = $row_2->descripcion;
                                $stock_anterior_act = $row_2->stock_anterior;
                                $cantidad_salida_act = $row_2->cantidad_salida;
                                $cantidad_ingreso_act = $row_2->cantidad_ingreso;
                                $precio_unitario_actual_act = $row_2->precio_unitario_actual;
                                $fecha_registro_kardex_post = $row_2->fecha_registro;
                                if($auxiliar_contador == 0){
                                    // El stock anterior viene a ser el stock actual del movimiento anterior
                                    $new_stock_anterior_act = $new_stock; // stock_anterior
                                    $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
                                    $auxiliar_contador++;
                                }
                                // Actualización del registro
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    /* Actualizar los datos para una entrada */
                                    $stock_actual_final = $new_stock_anterior_act + $cantidad_ingreso_act;
                                    $precio_unitario_actual_promedio_final = (($new_stock_anterior_act*$new_precio_unitario_anterior_act)+($cantidad_ingreso_act*$precio_unitario_actual_act))/($new_stock_anterior_act+$cantidad_ingreso_act);
                                    /* Actualizar BD */
                                    $actualizar = array(
                                        'stock_anterior'=> $new_stock_anterior_act,
                                        'precio_unitario_anterior'=> $new_precio_unitario_anterior_act,
                                        'stock_actual'=> $stock_actual_final,
                                        'precio_unitario_actual_promedio'=> $precio_unitario_actual_promedio_final
                                    );
                                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                                    $this->db->update('kardex_producto', $actualizar);
                                    /* fin de actualizar */
                                    /* Actualizar el precio unitario del producto */
                                    $actualizar = array(
                                        'precio_unitario'=> $precio_unitario_actual_promedio_final,
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar);
                                }else if($descripcion_act == 'SALIDA'){
                                    /* Actualizar los datos para una salida */
                                    $stock_actual_final = $new_stock_anterior_act - $cantidad_salida_act;
                                    $precio_unitario_actual_final = $new_precio_unitario_anterior_act;
                                    $precio_unitario_anterior_final = $new_precio_unitario_anterior_act;
                                    /* Actualizar BD */
                                    $actualizar = array(
                                        'stock_anterior'=> $new_stock_anterior_act,
                                        'precio_unitario_anterior'=> $precio_unitario_anterior_final,
                                        'stock_actual'=> $stock_actual_final,
                                        'precio_unitario_actual'=> $precio_unitario_actual_final
                                    );
                                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                                    $this->db->update('kardex_producto', $actualizar);
                                    /* fin de actualizar */
                                    $actualizar = array(
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar);
                                }if($descripcion_act == 'IMPORTACION'){
                                    /* Actualizar los datos para una entrada */
                                    $stock_actual_final = $new_stock_anterior_act + $cantidad_ingreso_act;
                                    $precio_unitario_actual_promedio_final = 0;
                                    /* Actualizar BD */
                                    $actualizar = array(
                                        'stock_anterior'=> $new_stock_anterior_act,
                                        'precio_unitario_anterior'=> $new_precio_unitario_anterior_act,
                                        'stock_actual'=> $stock_actual_final,
                                        'precio_unitario_actual_promedio'=> $precio_unitario_actual_promedio_final
                                    );
                                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                                    $this->db->update('kardex_producto', $actualizar);
                                    /* fin de actualizar */
                                    /* Actualizar el precio unitario del producto */
                                    $actualizar = array(
                                        'precio_unitario'=> $precio_unitario_actual_promedio_final,
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar);
                                }
                                // Dejar variables con el ultimo registro del stock y precio unitario obtenido para el siguiente recorrido
                                $new_stock_anterior_act = $stock_actual_final;
                                if($new_stock_anterior_act >= 0){
                                    if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                                    }else if($descripcion_act == 'SALIDA'){
                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                    }else if($descripcion_act == 'IMPORTACION'){
                                        $new_precio_unitario_anterior_act = 0;
                                    }
                                }else{
                                    echo 'negativo_registros_posteriores';
                                }

                                // se necesita conocer si el registro que se esta trabajando corresponde al ultimo del mes
                                // Selecciono el ultimo id del periodo al que corresponde la fecha
                                $elementos = explode("-", $fecha_registro_kardex_post);
                                $anio = $elementos[0];
                                $mes = $elementos[1];
                                $dia = $elementos[2];
                                if($mes == 12){
                                    $anio = $anio + 1;
                                    $mes_siguiente = 1;
                                    $dia = 1;
                                }else if($mes <= 11 ){
                                    $mes_siguiente = $mes + 1;
                                    $dia = 1;
                                }
                                $array = array($anio, $mes_siguiente, $dia);
                                $fecha_formateada_post = implode("-", $array);
                                // Formato
                                $this->db->select('id_kardex_producto');
                                $this->db->where('fecha_registro >=',$fecha_registro_kardex_post);
                                $this->db->where('fecha_registro <',date($fecha_formateada_post));
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->order_by("fecha_registro", "asc");
                                $this->db->order_by("id_kardex_producto", "asc");
                                $query = $this->db->get('kardex_producto');
                                if(count($query->result()) > 0){
                                    foreach($query->result() as $row_3){
                                        $id_kardex_producto_ultimo = $row_3->id_kardex_producto;
                                    }
                                }
                                // verificar si el id que se esta trabajando es el ultimo del mes
                                // para actualizar los saldos iniciales y el monto de cierre
                                if($id_kardex_producto == $id_kardex_producto_ultimo){
                                    // Actualizar los saldos iniciales y el monto de cierre
                                    // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                                    $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                                    $this->db->where('id_pro',$id_pro);
                                    $this->db->where('fecha_cierre',$fecha_formateada_post);
                                    $query = $this->db->get('saldos_iniciales');
                                    if($query->num_rows() > 0){
                                        foreach($query->result() as $row){
                                            $id_saldos_iniciales = $row->id_saldos_iniciales;
                                            $stock_inicial_antes_actualizacion = $row->stock_inicial;
                                            $stock_inicial_sta_clara_antes_actualizacion = $row->stock_inicial_sta_clara;
                                            $precio_uni_inicial_antes_actualizacion = $row->precio_uni_inicial; // todavia no esta actualizado
                                        }
                                        if($id_almacen == 2){ // Sta. anita
                                            $actualizar = array(
                                                'precio_uni_inicial'=> $new_precio_unitario_anterior_act,
                                                'stock_inicial'=> $new_stock_anterior_act
                                            );
                                            $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                            $this->db->update('saldos_iniciales', $actualizar);
                                        }
                                        // Actualizar monto final de cierre del mes
                                        // Obtengo los stock de cierre actualizados en el paso anterior
                                        $stock_general_cierre = $stock_inicial_antes_actualizacion + $stock_inicial_sta_clara_antes_actualizacion;
                                        $monto_parcial_producto_anterior = $precio_uni_inicial_antes_actualizacion * $stock_general_cierre;
                                        $monto_parcial_producto_nuevo = $new_precio_unitario_anterior_act * ($stock_general_cierre - $cantidad);
                                        // Seleccionar el monto de cierre
                                        $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                                        $this->db->where('fecha_auxiliar',$fecha_formateada_post);
                                        $query = $this->db->get('monto_cierre');
                                        if($query->num_rows()>0){
                                            foreach($query->result() as $row){
                                                $fecha_cierre = $row->fecha_cierre;
                                                $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                                                $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                                                $fecha_auxiliar = $row->fecha_auxiliar;
                                            }
                                            $monto_cierre_sta_anita = $monto_cierre_sta_anita - $monto_parcial_producto_anterior;
                                            $monto_cierre_sta_anita = $monto_cierre_sta_anita + $monto_parcial_producto_nuevo;
                                            // Nuevo monto de cierre general
                                            $monto_general_actualizado = $monto_cierre_sta_anita + $monto_cierre_sta_clara;
                                            $actualizar = array(
                                                'monto_cierre'=> $monto_general_actualizado,
                                                'monto_cierre_sta_anita'=> $monto_cierre_sta_anita
                                            );
                                            $this->db->where('fecha_auxiliar',$fecha_formateada_post);
                                            $this->db->update('monto_cierre',$actualizar);
                                        }
                                    }
                                }
                            }
                        }
                        echo '1';
                    }
                }else{
                    echo 'no_existe_stock_disponible';
                }
                
            }else{
                echo 'error';
            }
        }

        /* Fin del proceso - transacción */
        $this->db->trans_complete();
    }
    
    function finalizar_registro_otros()
    {
        $this->form_validation->set_rules('comprobante', 'Comprobante', 'trim|required|xss_clean');
        $this->form_validation->set_rules('numcomprobante', 'Nro. de Comprobante', 'trim|required|min_length[1]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('fecharegistro', 'Fecha de Registro', 'trim|required|xss_clean');
        $this->form_validation->set_rules('moneda', 'Moneda', 'trim|required|xss_clean');
        $this->form_validation->set_rules('proveedor', 'Proveedor', 'trim|required|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 1 dígito como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 20 dígitos como máximo.');
        //Delimitadores de ERROR:
        $this->form_validation->set_error_delimiters('<span>', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {
            //echo validation_errors();
            $data['error']= validation_errors();
            $data['listaagente']= $this->model_comercial->listaAgenteAduana();
            $data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
            $data['listacomprobante']= $this->model_comercial->listarComprobantes();
            $data['listaproveedor']= $this->model_comercial->listaProveedor();
            $data['listasimmon']= $this->model_comercial->listaSimMon();
            $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
            $this->load->view('comercial/registro_ingreso_otros', $data);
        }
        else
        {
            $existe = $this->cart->total_items();
            if($existe <= 0){
                $data['error'] = '<span style="color:red"><b>ERROR:</b> Debe Registrar un Productos como mínimo al Comprobante.</span>';
                $data['listaagente']= $this->model_comercial->listaAgenteAduana();
                $data['prodIngresados']= $this->model_comercial->listarProductosIngresados();
                $data['listacomprobante']= $this->model_comercial->listarComprobantes();
                $data['listaproveedor']= $this->model_comercial->listaProveedor();
                $data['listasimmon']= $this->model_comercial->listaSimMon();
                $data['listanombreproducto']= $this->model_comercial->listaNombreProducto();
                $this->load->view('comercial/registro_ingreso_otros', $data);
            }else{
                $tipo_comprobante = $this->security->xss_clean($this->input->post("comprobante"));
                $numcomprobante = $this->security->xss_clean($this->input->post("numcomprobante"));
                $moneda = $this->security->xss_clean($this->input->post("moneda"));
                $proveedor = $this->security->xss_clean($this->input->post("proveedor"));
                $fecharegistro = $this->security->xss_clean($this->input->post("fecharegistro"));
                //$porcentaje = $this->security->xss_clean($this->input->post("porcent"));
                $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
                $total = $this->cart->total()+($this->cart->total()*0.18);

                $carrito = $this->cart->contents();

                //Agregamos le registro_ingreso a la bd
                $datos = array(
                    "id_comprobante" => $tipo_comprobante,
                    "nro_comprobante" => $numcomprobante,
                    "fecha" => $fecharegistro,
                    "id_moneda" => $moneda,
                    "id_proveedor" => $proveedor,
                    "total" => $total,
                    //"gastos" => $porcentaje,
                    "id_almacen" => $almacen
                );
                $id_ingreso_producto = $this->model_comercial->agrega_ingreso($datos);

                //Agregamos el detalle del comprobante
                $result = $this->model_comercial->agregar_detalle_ingreso($carrito, $id_ingreso_producto);

                if(!$result){
                    //Sí no se encotnraron datos.
                    echo '<span style="color:red"><b>ERROR:</b> Este Proveedor no se encuentra registrado.</span>';
                }else{
                    //Registramos la sesion del usuario
                    $this->cart->destroy();
                    redirect('comercial/gestionotrosDoc');
                }

                //print_r($id_ingreso_producto);
                //redirect('comercial/gestioningreso');
            }
        }
    }

    public function traeMarca()
    {
        $marca = $this->model_comercial->getMarca();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($marca as $fila)
        {
            echo '<option value="'.$fila->id_marca_maquina.'">'.$fila->no_marca.'</option>';
        }
    }

    public function traeModelo()
    {
        $modelo = $this->model_comercial->getModelo();
        echo '<option value=""> :: SELECCIONE ::</option>';
        foreach($modelo as $fila)
        {
            echo '<option value="'.$fila->id_modelo_maquina.'">'.$fila->no_modelo.'</option>';
        }
    }

    public function traeSerie()
    {
        $modelo = $this->model_comercial->getSerie();
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
        $maquina = $this->model_comercial->listarMaquinaFiltroPdf();

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

    public function reporteproveedorespdf(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdf');

        // Se obtienen los alumnos de la base de datos
        $proveedores = $this->model_comercial->listarProveedoresFiltroPdf();
 
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

    public function reporteingresospdf(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfIngresos');

        // Se obtienen los alumnos de la base de datos
        $reg_ingresos = $this->model_comercial->listaRegistrosFiltroPdf();
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
                //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
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
            $this->pdf->Output("Lista de Registro de Ingreso de Productos.pdf", 'I');
    }

    public function reporteingresospdf_otros(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfIngresos');

        // Se obtienen los alumnos de la base de datos
        $reg_ingresos = $this->model_comercial->listaRegistrosFiltroPdf_otros();
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

    public function reportesalidapdf(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfSalidas');

        // Se obtienen los alumnos de la base de datos
        $reg_salida = $this->model_comercial->listaRegistrosSalidaFiltroPdf();
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

    public function reportesalida_solicitante_pdf(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfSalidas');

        // Se obtienen los alumnos de la base de datos
        $reg_salida = $this->model_comercial->listaRegistrosSalidaFiltroPdf();
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

    public function reporteproductospdf(){
        // Se carga el modelo alumno
        //$this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfProductos');
 
        // Se obtienen los productos de la base de datos
        $productos = $this->model_comercial->listarProductoFiltro();
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

    public function reporteingreso_producto_pdf(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfIngresos');

        // Se obtienen los alumnos de la base de datos
        $reg_ingresos = $this->model_comercial->listaRegistros_productoFiltroPdf();
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

    public function reporteingreso_producto_pdf_otros(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfIngresos_otros');

        // Se obtienen los alumnos de la base de datos
        $reg_ingresos = $this->model_comercial->listaRegistros_productoFiltroPdf_otros();
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

    public function reporteingreso_agente_pdf(){
        // Se carga el modelo alumno
        $this->load->model('model_comercial');
        // Se carga la libreria fpdf
        $this->load->library('pdfIngresos');

        // Se obtienen los alumnos de la base de datos
        $reg_ingresos = $this->model_comercial->listaRegistros_agenteFiltroPdf();
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
                //$this->pdf->Cell(25,5,$reg->id_reg,'B',0,'L',0);
                $this->pdf->Cell(26,8,$reg->nro_comprobante,'BR BT',0,'C',0);
                $this->pdf->Cell(50,8,$reg->razon_social,'BR BT',0,'C',0);
                $this->pdf->Cell(32,8,$reg->fecha,'BR BT',0,'C',0);
                $this->pdf->Cell(31,8,utf8_decode($reg->nombresimbolo),'BR BT',0,'C',0);
                $this->pdf->Cell(23,8,$reg->total,'BR BT',0,'C',0);
                $this->pdf->Cell(36,8,$reg->no_agente,'BR BT',0,'C',0);
                $this->pdf->Cell(26,8,$porcentaje.'%','BR BT',0,'C',0);
                $this->pdf->Cell(18,8,@number_format($gasto_agente, 2, '.', ''),'BR BT',0,'C',0);
                //$this->pdf->Cell(18,8,$,'BR BT',0,'C',0);
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

    public function guardarTipoCambio(){
        $this->form_validation->set_rules('compra_dol', 'Compra en Dólares', 'trim|required|min_length[4]|max_length[5]|xss_clean');
        $this->form_validation->set_rules('venta_dol', 'Venta en Dólares', 'trim|required|min_length[4]|max_length[5]|xss_clean');
        $this->form_validation->set_rules('compra_eur', 'Compra en Euros', 'trim|required|min_length[4]|max_length[5]|xss_clean');
        $this->form_validation->set_rules('venta_eur', 'Venta en Euros', 'trim|required|min_length[4]|max_length[5]|xss_clean');
        //Mensajes
        $this->form_validation->set_message('required','ERROR: Falta completar el campo: %s.');
        $this->form_validation->set_message('min_length','ERROR: El campo %s debe tener 4 dígitos como mínimo.');
        $this->form_validation->set_message('max_length','ERROR: El campo %s debe tener 5 dígitos como máximo.');
        //Dolimitadores de ERROR: Color
        $this->form_validation->set_error_delimiters('<span style="color:red;font-size:10px;float: left;">', '</span><br>');

        if($this->form_validation->run() == FALSE)
        {           
            echo validation_errors();
        }
        else
        {
            $datacompra_dol = $this->security->xss_clean($this->input->post('compra_dol'));
            $dataventa_dol = $this->security->xss_clean($this->input->post('venta_dol'));
            $datacompra_eur = $this->security->xss_clean($this->input->post('compra_eur'));
            $dataventa_eur = $this->security->xss_clean($this->input->post('venta_eur'));
            if(!empty($datacompra_dol) && !empty($dataventa_dol) && !empty($datacompra_eur) && !empty($dataventa_eur)){ 
                $result = $this->model_comercial->saveTipoCambio();
                if(!$result){
                    echo '<span style="color:red">ERROR: No se puede guardar ni actualizar.</span>';
                }else{
                    echo 'ok';
                }
            }else{ 
                echo 'no'; 
            }
        }
    }

    public function eliminarregistroingreso()
    {
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $id_registro_ingreso = $this->input->get('eliminar');
        $result = $this->model_comercial->eliminarRegistroIngreso_aleatorio($id_registro_ingreso,$almacen);
        if(!$result){
            echo '<b>--> No puede eliminar Registros de un periodo donde se ya realizo el Cierre Mensual de Almacén.</b>';
        }else{
            echo '1';
        }
    }

    public function eliminarsalidaproducto()
    {
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $id_salida_producto = $this->input->get('eliminar');
        $result = $this->model_comercial->eliminarSalidaProducto($id_salida_producto,$almacen);
        if(!$result){
            echo '<b>--> No puede eliminar Registros de un periodo donde se ya realizo el Cierre Mensual de Almacén.</b>';
        }else{
            echo '1';
        }
    }

    public function procedimiento_eliminacion_salidas()
    {
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $fechainicial = $this->security->xss_clean($this->input->post("fechainicial"));
        $fechafinal = $this->security->xss_clean($this->input->post("fechafinal"));
        // Realizar  la consulta de las salidas a eliminar
        $reg_salidas_eliminadas = $this->model_comercial->get_salidas_eliminar($fechainicial, $fechafinal);
        foreach ($reg_salidas_eliminadas as $row){
            $id_salida_producto = $row->id_salida_producto;
            $fecha = $row->fecha;
            $result = $this->model_comercial->eliminarSalidaProducto($id_salida_producto,$almacen);
            var_dump($id_salida_producto.' ');
        }
        if(!$result){
            echo '<b>--> No puede eliminar Registros de un periodo donde se ya realizo el Cierre Mensual de Almacén.</b>';
        }else{
            echo '1';
        }
    }

    public function actualizar_saldos_iniciales_controller(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $fechainicial = $this->security->xss_clean($this->input->post("fechainicial"));
        $fechafinal = $this->security->xss_clean($this->input->post("fechafinal"));
        // Formato a la fecha para actualizar los cierre anterior
        $elementos = explode("-", $fechainicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes;
            $dia = 1;
        }
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada_anterior = implode("-", $array);
        // Formato a la fecha para actualizar los cierre anterior
        $elementos = explode("-", $fechafinal);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes + 1;
            $dia = 1;
        }
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada_posterior = implode("-", $array);
        // Realizar un consulta de todos los productos registrados en el sistema
        // para verificar los movimientos de esos productos en el kardex y seleccionar el ultimo movimiento de ese mes
        // para obtener el stock y el precio final para el cierre del mes
        $data_product = $this->model_comercial->get_all_productos();
        foreach ($data_product as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $id_pro = $row->id_pro;
            // validacion si existe un registro de este producto en kardex dentro del periodo seleccionado
            $validacion = $this->model_comercial->validar_registros_producto_periodo($fechainicial, $fechafinal, $id_detalle_producto);
            if($validacion == 'no_existe_movimiento'){
                // Verificar si existe saldos iniciales del mes anterior para colocarlos en el saldo inicial actual
                $this->db->select('stock_inicial,precio_uni_inicial,id_saldos_iniciales');
                $this->db->where('fecha_cierre',date($fecha_formateada_anterior));
                $this->db->where('id_pro',$id_pro);
                $query = $this->db->get('saldos_iniciales');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $id_saldos_iniciales = $row->id_saldos_iniciales;
                        $stock_inicial = $row->stock_inicial;
                        $precio_uni_inicial = $row->precio_uni_inicial;
                    }
                    // Actualizar los saldos iniciales del mes que se selecciono
                    $actualizar = array(
                        'precio_uni_inicial'=> $precio_uni_inicial,
                        'stock_inicial' => $stock_inicial
                    );
                    $this->db->where('id_pro',$id_pro);
                    $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                    $this->db->update('saldos_iniciales', $actualizar);
                }else{
                    $actualizar = array(
                        'precio_uni_inicial'=> 0,
                        'stock_inicial' => 0
                    );
                    $this->db->where('id_pro',$id_pro);
                    $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                    $this->db->update('saldos_iniciales', $actualizar);
                }
            }else{
                // Obtener los ultimos datos nececesarios del kardex para la actualizacion del saldos inicial del producto en el periodo que corresponde
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                $this->db->where('id_kardex_producto',(int)$validacion);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $stock_actual = $row->stock_actual;
                    $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                    $precio_unitario_anterior = $row->precio_unitario_anterior;
                    $descripcion = $row->descripcion;
                    $precio_unitario_actual = $row->precio_unitario_actual;
                    $fecha_registro = $row->fecha_registro;
                }
                // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                if($descripcion == 'SALIDA'){
                    $precio_unitario_anterior_especial = $precio_unitario_anterior;
                }else if($descripcion == 'ENTRADA'){
                    $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                }

                // Actualizar el saldo inicial del producto en la fecha que corresponde
                $actualizar = array(
                    'precio_uni_inicial'=> $precio_unitario_anterior_especial,
                    'stock_inicial' => $stock_actual
                );
                $this->db->where('id_pro',$id_pro);
                $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                $this->db->update('saldos_iniciales', $actualizar);
            }
        }
        echo '1';
    }

    public function actualizar_saldos_iniciales_controller_version_2(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $fechainicial = $this->security->xss_clean($this->input->post("fechainicial"));
        $fechafinal = $this->security->xss_clean($this->input->post("fechafinal"));
        // Formato a la fecha para actualizar los cierre anterior
        $elementos = explode("-", $fechainicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes;
            $dia = 1;
        }
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada_anterior = implode("-", $array);
        // Formato a la fecha para actualizar los cierre anterior
        $elementos = explode("-", $fechafinal);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes + 1;
            $dia = 1;
        }
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada_posterior = implode("-", $array);
        // Realizar un consulta de todos los productos registrados en el sistema
        // para verificar los movimientos de esos productos en el kardex y seleccionar el ultimo movimiento de ese mes
        // para obtener el stock y el precio final para el cierre del mes
        $data_product = $this->model_comercial->get_all_productos();
        foreach ($data_product as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $id_pro = $row->id_pro;
            // validacion si existe un registro de este producto en kardex dentro del periodo seleccionado
            $validacion = $this->model_comercial->validar_registros_producto_periodo($fechainicial, $fechafinal, $id_detalle_producto);
            if($validacion == 'no_existe_movimiento'){
                // Verificar si existe saldos iniciales del mes anterior para colocarlos en el saldo inicial actual
                $this->db->select('stock_inicial,precio_uni_inicial,id_saldos_iniciales,stock_inicial_sta_clara');
                $this->db->where('fecha_cierre',date($fecha_formateada_anterior));
                $this->db->where('id_pro',$id_pro);
                $query = $this->db->get('saldos_iniciales');
                if(count($query->result()) > 0){
                    // Obtengo los saldos iniciales del mes anterior
                    // osea del mes actual que se esta trabajando
                    foreach($query->result() as $row){
                        $id_saldos_iniciales_anterior = $row->id_saldos_iniciales;
                        $stock_inicial_anterior = $row->stock_inicial;
                        $stock_inicial_sta_clara_anterior = $row->stock_inicial_sta_clara;
                        $precio_uni_inicial_anterior = $row->precio_uni_inicial;
                    }
                    $total_saldo_inicial_anterior = $stock_inicial_anterior + $stock_inicial_sta_clara_anterior;
                    // Obtener los saldos iniciales del mes posterior
                    $this->db->select('stock_inicial,precio_uni_inicial,id_saldos_iniciales,stock_inicial_sta_clara');
                    $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                    $this->db->where('id_pro',$id_pro);
                    $query = $this->db->get('saldos_iniciales');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row){
                            $id_saldos_iniciales_posterior = $row->id_saldos_iniciales;
                            $stock_inicial_posterior = $row->stock_inicial;
                            $stock_inicial_sta_clara_posterior = $row->stock_inicial_sta_clara;
                            $precio_uni_inicial_posterior = $row->precio_uni_inicial;
                        }
                    }
                    // validacion de resultados negativos
                    if($stock_inicial_sta_clara_posterior < 0){
                        $stock_inicial_sta_clara_posterior = 0;
                    }else if($stock_inicial_posterior < 0 ){
                        $stock_inicial_posterior = 0;
                    }
                    // totalizar stock's de cierre
                    $total_saldo_inicial_posterior = $stock_inicial_posterior + $stock_inicial_sta_clara_posterior;
                    // Distribucion de casos
                    if($total_saldo_inicial_anterior < $total_saldo_inicial_posterior){
                        $diferencia = $total_saldo_inicial_posterior - $total_saldo_inicial_anterior;
                        // Quitar la diferencia a sta anita
                        $result_posterior_anita = $stock_inicial_posterior - $diferencia;                       
                        if($result_posterior_anita > 0){
                            // Validacion por la cantidad en unidades de los saldos iniciales
                            // Actualizar los saldos iniciales del mes que se selecciono
                            $actualizar = array(
                                'precio_uni_inicial'=> $precio_uni_inicial_posterior,
                                'stock_inicial' => $result_posterior_anita
                            );
                            $this->db->where('id_pro',$id_pro);
                            $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                            $this->db->update('saldos_iniciales', $actualizar);
                        }else{
                            $result_posterior_clara = $stock_inicial_sta_clara_posterior - $diferencia;
                            // Actualizar los saldos iniciales del mes que se selecciono
                            $actualizar = array(
                                'precio_uni_inicial'=> $precio_uni_inicial_posterior,
                                'stock_inicial_sta_clara' => $result_posterior_clara
                            );
                            $this->db->where('id_pro',$id_pro);
                            $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                            $this->db->update('saldos_iniciales', $actualizar);
                        }
                    }else if($total_saldo_inicial_anterior > $total_saldo_inicial_posterior){
                        $diferencia = $total_saldo_inicial_anterior - $total_saldo_inicial_posterior;
                        // Aumento la diferencia a sta anita
                        $result_posterior_anita = $stock_inicial_posterior + $diferencia;
                        // Actualizar los saldos iniciales del mes que se selecciono
                        $actualizar = array(
                            'precio_uni_inicial'=> $precio_uni_inicial_posterior,
                            'stock_inicial' => $result_posterior_anita
                        );
                        $this->db->where('id_pro',$id_pro);
                        $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                        $this->db->update('saldos_iniciales', $actualizar);
                    }
                }else{
                    $actualizar = array(
                        'precio_uni_inicial'=> 0,
                        'stock_inicial' => 0
                    );
                    $this->db->where('id_pro',$id_pro);
                    $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                    $this->db->update('saldos_iniciales', $actualizar);
                }
            }else{
                // Obtener los ultimos datos nececesarios del kardex para la actualizacion del saldos inicial del producto en el periodo que corresponde
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                $this->db->where('id_kardex_producto',(int)$validacion);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $stock_actual = $row->stock_actual;
                    $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                    $precio_unitario_anterior = $row->precio_unitario_anterior;
                    $descripcion = $row->descripcion;
                    $precio_unitario_actual = $row->precio_unitario_actual;
                    $fecha_registro = $row->fecha_registro;
                }
                // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                if($descripcion == 'SALIDA'){
                    $precio_unitario_anterior_especial = $precio_unitario_anterior;
                }else if($descripcion == 'ENTRADA'  || $descripcion == 'ORDEN INGRESO'){
                    $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                }
                // Obtener los saldos iniciales de cierre del mes que ya se tiene como registro
                $this->db->select('stock_inicial,precio_uni_inicial,id_saldos_iniciales,stock_inicial_sta_clara');
                $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                $this->db->where('id_pro',$id_pro);
                $query = $this->db->get('saldos_iniciales');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $id_saldos_iniciales_posterior = $row->id_saldos_iniciales;
                        $stock_inicial_posterior = $row->stock_inicial;
                        $stock_inicial_sta_clara_posterior = $row->stock_inicial_sta_clara;
                        $precio_uni_inicial_posterior = $row->precio_uni_inicial;
                    }
                }
                // validacion de resultados negativos
                if($stock_inicial_sta_clara_posterior < 0){
                    $stock_inicial_sta_clara_posterior = 0;
                }else if($stock_inicial_posterior < 0 ){
                    $stock_inicial_posterior = 0;
                }
                // totalizar stock's de cierre
                $total_saldo_inicial_posterior = $stock_inicial_posterior + $stock_inicial_sta_clara_posterior;
                if($stock_actual < $total_saldo_inicial_posterior){
                    $diferencia = $total_saldo_inicial_posterior - $stock_actual;
                    // Quitar la diferencia a sta anita
                    $result_posterior_anita = $stock_inicial_posterior - $diferencia;                       
                    if($result_posterior_anita > 0){
                        // Validacion por la cantidad en unidades de los saldos iniciales
                        // Actualizar los saldos iniciales del mes que se selecciono
                        $actualizar = array(
                            'precio_uni_inicial'=> $precio_unitario_anterior_especial,
                            'stock_inicial' => $result_posterior_anita
                        );
                        $this->db->where('id_pro',$id_pro);
                        $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                        $this->db->update('saldos_iniciales', $actualizar);
                    }else{
                        $result_posterior_clara = $stock_inicial_sta_clara_posterior - $diferencia;
                        // Actualizar los saldos iniciales del mes que se selecciono
                        $actualizar = array(
                            'precio_uni_inicial'=> $precio_unitario_anterior_especial,
                            'stock_inicial_sta_clara' => $result_posterior_clara
                        );
                        $this->db->where('id_pro',$id_pro);
                        $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                        $this->db->update('saldos_iniciales', $actualizar);
                    }
                }else if($stock_actual > $total_saldo_inicial_posterior){
                    $diferencia = $stock_actual - $total_saldo_inicial_posterior;
                    // Aumento la diferencia a sta anita
                    $result_posterior_anita = $stock_inicial_posterior + $diferencia;
                    // Actualizar los saldos iniciales del mes que se selecciono
                    $actualizar = array(
                        'precio_uni_inicial'=> $precio_unitario_anterior_especial,
                        'stock_inicial' => $result_posterior_anita
                    );
                    $this->db->where('id_pro',$id_pro);
                    $this->db->where('fecha_cierre',date($fecha_formateada_posterior));
                    $this->db->update('saldos_iniciales', $actualizar);
                }
            }
        }
        echo '1';
    }

    public function actualizar_saldos_iniciales_controller_version_3(){
        // Realizar un consulta de todos los productos registrados en el sistema
        // para verificar los movimientos de esos productos en el kardex y seleccionar el ultimo movimiento
        // para obtener el stock y el precio final para el cierre del mes
        $data_product = $this->model_comercial->get_all_productos();
        foreach ($data_product as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $id_pro = $row->id_pro;
            $validacion = $this->model_comercial->validar_registros_producto_kardex($id_detalle_producto);
            if($validacion == 'no_existe_movimiento'){
                $actualizar = array(
                    'precio_unitario'=> 0
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('detalle_producto', $actualizar);
            }else{
                // Obtener los ultimos datos nececesarios del kardex para la actualizacion del saldos inicial del producto en el periodo que corresponde
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                $this->db->where('id_kardex_producto',(int)$validacion);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $stock_actual = $row->stock_actual;
                    $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                    $precio_unitario_anterior = $row->precio_unitario_anterior;
                    $descripcion = $row->descripcion;
                    $precio_unitario_actual = $row->precio_unitario_actual;
                    $fecha_registro = $row->fecha_registro;
                }
                // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                if($descripcion == 'SALIDA' || $descripcion == 'IMPORTACION'){
                    if($precio_unitario_anterior == ""){
                        $precio_unitario_anterior_especial = 0;
                    }else{
                        $precio_unitario_anterior_especial = $precio_unitario_anterior;
                    }
                }else if($descripcion == 'ENTRADA'){
                    if($precio_unitario_anterior == ""){
                        $precio_unitario_anterior_especial = 0;
                    }else{
                        $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                    }
                }else if($descripcion == 'ORDEN INGRESO'){
                    if($precio_unitario_anterior == ""){
                        $precio_unitario_anterior_especial = 0;
                    }else{
                        $precio_unitario_anterior_especial = $precio_unitario_actual;
                    }
                }
                $actualizar = array(
                    'precio_unitario'=> $precio_unitario_anterior_especial
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('detalle_producto', $actualizar);
            }
        }
        echo '1';
    }

    public function actualizar_stock_controller_version_4(){
        // Realizar un consulta de todos los productos registrados en el sistema
        // para verificar los movimientos de esos productos en el kardex y seleccionar el ultimo movimiento
        // para obtener el stock y el precio final para el cierre del mes
        $data_product = $this->model_comercial->get_all_productos();
        foreach ($data_product as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $id_pro = $row->id_pro;
            $validacion = $this->model_comercial->validar_registros_producto_kardex($id_detalle_producto);
            if($validacion == 'no_existe_movimiento'){
                $actualizar = array(
                    'stock'=> 0,
                    'stock_sta_clara'=> 0
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('detalle_producto', $actualizar);
            }else{
                // Obtener los ultimos datos nececesarios del kardex para la actualizacion del saldos inicial del producto en el periodo que corresponde
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                $this->db->where('id_kardex_producto',(int)$validacion);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $stock_actual_kardex = $row->stock_actual;
                    $descripcion = $row->descripcion;
                }
                // Obtener el stock actual de la tabla detalle_producto
                $this->db->select('stock,stock_sta_clara');
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $query = $this->db->get('detalle_producto');
                foreach($query->result() as $row){
                    $stock = $row->stock;
                    $stock_sta_clara = $row->stock_sta_clara;
                }
                $stock_total = $stock + $stock_sta_clara;

                // Validacion de resultados
                if($stock_actual_kardex == 0){
                    $actualizar = array(
                        'stock'=> 0,
                        'stock_sta_clara'=> 0
                    );
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->update('detalle_producto', $actualizar);
                }else{
                    if($stock_total > $stock_actual_kardex){
                        $diferencia = $stock_total - $stock_actual_kardex;
                        // Validar resultados negativos
                        // Sta anita
                        $result_sta_anita = $stock - $diferencia;
                        // Sta clara
                        $result_sta_clara = $stock_sta_clara - $diferencia;
                        if($result_sta_anita >= 0){
                            $actualizar = array(
                                'stock'=> $result_sta_anita
                            );
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $this->db->update('detalle_producto', $actualizar);
                        }else if($result_sta_clara >= 0){
                            $actualizar = array(
                                'stock_sta_clara'=> $result_sta_clara
                            );
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $this->db->update('detalle_producto', $actualizar);
                        }else{
                            var_dump(' validar manualmente: '.$id_detalle_producto.' ');
                        }
                    }else if($stock_total < $stock_actual_kardex){
                        $diferencia = $stock_actual_kardex - $stock_total;
                        if($stock == 0){
                            $stock_sta_clara = $stock_sta_clara + $diferencia;
                            $actualizar = array(
                                'stock_sta_clara'=> $stock_sta_clara
                            );
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $this->db->update('detalle_producto', $actualizar);
                        }else{
                            $stock = $stock + $diferencia;
                            $actualizar = array(
                                'stock'=> $stock
                            );
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $this->db->update('detalle_producto', $actualizar);
                        }
                    }
                }
            }
        }
        echo '1';
    }

    public function gestion_cierre_saldos_iniciales(){
        $nombre = $this->security->xss_clean($this->session->userdata('nombre')); //Variable de sesion
        $apellido = $this->security->xss_clean($this->session->userdata('apaterno')); //Variable de sesion
        if($nombre == "" AND $apellido == ""){
            $this->load->view('login');
        }else{
            if($this->model_comercial->existeTipoCambio() == TRUE){
            $data['tipocambio'] = 0;
            }else{
                $data['tipocambio'] = 1;
            }
            $data['monto']= $this->model_comercial->listarMontoCierre();
            $this->load->view('comercial/menu');
            $this->load->view('comercial/view_cierre_saldos_iniciales', $data);
        }
    }

    public function actualizar_saldos_iniciales_controller_version_6(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $fecha_inicial = $this->security->xss_clean($this->input->post("fecha_inicial"));
        $fecha_final = $this->security->xss_clean($this->input->post("fecha_final"));
        // Formato de la fecha anterior
        $elementos = explode("-", $fecha_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes;
            $dia = 1;
        }
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada_anterior = implode("-", $array);
        // Formato a la fecha posterior
        $elementos = explode("-", $fecha_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes + 1;
            $dia = 1;
        }
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada_posterior = implode("-", $array);
        // Realizar un consulta de todos los productos registrados en el sistema
        // para verificar los movimientos de esos productos en el kardex y seleccionar el ultimo movimiento de ese mes
        // para obtener el stock y el precio final para el cierre del mes
        $data_product = $this->model_comercial->get_all_productos_v2();
        foreach ($data_product as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $id_pro = $row->id_pro;
            // validacion si existe un registro de este producto en kardex dentro del periodo seleccionado
            $validacion = $this->model_comercial->validar_registros_producto_periodo($fecha_inicial, $fecha_final, $id_detalle_producto);
            if($validacion == 'no_existe_movimiento'){
                // Verificar si existe saldos iniciales del mes anterior para colocarlos en el saldo inicial actual
                $this->db->select('stock_inicial,precio_uni_inicial,id_saldos_iniciales,stock_inicial_sta_clara');
                $this->db->where('fecha_cierre',date($fecha_formateada_anterior));
                $this->db->where('id_pro',$id_pro);
                $query = $this->db->get('saldos_iniciales');
                if(count($query->result()) > 0){
                    // Obtengo los saldos iniciales del mes anterior
                    // osea del mes actual que se esta trabajando
                    foreach($query->result() as $row){
                        $id_saldos_iniciales_anterior = $row->id_saldos_iniciales;
                        $stock_inicial_anterior = $row->stock_inicial;
                        $stock_inicial_sta_clara_anterior = $row->stock_inicial_sta_clara;
                        $precio_uni_inicial_anterior = $row->precio_uni_inicial;
                    }
                    // Actualizar los saldos iniciales del mes que se selecciono
                    $datos = array(
                        'id_pro'=> $id_pro,
                        'fecha_cierre'=> $fecha_formateada_posterior,
                        'precio_uni_inicial'=> $precio_uni_inicial_anterior,
                        'stock_inicial' => $stock_inicial_anterior,
                        'stock_inicial_sta_clara' => $stock_inicial_sta_clara_anterior
                    );
                    $this->model_comercial->insert_saldos_iniciales($datos);
                }else{
                    $datos = array(
                        'id_pro'=> $id_pro,
                        'fecha_cierre'=> $fecha_formateada_posterior,
                        'precio_uni_inicial'=> 0,
                        'stock_inicial' => 0,
                        'stock_inicial_sta_clara' => 0
                    );
                    $this->model_comercial->insert_saldos_iniciales($datos);
                }
            }else{
                // Obtener los ultimos datos nececesarios del kardex para la actualizacion del saldos inicial del producto en el periodo que corresponde
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                $this->db->where('id_kardex_producto',(int)$validacion);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $stock_actual = $row->stock_actual;
                    $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                    $precio_unitario_anterior = $row->precio_unitario_anterior;
                    $descripcion = $row->descripcion;
                    $precio_unitario_actual = $row->precio_unitario_actual;
                    $fecha_registro = $row->fecha_registro;
                }
                // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                if($descripcion == 'SALIDA'){
                    $precio_unitario_anterior_especial = $precio_unitario_anterior;
                }else if($descripcion == 'ENTRADA'  || $descripcion == 'ORDEN INGRESO'){
                    $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                }
                // datos los saldos iniciales del mes que se selecciono
                $datos = array(
                    'id_pro'=> $id_pro,
                    'fecha_cierre'=> $fecha_formateada_posterior,
                    'precio_uni_inicial'=> $precio_unitario_anterior_especial,
                    'stock_inicial' => $stock_actual,
                    'stock_inicial_sta_clara' => 0
                );
                $this->model_comercial->insert_saldos_iniciales($datos);
            }
        }
        echo '1';
    }

    public function registrar_cierre_mes(){
        $fecha_inicial = $this->security->xss_clean($this->input->post('fecha_inicial'));
        $fecha_final = $this->security->xss_clean($this->input->post('fecha_final'));
        // Formateo de la fecha
        $elementos = explode("-", $fecha_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        // Nombre del mes
        if($mes == 1){
            $nombre_mes = "ENERO";
        }else if($mes == 2){
            $nombre_mes = "FEBRERO";
        }else if($mes == 3){
            $nombre_mes = "MARZO";
        }else if($mes == 4){
            $nombre_mes = "ABRIL";
        }else if($mes == 5){
            $nombre_mes = "MAYO";
        }else if($mes == 6){
            $nombre_mes = "JUNIO";
        }else if($mes == 7){
            $nombre_mes = "JULIO";
        }else if($mes == 8){
            $nombre_mes = "AGOSTO";
        }else if($mes == 9){
            $nombre_mes = "SETIEMBRE";
        }else if($mes == 10){
            $nombre_mes = "OCTUBRE";
        }else if($mes == 11){
            $nombre_mes = "NOVIEMBRE";
        }else if($mes == 12){
            $nombre_mes = "DICIEMBRE";
        }
        // Fecha posterior
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes + 1;
            $dia = 1;
        }
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada = implode("-", $array);
        // Procedimiento de validación
        $this->db->select('id_monto_cierre');
        $this->db->where('nombre_mes',$nombre_mes);
        $this->db->where('fecha_auxiliar',$fecha_formateada);
        $query = $this->db->get('monto_cierre');
        if(count($query->result()) > 0){
            echo 'error_validacion';
        }else{
            $result_monto = $this->model_comercial->cierre_almacen_montos_2016($fecha_formateada,$nombre_mes); // guarda el monto de cierre del mes en la tabla monto_cierre
            if(!$result_monto){
                echo 'error_validacion_monto';
            }else{
                echo '1';
            }
        }
    }

    public function eliminartrasladoproducto()
    {
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $id_detalle_traslado = $this->input->get('eliminar');
        $result = $this->model_comercial->eliminarTrasladoProducto($id_detalle_traslado,$almacen);
        if(!$result){
            echo '<b>--> No puede eliminar Registros de un periodo donde se ya realizo el Cierre Mensual de Almacén.</b>';
        }else{
            echo '1';
        }
    }

    public function eliminarregistrosalida()
    {
        $id_registro_salida = $this->input->get('eliminar');
        $this->model_comercial->eliminarRegistroSalida($id_registro_salida);
    }

    public function co_exportar_resumen_producto_excel(){
        $data['producto'] = $this->model_comercial->listarResumenProductos_report_excel();
        $this->load->view('comercial/reportes/report_excel_resumen_producto',$data);
    }

    public function consolidar_stock(){
        $this->model_comercial->consolidar_stock();
    }

    public function al_exportar_inventario(){
        $data = $this->security->xss_clean($this->uri->segment(3));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $data = json_decode($data, true);
        $f_inicial = $data[0];

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(9);

        // Add new sheet
        $objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(0); // Esta línea y en esta posición hace que los formatos vayan a la primera hoja

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        /* propiedades de la celda */
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);

        /* Traer informacion de la BD */
        $saldo_inicial = $this->model_comercial->get_info_saldos_iniciales($f_inicial);
        /* Recorro con todos los nombres seleccionados que tienen una salida/ingreso en el kardex */
        
        $sumatoria = 0;
        $p = 2;

        //Write cells
        $objWorkSheet->setCellValue('A1', 'FECHA DE CIERRE')
                     ->setCellValue('B1', 'NOMBRE DEL PRODUCTO')
                     ->setCellValue('C1', 'STOCK DE CIERRE')
                     ->setCellValue('D1', 'P. UNITARIO DE CIERRE');

        foreach ($saldo_inicial as $reg) {

            /* Formatos */
            $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            /* Centrar contenido */
            $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);

            /* border */
            $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);

            $nombre_producto = $reg->no_producto;
            $stock_inicial = $reg->stock_inicial;
            $precio_uni_inicial = $reg->precio_uni_inicial;
            $fecha_cierre = $reg->fecha_cierre;

            if($almacen == 1){
                $objWorkSheet->setCellValue('A'.$p, $reg->fecha_cierre)
                             ->setCellValue('B'.$p, $reg->no_producto)
                             ->setCellValueExplicit('C'.$p, $reg->stock_inicial_sta_clara)
                             ->setCellValueExplicit('D'.$p, $reg->precio_uni_inicial);
            }else if($almacen == 2){
                $objWorkSheet->setCellValue('A'.$p, $reg->fecha_cierre)
                             ->setCellValue('B'.$p, $reg->no_producto)
                             ->setCellValueExplicit('C'.$p, $reg->stock_inicial)
                             ->setCellValueExplicit('D'.$p, $reg->precio_uni_inicial);
            }

            /* Rename sheet */
            $objWorkSheet->setTitle("Inventario");
            $p++;
        }

        $objPHPExcel->setActiveSheetIndex(0);

        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=inventario.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function al_exportar_report_factura_mensual(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $f_inicial = $data[0];
        $f_final = $data[1];

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        /* Obtener el nombre del mes */
        $elementos = explode("-", $f_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];

        if($mes == 1){
            $nombre_mes = "ENERO";
        }else if($mes == 2){
            $nombre_mes = "FEBRERO";
        }else if($mes == 3){
            $nombre_mes = "MARZO";
        }else if($mes == 4){
            $nombre_mes = "ABRIL";
        }else if($mes == 5){
            $nombre_mes = "MAYO";
        }else if($mes == 6){
            $nombre_mes = "JUNIO";
        }else if($mes == 7){
            $nombre_mes = "JULIO";
        }else if($mes == 8){
            $nombre_mes = "AGOSTO";
        }else if($mes == 9){
            $nombre_mes = "SETIEMBRE";
        }else if($mes == 10){
            $nombre_mes = "OCTUBRE";
        }else if($mes == 11){
            $nombre_mes = "NOVIEMBRE";
        }else if($mes == 12){
            $nombre_mes = "DICIEMBRE";
        }

        /* Here your first sheet */
        $sheet = $objPHPExcel->getActiveSheet();

        /* Style - Bordes */
        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $style_2 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        // Add new sheet
        $objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(0); // Esta línea y en esta posición hace que los formatos vayan a la primera hoja
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K1:M1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($styleArray);
        //$objPHPExcel->getActiveSheet()->getRowDimension('A')->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

        // Write cells
        if($almacen == 1){
            $objWorkSheet->setCellValue('A1', 'SALIDA DE PRODUCTOS VALORIZADOS - STA. CLARA                     FECHA: '.$nombre_mes.' '.$anio);
        }else if($almacen == 2){
            $objWorkSheet->setCellValue('A1', 'SALIDA DE PRODUCTOS VALORIZADOS - STA. ANITA                     FECHA: '.$nombre_mes.' '.$anio);
        }
        $objWorkSheet->setCellValue('K1', ' SALIDAS ');

        $objWorkSheet->setCellValue('A2', '')
                     ->setCellValue('B2', 'MES')
                     ->setCellValue('C2', 'TIPO DOC.')
                     ->setCellValue('D2', 'SERIE')
                     ->setCellValue('E2', 'NUMERO')
                     ->setCellValue('F2', 'PROVEEDOR')
                     ->setCellValue('G2', 'NOMBRE DEL PRODUCTO')
                     ->setCellValue('H2', 'PROCED')
                     ->setCellValue('I2', 'SUM/REP')
                     ->setCellValue('J2', 'MEDIDA')
                     ->setCellValue('K2', 'CANTIDAD')
                     ->setCellValue('L2', 'CU')
                     ->setCellValue('M2', 'CT');

        /* Traer informacion de la BD */
        // Selecciono todos los productos que salieron de almacen dentro de la fecha seleccionada
        $result = $this->model_comercial->get_info_salidas_report($f_inicial, $f_final, $almacen);
        // Recorrido
        $p = 3;
        if(count($result) > 0){
            foreach ($result as $reg) {
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                /* Centrar contenido */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($style);
                /* border */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($borders);

                $id_detalle_producto = $reg->id_detalle_producto;
                $fecha_salida = $reg->fecha;
                $cantidad_salida = $reg->cantidad_salida;
                $no_producto = $reg->no_producto;
                $no_procedencia = $reg->no_procedencia;
                $no_categoria = $reg->no_categoria;
                $nom_uni_med = $reg->nom_uni_med;
                // Identificando las facturas utilizadas para la salida del producto
                $invoice = $this->model_comercial->get_info_facturas_report($id_detalle_producto);
                $sumatoria_unidades_factura = 0;
                $cant_facturas = 0;
                $contador_filas = 1;
                $variable_u = FALSE;
                $variable_p = FALSE;
                if(count($invoice) > 0){
                    foreach ($invoice as $row) {
                        if($sumatoria_unidades_factura < $cantidad_salida){
                            if($cant_facturas == 0){
                                if($row->serie_comprobante == '000'){
                                    $nombre_comprobante_u = 'INVOICE';
                                    $serie_factura_u = '';
                                }else{
                                    $nombre_comprobante_u = $row->no_comprobante;
                                    $serie_factura_u = $row->serie_comprobante;
                                }
                                $num_factura_u = $row->nro_comprobante;
                                $serie_factura_u = $row->serie_comprobante;
                                $razon_social_u = $row->razon_social;
                                $precio_ingreso_u = $row->precio;
                                $unidades_ingreso_u = $row->unidades;
                                // Analisis
                                if(($cantidad_salida - $unidades_ingreso_u) >= 0){
                                    $unidades_utilizadas_u = $unidades_ingreso_u;
                                }else if(($cantidad_salida - $unidades_ingreso_u) < 0){
                                    $unidades_utilizadas_u = $cantidad_salida;
                                }
                                $cant_facturas++;
                                // Sumatoria
                                $sumatoria_unidades_factura = $sumatoria_unidades_factura + $unidades_ingreso_u;
                                $variable_u = TRUE;
                            }else if($cant_facturas == 1){
                                if($row->serie_comprobante == '000'){
                                    $nombre_comprobante_p = 'INVOICE';
                                    $serie_factura_p = '';
                                }else{
                                    $nombre_comprobante_p = $row->no_comprobante;
                                    $serie_factura_p = $row->serie_comprobante;
                                }
                                $num_factura_p = $row->nro_comprobante;
                                $razon_social_p = $row->razon_social;
                                $precio_ingreso_p = $row->precio;
                                $unidades_ingreso_p = $row->unidades;
                                // Analisis
                                $unidades_utilizadas_p = $unidades_ingreso_p - ( $cantidad_salida -  $unidades_utilizadas_u );
                                $cant_facturas++;
                                // Sumatoria
                                $sumatoria_unidades_factura = $sumatoria_unidades_factura + $unidades_ingreso_p;
                                $variable_p = TRUE;
                            }
                        }
                        // Contador de filas
                    }
                }
                if($variable_p == TRUE){
                    $objWorkSheet->setCellValue('A'.$p, $contador_filas)
                                 ->setCellValue('B'.$p, $nombre_mes)
                                 ->setCellValue('C'.$p, $nombre_comprobante_p)
                                 ->setCellValueExplicit('D'.$p, $serie_factura_p,PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValue('E'.$p, $num_factura_p)
                                 ->setCellValue('F'.$p, $razon_social_p)
                                 ->setCellValue('G'.$p, $no_producto)
                                 ->setCellValue('H'.$p, $no_procedencia)
                                 ->setCellValue('I'.$p, $no_categoria)
                                 ->setCellValue('J'.$p, $nom_uni_med)
                                 ->setCellValue('K'.$p, $unidades_utilizadas_p)
                                 ->setCellValue('L'.$p, $precio_ingreso_p)
                                 ->setCellValue('M'.$p, ($unidades_utilizadas_p*$precio_ingreso_p));
                    $p++;
                    $contador_filas++;
                }
                if($variable_u == TRUE){
                    // Estilos
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    /* Centrar contenido */
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($style);
                    /* border */
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($borders);

                    $objWorkSheet->setCellValue('A'.$p, $contador_filas)
                                 ->setCellValue('B'.$p, $nombre_mes)
                                 ->setCellValue('C'.$p, $nombre_comprobante_u)
                                 ->setCellValueExplicit('D'.$p, $serie_factura_u,PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValue('E'.$p, $num_factura_u)
                                 ->setCellValue('F'.$p, $razon_social_u)
                                 ->setCellValue('G'.$p, $no_producto)
                                 ->setCellValue('H'.$p, $no_procedencia)
                                 ->setCellValue('I'.$p, $no_categoria)
                                 ->setCellValue('J'.$p, $nom_uni_med)
                                 ->setCellValue('K'.$p, $unidades_utilizadas_u)
                                 ->setCellValue('L'.$p, $precio_ingreso_u)
                                 ->setCellValue('M'.$p, ($unidades_utilizadas_u*$precio_ingreso_u));
                    $p++;
                    $contador_filas++;
                }
                /* Rename sheet */
                $objWorkSheet->setTitle("reporte_mensual_salidas");
            }
        }
        $objPHPExcel->setActiveSheetIndex(0);
        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_mensual_salidas.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // Esta opcion permite identificar el id de la factura que se utilizo para realizar la salida del producto
    public function al_exportar_report_factura_mensual_opcion_2(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $f_inicial = $data[0];
        $f_final = $data[1];

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        /* Obtener el nombre del mes */
        $elementos = explode("-", $f_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];

        if($mes == 1){
            $nombre_mes = "ENERO";
        }else if($mes == 2){
            $nombre_mes = "FEBRERO";
        }else if($mes == 3){
            $nombre_mes = "MARZO";
        }else if($mes == 4){
            $nombre_mes = "ABRIL";
        }else if($mes == 5){
            $nombre_mes = "MAYO";
        }else if($mes == 6){
            $nombre_mes = "JUNIO";
        }else if($mes == 7){
            $nombre_mes = "JULIO";
        }else if($mes == 8){
            $nombre_mes = "AGOSTO";
        }else if($mes == 9){
            $nombre_mes = "SETIEMBRE";
        }else if($mes == 10){
            $nombre_mes = "OCTUBRE";
        }else if($mes == 11){
            $nombre_mes = "NOVIEMBRE";
        }else if($mes == 12){
            $nombre_mes = "DICIEMBRE";
        }

        /* Here your first sheet */
        $sheet = $objPHPExcel->getActiveSheet();

        /* Style - Bordes */
        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $style_2 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        // Add new sheet
        $objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(0); // Esta línea y en esta posición hace que los formatos vayan a la primera hoja
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K1:M1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($styleArray);
        //$objPHPExcel->getActiveSheet()->getRowDimension('A')->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

        // Write cells
        if($almacen == 1){
            $objWorkSheet->setCellValue('A1', 'SALIDA DE PRODUCTOS VALORIZADOS - STA. CLARA                     FECHA: '.$nombre_mes.' '.$anio);
        }else if($almacen == 2){
            $objWorkSheet->setCellValue('A1', 'SALIDA DE PRODUCTOS VALORIZADOS - STA. ANITA                     FECHA: '.$nombre_mes.' '.$anio);
        }
        $objWorkSheet->setCellValue('K1', ' SALIDAS ');

        $objWorkSheet->setCellValue('A2', '')
                     ->setCellValue('B2', 'MES')
                     ->setCellValue('C2', 'TIPO DOC.')
                     ->setCellValue('D2', 'SERIE')
                     ->setCellValue('E2', 'NUMERO')
                     ->setCellValue('F2', 'PROVEEDOR')
                     ->setCellValue('G2', 'NOMBRE DEL PRODUCTO')
                     ->setCellValue('H2', 'PROCED')
                     ->setCellValue('I2', 'SUM/REP')
                     ->setCellValue('J2', 'MEDIDA')
                     ->setCellValue('K2', 'CANTIDAD')
                     ->setCellValue('L2', 'CU')
                     ->setCellValue('M2', 'CT');

        /* Traer informacion de la BD */
        // Selecciono todos los productos que salieron de almacen dentro de la fecha seleccionada
        $result = $this->model_comercial->get_info_salidas_report($f_inicial, $f_final, $almacen);
        // Recorrido
        $p = 3;
        if(count($result) > 0){
            foreach ($result as $reg) {
                $id_detalle_producto = $reg->id_detalle_producto;
                $fecha_salida = $reg->fecha;
                $cantidad_salida = $reg->cantidad_salida;
                $no_producto = $reg->no_producto;
                $no_procedencia = $reg->no_procedencia;
                $no_categoria = $reg->no_categoria;
                $nom_uni_med = $reg->nom_uni_med;
                $id_salida_producto = $reg->id_salida_producto;

                // Identificando las facturas utilizadas para la salida del producto
                $invoice = $this->model_comercial->get_select_facturas_asociadas($id_salida_producto);
                $contador_filas = 1;
                if(count($invoice) > 0){
                    foreach ($invoice as $row) {
                        $id_ingreso_producto = $row->id_ingreso_producto;
                        $cantidad_utilizada = $row->cantidad_utilizada;
                        // Obtener los datos del detalle de la factura
                        $data_invoice = $this->model_comercial->get_select_data_invoice($id_ingreso_producto);
                        foreach ($data_invoice as $data){
                            $serie = $data->serie_comprobante;
                            $nro_comprobante = $data->nro_comprobante;
                            $razon_social = $data->razon_social;
                            $no_comprobante = $data->no_comprobante;
                            $precio_entrada = $data->precio;
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        /* Centrar contenido */
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($style);
                        /* border */
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($borders);

                        $objWorkSheet->setCellValue('A'.$p, $contador_filas)
                                     ->setCellValue('B'.$p, $nombre_mes)
                                     ->setCellValue('C'.$p, $no_comprobante)
                                     ->setCellValueExplicit('D'.$p, $serie,PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('E'.$p, $nro_comprobante)
                                     ->setCellValue('F'.$p, $razon_social)
                                     ->setCellValue('G'.$p, $no_producto)
                                     ->setCellValue('H'.$p, $no_procedencia)
                                     ->setCellValue('I'.$p, $no_categoria)
                                     ->setCellValue('J'.$p, $nom_uni_med)
                                     ->setCellValue('K'.$p, $cantidad_utilizada)
                                     ->setCellValue('L'.$p, $precio_entrada)
                                     ->setCellValue('M'.$p, ($cantidad_utilizada*$precio_entrada));
                        $p++;
                        $contador_filas++;
                    }
                }
                /* Rename sheet */
                $objWorkSheet->setTitle("reporte_mensual_salidas_oficial");
            }
        }
        $objPHPExcel->setActiveSheetIndex(0);
        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_mensual_salidas_oficial.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function al_exportar_inventario_almacen(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        /* Here your first sheet */
        $sheet = $objPHPExcel->getActiveSheet();

         /* Style - Bordes */
        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $style_2 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        // Add new sheet
        $objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(0); // Esta línea y en esta posición hace que los formatos vayan a la primera hoja
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(13);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
        //$objPHPExcel->getActiveSheet()->getRowDimension('A')->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(55);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        //Write cells
        if($almacen == 1){
            $objWorkSheet->setCellValue('A1', 'INVENTARIO FISICO DE PRODUCTOS - STA. CLARA                     FECHA: '.date('d-m-y'));
        }else if($almacen == 2){
            $objWorkSheet->setCellValue('A1', 'INVENTARIO FISICO DE PRODUCTOS - REPUESTOS                     FECHA: '.date('d-m-y'));
        }
        $objWorkSheet->setCellValue('A2', 'ID PRODUCTO')
                     ->setCellValue('B2', 'NOMBRE O DESCRIPCION')
                     ->setCellValue('C2', 'ESTADO')
                     ->setCellValue('D2', 'CATEGORIA')
                     ->setCellValue('E2', 'TIPO DE PRODUCTO')
                     ->setCellValue('F2', 'PROCEDENCIA')
                     ->setCellValue('G2', 'U. MEDIDA')
                     ->setCellValue('H2', 'STOCK')
                     ->setCellValue('I2', 'P. UNITARIO')
                     ->setCellValue('J2', 'VALOR ECONOMICO S/.');
        /* Traer informacion de la BD */
        $result = $this->model_comercial->get_info_inventario_actual();
        /* Recorro con todos los nombres seleccionados que tienen una salida/ingreso en el kardex */
        $i = 0;
        $sumatoria_parciales = 0;
        $p = 3;
        foreach ($result as $reg) {
            $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            /* Centrar contenido */
            $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style);
            /* border */
            $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
            if($reg->estado == 't'){
                $estado = 'ACTIVO';
            }else if($reg->estado == 'f'){
                $estado = 'INACTIVO';
            }
            if($almacen == 1){
                $objWorkSheet->setCellValue('A'.$p, $reg->id_producto)
                             ->setCellValue('B'.$p, $reg->no_producto)
                             ->setCellValue('C'.$p, $estado)
                             ->setCellValue('D'.$p, $reg->no_categoria)
                             ->setCellValue('E'.$p, $reg->no_tipo_producto)
                             ->setCellValue('F'.$p, $reg->no_procedencia)
                             ->setCellValue('G'.$p, $reg->nom_uni_med)
                             ->setCellValue('H'.$p, $reg->stock_sta_clara)
                             ->setCellValue('I'.$p, "");
            }else if($almacen == 2){
                $objWorkSheet->setCellValue('A'.$p, $reg->id_pro)
                         ->setCellValue('B'.$p, $reg->no_producto)
                         ->setCellValue('C'.$p, $estado)
                         ->setCellValue('D'.$p, $reg->no_categoria)
                         ->setCellValue('E'.$p, $reg->no_tipo_producto)
                         ->setCellValue('F'.$p, $reg->no_procedencia)
                         ->setCellValue('G'.$p, $reg->nom_uni_med)
                         ->setCellValue('H'.$p, $reg->stock)
                         ->setCellValue('I'.$p, $reg->precio_unitario)
                         ->setCellValue('J'.$p, $reg->stock * $reg->precio_unitario);
                $sumatoria_parciales = $sumatoria_parciales + ($reg->stock * $reg->precio_unitario);
            }
            /* Rename sheet */
            $objWorkSheet->setTitle("Inventario_Almacen");
            $p++;
        }

        $y = $p;
        $objWorkSheet->setCellValue('A'.$y, "")
                     ->setCellValue('B'.$y, "")
                     ->setCellValue('C'.$y, "")
                     ->setCellValue('D'.$y, "")
                     ->setCellValue('E'.$y, "")
                     ->setCellValue('F'.$y, "")
                     ->setCellValue('G'.$y, "")
                     ->setCellValue('H'.$y, "")
                     ->setCellValue('I'.$y, "TOTAL EN S/.")
                     ->setCellValue('J'.$y, $sumatoria_parciales);

        $objPHPExcel->getActiveSheet()->getStyle('I'.$y)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('J'.$y)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        /* Centrar contenido */
        $objPHPExcel->getActiveSheet()->getStyle('I'.$y)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('I'.$y)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('J'.$y)->applyFromArray($style);
        /* border */
        $objPHPExcel->getActiveSheet()->getStyle('I'.$y)->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('J'.$y)->applyFromArray($borders);

        $objPHPExcel->setActiveSheetIndex(0);
        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=inventario_almacen.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function al_exportar_kardex_producto_excel(){
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $id_detalle_producto = $data[0];
        $f_inicial = $data[1];
        $f_final = $data[2];

        (array)$arr = str_split($f_final, 4);
        $anio = $arr[0];

        /* Formato para la fecha inicial */
        $elementos = explode("-", $f_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        $array = array($dia, $mes, $anio);
        $f_inicial = implode("-", $array);
        /* Fin */

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        /* Here your first sheet */
        $sheet = $objPHPExcel->getActiveSheet();

        /* Traer informacion de la BD */
        $nombre_productos_salidas = $this->model_comercial->traer_nombres_kardex_producto($id_detalle_producto);
        /* Recorro con todos los nombres seleccionados que tienen una salida/ingreso en el kardex */
        /*  */
        $i = 0;
        foreach ($nombre_productos_salidas as $reg) {
            $nombre_producto = $reg->no_producto;
            $id_unidad_medida = $reg->id_unidad_medida;
            $id_detalle_producto = $reg->id_detalle_producto;
            $id_pro = $reg->id_pro;

            // Add new sheet
            $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating
            $objPHPExcel->setActiveSheetIndex($i)->mergeCells('A1:D1');
            $objPHPExcel->setActiveSheetIndex($i)->mergeCells('A12:D12');
            $objPHPExcel->setActiveSheetIndex($i)->mergeCells('E12:G12');
            $objPHPExcel->setActiveSheetIndex($i)->mergeCells('H12:J12');
            $objPHPExcel->setActiveSheetIndex($i)->mergeCells('K12:M12');

            /* Style - Bordes */
            $borders = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    )
                ),
            );

            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

            $style_2 = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                )
            );

            $styleArray = array(
                'font' => array(
                    'bold' => true
                )
            );

            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('E12:G12')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('H12:J12')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('K12:M12')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('A13:M13')->applyFromArray($borders);

            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('E12:G12')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('H12:J12')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('K12:M12')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('A13:M13')->applyFromArray($style);

            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('E12:G12')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('H12:J12')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('K12:M12')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A13:M13')->applyFromArray($styleArray);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

            $objPHPExcel->getActiveSheet()->getStyle('A1:D10')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('F1:F10')->applyFromArray($styleArray);
            //Write cells
            $objWorkSheet->setCellValue('A1', 'INVENTARIO PERMANENTE VALORIZADO')
                         ->setCellValue('A2', 'PERIODO: '.$anio)
                         ->setCellValue('A3', 'RUC: 20101717098')
                         ->setCellValue('A4', 'TEJIDOS JORGITO SRL')
                         ->setCellValue('A5', 'CALLE LOS TELARES No 103-105 URB. VULCANO-ATE')
                         ->setCellValue('A6', 'CÓDIGO: PRD'.$id_pro)
                         ->setCellValue('A7', 'TIPO: 03')
                         ->setCellValue('A8', 'DESCRIPCIÓN: '.$nombre_producto)
                         ->setCellValue('A9', 'UNIDAD DE MEDIDA: '.$id_unidad_medida)
                         ->setCellValue('A10', 'MÉTODO DE EVALUACIÓN: COSTO PROMEDIO');
            $objWorkSheet->setCellValue('F1', 'FT: FACTURA')
                         ->setCellValue('F2', 'GR: GUÍA DE REMISIÓN')
                         ->setCellValue('F3', 'BV: BOLETA DE VENTA')
                         ->setCellValue('F4', 'NC: NOTA DE CRÉDITO')
                         ->setCellValue('F5', 'ND: NOTA DE DÉBITO')
                         ->setCellValue('F6', 'OS: ORDEN DE SALIDA')
                         ->setCellValue('F7', 'OI: ORDEN DE INGRESO')
                         ->setCellValue('F8', 'CU: COSTO UNITARIO (NUEVOS SOLES)')
                         ->setCellValue('F9', 'CT: COSTO TOTAL (NUEVOS SOLES)')
                         ->setCellValue('F10', 'SI: SALDO INICIAL');
            $objWorkSheet->setCellValue('A12', 'DOCUMENTO DE MOVIMIENTO')
                         ->setCellValue('E12', 'ENTRADAS')
                         ->setCellValue('H12', 'SALIDAS')
                         ->setCellValue('K12', 'SALDO FINAL');
            $objWorkSheet->setCellValue('A13', 'FECHA')
                         ->setCellValue('B13', 'TIPO')
                         ->setCellValue('C13', 'SERIE')
                         ->setCellValue('D13', 'NÚMERO')
                         ->setCellValue('E13', 'CANTIDAD')
                         ->setCellValue('F13', 'CU')
                         ->setCellValue('G13', 'CT')
                         ->setCellValue('H13', 'CANTIDAD')
                         ->setCellValue('I13', 'CU')
                         ->setCellValue('J13', 'CT')
                         ->setCellValue('K13', 'CANTIDAD')
                         ->setCellValue('L13', 'CU')
                         ->setCellValue('M13', 'CT');

            /* Formato para la fila 14 */
            $objPHPExcel->getActiveSheet()->getStyle('A14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('C14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('D14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('A14')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('C14')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('D14')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('E14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('F14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('G14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('H14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('I14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('J14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('K14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('L14')->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('M14')->applyFromArray($borders);

            /* Traer saldos iniciales de la BD */
            $saldos_iniciales = $this->model_comercial->traer_saldos_iniciales($f_inicial,$id_pro);

            /* varianles para las sumatorias */
            $sumatoria_cantidad_entradas = 0;
            $sumatoria_parciales_entradas = 0;

            $sumatoria_cantidad_salidas = 0;
            $sumatoria_parciales_salidas = 0;

            $sumatoria_cantidad_saldos = 0;
            $sumatoria_parciales_saldos = 0;

            $objPHPExcel->getActiveSheet()->getStyle('E14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('F14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('G14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('H14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('I14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('J14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('K14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('L14')->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('M14')->applyFromArray($style_2);

            $objPHPExcel->getActiveSheet()->getStyle('E14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('F14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('G14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('H14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('I14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('J14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('K14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('L14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('M14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            if( count($saldos_iniciales) > 0 ){
                foreach ($saldos_iniciales as $result) {
                    $total_saldos_iniciales = $result->stock_inicial + $result->stock_inicial_sta_clara;
                    /* Formato de Fecha */
                    $elementos = explode("-", $result->fecha_cierre);
                    $anio = $elementos[0];
                    $mes = $elementos[1];
                    $dia = $elementos[2];
                    $array = array($dia, $mes, $anio);
                    $fecha_formateada = implode("-", $array);
                    /* Fin */
                    $objWorkSheet->setCellValue('A14', $fecha_formateada)
                                 ->setCellValue('B14', " ")
                                 ->setCellValue('C14', "SI")
                                 ->setCellValue('D14', " ")
                                 ->setCellValue('E14', $total_saldos_iniciales)
                                 ->setCellValue('F14', $result->precio_uni_inicial)
                                 ->setCellValue('G14', $total_saldos_iniciales*$result->precio_uni_inicial)
                                 ->setCellValueExplicit('H14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValue('I14', $result->precio_uni_inicial)
                                 ->setCellValueExplicit('J14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValue('K14', $total_saldos_iniciales)
                                 ->setCellValue('L14', $result->precio_uni_inicial)
                                 ->setCellValue('M14', $total_saldos_iniciales*$result->precio_uni_inicial);
                    /* ENTRADAS */
                    $sumatoria_cantidad_entradas = $sumatoria_cantidad_entradas + $total_saldos_iniciales;
                    $sumatoria_parciales_entradas = $sumatoria_parciales_entradas + ($total_saldos_iniciales * $result->precio_uni_inicial);
                    /* SALDOS */
                    $sumatoria_cantidad_saldos = $sumatoria_cantidad_saldos + $total_saldos_iniciales;
                    $sumatoria_parciales_saldos = $sumatoria_parciales_saldos + ($total_saldos_iniciales * $result->precio_uni_inicial);
                }
            }else{
                $objWorkSheet->setCellValueExplicit('A14', $f_inicial)
                             ->setCellValueExplicit('B14', " ")
                             ->setCellValueExplicit('C14', "SI")
                             ->setCellValueExplicit('D14', " ")
                             ->setCellValueExplicit('E14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('F14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('G14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('H14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('I14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('J14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('K14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('L14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                             ->setCellValueExplicit('M14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING);
            }

            /* Recorrido del detalle del kardex general por producto */
            $detalle_movimientos_kardex = $this->model_comercial->traer_movimientos_kardex($id_detalle_producto,$f_inicial,$f_final);
            $existe = count($detalle_movimientos_kardex);
            $y = 0;
            if($existe > 0){
                foreach ($detalle_movimientos_kardex as $data) {
                    $p = 15;
                    $p = $p + $y;
                    /* Centrar contenido */
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);

                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($style_2);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($style_2);

                    $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($borders);
                    /* formato de variables */
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                    /* Traer ID de salida del producto */
                    if($data->descripcion == "SALIDA"){
                        $fecha_salida = $data->fecha_registro;
                        $detalle_producto = $data->id_detalle_producto;
                        $cantidad_salida = $data->cantidad_salida;
                    }

                    /* Formato de Fecha */
                    $elementos = explode("-", $data->fecha_registro);
                    $anio = $elementos[0];
                    $mes = $elementos[1];
                    $dia = $elementos[2];
                    $array = array($dia, $mes, $anio);
                    $fecha_formateada_2 = implode("-", $array);

                    /* fin de formato */
                    if($data->descripcion == "ENTRADA"){
                        $objWorkSheet->setCellValue('A'.$p, $fecha_formateada_2)
                                     ->setCellValue('B'.$p, "FT")
                                     ->setCellValueExplicit('C'.$p, str_pad($data->serie_comprobante, 3, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValueExplicit('D'.$p, str_pad($data->num_comprobante, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('E'.$p, $data->cantidad_ingreso)
                                     ->setCellValue('F'.$p, $data->precio_unitario_actual)
                                     ->setCellValue('G'.$p, $data->cantidad_ingreso * $data->precio_unitario_actual)
                                     ->setCellValueExplicit('H'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('I'.$p, $data->precio_unitario_actual)
                                     ->setCellValueExplicit('J'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('K'.$p, $data->stock_actual)
                                     ->setCellValue('L'.$p, $data->precio_unitario_actual_promedio)
                                     ->setCellValue('M'.$p, $data->stock_actual*$data->precio_unitario_actual_promedio);
                    }else if($data->descripcion == "SALIDA"){
                        $objWorkSheet->setCellValue('A'.$p, $fecha_formateada_2)
                                     ->setCellValue('B'.$p, "OS")
                                     ->setCellValue('C'.$p, "NIG")
                                     ->setCellValueExplicit('D'.$p, str_pad($data->id_kardex_producto, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValueExplicit('E'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValueExplicit('F'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValueExplicit('G'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('H'.$p, $data->cantidad_salida)
                                     ->setCellValue('I'.$p, $data->precio_unitario_anterior)
                                     ->setCellValue('J'.$p, $data->cantidad_salida*$data->precio_unitario_anterior)
                                     ->setCellValue('K'.$p, $data->stock_actual)
                                     ->setCellValue('L'.$p, $data->precio_unitario_actual)
                                     ->setCellValue('M'.$p, $data->stock_actual*$data->precio_unitario_actual);
                    }else if($data->descripcion == "IMPORTACION"){
                        $objWorkSheet->setCellValue('A'.$p, $fecha_formateada_2)
                                     ->setCellValue('B'.$p, "IMPORTACION")
                                     ->setCellValueExplicit('C'.$p, str_pad($data->serie_comprobante, 3, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValueExplicit('D'.$p, str_pad($data->num_comprobante, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('E'.$p, $data->cantidad_ingreso)
                                     ->setCellValue('F'.$p, $data->precio_unitario_actual)
                                     ->setCellValue('G'.$p, $data->cantidad_ingreso * $data->precio_unitario_actual)
                                     ->setCellValueExplicit('H'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('I'.$p, $data->precio_unitario_actual)
                                     ->setCellValueExplicit('J'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('K'.$p, $data->stock_actual)
                                     ->setCellValue('L'.$p, $data->precio_unitario_actual_promedio)
                                     ->setCellValue('M'.$p, $data->stock_actual*$data->precio_unitario_actual_promedio);
                    }else if($data->descripcion == "ORDEN INGRESO"){
                        $objWorkSheet->setCellValue('A'.$p, $fecha_formateada_2)
                                     ->setCellValue('B'.$p, "OI")
                                     ->setCellValueExplicit('C'.$p, str_pad($data->serie_comprobante, 3, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValueExplicit('D'.$p, str_pad($data->num_comprobante, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('E'.$p, $data->cantidad_ingreso)
                                     ->setCellValue('F'.$p, $data->precio_unitario_actual)
                                     ->setCellValue('G'.$p, $data->cantidad_ingreso * $data->precio_unitario_actual)
                                     ->setCellValueExplicit('H'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('I'.$p, $data->precio_unitario_actual)
                                     ->setCellValueExplicit('J'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('K'.$p, $data->stock_actual)
                                     ->setCellValue('L'.$p, $data->precio_unitario_actual_promedio)
                                     ->setCellValue('M'.$p, $data->stock_actual*$data->precio_unitario_actual_promedio);
                    }           
                    /* ENTRADAS */
                    $sumatoria_cantidad_entradas = $sumatoria_cantidad_entradas + $data->cantidad_ingreso;
                    $sumatoria_parciales_entradas = $sumatoria_parciales_entradas + ($data->cantidad_ingreso * $data->precio_unitario_actual);
                    /* SALIDAS */
                    $sumatoria_cantidad_salidas = $sumatoria_cantidad_salidas + $data->cantidad_salida;
                    $sumatoria_parciales_salidas = $sumatoria_parciales_salidas + ($data->cantidad_salida * $data->precio_unitario_actual);
                    /* SALDOS */
                    $sumatoria_cantidad_saldos = $sumatoria_cantidad_saldos + $data->stock_actual;
                    if($data->descripcion == "SALIDA"){
                        $sumatoria_parciales_saldos = $sumatoria_parciales_saldos + ($data->precio_unitario_actual * $data->stock_actual);
                    }else if($data->descripcion == "ENTRADA"){
                        $sumatoria_parciales_saldos = $sumatoria_parciales_saldos + ($data->precio_unitario_actual_promedio * $data->stock_actual);
                    }
                    $y = $y + 1;
                }
            }

            $p = 15 + $y;
            $objWorkSheet->setCellValue('A'.$p, "")
                         ->setCellValue('B'.$p, "")
                         ->setCellValue('C'.$p, "")
                         ->setCellValue('D'.$p, "TOTALES")
                         ->setCellValue('E'.$p, $sumatoria_cantidad_entradas)
                         ->setCellValue('F'.$p, "")
                         ->setCellValue('G'.$p, $sumatoria_parciales_entradas)
                         ->setCellValue('H'.$p, $sumatoria_cantidad_salidas)
                         ->setCellValue('I'.$p, "")
                         ->setCellValue('J'.$p, $sumatoria_parciales_salidas)
                         ->setCellValue('K'.$p, $sumatoria_cantidad_saldos)
                         ->setCellValue('L'.$p, "")
                         ->setCellValue('M'.$p, $sumatoria_parciales_saldos);

            /* Centrar contenido */
            $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($borders);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($borders);

            /* Dar formato numericos a las celdas */
            $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            /* Alinear el valor de la celda a la derecha */
            $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($style_2);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($style_2);

            /* Rename sheet */
            $objWorkSheet->setTitle("$nombre_producto");
            $i++;
        }

        $objPHPExcel->setActiveSheetIndex(0);

        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$nombre_producto.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function al_exportar_report_ingresos(){
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $f_inicial = $data[0];
        $f_final = $data[1];

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

        // Add new sheet
        $objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(0); // Esta línea y en esta posición hace que los formatos vayan a la primera hoja

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        /* propiedades de la celda */

        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
        

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

        //Write cells
        $objWorkSheet->setCellValue('A1', 'ITEM')
                     ->setCellValue('B1', 'COMPROBANTE')
                     ->setCellValue('C1', 'SERIE - NÚMERO')
                     ->setCellValue('D1', 'PROVEEDOR')
                     ->setCellValue('E1', 'FECHA DE REGISTRO ')
                     ->setCellValue('F1', 'MONEDA')
                     ->setCellValue('G1', 'MONTO TOTAL')
                     ->setCellValue('H1', 'TOTAL EN SOLES')
                     ->setCellValue('I1', 'PROCEDENCIA');

        /* Traer informacion de la BD */
        $movimientos_entrada = $this->model_comercial->traer_movimientos_entrada_facturas($f_inicial,$f_final);
        $existe = count($movimientos_entrada);
        $sumatoria_totales = 0;
        $p = 2;
        $i = 1;
        $suma_dolares = 0;
        $suma_euro = 0;
        $suma_franco = 0;
        $suma_soles = 0;
        $suma_total_soles = 0;
        if($existe > 0){
            foreach ($movimientos_entrada as $data) {
                /* Formatos */
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                /* Centrar contenido */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style);

                /* border */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);

                /* Obtener el tipo de cambio de la fecha de registro de la factura */
                $this->db->select('dolar_venta,euro_venta');
                $this->db->where('fecha_actual',$data->fecha);
                $query = $this->db->get('tipo_cambio');
                foreach($query->result() as $row){
                    $dolar_venta_fecha = $row->dolar_venta;
                    $euro_venta_fecha = $row->euro_venta;
                }
                /* Obtener el monto total en soles */
                if($data->id_agente == 2){
                    if($data->no_moneda == 'DOLARES'){
                        $convert_soles = $data->total * $dolar_venta_fecha;
                        $suma_dolares = $suma_dolares + $data->total;
                        $suma_total_soles = $suma_total_soles + $convert_soles;
                    }else if($data->no_moneda == 'EURO'){
                        $convert_soles = $data->total * $euro_venta_fecha;
                        $suma_euro = $suma_euro + $data->total;
                        $suma_total_soles = $suma_total_soles + $convert_soles;
                    }else{
                        $convert_soles = $data->total;
                        $suma_soles = $suma_soles + $data->total;
                        $suma_total_soles = $suma_total_soles + $data->total;
                    }
                }else{
                    $convert_soles = $data->total;
                    $suma_soles = $suma_soles + $data->total;
                    $suma_total_soles = $suma_total_soles + $data->total;
                }

                if($data->id_agente == ""){
                    $objWorkSheet->setCellValue('A'.$p, $i)
                                 ->setCellValue('B'.$p, $data->no_comprobante)
                                 ->setCellValue('C'.$p, str_pad($data->serie_comprobante, 3, 0, STR_PAD_LEFT)." - ".str_pad($data->nro_comprobante, 8, 0, STR_PAD_LEFT))
                                 ->setCellValue('D'.$p, $data->razon_social)
                                 ->setCellValue('E'.$p, $data->fecha)
                                 ->setCellValue('F'.$p, $data->simbolo_mon." ".$data->no_moneda)
                                 ->setCellValue('G'.$p, $data->total)
                                 ->setCellValue('H'.$p, $convert_soles)
                                 ->setCellValue('I'.$p, 'NACIONAL');
                }else{
                    $objWorkSheet->setCellValue('A'.$p, $i)
                                 ->setCellValue('B'.$p, $data->no_comprobante)
                                 ->setCellValue('C'.$p, str_pad($data->serie_comprobante, 3, 0, STR_PAD_LEFT)." - ".str_pad($data->nro_comprobante, 8, 0, STR_PAD_LEFT))
                                 ->setCellValue('D'.$p, $data->razon_social)
                                 ->setCellValue('E'.$p, $data->fecha)
                                 ->setCellValue('F'.$p, $data->simbolo_mon." ".$data->no_moneda)
                                 ->setCellValue('G'.$p, $data->total)
                                 ->setCellValue('H'.$p, $convert_soles)
                                 ->setCellValue('I'.$p, 'IMPORTADA');
                }

                $p = $p + 1;
                $i = $i + 1;
            }
        }
        /* ---------------------------------------------------------------------- */
        /* Formatos */
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        /* Centrar contenido */
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($styleArray);
        /* border */
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);

        $objWorkSheet->setCellValue('A'.$p, "")
                     ->setCellValue('B'.$p, "")
                     ->setCellValue('C'.$p, "")
                     ->setCellValue('D'.$p, "")
                     ->setCellValue('E'.$p, "")
                     ->setCellValue('F'.$p, "T. EN SOLES")
                     ->setCellValue('G'.$p, $suma_soles); // colocar lo siguiente me da un error: ->setCellValue('G'.$p, "S/. ".$suma_soles); al insertar el icono de soles, convierte todo los valores en texto y no lo puedo pasar a numerico
        $p = $p + 1;
        /* ---------------------------------------------------------------------- */
        /* formato */
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($styleArray);
        /* border */
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
        $objWorkSheet->setCellValue('A'.$p, "")
                     ->setCellValue('B'.$p, "")
                     ->setCellValue('C'.$p, "")
                     ->setCellValue('D'.$p, "")
                     ->setCellValue('E'.$p, "")
                     ->setCellValue('F'.$p, "T. EN DOLARES")
                     ->setCellValue('G'.$p, $suma_dolares);
        $p = $p + 1;
        /* ---------------------------------------------------------------------- */
        /* formato */
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($styleArray);
        /* border */
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
        $objWorkSheet->setCellValue('A'.$p, "")
                     ->setCellValue('B'.$p, "")
                     ->setCellValue('C'.$p, "")
                     ->setCellValue('D'.$p, "")
                     ->setCellValue('E'.$p, "")
                     ->setCellValue('F'.$p, "T. EN EUROS")
                     ->setCellValue('G'.$p, $suma_euro);
        $p = $p + 1;
        /* ---------------------------------------------------------------------- */
        /* formato */
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($styleArray);
        /* border */
        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);

        $objWorkSheet->setCellValue('A'.$p, "")
                     ->setCellValue('B'.$p, "")
                     ->setCellValue('C'.$p, "")
                     ->setCellValue('D'.$p, "")
                     ->setCellValue('E'.$p, "")
                     ->setCellValue('F'.$p, "SUMA TOTAL SOLES")
                     ->setCellValue('G'.$p, $suma_total_soles);
        /* ---------------------------------------------------------------------- */
        /* Rename sheet */
        $objWorkSheet->setTitle("Reporte de Facturas");
        //->setCellValueExplicit('G'.$p, $data->total,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0);

        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Reporte_De_Facturas.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function al_exportar_cierre_excel(){
        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

        // Add new sheet
        $objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(0); // Esta línea y en esta posición hace que los formatos vayan a la primera hoja

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        /* propiedades de la celda */
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
        

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);

        //Write cells
        $objWorkSheet->setCellValue('A1', 'ITEM')
                     ->setCellValue('B1', 'FECHA DE CIERRE')
                     ->setCellValue('C1', 'MES')
                     ->setCellValue('D1', 'MONTO DE CIERRE STA. ANITA')
                     ->setCellValue('E1', 'MONTO DE CIERRE STA. CLARA')
                     ->setCellValue('F1', 'MONTO DE CIERRE GENERAL');

        /* Traer informacion de la BD */
        $movimientos_cierre = $this->model_comercial->get_cierre_almacen();
        $existe = count($movimientos_cierre);
        $sumatoria_totales = 0;
        $p = 2; // contador de filas del excel
        $i = 1; // Este calor es el contador de cuantos registros existen
        if($existe > 0){
            foreach ($movimientos_cierre as $data) {
                // $no_producto = htmlentities($data->no_producto, ENT_QUOTES,'UTF-8');
                /* Formatos */
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                /* Centrar contenido */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);

                /* border */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);

                /* Formateando la Fecha */
                $elementos = explode("-", $data->fecha_cierre);
                $anio = $elementos[0];
                $mes = $elementos[1];
                $dia = $elementos[2];

                if($mes == "12"){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($styleArray);
                    $concat =  "A{$p}:F{$p}";
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($concat);
                    $objWorkSheet->setCellValue('A'.$p, $anio)
                                 ->setCellValue('B'.$p, "")
                                 ->setCellValue('C'.$p, "")
                                 ->setCellValue('D'.$p, "")
                                 ->setCellValue('E'.$p, "")
                                 ->setCellValue('F'.$p, $anio);
                }
                $p = $p + 1;
                $i = $i + 1;

                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                /* Centrar contenido */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);

                /* border */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);

                $objWorkSheet->setCellValue('A'.$p, str_pad($i, 4, 0, STR_PAD_LEFT))
                             ->setCellValue('B'.$p, $data->fecha_cierre)
                             ->setCellValue('C'.$p, $data->nombre_mes)
                             ->setCellValue('D'.$p, $data->monto_cierre_sta_anita)
                             ->setCellValue('E'.$p, $data->monto_cierre_sta_clara)
                             ->setCellValue('F'.$p, $data->monto_cierre);
                if($mes == "01"){
                    $p = $p + 1;
                }
            }
        }
        /* Rename sheet */
        $objWorkSheet->setTitle("Cierre_Almacen");
        //->setCellValueExplicit('G'.$p, $data->total,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0);
        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Cierre_De_Almacen.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function al_exportar_report_salidas(){
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $f_inicial = $data[0];
        $f_final = $data[1];

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

        // Add new sheet
        $objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(0); // Esta línea y en esta posición hace que los formatos vayan a la primera hoja

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        /* propiedades de la celda */
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);
        

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(65);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

        //Write cells
        $objWorkSheet->setCellValue('A1', 'ITEM')
                     ->setCellValue('B1', 'FECHA DE REGISTRO')
                     ->setCellValue('C1', 'SERIE')
                     ->setCellValue('D1', 'NÚMERO')
                     ->setCellValue('E1', 'CATEGORÍA')
                     ->setCellValue('F1', 'ÁREA')
                     ->setCellValue('G1', 'CÓDIGO PRODUCTO')
                     ->setCellValue('H1', 'PRODUCTO')
                     ->setCellValue('I1', 'UNID. MEDIDA')
                     ->setCellValue('J1', 'CANTIDAD SALIDA')
                     ->setCellValue('K1', 'VALORIZADO S/.');

        /* Traer informacion de la BD */
        $movimientos_salida = $this->model_comercial->traer_movimientos_salidas_facturas($f_inicial,$f_final);
        $existe = count($movimientos_salida);
        $sumatoria_totales = 0;
        $p = 2; // contador de filas del excel
        $i = 1; // Este calor es el contador de cuantos registros existen
        if($existe > 0){
            foreach ($movimientos_salida as $data) {
                /* $no_producto = htmlentities($data->no_producto, ENT_QUOTES,'UTF-8'); */
                /* Formatos */
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                /* Centrar contenido */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style);

                /* border */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);

                $objWorkSheet->setCellValue('A'.$p, $i)
                             ->setCellValue('B'.$p, $data->fecha)
                             ->setCellValue('C'.$p, "NITS")
                             ->setCellValue('D'.$p, str_pad($data->id_salida_producto, 8, 0, STR_PAD_LEFT))
                             ->setCellValue('E'.$p, $data->no_categoria)
                             ->setCellValue('F'.$p, $data->no_area)
                             ->setCellValue('G'.$p, "PRD".$data->id_pro)
                             ->setCellValue('H'.$p, $data->no_producto)
                             ->setCellValue('I'.$p, $data->nom_uni_med)
                             ->setCellValue('J'.$p, $data->cantidad_salida)
                             ->setCellValue('K'.$p, $data->cantidad_salida*$data->p_u_salida);
                $p = $p + 1;
                $i = $i + 1;
                $sumatoria_totales = $sumatoria_totales + ($data->cantidad_salida*$data->p_u_salida);
            }
        }
        /* ---------------------------------------------------------------------- */
        /* Formatos */
        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        /* Centrar contenido */
        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($styleArray);
        /* border */
        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);

        $objWorkSheet->setCellValue('A'.$p, "")
                     ->setCellValue('B'.$p, "")
                     ->setCellValue('C'.$p, "")
                     ->setCellValue('D'.$p, "")
                     ->setCellValue('E'.$p, "")
                     ->setCellValue('F'.$p, "")
                     ->setCellValue('G'.$p, "")
                     ->setCellValue('H'.$p, "")
                     ->setCellValue('I'.$p, "")
                     ->setCellValue('J'.$p, "TOTALES S/.")
                     ->setCellValue('K'.$p, $sumatoria_totales); // colocar lo siguiente me da un error: ->setCellValue('G'.$p, "S/. ".$suma_soles); al insertar el icono de soles, convierte todo los valores en texto y no lo puedo pasar a numerico
        /* ---------------------------------------------------------------------- */
        /* Rename sheet */
        $objWorkSheet->setTitle("Reporte de Salidas");
        //->setCellValueExplicit('G'.$p, $data->total,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->setActiveSheetIndex(0);

        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Reporte_De_Salidas.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        echo 'ok';
    }
    
    public function al_exportar_kardex_producto_excel_general(){
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $f_inicial = $data[0];
        $f_final = $data[1];
        $indice = $data[2];

        (array)$arr = str_split($f_final, 4);
        $anio = $arr[0];

        /* Formato para la fecha inicial */
        $elementos = explode("-", $f_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        $array = array($dia, $mes, $anio);
        $f_inicial = implode("-", $array);
        /* Fin */

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        /* Here your first sheet */
        $sheet = $objPHPExcel->getActiveSheet();

        /* Style - Bordes */
        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $style_2 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        /* Traer informacion de la BD */
        $nombre_productos_salidas = $this->model_comercial->traer_nombres_kardex($indice);
        /* Recorro con todos los nombres seleccionados que tienen una salida/ingreso en el kardex */
        /* Tambien debo considerar los que no han tenido registros en la tabla kardex pero si debe aparece SI o vacio */
        $i = 0;
        foreach ($nombre_productos_salidas as $reg) {

            $nombre_producto = $reg->no_producto;
            $id_producto = $reg->id_producto;
            $id_unidad_medida = $reg->id_unidad_medida;
            $id_detalle_producto = $reg->id_detalle_producto;
            $id_pro = $reg->id_pro;

            /* Traer sólo productos que tengan registros en el periodo seleccionado */
            $produtos_con_kardex = $this->model_comercial->traer_producto_con_kardex($id_detalle_producto,$f_inicial,$f_final);
            if( count($produtos_con_kardex) > 0 ){
                // Add new sheet
                $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating
                $objPHPExcel->setActiveSheetIndex($i)->mergeCells('A1:D1');
                $objPHPExcel->setActiveSheetIndex($i)->mergeCells('A12:D12');
                $objPHPExcel->setActiveSheetIndex($i)->mergeCells('E12:G12');
                $objPHPExcel->setActiveSheetIndex($i)->mergeCells('H12:J12');
                $objPHPExcel->setActiveSheetIndex($i)->mergeCells('K12:M12');

                $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('E12:G12')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('H12:J12')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('K12:M12')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('A13:M13')->applyFromArray($borders);

                $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E12:G12')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('H12:J12')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('K12:M12')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('A13:M13')->applyFromArray($style);

                $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('E12:G12')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('H12:J12')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('K12:M12')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A13:M13')->applyFromArray($styleArray);

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

                //Write cells
                $objWorkSheet->setCellValue('A1', 'INVENTARIO PERMANENTE VALORIZADO')
                             ->setCellValue('A2', 'PERIODO: '.$anio)
                             ->setCellValue('A3', 'RUC: 20101717098')
                             ->setCellValue('A4', 'TEJIDOS JORGITO SRL')
                             ->setCellValue('A5', 'CALLE LOS TELARES No 103-105 URB. VULCANO-ATE')
                             ->setCellValue('A6', 'CÓDIGO: '.$id_producto)
                             ->setCellValue('A7', 'TIPO: 05')
                             ->setCellValue('A8', 'DESCRIPCIÓN: '.$nombre_producto)
                             ->setCellValue('A9', 'UNIDAD DE MEDIDA: '.$id_unidad_medida)
                             ->setCellValue('A10', 'MÉTODO DE EVALUACIÓN: COSTO PROMEDIO');
                $objWorkSheet->setCellValue('F1', 'FT: FACTURA')
                             ->setCellValue('F2', 'GR: GUÍA DE REMISIÓN')
                             ->setCellValue('F3', 'BV: BOLETA DE VENTA')
                             ->setCellValue('F4', 'NC: NOTA DE CRÉDITO')
                             ->setCellValue('F5', 'ND: NOTA DE DÉBITO')
                             ->setCellValue('F6', 'OS: ORDEN DE SALIDA')
                             ->setCellValue('F7', 'OI: ORDEN DE INGRESO')
                             ->setCellValue('F8', 'CU: COSTO UNITARIO (NUEVOS SOLES)')
                             ->setCellValue('F9', 'CT: COSTO TOTAL (NUEVOS SOLES)')
                             ->setCellValue('F10', 'SI: SALDO INICIAL');
                $objWorkSheet->setCellValue('A12', 'DOCUMENTO DE MOVIMIENTO')
                             ->setCellValue('E12', 'ENTRADAS')
                             ->setCellValue('H12', 'SALIDAS')
                             ->setCellValue('K12', 'SALDO FINAL');
                $objWorkSheet->setCellValue('A13', 'FECHA')
                             ->setCellValue('B13', 'TIPO')
                             ->setCellValue('C13', 'SERIE')
                             ->setCellValue('D13', 'NÚMERO')
                             ->setCellValue('E13', 'CANTIDAD')
                             ->setCellValue('F13', 'CU')
                             ->setCellValue('G13', 'CT')
                             ->setCellValue('H13', 'CANTIDAD')
                             ->setCellValue('I13', 'CU')
                             ->setCellValue('J13', 'CT')
                             ->setCellValue('K13', 'CANTIDAD')
                             ->setCellValue('L13', 'CU')
                             ->setCellValue('M13', 'CT');

                /* Formato para la fila 14 */
                $objPHPExcel->getActiveSheet()->getStyle('A14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('C14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('A14')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('C14')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('D14')->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('G14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('H14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('I14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('J14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('K14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('L14')->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('M14')->applyFromArray($borders);

                /* Traer saldos iniciales de la BD */
                $saldos_iniciales = $this->model_comercial->traer_saldos_iniciales($f_inicial,$id_pro);

                /* varianles para las sumatorias */
                $sumatoria_cantidad_entradas = 0;
                $sumatoria_parciales_entradas = 0;

                $sumatoria_cantidad_salidas = 0;
                $sumatoria_parciales_salidas = 0;

                $sumatoria_cantidad_saldos = 0;
                $sumatoria_parciales_saldos = 0;

                $objPHPExcel->getActiveSheet()->getStyle('E14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('F14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('G14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('H14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('I14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('J14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('K14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('L14')->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('M14')->applyFromArray($style_2);

                $objPHPExcel->getActiveSheet()->getStyle('E14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('F14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('G14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('K14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('L14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M14')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                if( count($saldos_iniciales) > 0 ){
                    foreach ($saldos_iniciales as $result) {
                        /* Formato de Fecha */
                        $elementos = explode("-", $result->fecha_cierre);
                        $anio = $elementos[0];
                        $mes = $elementos[1];
                        $dia = $elementos[2];
                        $array = array($dia, $mes, $anio);
                        $fecha_formateada = implode("-", $array);
                        /* Fin */
                        $objWorkSheet->setCellValue('A14', $fecha_formateada)
                                     ->setCellValue('B14', " ")
                                     ->setCellValue('C14', "SI")
                                     ->setCellValue('D14', " ")
                                     ->setCellValue('E14', $result->stock_inicial)
                                     ->setCellValue('F14', $result->precio_uni_inicial)
                                     ->setCellValue('G14', $result->stock_inicial*$result->precio_uni_inicial)
                                     ->setCellValueExplicit('H14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('I14', $result->precio_uni_inicial)
                                     ->setCellValueExplicit('J14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('K14', $result->stock_inicial)
                                     ->setCellValue('L14', $result->precio_uni_inicial)
                                     ->setCellValue('M14', $result->stock_inicial*$result->precio_uni_inicial);
                        /* ENTRADAS */
                        $sumatoria_cantidad_entradas = $sumatoria_cantidad_entradas + $result->stock_inicial;
                        $sumatoria_parciales_entradas = $sumatoria_parciales_entradas + ($result->stock_inicial * $result->precio_uni_inicial);
                        /* SALDOS */
                        $sumatoria_cantidad_saldos = $sumatoria_cantidad_saldos + $result->stock_inicial;
                        $sumatoria_parciales_saldos = $sumatoria_parciales_saldos + ($result->stock_inicial * $result->precio_uni_inicial);
                    }
                }else{
                    $objWorkSheet->setCellValueExplicit('A14', $f_inicial)
                                 ->setCellValueExplicit('B14', " ")
                                 ->setCellValueExplicit('C14', "SI")
                                 ->setCellValueExplicit('D14', " ")
                                 ->setCellValueExplicit('E14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('F14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('G14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('H14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('I14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('J14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('K14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('L14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('M14', "0.00",PHPExcel_Cell_DataType::TYPE_STRING);
                }

                /* Recorrido del detalle del kardex general por producto */
                $detalle_movimientos_kardex = $this->model_comercial->traer_movimientos_kardex($id_detalle_producto,$f_inicial,$f_final);
                $y = 0;
                if( count($detalle_movimientos_kardex) > 0 ){
                    foreach ($detalle_movimientos_kardex as $data) {
                        $p = 15;
                        $p = $p + $y;
                        /* Centrar contenido */
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);

                        $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($style_2);

                        $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($borders);
                        /* formato de variables */
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                        /* Formato de Fecha */
                        $elementos = explode("-", $data->fecha_registro);
                        $anio = $elementos[0];
                        $mes = $elementos[1];
                        $dia = $elementos[2];
                        $array = array($dia, $mes, $anio);
                        $fecha_formateada_2 = implode("-", $array);
                        /* fin de formato */

                        /* fin de formato */
                        if($data->descripcion == "ENTRADA"){
                            $objWorkSheet->setCellValue('A'.$p, $fecha_formateada_2)
                                         ->setCellValue('B'.$p, "FT")
                                         ->setCellValueExplicit('C'.$p, str_pad($data->serie_comprobante, 3, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('D'.$p, str_pad($data->num_comprobante, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('E'.$p, $data->cantidad_ingreso)
                                         ->setCellValue('F'.$p, $data->precio_unitario_actual)
                                         ->setCellValue('G'.$p, $data->cantidad_ingreso * $data->precio_unitario_actual)
                                         ->setCellValueExplicit('H'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('I'.$p, $data->precio_unitario_actual)
                                         ->setCellValueExplicit('J'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('K'.$p, $data->stock_actual)
                                         ->setCellValue('L'.$p, $data->precio_unitario_actual_promedio)
                                         ->setCellValue('M'.$p, $data->stock_actual*$data->precio_unitario_actual_promedio);
                        }else if($data->descripcion == "SALIDA"){
                            $objWorkSheet->setCellValue('A'.$p, $fecha_formateada_2)
                                         ->setCellValue('B'.$p, "OS")
                                         ->setCellValue('C'.$p, "NIG")
                                         ->setCellValueExplicit('D'.$p, str_pad($data->id_kardex_producto, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('E'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('F'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('G'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('H'.$p, $data->cantidad_salida)
                                         ->setCellValue('I'.$p, $data->precio_unitario_anterior)
                                         ->setCellValue('J'.$p, $data->cantidad_salida*$data->precio_unitario_anterior)
                                         ->setCellValue('K'.$p, $data->stock_actual)
                                         ->setCellValue('L'.$p, $data->precio_unitario_actual)
                                         ->setCellValue('M'.$p, $data->stock_actual*$data->precio_unitario_actual);
                        }else if($data->descripcion == "ORDEN INGRESO"){
                            $objWorkSheet->setCellValue('A'.$p, $fecha_formateada_2)
                                         ->setCellValue('B'.$p, "OI")
                                         ->setCellValueExplicit('C'.$p, str_pad($data->serie_comprobante, 3, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('D'.$p, str_pad($data->num_comprobante, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('E'.$p, $data->cantidad_ingreso)
                                         ->setCellValue('F'.$p, $data->precio_unitario_actual)
                                         ->setCellValue('G'.$p, $data->cantidad_ingreso * $data->precio_unitario_actual)
                                         ->setCellValueExplicit('H'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('I'.$p, $data->precio_unitario_actual)
                                         ->setCellValueExplicit('J'.$p, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('K'.$p, $data->stock_actual)
                                         ->setCellValue('L'.$p, $data->precio_unitario_actual_promedio)
                                         ->setCellValue('M'.$p, $data->stock_actual*$data->precio_unitario_actual_promedio);
                        }               
                        /* ENTRADAS */
                        $sumatoria_cantidad_entradas = $sumatoria_cantidad_entradas + $data->cantidad_ingreso;
                        $sumatoria_parciales_entradas = $sumatoria_parciales_entradas + ($data->cantidad_ingreso * $data->precio_unitario_actual);
                        /* SALIDAS */
                        $sumatoria_cantidad_salidas = $sumatoria_cantidad_salidas + $data->cantidad_salida;
                        $sumatoria_parciales_salidas = $sumatoria_parciales_salidas + ($data->cantidad_salida * $data->precio_unitario_actual);
                        /* SALDOS */
                        $sumatoria_cantidad_saldos = $sumatoria_cantidad_saldos + $data->stock_actual;
                        if($data->descripcion == "SALIDA"){
                            $sumatoria_parciales_saldos = $sumatoria_parciales_saldos + ($data->precio_unitario_actual * $data->stock_actual);
                        }else if($data->descripcion == "ENTRADA"){
                            $sumatoria_parciales_saldos = $sumatoria_parciales_saldos + ($data->precio_unitario_actual_promedio * $data->stock_actual);
                        }
                        $y = $y + 1;
                    }
                }

                $p = 15 + $y;
                $objWorkSheet->setCellValue('A'.$p, "")
                             ->setCellValue('B'.$p, "")
                             ->setCellValue('C'.$p, "")
                             ->setCellValue('D'.$p, "TOTALES")
                             ->setCellValue('E'.$p, $sumatoria_cantidad_entradas)
                             ->setCellValue('F'.$p, "")
                             ->setCellValue('G'.$p, $sumatoria_parciales_entradas)
                             ->setCellValue('H'.$p, $sumatoria_cantidad_salidas)
                             ->setCellValue('I'.$p, "")
                             ->setCellValue('J'.$p, $sumatoria_parciales_salidas)
                             ->setCellValue('K'.$p, $sumatoria_cantidad_saldos)
                             ->setCellValue('L'.$p, "")
                             ->setCellValue('M'.$p, $sumatoria_parciales_saldos);

                /* Centrar contenido */
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$p)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($borders);

                /* Dar formato numericos a las celdas */
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                /* Alinear el valor de la celda a la derecha */
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->applyFromArray($style_2);

                /* Rename sheet */
                $objWorkSheet->setTitle("$nombre_producto");
                $i++;
            }
        }

        $objPHPExcel->setActiveSheetIndex(0);

        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Kardex_General_indice_$indice.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function al_exportar_kardex_sunat_excel(){
        $data = $this->security->xss_clean($this->uri->segment(3));
        $data = json_decode($data, true);
        $f_inicial = $data[0];
        $f_final = $data[1];

        (array)$arr = str_split($f_final, 4);
        $anio = $arr[0];

        /* Formato para la fecha inicial */
        $elementos = explode("-", $f_inicial);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        $array = array($dia, $mes, $anio);
        $f_inicial = implode("-", $array);
        /* Fin */

        $this->load->library('pHPExcel');
        /* variables de PHPExcel */
        $objPHPExcel = new PHPExcel();
        $nombre_archivo = "phpExcel";

        /* propiedades de la celda */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Narrow');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        /* Here your first sheet */
        $sheet = $objPHPExcel->getActiveSheet();

        /* Style - Bordes */
        $borders = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $style_2 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );
        /* Add new sheet */
        $objWorkSheet = $objPHPExcel->createSheet(0); /* Setting index when creating */
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E1:G1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:J1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K1:M1');

        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('E1:G1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('H1:J1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('K1:M1')->applyFromArray($borders);
        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($borders);

        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('E1:G1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('H1:J1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('K1:M1')->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($style);

        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('E1:G1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('H1:J1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('K1:M1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);

        /* Cabecera SUNAT */
        $objWorkSheet->setCellValue('A1', 'DOCUMENTO DE MOVIMIENTO')
                     ->setCellValue('E1', 'ENTRADAS')
                     ->setCellValue('H1', 'SALIDAS')
                     ->setCellValue('K1', 'SALDO FINAL');
        $objWorkSheet->setCellValue('A2', 'FECHA')
                     ->setCellValue('B2', 'TIPO')
                     ->setCellValue('C2', 'SERIE')
                     ->setCellValue('D2', 'NÚMERO')
                     ->setCellValue('E2', 'CANTIDAD')
                     ->setCellValue('F2', 'CU')
                     ->setCellValue('G2', 'CT')
                     ->setCellValue('H2', 'CANTIDAD')
                     ->setCellValue('I2', 'CU')
                     ->setCellValue('J2', 'CT')
                     ->setCellValue('K2', 'CANTIDAD')
                     ->setCellValue('L2', 'CU')
                     ->setCellValue('M2', 'CT')
                     ->setCellValue('O2', 'CODGIO')
                     ->setCellValue('P2', 'DESCRIPCION')
                     ->setCellValue('Q2', 'UNID DE MEDIDA');
        /* Traer informacion de la BD */
        $nombre_productos_salidas = $this->model_comercial->traer_nombres_kardex_sunat();
        /* Recorro con todos los nombres seleccionados que tienen una salida/ingreso en el kardex */
        /* Tambien debo considerar los que no han tenido registros en la tabla kardex pero si debe aparece SI o vacio */
        $i = 3;
        foreach ($nombre_productos_salidas as $reg) {

            $nombre_producto = $reg->no_producto;
            $id_producto = $reg->id_producto;
            $id_unidad_medida = $reg->id_unidad_medida;
            $id_detalle_producto = $reg->id_detalle_producto;
            $id_pro = $reg->id_pro;

            /* Traer sólo productos que tengan registros en el periodo seleccionado */
            $produtos_con_kardex = $this->model_comercial->traer_producto_con_kardex($id_detalle_producto,$f_inicial,$f_final);
            if( count($produtos_con_kardex) > 0 ){
                /* Formato para la filas */
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($borders);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($borders);

                /* Traer saldos iniciales de la BD */
                $saldos_iniciales = $this->model_comercial->traer_saldos_iniciales($f_inicial,$id_pro);

                $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($style_2);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($style_2);


                $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                if( count($saldos_iniciales) > 0 ){
                    foreach ($saldos_iniciales as $result) {
                        /* Formato de Fecha */
                        $elementos = explode("-", $result->fecha_cierre);
                        $anio = $elementos[0];
                        $mes = $elementos[1];
                        $dia = $elementos[2];
                        $array = array($dia, $mes, $anio);
                        $fecha_formateada = implode("-", $array);
                        /* Fin */
                        $stock_cierre_total = $result->stock_inicial + $result->stock_inicial_sta_clara;
                        $objWorkSheet->setCellValue('A'.$i, $fecha_formateada)
                                     ->setCellValue('B'.$i, " ")
                                     ->setCellValue('C'.$i, "SI")
                                     ->setCellValue('D'.$i, " ")
                                     ->setCellValue('E'.$i, $stock_cierre_total)
                                     ->setCellValue('F'.$i, $result->precio_uni_inicial)
                                     ->setCellValue('G'.$i, $stock_cierre_total*$result->precio_uni_inicial)
                                     ->setCellValueExplicit('H'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('I'.$i, $result->precio_uni_inicial)
                                     ->setCellValueExplicit('J'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                     ->setCellValue('K'.$i, $stock_cierre_total)
                                     ->setCellValue('L'.$i, $result->precio_uni_inicial)
                                     ->setCellValue('M'.$i, $stock_cierre_total*$result->precio_uni_inicial)
                                     ->setCellValue('O'.$i, $id_producto)
                                     ->setCellValue('P'.$i, $nombre_producto)
                                     ->setCellValue('Q'.$i, $id_unidad_medida);
                        $i++;
                    }
                }else{
                    $objWorkSheet->setCellValueExplicit('A'.$i, $f_inicial)
                                 ->setCellValueExplicit('B'.$i, " ")
                                 ->setCellValueExplicit('C'.$i, "SI")
                                 ->setCellValueExplicit('D'.$i, " ")
                                 ->setCellValueExplicit('E'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('F'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('G'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('H'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('I'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('J'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('K'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('L'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('M'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                 ->setCellValueExplicit('O'.$i, $id_producto)
                                 ->setCellValueExplicit('P'.$i, $nombre_producto)
                                 ->setCellValueExplicit('Q'.$i, $id_unidad_medida);
                    $i++;
                }

                /* Recorrido del detalle del kardex general por producto */
                $detalle_movimientos_kardex = $this->model_comercial->traer_movimientos_kardex($id_detalle_producto,$f_inicial,$f_final);
                if( count($detalle_movimientos_kardex) > 0 ){
                    foreach ($detalle_movimientos_kardex as $data) {
                        /* Centrar contenido */
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($borders);

                        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($style_2);
                        $objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($style_2);

                        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($borders);
                        $objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($borders);
                        /* formato de variables */
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                        /* Formato de Fecha */
                        $elementos = explode("-", $data->fecha_registro);
                        $anio = $elementos[0];
                        $mes = $elementos[1];
                        $dia = $elementos[2];
                        $array = array($dia, $mes, $anio);
                        $fecha_formateada_2 = implode("-", $array);
                        /* fin de formato */
                        
                        /* fin de formato */
                        if($data->descripcion == "ENTRADA"){
                            $objWorkSheet->setCellValue('A'.$i, $fecha_formateada_2)
                                         ->setCellValue('B'.$i, "FT")
                                         ->setCellValueExplicit('C'.$i, $data->serie_comprobante,PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('D'.$i, str_pad($data->num_comprobante, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('E'.$i, $data->cantidad_ingreso)
                                         ->setCellValue('F'.$i, $data->precio_unitario_actual)
                                         ->setCellValue('G'.$i, $data->cantidad_ingreso * $data->precio_unitario_actual)
                                         ->setCellValueExplicit('H'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('I'.$i, $data->precio_unitario_actual_promedio)
                                         ->setCellValueExplicit('J'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('K'.$i, $data->stock_actual)
                                         ->setCellValue('L'.$i, $data->precio_unitario_actual_promedio)
                                         ->setCellValue('M'.$i, $data->stock_actual*$data->precio_unitario_actual_promedio)
                                         ->setCellValue('O'.$i, $data->id_producto)
                                         ->setCellValue('P'.$i, $data->no_producto)
                                         ->setCellValue('Q'.$i, $data->id_unidad_medida);
                            $i++;
                        }else if($data->descripcion == "SALIDA"){
                            $objWorkSheet->setCellValue('A'.$i, $fecha_formateada_2)
                                         ->setCellValue('B'.$i, "OS")
                                         ->setCellValue('C'.$i, "NIG")
                                         ->setCellValueExplicit('D'.$i, str_pad($data->id_kardex_producto, 8, 0, STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('E'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('F'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValueExplicit('G'.$i, "0.00",PHPExcel_Cell_DataType::TYPE_STRING)
                                         ->setCellValue('H'.$i, $data->cantidad_salida)
                                         ->setCellValue('I'.$i, $data->precio_unitario_anterior)
                                         ->setCellValue('J'.$i, $data->cantidad_salida*$data->precio_unitario_anterior)
                                         ->setCellValue('K'.$i, $data->stock_actual)
                                         ->setCellValue('L'.$i, $data->precio_unitario_actual)
                                         ->setCellValue('M'.$i, $data->stock_actual*$data->precio_unitario_actual)
                                         ->setCellValue('O'.$i, $data->id_producto)
                                         ->setCellValue('P'.$i, $data->no_producto)
                                         ->setCellValue('Q'.$i, $data->id_unidad_medida);
                            $i++;
                        }
                    }
                }
                /* Rename sheet */
                $objWorkSheet->setTitle("Reporte SUNAT I");
            }
            
            
        }

        $objPHPExcel->setActiveSheetIndex(0);

        /* datos de la salida del excel */
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Kardex_General.xls");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function validar_existencia_saldo_inicial(){
        $id_producto = $this->security->xss_clean($this->input->post("id_producto"));
        $fechainicial = $this->security->xss_clean($this->input->post("fechainicial"));
        $datos = $this->model_comercial->report_saldo_inicial($id_producto,$fechainicial);
        $existe = count($datos);
          if($existe <= 0){
            echo '1';
        }
    }

    public function eliminar_salidas_2014(){
        $result = $this->model_comercial->eliminar_salidas_2014();
        // Verificamos que existan resultados
        if(!$result){
            // Sí no se encotnraron datos.
            echo '<span style="color:red"><b>ERROR: </b>ERROR</span>';
        }else{
            // Registramos la sesion del usuario
            echo '1';
        }
    }

    public function actualizar_saldos_iniciales(){
        $result = $this->model_comercial->actualizar_saldos_iniciales();
        // Verificamos que existan resultados
        if(!$result){
            // Sí no se encotnraron datos.
            echo '<span style="color:red"><b>ERROR: </b>ERROR</span>';
        }else{
            // Registramos la sesion del usuario
            echo '1';
        }
    }
    
}
?>