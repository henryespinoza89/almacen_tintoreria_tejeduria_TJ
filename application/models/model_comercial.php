<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//date_default_timezone_set('MST');
class Model_comercial extends CI_Model {

	public function __construct(){
		parent::__construct();
        $this->load->library('session');
	}

    public function listarCategoria(){
        $this->db->select('id_categoria,no_categoria');
        $this->db->order_by('no_categoria', 'ASC');           
        $query = $this->db->get('categoria');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_categoria, ENT_QUOTES)] = htmlspecialchars($row->no_categoria, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarTipoProducto(){
        $this->db->select('id_tipo_producto,no_tipo_producto');
        $this->db->order_by('no_tipo_producto', 'ASC');           
        $query = $this->db->get('tipo_producto');
        if($query->num_rows()>0){
            foreach($query->result() as $row2) 
                $arrDatos[htmlspecialchars($row2->id_tipo_producto, ENT_QUOTES)] = htmlspecialchars($row2->no_tipo_producto, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarMaquinaCombo(){
        $this->db->select('id_maquina,nombre_maquina');
        $this->db->order_by('nombre_maquina', 'ASC');           
        $query = $this->db->get('maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_maquina, ENT_QUOTES)] = htmlspecialchars($row->nombre_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function get_salidas_eliminar($fechainicial, $fechafinal){
        // esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        $filtro .= " AND DATE(salida_producto.fecha) BETWEEN'".$fechainicial."'AND'".$fechafinal."'";
        $filtro .= " ORDER BY salida_producto.fecha ASC, salida_producto.id_salida_producto ASC";
        $filtro .= " LIMIT 150";
        $sql = "SELECT salida_producto.id_salida_producto,salida_producto.fecha
        FROM salida_producto
        WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarUbicacionProductos(){
        $filtro = "";
        if($this->input->post('ubicacion_producto')){
            $filtro .= " AND (ubicacion.nombre_ubicacion ILIKE '%".$this->security->xss_clean($this->input->post('ubicacion_producto'))."%')";   
        }
        $filtro .= " ORDER BY ubicacion.nombre_ubicacion ASC";
        $sql = "SELECT ubicacion.id_ubicacion,ubicacion.nombre_ubicacion
        FROM ubicacion WHERE ubicacion.id_ubicacion IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getUbicacionProducto(){
        //Recuperamos el ID  -> 
        $id_ubicacion = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT ubicacion.id_ubicacion,ubicacion.nombre_ubicacion
                FROM ubicacion
                WHERE ubicacion.id_ubicacion =".$id_ubicacion;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminar_ubicacion_producto($id_ubicacion){
        // verificar si la ubicacion del producto esta referenciada desde la tabla producto
        $this->db->select('id_ubicacion');
        $this->db->where('id_ubicacion',$id_ubicacion);
        $query = $this->db->get('producto');
        if($query->num_rows() <= 0){
            $sql = "DELETE FROM ubicacion WHERE id_ubicacion = " . $id_ubicacion . "";
            $query = $this->db->query($sql);
            if($query == 'TRUE'){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function eliminar_tipo_producto($id_tipo_producto){
        $this->db->select('id_tipo_producto');
        $this->db->where('id_tipo_producto',$id_tipo_producto);
        $query = $this->db->get('producto');
        if($query->num_rows() <= 0){
            $sql = "DELETE FROM tipo_producto WHERE id_tipo_producto = " . $id_tipo_producto . "";
            $query = $this->db->query($sql);
            if($query == 'TRUE'){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function eliminar_producto($id_pro){
        $this->db->select('id_detalle_producto');
        $this->db->where('id_pro',$id_pro);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_dp = $row->id_detalle_producto;
        }

        $this->db->select('id_detalle_producto');
        $this->db->where('id_detalle_producto',$id_dp);
        $query = $this->db->get('detalle_ingreso_producto');
        if($query->num_rows() > 0){
            return false;
        }else{
            $this->db->select('id_pro');
            $this->db->where('id_pro',$id_pro);
            $query = $this->db->get('saldos_iniciales');
            if($query->num_rows()>0){
                return false;
            }else{
                $sql = "DELETE FROM producto WHERE id_pro = " . $id_pro . "";
                $query = $this->db->query($sql);

                $sql = "DELETE FROM detalle_producto WHERE id_detalle_producto = " . $id_dp . "";
                $query = $this->db->query($sql);
                
                return true;
            }
        }
    }

    function eliminar_categoria_producto($id_categoria){
        $this->db->select('id_categoria');
        $this->db->where('id_categoria',$id_categoria);
        $query = $this->db->get('producto');
        if($query->num_rows() <= 0){
            $sql = "DELETE FROM categoria WHERE id_categoria = " . $id_categoria . "";
            $query = $this->db->query($sql);
            if($query == 'TRUE'){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function updateUbicacion($actualizar_data, $edit_ubicacion){
        $id_ubicacion = $this->security->xss_clean($this->uri->segment(3));
        /* Validación de duplicidad */
        $this->db->select('id_ubicacion');
        $this->db->where('nombre_ubicacion',$edit_ubicacion);
        $query = $this->db->get('ubicacion');
        if($query->num_rows() <= 0){
            /* Actualización */
            $this->db->where('id_ubicacion',$id_ubicacion);
            $this->db->update('ubicacion', $actualizar_data);
            return true;
        }else{
            return false;
        }
    }

    function update_tipo_producto($actualizar_data, $edittipprod){
        $id_tipo_producto = $this->security->xss_clean($this->uri->segment(3));
        // Validación de duplicidad
        $this->db->select('id_tipo_producto');
        $this->db->where('no_tipo_producto',$edittipprod);
        $query = $this->db->get('tipo_producto');
        if($query->num_rows() <= 0){
            // Actualización
            $this->db->where('id_tipo_producto',$id_tipo_producto);
            $this->db->update('tipo_producto', $actualizar_data);
            return true;
        }else{
            return false;
        }
    }

    function update_categoria_producto($actualizar_data, $editcatprod){
        $id_categoria = $this->security->xss_clean($this->uri->segment(3));
        // Validación de duplicidad
        $this->db->select('id_categoria');
        $this->db->where('no_categoria',$editcatprod);
        $query = $this->db->get('categoria');
        if($query->num_rows() <= 0){
            // Actualización
            $this->db->where('id_categoria',$id_categoria);
            $this->db->update('categoria', $actualizar_data);
            return true;
        }else{
            return false;
        }
    }

    function updateAgenteAduana($actualizar_data, $editnombreagente){
        $id_agente = $this->security->xss_clean($this->uri->segment(3));
        /* Validación de duplicidad */
        $this->db->select('id_agente');
        $this->db->where('no_agente',$editnombreagente);
        $query = $this->db->get('agente_aduana');
        if($query->num_rows() <= 0){
            /* Actualización */
            $this->db->where('id_agente',$id_agente);
            $this->db->update('agente_aduana', $actualizar_data);
            return true;
        }else{
            return false;
        }
    }

    function save_ubicacion_producto(){
        $ubicacion_producto_modal = strtoupper($this->security->xss_clean($this->input->post('ubicacion_producto_modal')));
        $this->db->select('nombre_ubicacion');
        $this->db->where('nombre_ubicacion',$ubicacion_producto_modal);
        $query = $this->db->get('ubicacion');
        if($query->num_rows() <= 0){
            $registro = array(
                'nombre_ubicacion'=> $ubicacion_producto_modal
            );
            $this->db->insert('ubicacion', $registro);
            return true;
        }else{
            return false;
        }
    }

    function save_categoria_producto(){
        $categoria_producto_modal = strtoupper($this->security->xss_clean($this->input->post('categoria_producto_modal')));
        $this->db->select('no_categoria');
        $this->db->where('no_categoria',$categoria_producto_modal);
        $query = $this->db->get('categoria');
        if($query->num_rows() <= 0){
            $registro = array(
                'no_categoria'=> $categoria_producto_modal
            );
            $this->db->insert('categoria', $registro);
            return true;
        }else{
            return false;
        }
    }

    function save_tipo_producto(){
        $tipo_producto_modal = strtoupper($this->security->xss_clean($this->input->post('tipo_producto_modal')));
        $this->db->select('no_tipo_producto');
        $this->db->where('no_tipo_producto',$tipo_producto_modal);
        $query = $this->db->get('tipo_producto');
        if($query->num_rows() <= 0){
            $registro = array(
                'no_tipo_producto'=> $tipo_producto_modal
            );
            $this->db->insert('tipo_producto', $registro);
            return true;
        }else{
            return false;
        }
    }

    function get_facturas_importadas_pendientes(){
        $filtro = "";
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen'));
        $filtro .= " AND ingreso_producto.id_comprobante =".(int)4;
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.nro_comprobante,ingreso_producto.fecha,ingreso_producto.total,
        ingreso_producto.gastos,ingreso_producto.id_almacen,ingreso_producto.cs_igv,ingreso_producto.serie_comprobante,comprobante.no_comprobante,
        moneda.no_moneda,agente_aduana.no_agente,proveedor.razon_social,ingreso_producto.id_comprobante
        FROM
        ingreso_producto
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        INNER JOIN agente_aduana ON ingreso_producto.id_agente = agente_aduana.id_agente
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro."ORDER BY ingreso_producto.fecha ASC";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_datos_detalle_pedido_fill_inputs($id_ingreso_producto){
        try{
            $filtro = $id_ingreso_producto;
            $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante,ingreso_producto.nro_comprobante,ingreso_producto.fecha,
                    ingreso_producto.id_moneda,ingreso_producto.id_proveedor,ingreso_producto.total,ingreso_producto.gastos,ingreso_producto.id_almacen,
                    ingreso_producto.id_agente,ingreso_producto.cs_igv,ingreso_producto.serie_comprobante,proveedor.razon_social
                    FROM
                    ingreso_producto
                    INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
                    WHERE ingreso_producto.id_ingreso_producto =".$filtro;
            $query = $this->db->query($sql);
            $a_data = $query->result_array();
            return $a_data;
        }catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function save_agente_aduana(){
        $agente_aduana_modal = strtoupper($this->security->xss_clean($this->input->post('agente_aduana_modal')));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $this->db->select('no_agente');
        $this->db->where('no_agente',$agente_aduana_modal);
        $this->db->where('id_almacen',$almacen);
        $query = $this->db->get('agente_aduana');
        if($query->num_rows() <= 0){
            $registro = array(
                'no_agente'=> $agente_aduana_modal,
                'id_almacen'=> $almacen
            );
            $this->db->insert('agente_aduana', $registro);
            return true;
        }else{
            return false;
        }
    }

    public function listarTipoProdCombo($id_pro){
        $this->db->select('id_tipo_producto,no_tipo_producto');
        $this->db->order_by('no_tipo_producto', 'ASC');           
        $query = $this->db->get('tipo_producto');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row2) 
                $arrDatos[htmlspecialchars($row2->id_tipo_producto, ENT_QUOTES)] = htmlspecialchars($row2->no_tipo_producto, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function validacion_cierre_almacen_model($fecha_cierre){
        $sql = "SELECT producto.id_pro,detalle_producto.stock,detalle_producto.precio_unitario
                FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto";
        $query = $this->db->query($sql);
        
        /* Formateando la Fecha */
        $elementos = explode("-", $fecha_cierre);
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
        $fecha_formateada = implode("-", $array);
        /* Fin */

        foreach($query->result() as $row)
        {
            $id_pro = $row->id_pro;
            $stock = $row->stock;
            $precio_unitario = $row->precio_unitario;
            
            $this->db->select('id_saldos_iniciales');
            $this->db->where('id_pro',$id_pro);
            $this->db->where('fecha_cierre',$fecha_formateada);
            $query = $this->db->get('saldos_iniciales');
            if($query->num_rows() > 0){
                return 'error_validacion';
            }else{
                return 'validacion_conforme';
            }
        }

    }

    function cierre_almacen_montos_model($fecha_cierre){
        $sumatoria = 0;
        $sumatoria_sta_clara = 0;
        $sumatoria_sta_anita = 0;
        $sql = "SELECT producto.id_pro,detalle_producto.stock,detalle_producto.precio_unitario,detalle_producto.stock_sta_clara
                FROM
                producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto";
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {
            $id_pro = $row->id_pro;
            $stock = $row->stock;
            $stock_sta_clara = $row->stock_sta_clara;
            $precio_unitario = $row->precio_unitario;

            $stock_general = $stock + $stock_sta_clara;
            $sumatoria_sta_clara = $sumatoria_sta_clara + ($stock_sta_clara*$precio_unitario);
            $sumatoria_sta_anita = $sumatoria_sta_anita + ($stock*$precio_unitario);
            $sumatoria = $sumatoria + ($stock_general*$precio_unitario);
        }
        /* Obtener el nombre del mes */
        $elementos = explode("-", $fecha_cierre);
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

        // Obtener la fecha con la que hizo el registro de los saldos iniciales
        // Formateando la Fecha
        $elementos_2 = explode("-", $fecha_cierre);
        $anio = $elementos_2[0];
        $mes = $elementos_2[1];
        $dia = $elementos_2[2];

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
        /* Fin */

        $datos_cierre_mes = array(
            "fecha_cierre" => $fecha_cierre,
            "monto_cierre" => $sumatoria,
            "monto_cierre_sta_anita" => $sumatoria_sta_anita,
            "monto_cierre_sta_clara" => $sumatoria_sta_clara,
            "monto_cierre" => $sumatoria,
            "nombre_mes" => $nombre_mes,
            "fecha_auxiliar" => $fecha_formateada
        );
        $this->db->insert('monto_cierre', $datos_cierre_mes);
        return true;
    }

    function cierre_almacen_meses_anteriores_model($fecha_cierre){
        $sumatoria = 0;
        $filtro = "";
        $filtro .= " AND DATE(saldos_iniciales.fecha_cierre) ='".$fecha_cierre."'";
        $sql = "SELECT saldos_iniciales.id_saldos_iniciales,saldos_iniciales.id_pro,saldos_iniciales.fecha_cierre,saldos_iniciales.stock_inicial,saldos_iniciales.precio_uni_inicial
                FROM saldos_iniciales
                WHERE saldos_iniciales.id_saldos_iniciales IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {
            $stock = $row->stock_inicial;
            $precio_unitario = $row->precio_uni_inicial;

            $sumatoria = $sumatoria + ($stock*$precio_unitario);
        }

        /* Obtener el nombre del mes */
        $elementos = explode("-", $fecha_cierre);
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

        $datos_cierre_mes = array(
            "fecha_cierre" => $fecha_cierre,
            "monto_cierre" => $sumatoria,
            "nombre_mes" => $nombre_mes
        );
        $this->db->insert('monto_cierre', $datos_cierre_mes);

        return true;
    }

    function listarUnidadMedidaCombo(){
        $this->db->select('id_unidad_medida,nom_uni_med');
        $this->db->order_by('nom_uni_med', 'ASC');           
        $query = $this->db->get('unidad_medida');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_unidad_medida, ENT_QUOTES)] = htmlspecialchars($row->nom_uni_med, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }
    
    public function listarCategoriaProductos(){
        $sql = "SELECT categoria.id_categoria,categoria.no_categoria FROM categoria";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

/*
    public function listarCategoriaProductos(){
        //$sql = "call listarCategoria();";
        $query = $this->db->call_function('call listarCategoria()');
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }
    */
    /* procedure */
    public function saveCetegoriaProducto(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        //Verifico si existe
        $this->db->where('no_categoria',$nombre);
        $query = $this->db->get('categoria');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'no_categoria'=> $nombre
            );
            $this->db->insert('categoria', $registro);
            return true;
        }
    }

    function save_detalle_producto($a_data_detalle){
        $insert = $this->db->insert('detalle_producto',$a_data_detalle);
        if($insert) return $this->db->insert_id();
        //echo 'data ingresada satisfactoriamente';
    }

    function save_producto($a_data_producto){
        $this->db->insert('producto',$a_data_producto);
        //echo 'data ingresada satisfactoriamente';
    }

    public function agrega_ingreso($datos, $seriecomprobante, $numcomprobante, $proveedor, $fecharegistro)
    {   
        $fecharegistro = $this->security->xss_clean($this->input->post("fecharegistro"));
        $this->db->select('dolar_venta,euro_venta');
        $this->db->where('fecha_actual',$fecharegistro);
        $query = $this->db->get('tipo_cambio');
        if($query->num_rows()>0){
            $this->db->select('id_ingreso_producto');
            $this->db->where('serie_comprobante',$seriecomprobante);
            $this->db->where('nro_comprobante',$numcomprobante);
            $this->db->where('id_proveedor',$proveedor);
            $this->db->where('fecha',$fecharegistro);
            $query = $this->db->get('ingreso_producto');
            if($query->num_rows()>0){
                foreach($query->result() as $row){
                    $id_ingreso_producto = $row->id_ingreso_producto;
                }
                // return $id_ingreso_producto;
                return 'actualizacion_registro';
            }else{
                $last_id = $this->db->insert('ingreso_producto', $datos);
                if($last_id != ""){
                    return $this->db->insert_id();
                }else{
                    return FALSE;
                }
            }
        }else{
            return FALSE;
        }
    }

    public function insert_orden_ingreso($datos)
    {   
        $last_id = $this->db->insert('adm_orden_ingreso', $datos);
        if($last_id != ""){
            return $this->db->insert_id();
        }else{
            return "error_inesperado";
        }
    }

    function cierre_almacen_model($fecha_cierre){
        $sql = "SELECT producto.id_pro,detalle_producto.stock,detalle_producto.precio_unitario,
                detalle_producto.stock_sta_clara
                FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto";
        $query = $this->db->query($sql);
        /* Formateando la Fecha */
        $elementos = explode("-", $fecha_cierre);
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
        $fecha_formateada = implode("-", $array);
        //echo $fecha_formateada;
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
            $stock = $row->stock;
            $stock_sta_clara = $row->stock_sta_clara;
            $precio_unitario = $row->precio_unitario;
            if($precio_unitario != ""){
                $datos = array(
                    "id_pro" => $id_pro,
                    "fecha_cierre" => $fecha_formateada,
                    "stock_inicial" => $stock,
                    "stock_inicial_sta_clara" => $stock_sta_clara,
                    "precio_uni_inicial" => $precio_unitario
                );
                $this->db->insert('saldos_iniciales', $datos);
            }else if($precio_unitario == ""){
                $datos = array(
                    "id_pro" => $id_pro,
                    "fecha_cierre" => $fecha_formateada,
                    "stock_inicial" => $stock,
                    "stock_inicial_sta_clara" => $stock_sta_clara,
                    "precio_uni_inicial" => 0
                );
                $this->db->insert('saldos_iniciales', $datos);
            }
        }
        return true;
    }

    public function actualizar_detalle_kardex_importado($id_proveedor,$id_comprobante,$suma_parciales_factura,$id_detalle_producto,$cantidad_ingreso,$precio_ingreso,$fecharegistro,$seriecomprobante,$numcomprobante,$total_factura_contabilidad,$almacen)
    {
        $aux_bucle_saldos_ini = 0;
        // Obtener el ID del registro de la cabecera de la factura
        $this->db->select('id_ingreso_producto');
        $this->db->where('serie_comprobante',$seriecomprobante);
        $this->db->where('nro_comprobante',$numcomprobante);
        $this->db->where('id_proveedor',$id_proveedor);
        $this->db->where('fecha',$fecharegistro);
        $query = $this->db->get('ingreso_producto');
        if($query->num_rows()>0){
            foreach($query->result() as $row){
                $id_ingreso_producto = $row->id_ingreso_producto;
            }
            // Actualizar el monto total de la factura importada
            $actualizar = array(
                'id_comprobante'=> 2,
                'total'=> $total_factura_contabilidad
            );
            $this->db->where('id_ingreso_producto',$id_ingreso_producto);
            $this->db->update('ingreso_producto', $actualizar);

            /* Procedimiento para la actualizacion del kardex */
            $calculo_porcentaje = ($cantidad_ingreso*$precio_ingreso)/$suma_parciales_factura;
            $p_u_gastos = ($calculo_porcentaje * $total_factura_contabilidad)/$cantidad_ingreso;
            /* Traer datos del producto */
            $this->db->select('stock,precio_unitario,stock_referencial_sta_anita,precio_unitario_referencial');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $stock_sta_anita = $row->stock; /* Stock de Sta anita */
                $stock_referencial_sta_anita = $row->stock_referencial_sta_anita;
                $precio_unitario = $row->precio_unitario;
                $precio_unitario_referencial = $row->precio_unitario_referencial;
            }

            $this->db->select('id_pro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('producto');
            foreach($query->result() as $row){
                $id_pro = $row->id_pro;
            }

            if($almacen == 2){ /* Sta. Anita */
                $stock_referencial = $stock_referencial_sta_anita;
            }

            /* Actualizar el precio unitario */
            // Considero el stock_referencial para conocer el stock del producto antes de hacer la insercion de la guia de remision
            // sirviendome para calcular el precio unitario correcto
            $nuevo_precio_unitario = (($stock_referencial*$precio_unitario_referencial)+($cantidad_ingreso*$p_u_gastos))/($stock_referencial + $cantidad_ingreso);
            $actualizar = array(
                'precio_unitario'=> $nuevo_precio_unitario
            );
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
            /* End de la actualización */

            /* realizo LA ACTUALIZACION del kardex */ /* 1ero selecciono la fila del producto que voy actualizar */
            $actualizar_kardex = array(
                'precio_unitario_anterior'=> $precio_unitario_referencial,
                'precio_unitario_actual'=> $p_u_gastos,
                'precio_unitario_actual_promedio'=> $nuevo_precio_unitario,
                'fecha_registro'=> $fecharegistro,
                'serie_comprobante'=> $seriecomprobante,
                'num_comprobante'=> $numcomprobante,
                'descripcion'=> "ENTRADA"
            );
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->where('num_comprobante',$numcomprobante);
            $this->db->where('descripcion',"IMPORTACION");
            $this->db->update('kardex_producto', $actualizar_kardex);
            /* Realizo la actualización de las salidas posteriores a la fecha de ingreso de la guia de remision */
            /* Obtengo el id del registro en el kardex para realizar la actualización */
            $this->db->select('id_kardex_producto,num_comprobante,descripcion');
            $this->db->where('fecha_registro >',$fecharegistro);
            // $this->db->where('descripcion',"SALIDA");
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('kardex_producto');
            foreach($query->result() as $row){
                $id_kardex_producto = $row->id_kardex_producto;
                $num_comprobante = $row->num_comprobante;
                $descripcion = $row->descripcion;
                // Actualizar registros del producto en el kardex
                $actualizar_precio_kardex = array(
                    'precio_unitario_anterior'=> $nuevo_precio_unitario,
                    'precio_unitario_actual'=> $nuevo_precio_unitario
                );
                $this->db->where('id_kardex_producto',$id_kardex_producto);
                $this->db->update('kardex_producto', $actualizar_precio_kardex);
                // Actualizar el precio de salida en la tabla salida_producto siempre y cuando sea una salida
                // puede traer una orden de ingreso
                if($descripcion != 'ORDEN INGRESO'){
                    $actualizar_precio_salida = array(
                        'p_u_salida'=> $nuevo_precio_unitario
                    );
                    $this->db->where('id_salida_producto',$num_comprobante);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->update('detalle_salida_producto', $actualizar_precio_salida);
                }else if($descripcion == 'ORDEN INGRESO'){
                    // Actualizar el precio unitario en el kardex
                    $actualizar_precio_io_kardex = array(
                        'precio_unitario_actual_promedio'=> $nuevo_precio_unitario
                    );
                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                    $this->db->update('kardex_producto', $actualizar_precio_io_kardex);
                }
            }
            // Formateando la Fecha
            $elementos = explode("-", $fecharegistro);
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
            $fecha_formateada = implode("-", $array);

            $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
            $this->db->where('id_pro',$id_pro);
            $this->db->where('fecha_cierre',$fecha_formateada);
            $query = $this->db->get('saldos_iniciales');
            if($query->num_rows()>0){
                foreach($query->result() as $row){
                    $id_saldos_iniciales = $row->id_saldos_iniciales;
                    $stock_inicial = $row->stock_inicial;
                    $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                    $precio_uni_inicial = $row->precio_uni_inicial;
                }
                $actualizar = array(
                    'precio_uni_inicial'=> $nuevo_precio_unitario
                );
                $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                $this->db->update('saldos_iniciales', $actualizar);
            }
            return true;
        }else{
            return 'no_se_encontro_factura_importada';
        }
    }

    public function inserta_factura_masiva($id_comprobante,$suma_parciales_factura,$a_data,$id_detalle_producto,$cantidad_ingreso,$precio_ingreso,$fecharegistro,$seriecomprobante,$numcomprobante,$total_factura_contabilidad,$almacen)
    {
        // Realizo el registro del detalle de la factura
        $insert = $this->db->insert('detalle_ingreso_producto', $a_data);
        // if($insert) return  $this->db->insert_id();
        // Calculo del precio unitario incluido los gastos
        if($id_comprobante == 4){
            $calculo_porcentaje = 0;
            $p_u_gastos = 0;
        }else if($id_comprobante == 2){
            $calculo_porcentaje = ($cantidad_ingreso*$precio_ingreso)/$suma_parciales_factura;
            $p_u_gastos = ($calculo_porcentaje * $total_factura_contabilidad)/$cantidad_ingreso;
        }

        // Obtener el id del area
        /*
        $this->db->select('id_area');
        $this->db->where('no_area',$nombre_area);
        $query = $this->db->get('area');
        foreach($query->result() as $row){
            $id_area = $row->id_area;
        }
        */
        // Seleccion el id de la tabla producto
        $this->db->select('id_pro');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
        }
        // Seleccionar el stock de acuerdo al producto y al área
        // Actualización del Stock
        /*
        $this->db->select('stock_area_sta_clara,stock_area_sta_anita,id_detalle_producto_area');
        $this->db->where('id_area',$id_area);
        $this->db->where('id_pro',$id_pro);
        $query = $this->db->get('detalle_producto_area');
        foreach($query->result() as $row){
            $stock_area_sta_clara = $row->stock_area_sta_clara;
            $stock_area_sta_anita = $row->stock_area_sta_anita;
            $id_detalle_producto_area = $row->id_detalle_producto_area;
        }

        if($almacen == 1){
            $stock_actualizado = $stock_area_sta_clara + $cantidad_ingreso;
            $actualizar_stock_area = array(
                'stock_area_sta_clara'=> $stock_actualizado
            );
        }else if($almacen == 2){
            $stock_actualizado = $stock_area_sta_anita + $cantidad_ingreso;
            $actualizar_stock_area = array(
                'stock_area_sta_anita'=> $stock_actualizado
            );
        }
        $this->db->where('id_detalle_producto_area',$id_detalle_producto_area);
        $this->db->update('detalle_producto_area', $actualizar_stock_area);
        */
        /* Actualización del Stock y Precio Unitario */
        $this->db->select('stock,precio_unitario,stock_referencial_sta_anita');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $stock_sta_anita_actual = $row->stock; /* Stock de Sta anita */
            $precio_unitario = $row->precio_unitario;
            $stock_referencial_sta_anita = $row->stock_referencial_sta_anita; /* Stock Referencial de Sta anita */
        }

        if($almacen == 2){ /* Sta. Anita */
            $stock_general = $stock_sta_anita_actual; /* Stock general actual */
            $stock_sta_anita = $stock_sta_anita_actual + $cantidad_ingreso; /* Stock actualizado del almacen de sta anita */
            $stock_actualizado = $stock_general + $cantidad_ingreso; /* stock general + la cantidad de ingreso en la factura - Kardex */
            if($id_comprobante == 4){
                $nuevo_precio_unitario = $precio_unitario;
            }else if($id_comprobante == 2){
                $nuevo_precio_unitario = (($stock_general*$precio_unitario)+($cantidad_ingreso*$p_u_gastos))/($stock_general+$cantidad_ingreso);
                $nuevo_precio_unitario = @number_format($nuevo_precio_unitario, 2, '.', '');
            }
            $actualizar = array(
                'stock'=> $stock_sta_anita,
                'stock_referencial_sta_anita'=> $stock_sta_anita_actual,
                'precio_unitario'=> $nuevo_precio_unitario,
                'precio_unitario_referencial'=> $nuevo_precio_unitario
            );
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
            // Realizar registro para el kardex // en el kardex el precio unitario del producto debe ir en soles */
            if($id_comprobante == 4){
                // Gestión de kardex para el registro
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro <=',$fecharegistro);
                // $this->db->where('id_kardex_producto <',$id_kardex_producto);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                    }
                    // Obtener los datos del ultimo registro de la fecha
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                    $this->db->where('id_kardex_producto',$auxiliar);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual = $row->stock_actual;
                        $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                        $precio_unitario_anterior = $row->precio_unitario_anterior;
                        $descripcion = $row->descripcion;
                    }

                    $new_stock = $stock_actual + $cantidad_ingreso;

                    $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                    'descripcion' => "IMPORTACION",
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'stock_anterior' => $stock_actual,
                                    'precio_unitario_anterior' => $nuevo_precio_unitario,
                                    'cantidad_ingreso' => $cantidad_ingreso,
                                    'stock_actual' => $new_stock,
                                    'precio_unitario_actual_promedio' => $nuevo_precio_unitario,
                                    'precio_unitario_actual' => $nuevo_precio_unitario,
                                    'num_comprobante' => $numcomprobante,
                                    'serie_comprobante' => $seriecomprobante,
                                    );
                    $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                    // $this->db->insert('kardex_producto', $a_data_kardex);
                    // End registro para el kardex
                }else{
                    /* Realizar registro para el kardex */ /* en el kardex el precio unitario del producto debe ir en soles */
                    $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                    'descripcion' => "IMPORTACION",
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'stock_anterior' => 0,
                                    'precio_unitario_anterior' => 0,
                                    'cantidad_ingreso' => $cantidad_ingreso,
                                    'stock_actual' => $cantidad_ingreso,
                                    'precio_unitario_actual_promedio' => $nuevo_precio_unitario,
                                    'precio_unitario_actual' => $nuevo_precio_unitario,
                                    'num_comprobante' => $numcomprobante,
                                    'serie_comprobante' => $seriecomprobante,
                                    );
                    $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                    /* End registro para el kardex */
                }
                // Actualizar movimientos posteriores
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro >',$fecharegistro);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $id_kardex_producto = $row->id_kardex_producto; /* ID del movimiento en el kardex */
                        // Obtener detalle del movimiento
                        $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual');
                        $this->db->where('id_kardex_producto',$id_kardex_producto);
                        $query = $this->db->get('kardex_producto');
                        foreach($query->result() as $row){
                            $stock_actual_act = $row->stock_actual;
                            $precio_unitario_actual_promedio_act = $row->precio_unitario_actual_promedio;
                            $precio_unitario_anterior_act = $row->precio_unitario_anterior;
                            $descripcion_act = $row->descripcion;
                            $stock_anterior_act = $row->stock_anterior;
                            $cantidad_salida_act = $row->cantidad_salida;
                            $cantidad_ingreso_act = $row->cantidad_ingreso;
                            $precio_unitario_actual_act = $row->precio_unitario_actual;
                            // Actualizacion de registros
                            if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                if($auxiliar_contador == 0){
                                    /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                    $new_stock_anterior_act = $new_stock; // stock_anterior
                                    $new_precio_unitario_anterior_act = $nuevo_precio_unitario; // precio_unitario_anterior
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
                                    $new_stock_anterior_act = $new_stock; // stock_anterior
                                    $new_precio_unitario_anterior_act = $nuevo_precio_unitario; // precio_unitario_anterior
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
                                    $new_stock_anterior_act = $new_stock; // stock_anterior
                                    $new_precio_unitario_anterior_act = $nuevo_precio_unitario; // precio_unitario_anterior
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
                            // Dejar variables con el ultimo registro del stock y precio unitario obtenido
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
            }else if($id_comprobante == 2){
                // Gestión de kardex para el registro
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro <=',$fecharegistro);
                // $this->db->where('id_kardex_producto <',$id_kardex_producto);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                    }
                    // Obtener los datos del ultimo registro de la fecha
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                    $this->db->where('id_kardex_producto',$auxiliar);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual = $row->stock_actual;
                        $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                        $precio_unitario_anterior = $row->precio_unitario_anterior;
                        $descripcion = $row->descripcion;
                    }
                    $new_stock = $stock_actual + $cantidad_ingreso;
                    // Registro en la tabla del kardex
                    $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                    'descripcion' => "ENTRADA",
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'stock_anterior' => $stock_actual,
                                    'precio_unitario_anterior' => $precio_unitario_anterior,
                                    'cantidad_ingreso' => $cantidad_ingreso,
                                    'stock_actual' => $new_stock,
                                    'precio_unitario_actual_promedio' => $nuevo_precio_unitario,
                                    'precio_unitario_actual' => $p_u_gastos,
                                    'num_comprobante' => $numcomprobante,
                                    'serie_comprobante' => $seriecomprobante,
                                    );
                    $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                }else{
                    $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                    'descripcion' => "ENTRADA",
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'stock_anterior' => 0,
                                    'precio_unitario_anterior' => 0,
                                    'cantidad_ingreso' => $cantidad_ingreso,
                                    'stock_actual' => $cantidad_ingreso,
                                    'precio_unitario_actual_promedio' => $nuevo_precio_unitario,
                                    'precio_unitario_actual' => $p_u_gastos,
                                    'num_comprobante' => $numcomprobante,
                                    'serie_comprobante' => $seriecomprobante,
                                    );
                    $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                }
                // Actualizar movimientos posteriores
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro >',$fecharegistro);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $id_kardex_producto = $row->id_kardex_producto; /* ID del movimiento en el kardex */
                        // Obtener detalle del movimiento
                        $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual');
                        $this->db->where('id_kardex_producto',$id_kardex_producto);
                        $query = $this->db->get('kardex_producto');
                        foreach($query->result() as $row){
                            $stock_actual_act = $row->stock_actual;
                            $precio_unitario_actual_promedio_act = $row->precio_unitario_actual_promedio;
                            $precio_unitario_anterior_act = $row->precio_unitario_anterior;
                            $descripcion_act = $row->descripcion;
                            $stock_anterior_act = $row->stock_anterior;
                            $cantidad_salida_act = $row->cantidad_salida;
                            $cantidad_ingreso_act = $row->cantidad_ingreso;
                            $precio_unitario_actual_act = $row->precio_unitario_actual;
                            // Actualizacion de registros
                            if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                if($auxiliar_contador == 0){
                                    /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                    $new_stock_anterior_act = $new_stock; // stock_anterior
                                    $new_precio_unitario_anterior_act = $nuevo_precio_unitario; // precio_unitario_anterior
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
                                    $new_stock_anterior_act = $new_stock; // stock_anterior
                                    $new_precio_unitario_anterior_act = $nuevo_precio_unitario; // precio_unitario_anterior
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
                                    $new_stock_anterior_act = $new_stock; // stock_anterior
                                    $new_precio_unitario_anterior_act = $nuevo_precio_unitario; // precio_unitario_anterior
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
                            // Dejar variables con el ultimo registro del stock y precio unitario obtenido
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
            }
        }
        return true;
    }

    public function getCatProdEditar(){
        //Recuperamos el ID  -> 
        $id_categoria_producto = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT categoria.id_categoria,categoria.no_categoria
                FROM categoria
                WHERE categoria.id_categoria=".$id_categoria_producto;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function actualizaCategoriaProducto(){
        //Recuperamos el ID  -> 
        $id_categoria_producto = $this->security->xss_clean($this->uri->segment(3));
        $catprod = strtoupper($this->security->xss_clean($this->input->post('editcatprod')));
        //Verifico si existe
        $this->db->where('no_categoria',$catprod);
        $query = $this->db->get('categoria');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'no_categoria' => $catprod
            );
            $this->db->where('id_categoria',$id_categoria_producto);
            $this->db->update('categoria', $actualizar);
            return true;
        }
    }

    public function eliminarCategoriaProducto($idcategoriaproducto){
        $sql = "DELETE FROM categoria WHERE id_categoria = " . $idcategoriaproducto . "";
        $query = $this->db->query($sql); 
        if($query->num_rows()>0)
            {
                return $query->result();
            }
    }

    public function listarCategoriaProdCombo(){
        $this->db->select('id_categoria,no_categoria');
        $this->db->order_by('no_categoria', 'ASC');           
        $query = $this->db->get('categoria');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_categoria, ENT_QUOTES)] = htmlspecialchars($row->no_categoria, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarTipoProd(){
        $sql = "SELECT tipo_producto.no_tipo_producto,tipo_producto.id_tipo_producto
                FROM tipo_producto";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function saveTipoProducto(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $this->db->where('no_tipo_producto',$nombre);
        $query = $this->db->get('tipo_producto');
        if($query->num_rows()>0){
            return false;
        }else{
            $array = array(
                'no_tipo_producto'=> $nombre
            );
            $this->db->insert('tipo_producto', $array);
            return true;
        }
    }

    public function getTipoProdEditar(){
        //Recuperamos el ID  -> 
        $id_tipo_producto = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT tipo_producto.id_tipo_producto,tipo_producto.no_tipo_producto
                FROM tipo_producto
                WHERE tipo_producto.id_tipo_producto=".$id_tipo_producto;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function actualizaTipoProducto(){
        // Recuperamos el ID
        $id_tipo_producto = $this->security->xss_clean($this->uri->segment(3));
        $no_tipo_producto = strtoupper($this->security->xss_clean($this->input->post('edittipprod')));
        // Verifico si existe
        $this->db->where('no_tipo_producto',$no_tipo_producto);
        $query = $this->db->get('tipo_producto');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'no_tipo_producto' => $no_tipo_producto
            );
            $this->db->where('id_tipo_producto',$id_tipo_producto);
            $this->db->update('tipo_producto', $actualizar);
            return true;
        }
    }

    public function eliminarTipoProducto($id_tipo_producto){
    $sql = "DELETE FROM tipo_producto WHERE id_tipo_producto = " . $id_tipo_producto . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function getTipo(){
        $idcat = $this->security->xss_clean($this->input->post('categoria'));
        //Realizamos la consulta a la Base de Datos
        $sql = "SELECT DISTINCT tipo_producto.id_tipo_producto,tipo_producto.no_tipo_producto FROM categoria
        INNER JOIN tipo_producto ON tipo_producto.id_categoria = categoria.id_categoria
        WHERE categoria.id_categoria = ".$idcat;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

/*
    public function buscador($abuscar)
    {
        //usamos after para decir que empiece a buscar por
        //el principio de la cadena
        //ej SELECT localidad from localidades_es 
        //WHERE localidad LIKE '%$abuscar' limit 12
        $this->db->select('no_producto');
        
        $this->db->like('no_producto',$abuscar);
        
        $resultados = $this->db->get('detalle_producto', 12);
        
        //si existe algún resultado lo devolvemos
        if($resultados->num_rows() > 0)
        {
            
            return $resultados->result();
            
        //en otro caso devolvemos false
        }else{
            
            return FALSE;
            
        }
        
    }

    function get_data($item,$match) { 
        $this->db->like($item, $match); 
        $query = $this->db->get('detalle_producto'); 
        return $query->result();          
    }
*/

    function get_nombre_proveedor_autocomplete($nombre_proveedor){
        try {
            $filtro = "";
            $filtro .= "LIMIT 10";
            $sql = "SELECT proveedor.id_proveedor,proveedor.razon_social,proveedor.ruc,proveedor.pais,proveedor.direccion,proveedor.telefono1
                    FROM proveedor
                    WHERE proveedor.razon_social ILIKE '%".$nombre_proveedor."%'".$filtro;
            $query = $this->db->query($sql);

            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function get_nombre_producto_autocomplete($nombre_producto){
        try {
            $filtro = "";
            $filtro .= " AND producto.estado = TRUE ";
            $filtro .= "LIMIT 10";
            $sql = "SELECT DISTINCT detalle_producto.no_producto,detalle_producto.stock,unidad_medida.nom_uni_med
                    FROM producto
                    INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                    WHERE detalle_producto.no_producto ILIKE '%".$nombre_producto."%'".$filtro;
            $query = $this->db->query($sql);

            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function get_nombre_producto_autocomplete_traslado($nombre_producto, $id_area){
        try {
            $filtro = "";
            //$filtro .= " AND detalle_producto.stock > 0 ";
            $filtro .= " AND detalle_producto_area.id_area =".(int)$id_area;
            $filtro .= " AND producto.estado = TRUE ";
            $filtro .= "LIMIT 10";
            $sql = "SELECT DISTINCT detalle_producto.no_producto,unidad_medida.nom_uni_med,producto.column_temp,
                    detalle_producto_area.stock_area_sta_anita,detalle_producto_area.stock_area_sta_clara,
                    detalle_producto_area.id_area,detalle_producto.id_detalle_producto
                    FROM producto
                    INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                    INNER JOIN detalle_producto_area ON detalle_producto_area.id_pro = producto.id_pro
                    INNER JOIN area ON detalle_producto_area.id_area = area.id_area
                    WHERE detalle_producto.no_producto ILIKE '%".$nombre_producto."%'".$filtro;
            $query = $this->db->query($sql);
            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function get_nombre_producto_autocomplete_area($nombre_producto, $id_area){
        try {
            $filtro = "";
            $filtro .= " AND detalle_producto_area.id_area =".(int)$id_area;
            $filtro .= " AND producto.estado = TRUE ";
            $filtro .= " LIMIT 10";
            $sql = "SELECT DISTINCT detalle_producto.no_producto,unidad_medida.nom_uni_med,producto.column_temp,
                    detalle_producto_area.stock_area_sta_anita,detalle_producto_area.stock_area_sta_clara,detalle_producto_area.id_area
                    FROM producto
                    INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                    INNER JOIN detalle_producto_area ON detalle_producto_area.id_pro = producto.id_pro
                    INNER JOIN area ON detalle_producto_area.id_area = area.id_area
                    WHERE detalle_producto.no_producto ILIKE '%".$nombre_producto."%'".$filtro;
            $query = $this->db->query($sql);

            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function get_nombre_producto_autocomplete_consultar_salidas($nombre_producto){
        try {
            $filtro = "";
            $filtro .= " AND producto.estado = TRUE ";
            $filtro .= " LIMIT 10";
            $sql = "SELECT DISTINCT detalle_producto.no_producto, producto.estado
                    FROM detalle_producto
                    INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    WHERE detalle_producto.no_producto ILIKE '%".$nombre_producto."%'".$filtro;
            $query = $this->db->query($sql);

            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function get_nombre_producto_autocomplete_salida($nombre_producto){
        try {
            $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
            $filtro = "";
            $filtro .= " AND producto.estado = TRUE ";
            $filtro .= "LIMIT 10";
            $sql = "SELECT DISTINCT detalle_producto.no_producto,unidad_medida.nom_uni_med
                    FROM producto
                    INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                    WHERE detalle_producto.no_producto ILIKE '%".$nombre_producto."%'".$filtro;
            $query = $this->db->query($sql);

            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function get_nombre_producto_autocomplete_with_id($nombre_producto){
        try {
            $filtro = "";
            $filtro .= "LIMIT 10";
            //$filtro .= " AND detalle_producto.stock > 0 ";
            //$filtro .= " AND producto.estado = TRUE ";
            $sql = "SELECT detalle_producto.no_producto,detalle_producto.stock,unidad_medida.nom_uni_med,detalle_producto.id_detalle_producto
                    FROM producto
                    INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                    WHERE detalle_producto.no_producto ILIKE '%".$nombre_producto."%'".$filtro;
            $query = $this->db->query($sql);

            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    public function getDataUnidadMedida($nombre_producto){
        //echo "<script languaje='javascript'>alert('Hola')</script>";
        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
        }
        
        $sql = "SELECT DISTINCT detalle_producto.id_detalle_producto,unidad_medida.nom_uni_med
                FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                WHERE detalle_producto.id_detalle_producto = ".$id_detalle_producto;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function getNombreProducto_autocompletar(){
        $filtro="";
        $filtro = "LIMIT 10";
        $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
        if( $nombre_producto != ""){
            $sql = "SELECT detalle_producto.no_producto
                    FROM producto 
                    INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    WHERE detalle_producto.no_producto ILIKE '%".$nombre_producto."%'".$filtro;
            $query = $this->db->query($sql);
            if($query->num_rows()>0){
                return $query->result();
            }
        }
    }

    public function listarMarca(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $this->db->where('id_almacen',$almacen);
        $this->db->select('id_marca_maquina,no_marca');
        $this->db->order_by('no_marca', 'ASC');           
        $query = $this->db->get('marca_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_marca_maquina, ENT_QUOTES)] = htmlspecialchars($row->no_marca, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function getDataStock($nombre_producto){
        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$nombre_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
        }
        
        $filtro = "";
        $filtro .= " AND detalle_producto.id_detalle_producto ='".$id_detalle_producto."'";
        $filtro .= " LIMIT 1";
        
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto,
                detalle_producto.stock,detalle_producto.precio_unitario
                FROM detalle_producto
                WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function getDataStock_general_cuadre($nombre_producto){
        $filtro = "";
        $filtro .= " AND detalle_producto.no_producto ='".$nombre_producto."'";
        $filtro .= " LIMIT 1";
        
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto,detalle_producto.stock,detalle_producto.precio_unitario
                FROM detalle_producto
                WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarProducto(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe el nombre del producto
        if($this->input->post('nombre')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }
        /*
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        /* $filtro .= " AND producto.estado = TRUE "; */
        $filtro .= " LIMIT 50";
        $sql = "SELECT DISTINCT producto.id_pro,producto.observacion,tipo_producto.no_tipo_producto,categoria.no_categoria,detalle_producto.no_producto,
                procedencia.no_procedencia,unidad_medida.nom_uni_med,ubicacion.nombre_ubicacion,detalle_producto.stock
                FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
                INNER JOIN tipo_producto ON producto.id_tipo_producto = tipo_producto.id_tipo_producto
                INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                LEFT JOIN ubicacion ON producto.id_ubicacion = ubicacion.id_ubicacion
                WHERE producto.id_pro IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function get_producto_insert_last($nombre_producto){
        $filtro = "";
        $filtro .= " AND detalle_producto.no_producto ='".$nombre_producto."'";
        $sql = "SELECT DISTINCT producto.id_pro,producto.observacion,tipo_producto.no_tipo_producto,categoria.no_categoria,detalle_producto.no_producto,
                procedencia.no_procedencia,unidad_medida.nom_uni_med,ubicacion.nombre_ubicacion,detalle_producto.stock
                FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
                INNER JOIN tipo_producto ON producto.id_tipo_producto = tipo_producto.id_tipo_producto
                INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                LEFT JOIN ubicacion ON producto.id_ubicacion = ubicacion.id_ubicacion
                WHERE producto.id_pro IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarModelo(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $this->db->where('id_almacen',$almacen);
        $this->db->select('id_modelo_maquina,no_modelo');
        $this->db->order_by('no_modelo', 'ASC');           
        $query = $this->db->get('modelo_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_modelo_maquina, ENT_QUOTES)] = htmlspecialchars($row->no_modelo, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarSerie(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $this->db->where('id_almacen',$almacen);
        $this->db->select('id_serie_maquina,no_serie');
        $this->db->order_by('no_serie', 'ASC');           
        $query = $this->db->get('serie_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_serie_maquina, ENT_QUOTES)] = htmlspecialchars($row->no_serie, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarEstado(){
        $this->db->select('id_estado_maquina,no_estado_maquina');
        $this->db->order_by('no_estado_maquina', 'ASC');           
        $query = $this->db->get('estado_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_estado_maquina, ENT_QUOTES)] = htmlspecialchars($row->no_estado_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarProcedencia(){
        $this->db->select('id_procedencia,no_procedencia');
        $this->db->order_by('no_procedencia', 'ASC');           
        $query = $this->db->get('procedencia');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_procedencia, ENT_QUOTES)] = htmlspecialchars($row->no_procedencia, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function saveProducto(){
        /* Aplicar transacciones */
        try{
            // Inicio de transaccion
            $this->db->trans_begin();
            // $codigopro = strtoupper($this->security->xss_clean($this->input->post('codigopro')));
            $ubicacion_producto = $this->security->xss_clean($this->input->post('ubicacion_producto'));
            $categoria = $this->security->xss_clean($this->input->post('categoria'));
            $procedencia = $this->security->xss_clean($this->input->post('procedencia'));
            $observacion = strtoupper($this->security->xss_clean($this->input->post('obser')));
            $uni_med = strtoupper($this->security->xss_clean($this->input->post('uni_med')));
            $almacen = $this->security->xss_clean($this->session->userdata('almacen')); // Variable de sesion
            $nombrepro = strtoupper($this->security->xss_clean($this->input->post('nombrepro')));
            $id_tipo_producto = strtoupper($this->security->xss_clean($this->input->post('tipo_producto')));
            $area = $this->security->xss_clean($this->input->post('area'));
            // Obtener el id de la ubicacion del producto
            $this->db->select('id_ubicacion');
            $this->db->where('nombre_ubicacion',$ubicacion_producto);
            $query_ubicacion = $this->db->get('ubicacion');
            if($query_ubicacion->num_rows() == 0){
                return 'ubicacion_no_existe';
            }else{
                foreach($query_ubicacion->result() as $row){
                    $id_ubicacion = $row->id_ubicacion;
                }
                // Obtener el ID de la unidad de medida registrada
                $this->db->select('id_unidad_medida');
                $this->db->where('nom_uni_med',$uni_med);
                $query = $this->db->get('unidad_medida');
                if($query->num_rows() == 0){
                    return 'unidad_no_existe';
                }else{
                    // Obtengo el ID de la Unidad de Medida
                    foreach($query->result() as $row){
                        $id_unidad_medida = $row->id_unidad_medida;
                    }
                    // Verifico si existe un registro del mismo nombre
                    $filtro = "";
                    $filtro .= " AND detalle_producto.no_producto ='".$nombrepro."'";
                    $sql = "SELECT detalle_producto.no_producto,producto.id_pro
                    FROM producto
                    INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    WHERE producto.id_pro IS NOT NULL".$filtro;
                    $query = $this->db->query($sql);
                    if($query->num_rows()>0){
                        return 'nombre_producto';
                    }else{
                        /* Obtener el indice actual */
                        $sql = "SELECT Max(producto.indice) AS indice_mayor FROM producto";
                        $query = $this->db->query($sql);
                        foreach ($query->result() as $row) {
                            $indice_mayor = $row->indice_mayor;
                        }
                        /* Obtener cuantos registros se tiene con el indice actual */
                        $sql = "SELECT Count(producto.indice) AS cant_indices FROM producto WHERE indice=".$indice_mayor."";
                        $query = $this->db->query($sql);
                        foreach ($query->result() as $row) {
                            $cant_indices = $row->cant_indices;
                        }

                        if($cant_indices < 500){
                            $indice_mayor = $indice_mayor;
                        }else if($cant_indices >= 500){
                            $indice_mayor = $indice_mayor + 1;
                        }

                        // Registro de Producto
                        $registroini = array(
                            'no_producto'=> $nombrepro
                        );
                        $this->db->insert('detalle_producto', $registroini);

                        $this->db->select('id_detalle_producto');
                        $this->db->where('no_producto',$nombrepro);
                        $query_4 = $this->db->get('detalle_producto');
                        foreach($query_4->result() as $row){
                            $id_dp = $row->id_detalle_producto;
                        }

                        if($id_dp != ""){
                            // Registro de datos del producto
                            $registro = array(
                                'id_categoria'=> $categoria,
                                'id_procedencia'=> $procedencia,
                                'observacion'=> $observacion,
                                'id_almacen'=> $almacen,
                                'id_detalle_producto'=> $id_dp,
                                'id_unidad_medida'=> $id_unidad_medida,
                                'id_tipo_producto'=> $id_tipo_producto,
                                'indice'=> $indice_mayor,
                                'id_ubicacion'=> $id_ubicacion
                            );
                            $this->db->insert('producto', $registro);
                            // - Fin de registro -

                            // Validación de registro en la tabla Producto
                            //echo '1'; No imprime cuando se produce un error en la funcion de inserción de arriba
                            $this->db->select('id_pro');
                            $this->db->where('id_detalle_producto',$id_dp);
                            $query_5 = $this->db->get('producto');
                            foreach($query_5->result() as $row){
                                $id_pro = $row->id_pro;
                            }
                            if($id_pro != ""){
                                /*  Fin de transaccion */
                                $this->db->trans_complete();
                                /* Retornar parámetro */
                                return 'registro_correcto';
                            }else{
                                $sql = "DELETE FROM detalle_producto WHERE no_producto = " . $no_producto . "";
                                $query = $this->db->query($sql);
                                if($query->num_rows()>0)
                                { return id_null; }
                            }
                        }else{
                            $sql = "DELETE FROM detalle_producto WHERE no_producto = " . $no_producto . "";
                            $query = $this->db->query($sql);
                            if($query->num_rows()>0)
                            { return id_null; }
                        }
                    }
                }
            }
        }catch(Exception $e){
            return 'error_registro';
        }   
    }

    public function saveTipoCambio_vista(){
        $fecha_registro = $this->security->xss_clean($this->input->post('fecha_registro'));
        $dolar_compra_reg = $this->security->xss_clean($this->input->post('dolar_compra_reg'));
        $dolar_venta_reg = $this->security->xss_clean($this->input->post('dolar_venta_reg'));
        $euro_compra_reg = $this->security->xss_clean($this->input->post('euro_compra_reg'));
        $euro_venta_reg = $this->security->xss_clean($this->input->post('euro_venta_reg'));
        //Verifico si existe
        $this->db->where('fecha_actual',$fecha_registro);
        $query = $this->db->get('tipo_cambio');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Producto
            $registro = array(
                'fecha_actual'=>$fecha_registro, 
                'dolar_compra'=>$dolar_compra_reg, 
                'dolar_venta'=>$dolar_venta_reg,
                'euro_compra'=>$euro_compra_reg, 
                'euro_venta'=>$euro_venta_reg
            );
            $this->db->insert('tipo_cambio', $registro);
            return true;
        }
    }

    function get_ubicacion_producto_autocomplete($nombre_ubicacion){
        try {
            $this->db->select("ubicacion.id_ubicacion,ubicacion.nombre_ubicacion");
            $this->db->from("ubicacion");
            $this->db->where("ubicacion.nombre_ubicacion like",$nombre_ubicacion."%");
            $this->db->limit(10);
            
            $query = $this->db->get();

            $a_data = $query->result_array();

            return $a_data;

        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    function get_unidad_medida_autocomplete($unidad_medida){
        try {
            $this->db->select("unidad_medida.id_unidad_medida,unidad_medida.nom_uni_med");
            $this->db->from("unidad_medida");
            $this->db->where("unidad_medida.nom_uni_med like",$unidad_medida."%");
            $this->db->limit(10);
            
            $query = $this->db->get();

            $a_data = $query->result_array();

            return $a_data;

        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    
    public function saveRegistroIngreso(){
        $cantidad = $this->security->xss_clean($this->input->post('cantidad'));
        $pu = $this->security->xss_clean($this->input->post('pu'));
        $nomproducto = $this->security->xss_clean($this->input->post('nomproducto'));
        $pt = $this->security->xss_clean($this->input->post('pt'));
        $numcomprobante = $this->security->xss_clean($this->input->post('numcomprobante'));
        //$comprobante = $this->security->xss_clean($this->input->post('comprobante'));
        
        //Verifico si existe
        $this->db->where('id_detalle_producto',$nomproducto);
        $this->db->where('id_ingreso_producto',$numcomprobante);
        $query = $this->db->get('detalle_ingreso_producto');
        if($query->num_rows()>0){
            return false;
        }else{

            //Actualización
            $this->db->select('stock');
            $this->db->where('id_detalle_producto',$nomproducto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $stock = $row->stock;
            }
            $stock = $stock + $cantidad;
            $actualizar = array(
                'stock'=> $stock,
                'precio_unitario'=> $pu
            );
            $this->db->where('id_detalle_producto',$nomproducto);
            $this->db->update('detalle_producto', $actualizar);

            //Registro
            $registro = array(
                'unidades'=> $cantidad,
                'id_detalle_producto'=> $nomproducto,  
                'precio_parcial'=> $pt,
                'id_ingreso_producto'=> $numcomprobante
            );
            $this->db->insert('detalle_ingreso_producto', $registro);
            return true;
        }
    }


    /*
    public function getSearchResultados($nombre){
        $output='';
        if($nombre != ''){
            $this->db->select("no_producto");
            $this->db->from("detalle_producto");
            $this->db->like("no_producto",$nombre,'after'); //despues de la una letra, cualquier cadena
            $this->db->limit(5, 0);
            $query = $this->db->get();
            if($query->num_rows() > 0){
                foreach ($query->result() as $fila) {
                    $output .= "<li>".$fila->nombre."</li>";
                }
            }
            $query->free_result();
        }
        return $output;
    }
    */

    public function saveRegistroSalida(){
        $id_maquina = $this->security->xss_clean($this->input->post('maquina'));
        $id_marca = $this->security->xss_clean($this->input->post('marca'));
        $id_modelo = $this->security->xss_clean($this->input->post('modelo'));
        $id_serie = $this->security->xss_clean($this->input->post('serie'));
        $id_area = $this->security->xss_clean($this->input->post('area'));
        $solicitante = strtoupper($this->security->xss_clean($this->input->post('solicitante')));
        $fecharegistro = $this->security->xss_clean($this->input->post('fecharegistro'));
        $id_detalle_producto = $this->security->xss_clean($this->input->post('nomproducto'));
        $cantidad = $this->security->xss_clean($this->input->post('cantidad'));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        //Verifico si existe stock disponible
        $this->db->select('stock');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $stock = $row->stock;
        }
        if($stock < $cantidad){
            return false;
        }else{
            //Actualización
            $this->db->select('stock');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $stock = $row->stock;
            }
            $stock = $stock - $cantidad;
            $actualizar = array(
                'stock'=> $stock
            );
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);

            //Registro
            $registro = array(
                'id_nombre_maquina'=> $id_maquina,
                'id_marca'=> $id_marca,  
                'id_modelo'=> $id_modelo,
                'id_serie'=> $id_serie,
                'id_area'=> $id_area,
                'solicitante'=> $solicitante,
                'fecha'=> $fecharegistro,
                'id_detalle_producto'=> $id_detalle_producto,
                'cantidad_salida'=> $cantidad,
                'id_almacen'=> $almacen
            );
            $this->db->insert('salida_producto', $registro);
            return true;
        }
    }

    public function getStock()
    {
        $nomproducto = $this->security->xss_clean($this->input->post('nomproducto'));
        $this->db->select('stock');
        $this->db->where('id_detalle_producto',$nomproducto);
        $query = $this->db->get('detalle_producto');
        if($query->num_rows()>0)
        {
            return $query->result();        
        }
    }

    public function getEncargado()
    {
        $area = $this->security->xss_clean($this->input->post('area'));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        $this->db->select('encargado');
        $this->db->where('id_area',$area);
        //$this->db->where('id_almacen',$almacen);
        $query = $this->db->get('area');
        if($query->num_rows()>0)
        {
            return $query->result();        
        }
    } 

    public function getUnidadMedida()
    {
        $nomproducto = $this->security->xss_clean($this->input->post('nomproducto'));
        $this->db->select('unidad_medida');
        $this->db->where('id_detalle_producto',$nomproducto);
        $query = $this->db->get('producto');
        if($query->num_rows()>0)
        {
            return $query->result();        
        }
    }

    public function saveMaquina(){
        $nombre_maquina_modal = $this->security->xss_clean($this->input->post('nombre_maquina_modal'));                        
        $estado = $this->security->xss_clean($this->input->post('estado'));
        $observacion = $this->security->xss_clean($this->input->post('obser'));        
        // Validacion de duplicidad
        $this->db->where('nombre_maquina',$nombre_maquina_modal);
        $query = $this->db->get('maquina');
        if($query->num_rows() > 0){
            return 'duplicidad';
        }else{
            $registro = array(
                'nombre_maquina'=> strtoupper($nombre_maquina_modal),  
                'id_estado_maquina'=> $estado,
                'observacion_maq'=> $observacion
            );
            $this->db->insert('maquina', $registro);
            return 'successfull';
        }
    }

    public function save_parte_Maquina(){
        $parte_maquina_modal = $this->security->xss_clean($this->input->post('parte_maquina_modal'));
        $id_maquina = $this->security->xss_clean($this->input->post('maquina'));
        // Validacion de duplicidad
        $this->db->where('nombre_parte_maquina',$parte_maquina_modal);
        $this->db->where('id_maquina',$id_maquina);
        $query = $this->db->get('parte_maquina');
        if($query->num_rows() > 0){
            return 'duplicidad';
        }else{
            $registro = array(
                'nombre_parte_maquina'=> strtoupper($parte_maquina_modal),
                'id_maquina'=> $id_maquina
            );
            $this->db->insert('parte_maquina', $registro);
            return 'successfull';
        }
    }

    function actualiza_parte_Maquina(){
        // Recuperamos el ID
        $id_parte_maquina = $this->security->xss_clean($this->uri->segment(3));
        $edit_parte_maquina = $this->security->xss_clean($this->input->post('edit_parte_maquina'));
        $edit_maquina = $this->security->xss_clean($this->input->post('edit_maquina'));
        // Validacion de duplicidad
        $this->db->where('nombre_parte_maquina',$edit_parte_maquina);
        $this->db->where('id_maquina',$edit_maquina);
        $query = $this->db->get('parte_maquina');
        if($query->num_rows() > 0){
            return 'duplicidad';
        }else{
            $actualizar = array(
                'nombre_parte_maquina' => strtoupper($edit_parte_maquina),
                'id_maquina'=> $edit_maquina
            );
            $this->db->where('id_parte_maquina',$id_parte_maquina);
            $this->db->update('parte_maquina', $actualizar);
            return 'successfull';
        }
    }

    public function get_parte_maquina(){
        $id_maquina = $this->security->xss_clean($this->input->post('id_maquina'));
        // Realizamos la consulta a la Base de Datos
        $sql = "SELECT DISTINCT parte_maquina.id_parte_maquina,parte_maquina.nombre_parte_maquina,maquina.nombre_maquina,maquina.id_maquina
        FROM parte_maquina
        LEFT JOIN maquina ON parte_maquina.id_maquina = maquina.id_maquina
        WHERE maquina.id_maquina = ".$id_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result();
        }
    }

    public function saveNombreMaquina(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        //$nombrev = strtoupper($nombre);
        //Verifico si existe
        $this->db->where('id_almacen',$almacen);
        $this->db->where('nombre_maquina',$nombre);
        $query = $this->db->get('nombre_maquina');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'nombre_maquina'=> $nombre,
                'id_almacen'=> $almacen
            );
            $this->db->insert('nombre_maquina', $registro);
            return true;
        }
    }

    public function saveMarcaMaquina(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $id_nombre_maquina = $this->security->xss_clean($this->input->post('maquina'));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        //$nombrev = strtoupper($nombre);
        //Verifico si existe
        $this->db->where('id_almacen',$almacen);
        $this->db->where('no_marca',$nombre);
        $this->db->where('id_nombre_maquina',$id_nombre_maquina);
        $query = $this->db->get('marca_maquina');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'no_marca'=> $nombre,
                'id_nombre_maquina'=> $id_nombre_maquina,
                'id_almacen'=> $almacen
            );
            $this->db->insert('marca_maquina', $registro);
            return true;
        }
    }

    public function saveArea(){
        $encargado = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $area = strtoupper($this->security->xss_clean($this->input->post('area')));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        // $nombrev = strtoupper($nombre);
        // Verifico si existe
        $this->db->where('id_almacen',$almacen);
        $this->db->where('no_area',$area);
        $query = $this->db->get('area');
        if($query->num_rows()>0){
            return false;
        }else{
            // Registro de nombre de area
            if( $almacen == 1 ){
                $registro = array(
                    'no_area'=> $area,
                    'encargado_sta_clara'=> $encargado,
                    'id_almacen'=> $almacen
                );
                $this->db->insert('area', $registro);
            }else if( $almacen == 2 ){
                $registro = array(
                    'no_area'=> $area,
                    'encargado'=> $encargado,
                    'id_almacen'=> $almacen
                );
                $this->db->insert('area', $registro);
            }
            return true;
        }
    }

    public function saveModeloMaquina(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $id_marca_maquina = $this->security->xss_clean($this->input->post('marca'));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        //$nombrev = strtoupper($nombre);
        //Verifico si existe
        $this->db->where('id_almacen',$almacen);
        $this->db->where('no_modelo',$nombre);
        $query = $this->db->get('modelo_maquina');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'no_modelo'=> $nombre,
                'id_marca_maquina'=> $id_marca_maquina,
                'id_almacen'=> $almacen
            );
            $this->db->insert('modelo_maquina', $registro);
            return true;
        }
    }

    public function saveSerieMaquina(){
        $serie = strtoupper($this->security->xss_clean($this->input->post('serie')));
        $id_modelo_maquina = $this->security->xss_clean($this->input->post('modelo'));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        //$nombrev = strtoupper($nombre);
        //Verifico si existe
        $this->db->where('id_almacen',$almacen);
        $this->db->where('no_serie',$serie);
        $query = $this->db->get('serie_maquina');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'no_serie'=> $serie,
                'id_modelo_maquina'=> $id_modelo_maquina,
                'id_almacen'=> $almacen
            );
            $this->db->insert('serie_maquina', $registro);
            return true;
        }
    }

    public function saveMoneda(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $simbolo = strtoupper($this->security->xss_clean($this->input->post('simbolo')));
        //$nombrev = strtoupper($nombre);
        //Verifico si existe
        $this->db->where('no_moneda',$nombre);
        $query = $this->db->get('moneda');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'no_moneda'=> $nombre,
                'simbolo_mon'=> $simbolo
            );
            $this->db->insert('moneda', $registro);
            return true;
        }
    }

    public function saveAgente(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        //$nombrev = strtoupper($nombre);
        //Verifico si existe
        $this->db->where('no_agente',$nombre);
        $this->db->where('id_almacen',$almacen);
        $query = $this->db->get('agente_aduana');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'no_agente'=> $nombre,
                'id_almacen'=> $almacen
            );
            $this->db->insert('agente_aduana', $registro);
            return true;
        }
    }

    public function saveComprobante(){
        $nombre = strtoupper($this->security->xss_clean($this->input->post('nombre')));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        //$nombrev = strtoupper($nombre);
        //Verifico si existe
        $this->db->where('no_comprobante',$nombre);
        $query = $this->db->get('comprobante');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Nombre de Máquina
            $registro = array(
                'no_comprobante'=> $nombre,
                'id_almacen'=> $almacen
            );
            $this->db->insert('comprobante', $registro);
            return true;
        }
    }

    public function saveProveedor(){
        $ruc = $this->security->xss_clean($this->input->post('ruc'));
        $rz = strtoupper($this->security->xss_clean($this->input->post('rz')));
        $pais = strtoupper($this->security->xss_clean($this->input->post('pais')));
        $direccion = strtoupper($this->security->xss_clean($this->input->post('direccion')));
        $telefono1 = $this->security->xss_clean($this->input->post('telefono1'));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        if( $ruc == ""){
            $ruc = 0;
        }
        // Verifico si existe
        $this->db->where('ruc',$ruc);
        $query = $this->db->get('proveedor');
        if($query->num_rows() > 0){
            return false;
        }else{
            if( $ruc == 0){
                $ruc = null;
            }
            $registro = array(
                'razon_social'=> $rz,
                'ruc'=> $ruc,
                'pais'=> $pais,
                'direccion'=> $direccion,
                'telefono1'=> $telefono1,
                'id_almacen'=> $almacen,
            );
            $this->db->insert('proveedor', $registro);
            return true;
        }
    }

    function saveSalidaProducto($a_data,$return_id = false){
        try{
            $this->db->insert('salida_producto',$a_data);
            if($return_id){
                //return 'partida_registrada';
                return $this->db->insert_id();
            }else{
                return 'error_inesperado';
            }
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    function save_salida_detalle_producto($a_data,$return_id = false){
        try{
            $this->db->insert('detalle_salida_producto',$a_data);
            if($return_id){
                //return 'partida_registrada';
                return $this->db->insert_id();
            }else{
                return 'error_inesperado';
            }
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    function saveSalidaProductoKardex($a_data_kardex,$return_id = false){
        try{
            $this->db->insert('kardex_producto',$a_data_kardex);
            if($return_id){
                //return 'registro_kardex_successful';
                return $this->db->insert_id();
            }else{
                return 'error_inesperado';
            }
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    function descontarStock($id_detalle_producto,$cantidad,$stock_actual,$id_almacen, $id_area){
        try{
            $stock_actualizado = $stock_actual - $cantidad;
            if($id_almacen == 1){
                $actualizar = array(
                    'stock_sta_clara' => $stock_actualizado
                );
            }else if($id_almacen == 2){
                $actualizar = array(
                    'stock' => $stock_actualizado
                );
            }  
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
            return 'descuento_successfull';
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    function descontarStock_general($id_detalle_producto,$cantidad,$stock_actual,$id_almacen){
        try{
            $stock_actualizado = $stock_actual - $cantidad;
            //var_dump($stock_actualizado);
            if($id_almacen == 1){
                $actualizar = array(
                    'stock_sta_clara' => $stock_actualizado
                );
            }else if($id_almacen == 2){
                $actualizar = array(
                    'stock' => $stock_actualizado
                );
            }  
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
            return 'descuento_successfull';
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    function descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actual,$id_almacen, $id_area){
        try{
            $stock_actualizado = $stock_actual;
            if($id_almacen == 1){
                $actualizar = array(
                    'stock_sta_clara' => $stock_actualizado
                );
            }else if($id_almacen == 2){
                $actualizar = array(
                    'stock' => $stock_actualizado
                );
            }  
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
            
            // Regresar el stock del producto por el criterio del area
            // Seleccion el id de la tabla producto
            $this->db->select('id_pro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('producto');
            foreach($query->result() as $row){
                $id_pro = $row->id_pro;
            }
            // Seleccionar el stock de acuerdo al producto y al área
            // Actualización del Stock
            $this->db->select('stock_area_sta_clara,stock_area_sta_anita,id_detalle_producto_area');
            $this->db->where('id_area',$id_area);
            $this->db->where('id_pro',$id_pro);
            $query = $this->db->get('detalle_producto_area');
            foreach($query->result() as $row){
                $stock_area_sta_clara = $row->stock_area_sta_clara;
                $stock_area_sta_anita = $row->stock_area_sta_anita;
                $id_detalle_producto_area = $row->id_detalle_producto_area;
            }

            if($id_almacen == 1){
                $stock_actualizado = $stock_area_sta_clara + $cantidad;
                $actualizar_stock_area = array(
                    'stock_area_sta_clara'=> $stock_actualizado
                );
            }else if($id_almacen == 2){
                $stock_actualizado = $stock_area_sta_anita + $cantidad;
                $actualizar_stock_area = array(
                    'stock_area_sta_anita'=> $stock_actualizado
                );
            }
            $this->db->where('id_detalle_producto_area',$id_detalle_producto_area);
            $this->db->update('detalle_producto_area', $actualizar_stock_area);

            return 'descuento_successfull';
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    function update_stock_general_cuadre($id_detalle_producto,$stock_actual,$id_almacen){
        try{
            $stock_actualizado = $stock_actual;
            if($id_almacen == 1){
                $actualizar = array(
                    'stock_sta_clara' => $stock_actualizado
                );
            }else if($id_almacen == 2){
                $actualizar = array(
                    'stock' => $stock_actualizado
                );
            }  
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);

            return 'descuento_successfull';
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    function actualizarEstado($id_detalle_producto){
        try{

            $actualizar = array(
                'estado' => 'FALSE'
            );
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('producto', $actualizar);
            return 'descuento_successfull';
        }catch(Exception $e){
            throw new Exception("Error Inesperado");
            return false;
        }
    }

    public function listarTipoCambio(){
        $filtro = "";
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(tipo_cambio.fecha_actual) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'";
        }
        // $filtro .= " LIMIT 10";
        $sql = "SELECT tipo_cambio.fecha_actual,tipo_cambio.dolar_compra,tipo_cambio.dolar_venta,tipo_cambio.euro_compra,
        tipo_cambio.euro_venta,tipo_cambio.id_tipo_cambio
        FROM tipo_cambio
        WHERE tipo_cambio.id_tipo_cambio IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarProductoFiltro(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        //si selecciona la categoria del producto
        if($this->input->post('categoria')){
            $filtro .= " AND categoria.id_categoria =".(int)$this->security->xss_clean($this->input->post('categoria')); 
        }
        //si selecciona la PROCEDENCIA del producto
        if($this->input->post('procedencia')){
            $filtro .= " AND procedencia.id_procedencia =".(int)$this->security->xss_clean($this->input->post('procedencia')); 
        }
        //si selecciona EL TIPO DE MAQUINA PARA LA CUAL ENTRO EL PRODUCTO
        if($this->input->post('maquina')){
            $filtro .= " AND producto.id_nombre_maquina =".(int)$this->security->xss_clean($this->input->post('maquina')); 
        }
        if($this->input->post('marca')){
            $filtro .= " AND producto.id_marca_maquina =".(int)$this->security->xss_clean($this->input->post('marca')); 
        }
        if($this->input->post('modelo')){
            $filtro .= " AND producto.id_modelo_maquina =".(int)$this->security->xss_clean($this->input->post('modelo')); 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT producto.id_pro, producto.id_producto,detalle_producto.no_producto,detalle_producto.stock,
        categoria.no_categoria, procedencia.no_procedencia,detalle_producto.precio_unitario,
        producto.observacion, producto.unidad_medida, nombre_maquina.nombre_maquina FROM producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        INNER JOIN nombre_maquina ON producto.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE producto.id_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarProductoGeneral(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        //si selecciona EL TIPO DE MAQUINA PARA LA CUAL ENTRO EL PRODUCTO
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT producto.id_pro, producto.id_producto,detalle_producto.no_producto,detalle_producto.stock,
        categoria.no_categoria, procedencia.no_procedencia,detalle_producto.precio_unitario,
        producto.observacion, producto.unidad_medida, nombre_maquina.nombre_maquina FROM producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        INNER JOIN nombre_maquina ON producto.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE producto.id_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistros(){
        $filtro = "";
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(ingreso_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        if($this->input->post('num_factura')){
            $filtro .= " AND (ingreso_producto.nro_comprobante ILIKE '%".$this->security->xss_clean($this->input->post('num_factura'))."%')"; 
        }
        if($this->input->post('nombre_proveedor')){
            $filtro .= " AND (proveedor.razon_social ILIKE '%".$this->security->xss_clean($this->input->post('nombre_proveedor'))."%')"; 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        // $filtro .= " LIMIT 100";
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante, 
        ingreso_producto.nro_comprobante,ingreso_producto.fecha,proveedor.razon_social, 
        ingreso_producto.id_almacen, proveedor.id_proveedor, ingreso_producto.total,
        comprobante.no_comprobante,ingreso_producto.serie_comprobante,
        (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo 
        FROM ingreso_producto
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        WHERE ingreso_producto.id_comprobante = '2' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }   

    public function listaRegistrosFiltroPdf(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nombre')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(ingreso_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('proveedor')){
            $filtro .= " AND proveedor.id_proveedor =".(int)$this->security->xss_clean($this->input->post('proveedor')); 
        }
        if($this->input->post('moneda')){
            $filtro .= " AND ingreso_producto.id_moneda =".(int)$this->security->xss_clean($this->input->post('moneda')); 
        }
        if($this->input->post('num_factura')){
            $filtro .= " AND ingreso_producto.nro_comprobante =".(int)$this->security->xss_clean($this->input->post('num_factura')); 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante, comprobante.no_comprobante,
        ingreso_producto.nro_comprobante,ingreso_producto.fecha,proveedor.razon_social, moneda.no_moneda,
        ingreso_producto.id_almacen, proveedor.id_proveedor, ingreso_producto.total,ingreso_producto.gastos,
        (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo 
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        WHERE ingreso_producto.id_comprobante = '1' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistrosFiltroPdf_otros(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nombre')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(ingreso_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('proveedor')){
            $filtro .= " AND proveedor.id_proveedor =".(int)$this->security->xss_clean($this->input->post('proveedor')); 
        }
        if($this->input->post('comprobante')){
            $filtro .= " AND ingreso_producto.id_comprobante =".(int)$this->security->xss_clean($this->input->post('comprobante')); 
        }
        if($this->input->post('moneda')){
            $filtro .= " AND ingreso_producto.id_moneda =".(int)$this->security->xss_clean($this->input->post('moneda')); 
        }
        if($this->input->post('num_factura')){
            $filtro .= " AND ingreso_producto.nro_comprobante =".(int)$this->security->xss_clean($this->input->post('num_factura')); 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante, comprobante.no_comprobante,
        ingreso_producto.nro_comprobante,ingreso_producto.fecha,proveedor.razon_social, moneda.no_moneda,
        ingreso_producto.id_almacen, proveedor.id_proveedor, ingreso_producto.total,
        (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo 
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        WHERE ingreso_producto.id_comprobante <> '1' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistros_productoFiltroPdf(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nomproducto')){
            $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$this->security->xss_clean($this->input->post('nomproducto')); 
        }
        if($this->input->post('fechainicial_2') AND $this->input->post('fechafinal_2')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_2'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_2'))."'"; 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT ingreso_producto.id_ingreso_producto,comprobante.no_comprobante, ingreso_producto.nro_comprobante,
        proveedor.razon_social,ingreso_producto.fecha,detalle_producto.no_producto, moneda.no_moneda,
        detalle_ingreso_producto.precio, detalle_ingreso_producto.unidades,
        (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
        INNER JOIN detalle_producto ON detalle_ingreso_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE ingreso_producto.id_comprobante = '1' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistros_productoFiltroPdf_otros(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nomproducto')){
            $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$this->security->xss_clean($this->input->post('nomproducto')); 
        }
        if($this->input->post('comprobante')){
            $filtro .= " AND ingreso_producto.id_comprobante =".(int)$this->security->xss_clean($this->input->post('comprobante')); 
        }
        if($this->input->post('fechainicial_2') AND $this->input->post('fechafinal_2')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_2'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_2'))."'"; 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT ingreso_producto.id_ingreso_producto,comprobante.no_comprobante, ingreso_producto.nro_comprobante,
        proveedor.razon_social,ingreso_producto.fecha,detalle_producto.no_producto, moneda.no_moneda,
        detalle_ingreso_producto.precio, detalle_ingreso_producto.unidades,
        (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
        INNER JOIN detalle_producto ON detalle_ingreso_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE ingreso_producto.id_comprobante <> '1' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistros_agenteFiltroPdf(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('agente')){
            $filtro .= " AND agente_aduana.id_agente =".(int)$this->security->xss_clean($this->input->post('agente')); 
        }
        if($this->input->post('fechainicial_3') AND $this->input->post('fechafinal_3')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_3'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_3'))."'"; 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT ingreso_producto.nro_comprobante,proveedor.razon_social,ingreso_producto.fecha, moneda.no_moneda,
        moneda.no_moneda,moneda.simbolo_mon,ingreso_producto.total,agente_aduana.no_agente,ingreso_producto.gastos,
        (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN agente_aduana ON ingreso_producto.id_agente = agente_aduana.id_agente
        WHERE ingreso_producto.id_comprobante = '1' AND ingreso_producto.nro_comprobante IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarMontoCierre(){
        $filtro = "";
        $filtro .= " ORDER BY monto_cierre.fecha_cierre DESC";
        $filtro .= " LIMIT 12";
        $sql = "SELECT monto_cierre.id_monto_cierre,monto_cierre.fecha_cierre,monto_cierre.monto_cierre,monto_cierre.nombre_mes,
                       monto_cierre.monto_cierre_sta_anita,monto_cierre.monto_cierre_sta_clara
                FROM monto_cierre
                WHERE monto_cierre.id_monto_cierre IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaSalidaProducto(){
        $filtro = "";
        if($this->input->post('nombre_producto')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre_producto'))."%')";   
        }
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(salida_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(salida_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen'));
        }
        if($this->input->post('area')){
            $filtro .= " AND area.id_area =".(int)$this->security->xss_clean($this->input->post('area')); 
        }
        $filtro .= " ORDER BY area.no_area ASC";
        $filtro .= " LIMIT 100";
        $sql = "SELECT salida_producto.id_salida_producto,salida_producto.solicitante,salida_producto.fecha,detalle_salida_producto.cantidad_salida,detalle_producto.no_producto,
        area.no_area,maquina.nombre_maquina,parte_maquina.nombre_parte_maquina,salida_producto.observacion,detalle_salida_producto.id_detalle_producto
        FROM salida_producto
        INNER JOIN detalle_salida_producto ON detalle_salida_producto.id_salida_producto = salida_producto.id_salida_producto
        INNER JOIN area ON salida_producto.id_area = area.id_area
        INNER JOIN detalle_producto ON detalle_salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN maquina ON detalle_salida_producto.id_maquina = maquina.id_maquina
        LEFT JOIN parte_maquina ON parte_maquina.id_maquina = maquina.id_maquina AND detalle_salida_producto.id_parte_maquina = parte_maquina.id_parte_maquina
        WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_datos_detalle_pedido($id_salida_producto,$id_detalle_producto){
        try{
            $filtro = "";
            $filtro .= " AND salida_producto.id_salida_producto =".(int)$id_salida_producto;
            $filtro .= " AND detalle_salida_producto.id_detalle_producto =".(int)$id_detalle_producto;
            $sql = "SELECT salida_producto.id_salida_producto,detalle_salida_producto.id_detalle_producto,salida_producto.id_area,
                    salida_producto.solicitante,salida_producto.fecha,detalle_salida_producto.cantidad_salida,salida_producto.id_almacen,
                    detalle_salida_producto.p_u_salida,detalle_salida_producto.id_maquina,detalle_salida_producto.id_parte_maquina,
                    detalle_producto.no_producto,detalle_producto.stock,unidad_medida.nom_uni_med
                    FROM
                    salida_producto
                    INNER JOIN detalle_salida_producto ON detalle_salida_producto.id_salida_producto = salida_producto.id_salida_producto
                    INNER JOIN detalle_producto ON detalle_salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                    WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
            $query = $this->db->query($sql);
            $a_data = $query->result_array();
            return $a_data;
        }catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }

    public function listaTrasladoProducto(){
        $filtro = "";
        if($this->input->post('nombre_producto')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre_producto'))."%')";   
        }
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(traslado.fecha_traslado) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(traslado.fecha_traslado) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND traslado.id_almacen_llegada =".(int)$this->security->xss_clean($this->session->userdata('almacen'));
        $filtro .= " LIMIT 100";
        $sql = "SELECT detalle_producto.no_producto,detalle_traslado.cantidad_traslado,traslado.fecha_traslado,detalle_traslado.id_detalle_traslado,
                detalle_traslado.id_detalle_producto,traslado.id_almacen_partida,traslado.id_almacen_llegada,traslado.id_traslado
                FROM traslado
                INNER JOIN detalle_traslado ON detalle_traslado.id_traslado = traslado.id_traslado
                INNER JOIN detalle_producto ON detalle_traslado.id_detalle_producto = detalle_producto.id_detalle_producto
                WHERE traslado.id_traslado IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaAlmacenCombo_traslado_llegada(){
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        // Obtener el almacen de llegada
        if($id_almacen == 1){
            $id_almacen = 2;
        }else if($id_almacen == 2){
            $id_almacen = 1;
        }
        $filtro = "";
        $filtro .= " AND almacen.id_almacen =".(int)$id_almacen;
        $sql = "SELECT almacen.id_almacen,almacen.no_almacen FROM almacen
                WHERE almacen.id_almacen IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_almacen, ENT_QUOTES)] = htmlspecialchars($row->no_almacen, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaAlmacenCombo_traslado_inicio(){
        $id_almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $filtro = "";
        $filtro .= " AND almacen.id_almacen =".(int)$id_almacen;
        $sql = "SELECT almacen.id_almacen,almacen.no_almacen FROM almacen
                WHERE almacen.id_almacen IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_almacen, ENT_QUOTES)] = htmlspecialchars($row->no_almacen, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function agrega_ingreso_traslado($datos)
    {
        $insert = $this->db->insert('traslado', $datos);
        if($insert){
            return $this->db->insert_id();  
        }else{
            return FALSE;
        }
    }

    function agregar_detalle_ingreso_traslado($carrito, $id_ingreso_traslado, $fecharegistro, $id_almacen)
    {
        foreach ($carrito as $item) {
            // Datos del area
            if($this->cart->has_options($item['rowid']) === TRUE){
                foreach ($this->cart->product_options($item['rowid']) as $option_name => $option_value){
                    $no_area = $option_value;
                }
            }
            // Obtengo el id_area
            $this->db->select('id_area');
            $this->db->where('no_area',$no_area);
            $query = $this->db->get('area');
            foreach($query->result() as $row){
                $id_area = $row->id_area;
            }
            // Datos del producto
            $no_producto = $item['name'];
            $this->db->select('id_detalle_producto,stock,stock_sta_clara');
            $this->db->where('no_producto',$no_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $id_detalle_producto = $row->id_detalle_producto;
                $stock_sta_anita = $row->stock;
                $stock_sta_clara = $row->stock_sta_clara;
            }

            $this->db->select('id_pro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('producto');
            foreach($query->result() as $row){
                $id_pro = $row->id_pro;
            }

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
            }

            $datos = array(
                "id_traslado" => $id_ingreso_traslado,
                "id_detalle_producto" => $id_detalle_producto,
                "cantidad_traslado" => $item['qty'],
            );
            $this->db->insert('detalle_traslado', $datos);
            // Descontar - Aumentar el stock segun el almacen
            if($id_almacen == 1){ // Sta. Clara
                // Stock general
                $stock_sta_clara = $stock_sta_clara - $item['qty'];
                $stock_sta_anita = $stock_sta_anita + $item['qty'];
                $actualizar = array(
                    'stock'=> $stock_sta_anita,
                    'stock_sta_clara'=> $stock_sta_clara
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('detalle_producto', $actualizar);
                // Stock por areas
                $stock_area_sta_clara = $stock_area_sta_clara - $item['qty'];
                $stock_area_sta_anita = $stock_area_sta_anita + $item['qty'];
                $actualizar_area = array(
                    'stock_area_sta_anita'=> $stock_area_sta_anita,
                    'stock_area_sta_clara'=> $stock_area_sta_clara
                );
                $this->db->where('id_pro',$id_pro);
                $this->db->where('id_area',$id_area);
                $this->db->update('detalle_producto_area', $actualizar_area);
            }else if($id_almacen == 2){ // Sta. Anita
                $stock_sta_anita = $stock_sta_anita - $item['qty'];
                $stock_sta_clara = $stock_sta_clara + $item['qty'];
                $actualizar = array(
                    'stock'=> $stock_sta_anita,
                    'stock_sta_clara'=> $stock_sta_clara
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('detalle_producto', $actualizar);
                // Stock por areas
                $stock_area_sta_anita = $stock_area_sta_anita - $item['qty'];
                $stock_area_sta_clara = $stock_area_sta_clara + $item['qty'];
                $actualizar_area = array(
                    'stock_area_sta_anita'=> $stock_area_sta_anita,
                    'stock_area_sta_clara'=> $stock_area_sta_clara
                );
                $this->db->where('id_pro',$id_pro);
                $this->db->where('id_area',$id_area);
                $this->db->update('detalle_producto_area', $actualizar_area);
            }
        }
        return TRUE;
    }

    function listaSalidaProducto_2013(){
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }

        $sql = "SELECT salida_producto.id_salida_producto,area.no_area,salida_producto.solicitante,salida_producto.fecha,
        detalle_producto.no_producto,salida_producto.cantidad_salida,area.encargado
        FROM
        salida_producto
        INNER JOIN area ON salida_producto.id_area = area.id_area
        INNER JOIN detalle_producto ON salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaSalidaProductoVal(){
        $sql = "SELECT salida_producto.id_salida_producto,nombre_maquina.nombre_maquina, 
        marca_maquina.no_marca,modelo_maquina.no_modelo,serie_maquina.no_serie, 
        area.no_area, salida_producto.solicitante, salida_producto.fecha,
        detalle_producto.no_producto, salida_producto.cantidad_salida
        FROM salida_producto
        INNER JOIN nombre_maquina ON salida_producto.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina AND salida_producto.id_marca = marca_maquina.id_marca_maquina
        INNER JOIN modelo_maquina ON modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina AND salida_producto.id_modelo = modelo_maquina.id_modelo_maquina
        INNER JOIN serie_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina AND salida_producto.id_serie = serie_maquina.id_serie_maquina
        INNER JOIN area ON salida_producto.id_area = area.id_area
        INNER JOIN detalle_producto ON salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listar_areas_salidas(){
        $filtro = "";
        $filtro .= " salida_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen'));
        $filtro .= " AND DATE(salida_producto.fecha) ='".date('Y-m-d')."'";
        $sql = "SELECT DISTINCT area.no_area,area.id_area
                FROM area
                INNER JOIN salida_producto ON salida_producto.id_area = area.id_area 
                WHERE ".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistrosSalidaFiltroPdf(){
        $filtro = "";
        if($this->input->post('solicitante')){
            $filtro = " AND (salida_producto.solicitante ILIKE '%".$this->security->xss_clean($this->input->post('solicitante'))."%')";   
        }
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(salida_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('fecharegistro_X')){
            $filtro .= " AND DATE(salida_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro_X'))."'"; 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(salida_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        if($this->input->post('fechainicial_X') AND $this->input->post('fechafinal_X')){
            $filtro .= " AND DATE(salida_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_X'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_X'))."'"; 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen'));
        }
        if($this->input->post('nomproducto')){
            $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$this->security->xss_clean($this->input->post('nomproducto')); 
        }
        if($this->input->post('marca')){
            $filtro .= " AND marca_maquina.id_marca_maquina =".(int)$this->security->xss_clean($this->input->post('marca')); 
        }
        if($this->input->post('modelo')){
            $filtro .= " AND modelo_maquina.id_modelo_maquina =".(int)$this->security->xss_clean($this->input->post('modelo')); 
        }
        if($this->input->post('area')){
            $filtro .= " AND area.id_area =".(int)$this->security->xss_clean($this->input->post('area')); 
        }
        if($this->input->post('serie')){
            $filtro .= " AND serie_maquina.id_serie_maquina =".(int)$this->security->xss_clean($this->input->post('serie')); 
        }
        $sql = "SELECT salida_producto.id_salida_producto,nombre_maquina.nombre_maquina,marca_maquina.no_marca,modelo_maquina.no_modelo,
        serie_maquina.no_serie,area.no_area,salida_producto.solicitante,salida_producto.fecha,
        detalle_producto.no_producto,salida_producto.cantidad_salida,detalle_producto.precio_unitario
        FROM salida_producto
        INNER JOIN nombre_maquina ON salida_producto.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina AND salida_producto.id_marca = marca_maquina.id_marca_maquina
        INNER JOIN modelo_maquina ON modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina AND salida_producto.id_modelo = modelo_maquina.id_modelo_maquina
        INNER JOIN serie_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina AND salida_producto.id_serie = serie_maquina.id_serie_maquina
        INNER JOIN detalle_producto ON salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN area ON salida_producto.id_area = area.id_area
        WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistros_otros(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si esribe una fecha de registro 
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(ingreso_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'";
        }
        if($this->input->post('proveedor')){
            $filtro .= " AND proveedor.id_proveedor =".(int)$this->security->xss_clean($this->input->post('proveedor')); 
        }
        if($this->input->post('tipocom')){
            $filtro .= " AND ingreso_producto.id_comprobante =".(int)$this->security->xss_clean($this->input->post('tipocom')); 
        }
        if($this->input->post('num_factura')){
            $filtro .= " AND ingreso_producto.nro_comprobante =".(int)$this->security->xss_clean($this->input->post('num_factura')); 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante, ingreso_producto.nro_comprobante,ingreso_producto.fecha,
        proveedor.razon_social,ingreso_producto.id_almacen, proveedor.id_proveedor, comprobante.no_comprobante, ingreso_producto.total, moneda.no_moneda, (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        WHERE ingreso_producto.id_comprobante <> '2' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarMarcaMaquinas(){
        $almacen = $this->session->userdata('almacen');
        $sql = "SELECT marca_maquina.id_marca_maquina, marca_maquina.no_marca, nombre_maquina.nombre_maquina 
                FROM nombre_maquina 
                INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina
                WHERE marca_maquina.id_almacen=".$almacen;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarModeloMaquinas(){
        $almacen = $this->session->userdata('almacen');
        $sql = "SELECT modelo_maquina.id_modelo_maquina, marca_maquina.no_marca, modelo_maquina.no_modelo 
                FROM marca_maquina 
                INNER JOIN modelo_maquina ON modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina
                WHERE modelo_maquina.id_almacen=".$almacen;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarSerieMaquinas(){
        $almacen = $this->session->userdata('almacen');
        $sql = "SELECT serie_maquina.id_serie_maquina, modelo_maquina.no_modelo, serie_maquina.no_serie 
                FROM serie_maquina 
                INNER JOIN modelo_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina
                WHERE serie_maquina.id_almacen=".$almacen;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarNombreMaquinas(){
        $almacen = $this->session->userdata('almacen');
        $sql = "SELECT nombre_maquina.id_nombre_maquina, nombre_maquina.nombre_maquina FROM nombre_maquina WHERE nombre_maquina.id_almacen=".$almacen;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        /*
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND nombre_maquina.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }

        $sql = "SELECT nombre_maquina.id_nombre_maquina, nombre_maquina.nombre_maquina
        FROM nombre_maquina WHERE nombre_maquina.id_nombre_maquina IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_nombre_maquina, ENT_QUOTES)] = htmlspecialchars($row->nombre_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
        */
    }

    function listarResumenProductos_report_excel(){
        $sql = "SELECT producto.id_pro,producto.id_producto,detalle_producto.no_producto,detalle_producto.stock,
        detalle_producto.precio_unitario,categoria.no_categoria,tipo_producto.no_tipo_producto,procedencia.no_procedencia,
        unidad_medida.nom_uni_med,area.no_area
        FROM
        producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        LEFT JOIN tipo_producto ON tipo_producto.id_categoria = categoria.id_categoria AND producto.id_tipo_producto = tipo_producto.id_tipo_producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
        LEFT JOIN detalle_producto_area ON detalle_producto_area.id_pro = producto.id_pro
        LEFT JOIN area ON detalle_producto_area.id_area = area.id_area
        WHERE producto.id_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function consolidar_stock(){
        $sql = "SELECT producto.id_pro,detalle_producto.stock,detalle_producto.precio_unitario
                FROM
                producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto";
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {
            $id_pro = $row->id_pro;
            $stock = $row->stock;
            $precio_unitario = $row->precio_unitario;
            /* 1ero guardamos esta informacion */
            /*
            $datos = array(
                "id_pro" => $id_pro,
                "fecha_cierre" => "2012-01-01"
            );
            $this->db->insert('saldos_iniciales', $datos);
            */
            
            $actualizar = array(
                'stock_inicial' => $stock,
                'precio_uni_inicial' => $precio_unitario
            );
            $this->db->where('id_pro',$id_pro);
            $this->db->update('saldos_iniciales', $actualizar);
        }
    }

    function report_excel_kardex_producto($id_detalle_producto,$f_inicial,$f_final){
        $filtro  = "";
        $filtro .= " AND DATE(kardex_producto.fecha_registro) BETWEEN'".$f_inicial."'AND'".$f_final."'";
        $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$id_detalle_producto;
        $sql = "SELECT kardex_producto.descripcion,detalle_producto.no_producto,kardex_producto.stock_anterior,
        kardex_producto.precio_unitario_anterior,kardex_producto.cantidad_salida,kardex_producto.cantidad_ingreso,
        kardex_producto.fecha_registro,kardex_producto.stock_actual,kardex_producto.precio_unitario_actual,
        kardex_producto.precio_unitario_actual_promedio,kardex_producto.id_kardex_producto,producto.id_producto,
        unidad_medida.id_unidad_medida,detalle_producto.id_detalle_producto,kardex_producto.num_comprobante,kardex_producto.serie_comprobante
        FROM
        kardex_producto
        INNER JOIN detalle_producto ON kardex_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
        WHERE kardex_producto.id_kardex_producto IS NOT NULL".$filtro."ORDER BY kardex_producto.fecha_registro ASC";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_producto_con_kardex($id_detalle_producto,$f_inicial,$f_final){
        $filtro  = "";
        $filtro .= " AND DATE(kardex_producto.fecha_registro) BETWEEN'".$f_inicial."'AND'".$f_final."'";
        $filtro .= " AND kardex_producto.id_detalle_producto =".(int)$id_detalle_producto;
        $sql = "SELECT DISTINCT kardex_producto.id_detalle_producto,kardex_producto.fecha_registro
                FROM kardex_producto
                WHERE kardex_producto.id_kardex_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getDatosProducto_kardex($id_detalle_producto){
        $filtro = "";
        $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$id_detalle_producto;
        $sql = "SELECT detalle_producto.no_producto,producto.id_producto,producto.id_unidad_medida,detalle_producto.id_detalle_producto
        FROM
        producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_nombres_kardex($indice){
        $filtro = "";
        $filtro .= " AND producto.indice =".(int)$indice;
        $sql = "SELECT DISTINCT detalle_producto.no_producto,producto.id_pro,producto.id_producto,producto.id_unidad_medida,unidad_medida.nom_uni_med,
                detalle_producto.id_detalle_producto,producto.indice
                FROM kardex_producto
                INNER JOIN detalle_producto ON kardex_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                WHERE producto.indice IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_nombres_kardex_sunat(){
        $sql = "SELECT DISTINCT detalle_producto.no_producto,producto.id_pro,producto.id_producto,producto.id_unidad_medida,unidad_medida.nom_uni_med,
                detalle_producto.id_detalle_producto,producto.indice
                FROM kardex_producto
                INNER JOIN detalle_producto ON kardex_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                WHERE producto.indice IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_nombres_kardex_producto($id_detalle_producto){
        $filtro = "";
        $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$id_detalle_producto;
        $sql = "SELECT DISTINCT detalle_producto.no_producto,unidad_medida.id_unidad_medida,detalle_producto.id_detalle_producto,producto.id_pro
        FROM
        detalle_producto
        LEFT JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        RIGHT JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
        LEFT JOIN kardex_producto ON kardex_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_movimientos_entrada_facturas($f_inicial,$f_final){
        $filtro = "";
        $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$f_inicial."'AND'".$f_final."'";
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen'));
        $sql = "SELECT comprobante.no_comprobante,ingreso_producto.nro_comprobante,ingreso_producto.serie_comprobante,
        proveedor.razon_social,ingreso_producto.fecha,moneda.simbolo_mon,moneda.no_moneda,ingreso_producto.total,
        ingreso_producto.gastos,ingreso_producto.id_ingreso_producto,ingreso_producto.id_agente,ingreso_producto.id_almacen
        FROM
        ingreso_producto
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro."ORDER BY ingreso_producto.fecha ASC";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_movimientos_salidas_facturas($f_inicial,$f_final){
        $filtro = "";
        $filtro .= " AND DATE(salida_producto.fecha) BETWEEN'".$f_inicial."'AND'".$f_final."'";
        $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen'));
        $filtro .= " ORDER BY salida_producto.fecha ASC , salida_producto.id_salida_producto ASC";
        $sql = "SELECT salida_producto.id_salida_producto,salida_producto.id_area,salida_producto.solicitante,salida_producto.fecha,
                detalle_salida_producto.id_detalle_producto,detalle_salida_producto.cantidad_salida,detalle_salida_producto.p_u_salida,
                salida_producto.id_almacen,detalle_salida_producto.id_maquina,detalle_salida_producto.id_parte_maquina,
                salida_producto.observacion,area.no_area,detalle_producto.no_producto,producto.id_pro,unidad_medida.nom_uni_med,categoria.no_categoria
                FROM salida_producto
                INNER JOIN detalle_salida_producto ON detalle_salida_producto.id_salida_producto = salida_producto.id_salida_producto
                INNER JOIN detalle_producto ON detalle_salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                INNER JOIN area ON salida_producto.id_area = area.id_area
                WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_cierre_almacen(){
        $filtro = "";
        $filtro .= " ORDER BY monto_cierre.fecha_cierre DESC";
        $sql = "SELECT monto_cierre.id_monto_cierre,monto_cierre.fecha_cierre,monto_cierre.monto_cierre,monto_cierre.nombre_mes,
                monto_cierre.monto_cierre_sta_anita,monto_cierre.monto_cierre_sta_clara
                FROM monto_cierre
                WHERE monto_cierre.id_monto_cierre IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_movimientos_kardex($id_detalle_producto,$f_inicial,$f_final){
        $filtro = "";
        $filtro .= " AND kardex_producto.id_detalle_producto =".(int)$id_detalle_producto;
        $filtro .= " AND DATE(kardex_producto.fecha_registro) BETWEEN'".$f_inicial."'AND'".$f_final."'";
        $filtro .= " ORDER BY kardex_producto.fecha_registro ASC , kardex_producto.id_kardex_producto ASC";
        $sql = "SELECT kardex_producto.id_kardex_producto,kardex_producto.descripcion,kardex_producto.id_detalle_producto,kardex_producto.stock_anterior,
        kardex_producto.precio_unitario_anterior,kardex_producto.cantidad_salida,kardex_producto.stock_actual,kardex_producto.precio_unitario_actual,
        kardex_producto.fecha_registro,kardex_producto.cantidad_ingreso,kardex_producto.precio_unitario_actual_promedio,kardex_producto.serie_comprobante,
        kardex_producto.num_comprobante,detalle_producto.no_producto,unidad_medida.id_unidad_medida,unidad_medida.nom_uni_med
        FROM kardex_producto
        INNER JOIN detalle_producto ON kardex_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
        WHERE kardex_producto.id_kardex_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_info_salidas_report($f_inicial, $f_final, $almacen){
        $filtro = "";
        $filtro .= " AND salida_producto.id_almacen =".(int)$almacen;
        $filtro .= " AND DATE(salida_producto.fecha) BETWEEN'".$f_inicial."'AND'".$f_final."'";
        $filtro .= " ORDER BY salida_producto.fecha ASC , salida_producto.id_salida_producto ASC";
        $sql = "SELECT DISTINCT salida_producto.id_salida_producto,salida_producto.id_detalle_producto,
        salida_producto.cantidad_salida,salida_producto.p_u_salida,salida_producto.fecha,detalle_producto.no_producto,
        procedencia.no_procedencia,categoria.no_categoria,unidad_medida.nom_uni_med,salida_producto.id_almacen
        FROM salida_producto
        INNER JOIN detalle_producto ON salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_info_facturas_report($id_detalle_producto){
        $filtro = "";
        $filtro .= " AND detalle_ingreso_producto.id_detalle_producto =".(int)$id_detalle_producto;
        $filtro .= " ORDER BY ingreso_producto.fecha DESC , ingreso_producto.id_ingreso_producto DESC";
        $filtro .= " LIMIT 2";
        $sql = "SELECT ingreso_producto.id_comprobante,ingreso_producto.serie_comprobante,ingreso_producto.nro_comprobante,
        proveedor.razon_social,detalle_ingreso_producto.unidades,detalle_ingreso_producto.id_detalle_producto,ingreso_producto.id_ingreso_producto,
        detalle_ingreso_producto.precio,comprobante.no_comprobante
        FROM ingreso_producto
        INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_all_productos(){
        $sql = "SELECT DISTINCT detalle_producto.id_detalle_producto,detalle_producto.no_producto,producto.id_pro
        FROM detalle_producto
        INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE detalle_producto.id_detalle_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_select_facturas_asociadas($id_salida_producto){
        $filtro = "";
        $filtro .= " AND adm_facturas_asociadas.id_salida_producto =".(int)$id_salida_producto;
        $filtro .= " ORDER BY adm_facturas_asociadas.id_facturas_asociadas ASC";
        $sql = "SELECT adm_facturas_asociadas.id_facturas_asociadas,adm_facturas_asociadas.id_salida_producto,adm_facturas_asociadas.id_ingreso_producto,
        adm_facturas_asociadas.cantidad_utilizada
        FROM adm_facturas_asociadas
        WHERE adm_facturas_asociadas.id_facturas_asociadas IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_select_data_invoice($id_ingreso_producto){
        $filtro = "";
        $filtro .= " AND ingreso_producto.id_ingreso_producto =".(int)$id_ingreso_producto;
        $sql = "SELECT ingreso_producto.serie_comprobante,ingreso_producto.nro_comprobante,proveedor.razon_social,comprobante.no_comprobante,
        ingreso_producto.id_ingreso_producto,detalle_ingreso_producto.id_detalle_producto,detalle_ingreso_producto.precio
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
        WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function report_saldo_inicial($id_detalle_producto,$f_inicial){
        $filtro = "";
        $filtro .= " AND DATE(saldos_iniciales.fecha_cierre) ='".$f_inicial."'";
        $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$id_detalle_producto;
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto,saldos_iniciales.stock_inicial,
        saldos_iniciales.precio_uni_inicial,saldos_iniciales.fecha_cierre,saldos_iniciales.id_saldos_iniciales
        FROM saldos_iniciales
        INNER JOIN producto ON saldos_iniciales.id_pro = producto.id_pro
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE saldos_iniciales.id_saldos_iniciales IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function traer_saldos_iniciales($f_inicial,$id_pro){
        $filtro = "";
        $filtro .= " AND DATE(saldos_iniciales.fecha_cierre) ='".$f_inicial."'";
        $filtro .= " AND saldos_iniciales.id_pro =".(int)$id_pro;
        $sql = "SELECT saldos_iniciales.id_saldos_iniciales,saldos_iniciales.fecha_cierre,saldos_iniciales.stock_inicial,saldos_iniciales.precio_uni_inicial,
        saldos_iniciales.id_pro,saldos_iniciales.stock_inicial_sta_clara
        FROM saldos_iniciales
        WHERE saldos_iniciales.id_saldos_iniciales IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_info_saldos_iniciales($f_inicial){
        $filtro = "";
        $filtro .= " AND DATE(saldos_iniciales.fecha_cierre) ='".$f_inicial."'";
        $sql = "SELECT saldos_iniciales.fecha_cierre,detalle_producto.no_producto,saldos_iniciales.stock_inicial,
        saldos_iniciales.precio_uni_inicial,saldos_iniciales.stock_inicial_sta_clara
        FROM saldos_iniciales
        INNER JOIN producto ON saldos_iniciales.id_pro = producto.id_pro
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE saldos_iniciales.id_saldos_iniciales IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function get_info_inventario_actual(){
        $sql = "SELECT producto.id_pro,producto.estado,detalle_producto.no_producto,detalle_producto.stock,detalle_producto.precio_unitario,
        categoria.no_categoria,tipo_producto.no_tipo_producto,procedencia.no_procedencia,unidad_medida.nom_uni_med,ubicacion.nombre_ubicacion
        FROM producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        INNER JOIN tipo_producto ON producto.id_tipo_producto = tipo_producto.id_tipo_producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
        INNER JOIN ubicacion ON producto.id_ubicacion = ubicacion.id_ubicacion
        WHERE producto.id_pro IS NOT NULL ORDER BY producto.estado ASC";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarMoneda(){
        $sql = "SELECT moneda.id_moneda, moneda.no_moneda, moneda.simbolo_mon  FROM moneda";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarAduana(){
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND agente_aduana.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT agente_aduana.id_agente, agente_aduana.no_agente 
        FROM agente_aduana WHERE agente_aduana.id_agente IS NOT NULL".$filtro
        ;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarComprobantes_lista(){
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND comprobante.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT comprobante.id_comprobante, comprobante.no_comprobante FROM comprobante 
        WHERE comprobante.no_comprobante <> 'FACTURA' AND comprobante.id_comprobante IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarMaquinaRegistradas(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nombre')){
            $filtro = " AND (maquina.nombre_maquina ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }
        $sql = "SELECT maquina.nombre_maquina,estado_maquina.no_estado_maquina,maquina.observacion_maq,maquina.id_maquina
        FROM maquina 
        INNER JOIN estado_maquina ON maquina.id_estado_maquina = estado_maquina.id_estado_maquina
        WHERE maquina.id_maquina IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarMaquinas(){
        $this->db->select('id_maquina,nombre_maquina');
        $this->db->order_by('nombre_maquina', 'ASC');           
        $query = $this->db->get('maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_maquina, ENT_QUOTES)] = htmlspecialchars($row->nombre_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listar_parte_Maquinas(){
        $id_maquina = $this->session->userdata('id_maquina');
        // echo $id_maquina;
        $this->db->select('id_parte_maquina,nombre_parte_maquina');
        // if($this->session->userdata('id_maquina') != ""){$this->db->where('id_maquina',$this->session->userdata('id_maquina'));}
        $this->db->where('id_maquina',$id_maquina);
        $this->db->order_by('nombre_parte_maquina', 'ASC');           
        $query = $this->db->get('parte_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_parte_maquina, ENT_QUOTES)] = htmlspecialchars($row->nombre_parte_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listarMaquinaFiltroPdf(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('maquinaS')){
            $filtro .= " AND maquina.id_nombre_maquina =".$this->security->xss_clean($this->input->post('maquinaS'));
        }
        if($this->input->post('maquina')){
            $filtro .= " AND maquina.id_nombre_maquina =".$this->security->xss_clean($this->input->post('maquina'));
        }
        if($this->input->post('marca')){
            $filtro .= " AND maquina.id_marca_maquina =".$this->security->xss_clean($this->input->post('marca'));
        }
        if($this->input->post('estado')){
            $filtro .= " AND maquina.id_estado_maquina =".$this->security->xss_clean($this->input->post('estado'));
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND maquina.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        // si esribe ID
        
        /*if($this->input->post('id_maquina')){
            $filtro = " AND maquina.id_maquina =".$this->security->xss_clean($this->input->post('id_maquina'));   
        }*/  
        $sql = "SELECT maquina.id_maquina, nombre_maquina.nombre_maquina, marca_maquina.no_marca, modelo_maquina.no_modelo,
        serie_maquina.no_serie, estado_maquina.no_estado_maquina, maquina.observacion_maq, maquina.fe_registro
        FROM maquina 
        INNER JOIN nombre_maquina ON maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        INNER JOIN modelo_maquina ON maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina
        INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina AND maquina.id_marca_maquina = marca_maquina.id_marca_maquina AND modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina
        INNER JOIN serie_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina AND maquina.id_serie_maquina = serie_maquina.id_serie_maquina
        INNER JOIN estado_maquina ON maquina.id_estado_maquina = estado_maquina.id_estado_maquina
        WHERE maquina.id_maquina IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarProveedores(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe la razon social del proveedor
        if($this->input->post('nombre')){
            $filtro = " AND (proveedor.razon_social ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }

        if($this->input->post('ruc_prov')){
            $filtro .= " AND proveedor.RUC =".$this->security->xss_clean($this->input->post('ruc_prov'));
        }
        /*
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        /*
        // si esribe ID
         if($this->input->post('ruc_prov')){
            $filtro .= " AND proveedor.id_proveedor =".$this->security->xss_clean($this->input->post('ruc_prov'));   
        }
        */  
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.ruc, proveedor.pais, 
        proveedor.direccion, proveedor.telefono1 FROM proveedor WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarProveedoresFiltroPdf(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe la razon social del proveedor
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(proveedor.fe_registro) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(proveedor.fe_registro) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        } 
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.ruc, proveedor.pais, 
        proveedor.direccion, proveedor.telefono1,proveedor.fe_registro FROM proveedor WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function listarUsuario(){
        //esta variable filtrará y concatenará los diferentes filtros
        // CASE usuario.id_almacen WHEN '1' THEN 'Santa Clara' WHEN '2' THEN 'Hilos' WHEN '3' THEN 'Tejedurias' END as id_almacen
        $filtro = "";
        // si escribe el nombre 
         if($this->input->post('nombre')){
            $filtro = " AND (usuario.no_usuario ||' '|| usuario.ape_paterno ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }
        // si esribe ID
         if($this->input->post('txt_usuario')){
            $filtro .= " AND (usuario.tx_usuario ILIKE '%".$this->security->xss_clean($this->input->post('txt_usuario'))."%')";   
        }  
        $sql = "SELECT usuario.id_usuario, usuario.no_usuario, usuario.ape_paterno, 
        CASE usuario.fl_estado WHEN 't' THEN 'Activo' WHEN 'f' THEN 'Inactivo' END as fl_estado, 
        usuario.tx_usuario, tipo_usuario.no_tipo_usuario, almacen.no_almacen, usuario.fe_registro
        FROM usuario INNER JOIN tipo_usuario ON usuario.id_tipo_usuario = tipo_usuario.id_tipo_usuario
        INNER JOIN almacen ON usuario.id_almacen = almacen.id_almacen
        WHERE usuario.id_usuario IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    // ESTA ES LA PARA LLENAR COMBOS
    function listarTipoUsuario(){
        $query = $this->db->query("SELECT id_tipo_usuario,no_tipo_usuario FROM tipo_usuario ORDER BY no_tipo_usuario DESC");
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_tipo_usuario, ENT_QUOTES)] = htmlspecialchars($row->no_tipo_usuario, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listaProveedor(){
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.id_almacen
        FROM proveedor WHERE proveedor.id_proveedor IS NOT NULL".$filtro."ORDER BY proveedor.razon_social ASC";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_proveedor, ENT_QUOTES)] = htmlspecialchars($row->razon_social, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listaComprobante(){
        $query = $this->db->query("SELECT id_comprobante,no_comprobante FROM comprobante WHERE comprobante.no_comprobante = 'FACTURA' ORDER BY no_comprobante ASC");
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_comprobante, ENT_QUOTES)] = htmlspecialchars($row->no_comprobante, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listaComprobante_importado(){
        $query = $this->db->query("SELECT id_comprobante,no_comprobante FROM comprobante WHERE comprobante.no_comprobante = 'FACTURA' OR comprobante.no_comprobante = 'GUIA DE REMISION' ORDER BY no_comprobante ASC");
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_comprobante, ENT_QUOTES)] = htmlspecialchars($row->no_comprobante, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listaSimMon(){
        $query = $this->db->query("SELECT id_moneda,(no_moneda ||' : '||simbolo_mon) AS nombresimbolo FROM moneda ORDER BY nombresimbolo ASC");
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_moneda, ENT_QUOTES)] = htmlspecialchars($row->nombresimbolo, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listaAgenteAduana(){
        /*$filtro = "";
        /*
        if($this->session->userdata('almacen') != 4){
            $filtro = " AND agente_aduana.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        */
        $sql = "SELECT id_agente,no_agente FROM agente_aduana  
                WHERE agente_aduana.no_agente != 'REGISTRO SIN AGENTE'";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_agente, ENT_QUOTES)] = htmlspecialchars($row->no_agente, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function ComboIndice(){
        $filtro = "";
        $filtro .= " ORDER BY producto.indice ASC";
        $sql = "SELECT indice FROM producto  
                WHERE producto.indice IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->indice, ENT_QUOTES)] = htmlspecialchars($row->indice, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listarComprobantes(){
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro = " AND comprobante.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT id_comprobante,no_comprobante FROM comprobante  
                WHERE comprobante.no_comprobante <> 'FACTURA' AND comprobante.id_comprobante IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_comprobante, ENT_QUOTES)] = htmlspecialchars($row->no_comprobante, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listaNombreProducto(){
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro = " AND producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro."ORDER BY detalle_producto.no_producto ASC";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_detalle_producto, ENT_QUOTES)] = htmlspecialchars($row->no_producto, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listarArea(){
        /*
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro = " AND area.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        */
        $filtro = "";
        $filtro .= " ORDER BY area.no_area ASC";
        $sql = "SELECT area.id_area,area.no_area FROM area
                INNER JOIN almacen ON area.id_almacen = almacen.id_almacen
                WHERE area.id_area IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_area, ENT_QUOTES)] = htmlspecialchars($row->no_area, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function listarAlmacen(){
        $query = $this->db->query("SELECT id_almacen,no_almacen FROM almacen WHERE almacen.no_almacen = 'SANTA ANITA' OR almacen.no_almacen = 'SANTA CLARA' OR almacen.no_almacen = 'TEJEDURIA' ORDER BY no_almacen");
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_almacen, ENT_QUOTES)] = htmlspecialchars($row->no_almacen, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    function getDatosRucExiste()
    {
        $nroruc = $this->security->xss_clean($this->input->post('nroruc'));
        $sql = "SELECT DISTINCT proveedor.ruc, proveedor.razon_social, proveedor.direccion FROM proveedor WHERE proveedor.ruc =".$nroruc;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();        
        }
    }

    function existeRuc()
    { 
        $nroruc = $this->security->xss_clean($this->input->post('nroruc'));
        $sql = "SELECT ruc FROM proveedor WHERE proveedor.ruc = $nroruc";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return true;
        }else {
            return false;
        }
    }

    function getMaqEditar(){
        $id_maquina = $this->security->xss_clean($this->uri->segment(3));
        $sql = "SELECT maquina.nombre_maquina,estado_maquina.no_estado_maquina,maquina.observacion_maq,maquina.id_maquina,estado_maquina.id_estado_maquina
                FROM maquina
                INNER JOIN estado_maquina ON maquina.id_estado_maquina = estado_maquina.id_estado_maquina
                WHERE maquina.id_maquina=".$id_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result();
        }
    }

    function getParteMaqEditar(){
        $id_parte_maquina = $this->security->xss_clean($this->uri->segment(3));
        $sql = "SELECT parte_maquina.id_parte_maquina,parte_maquina.nombre_parte_maquina,maquina.nombre_maquina,maquina.id_maquina
                FROM parte_maquina
                LEFT JOIN maquina ON parte_maquina.id_maquina = maquina.id_maquina
                WHERE parte_maquina.id_parte_maquina =".$id_parte_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result();
        }
    }

    function listarParteMaquinaRegistradas(){
        $sql = "SELECT parte_maquina.id_parte_maquina,parte_maquina.nombre_parte_maquina,maquina.nombre_maquina
                FROM parte_maquina
                LEFT JOIN maquina ON parte_maquina.id_maquina = maquina.id_maquina
                WHERE parte_maquina.id_parte_maquina IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getNomMaqEditar(){
        //Recuperamos el ID  -> 
        $id_nombre_maquina = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos

        $sql = "SELECT nombre_maquina.id_nombre_maquina, nombre_maquina.nombre_maquina
                FROM nombre_maquina
                WHERE nombre_maquina.id_nombre_maquina=".$id_nombre_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getDatosArea(){
        //Recuperamos el ID  -> 
        $id_area = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos

        $sql = "SELECT area.id_area,area.no_area,area.encargado,area.encargado_sta_clara
                FROM area
                WHERE area.id_area=".$id_area;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getMarMaqEditar(){
        //Recuperamos el ID  -> 
        $id_marca_maquina = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT marca_maquina.id_marca_maquina, marca_maquina.no_marca,marca_maquina.id_nombre_maquina
                FROM marca_maquina
                WHERE marca_maquina.id_marca_maquina=".$id_marca_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getModMaqEditar(){
        //Recuperamos el ID  -> 
        $id_modelo_maquina = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT modelo_maquina.id_modelo_maquina, modelo_maquina.no_modelo,modelo_maquina.id_marca_maquina
                FROM modelo_maquina
                WHERE modelo_maquina.id_modelo_maquina=".$id_modelo_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getSerMaqEditar(){
        //Recuperamos el ID  -> 
        $id_serie_maquina = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT serie_maquina.id_serie_maquina, serie_maquina.no_serie,serie_maquina.id_modelo_maquina
                FROM serie_maquina
                WHERE serie_maquina.id_serie_maquina=".$id_serie_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getDatosMoneda(){
        //Recuperamos el ID  -> 
        $id_moneda = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos

        $sql = "SELECT moneda.id_moneda, moneda.no_moneda, moneda.simbolo_mon
                FROM moneda
                WHERE moneda.id_moneda=".$id_moneda;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getDatosAgente(){
        //Recuperamos el ID  -> 
        $id_agente = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos

        $sql = "SELECT agente_aduana.id_agente, agente_aduana.no_agente
                FROM agente_aduana
                WHERE agente_aduana.id_agente=".$id_agente;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getDatosComprobante(){
        //Recuperamos el ID  -> 
        $id_comprobante = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos

        $sql = "SELECT comprobante.id_comprobante, comprobante.no_comprobante
                FROM comprobante
                WHERE comprobante.id_comprobante=".$id_comprobante;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getProdEditar(){
        // recuperamos el ID -> 
        $id_pro = $this->security->xss_clean($this->uri->segment(3));
        $filtro = "";
        $filtro .= " AND producto.id_pro =".(int)$id_pro;
        $sql = "SELECT DISTINCT producto.id_pro,producto.observacion,tipo_producto.no_tipo_producto,categoria.no_categoria,detalle_producto.no_producto,
                procedencia.no_procedencia,unidad_medida.nom_uni_med,ubicacion.nombre_ubicacion,tipo_producto.id_tipo_producto,categoria.id_categoria,
                procedencia.id_procedencia,unidad_medida.id_unidad_medida,ubicacion.id_ubicacion
                FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
                INNER JOIN tipo_producto ON producto.id_tipo_producto = tipo_producto.id_tipo_producto
                INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                LEFT JOIN ubicacion ON producto.id_ubicacion = ubicacion.id_ubicacion
                WHERE producto.id_pro IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result();
        }
    }

    function getSalidasProductos($id_area, $fecha_actual){
        $filtro = "";
        $filtro .= " AND DATE(salida_producto.fecha) ='".$fecha_actual."'"; 
        $filtro .= " AND area.id_area =".(int)$id_area; 
        $sql = "SELECT salida_producto.fecha,detalle_producto.no_producto,salida_producto.cantidad_salida,area.no_area,
                salida_producto.id_salida_producto,area.id_area,salida_producto.p_u_salida,unidad_medida.nom_uni_med,
                salida_producto.solicitante
                FROM salida_producto
                INNER JOIN area ON salida_producto.id_area = area.id_area
                INNER JOIN detalle_producto ON salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getSalidasProductos_print_cabecera($id_salida_producto){
        $filtro = "";
        $filtro .= " AND salida_producto.id_salida_producto =".(int)$id_salida_producto; 
        $filtro .= " LIMIT 1";
        $sql = "SELECT salida_producto.id_salida_producto,salida_producto.solicitante,salida_producto.fecha,area.no_area,salida_producto.observacion,
                maquina.nombre_maquina,parte_maquina.nombre_parte_maquina
                FROM salida_producto
                INNER JOIN area ON salida_producto.id_area = area.id_area
                INNER JOIN detalle_salida_producto ON detalle_salida_producto.id_salida_producto = salida_producto.id_salida_producto
                INNER JOIN maquina ON detalle_salida_producto.id_maquina = maquina.id_maquina
                LEFT JOIN parte_maquina ON parte_maquina.id_maquina = maquina.id_maquina AND detalle_salida_producto.id_parte_maquina = parte_maquina.id_parte_maquina
                WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getSalidasProductos_print_detalle_salida($id_salida_producto){
        $filtro = "";
        $filtro .= " AND detalle_salida_producto.id_salida_producto =".(int)$id_salida_producto; 
        $sql = "SELECT detalle_salida_producto.id_salida_producto,detalle_producto.no_producto,producto.id_pro,ubicacion.nombre_ubicacion,unidad_medida.nom_uni_med,
                detalle_salida_producto.cantidad_salida
                FROM
                detalle_salida_producto
                INNER JOIN detalle_producto ON detalle_salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN ubicacion ON producto.id_ubicacion = ubicacion.id_ubicacion
                INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
                WHERE detalle_salida_producto.id_salida_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTCEditar(){
        //Recuperamos el ID  -> 
        $id_tipo_cambio = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT tipo_cambio.fecha_actual,tipo_cambio.dolar_compra,tipo_cambio.dolar_venta,
                tipo_cambio.euro_compra,tipo_cambio.euro_venta,tipo_cambio.id_tipo_cambio
                FROM tipo_cambio
                WHERE tipo_cambio.id_tipo_cambio=".$id_tipo_cambio;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    /*
    $sql = "SELECT maquina.id_maquina, nombre_maquina.id_nombre_maquina, 
                marca_maquina.id_marca_maquina, modelo_maquina.id_modelo_maquina,
                serie_maquina.id_serie_maquina, estado_maquina.id_estado_maquina,
                maquina.observacion_maq
                FROM maquina
                INNER JOIN nombre_maquina ON maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina
                INNER JOIN modelo_maquina ON maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina
                INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina AND maquina.id_marca_maquina = marca_maquina.id_marca_maquina AND modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina
                INNER JOIN serie_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina AND maquina.id_serie_maquina = serie_maquina.id_serie_maquina
                INNER JOIN estado_maquina ON maquina.id_estado_maquina = estado_maquina.id_estado_maquina
                WHERE maquina.id_maquina=".$id_maquina;
    */

    function getDetalleFactura(){
        //Recuperamos el ID  -> 
        $id_ingreso_producto = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT proveedor.razon_social,ingreso_producto.nro_comprobante,comprobante.no_comprobante,
                ingreso_producto.fecha,(no_moneda ||' : '|| simbolo_mon) AS nombresimbolo,
                ingreso_producto.id_ingreso_producto,ingreso_producto.cs_igv
                FROM ingreso_producto
                INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
                INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
                INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
                WHERE ingreso_producto.id_ingreso_producto=".$id_ingreso_producto;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }


    function get_datos_factura_importada(){
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $filtro = "";
        $filtro .= " AND ingreso_producto.id_comprobante =".(int)4;
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$almacen;
        $filtro .= " ORDER BY ingreso_producto.fecha ASC";
        $sql = "SELECT ingreso_producto.id_comprobante,ingreso_producto.nro_comprobante,ingreso_producto.fecha,
                       ingreso_producto.serie_comprobante,proveedor.razon_social,ingreso_producto.id_ingreso_producto
                FROM ingreso_producto
                INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
                WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getDetalleProd(){
        // Recuperamos el ID  ->
        $id_ingreso_producto = $this->security->xss_clean($this->uri->segment(3));
        // Consulto en Base de Datos
        $sql = "SELECT detalle_ingreso_producto.unidades,detalle_producto.no_producto,detalle_ingreso_producto.precio,
                (detalle_ingreso_producto.unidades * detalle_ingreso_producto.precio) AS valor_total,
                detalle_ingreso_producto.id_ingreso_producto,producto.id_pro
                FROM ingreso_producto
                INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
                INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
                INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
                INNER JOIN detalle_producto ON detalle_ingreso_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                WHERE detalle_ingreso_producto.id_ingreso_producto=".$id_ingreso_producto;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getProvEditar(){
        //Recuperamos el ID  -> 
        $id_proveedor = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.ruc, proveedor.pais,
                       proveedor.direccion,proveedor.telefono1
                FROM proveedor
                WHERE proveedor.id_proveedor=".$id_proveedor;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminarNombreMaquina($idnombremaquina){
    $sql = "DELETE FROM nombre_maquina WHERE id_nombre_maquina = " . $idnombremaquina . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminar_insert_salida($id){
    /*
    var_dump('prueba_model');
    var_dump($id);
    var_dump('cantidad_salida');
    $this->db->select('cantidad_salida');
    $this->db->where('id_salida_producto',$id);
    $query = $this->db->get('salida_producto');
    foreach($query->result() as $row){
        $cantidad_salida = $row->cantidad_salida;
    }
    var_dump($cantidad_salida);
    */
        $sql = "DELETE FROM salida_producto WHERE id_salida_producto = " . $id . "";
        $query = $this->db->query($sql);
        //echo $query;
        /*
        if($query->num_rows()>0){
            return $query->result();
        }
        */
        return 'eliminacion_correcta';
    }

    function eliminar_insert_kardex($id){
        $sql = "DELETE FROM kardex_producto WHERE id_kardex_producto = " . $id . "";
        $query = $this->db->query($sql);
        return 'eliminacion_correcta';
    }

    function eliminarArea($idarea){
    $sql = "DELETE FROM area WHERE id_area = " . $idarea . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminarMarcaMaquina($idmarcamaquina){
    $sql = "DELETE FROM marca_maquina WHERE id_marca_maquina = " . $idmarcamaquina . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminarmodelomaquina($idmodelomaquina){
    $sql = "DELETE FROM modelo_maquina WHERE id_modelo_maquina = " . $idmodelomaquina . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminarSerieMaquina($idseriemaquina){
    $sql = "DELETE FROM serie_maquina WHERE id_serie_maquina = " . $idseriemaquina . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminarMoneda($idmoneda){
    $sql = "DELETE FROM moneda WHERE id_moneda = " . $idmoneda . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminaragente($idagente){
    $sql = "DELETE FROM agente_aduana WHERE id_agente = " . $idagente . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminarComprobante($idcomprobante){
    $sql = "DELETE FROM comprobante WHERE id_comprobante = " . $idcomprobante . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminarProducto($id_pro)
    {
        $this->db->select('id_detalle_producto');
        $this->db->where('id_pro',$id_pro);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_dp = $row->id_detalle_producto;
        }
        // Se verifica si el producto esta asociado a una factura registrada
        $this->db->select('id_detalle_producto');
        $this->db->where('id_detalle_producto',$id_dp);
        $query = $this->db->get('detalle_ingreso_producto');
        if($query->num_rows()>0){
            return 'producto_factura';
        }else{
            $this->db->select('id_pro');
            $this->db->where('id_pro',$id_pro);
            $query = $this->db->get('saldos_iniciales');
            if($query->num_rows()>0){
                return 'producto_saldo_inicial';
            }else{
                $sql = "DELETE FROM producto WHERE id_pro = " . $id_pro . "";
                $query = $this->db->query($sql);

                $sql = "DELETE FROM detalle_producto WHERE id_detalle_producto = " . $id_dp . "";
                $query = $this->db->query($sql);
                
                return 'eliminacion_correcta';
            }
        }
    }

    function validarRegistroCierre($fecha_registro)
    {
        /* Formateando la Fecha */
        $elementos = explode("-", $fecha_registro);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];

        /* Validar si el mes es diciembre 12 : sino sale fuera de rango */
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes + 1;
            $dia = 1;
        }
        /* Ubicar la fecha en un cierre posterior para la validacion */

        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada = implode("-", $array);
        /* Fin de Formateo de la Fecha*/

        $this->db->select('id_saldos_iniciales');
        $this->db->where('fecha_cierre',$fecha_formateada);
        $query = $this->db->get('saldos_iniciales');
        if($query->num_rows()>0){
            return 'periodo_cerrado';
        }else{
            return 'successfull';
        }
    }

    function eliminarTrasladoProducto($id_detalle_traslado,$almacen){

        $aux_bucle_saldos_ini = 0;

        $filtro = "";
        $filtro .= " AND detalle_traslado.id_detalle_traslado =".(int)$id_detalle_traslado;
        $sql = "SELECT traslado.fecha_traslado,detalle_traslado.id_detalle_traslado,detalle_traslado.id_detalle_producto,
                detalle_traslado.cantidad_traslado,detalle_traslado.id_traslado
                FROM traslado
                INNER JOIN detalle_traslado ON detalle_traslado.id_traslado = traslado.id_traslado
                WHERE detalle_traslado.id_detalle_traslado IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            foreach($query->result() as $row){
                $fecha_traslado = $row->fecha_traslado;
                $id_detalle_producto = $row->id_detalle_producto;
                $cantidad_traslado = $row->cantidad_traslado;
            
                $this->db->select('id_pro');
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $query = $this->db->get('producto');
                foreach($query->result() as $row){
                    $id_producto = $row->id_pro;
                }

                // Formateando la Fecha
                $elementos = explode("-", $fecha_traslado);
                $anio = $elementos[0];
                $mes = $elementos[1];
                $dia = $elementos[2];
                // Validar si el mes es diciembre 12 : sino sale fuera de rango
                if($mes == 12){
                    $anio = $anio + 1;
                    $mes_siguiente = 1;
                    $dia = 1;
                }else if($mes <= 11 ){
                    $mes_siguiente = $mes + 1;
                    $dia = 1;
                }
                // Ubicar la fecha en un cierre posterior para la validacion
                $array = array($anio, $mes_siguiente, $dia);
                $fecha_formateada = implode("-", $array);

                do{
                    // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                    // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                    $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                    $this->db->where('id_pro',$id_producto);
                    $this->db->where('fecha_cierre',$fecha_formateada);
                    $query = $this->db->get('saldos_iniciales');
                    if($query->num_rows()>0){
                        foreach($query->result() as $row){
                            $id_saldos_iniciales = $row->id_saldos_iniciales;
                            $stock_inicial = $row->stock_inicial;
                            $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                            $precio_uni_inicial = $row->precio_uni_inicial;
                        }
                        if($almacen == 1){ // Sta. Clara
                            $stock_inicial = $stock_inicial + $cantidad_traslado;
                            $stock_inicial_sta_clara = $stock_inicial_sta_clara - $cantidad_traslado;
                            $actualizar = array(
                                'stock_inicial_sta_clara'=> $stock_inicial_sta_clara,
                                'stock_inicial'=> $stock_inicial,
                            );
                            $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                            $this->db->update('saldos_iniciales', $actualizar);
                        }else if($almacen == 2){ // Sta. anita
                            $stock_inicial = $stock_inicial + $cantidad_salida;
                            $actualizar = array(
                                'stock_inicial'=> $stock_inicial,
                            );
                            $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                            $this->db->update('saldos_iniciales', $actualizar);
                        }

                        $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                        $this->db->where('fecha_auxiliar',$fecha_formateada);
                        $query = $this->db->get('monto_cierre');
                        if($query->num_rows()>0){
                            foreach($query->result() as $row){
                                $fecha_cierre = $row->fecha_cierre;
                                $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                                $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                                $fecha_auxiliar = $row->fecha_auxiliar;
                            }

                            // Sta. Clara
                            $anterior_c = ( $stock_inicial_sta_clara + $cantidad_traslado ) * $precio_uni_inicial;
                            $posterior_c = $stock_inicial_sta_clara * $precio_uni_inicial;
                            $monto_cierre_sta_clara = $monto_cierre_sta_clara - $anterior_c;
                            $monto_cierre_sta_clara = $monto_cierre_sta_clara + $posterior_c;
                            
                            // Sta. Anita
                            $anterior_a = ( $stock_inicial - $cantidad_traslado ) * $precio_uni_inicial;
                            $posterior_a = $stock_inicial * $precio_uni_inicial;
                            $monto_cierre_sta_anita = $monto_cierre_sta_anita - $anterior_a;
                            $monto_cierre_sta_anita = $monto_cierre_sta_anita + $posterior_a;

                            $monto_general_actualizado = $monto_cierre_sta_clara + $monto_cierre_sta_anita;
                            
                            $actualizar = array(
                                'monto_cierre'=> $monto_general_actualizado,
                                'monto_cierre_sta_clara'=> $monto_cierre_sta_clara,
                                'monto_cierre_sta_anita'=> $monto_cierre_sta_anita
                            );
                            $this->db->where('fecha_auxiliar',$fecha_formateada);
                            $this->db->update('monto_cierre',$actualizar);
                        }

                        // Aumentar la fecha para la siguiente busqueda de cierre // Ya se tiene la fecha con el formato correcto
                        $elementos_act = explode("-", $fecha_formateada);
                        $anio = $elementos_act[0];
                        $mes = $elementos_act[1];
                        $dia = $elementos_act[2];
                        if($mes == 12){
                            $anio = $anio + 1;
                            $mes_siguiente = 01;
                            $dia = 1;
                        }else if($mes <= 11 ){
                            $mes_siguiente = $mes + 1;
                            $dia = 1;
                        }
                        $array = array($anio, $mes_siguiente, $dia);
                        $fecha_formateada = implode("-", $array);
                    }else{
                        $aux_bucle_saldos_ini = 1;
                    }
                }while($aux_bucle_saldos_ini == 0);   
            }    
        }
        return true;
    }


    function eliminarSalidaProducto($id_salida_producto,$almacen)
    {
        $aux_bucle_saldos_ini = 0;
        $aux_bucle_saldos_ini_opcion_2 = 0;
        // $variable_permanente = 0;
        $variable_fecha = "";
        // VALIDACION DE CAMPOS DE STOCK POR AREAS NO TODOS LOS PRODUCTOS TIENEN ESTA INNFORMACION
        $stock_area_sta_clara = "";
        $stock_area_sta_anita = "";
        $id_detalle_producto_area = "";
        // Inicio del proceso - transacción
        //$this->db->trans_begin();

        /* Seleccionar datos del tabla salida_producto */
        $this->db->select('fecha,id_detalle_producto,cantidad_salida,id_area');
        $this->db->where('id_salida_producto',$id_salida_producto);
        $query = $this->db->get('salida_producto');
        foreach($query->result() as $row){
            $fecha_registro = $row->fecha;
            $id_detalle_producto = $row->id_detalle_producto;
            $id_area = $row->id_area;
            $cantidad_salida = $row->cantidad_salida;
        }
        /* Formateando la Fecha */
        $elementos = explode("-", $fecha_registro);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];

        /* Validar si el mes es diciembre 12 : sino sale fuera de rango */
        if($mes == 12){
            $anio = $anio + 1;
            $mes_siguiente = 1;
            $dia = 1;
        }else if($mes <= 11 ){
            $mes_siguiente = $mes + 1;
            $dia = 1;
        }
        /* Ubicar la fecha en un cierre posterior para la validacion */
        
        $array = array($anio, $mes_siguiente, $dia);
        $fecha_formateada = implode("-", $array);
        /* Fin de Formateo de la Fecha*/
        /* Seleccionar el id_pro (este dato se maneja en los saldos_iniciales) */
        $this->db->select('id_pro');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_producto = $row->id_pro;
        }
        /* Seleccionar el stock actual del producto para actualizar (sumar la cantidad sacada) */
        $this->db->select('stock,stock_sta_clara');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $stock = $row->stock;
            $stock_sta_clara = $row->stock_sta_clara;
        }
        /* Verificar si la salida a eliminar corresponde a un periodo de cierre anterior */
        $this->db->select('id_saldos_iniciales');
        $this->db->where('id_pro',$id_producto);
        $this->db->where('fecha_cierre',$fecha_formateada);
        $query = $this->db->get('saldos_iniciales');
        if($query->num_rows() > 0){ // Esta opcion permite realizar el registro, cuadrar kardex y modificar datos de cierre
            // return false;
            // ubicar los datos de la tabla que asocia una salida con una factura
            // regresar el stock referencial de la factura que se uso
            $this->db->select('cantidad_utilizada,id_ingreso_producto');
            $this->db->where('id_salida_producto',$id_salida_producto);
            $query = $this->db->get('adm_facturas_asociadas');
            foreach($query->result() as $row){
                $cantidad_utilizada = $row->cantidad_utilizada;
                $id_ingreso_producto = $row->id_ingreso_producto;
                // Obtener los datos del detalle de la factura
                $this->db->select('unidades_referencial');
                $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $query = $this->db->get('detalle_ingreso_producto');
                foreach($query->result() as $row){
                    $unidades_referencial = $row->unidades_referencial;
                }
                // Regresar el stock en la factura que corresponde
                $unidades_actualizada = $unidades_referencial + $cantidad_utilizada;
                $actualizar_referencia = array(
                    'unidades_referencial'=> $unidades_actualizada
                );
                $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('detalle_ingreso_producto', $actualizar_referencia);
            }
            // Eliminar el registro de la factura asociada a la salida
            $sql = "DELETE FROM adm_facturas_asociadas WHERE id_salida_producto = " . $id_salida_producto . "";
            $query = $this->db->query($sql);
            /* Seleccionar el id_kardex_producto a eliminar */
            $this->db->select('id_kardex_producto');
            $this->db->where('num_comprobante',$id_salida_producto);
            $this->db->where('descripcion','SALIDA');
            $query = $this->db->get('kardex_producto');
            foreach($query->result() as $row){
                $id_kardex_producto_eliminado = $row->id_kardex_producto;
            }
            // Fin de selección
            // antes de eliminar valido si este registro en el kardex es el ultimo del mes
            // Formateo la fecha para la validacion
            $elementos = explode("-", $fecha_registro);
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
            $fecha_formateada_validacion = implode("-", $array);
            // Procedimiento de validacion
            $unico_id_kardex_producto = "";
            $this->db->select('id_kardex_producto');
            $this->db->where('fecha_registro >=',$fecha_registro);
            $this->db->where('fecha_registro <',date($fecha_formateada_validacion));
            $this->db->where('id_kardex_producto >=',(int)$id_kardex_producto_eliminado);
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->order_by("fecha_registro", "asc");
            $this->db->order_by("id_kardex_producto", "asc");
            $query = $this->db->get('kardex_producto');
            $numero = count($query->result());
            if($numero == 1){
                foreach($query->result() as $row_2){
                    $unico_id_kardex_producto = $row_2->id_kardex_producto;
                    // Obtener toda la data necesario para la actualizacion del monto de cierre del mes al que corresponde
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual,id_detalle_producto,fecha_registro');
                    $this->db->where('id_kardex_producto',$unico_id_kardex_producto);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row_2){
                       // datos de kardex del movimiento eliminado
                       $id_detalle_producto_elim = $row_2->id_detalle_producto;
                       $stock_actual_elim = $row_2->stock_actual;
                       $precio_unitario_actual_promedio_elim = $row_2->precio_unitario_actual_promedio;
                       $precio_unitario_anterior_elim = $row_2->precio_unitario_anterior;
                       $descripcion_elim = $row_2->descripcion;
                       $stock_anterior_elim = $row_2->stock_anterior;
                       $cantidad_salida_elim = $row_2->cantidad_salida;
                       $cantidad_ingreso_elim = $row_2->cantidad_ingreso;
                       $precio_unitario_actual_elim = $row_2->precio_unitario_actual;
                       $fecha_registro_kardex_elim = $row_2->fecha_registro;
                    }
                }
            }

            $sql = "DELETE FROM kardex_producto WHERE id_kardex_producto = " . $id_kardex_producto_eliminado . "";
            $query = $this->db->query($sql);
            // eliminar la salida de la tabla salida_producto
            $sql = "DELETE FROM salida_producto WHERE id_salida_producto = " . $id_salida_producto . "";
            $query = $this->db->query($sql);
            /* Actualizar el stock del producto general */
            if($almacen == 1){
                $stock_sta_clara = $stock_sta_clara + $cantidad_salida;
                $actualizar = array(
                    'stock_sta_clara' => $stock_sta_clara
                );
            }else if($almacen == 2){
                $stock = $stock + $cantidad_salida;
                $actualizar = array(
                    'stock' => $stock
                );
            }
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
            /* Fin de actualizacion */
            // Actualizar el stock del producto por area
            $this->db->select('stock_area_sta_clara,stock_area_sta_anita,id_detalle_producto_area');
            $this->db->where('id_area',$id_area);
            $this->db->where('id_pro',$id_producto);
            $query = $this->db->get('detalle_producto_area');
            foreach($query->result() as $row){
                $stock_area_sta_clara = $row->stock_area_sta_clara;
                $stock_area_sta_anita = $row->stock_area_sta_anita;
                $id_detalle_producto_area = $row->id_detalle_producto_area;
            }
            if($stock_area_sta_clara != "" && $stock_area_sta_anita != "" && $id_detalle_producto_area != ""){
                if($almacen == 1){
                    $stock_area_sta_clara = $stock_area_sta_clara + $cantidad_salida;
                    $actualizar_stock_area = array(
                        'stock_area_sta_clara' => $stock_area_sta_clara
                    );
                }else if($almacen == 2){
                    $stock_area_sta_anita = $stock_area_sta_anita + $cantidad_salida;
                    $actualizar_stock_area = array(
                        'stock_area_sta_anita' => $stock_area_sta_anita
                    );
                }
                $this->db->where('id_detalle_producto_area',$id_detalle_producto_area);
                $this->db->update('detalle_producto_area', $actualizar_stock_area);
            }
            // reorganizar los datos del kardex considerando movimientos en el mismo dia y posterior
            // Obtener el ultimo id de registro para la fecha
            $this->db->select('id_kardex_producto');
            $this->db->where('fecha_registro <=',$fecha_registro);
            $this->db->where('id_kardex_producto <',$id_kardex_producto_eliminado);
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->order_by("fecha_registro", "asc");
            $this->db->order_by("id_kardex_producto", "asc");
            $query = $this->db->get('kardex_producto');
            if(count($query->result()) > 0){
                foreach($query->result() as $row){
                    $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                }
                // Obtener los datos del ultimo registro de la fecha
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                $this->db->where('id_kardex_producto',$auxiliar);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $stock_actual = $row->stock_actual;
                    $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                    $precio_unitario_anterior = $row->precio_unitario_anterior;
                    $descripcion = $row->descripcion;
                    $precio_unitario_actual = $row->precio_unitario_actual;
                    $fecha_registro_kardex = $row->fecha_registro;
                }
                // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                if($descripcion == 'SALIDA'){
                    $precio_unitario_anterior_especial = $precio_unitario_anterior;
                }else if($descripcion == 'ENTRADA'){
                    $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                }
                // como no existe movimientos posteriores debo actualizar los datos de cierre en este momento
                // LAS ACTUALIZACIONES DE LOS SALDOS INICIALES Y EL MONTO SE DEBE HACER POR RECORRIDO DE BUSQUEDA DE CADA REGISTRO DEL KARDEX
                // Actualizar los datos de cierre de mes si la salida correspondia a un periodo ya cerrado
                $elementos = explode("-", $fecha_registro_kardex);
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
                $fecha_formateada_ultimo_registro_anterior = implode("-", $array);
                // $fecha_formateada_validacion -- Es la fecha de eliminacion del registro
                if( $fecha_formateada_validacion !=  $fecha_formateada_ultimo_registro_anterior){
                    $fecha_formateada_act_stock = $fecha_formateada_validacion;
                }else{
                    $fecha_formateada_act_stock = $fecha_formateada_ultimo_registro_anterior;
                }
                do{
                    // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                    // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                    $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                    $this->db->where('id_pro',$id_producto);
                    $this->db->where('fecha_cierre',$fecha_formateada_act_stock);
                    $query = $this->db->get('saldos_iniciales');
                    if($query->num_rows()>0){
                        foreach($query->result() as $row){
                            $id_saldos_iniciales = $row->id_saldos_iniciales;
                            $stock_inicial = $row->stock_inicial;
                            $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                            $precio_uni_inicial = $row->precio_uni_inicial;
                        }
                        if($almacen == 1){ // Sta. Clara
                            $stock_inicial_sta_clara = $stock_inicial_sta_clara + $cantidad_salida;
                            $actualizar = array(
                                'stock_inicial_sta_clara'=> $stock_inicial_sta_clara,
                                // 'precio_uni_inicial'=> $precio_unitario_anterior_especial
                            );
                            $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                            $this->db->update('saldos_iniciales', $actualizar);
                        }else if($almacen == 2){ // Sta. anita
                            $stock_inicial = $stock_inicial + $cantidad_salida;
                            $actualizar = array(
                                'stock_inicial'=> $stock_inicial,
                                // 'precio_uni_inicial'=> $precio_unitario_anterior_especial
                            );
                            $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                            $this->db->update('saldos_iniciales', $actualizar);
                        }
                        // Aumentar la fecha para la siguiente busqueda de cierre // Ya se tiene la fecha con el formato correcto
                        $elementos_act = explode("-", $fecha_formateada_act_stock);
                        $anio = $elementos_act[0];
                        $mes = $elementos_act[1];
                        $dia = $elementos_act[2];
                        if($mes == 12){
                            $anio = $anio + 1;
                            $mes_siguiente = 01;
                            $dia = 1;
                        }else if($mes <= 11 ){
                            $mes_siguiente = $mes + 1;
                            $dia = 1;
                        }
                        $array = array($anio, $mes_siguiente, $dia);
                        $fecha_formateada_act_stock = implode("-", $array);
                    }else{
                        $aux_bucle_saldos_ini = 1;
                    }
                }while($aux_bucle_saldos_ini == 0);
                // Considero si la salida eliminada corresponde al ultimo registro del mes
                // para actualizar el monto de cierre del mes al que corresponde
                if($unico_id_kardex_producto != ""){
                    // Obtengo las variables necesarias del ultimo movimiento
                    // El stock anterior viene a ser el stock actual del movimiento anterior
                    $new_stock_anterior_act_cierre = $stock_actual; // stock_anterior
                    $new_precio_unitario_anterior_cierre = $precio_unitario_anterior_especial; // precio_unitario_anterior
                    if($descripcion_elim == 'ENTRADA'){
                        $new_precio_unitario_cierre = (($new_stock_anterior_act_cierre*$new_precio_unitario_anterior_cierre)+($cantidad_ingreso_elim*$precio_unitario_actual_elim))/($new_stock_anterior_act_cierre+$cantidad_ingreso_elim);
                        $precio_antes_actualizacion_cierre = $precio_unitario_actual_promedio_elim;
                    }else if($descripcion_elim == 'SALIDA'){
                        $new_precio_unitario_cierre = $new_precio_unitario_anterior_cierre;
                        $precio_antes_actualizacion_cierre = $precio_unitario_actual_elim;
                    }

                    // Obtengo los stock de cierre actualizados en el paso anterior
                    $stock_general_cierre_ultimo = $stock_inicial + $stock_inicial_sta_clara;
                    $monto_parcial_producto_anterior_cierre = $precio_antes_actualizacion_cierre * ( $stock_general_cierre_ultimo - $cantidad_salida );
                    $monto_parcial_producto_nuevo_cierre = $new_precio_unitario_anterior_cierre * $stock_general_cierre_ultimo;

                    $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                    $this->db->where('fecha_auxiliar',$fecha_formateada_validacion);
                    $query = $this->db->get('monto_cierre');
                    if($query->num_rows()>0){
                        foreach($query->result() as $row){
                            $fecha_cierre = $row->fecha_cierre;
                            $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                            $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                            $fecha_auxiliar = $row->fecha_auxiliar;
                        }
                        if($almacen == 1){ // Sta. Clara
                            $monto_cierre_sta_clara = $monto_cierre_sta_clara - $monto_parcial_producto_anterior_cierre;
                            $monto_cierre_sta_clara = $monto_cierre_sta_clara + $monto_parcial_producto_nuevo_cierre;
                            // Nuevo monto de cierre general
                            $monto_general_actualizado = $monto_cierre_sta_clara + $monto_cierre_sta_anita;
                            // echo ' 1. monto_general_actualizado '.$monto_cierre_sta_clara;
                            $actualizar = array(
                                'monto_cierre'=> $monto_general_actualizado,
                                'monto_cierre_sta_clara'=> $monto_cierre_sta_clara
                            );
                            $this->db->where('fecha_auxiliar',$fecha_formateada_validacion);
                            $this->db->update('monto_cierre',$actualizar);
                        }
                    }
                }
                // Hasta este punto se obitiene los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso
                // Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización
                $id_kardex_producto = "";
                $auxiliar_contador = 0;
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro >=',$fecha_registro);
                $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row_2){
                        // Procedimiento
                        $id_kardex_producto = $row_2->id_kardex_producto; /* ID del movimiento en el kardex */
                        /* Obtener los datos del movimiento del kardex */
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
                            // Actualización del registro
                            if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                if($auxiliar_contador == 0){
                                    /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                    $new_stock_anterior_act = $stock_actual; // stock_anterior
                                    $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                    'precio_unitario'=> $precio_unitario_actual_promedio_final,
                                    'stock' => $stock_actual_final
                                );
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->update('detalle_producto', $actualizar_p_u_2);
                            }else if($descripcion_act == 'SALIDA'){
                                if($auxiliar_contador == 0){
                                    /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                    $new_stock_anterior_act = $stock_actual; // stock_anterior
                                    $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                $actualizar_p_u_2 = array(
                                    'stock' => $stock_actual_final
                                );
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->update('detalle_producto', $actualizar_p_u_2);
                            }if($descripcion_act == 'IMPORTACION'){
                                if($auxiliar_contador == 0){
                                    /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                    $new_stock_anterior_act = $stock_actual; // stock_anterior
                                    $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                $unidades_utilizadas = $cantidad_ingreso_act;
                                $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                            }else if($descripcion_act == 'SALIDA'){
                                $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                $unidades_utilizadas = $cantidad_salida_act;
                                $precio_antes_actualizacion = $precio_unitario_actual_act;
                            }else if($descripcion_act == 'IMPORTACION'){
                                $new_precio_unitario_anterior_act = 0;
                                $unidades_utilizadas = $cantidad_ingreso_act;
                                $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                            }
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
                            // ESTA FECHA ME PERMITE SELECCIONAR PRECIO UNITARIO CON EL QUE SE GUARDO 

                            // Formato
                            $this->db->select('id_kardex_producto');
                            $this->db->where('fecha_registro >=',$fecha_registro_kardex_post);
                            $this->db->where('fecha_registro <',date($fecha_formateada_post));
                            $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $this->db->order_by("fecha_registro", "asc");
                            $this->db->order_by("id_kardex_producto", "asc");
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row_3){
                                $id_kardex_producto_ultimo = $row_3->id_kardex_producto;
                            }
                        }
                        // LAS ACTUALIZACIONES DE LOS SALDOS INICIALES Y EL MONTO SE DEBE HACER POR RECORRIDO DE BUSQUEDA DE CADA REGISTRO DEL KARDEX
                        // Actualizar los datos de cierre de mes si la salida correspondia a un periodo ya cerrado
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
                        $fecha_formateada = implode("-", $array);
                        // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                        // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                        $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                        $this->db->where('id_pro',$id_producto);
                        $this->db->where('fecha_cierre',$fecha_formateada);
                        $query = $this->db->get('saldos_iniciales');
                        if($query->num_rows()>0){
                            foreach($query->result() as $row){
                                $id_saldos_iniciales = $row->id_saldos_iniciales;
                                $stock_inicial = $row->stock_inicial;
                                $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                                $precio_uni_inicial = $row->precio_uni_inicial; // todavia no esta actualizado
                            }
                            // Validacion
                            if($id_kardex_producto == $id_kardex_producto_ultimo){
                                if($almacen == 1){ // Sta. Clara
                                    $actualizar = array(
                                        'precio_uni_inicial'=> $new_precio_unitario_anterior_act
                                    );
                                    $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                    $this->db->update('saldos_iniciales', $actualizar);
                                }else if($almacen == 2){ // Sta. anita
                                    $actualizar = array(
                                        'precio_uni_inicial'=> $new_precio_unitario_anterior_act
                                    );
                                    $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                    $this->db->update('saldos_iniciales', $actualizar);
                                }
                                // Actualizar monto final de cierre del mes
                                // Obtengo los stock de cierre actualizados en el paso anterior
                                $stock_general_cierre = $stock_inicial + $stock_inicial_sta_clara;
                                $monto_parcial_producto_anterior = $precio_antes_actualizacion * ( $stock_general_cierre - $cantidad_salida );
                                $monto_parcial_producto_nuevo = $new_precio_unitario_anterior_act * $stock_general_cierre;
                                // Seleccionar el monto de cierre
                                $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                                $this->db->where('fecha_auxiliar',$fecha_formateada);
                                $query = $this->db->get('monto_cierre');
                                if($query->num_rows()>0){
                                    foreach($query->result() as $row){
                                        $fecha_cierre = $row->fecha_cierre;
                                        $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                                        $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                                        $fecha_auxiliar = $row->fecha_auxiliar;
                                    }
                                    if($almacen == 1){ // Sta. Clara
                                        $monto_cierre_sta_clara = $monto_cierre_sta_clara - $monto_parcial_producto_anterior;
                                        $monto_cierre_sta_clara = $monto_cierre_sta_clara + $monto_parcial_producto_nuevo;
                                        // Nuevo monto de cierre general
                                        $monto_general_actualizado = $monto_cierre_sta_clara + $monto_cierre_sta_anita;
                                        // echo ' 1. monto_general_actualizado '.$monto_cierre_sta_clara;
                                        $actualizar = array(
                                            'monto_cierre'=> $monto_general_actualizado,
                                            'monto_cierre_sta_clara'=> $monto_cierre_sta_clara
                                        );
                                        $this->db->where('fecha_auxiliar',$fecha_formateada);
                                        $this->db->update('monto_cierre',$actualizar);
                                    }else if($almacen == 2){ // Sta. anita
                                        $monto_cierre_sta_anita = $monto_cierre_sta_anita - $monto_parcial_producto_anterior;
                                        $monto_cierre_sta_anita = $monto_cierre_sta_anita + $monto_parcial_producto_nuevo;
                                        // Nuevo monto de cierre general
                                        $monto_general_actualizado = $monto_cierre_sta_anita + $monto_cierre_sta_clara;
                                        $actualizar = array(
                                            'monto_cierre'=> $monto_general_actualizado,
                                            'monto_cierre_sta_anita'=> $monto_cierre_sta_anita
                                        );
                                        $this->db->where('fecha_auxiliar',$fecha_formateada);
                                        $this->db->update('monto_cierre',$actualizar);
                                    }
                                }
                            }
                            // Limpiar variabls
                            $id_saldos_iniciales = " ";
                            $stock_inicial = " ";
                            $stock_inicial_sta_clara = " ";
                            $precio_uni_inicial = " ";
                        }
                    }
                }
                return true;
            }
        }else{
            // ubicar los datos de la tabla que asocia una salida con una factura
            // regresar el stock referencial de la factura que se uso
            /*
            $this->db->select('cantidad_utilizada,id_ingreso_producto');
            $this->db->where('id_salida_producto',$id_salida_producto);
            $query = $this->db->get('adm_facturas_asociadas');
            foreach($query->result() as $row){
                $cantidad_utilizada = $row->cantidad_utilizada;
                $id_ingreso_producto = $row->id_ingreso_producto;
                // Obtener los datos del detalle de la factura
                $this->db->select('unidades_referencial');
                $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $query = $this->db->get('detalle_ingreso_producto');
                foreach($query->result() as $row){
                    $unidades_referencial = $row->unidades_referencial;
                }
                // Regresar el stock en la factura que corresponde
                $unidades_actualizada = $unidades_referencial + $cantidad_utilizada;
                $actualizar_referencia = array(
                    'unidades_referencial'=> $unidades_actualizada
                );
                $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('detalle_ingreso_producto', $actualizar_referencia);
            }
            */
            // Eliminar el registro de la factura asociada a la salida
            /*
            $sql = "DELETE FROM adm_facturas_asociadas WHERE id_salida_producto = " . $id_salida_producto . "";
            $query = $this->db->query($sql);
            */
            /* Seleccionar el id_kardex_producto a eliminar */
            $this->db->select('id_kardex_producto');
            $this->db->where('num_comprobante',$id_salida_producto);
            $this->db->where('descripcion','SALIDA');
            $query = $this->db->get('kardex_producto');
            foreach($query->result() as $row){
                $id_kardex_producto_eliminado = $row->id_kardex_producto;
            }
            /* Fin de selección */
            $sql = "DELETE FROM kardex_producto WHERE id_kardex_producto = " . $id_kardex_producto_eliminado . "";
            $query = $this->db->query($sql);
            // eliminar la salida de la tabla salida_producto
            $sql = "DELETE FROM salida_producto WHERE id_salida_producto = " . $id_salida_producto . "";
            $query = $this->db->query($sql);
            /* Actualizar el stock del producto general */
            /*
            if($almacen == 1){
                $stock_sta_clara = $stock_sta_clara + $cantidad_salida;
                $actualizar = array(
                    'stock_sta_clara' => $stock_sta_clara
                );
            }else if($almacen == 2){
                $stock = $stock + $cantidad_salida;
                $actualizar = array(
                    'stock' => $stock
                );
            }
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
            */
            /* Fin de actualizacion */
            // Actualizar el stock del producto por area
            /*
            $this->db->select('stock_area_sta_clara,stock_area_sta_anita,id_detalle_producto_area');
            $this->db->where('id_area',$id_area);
            $this->db->where('id_pro',$id_producto);
            $query = $this->db->get('detalle_producto_area');
            foreach($query->result() as $row){
                $stock_area_sta_clara = $row->stock_area_sta_clara;
                $stock_area_sta_anita = $row->stock_area_sta_anita;
                $id_detalle_producto_area = $row->id_detalle_producto_area;
            }
            if($almacen == 1){
                $stock_area_sta_clara = $stock_area_sta_clara + $cantidad_salida;
                $actualizar_stock_area = array(
                    'stock_area_sta_clara' => $stock_area_sta_clara
                );
            }else if($almacen == 2){
                $stock_area_sta_anita = $stock_area_sta_anita + $cantidad_salida;
                $actualizar_stock_area = array(
                    'stock_area_sta_anita' => $stock_area_sta_anita
                );
            }
            $this->db->where('id_detalle_producto_area',$id_detalle_producto_area);
            $this->db->update('detalle_producto_area', $actualizar_stock_area);
            */
            // reorganizar los datos del kardex considerando movimientos en el mismo dia y posterior
            // Obtener el ultimo id de registro para la fecha
            $this->db->select('id_kardex_producto');
            $this->db->where('fecha_registro <=',$fecha_registro);
            $this->db->where('id_kardex_producto <',$id_kardex_producto_eliminado);
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->order_by("fecha_registro", "asc");
            $this->db->order_by("id_kardex_producto", "asc");
            $query = $this->db->get('kardex_producto');
            if(count($query->result()) > 0){
                foreach($query->result() as $row){
                    $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                }
                // Obtener los datos del ultimo registro de la fecha
                $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                $this->db->where('id_kardex_producto',$auxiliar);
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $stock_actual = $row->stock_actual;
                    $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                    $precio_unitario_anterior = $row->precio_unitario_anterior;
                    $descripcion = $row->descripcion;
                    $precio_unitario_actual = $row->precio_unitario_actual;
                    $fecha_registro_kardex = $row->fecha_registro;
                }
                // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                if($descripcion == 'SALIDA'){
                    $precio_unitario_anterior_especial = $precio_unitario_anterior;
                }else if($descripcion == 'ENTRADA'){
                    $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                }
                // Hasta este punto se obitiene los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso
                // Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización
                $id_kardex_producto = "";
                $auxiliar_contador = 0;
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro >=',$fecha_registro);
                $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row_2){
                        $id_kardex_producto = $row_2->id_kardex_producto; /* ID del movimiento en el kardex */
                        /* Obtener los datos del movimiento del kardex */
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
                            // Actualización del registro
                            if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                if($auxiliar_contador == 0){
                                    /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                    $new_stock_anterior_act = $stock_actual; // stock_anterior
                                    $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                    $new_stock_anterior_act = $stock_actual; // stock_anterior
                                    $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                    $new_stock_anterior_act = $stock_actual; // stock_anterior
                                    $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                $unidades_utilizadas = $cantidad_ingreso_act;
                                $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                            }else if($descripcion_act == 'SALIDA'){
                                $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                $unidades_utilizadas = $cantidad_salida_act;
                                $precio_antes_actualizacion = $precio_unitario_actual_act;
                            }else if($descripcion_act == 'IMPORTACION'){
                                $new_precio_unitario_anterior_act = 0;
                                $unidades_utilizadas = $cantidad_ingreso_act;
                                $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                            }
                        }
                    }
                }else{
                    return true;
                }
            }
            return true; // Esta opcion me permite realizar el registro, cuadrar el kardex pero no se modifica datos de cierre ya que es un periodo actual
        }
        // Fin del proceso - transacción
        //$this->db->trans_complete();
    }

    function updateIndice(){
        $i=1;
        $filtro = "";
        $filtro .= " ORDER BY detalle_producto.id_detalle_producto ASC";
        $sql = "SELECT detalle_producto.id_detalle_producto
                FROM detalle_producto
                WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $id_detalle_producto = $row->id_detalle_producto;
            if($i<=500){
                $actualizar = array(
                    'indice' => 1
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>500 && $i<=1000){
                $actualizar = array(
                    'indice' => 2
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>1000 && $i<=1500){
                $actualizar = array(
                    'indice' => 3
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>1500 && $i<=2000){
                $actualizar = array(
                    'indice' => 4
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>2000 && $i<=2500){
                $actualizar = array(
                    'indice' => 5
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>2500 && $i<=3000){
                $actualizar = array(
                    'indice' => 6
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>3000 && $i<=3500){
                $actualizar = array(
                    'indice' => 7
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>3500 && $i<=4000){
                $actualizar = array(
                    'indice' => 8
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }else if($i>4000 && $i<=4500){
                $actualizar = array(
                    'indice' => 9
                );
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->update('producto', $actualizar);
                $i++;
            }
        }
        return TRUE;
    }

    function validar_registros_producto_periodo($fechainicial, $fechafinal, $id_detalle_producto){
        $id_kardex_producto_ultimo = "";

        $this->db->select('id_kardex_producto');
        $this->db->where('fecha_registro >=',date($fechainicial));
        $this->db->where('fecha_registro <=',date($fechafinal));
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $this->db->order_by("fecha_registro", "asc");
        $this->db->order_by("id_kardex_producto", "asc");
        $query = $this->db->get('kardex_producto');
        foreach($query->result() as $row){
            $id_kardex_producto_ultimo = $row->id_kardex_producto;
        }
        if($id_kardex_producto_ultimo == ""){
            return 'no_existe_movimiento';
        }else{
            return $id_kardex_producto_ultimo;
        }
    }

    function cierre_almacen_montos_2016($fecha_formateada,$nombre_mes){
        $sumatoria = 0;
        $filtro = "";
        $filtro .= " AND DATE(saldos_iniciales.fecha_cierre) ='".$fecha_formateada."'";
        $sql = "SELECT saldos_iniciales.id_saldos_iniciales,saldos_iniciales.id_pro,saldos_iniciales.fecha_cierre,saldos_iniciales.stock_inicial,saldos_iniciales.precio_uni_inicial,saldos_iniciales.stock_inicial_sta_clara
                FROM saldos_iniciales
                WHERE saldos_iniciales.id_saldos_iniciales IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        foreach($query->result() as $row){
            $id_pro = $row->id_pro;
            $stock_inicial_sta_anita = $row->stock_inicial;
            $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
            $precio_unitario = $row->precio_uni_inicial;

            $stock_general = $stock_inicial_sta_anita + $stock_inicial_sta_clara;
            $sumatoria = $sumatoria + ($stock_general*$precio_unitario);
        }
        $datos_cierre_mes = array(
            "fecha_cierre" => $fecha_formateada,
            "monto_cierre" => $sumatoria,
            "monto_cierre_sta_anita" => $sumatoria,
            "monto_cierre_sta_clara" => 0,
            "nombre_mes" => $nombre_mes,
            "fecha_auxiliar" => $fecha_formateada
        );
        $this->db->insert('monto_cierre', $datos_cierre_mes);
        return true;
    }

    public function insert_saldos_iniciales($datos)
    {   
        $last_id = $this->db->insert('saldos_iniciales', $datos);
        if($last_id != ""){
            return $this->db->insert_id();
        }else{
            return "error_inesperado";
        }
    }

    function validar_registros_producto_kardex($id_detalle_producto){
        $id_kardex_producto_ultimo = "";
        $this->db->select('id_kardex_producto');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $this->db->order_by("fecha_registro", "asc");
        $this->db->order_by("id_kardex_producto", "asc");
        $query = $this->db->get('kardex_producto');
        foreach($query->result() as $row){
            $id_kardex_producto_ultimo = $row->id_kardex_producto;
        }
        if($id_kardex_producto_ultimo == ""){
            return 'no_existe_movimiento';
        }else{
            return $id_kardex_producto_ultimo;
        }
    }

    function eliminarProveedor($idproveedor){
        $this->db->select('id_proveedor');
        $this->db->where('id_proveedor',$idproveedor);
        $query = $this->db->get('ingreso_producto');
        if($query->num_rows()>0){
            return false;
        }else{
            $sql = "DELETE FROM proveedor WHERE id_proveedor = " . $idproveedor . "";
            $query = $this->db->query($sql);
            return true;
        }
    }

    function eliminarMaquina($idmaquina){
    $sql = "DELETE FROM maquina WHERE id_maquina = " . $idmaquina . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function eliminar_parte_Maquina($id_parte_maquina){
    $sql = "DELETE FROM parte_maquina WHERE id_parte_maquina = " . $id_parte_maquina . "";
    $query = $this->db->query($sql); 
    if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function actualizaNombreMaquina(){
        //Recuperamos el ID  -> 
        $id_nombre_maquina = $this->security->xss_clean($this->uri->segment(3));
        $nombremaq = strtoupper($this->security->xss_clean($this->input->post('editnombremaq')));
        //Verifico si existe
        $this->db->where('nombre_maquina',$nombremaq);
        $query = $this->db->get('nombre_maquina');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'nombre_maquina' => $nombremaq
            );
            $this->db->where('id_nombre_maquina',$id_nombre_maquina);
            $this->db->update('nombre_maquina', $actualizar);
            return true;
        }
    }

    function actualizaArea(){
        //Recuperamos el ID  -> 
        $id_area = $this->security->xss_clean($this->uri->segment(3));
        $area = strtoupper($this->security->xss_clean($this->input->post('editarea')));
        $responsable = strtoupper($this->security->xss_clean($this->input->post('nombre_encargado')));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        //Verifico si existe
        $this->db->where('no_area',$area);
        $this->db->where('id_almacen',$almacen);
        $this->db->where('encargado',$responsable);
        $query = $this->db->get('area');
        if($query->num_rows()>0){
                return false;
        }else{
            if( $almacen == 1 ){
                $actualizar = array(
                    'no_area' => $area,
                    'encargado_sta_clara' => $responsable
                );
                $this->db->where('id_area',$id_area);
                $this->db->update('area', $actualizar);
            }else if( $almacen == 2 ){
                $actualizar = array(
                    'no_area' => $area,
                    'encargado' => $responsable
                );
                $this->db->where('id_area',$id_area);
                $this->db->update('area', $actualizar);
            }
            return true;
        }
    }

    function actualizaMarcaMaquina(){
        //Recuperamos el ID  -> 
        $id_marca_maquina = $this->security->xss_clean($this->uri->segment(3));
        $id_nombremaq = $this->security->xss_clean($this->input->post('editnombremaq'));
        $marcamaq = strtoupper($this->security->xss_clean($this->input->post('editmarcamaq')));
        //Verifico si existe
        $this->db->where('no_marca',$marcamaq);
        $this->db->where('id_nombre_maquina',$id_nombremaq);
        $query = $this->db->get('marca_maquina');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'no_marca' => $marcamaq,
                'id_nombre_maquina' => $id_nombremaq
            );
            $this->db->where('id_marca_maquina',$id_marca_maquina);
            $this->db->update('marca_maquina', $actualizar);
            return true;
        }
    }

    function actualizaSerieMaquina(){
        //Recuperamos el ID  -> 
        $id_serie_maquina = $this->security->xss_clean($this->uri->segment(3));
        $id_modelomaq = $this->security->xss_clean($this->input->post('editmodelomaq'));
        $seriemaq = strtoupper($this->security->xss_clean($this->input->post('editseriemaq')));
        //Verifico si existe
        $this->db->where('no_serie',$seriemaq);
        $this->db->where('id_modelo_maquina',$id_modelomaq);
        $query = $this->db->get('serie_maquina');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'no_serie' => $seriemaq,
                'id_modelo_maquina' => $id_modelomaq
            );
            $this->db->where('id_serie_maquina',$id_serie_maquina);
            $this->db->update('serie_maquina', $actualizar);
            return true;
        }
    }

    public function actualizaModeloMaquina(){
        //Recuperamos el ID  -> 
        $id_modelo_maquina = $this->security->xss_clean($this->uri->segment(3));
        $id_marcamaq = $this->security->xss_clean($this->input->post('editmarcamaq'));
        $modelomaq = strtoupper($this->security->xss_clean($this->input->post('editmodelomaq')));
        //Verifico si existe
        $this->db->where('no_modelo',$modelomaq);
        $this->db->where('id_marca_maquina',$id_marcamaq);
        $query = $this->db->get('modelo_maquina');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'no_modelo' => $modelomaq,
                'id_marca_maquina' => $id_marcamaq
            );
            $this->db->where('id_modelo_maquina',$id_modelo_maquina);
            $this->db->update('modelo_maquina', $actualizar);
            return true;
        }
    }

    public function actualizaAgente(){
        //Recuperamos el ID  -> 
        $id_agente = $this->security->xss_clean($this->uri->segment(3));
        $editnombreagente = strtoupper($this->security->xss_clean($this->input->post('editnombreagente')));
        $almacen = $this->security->xss_clean($this->session->userdata('almacen')); //Variable de sesion
        //verifico si existe
        $this->db->where('id_almacen',$almacen);
        $this->db->where('no_agente',$editnombreagente);
        $query = $this->db->get('agente_aduana');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'no_agente' => $editnombreagente
            );
            $this->db->where('id_agente',$id_agente);
            $this->db->update('agente_aduana', $actualizar);
            return true;
        } 
    }

    public function actualizaComprobante(){
        //Recuperamos el ID  -> 
        $id_comprobante = $this->security->xss_clean($this->uri->segment(3));
        $editnombrecomprobante = strtoupper($this->security->xss_clean($this->input->post('editnombrecomprobante')));
        //verifico si existe
        $this->db->where('no_comprobante',$editnombrecomprobante);
        $query = $this->db->get('comprobante');
        if($query->num_rows()>0){
                return false;
        }else{
            $actualizar = array(
                'no_comprobante' => $editnombrecomprobante
            );
            $this->db->where('id_comprobante',$id_comprobante);
            $this->db->update('comprobante', $actualizar);
            return true;
        } 
    }

    public function actualizaMoneda(){
        //Recuperamos el ID  -> 
        $id_moneda = $this->security->xss_clean($this->uri->segment(3));
        $nombremon = strtoupper($this->security->xss_clean($this->input->post('editnombremon')));
        $simmon = strtoupper($this->security->xss_clean($this->input->post('editsimbolomon')));
        //Verifico si existe
        $this->db->where('simbolo_mon',$simmon);
        $this->db->where('no_moneda',$nombremon);
        $query = $this->db->get('moneda');
        if($query->num_rows()>0){
            return false;
        }else{
            $actualizar = array(
                'no_moneda' => $nombremon,
                'simbolo_mon' => $simmon
            );
            $this->db->where('id_moneda',$id_moneda);
            $this->db->update('moneda', $actualizar);
            return true; 
        }
    }

    public function UpdatePassword(){
        //Recuperamos los datos
        $user = $this->security->xss_clean($this->input->post('user'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $datacontrasena = $this->security->xss_clean($this->input->post('datacontrasena'));

        $this->db->select('tx_contrasena');
        $this->db->where('tx_usuario',$user);
        $query = $this->db->get('usuario');
        foreach($query->result() as $row){
            $tx_contrasena = $row->tx_contrasena;
        }

        if($tx_contrasena == $password){
            $actualizar = array(
                'tx_contrasena' => $datacontrasena
            );
            $this->db->where('tx_usuario',$user);
            $this->db->update('usuario', $actualizar);
            return true;
        }else{
            return false;
        }

    }

    function actualizaMaquina(){
        //Recuperamos el ID  -> 
        $id_maquina = $this->security->xss_clean($this->uri->segment(3));
        $editnombremaquina = $this->security->xss_clean($this->input->post('editnombremaquina'));
        $estado = $this->security->xss_clean($this->input->post('editestado'));
        $obser = $this->security->xss_clean($this->input->post('editobser'));

        $actualizar = array(
            'nombre_maquina' => strtoupper($editnombremaquina),
            'id_estado_maquina'=>$estado,
            'observacion_maq'=>$obser
        );
        $this->db->where('id_maquina',$id_maquina);
        $this->db->update('maquina', $actualizar);
        return true; 
    }

    public function actualizaProducto(){
        $id_ubicacion = 0;
        $id_dp_act = null;
        $id_pro = $this->security->xss_clean($this->uri->segment(3));

        $editnombreprod = strtoupper($this->security->xss_clean($this->input->post('editnombreprod')));
        $editcat = $this->security->xss_clean($this->input->post('editcat'));
        $edittipoprod = $this->security->xss_clean($this->input->post('edittipoprod'));
        $editprocedencia = $this->security->xss_clean($this->input->post('editprocedencia'));
        $editobser = $this->security->xss_clean($this->input->post('editobser'));
        $id_uni_med = $this->security->xss_clean($this->input->post('editunid_med'));
        $nombre_ubicacion = $this->security->xss_clean($this->input->post('edit_ubicacion'));

        // id_dp del producto inicial
        $this->db->select('id_detalle_producto');
        $this->db->where('id_pro',$id_pro);
        $query = $this->db->get('producto');
        foreach($query->result() as $row){
            $id_dp = $row->id_detalle_producto;
        }

        $this->db->select('id_ubicacion');
        $this->db->where('nombre_ubicacion',$nombre_ubicacion);
        $query = $this->db->get('ubicacion');
        foreach($query->result() as $row){
            $id_ubicacion = $row->id_ubicacion;
        }

        $this->db->select('id_detalle_producto');
        $this->db->where('no_producto',$editnombreprod);
        $query = $this->db->get('detalle_producto');
        if($query->num_rows() > 0){
            foreach($query->result() as $row){
                $id_dp_act = $row->id_detalle_producto;
            }
        }

        if(($id_dp == $id_dp_act) || $id_dp_act == null){
            if($id_ubicacion != 0){
                $actualizardetalle = array(
                    'no_producto' => $editnombreprod
                );
                $this->db->where('id_detalle_producto',$id_dp);
                $this->db->update('detalle_producto', $actualizardetalle);

                $actualizar = array(
                    'id_categoria' => $editcat,
                    'id_tipo_producto' => $edittipoprod,
                    'id_procedencia'=>$editprocedencia,
                    'observacion'=>$editobser,
                    'id_unidad_medida'=>$id_uni_med,
                    'id_ubicacion'=>$id_ubicacion
                );
                $this->db->where('id_pro',$id_pro);
                $this->db->update('producto', $actualizar);
                return 'successfull';
            }else{
                return 'no_existe_ubicacion';
            }
        }else{
            return 'producto_duplicado';
        }
    }

    function eliminarRegistroIngreso($id_registro_ingreso,$almacen){
        // Consulto en Base de Datos
        $sql = "SELECT detalle_ingreso_producto.unidades,detalle_producto.no_producto,producto.id_producto,
        detalle_ingreso_producto.precio,ingreso_producto.id_ingreso_producto,detalle_producto.id_detalle_producto, detalle_producto.stock,
        detalle_producto.precio_unitario,ingreso_producto.id_moneda,ingreso_producto.fecha,ingreso_producto.cs_igv, ingreso_producto.nro_comprobante,
        detalle_producto.stock_sta_clara
        FROM ingreso_producto 
        INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
        INNER JOIN detalle_producto ON detalle_ingreso_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE ingreso_producto.id_ingreso_producto =".$id_registro_ingreso;
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {
            $id_moneda = $row->id_moneda;
            $cs_igv = $row->cs_igv;
            $fecha = $row->fecha;
            $unidades = $row->unidades; // Unidades de ingreso con la factura
            $id_producto = $row->id_producto;
            $precio_factura = $row->precio;
            $nro_comprobante = $row->nro_comprobante;

            // Validacion si el precio entro con/sin IGV
            if($cs_igv == "t"){
                $precio_csigv = $precio_factura / 1.18;
            }else if($cs_igv == "f"){
                $precio_csigv = $precio_factura;
            }

            // obtengo el Nombre de la moneda por medio del id_moneda
            $this->db->select('no_moneda');
            $this->db->where('id_moneda',$id_moneda);
            $query2 = $this->db->get('moneda');
            foreach($query2->result() as $row2){
                $no_moneda = $row2->no_moneda;
            }

            // obtengo el tipo de cambio
            $this->db->select('dolar_venta,euro_venta,fr_venta');
            $this->db->where('fecha_actual',$fecha);
            $query3 = $this->db->get('tipo_cambio');
            foreach($query3->result() as $row3){
                $dolar_venta = $row3->dolar_venta;
                $euro_venta = $row3->euro_venta;
                $fr_venta = $row3->fr_venta;
            }

            // obtengo el precio de registro del producto en soles con el tipo de cambio del dia de registro
            if($no_moneda == 'DOLARES'){
                $precio_registro_soles = $precio_csigv * $dolar_venta; 
            }else if($no_moneda == 'EURO'){
                $precio_registro_soles = $precio_csigv * $euro_venta; 
            }else if($no_moneda == 'FRANCO SUIZO'){
                $precio_registro_soles = $precio_csigv * $fr_venta; 
            }else{
                $precio_registro_soles = $precio_csigv;
            }

            $stock_general = $row->stock_sta_clara + $row->stock;
            $precio_unitario_act = (($stock_general * $row->precio_unitario) - ($unidades * $precio_registro_soles))/($stock_general - $unidades);
            if($almacen == 1){
                $stock_actualizado =  $row->stock_sta_clara - $unidades;
                $actualizar = array(
                    'stock_sta_clara' => $stock_actualizado,
                    'precio_unitario' => $precio_unitario_act
                );
            }else if($almacen == 2){
                $stock_actualizado =  $row->stock - $unidades;
                $actualizar = array(
                    'stock' => $stock_actualizado,
                    'precio_unitario' => $precio_unitario_act
                );
            }
            $this->db->where('id_detalle_producto',$row->id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);

            $sql = "DELETE FROM kardex_producto WHERE id_detalle_producto = " . $row->id_detalle_producto . " AND DATE(fecha_registro) = '" .$fecha."' AND num_comprobante = '" .$nro_comprobante."'";
            $query = $this->db->query($sql);
        }

        $sql = "DELETE FROM detalle_ingreso_producto WHERE id_ingreso_producto = " . $id_registro_ingreso . "";
        $query = $this->db->query($sql);

        $sql = "DELETE FROM ingreso_producto WHERE id_ingreso_producto = " . $id_registro_ingreso . "";
        $query = $this->db->query($sql);

        if($query->num_rows()>0){
            return $query->result();
        }
    }

    function eliminarRegistroIngreso_aleatorio($id_registro_ingreso,$almacen){
        $aux_bucle_saldos_ini = 0;
        // Consulto en Base de Datos
        $sql = "SELECT detalle_ingreso_producto.unidades,detalle_producto.no_producto,producto.id_producto,
        detalle_ingreso_producto.precio,ingreso_producto.id_ingreso_producto,detalle_producto.id_detalle_producto, detalle_producto.stock,
        detalle_producto.precio_unitario,ingreso_producto.id_moneda,ingreso_producto.fecha,ingreso_producto.cs_igv, ingreso_producto.nro_comprobante,
        detalle_producto.stock_sta_clara
        FROM ingreso_producto 
        INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
        INNER JOIN detalle_producto ON detalle_ingreso_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE ingreso_producto.id_ingreso_producto =".$id_registro_ingreso;
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {
            $id_moneda = $row->id_moneda;
            $cs_igv = $row->cs_igv;
            $fecha_registro = $row->fecha;
            $unidades_ingreso = $row->unidades; // Unidades de ingreso con la factura
            $id_producto = $row->id_producto;
            $id_detalle_producto = $row->id_detalle_producto;
            $id_ingreso_producto = $row->id_ingreso_producto;
            $precio_factura = $row->precio;
            $nro_comprobante = $row->nro_comprobante;

            /* Seleccionar el id_pro (este dato se maneja en los saldos_iniciales) */
            $this->db->select('id_pro');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('producto');
            foreach($query->result() as $row){
                $id_producto = $row->id_pro;
            }

            /* Seleccionar el stock actual del producto para actualizar (sumar la cantidad sacada) */
            $this->db->select('stock,stock_sta_clara');
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $query = $this->db->get('detalle_producto');
            foreach($query->result() as $row){
                $stock = $row->stock;
                $stock_sta_clara = $row->stock_sta_clara;
            }

            /* Formateando la Fecha */
            $elementos = explode("-", $fecha_registro);
            $anio = $elementos[0];
            $mes = $elementos[1];
            $dia = $elementos[2];

            // Validar si el mes es diciembre 12 : sino sale fuera de rango
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
            // Fin de Formateo de la Fecha
            // Verificar si la factura a eliminar corresponde a un periodo de cierre anterior 
            $this->db->select('id_saldos_iniciales');
            $this->db->where('id_pro',$id_producto);
            $this->db->where('fecha_cierre',$fecha_formateada);
            $query = $this->db->get('saldos_iniciales');
            if($query->num_rows()>0){
                // Seleccionar el id_kardex_producto a eliminar
                $this->db->select('id_kardex_producto');
                $this->db->where('num_comprobante',$nro_comprobante);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->where('descripcion','ENTRADA');
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $id_kardex_producto_eliminado = $row->id_kardex_producto;
                }
                // Fin de selección
                // Procedimiento de validacion
                $unico_id_kardex_producto = "";
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro >=',$fecha_registro);
                $this->db->where('fecha_registro <',date($fecha_formateada));
                $this->db->where('id_kardex_producto >=',(int)$id_kardex_producto_eliminado);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                $numero = count($query->result());
                if($numero == 1){
                    foreach($query->result() as $row_2){
                        $unico_id_kardex_producto = $row_2->id_kardex_producto;
                        // Obtener toda la data necesario para la actualizacion del monto de cierre del mes al que corresponde
                        $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual,id_detalle_producto,fecha_registro');
                        $this->db->where('id_kardex_producto',$unico_id_kardex_producto);
                        $query = $this->db->get('kardex_producto');
                        foreach($query->result() as $row_2){
                           // datos de kardex del movimiento eliminado
                           $id_detalle_producto_elim = $row_2->id_detalle_producto;
                           $stock_actual_elim = $row_2->stock_actual;
                           $precio_unitario_actual_promedio_elim = $row_2->precio_unitario_actual_promedio;
                           $precio_unitario_anterior_elim = $row_2->precio_unitario_anterior;
                           $descripcion_elim = $row_2->descripcion;
                           $stock_anterior_elim = $row_2->stock_anterior;
                           $cantidad_salida_elim = $row_2->cantidad_salida;
                           $cantidad_ingreso_elim = $row_2->cantidad_ingreso;
                           $precio_unitario_actual_elim = $row_2->precio_unitario_actual;
                           $fecha_registro_kardex_elim = $row_2->fecha_registro;
                        }
                        // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                        if($descripcion_elim == 'SALIDA'){
                            $precio_unitario_anterior_especial_elim = $precio_unitario_actual_elim;
                        }else if($descripcion_elim == 'ENTRADA'){
                            $precio_unitario_anterior_especial_elim = $precio_unitario_actual_promedio_elim;
                        }
                    }
                }
                // reorganizar los datos del kardex considerando movimientos en el mismo dia y posterior
                // Obtener el ultimo id de registro para la fecha de ese producto
                // Procedimiento
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro <=',$fecha_registro);
                $this->db->where('id_kardex_producto <',$id_kardex_producto_eliminado);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                    }
                    // Obtener los datos del ultimo registro de la fecha
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                    $this->db->where('id_kardex_producto',$auxiliar);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual = $row->stock_actual;
                        $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                        $precio_unitario_anterior = $row->precio_unitario_anterior;
                        $descripcion = $row->descripcion;
                        $precio_unitario_actual = $row->precio_unitario_actual;
                        $fecha_registro_kardex = $row->fecha_registro;
                    }
                    // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                    if($descripcion == 'SALIDA'){
                        $precio_unitario_anterior_especial = $precio_unitario_anterior;
                    }else if($descripcion == 'ENTRADA'){
                        $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                    }
                    $elementos = explode("-", $fecha_registro_kardex);
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
                    $fecha_formateada_ultimo_registro_anterior = implode("-", $array);
                    // $fecha_formateada -- Es la fecha de eliminacion del registro
                    if( $fecha_formateada !=  $fecha_formateada_ultimo_registro_anterior){
                        $fecha_formateada_act_stock = $fecha_formateada;
                    }else{
                        $fecha_formateada_act_stock = $fecha_formateada_ultimo_registro_anterior;
                    }
                    do{
                        // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                        // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                        $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                        $this->db->where('id_pro',$id_producto);
                        $this->db->where('fecha_cierre',$fecha_formateada_act_stock);
                        $query = $this->db->get('saldos_iniciales');
                        if($query->num_rows()>0){
                            foreach($query->result() as $row){
                                $id_saldos_iniciales = $row->id_saldos_iniciales;
                                $stock_inicial = $row->stock_inicial;
                                $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                                $precio_uni_inicial = $row->precio_uni_inicial;
                            }
                            if($almacen == 1){ // Sta. Clara
                                $stock_inicial_sta_clara = $stock_inicial_sta_clara - $unidades_ingreso;
                                $actualizar = array(
                                    'stock_inicial_sta_clara'=> $stock_inicial_sta_clara,
                                    // 'precio_uni_inicial'=> $precio_unitario_anterior_especial
                                );
                                $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                $this->db->update('saldos_iniciales', $actualizar);
                            }else if($almacen == 2){ // Sta. anita
                                $stock_inicial = $stock_inicial - $unidades_ingreso;
                                $actualizar = array(
                                    'stock_inicial'=> $stock_inicial,
                                    // 'precio_uni_inicial'=> $precio_unitario_anterior_especial
                                );
                                $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                $this->db->update('saldos_iniciales', $actualizar);
                            }
                            // Aumentar la fecha para la siguiente busqueda de cierre // Ya se tiene la fecha con el formato correcto
                            $elementos_act = explode("-", $fecha_formateada_act_stock);
                            $anio = $elementos_act[0];
                            $mes = $elementos_act[1];
                            $dia = $elementos_act[2];
                            if($mes == 12){
                                $anio = $anio + 1;
                                $mes_siguiente = 01;
                                $dia = 1;
                            }else if($mes <= 11 ){
                                $mes_siguiente = $mes + 1;
                                $dia = 1;
                            }
                            $array = array($anio, $mes_siguiente, $dia);
                            $fecha_formateada_act_stock = implode("-", $array);
                        }else{
                            $aux_bucle_saldos_ini = 1;
                        }
                    }while($aux_bucle_saldos_ini == 0);

                    // Considero si el movimiento en el kardex del producto de la factura eliminada corresponde al ultimo registro del mes
                    // para actualizar el monto de cierre del mes al que corresponde
                    if($unico_id_kardex_producto != ""){
                        // Obtengo las variables necesarias del ultimo movimiento
                        // El stock anterior viene a ser el stock actual del movimiento anterior
                        $new_stock_anterior_act_cierre = $stock_actual; // stock_anterior
                        $new_precio_unitario_anterior_cierre = $precio_unitario_anterior_especial; // precio_unitario_anterior
                        if($descripcion_elim == 'ENTRADA'){
                            $new_precio_unitario_cierre = (($new_stock_anterior_act_cierre*$new_precio_unitario_anterior_cierre)+($cantidad_ingreso_elim*$precio_unitario_actual_elim))/($new_stock_anterior_act_cierre+$cantidad_ingreso_elim);
                            $precio_antes_actualizacion_cierre = $precio_unitario_actual_promedio_elim;
                        }else if($descripcion_elim == 'SALIDA'){
                            $new_precio_unitario_cierre = $new_precio_unitario_anterior_cierre;
                            $precio_antes_actualizacion_cierre = $precio_unitario_actual_elim;
                        }

                        // Obtengo los stock de cierre actualizados en el paso anterior
                        $stock_general_cierre_ultimo = $stock_inicial + $stock_inicial_sta_clara;
                        $monto_parcial_producto_anterior_cierre = $precio_antes_actualizacion_cierre * ( $stock_general_cierre_ultimo + $unidades_ingreso );
                        $monto_parcial_producto_nuevo_cierre = $new_precio_unitario_anterior_cierre * $stock_general_cierre_ultimo;

                        $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                        $this->db->where('fecha_auxiliar',$fecha_formateada);
                        $query = $this->db->get('monto_cierre');
                        if($query->num_rows()>0){
                            foreach($query->result() as $row){
                                $fecha_cierre = $row->fecha_cierre;
                                $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                                $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                                $fecha_auxiliar = $row->fecha_auxiliar;
                            }
                            if($almacen == 1){ // Sta. Clara
                                $monto_cierre_sta_clara = $monto_cierre_sta_clara - $monto_parcial_producto_anterior_cierre;
                                $monto_cierre_sta_clara = $monto_cierre_sta_clara + $monto_parcial_producto_nuevo_cierre;
                                // Nuevo monto de cierre general
                                $monto_general_actualizado = $monto_cierre_sta_clara + $monto_cierre_sta_anita;
                                // echo ' 1. monto_general_actualizado '.$monto_cierre_sta_clara;
                                $actualizar = array(
                                    'monto_cierre'=> $monto_general_actualizado,
                                    'monto_cierre_sta_clara'=> $monto_cierre_sta_clara
                                );
                                $this->db->where('fecha_auxiliar',$fecha_formateada);
                                $this->db->update('monto_cierre',$actualizar);
                            }
                        }
                    }
                    // Hasta este punto se obitiene los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso
                    // Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización
                    $id_kardex_producto = "";
                    $auxiliar_contador = 0;
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro >=',$fecha_registro);
                    $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row_2){
                            // Procedimiento
                            $id_kardex_producto = $row_2->id_kardex_producto; // ID del movimiento en el kardex
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
                                // Actualización del registro
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                        'precio_unitario'=> $precio_unitario_actual_promedio_final,
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'SALIDA'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                    $actualizar_p_u_2 = array(
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'IMPORTACION'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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

                                if($stock_actual_final < 0){
                                    var_dump($id_detalle_producto).' ';
                                }

                                /* Dejar variables con el ultimo registro del stock y precio unitario obtenido */
                                /* Este paso se realizo en la linea 4277 pero solo sirvio para un recorrido */
                                $new_stock_anterior_act = $stock_actual_final;
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                                    $unidades_utilizadas = $cantidad_ingreso_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                                }else if($descripcion_act == 'SALIDA'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                    $unidades_utilizadas = $cantidad_salida_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_act;
                                }
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
                                // ESTA FECHA ME PERMITE SELECCIONAR PRECIO UNITARIO CON EL QUE SE GUARDO
                                // Formato
                                $this->db->select('id_kardex_producto');
                                $this->db->where('fecha_registro >=',$fecha_registro_kardex_post);
                                $this->db->where('fecha_registro <',date($fecha_formateada_post));
                                $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->order_by("fecha_registro", "asc");
                                $this->db->order_by("id_kardex_producto", "asc");
                                $query = $this->db->get('kardex_producto');
                                foreach($query->result() as $row_3){
                                    $id_kardex_producto_ultimo = $row_3->id_kardex_producto;
                                }
                            }
                            // LAS ACTUALIZACIONES DE LOS SALDOS INICIALES Y EL MONTO SE DEBE HACER POR RECORRIDO DE BUSQUEDA DE CADA REGISTRO DEL KARDEX
                            // Actualizar los datos de cierre de mes si la salida correspondia a un periodo ya cerrado
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
                            $fecha_formateada = implode("-", $array);
                            // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                            // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                            $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                            $this->db->where('id_pro',$id_producto);
                            $this->db->where('fecha_cierre',$fecha_formateada);
                            $query = $this->db->get('saldos_iniciales');
                            if($query->num_rows()>0){
                                foreach($query->result() as $row){
                                    $id_saldos_iniciales = $row->id_saldos_iniciales;
                                    $stock_inicial = $row->stock_inicial;
                                    $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                                    $precio_uni_inicial = $row->precio_uni_inicial; // todavia no esta actualizado
                                }
                                // Validacion
                                if($id_kardex_producto == $id_kardex_producto_ultimo){
                                    if($almacen == 1){ // Sta. Clara
                                        $actualizar = array(
                                            'precio_uni_inicial'=> $new_precio_unitario_anterior_act
                                        );
                                        $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                        $this->db->update('saldos_iniciales', $actualizar);
                                    }else if($almacen == 2){ // Sta. anita
                                        $actualizar = array(
                                            'precio_uni_inicial'=> $new_precio_unitario_anterior_act
                                        );
                                        $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                        $this->db->update('saldos_iniciales', $actualizar);
                                    }
                                    // Actualizar monto final de cierre del mes
                                    // Obtengo los stock de cierre actualizados en el paso anterior
                                    $stock_general_cierre = $stock_inicial + $stock_inicial_sta_clara;
                                    $monto_parcial_producto_anterior = $precio_antes_actualizacion * ( $stock_general_cierre + $unidades_ingreso );
                                    $monto_parcial_producto_nuevo = $new_precio_unitario_anterior_act * $stock_general_cierre;
                                    // Seleccionar el monto de cierre
                                    $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                                    $this->db->where('fecha_auxiliar',$fecha_formateada);
                                    $query = $this->db->get('monto_cierre');
                                    if($query->num_rows()>0){
                                        foreach($query->result() as $row){
                                            $fecha_cierre = $row->fecha_cierre;
                                            $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                                            $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                                            $fecha_auxiliar = $row->fecha_auxiliar;
                                        }
                                        if($almacen == 1){ // Sta. Clara
                                            $monto_cierre_sta_clara = $monto_cierre_sta_clara - $monto_parcial_producto_anterior;
                                            $monto_cierre_sta_clara = $monto_cierre_sta_clara + $monto_parcial_producto_nuevo;
                                            // Nuevo monto de cierre general
                                            $monto_general_actualizado = $monto_cierre_sta_clara + $monto_cierre_sta_anita;
                                            // echo ' 1. monto_general_actualizado '.$monto_cierre_sta_clara;
                                            $actualizar = array(
                                                'monto_cierre'=> $monto_general_actualizado,
                                                'monto_cierre_sta_clara'=> $monto_cierre_sta_clara
                                            );
                                            $this->db->where('fecha_auxiliar',$fecha_formateada);
                                            $this->db->update('monto_cierre',$actualizar);
                                        }else if($almacen == 2){ // Sta. anita
                                            $monto_cierre_sta_anita = $monto_cierre_sta_anita - $monto_parcial_producto_anterior;
                                            $monto_cierre_sta_anita = $monto_cierre_sta_anita + $monto_parcial_producto_nuevo;
                                            // Nuevo monto de cierre general
                                            $monto_general_actualizado = $monto_cierre_sta_anita + $monto_cierre_sta_clara;
                                            $actualizar = array(
                                                'monto_cierre'=> $monto_general_actualizado,
                                                'monto_cierre_sta_anita'=> $monto_cierre_sta_anita
                                            );
                                            $this->db->where('fecha_auxiliar',$fecha_formateada);
                                            $this->db->update('monto_cierre',$actualizar);
                                        }
                                    }
                                }
                                // Limpiar variabls
                                $id_saldos_iniciales = " ";
                                $stock_inicial = " ";
                                $stock_inicial_sta_clara = " ";
                                $precio_uni_inicial = " ";
                            }
                        }
                    }
                    // Eliminar el registro del kardex del producto
                    $sql = "DELETE FROM kardex_producto WHERE id_detalle_producto = " . $id_detalle_producto . " AND DATE(fecha_registro) = '" .$fecha_registro."' AND num_comprobante = '" .$nro_comprobante."'";
                    $query = $this->db->query($sql);
                }else{
                    // Obtener los datos del ultimo registro de la fecha
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                    $this->db->where('id_kardex_producto',$id_kardex_producto_eliminado);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual = 0;
                        $precio_unitario_actual_promedio = 0;
                        $precio_unitario_anterior = 0;
                        $descripcion = $row->descripcion;
                        $precio_unitario_actual = 0;
                        $fecha_registro_kardex = $row->fecha_registro;
                    }
                    // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                    if($descripcion == 'SALIDA'){
                        $precio_unitario_anterior_especial = $precio_unitario_anterior;
                    }else if($descripcion == 'ENTRADA'){
                        $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                    }
                    $elementos = explode("-", $fecha_registro_kardex);
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
                    $fecha_formateada_ultimo_registro_anterior = implode("-", $array);
                    // $fecha_formateada -- Es la fecha de eliminacion del registro
                    if( $fecha_formateada !=  $fecha_formateada_ultimo_registro_anterior){
                        $fecha_formateada_act_stock = $fecha_formateada;
                    }else{
                        $fecha_formateada_act_stock = $fecha_formateada_ultimo_registro_anterior;
                    }
                    do{
                        // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                        // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                        $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                        $this->db->where('id_pro',$id_producto);
                        $this->db->where('fecha_cierre',$fecha_formateada_act_stock);
                        $query = $this->db->get('saldos_iniciales');
                        if($query->num_rows()>0){
                            foreach($query->result() as $row){
                                $id_saldos_iniciales = $row->id_saldos_iniciales;
                                $stock_inicial = $row->stock_inicial;
                                $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                                $precio_uni_inicial = $row->precio_uni_inicial;
                            }
                            if($almacen == 1){ // Sta. Clara
                                $stock_inicial_sta_clara = $stock_inicial_sta_clara - $unidades_ingreso;
                                $actualizar = array(
                                    'stock_inicial_sta_clara'=> $stock_inicial_sta_clara,
                                    // 'precio_uni_inicial'=> $precio_unitario_anterior_especial
                                );
                                $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                $this->db->update('saldos_iniciales', $actualizar);
                            }else if($almacen == 2){ // Sta. anita
                                $stock_inicial = $stock_inicial - $unidades_ingreso;
                                $actualizar = array(
                                    'stock_inicial'=> $stock_inicial,
                                    // 'precio_uni_inicial'=> $precio_unitario_anterior_especial
                                );
                                $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                $this->db->update('saldos_iniciales', $actualizar);
                            }
                            // Aumentar la fecha para la siguiente busqueda de cierre // Ya se tiene la fecha con el formato correcto
                            $elementos_act = explode("-", $fecha_formateada_act_stock);
                            $anio = $elementos_act[0];
                            $mes = $elementos_act[1];
                            $dia = $elementos_act[2];
                            if($mes == 12){
                                $anio = $anio + 1;
                                $mes_siguiente = 01;
                                $dia = 1;
                            }else if($mes <= 11 ){
                                $mes_siguiente = $mes + 1;
                                $dia = 1;
                            }
                            $array = array($anio, $mes_siguiente, $dia);
                            $fecha_formateada_act_stock = implode("-", $array);
                        }else{
                            $aux_bucle_saldos_ini = 1;
                        }
                    }while($aux_bucle_saldos_ini == 0);
                    // Considero si el movimiento en el kardex del producto de la factura eliminada corresponde al ultimo registro del mes
                    // para actualizar el monto de cierre del mes al que corresponde
                    if($unico_id_kardex_producto != ""){
                        // Obtengo las variables necesarias del ultimo movimiento
                        // El stock anterior viene a ser el stock actual del movimiento anterior
                        $new_stock_anterior_act_cierre = $stock_actual; // stock_anterior
                        $new_precio_unitario_anterior_cierre = $precio_unitario_anterior_especial; // precio_unitario_anterior
                        if($descripcion_elim == 'ENTRADA'){
                            $new_precio_unitario_cierre = (($new_stock_anterior_act_cierre*$new_precio_unitario_anterior_cierre)+($cantidad_ingreso_elim*$precio_unitario_actual_elim))/($new_stock_anterior_act_cierre+$cantidad_ingreso_elim);
                            $precio_antes_actualizacion_cierre = $precio_unitario_actual_promedio_elim;
                        }else if($descripcion_elim == 'SALIDA'){
                            $new_precio_unitario_cierre = $new_precio_unitario_anterior_cierre;
                            $precio_antes_actualizacion_cierre = $precio_unitario_actual_elim;
                        }

                        // Obtengo los stock de cierre actualizados en el paso anterior
                        $stock_general_cierre_ultimo = $stock_inicial + $stock_inicial_sta_clara;
                        $monto_parcial_producto_anterior_cierre = $precio_antes_actualizacion_cierre * ( $stock_general_cierre_ultimo + $unidades_ingreso );
                        $monto_parcial_producto_nuevo_cierre = $new_precio_unitario_anterior_cierre * $stock_general_cierre_ultimo;

                        $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                        $this->db->where('fecha_auxiliar',$fecha_formateada);
                        $query = $this->db->get('monto_cierre');
                        if($query->num_rows()>0){
                            foreach($query->result() as $row){
                                $fecha_cierre = $row->fecha_cierre;
                                $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                                $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                                $fecha_auxiliar = $row->fecha_auxiliar;
                            }
                            if($almacen == 1){ // Sta. Clara
                                $monto_cierre_sta_clara = $monto_cierre_sta_clara - $monto_parcial_producto_anterior_cierre;
                                $monto_cierre_sta_clara = $monto_cierre_sta_clara + $monto_parcial_producto_nuevo_cierre;
                                // Nuevo monto de cierre general
                                $monto_general_actualizado = $monto_cierre_sta_clara + $monto_cierre_sta_anita;
                                // echo ' 1. monto_general_actualizado '.$monto_cierre_sta_clara;
                                $actualizar = array(
                                    'monto_cierre'=> $monto_general_actualizado,
                                    'monto_cierre_sta_clara'=> $monto_cierre_sta_clara
                                );
                                $this->db->where('fecha_auxiliar',$fecha_formateada);
                                $this->db->update('monto_cierre',$actualizar);
                            }
                        }
                    }
                    // Hasta este punto se obitiene los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso
                    // Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización
                    $id_kardex_producto = "";
                    $auxiliar_contador = 0;
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro >=',$fecha_registro);
                    $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row_2){
                            // Procedimiento
                            $id_kardex_producto = $row_2->id_kardex_producto; // ID del movimiento en el kardex
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
                                // Actualización del registro
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                        'precio_unitario'=> $precio_unitario_actual_promedio_final,
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'SALIDA'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                    $actualizar_p_u_2 = array(
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'IMPORTACION'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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

                                if($stock_actual_final < 0){
                                    var_dump($id_detalle_producto).' ';
                                }
                                /* Dejar variables con el ultimo registro del stock y precio unitario obtenido */
                                /* Este paso se realizo en la linea 4277 pero solo sirvio para un recorrido */
                                $new_stock_anterior_act = $stock_actual_final;
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                                    $unidades_utilizadas = $cantidad_ingreso_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                                }else if($descripcion_act == 'SALIDA'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                    $unidades_utilizadas = $cantidad_salida_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_act;
                                }
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
                                // ESTA FECHA ME PERMITE SELECCIONAR PRECIO UNITARIO CON EL QUE SE GUARDO
                                // Formato
                                $this->db->select('id_kardex_producto');
                                $this->db->where('fecha_registro >=',$fecha_registro_kardex_post);
                                $this->db->where('fecha_registro <',date($fecha_formateada_post));
                                $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->order_by("fecha_registro", "asc");
                                $this->db->order_by("id_kardex_producto", "asc");
                                $query = $this->db->get('kardex_producto');
                                foreach($query->result() as $row_3){
                                    $id_kardex_producto_ultimo = $row_3->id_kardex_producto;
                                }
                            }
                            // LAS ACTUALIZACIONES DE LOS SALDOS INICIALES Y EL MONTO SE DEBE HACER POR RECORRIDO DE BUSQUEDA DE CADA REGISTRO DEL KARDEX
                            // Actualizar los datos de cierre de mes si la salida correspondia a un periodo ya cerrado
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
                            $fecha_formateada = implode("-", $array);
                            // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                            // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                            $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                            $this->db->where('id_pro',$id_producto);
                            $this->db->where('fecha_cierre',$fecha_formateada);
                            $query = $this->db->get('saldos_iniciales');
                            if($query->num_rows()>0){
                                foreach($query->result() as $row){
                                    $id_saldos_iniciales = $row->id_saldos_iniciales;
                                    $stock_inicial = $row->stock_inicial;
                                    $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                                    $precio_uni_inicial = $row->precio_uni_inicial; // todavia no esta actualizado
                                }
                                // Validacion
                                if($id_kardex_producto == $id_kardex_producto_ultimo){
                                    if($almacen == 1){ // Sta. Clara
                                        $actualizar = array(
                                            'precio_uni_inicial'=> $new_precio_unitario_anterior_act
                                        );
                                        $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                        $this->db->update('saldos_iniciales', $actualizar);
                                    }else if($almacen == 2){ // Sta. anita
                                        $actualizar = array(
                                            'precio_uni_inicial'=> $new_precio_unitario_anterior_act
                                        );
                                        $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                                        $this->db->update('saldos_iniciales', $actualizar);
                                    }
                                    // Actualizar monto final de cierre del mes
                                    // Obtengo los stock de cierre actualizados en el paso anterior
                                    $stock_general_cierre = $stock_inicial + $stock_inicial_sta_clara;
                                    $monto_parcial_producto_anterior = $precio_antes_actualizacion * ( $stock_general_cierre + $unidades_ingreso );
                                    $monto_parcial_producto_nuevo = $new_precio_unitario_anterior_act * $stock_general_cierre;
                                    // Seleccionar el monto de cierre
                                    $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                                    $this->db->where('fecha_auxiliar',$fecha_formateada);
                                    $query = $this->db->get('monto_cierre');
                                    if($query->num_rows()>0){
                                        foreach($query->result() as $row){
                                            $fecha_cierre = $row->fecha_cierre;
                                            $monto_cierre_sta_anita = $row->monto_cierre_sta_anita;
                                            $monto_cierre_sta_clara = $row->monto_cierre_sta_clara;
                                            $fecha_auxiliar = $row->fecha_auxiliar;
                                        }
                                        if($almacen == 1){ // Sta. Clara
                                            $monto_cierre_sta_clara = $monto_cierre_sta_clara - $monto_parcial_producto_anterior;
                                            $monto_cierre_sta_clara = $monto_cierre_sta_clara + $monto_parcial_producto_nuevo;
                                            // Nuevo monto de cierre general
                                            $monto_general_actualizado = $monto_cierre_sta_clara + $monto_cierre_sta_anita;
                                            // echo ' 1. monto_general_actualizado '.$monto_cierre_sta_clara;
                                            $actualizar = array(
                                                'monto_cierre'=> $monto_general_actualizado,
                                                'monto_cierre_sta_clara'=> $monto_cierre_sta_clara
                                            );
                                            $this->db->where('fecha_auxiliar',$fecha_formateada);
                                            $this->db->update('monto_cierre',$actualizar);
                                        }else if($almacen == 2){ // Sta. anita
                                            $monto_cierre_sta_anita = $monto_cierre_sta_anita - $monto_parcial_producto_anterior;
                                            $monto_cierre_sta_anita = $monto_cierre_sta_anita + $monto_parcial_producto_nuevo;
                                            // Nuevo monto de cierre general
                                            $monto_general_actualizado = $monto_cierre_sta_anita + $monto_cierre_sta_clara;
                                            $actualizar = array(
                                                'monto_cierre'=> $monto_general_actualizado,
                                                'monto_cierre_sta_anita'=> $monto_cierre_sta_anita
                                            );
                                            $this->db->where('fecha_auxiliar',$fecha_formateada);
                                            $this->db->update('monto_cierre',$actualizar);
                                        }
                                    }
                                }
                                // Limpiar variabls
                                $id_saldos_iniciales = " ";
                                $stock_inicial = " ";
                                $stock_inicial_sta_clara = " ";
                                $precio_uni_inicial = " ";
                            }
                        }
                    }
                    // Eliminar el registro del kardex del producto
                    $sql = "DELETE FROM kardex_producto WHERE id_detalle_producto = " . $id_detalle_producto . " AND DATE(fecha_registro) = '" .$fecha_registro."' AND num_comprobante = '" .$nro_comprobante."'";
                    $query = $this->db->query($sql);


                }
            }else{
                // Aca se debe realizar un registro normal sin modificar los montos de cierre ya que esta fuera del periodo de cierre
                $this->db->select('id_kardex_producto');
                $this->db->where('num_comprobante',$nro_comprobante);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->where('descripcion','ENTRADA');
                $query = $this->db->get('kardex_producto');
                foreach($query->result() as $row){
                    $id_kardex_producto_eliminado = $row->id_kardex_producto;
                }

                // reorganizar los datos del kardex considerando movimientos en el mismo dia y posterior
                // Obtener el ultimo id de registro para la fecha de ese producto
                // Procedimiento
                $this->db->select('id_kardex_producto');
                $this->db->where('fecha_registro <=',$fecha_registro);
                $this->db->where('id_kardex_producto <',$id_kardex_producto_eliminado);
                $this->db->where('id_detalle_producto',$id_detalle_producto);
                $this->db->order_by("fecha_registro", "asc");
                $this->db->order_by("id_kardex_producto", "asc");
                $query = $this->db->get('kardex_producto');
                if(count($query->result()) > 0){
                    foreach($query->result() as $row){
                        $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                    }
                    // Obtener los datos del ultimo registro de la fecha
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                    $this->db->where('id_kardex_producto',$auxiliar);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual = $row->stock_actual;
                        $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                        $precio_unitario_anterior = $row->precio_unitario_anterior;
                        $descripcion = $row->descripcion;
                        $precio_unitario_actual = $row->precio_unitario_actual;
                        $fecha_registro_kardex = $row->fecha_registro;
                    }
                    // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                    if($descripcion == 'SALIDA'){
                        $precio_unitario_anterior_especial = $precio_unitario_anterior;
                    }else if($descripcion == 'ENTRADA'){
                        $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                    }
                    // Hasta este punto se obitiene los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso
                    // Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización
                    $id_kardex_producto = "";
                    $auxiliar_contador = 0;
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro >=',$fecha_registro);
                    $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row_2){
                            // Procedimiento
                            $id_kardex_producto = $row_2->id_kardex_producto; // ID del movimiento en el kardex
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
                                // Actualización del registro
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                        'precio_unitario'=> $precio_unitario_actual_promedio_final,
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'SALIDA'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                    $actualizar_p_u_2 = array(
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'IMPORTACION'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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

                                if($stock_actual_final < 0){
                                    var_dump($id_detalle_producto).' ';
                                }

                                /* Dejar variables con el ultimo registro del stock y precio unitario obtenido */
                                /* Este paso se realizo en la linea 4277 pero solo sirvio para un recorrido */
                                $new_stock_anterior_act = $stock_actual_final;
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                                    $unidades_utilizadas = $cantidad_ingreso_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                                }else if($descripcion_act == 'SALIDA'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                    $unidades_utilizadas = $cantidad_salida_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_act;
                                }
                            }
                        }
                    }
                    $sql = "DELETE FROM kardex_producto WHERE id_detalle_producto = " . $id_detalle_producto . " AND DATE(fecha_registro) = '" .$fecha_registro."' AND num_comprobante = '" .$nro_comprobante."'";
                    $query = $this->db->query($sql);
                }else {
                    // Obtener los datos del ultimo registro de la fecha
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,precio_unitario_actual,fecha_registro');
                    $this->db->where('id_kardex_producto',$id_kardex_producto_eliminado);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual = 0;
                        $precio_unitario_actual_promedio = 0;
                        $precio_unitario_anterior = 0;
                        $descripcion = $row->descripcion;
                        $precio_unitario_actual = 0;
                        $fecha_registro_kardex = $row->fecha_registro;
                    }
                    // Considerar el ultimo precio que se manejo dependiente del tipo de movimiento
                    if($descripcion == 'SALIDA'){
                        $precio_unitario_anterior_especial = $precio_unitario_anterior;
                    }else if($descripcion == 'ENTRADA'){
                        $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                    }

                    $id_kardex_producto = "";
                    $auxiliar_contador = 0;
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro >=',$fecha_registro);
                    $this->db->where('id_kardex_producto >',$id_kardex_producto_eliminado);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row_2){
                            // Procedimiento
                            $id_kardex_producto = $row_2->id_kardex_producto; // ID del movimiento en el kardex
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
                                // Actualización del registro
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                        'precio_unitario'=> $precio_unitario_actual_promedio_final,
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'SALIDA'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                                    $actualizar_p_u_2 = array(
                                        'stock' => $stock_actual_final
                                    );
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                }else if($descripcion_act == 'IMPORTACION'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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

                                if($stock_actual_final < 0){
                                    var_dump($id_detalle_producto).' ';
                                }
                                /* Dejar variables con el ultimo registro del stock y precio unitario obtenido */
                                /* Este paso se realizo en la linea 4277 pero solo sirvio para un recorrido */
                                $new_stock_anterior_act = $stock_actual_final;
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                                    $unidades_utilizadas = $cantidad_ingreso_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_promedio_act;
                                }else if($descripcion_act == 'SALIDA'){
                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                    $unidades_utilizadas = $cantidad_salida_act;
                                    $precio_antes_actualizacion = $precio_unitario_actual_act;
                                }
                            }
                        }
                    }
                    $sql = "DELETE FROM kardex_producto WHERE id_detalle_producto = " . $id_detalle_producto . " AND DATE(fecha_registro) = '" .$fecha_registro."' AND num_comprobante = '" .$nro_comprobante."'";
                    $query = $this->db->query($sql);
                }
            }
        }

        $sql = "DELETE FROM detalle_ingreso_producto WHERE id_ingreso_producto = " . $id_registro_ingreso . "";
        $query = $this->db->query($sql);

        $sql = "DELETE FROM ingreso_producto WHERE id_ingreso_producto = " . $id_registro_ingreso . "";
        $query = $this->db->query($sql);

        return true;
    }

    public function eliminarRegistroSalida($id_registro_salida){
        $sql = " SELECT salida_producto.id_salida_producto,salida_producto.cantidad_salida,detalle_producto.id_detalle_producto,
        detalle_producto.no_producto,detalle_producto.stock
        FROM
        salida_producto
        INNER JOIN detalle_producto ON salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE salida_producto.id_salida_producto =".$id_registro_salida;
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {
            $cantidad_salida = $row->cantidad_salida;
            $stock_actualizado =  $row->stock + $cantidad_salida;
            $actualizar = array(
                'stock' => $stock_actualizado
            );
            $this->db->where('id_detalle_producto',$row->id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
        }

        $sql = "DELETE FROM salida_producto WHERE id_salida_producto = " . $id_registro_salida . "";
        $query = $this->db->query($sql);

        if($query->num_rows()>0){
            return $query->result();
        }
    }

    function get_all_productos_v2(){
        $sql = "SELECT DISTINCT detalle_producto.id_detalle_producto,detalle_producto.no_producto,producto.id_pro,producto.estado,detalle_producto.stock
        FROM detalle_producto
        INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE detalle_producto.id_detalle_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result();
        }
    }

    public function actualizaTipoCambio(){
        // Recuperamos el ID  -> 
        $id_tipo_cambio = $this->security->xss_clean($this->uri->segment(3));
        $edit_dolar_compra = $this->security->xss_clean($this->input->post('edit_dolar_compra'));
        $edit_dolar_venta = $this->security->xss_clean($this->input->post('edit_dolar_venta'));
        $edit_euro_compra = $this->security->xss_clean($this->input->post('edit_euro_compra'));
        $edit_euro_venta = $this->security->xss_clean($this->input->post('edit_euro_venta'));

        $actualizar = array(
            'dolar_compra' => $edit_dolar_compra,
            'dolar_venta' => $edit_dolar_venta,
            'euro_compra'=>$edit_euro_compra,
            'euro_venta'=>$edit_euro_venta,
        );
        $this->db->where('id_tipo_cambio',$id_tipo_cambio);
        $this->db->update('tipo_cambio', $actualizar);
        return true;
    }

    public function actualizaProveedor(){
        //Recuperamos el ID  -> 
        $id_proveedor = $this->security->xss_clean($this->uri->segment(3));
        $edit_rz = strtoupper($this->security->xss_clean($this->input->post('edit_rz')));
        $edit_ruc = $this->security->xss_clean($this->input->post('edit_ruc'));
        $edit_pais = strtoupper($this->security->xss_clean($this->input->post('edit_pais')));
        $edit_direc = strtoupper($this->security->xss_clean($this->input->post('edit_direc')));
        $edit_tel1 = $this->security->xss_clean($this->input->post('edit_tel1'));
        //Verifico si existe el RUC del proveedor
        /*
        $this->db->where('ruc',$edit_ruc);
        $query = $this->db->get('proveedor');
        if($query->num_rows()>0){
            return false;
        }else{
*/
            $actualizar = array(
                'razon_social' => $edit_rz,
                'ruc' => $edit_ruc,
                'pais' => $edit_pais,
                'direccion'=>$edit_direc,
                'telefono1'=>$edit_tel1
            );
            $this->db->where('id_proveedor',$id_proveedor);
            $this->db->update('proveedor', $actualizar);
            return true;
        /*}*/
    }
    
    public function getUserEditar(){
        //Recuperamos el ID  -> 
        $id_usuario = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos
        $sql = "SELECT usuario.id_usuario, usuario.no_usuario, 
        usuario.ape_paterno, tipo_usuario.id_tipo_usuario,
        CASE usuario.fl_estado WHEN 't' THEN 'true' WHEN 'f' THEN 'false' END as fl_estado,
        almacen.id_almacen, usuario.tx_usuario , usuario.correo_electronico
        FROM usuario 
        INNER JOIN tipo_usuario ON usuario.id_tipo_usuario = tipo_usuario.id_tipo_usuario
        INNER JOIN almacen ON usuario.id_almacen = almacen.id_almacen
        WHERE usuario.id_usuario=".$id_usuario;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

/*
                $id_maquina = $this->security->xss_clean($this->uri->segment(3));
        //Consulto en Base de Datos

        $sql = "SELECT maquina.id_maquina,nombre_maquina.id_nombre_maquina,maquina.marca_maq,
                maquina.modelo_maq,
                estado_maquina.id_estado_maquina,
                maquina.observacion_maq 
                FROM maquina
                INNER JOIN estado_maquina ON maquina.id_estado_maquina = estado_maquina.id_estado_maquina
                INNER JOIN nombre_maquina ON maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina
                WHERE maquina.id_maquina=".$id_maquina;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }

*/



    public function listarProductosIngresados(){
        //$id_ingreso_producto = $this->security->xss_clean($this->input->post('numcomprobante'));
        if($this->input->post('numcomprobante')){
            $filtro = $this->security->xss_clean($this->input->post('numcomprobante'));
            $sql = "SELECT detalle_producto.id_detalle_producto,detalle_ingreso_producto.unidades,detalle_producto.no_producto,producto.id_producto,
                    detalle_producto.precio_unitario,listarProductodetalle_ingreso_producto.precio,detalle_ingreso_producto.id_ingreso_producto,detalle_ingreso_producto.id_detalle_ing_prod
                    FROM detalle_ingreso_producto
                    INNER JOIN detalle_producto ON detalle_ingreso_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    INNER JOIN producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    WHERE detalle_ingreso_producto.id_ingreso_producto=".$filtro;
            $query = $this->db->query($sql);
            if($query->num_rows()>0)
            {
            return $query->result();
            }
        }
    }

    public function get_data_report_facturas_2015(){
        $array_montos = [];
        
        for ($i=1; $i <= 12; $i++) {
            $sumatoria_soles = 0;
            $anio = '2015';
            $mes = $i;
            $dia_inicial = '01';
            $dia_final = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));// conocer el ultimo dia del mes

            $array_inicial = array($anio, $mes, $dia_inicial);
            $fecha_inicial = implode("-", $array_inicial);

            $array_final = array($anio, $mes, $dia_final);
            $fecha_final = implode("-", $array_final);

            $filtro = "";
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$fecha_inicial."'AND'".$fecha_final."'";

            $sql ="SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.fecha,ingreso_producto.total,
                   ingreso_producto.id_agente,ingreso_producto.cs_igv,ingreso_producto.id_moneda FROM ingreso_producto
                   WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;

            $query = $this->db->query($sql);

            foreach ($query->result() as $key) {
                $valor_total = $key->total;
                $fecha_registro = $key->fecha;
                $id_moneda = $key->id_moneda;
                $id_agente = $key->id_agente;

                $this->db->select('dolar_venta,euro_venta');
                $this->db->where('fecha_actual',$fecha_registro);
                $query3 = $this->db->get('tipo_cambio');
                foreach($query3->result() as $row3){
                    $dolar_venta = $row3->dolar_venta;
                    $euro_venta = $row3->euro_venta;
                }

                $this->db->select('no_moneda');
                $this->db->where('id_moneda',$id_moneda);
                $query2 = $this->db->get('moneda');
                foreach($query2->result() as $row){
                    $no_moneda = $row->no_moneda;
                }

                if($no_moneda == 'DOLARES' && $id_agente == null){
                    $conversion_valor_total = $valor_total * $dolar_venta; 
                }else if($no_moneda == 'EURO' && $id_agente == null){
                    $conversion_valor_total = $valor_total * $euro_venta; 
                }else{
                    $conversion_valor_total = $valor_total;
                }

                $sumatoria_soles = $sumatoria_soles + $conversion_valor_total;
            }
            array_push($array_montos, @number_format($sumatoria_soles, 2, '.', ''));
        }
        return $array_montos;
    }

    public function get_data_inventario_almacen_categoria(){
        $array_montos = [];

        $sql ="SELECT categoria.id_categoria,categoria.no_categoria FROM categoria
               WHERE categoria.id_categoria IS NOT NULL";
        $query = $this->db->query($sql);
        foreach ($query->result() as $key) {
            $sumatoria_soles = 0;
            $id_categoria = $key->id_categoria;
            // obtener los productos registrados en cada categoria
            $filtro = "";
            $filtro .= " AND producto.id_categoria =".(int)$id_categoria;
            $sql_2 ="SELECT detalle_producto.no_producto,detalle_producto.stock,detalle_producto.precio_unitario,producto.id_categoria,producto.id_pro FROM producto
                   INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                   WHERE producto.id_pro IS NOT NULL".$filtro;
            $query_2 = $this->db->query($sql_2);
            foreach ($query_2->result() as $row) {
                $stock = $row->stock;
                $precio_unitario = $row->precio_unitario;
                $sumatoria_soles = $sumatoria_soles + ($stock*$precio_unitario);
            }
            array_push($array_montos, @number_format($sumatoria_soles, 2, '.', ''));
        }
        
        return $array_montos;
    }

    public function get_data_report_facturas_2016(){
        $array_montos = [];
        
        for ($i=1; $i <= 12; $i++) {
            $sumatoria_soles = 0;
            $anio = '2016';
            $mes = $i;
            $dia_inicial = '01';
            $dia_final = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));// conocer el ultimo dia del mes

            $array_inicial = array($anio, $mes, $dia_inicial);
            $fecha_inicial = implode("-", $array_inicial);

            $array_final = array($anio, $mes, $dia_final);
            $fecha_final = implode("-", $array_final);

            $filtro = "";
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$fecha_inicial."'AND'".$fecha_final."'";

            $sql ="SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.fecha,ingreso_producto.total,
                   ingreso_producto.id_agente,ingreso_producto.cs_igv,ingreso_producto.id_moneda FROM ingreso_producto
                   WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;

            $query = $this->db->query($sql);

            foreach ($query->result() as $key) {
                $valor_total = $key->total;
                $fecha_registro = $key->fecha;
                $id_moneda = $key->id_moneda;
                $id_agente = $key->id_agente;

                $this->db->select('dolar_venta,euro_venta');
                $this->db->where('fecha_actual',$fecha_registro);
                $query3 = $this->db->get('tipo_cambio');
                foreach($query3->result() as $row3){
                    $dolar_venta = $row3->dolar_venta;
                    $euro_venta = $row3->euro_venta;
                }

                $this->db->select('no_moneda');
                $this->db->where('id_moneda',$id_moneda);
                $query2 = $this->db->get('moneda');
                foreach($query2->result() as $row){
                    $no_moneda = $row->no_moneda;
                }

                if($no_moneda == 'DOLARES' && $id_agente == null){
                    $conversion_valor_total = $valor_total * $dolar_venta; 
                }else if($no_moneda == 'EURO' && $id_agente == null){
                    $conversion_valor_total = $valor_total * $euro_venta; 
                }else{
                    $conversion_valor_total = $valor_total;
                }

                $sumatoria_soles = $sumatoria_soles + $conversion_valor_total;
            }
            array_push($array_montos, @number_format($sumatoria_soles, 2, '.', ''));
        }
        return $array_montos;
    }

    public function get_data_report_consumos_2016(){
        $array_montos = [];
        
        for ($i=1; $i <= 12; $i++) {
            $sumatoria_soles = 0;
            $anio = '2016';
            $mes = $i;
            $dia_inicial = '01';
            $dia_final = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));// conocer el ultimo dia del mes

            $array_inicial = array($anio, $mes, $dia_inicial);
            $fecha_inicial = implode("-", $array_inicial);

            $array_final = array($anio, $mes, $dia_final);
            $fecha_final = implode("-", $array_final);

            $filtro = "";
            $filtro .= " AND DATE(salida_producto.fecha) BETWEEN'".$fecha_inicial."'AND'".$fecha_final."'";

            $sql ="SELECT salida_producto.fecha,salida_producto.id_area,detalle_producto.no_producto,salida_producto.id_salida_producto,
                    detalle_salida_producto.cantidad_salida,detalle_salida_producto.p_u_salida FROM salida_producto
                    INNER JOIN detalle_salida_producto ON detalle_salida_producto.id_salida_producto = salida_producto.id_salida_producto
                    INNER JOIN detalle_producto ON detalle_salida_producto.id_detalle_producto = detalle_producto.id_detalle_producto
                    WHERE salida_producto.id_salida_producto IS NOT NULL".$filtro;

            $query = $this->db->query($sql);

            foreach ($query->result() as $key) {
                $no_producto = $key->no_producto;
                $cantidad_salida = $key->cantidad_salida;
                $p_u_salida = $key->p_u_salida;

                $sumatoria_soles = $sumatoria_soles + ($cantidad_salida*$p_u_salida);
            }
            array_push($array_montos, @number_format($sumatoria_soles, 2, '.', ''));
        }
        return $array_montos;
    }

    public function kardex_orden_ingreso($id_ingreso_producto, $id_detalle_producto, $cantidad, $almacen){
        // Actualización del Stock
        $this->db->select('stock,precio_unitario');
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $query = $this->db->get('detalle_producto');
        foreach($query->result() as $row){
            $stock_sta_anita = $row->stock;
            $precio_unitario = $row->precio_unitario;
        }
        $stock_actual = $stock_sta_anita;
        $new_stock = $stock_actual + $cantidad;

        $stock_actualizado = $stock_sta_anita + $cantidad;
        $actualizar = array(
            'stock'=> $stock_actualizado,
        );
        $this->db->where('id_detalle_producto',$id_detalle_producto);
        $this->db->update('detalle_producto', $actualizar);
        // Kardex del producto
        $a_data_kardex = array('fecha_registro' => date('Y-m-d'),
                        'descripcion' => "ORDEN INGRESO",
                        'id_detalle_producto' => $id_detalle_producto,
                        'stock_anterior' => $stock_actual,
                        'precio_unitario_anterior' => $precio_unitario,
                        'cantidad_ingreso' => $cantidad,
                        'stock_actual' => $new_stock,
                        'precio_unitario_actual_promedio' => $precio_unitario,
                        'precio_unitario_actual' => $precio_unitario,
                        'num_comprobante' => $id_ingreso_producto,
                        'serie_comprobante' => "001",
                        );
        $this->db->insert('kardex_producto', $a_data_kardex);
        return 'registro_correcto';
    }

    

    public function agregar_detalle_ingreso($carrito, $id_ingreso_producto, $fecharegistro, $numcomprobante, $seriecomprobante, $porcentaje, $almacen)
    {
        $auxiliar = 0;
        $auxiliar_2 = 0;
        $auxiliar_3 = 0;
        $auxiliar_contador = 0;
        $id_moneda = $this->security->xss_clean($this->input->post("moneda"));
        // obtengo el Nombre de la moneda por medio del id_moneda
        $this->db->select('no_moneda');
        $this->db->where('id_moneda',$id_moneda);
        $query = $this->db->get('moneda');
        foreach($query->result() as $row){
            $no_moneda = $row->no_moneda;
        }
        // obtengo los tipos de cambio del día //obtengo los tipos de cambio de la fecha con la que realizo el registro
        $fecharegistro = $this->security->xss_clean($this->input->post("fecharegistro"));
        $this->db->select('dolar_venta,euro_venta');
        $this->db->where('fecha_actual',$fecharegistro);
        $query = $this->db->get('tipo_cambio');
        if($query->num_rows()>0){
            foreach($query->result() as $row){
                $dolar_venta = $row->dolar_venta;
                $euro_venta = $row->euro_venta;
            }
            foreach ($carrito as $item) {
                $new_stock_anterior_act = 0; // elimina el error en el kardex
                // Obtener el monto total de la factura
                $monto_total_factura = $this->cart->total();
                // Obtengo el nombre del producto
                $no_producto = $item['name'];

                // Obtengo los datos del producto antes de actualizarlos. Stock y Precio Unitario anterior
                $this->db->select('id_detalle_producto,stock,precio_unitario');
                $this->db->where('no_producto',$no_producto);
                $query = $this->db->get('detalle_producto');
                foreach($query->result() as $row){
                    $id_detalle_producto = $row->id_detalle_producto;
                    $stock_actual = $row->stock;
                    $precio_unitario = $row->precio_unitario;
                }
                /* Registro de detalle producto */
                $datos = array(
                    "unidades_referencial" => $item['qty'],
                    "unidades" => $item['qty'],
                    "id_detalle_producto" => $id_detalle_producto,
                    "precio" => $item['price'], // GUARDO EL PRECIO UNITARIO DEL PRODUCTO COMO SE INGRESO EN LA FACTURA
                    "id_ingreso_producto" => $id_ingreso_producto
                );
                $this->db->insert('detalle_ingreso_producto', $datos);
                /* Fin del registro del detalle del producto */

                /* obtengo el precio unitario en soles con el tipo de cambio del dia de registro */
                /* Este P.U es con el que esta entrando con la factura $item['price'] */
                /* El precio unitario para el registro en la tabla si debe evaluar si es con igv o son igv */
                if ($this->session->userdata('csigv') == "true"){ // Precio unitario con IGV
                    $precio_unitario_evaluado = $item['price']/(1.18); // Con IGV
                }else if ($this->session->userdata('csigv') == "false"){ // Precio unitario sin IGV
                    $precio_unitario_evaluado = $item['price']; // Sin IGV
                }

                /* obtengo el precio unitario del producto en soles */
                if($no_moneda == 'DOLARES'){
                    $precio_unitario_soles = $precio_unitario_evaluado * $dolar_venta;
                }else if($no_moneda == 'EURO'){
                    $precio_unitario_soles = $precio_unitario_evaluado * $euro_venta;
                }else{
                    $precio_unitario_soles = $precio_unitario_evaluado;
                }

                // Seleccionar el stock de acuerdo al almacen
                if($almacen == 1){ /* Sta. Clara */
                    // Actualización del Stock y el Precio Unitario
                    $this->db->select('stock,stock_sta_clara,precio_unitario');
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $query = $this->db->get('detalle_producto');
                    foreach($query->result() as $row){
                        $stock_sta_clara = $row->stock_sta_clara;
                        $stock_sta_anita = $row->stock;
                        $precio_unitario = $row->precio_unitario;
                    }
                    // Actualizo los datos en funcion del stock general
                    // Obtengo el stock general y el precio unitario general
                    $stock_general = $stock_sta_clara + $stock_sta_anita; /* Stock general actual */
                    $stock_actualizado = $stock_general + $item['qty']; /* stock general + la cantidad de ingreso en la factura */
                    $nuevo_precio_unitario = (($stock_general*$precio_unitario)+($item['qty']*$precio_unitario_soles))/($stock_general+$item['qty']);
                    // Actualizo los datos del stock por almacen, en este caso sta. clara
                    // Criterio general de stock
                    $stock_sta_clara = $stock_sta_clara + $item['qty'];
                    $actualizar = array(
                        'stock_sta_clara'=> $stock_sta_clara,
                        'precio_unitario'=> $nuevo_precio_unitario
                    );
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->update('detalle_producto', $actualizar);
                    /* End de la actualización */

                    // Criterio por area
                    // Seleccion el id de la tabla producto
                    $this->db->select('id_pro');
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $query = $this->db->get('producto');
                    foreach($query->result() as $row){
                        $id_pro = $row->id_pro;
                    }
                    // Seleccionar el stock de acuerdo al producto y al área
                    // Actualización del Stock
                    $this->db->select('stock_area_sta_clara,id_detalle_producto_area');
                    $this->db->where('id_area',$id_area);
                    $this->db->where('id_pro',$id_pro);
                    $query = $this->db->get('detalle_producto_area');
                    foreach($query->result() as $row){
                        $stock_area_sta_clara = $row->stock_area_sta_clara;
                        $id_detalle_producto_area = $row->id_detalle_producto_area;
                    }
                    $stock_area_sta_clara = $stock_area_sta_clara + $item['qty'];
                    $actualizar_stock_area = array(
                        'stock_area_sta_clara'=> $stock_area_sta_clara
                    );
                    $this->db->where('id_detalle_producto_area',$id_detalle_producto_area);
                    $this->db->update('detalle_producto_area', $actualizar_stock_area);
                    /* Gestión de kardex */
                    /* Obtener el ultimo id de registro para la fecha */
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro <=',$fecharegistro);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row){
                            /*if($row->id_kardex_producto > $auxiliar){*/
                                $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                            /*}*/
                        }
                        /* Obtener los datos del ultimo registro de la fecha */
                        $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                        $this->db->where('id_kardex_producto',$auxiliar);
                        $query = $this->db->get('kardex_producto');
                        foreach($query->result() as $row){
                            $stock_actual = $row->stock_actual;
                            $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                            $precio_unitario_anterior = $row->precio_unitario_anterior;
                            $descripcion = $row->descripcion;
                        }
                        /* hacer el calculo del precio unitario y stock en funcion del movimiento */
                        /* Nuevo precio unitario promedio */
                        if($descripcion == 'SALIDA'){
                            $new_precio_unitario_especial = (($stock_actual*$precio_unitario_anterior)+($item['qty']*$precio_unitario_soles))/($stock_actual+$item['qty']);
                            $precio_unitario_anterior_especial = $precio_unitario_anterior;
                        }else if($descripcion == 'ENTRADA' || $descripcion == 'ORDEN INGRESO'){
                            $new_precio_unitario_especial = (($stock_actual*$precio_unitario_actual_promedio)+($item['qty']*$precio_unitario_soles))/($stock_actual+$item['qty']);
                            $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                        }
                        $new_stock = $stock_actual + $item['qty'];
                        /* Realizar el registro en el kardex */
                        $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                        'descripcion' => "ENTRADA",
                                        'id_detalle_producto' => $id_detalle_producto,
                                        'stock_anterior' => $stock_actual,
                                        'precio_unitario_anterior' => $precio_unitario_anterior_especial,
                                        'cantidad_ingreso' => $item['qty'],
                                        'stock_actual' => $new_stock,
                                        'precio_unitario_actual_promedio' => $new_precio_unitario_especial,
                                        'precio_unitario_actual' => $precio_unitario_soles,
                                        'num_comprobante' => $numcomprobante,
                                        'serie_comprobante' => $seriecomprobante,
                                        );
                        $result_id_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                        /* End registro para el kardex */
                        /* Actualizar el precio unitario del producto */
                        $actualizar_p_u_1 = array(
                            'precio_unitario'=> $new_precio_unitario_especial
                        );
                        $this->db->where('id_detalle_producto',$id_detalle_producto);
                        $this->db->update('detalle_producto', $actualizar_p_u_1);
                    }else{
                        /* Realizar registro para el kardex */ /* en el kardex el precio unitario del producto debe ir en soles */
                        $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                        'descripcion' => "ENTRADA",
                                        'id_detalle_producto' => $id_detalle_producto,
                                        'stock_anterior' => $stock_general,
                                        'precio_unitario_anterior' => $precio_unitario,
                                        'cantidad_ingreso' => $item['qty'],
                                        'stock_actual' => $stock_actualizado,
                                        'precio_unitario_actual_promedio' => $nuevo_precio_unitario,
                                        'precio_unitario_actual' => $precio_unitario_soles,
                                        'num_comprobante' => $numcomprobante,
                                        'serie_comprobante' => $seriecomprobante,
                                        );
                        $result_id_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                        /* End registro para el kardex */
                    }
                    /* Hasta este punto actualiza los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso */

                    /***************************************************************************************************************/
                    /* Obtener los datos del ultimo movimiento - sea este el ultimo movimiento realizado */
                    /* puede ser el registro del paso anterior */
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro',$fecharegistro);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        if($row->id_kardex_producto > $auxiliar_2){
                            $auxiliar_2 = $row->id_kardex_producto;
                        }
                    }

                    /* Obtener detalles del ultimo registro de la fecha en el kardex */
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                    $this->db->where('id_kardex_producto',$auxiliar_2);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual_actualizacion_registros = $row->stock_actual;
                        $precio_unitario_actual_promedio_actualizacion_registros = $row->precio_unitario_actual_promedio;
                        $precio_unitario_anterior_actualizacion_registros = $row->precio_unitario_anterior;
                        $descripcion_actualizacion_registros = $row->descripcion;
                        if($descripcion_actualizacion_registros == 'SALIDA'){
                            $precio_unitario_actual_actualizacion_registros = $precio_unitario_anterior_actualizacion_registros;
                        }else if($descripcion_actualizacion_registros == 'ENTRADA' || $descripcion_actualizacion_registros == 'ORDEN INGRESO'){
                            $precio_unitario_actual_actualizacion_registros = $precio_unitario_actual_promedio_actualizacion_registros;
                        }else if($descripcion_actualizacion_registros == 'IMPORTACION'){
                            $precio_unitario_actual_actualizacion_registros = 0;
                        }
                    }
                    /***************************************************************************************************************/

                    /* Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización */
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro >',$fecharegistro);
                    // $this->db->where('id_kardex_producto >',$result_id_kardex);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row){
                            $id_kardex_producto = $row->id_kardex_producto; /* ID del movimiento en el kardex */
                            //var_dump($id_detalle_producto.' -> '.$id_kardex_producto);
                            /* Obtener los datos del movimiento del kardex */
                            $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual');
                            $this->db->where('id_kardex_producto',$id_kardex_producto);
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row){
                                $stock_actual_act = $row->stock_actual;
                                $precio_unitario_actual_promedio_act = $row->precio_unitario_actual_promedio;
                                $precio_unitario_anterior_act = $row->precio_unitario_anterior;
                                $descripcion_act = $row->descripcion;
                                $stock_anterior_act = $row->stock_anterior;
                                $cantidad_salida_act = $row->cantidad_salida;
                                $cantidad_ingreso_act = $row->cantidad_ingreso;
                                $precio_unitario_actual_act = $row->precio_unitario_actual;
                                /* Actualización del registro */
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
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
                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
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
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                }else if($almacen == 2){ /* Sta. Anita */
                    /* Actualización del Stock y el Precio Unitario */
                    $this->db->select('stock,precio_unitario');
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $query = $this->db->get('detalle_producto');
                    foreach($query->result() as $row){
                        $stock_sta_anita = $row->stock;
                        $precio_unitario = $row->precio_unitario;
                    }
                    // Actualizo los datos en funcion del stock general
                    // Obtengo el stock general y el precio unitario general
                    $stock_general = $stock_sta_anita; /* Stock general actual */
                    $stock_actualizado = $stock_general + $item['qty']; /* stock general + la cantidad de ingreso en la factura */
                    $nuevo_precio_unitario = (($stock_general*$precio_unitario)+($item['qty']*$precio_unitario_soles))/($stock_general+$item['qty']);
                    // Actualizo los datos del stock por almacen, en este caso sta. clara
                    // Criterio general de stock
                    $stock_sta_anita = $stock_sta_anita + $item['qty'];
                    $actualizar = array(
                        'stock'=> $stock_sta_anita,
                        'precio_unitario'=> $nuevo_precio_unitario
                    );
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->update('detalle_producto', $actualizar);
                    /* End de la actualización */
                    /* Gestión de kardex */
                    /* Obtener el ultimo id de registro para la fecha */
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro <=',$fecharegistro);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row){
                            /*if($row->id_kardex_producto > $auxiliar){*/
                                $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                            /*}*/
                        }
                        /* Obtener los datos del ultimo registro de la fecha */
                        $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                        $this->db->where('id_kardex_producto',$auxiliar);
                        $query = $this->db->get('kardex_producto');
                        foreach($query->result() as $row){
                            $stock_actual = $row->stock_actual;
                            $precio_unitario_actual_promedio = $row->precio_unitario_actual_promedio;
                            $precio_unitario_anterior = $row->precio_unitario_anterior;
                            $descripcion = $row->descripcion;
                        }
                        /* hacer el calculo del precio unitario y stock en funcion del movimiento */
                        /* Nuevo precio unitario promedio */
                        if($descripcion == 'SALIDA'){
                            $new_precio_unitario_especial = (($stock_actual*$precio_unitario_anterior)+($item['qty']*$precio_unitario_soles))/($stock_actual+$item['qty']);
                            $precio_unitario_anterior_especial = $precio_unitario_anterior;
                        }else if($descripcion == 'ENTRADA' || $descripcion== 'ORDEN INGRESO'){
                            $new_precio_unitario_especial = (($stock_actual*$precio_unitario_actual_promedio)+($item['qty']*$precio_unitario_soles))/($stock_actual+$item['qty']);
                            $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                        }
                        $new_stock = $stock_actual + $item['qty'];
                        /* Realizar el registro en el kardex */
                        $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                        'descripcion' => "ENTRADA",
                                        'id_detalle_producto' => $id_detalle_producto,
                                        'stock_anterior' => $stock_actual,
                                        'precio_unitario_anterior' => $precio_unitario_anterior_especial,
                                        'cantidad_ingreso' => $item['qty'],
                                        'stock_actual' => $new_stock,
                                        'precio_unitario_actual_promedio' => $new_precio_unitario_especial,
                                        'precio_unitario_actual' => $precio_unitario_soles,
                                        'num_comprobante' => $numcomprobante,
                                        'serie_comprobante' => $seriecomprobante,
                                        );
                        $result_id_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                        /* End registro para el kardex */
                        /* Actualizar el precio unitario del producto */
                        $actualizar_p_u_1 = array(
                            'precio_unitario'=> $new_precio_unitario_especial
                        );
                        $this->db->where('id_detalle_producto',$id_detalle_producto);
                        $this->db->update('detalle_producto', $actualizar_p_u_1);
                    }else{
                        /* Realizar registro para el kardex */ /* en el kardex el precio unitario del producto debe ir en soles */
                        $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                        'descripcion' => "ENTRADA",
                                        'id_detalle_producto' => $id_detalle_producto,
                                        'stock_anterior' => 0,
                                        'precio_unitario_anterior' => 0,
                                        'cantidad_ingreso' => $item['qty'],
                                        'stock_actual' => $item['qty'],
                                        'precio_unitario_actual_promedio' => $precio_unitario_soles,
                                        'precio_unitario_actual' => $precio_unitario_soles,
                                        'num_comprobante' => $numcomprobante,
                                        'serie_comprobante' => $seriecomprobante,
                                        );
                        $result_id_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                        /* End registro para el kardex */
                    }
                    /* Hasta este punto actualiza los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso */

                    /***************************************************************************************************************/
                    /* Obtener los datos del ultimo movimiento - sea este el ultimo movimiento realizado */
                    /* puede ser el registro del paso anterior */
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro',$fecharegistro);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        if($row->id_kardex_producto > $auxiliar_2){
                            $auxiliar_2 = $row->id_kardex_producto;
                        }
                    }

                    /* Obtener detalles del ultimo registro de la fecha en el kardex */
                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                    $this->db->where('id_kardex_producto',$auxiliar_2);
                    $query = $this->db->get('kardex_producto');
                    foreach($query->result() as $row){
                        $stock_actual_actualizacion_registros = $row->stock_actual;
                        $precio_unitario_actual_promedio_actualizacion_registros = $row->precio_unitario_actual_promedio;
                        $precio_unitario_anterior_actualizacion_registros = $row->precio_unitario_anterior;
                        $descripcion_actualizacion_registros = $row->descripcion;
                        if($descripcion_actualizacion_registros == 'SALIDA'){
                            $precio_unitario_actual_actualizacion_registros = $precio_unitario_anterior_actualizacion_registros;
                        }else if($descripcion_actualizacion_registros == 'ENTRADA' || $descripcion_actualizacion_registros == 'ORDEN INGRESO'){
                            $precio_unitario_actual_actualizacion_registros = $precio_unitario_actual_promedio_actualizacion_registros;
                        }else if($descripcion_actualizacion_registros == 'IMPORTACION'){
                            $precio_unitario_actual_actualizacion_registros = 0;
                        }
                    }
                    /***************************************************************************************************************/

                    /* Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización */
                    $this->db->select('id_kardex_producto');
                    $this->db->where('fecha_registro >',$fecharegistro);
                    // $this->db->where('id_kardex_producto >',$result_id_kardex);
                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                    $this->db->order_by("fecha_registro", "asc");
                    $this->db->order_by("id_kardex_producto", "asc");
                    $query = $this->db->get('kardex_producto');
                    if(count($query->result()) > 0){
                        foreach($query->result() as $row){
                            $id_kardex_producto = $row->id_kardex_producto; /* ID del movimiento en el kardex */
                            /* Obtener los datos del movimiento del kardex */
                            $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual,id_detalle_producto');
                            $this->db->where('id_kardex_producto',$id_kardex_producto);
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row){
                                $id_detalle_producto = $row->id_detalle_producto;
                                $stock_actual_act = $row->stock_actual;
                                $precio_unitario_actual_promedio_act = $row->precio_unitario_actual_promedio;
                                $precio_unitario_anterior_act = $row->precio_unitario_anterior;
                                $descripcion_act = $row->descripcion;
                                $stock_anterior_act = $row->stock_anterior;
                                $cantidad_salida_act = $row->cantidad_salida;
                                $cantidad_ingreso_act = $row->cantidad_ingreso;
                                $precio_unitario_actual_act = $row->precio_unitario_actual;
                                /* Actualización del registro */
                                if($descripcion_act == 'ENTRADA' || $descripcion_act == 'ORDEN INGRESO'){
                                    if($auxiliar_contador == 0){
                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
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
                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
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
                                        $new_stock_anterior_act = $stock_actual; // stock_anterior
                                        $new_precio_unitario_anterior_act = $precio_unitario_anterior_especial; // precio_unitario_anterior
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
                }
            }
            return TRUE;
        }else{
            return FALSE;
        }
    }


    public function getMarca(){
        //Obtenemos la ID del Departamento
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $idmaq = $this->security->xss_clean($this->input->post('maquina'));
        //Realizamos la consulta a la Base de Datos
        $sql = "SELECT DISTINCT marca_maquina.id_marca_maquina, marca_maquina.no_marca FROM nombre_maquina
        INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        WHERE marca_maquina.id_almacen = ".$almacen." AND nombre_maquina.id_nombre_maquina = ".$idmaq;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function getModelo(){
        //Obtenemos la ID del Departamento
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $idmarca = $this->security->xss_clean($this->input->post('marca'));
        //Realizamos la consulta a la Base de Datos
        $sql = "SELECT DISTINCT modelo_maquina.id_modelo_maquina, modelo_maquina.no_modelo FROM modelo_maquina
        INNER JOIN marca_maquina ON modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina
        WHERE modelo_maquina.id_almacen = ".$almacen." AND marca_maquina.id_marca_maquina = ".$idmarca;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function getSerie(){
        //Obtenemos la ID del Departamento
        $almacen = $this->security->xss_clean($this->session->userdata('almacen'));
        $idmodelo = $this->security->xss_clean($this->input->post('modelo'));
        //Realizamos la consulta a la Base de Datos
        $sql = "SELECT DISTINCT serie_maquina.id_serie_maquina, serie_maquina.no_serie FROM serie_maquina
        INNER JOIN modelo_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina
        WHERE serie_maquina.id_almacen = ".$almacen." AND modelo_maquina.id_modelo_maquina = ".$idmodelo;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function get_all_salidas_producto(){
        $sql = "SELECT salida_producto.id_salida_producto,salida_producto.id_area,salida_producto.solicitante,salida_producto.fecha,
        salida_producto.id_detalle_producto,salida_producto.cantidad_salida,salida_producto.id_almacen,salida_producto.p_u_salida,
        salida_producto.id_maquina,salida_producto.id_parte_maquina,salida_producto.observacion 
        FROM salida_producto
        WHERE salida_producto.id_salida_producto IS NOT NULL ";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarAreaE(){
        //$almacen = $this->session->userdata('almacen');
        /*
        $filtro = "";
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND area.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        */
        $sql = "SELECT area.id_area, area.no_area, area.encargado, area.encargado_sta_clara
                FROM area 
                INNER JOIN almacen ON area.id_almacen = almacen.id_almacen
                WHERE area.id_area IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function existeTipoCambio(){
        //Verifico sí éxiste el tipo de cambio del día
        $this->db->where('fecha_actual', date('Y-m-d'));           
        $query = $this->db->get('tipo_cambio');
        // Revisamos los resultados
        if ($query->num_rows() > 0)
        {
            #Ya existe el tipo de cambio
            return true;
        }
        #No existe tipo de cambio
        return false;
    }

    public function saveTipoCambio(){
        // Recuperamos los Inputs
        $compra_dol = $this->security->xss_clean($this->input->post('compra_dol'));
        $venta_dol = $this->security->xss_clean($this->input->post('venta_dol'));
        $compra_eur = $this->security->xss_clean($this->input->post('compra_eur'));
        $venta_eur = $this->security->xss_clean($this->input->post('venta_eur'));
        //Verifico sí éxiste el tipo de cambio del día
        $this->db->where('fecha_actual', date('Y-m-d'));           
        $query = $this->db->get('tipo_cambio');
        // Revisamos los resultados
        if ($query->num_rows() > 0)
        {
            $actualizo = array(
                            'dolar_compra'=>$compra_dol, 
                            'dolar_venta'=>$venta_dol,
                            'euro_compra'=>$compra_eur, 
                            'euro_venta'=>$venta_eur
                            );
            $this->db->where('fecha_actual', date('Y-m-d'));
            $this->db->update('tipo_cambio', $actualizo);
            return true;
        }
        else
        {
            $registro = array(
                            'dolar_compra'=>$compra_dol, 
                            'dolar_venta'=>$venta_dol,
                            'euro_compra'=>$compra_eur, 
                            'euro_venta'=>$venta_eur
                            );
            $this->db->insert('tipo_cambio', $registro);
            return true;
        }
        return false;
    }

    public function insert_ubicacion_producto($datos)
    {   
        $last_id = $this->db->insert('ubicacion', $datos);
        if($last_id != ""){
            return $this->db->insert_id();
        }else{
            return "error_inesperado";
        }
    }

    function eliminar_salidas_2014(){
        $fecha_inicio = "2014-01-01";
        $fecha_final = "2014-12-31";
        $filtro = "";
        $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$fecha_inicio."'AND'".$fecha_final."'";

        /* Filtro para el kardex */
        $filtro_kardex = "";
        $filtro_kardex .= " DATE(kardex_producto.fecha_registro) BETWEEN'".$fecha_inicio."'AND'".$fecha_final."'";

        /* Filtro para las salidas de producto */
        $filtro_salida = "";
        $filtro_salida .= " DATE(salida_producto.fecha) BETWEEN'".$fecha_inicio."'AND'".$fecha_final."'"; 

        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante,ingreso_producto.nro_comprobante,ingreso_producto.fecha,
                ingreso_producto.id_moneda,ingreso_producto.id_proveedor,ingreso_producto.total,ingreso_producto.gastos,ingreso_producto.id_almacen,
                ingreso_producto.id_agente,ingreso_producto.cs_igv,ingreso_producto.serie_comprobante
                FROM ingreso_producto 
                WHERE ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {  
            
            $id_ingreso_producto = $row->id_ingreso_producto;

            $sql = "DELETE FROM detalle_ingreso_producto WHERE id_ingreso_producto = " . $id_ingreso_producto . "";
            $query = $this->db->query($sql);

            $sql = "DELETE FROM ingreso_producto WHERE id_ingreso_producto = " . $id_ingreso_producto . "";
            $query = $this->db->query($sql);
        }

        $sql = "DELETE FROM kardex_producto WHERE".$filtro_kardex;
        $query = $this->db->query($sql);

        $sql = "DELETE FROM salida_producto WHERE".$filtro_salida;
        $query = $this->db->query($sql);

        return true;
    }

    function actualizar_saldos_iniciales(){
        /* Poner a 0 el stock y precio de todos los productos */
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto,detalle_producto.stock,detalle_producto.precio_unitario FROM detalle_producto";
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {
            $id_detalle_producto = $row->id_detalle_producto;

            $actualizar = array(
            'stock'=> 0,
            'precio_unitario'=> 0
            );
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
        }

        /* Actualizar el stock y precio por los datos de cierre diciembre del 2013 */
        $fecha_busqueda = "2014-01-01";
        $filtro = "";
        $filtro .= " AND DATE(saldos_iniciales.fecha_cierre) ='".$fecha_busqueda."'";
        $sql = "SELECT detalle_producto.no_producto,saldos_iniciales.stock_inicial,saldos_iniciales.precio_uni_inicial,saldos_iniciales.fecha_cierre,
                saldos_iniciales.id_saldos_iniciales,detalle_producto.id_detalle_producto
                FROM saldos_iniciales
                INNER JOIN producto ON saldos_iniciales.id_pro = producto.id_pro
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                WHERE saldos_iniciales.id_saldos_iniciales IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        foreach($query->result() as $row){
            $id_detalle_producto = $row->id_detalle_producto;
            $stock_inicial = $row->stock_inicial;
            $precio_uni_inicial = $row->precio_uni_inicial;

            $actualizar = array(
            'stock'=> $stock_inicial,
            'precio_unitario'=> $precio_uni_inicial
            );
            $this->db->where('id_detalle_producto',$id_detalle_producto);
            $this->db->update('detalle_producto', $actualizar);
        }
        return true;
    }

    function actualizar_saldos_iniciales_cuadre($fecha_registro,$id_pro,$stock_actualizado,$id_almacen)
    {
        $aux_parametro_cuadre = 0;
        /* Formateando la Fecha */
        $elementos = explode("-", $fecha_registro);
        $anio = $elementos[0];
        $mes = $elementos[1];
        $dia = $elementos[2];
        do{
            /* Validar si el mes es diciembre 12 : sino sale fuera de rango */
            if($mes == 12){
                $anio = $anio + 1;
                $mes = 1;
                $dia = 1;
            }else if($mes <= 11 ){
                $mes = $mes + 1;
                $dia = 1;
            }
            /* Ubicar la fecha en un cierre posterior para la validacion */
            $array = array($anio, $mes, $dia);
            $fecha_formateada = implode("-", $array);
            /* Fin de Formateo de la Fecha*/
            /* Actualizar el stock del producto */
            // Para validación
            $this->db->select('id_saldos_iniciales');
            $this->db->where('id_pro',$id_pro);
            $this->db->where('fecha_cierre',$fecha_formateada);
            $query = $this->db->get('saldos_iniciales');
            if($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $id_saldos_iniciales = $row->id_saldos_iniciales;
                }
                if($id_almacen == 1){
                    $actualizar = array(
                        'stock_inicial_sta_clara' => $stock_actualizado
                    );
                }else if($id_almacen == 2){
                    $actualizar = array(
                        'stock_inicial' => $stock_actualizado
                    );
                }
                $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                $this->db->update('saldos_iniciales', $actualizar);
                /* Fin de actualizacion */
            }else{
                $aux_parametro_cuadre = 1;
            }
        }while($aux_parametro_cuadre == 0);
        
    }

    public function actualizar_stock_producto_area($id_pro,$area,$id_almacen,$cantidad){
        if($id_almacen == 1){
            $actualizar_stock_area = array(
                'stock_area_sta_clara' => $cantidad
            );
            $this->db->where('id_area',$area);
            $this->db->where('id_pro',$id_pro);
            $this->db->update('detalle_producto_area', $actualizar_stock_area);
        }else if($id_almacen == 2){
            $actualizar_stock_area = array(
                'stock_area_sta_anita' => $cantidad
            );
            $this->db->where('id_area',$area);
            $this->db->where('id_pro',$id_pro);
            $this->db->update('detalle_producto_area', $actualizar_stock_area);
        }
        return true;
    }

    function finalizar_salida_before_13($result_insert, $id_area, $solicitante,$fecharegistro,$observacion,$id_maquina,$id_parte_maquina,$nombre_producto,$cantidad)
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
        // $id_area = $this->security->xss_clean($this->input->post('id_area'));
        // $solicitante = strtoupper($this->security->xss_clean($this->input->post('solicitante')));
        // $fecharegistro = $this->security->xss_clean($this->input->post('fecharegistro'));
        // $nombre_producto = $this->security->xss_clean($this->input->post('nombre_producto'));
        // $cantidad = $this->security->xss_clean($this->input->post('cantidad'));
        // $id_maquina = $this->security->xss_clean($this->input->post('id_maquina'));
        // $id_parte_maquina = $this->security->xss_clean($this->input->post('id_parte_maquina'));
        // $observacion = $this->security->xss_clean($this->input->post('observacion'));
        if($id_parte_maquina == ''){
            $id_parte_maquina = null;
        }
        // Obtengo los datos del producto antes de actualizarlos. Stock y Precio Unitario anterior
        $this->db->select('id_detalle_producto,stock,precio_unitario');
        $this->db->where('no_producto',$nombre_producto);
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

        // Validar el stock disponible por almacen
        if($id_almacen == 2){
            if($cantidad > $stock_actual_sta_anita){
                return 'error_stock';
            }else{
                // Validar si la salida esta en un periodo que ya cerro
                $result_cierre = $this->model_comercial->validarRegistroCierre($fecharegistro);
                if($result_cierre == 'successfull'){
                    // Realizar la salida del prodcuto
                    $a_data_detalle = array('id_detalle_producto' => $id_detalle_producto,
                                            'cantidad_salida' => $cantidad,
                                            'id_parte_maquina' => $id_parte_maquina,
                                            'id_maquina' => $id_maquina,
                                            'id_salida_producto' => $result_insert,
                                            'p_u_salida' => $precio_unitario
                                            );
                    $this->model_comercial->save_salida_detalle_producto($a_data_detalle,true);
                    // Fin del registro de la salida
                    if($result_insert != ""){
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
                                // if($row->id_kardex_producto > $auxiliar){
                                    $auxiliar = $row->id_kardex_producto; // devuelve el ultimo id que no necesariamente es el mayor
                                // }
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
                            // hacer el calculo del precio unitario y stock en funcion del movimiento
                            // nuevo precio unitario promedio
                            if($descripcion == 'SALIDA'){
                                $precio_unitario_anterior_especial = $precio_unitario_anterior;
                            }else if($descripcion == 'ENTRADA'){
                                $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                            }else if($descripcion == 'IMPORTACION'){
                                $precio_unitario_anterior_especial = 0;
                            }
                            $new_stock = $stock_actual - $cantidad;

                            if($new_stock >= 0){
                                /* Actualizar el stock del producto */
                                $this->model_comercial->descontarStock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                                /* Fin code */
                                /* Actualizar estado del producto validando si tiene stock 0 */
                                $stock_actual_general = $stock_actual + $stock_actual_sta_anita;
                                $stock_actualizado = $stock_actual + $stock_actual_sta_anita - $cantidad;
                                /*
                                if($stock_actualizado == 0){
                                    $this->model_comercial->actualizarEstado($id_detalle_producto);
                                }
                                */
                                /* Fin de actualizacion */

                                /* Realizar el registro en el kardex */
                                $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                                'descripcion' => "SALIDA",
                                                'id_detalle_producto' => $id_detalle_producto,
                                                'stock_anterior' => $stock_actual,
                                                'precio_unitario_anterior' => $precio_unitario_anterior_especial,
                                                'cantidad_salida' => $cantidad,
                                                'stock_actual' => $new_stock,
                                                //'precio_unitario_actual_promedio' => $new_precio_unitario_especial,
                                                'precio_unitario_actual' => $precio_unitario_anterior_especial,
                                                'num_comprobante' => $result_insert,
                                                );
                                $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                                /* End registro para el kardex */
                            }else{
                                /* Borrar los registros anteriores actualización e insercion de la salida*/
                                //$this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                                $this->model_comercial->eliminar_insert_salida($result_insert);
                                $validar_stock = 'no_existe_stock_disponible';
                            }
                        }else{
                            $validar_stock = 'no_existe_stock_disponible';
                            /*
                            // Actualizar el stock del producto
                            $this->model_comercial->descontarStock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                            // Fin code
                            // Actualizar estado del producto validando si tiene stock 0
                            $stock_actual_general = $stock_area_sta_anita + $stock_area_sta_clara;
                            $stock_actualizado = $stock_area_sta_anita + $stock_area_sta_clara - $cantidad;
                            */
                            // Decido comentar estas lineas de codigo por que no es necesario cambiar el estado del producto a un estado
                            // inactivo cuando el stock del producto llega a 0
                            /*
                            if($stock_actualizado == 0 AND $column_temp == ""){
                                $this->model_comercial->actualizarEstado($id_detalle_producto);
                            }
                            */
                            // Fin de actualizacion
                            // Realizar registro para el kardex
                            /*
                            $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                                   'descripcion' => "SALIDA",
                                                   'id_detalle_producto' => $id_detalle_producto,
                                                   'stock_anterior' => $stock_actual_general,
                                                   'precio_unitario_anterior' => $precio_unitario,
                                                   'cantidad_salida' => $cantidad,
                                                   'stock_actual' => $stock_actualizado,
                                                   'precio_unitario_actual' => $precio_unitario,
                                                   'num_comprobante' => $result_insert,
                                                   );
                            $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                            */
                            // Fin del registro para el kardex
                        }
                        // Hasta este punto actualiza los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso
                        if($validar_stock == 'no_existe_stock_disponible'){
                            return 'no_existe_stock_disponible';
                        }else{
                            /***************************************************************************************************************/
                            /* Obtener los datos del ultimo movimiento - sea este el ultimo movimiento realizado */
                            /* puede ser el registro del paso anterior */
                            $this->db->select('id_kardex_producto');
                            $this->db->where('fecha_registro',$fecharegistro);
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row){
                                if($row->id_kardex_producto > $auxiliar_2){
                                    $auxiliar_2 = $row->id_kardex_producto;
                                }
                            }

                            /* Obtener detalles del ultimo registro de la fecha en el kardex */
                            $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                            $this->db->where('id_kardex_producto',$auxiliar_2);
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row){
                                $stock_actual_actualizacion_registros = $row->stock_actual;
                                $precio_unitario_actual_promedio_actualizacion_registros = $row->precio_unitario_actual_promedio;
                                $precio_unitario_anterior_actualizacion_registros = $row->precio_unitario_anterior;
                                $descripcion_actualizacion_registros = $row->descripcion;
                                if($descripcion_actualizacion_registros == 'SALIDA'){
                                    $precio_unitario_actual_actualizacion_registros = $precio_unitario_anterior_actualizacion_registros;
                                }else if($descripcion_actualizacion_registros == 'ENTRADA'){
                                    $precio_unitario_actual_actualizacion_registros = $precio_unitario_actual_promedio_actualizacion_registros;
                                }
                            }
                            /***************************************************************************************************************/

                            /* Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización */
                            $this->db->select('id_kardex_producto');
                            $this->db->where('fecha_registro >',$fecharegistro);
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $this->db->order_by("fecha_registro", "asc");
                            $this->db->order_by("id_kardex_producto", "asc");
                            $query = $this->db->get('kardex_producto');
                            if(count($query->result()) > 0){
                                /* Validar si en algun registro existe un stock final negativo */
                                foreach($query->result() as $row){
                                    $id_kardex_producto = $row->id_kardex_producto; /* ID del movimiento en el kardex */
                                    /* Obtener los datos del movimiento del kardex */
                                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual,id_detalle_producto');
                                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                                    $query = $this->db->get('kardex_producto');
                                    foreach($query->result() as $row){
                                        $id_detalle_producto = $row->id_detalle_producto;
                                        $stock_actual_act = $row->stock_actual;
                                        $precio_unitario_actual_promedio_act = $row->precio_unitario_actual_promedio;
                                        $precio_unitario_anterior_act = $row->precio_unitario_anterior;
                                        $descripcion_act = $row->descripcion;
                                        $stock_anterior_act = $row->stock_anterior;
                                        $cantidad_salida_act = $row->cantidad_salida;
                                        $cantidad_ingreso_act = $row->cantidad_ingreso;
                                        $precio_unitario_actual_act = $row->precio_unitario_actual;
                                        if($descripcion_act == 'ENTRADA'){
                                            if($auxiliar_contador == 0){
                                                /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                                $new_stock_anterior_act_validacion = $stock_actual_actualizacion_registros; // stock_anterior
                                                $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
                                                $auxiliar_contador++;
                                            }
                                            /* validacion */
                                            $stock_actual_final = $new_stock_anterior_act_validacion + $cantidad_ingreso_act;
                                        }else if($descripcion_act == 'SALIDA'){
                                            if($auxiliar_contador == 0){
                                                /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                                $new_stock_anterior_act_validacion = $stock_actual_actualizacion_registros; // stock_anterior
                                                $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
                                                $auxiliar_contador++;
                                            }
                                            /* validacion */
                                            $stock_actual_final = $new_stock_anterior_act_validacion - $cantidad_salida_act;
                                        }
                                        /* validar stock negativo */
                                        if($stock_actual_final < 0){
                                            $auxiliar_stock_negatiVo = 'stock_negativo';
                                        }
                                        /* Dejar variables con el ultimo registro del stock y precio unitario obtenido */
                                        $new_stock_anterior_act_validacion = $stock_actual_final;
                                    }
                                }

                                if($auxiliar_stock_negatiVo == 'stock_negativo'){
                                    $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                                    $this->model_comercial->eliminar_insert_kardex($result_kardex);
                                    $this->model_comercial->eliminar_insert_salida($result_insert);
                                    return 'no_existe_stock_disponible_actualizacion_negativo';
                                }else{
                                    $id_kardex_producto = "";
                                    $auxiliar_contador = 0;
                                    $this->db->select('id_kardex_producto');
                                    $this->db->where('fecha_registro >',$fecharegistro);
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
                                                if($descripcion_act == 'ENTRADA'){
                                                    if($auxiliar_contador == 0){
                                                        /* El stock anterior viene a ser el stock actual del movimiento anterior */
                                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
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
                                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
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
                                                }
                                                /* Dejar variables con el ultimo registro del stock y precio unitario obtenido */
                                                /* Este paso se realizo en la linea 4277 pero solo sirvio para un recorrido */
                                                $new_stock_anterior_act = $stock_actual_final;
                                                if($descripcion_act == 'ENTRADA'){
                                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                                                }else if($descripcion_act == 'SALIDA'){
                                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                                }
                                            }
                                        }
                                    }else{
                                        return 'error_vacio';
                                    }
                                }
                            }
                            if($auxiliar_stock_negatiVo != 'stock_negativo'){
                                /* Creo las variables de sesion para un registro más rapido Area y Fecha, se quedará con el ultimo registro realizado*/
                                $datasession_area_fecha_salida = array(
                                    'id_area' => $this->security->xss_clean($this->input->post('id_area')),
                                    'fecharegistro' => strtoupper($this->security->xss_clean($this->input->post('fecharegistro'))),
                                );
                                $this->session->set_userdata($datasession_area_fecha_salida);

                                // ENVIAR PARAMETRO
                                return '1';
                            }
                        }
                    }
                }else{
                    // Comentamos esta linea para cuadrar el cierre ya que necesitamos quitar realizar más salidas
                    return 'error_cierre';
                    // Se deja comentado esta informacion para un desarrollo posterior
                    // Inicio de transacciones
                    //$hora = date('d-m-Y H:i:s'); //Para obtener la hora del sistema
                    /* Realizar la salida del prodcuto */
                    /*
                    $a_data = array('id_area' => $id_area,
                                    'fecha' => $fecharegistro,
                                    'id_detalle_producto' => $id_detalle_producto,
                                    'cantidad_salida' => $cantidad,
                                    'id_almacen' => $id_almacen,
                                    'p_u_salida' => $precio_unitario,
                                    'solicitante' => $solicitante,
                                    );
                    $result_insert = $this->model_comercial->saveSalidaProducto($a_data,true);
                    */
                    /* Fin del registro de la salida */
                    // Procedimiento para conocer la factura a la que pertenece la salida
                    // ademas de descontar el stock del producto a las unidades referencial
                    // Ubico las dos ultimas facturas usadas para cargar el stock de ese producto
                    /*
                    $contador_facturas = 0;
                    $variable_u = FALSE;
                    $variable_p = FALSE;
                    $invoice = $this->model_comercial->get_info_facturas_report($id_detalle_producto);
                    if(count($invoice) > 0){
                        $contador_facturas = count($invoice);
                        if($contador_facturas == 2){
                            foreach ($invoice as $row) {
                                // Almacenar toda la informacion en variables distintas
                                // Obtener las unidades referenciales de ese producto en esa factura
                                $id_ingreso_producto = $row->id_ingreso_producto;
                                if($variable_u == FALSE){
                                    // Con el ID de la factura unico el stock referencial del producto
                                    $this->db->select('unidades_referencial');
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                                    $query = $this->db->get('detalle_ingreso_producto');
                                    foreach($query->result() as $row){
                                        $unidades_referencial_u = $row->unidades_referencial;
                                    }
                                    $id_factura_u = $id_ingreso_producto;
                                    $variable_u = TRUE;
                                }else if($variable_p == FALSE && $variable_u == TRUE){
                                    $this->db->select('unidades_referencial');
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                                    $query = $this->db->get('detalle_ingreso_producto');
                                    foreach($query->result() as $row){
                                        $unidades_referencial_p = $row->unidades_referencial;
                                    }
                                    $id_factura_p = $id_ingreso_producto;
                                    $variable_p = TRUE;
                                }
                            }
                            // Verifico si la cantidad del stock referencial de la segunda factura tiene stock
                            // suficiente para generar la salida
                            if( $unidades_referencial_p >= $cantidad ){
                                // La salida se efectuo con stock de esta factura
                                // Guardar el id de la factura en el registro que se realizo lineas arriba
                                // $id_factura_final = $id_factura_p;
                                // Actualizar el stock de las unidades de referencia
                                $unidades_actualizadas = $unidades_referencial_p - $cantidad;
                                $actualizar = array('unidades_referencial'=> $unidades_actualizadas);
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->where('id_ingreso_producto',$id_factura_p);
                                $this->db->update('detalle_ingreso_producto', $actualizar);
                                // Guardar la relacion de la salida con la factura
                                $registro_data = array(
                                    'id_salida_producto'=> $result_insert,
                                    'id_ingreso_producto'=> $id_factura_p,
                                    'cantidad_utilizada'=> $cantidad
                                );
                                $this->db->insert('adm_facturas_asociadas', $registro_data);
                            }else if( $unidades_referencial_p < $cantidad && $unidades_referencial_p != 0){
                                // Utilizamos las unidades referenciales para completar la salida
                                $unidades_restantes = $cantidad - $unidades_referencial_p;
                                // Actualizamos la penultima factura
                                $actualizar_p = array('unidades_referencial'=> 0);
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->where('id_ingreso_producto',$id_factura_p);
                                $this->db->update('detalle_ingreso_producto', $actualizar_p);
                                // Guardar la relacion de la salida con la factura
                                $registro_data_p = array(
                                    'id_salida_producto'=> $result_insert,
                                    'id_ingreso_producto'=> $id_factura_p,
                                    'cantidad_utilizada'=> $unidades_referencial_p
                                );
                                $this->db->insert('adm_facturas_asociadas', $registro_data_p);
                                // Actualizamos la ultima factura
                                $unidades_actualizadas = $unidades_referencial_u - $unidades_restantes;
                                $actualizar_u = array('unidades_referencial'=> $unidades_actualizadas);
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->where('id_ingreso_producto',$id_factura_u);
                                $this->db->update('detalle_ingreso_producto', $actualizar_u);
                                // Guardar la relacion de la salida con la factura
                                $registro_data_u = array(
                                    'id_salida_producto'=> $result_insert,
                                    'id_ingreso_producto'=> $id_factura_u,
                                    'cantidad_utilizada'=> $unidades_restantes
                                );
                                $this->db->insert('adm_facturas_asociadas', $registro_data_u);
                            }else if($unidades_referencial_p == 0){
                                // Actualizamos la ultima factura
                                $unidades_actualizadas = $unidades_referencial_u - $cantidad;
                                $actualizar_u = array('unidades_referencial'=> $unidades_actualizadas);
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->where('id_ingreso_producto',$id_factura_u);
                                $this->db->update('detalle_ingreso_producto', $actualizar_u);
                                // Guardar la relacion de la salida con la factura
                                $registro_data_u = array(
                                    'id_salida_producto'=> $result_insert,
                                    'id_ingreso_producto'=> $id_factura_u,
                                    'cantidad_utilizada'=> $cantidad
                                );
                                $this->db->insert('adm_facturas_asociadas', $registro_data_u);
                            }
                        }else if($contador_facturas == 1){
                            foreach ($invoice as $row) {
                                $id_ingreso_producto = $row->id_ingreso_producto;
                                // Seleccionar unidades referencial
                                $this->db->select('unidades_referencial');
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                                $query = $this->db->get('detalle_ingreso_producto');
                                foreach($query->result() as $row){
                                    $unidades_referencial_u = $row->unidades_referencial;
                                }
                                $unidades_actualizadas = $unidades_referencial_u - $cantidad;
                                $actualizar_u = array('unidades_referencial'=> $unidades_actualizadas);
                                $this->db->where('id_detalle_producto',$id_detalle_producto);
                                $this->db->where('id_ingreso_producto',$id_ingreso_producto);
                                $this->db->update('detalle_ingreso_producto', $actualizar_u);
                                // Guardar la relacion de la salida con la factura
                                $registro_data_u = array(
                                    'id_salida_producto'=> $result_insert,
                                    'id_ingreso_producto'=> $id_ingreso_producto,
                                    'cantidad_utilizada'=> $cantidad
                                );
                                $this->db->insert('adm_facturas_asociadas', $registro_data_u);
                            }
                        }
                    }
                    */
                    /*
                    if($result_insert != ""){
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
                            // hacer el calculo del precio unitario y stock en funcion del movimiento
                            // Nuevo precio unitario promedio
                            if($descripcion == 'SALIDA'){
                                //$new_precio_unitario_especial = $precio_unitario_actual;
                                $precio_unitario_anterior_especial = $precio_unitario_anterior;
                            }else if($descripcion == 'ENTRADA'){
                                //$new_precio_unitario_especial = (($stock_actual*$precio_unitario_actual_promedio)+($item['qty']*$precio_unitario_soles))/($stock_actual+$item['qty']);
                                $precio_unitario_anterior_especial = $precio_unitario_actual_promedio;
                            }
                            $new_stock = $stock_actual - $cantidad;

                            if($new_stock >= 0){
                                // Actualizar el stock del producto
                                $this->model_comercial->descontarStock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                                // Fin code
                                // Actualizar estado del producto validando si tiene stock 0
                                $stock_actual_general = $stock_actual + $stock_actual_sta_anita;
                                $stock_actualizado = $stock_actual + $stock_actual_sta_anita - $cantidad;
                                if($stock_actualizado == 0 AND $column_temp == ""){
                                    $this->model_comercial->actualizarEstado($id_detalle_producto);
                                }
                                // Fin de actualizacion

                                // Realizar el registro en el kardex
                                $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                                'descripcion' => "SALIDA",
                                                'id_detalle_producto' => $id_detalle_producto,
                                                'stock_anterior' => $stock_actual,
                                                'precio_unitario_anterior' => $precio_unitario_anterior_especial,
                                                'cantidad_salida' => $cantidad,
                                                'stock_actual' => $new_stock,
                                                //'precio_unitario_actual_promedio' => $new_precio_unitario_especial,
                                                'precio_unitario_actual' => $precio_unitario_anterior_especial,
                                                'num_comprobante' => $result_insert,
                                                );
                                $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                                // End registro para el kardex
                            }else{
                                // Borrar los registros anteriores actualización e insercion de la salida
                                // $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                                $this->model_comercial->eliminar_insert_salida($result_insert);
                                $validar_stock = 'no_existe_stock_disponible';
                            }
                        }else{
                            // Actualizar el stock del producto
                            $this->model_comercial->descontarStock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                            // Fin code
                            // Actualizar estado del producto validando si tiene stock 0
                            $stock_actual_general = $stock_area_sta_anita + $stock_area_sta_clara;
                            $stock_actualizado = $stock_area_sta_anita + $stock_area_sta_clara - $cantidad;
                            // Decido comentar estas lineas de codigo por que no es necesario cambiar el estado del producto a un estado
                            // inactivo cuando el stock del producto llega a 0
                            // Fin de actualizacion
                            // Realizar registro para el kardex
                            $a_data_kardex = array('fecha_registro' => $fecharegistro,
                                                   'descripcion' => "SALIDA",
                                                   'id_detalle_producto' => $id_detalle_producto,
                                                   'stock_anterior' => $stock_actual_general,
                                                   'precio_unitario_anterior' => $precio_unitario,
                                                   'cantidad_salida' => $cantidad,
                                                   'stock_actual' => $stock_actualizado,
                                                   'precio_unitario_actual' => $precio_unitario,
                                                   'num_comprobante' => $result_insert,
                                                   );
                            $result_kardex = $this->model_comercial->saveSalidaProductoKardex($a_data_kardex,true);
                            // Fin del registro para el kardex
                        }
                        // Hasta este punto actualiza los datos del ultimo movimiento realizado en la fecha sea una salida o un ingreso
                        if($validar_stock == 'no_existe_stock_disponible'){
                            echo 'no_existe_stock_disponible';
                        }else{
                            // Obtener los datos del ultimo movimiento - sea este el ultimo movimiento realizado
                            // puede ser el registro del paso anterior
                            $this->db->select('id_kardex_producto');
                            $this->db->where('fecha_registro',$fecharegistro);
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row){
                                if($row->id_kardex_producto > $auxiliar_2){
                                    $auxiliar_2 = $row->id_kardex_producto;
                                }
                            }

                            // Obtener detalles del ultimo registro de la fecha en el kardex
                            $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion');
                            $this->db->where('id_kardex_producto',$auxiliar_2);
                            $query = $this->db->get('kardex_producto');
                            foreach($query->result() as $row){
                                $stock_actual_actualizacion_registros = $row->stock_actual;
                                $precio_unitario_actual_promedio_actualizacion_registros = $row->precio_unitario_actual_promedio;
                                $precio_unitario_anterior_actualizacion_registros = $row->precio_unitario_anterior;
                                $descripcion_actualizacion_registros = $row->descripcion;
                                if($descripcion_actualizacion_registros == 'SALIDA'){
                                    $precio_unitario_actual_actualizacion_registros = $precio_unitario_anterior_actualizacion_registros;
                                }else if($descripcion_actualizacion_registros == 'ENTRADA'){
                                    $precio_unitario_actual_actualizacion_registros = $precio_unitario_actual_promedio_actualizacion_registros;
                                }
                            }

                            // Se da paso a verificar si existen salidas posteriores a la fecha, para su actualización
                            $this->db->select('id_kardex_producto');
                            $this->db->where('fecha_registro >',$fecharegistro);
                            $this->db->where('id_detalle_producto',$id_detalle_producto);
                            $this->db->order_by("fecha_registro", "asc");
                            $this->db->order_by("id_kardex_producto", "asc");
                            $query = $this->db->get('kardex_producto');
                            if(count($query->result()) > 0){
                                // Validar si en algun registro existe un stock final negativo
                                foreach($query->result() as $row){
                                    $id_kardex_producto = $row->id_kardex_producto; // ID del movimiento en el kardex
                                    // Obtener los datos del movimiento del kardex
                                    $this->db->select('stock_actual,precio_unitario_actual_promedio,precio_unitario_anterior,descripcion,stock_anterior,cantidad_salida,cantidad_ingreso,precio_unitario_actual,id_detalle_producto');
                                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                                    $query = $this->db->get('kardex_producto');
                                    foreach($query->result() as $row){
                                        $id_detalle_producto = $row->id_detalle_producto;
                                        $stock_actual_act = $row->stock_actual;
                                        $precio_unitario_actual_promedio_act = $row->precio_unitario_actual_promedio;
                                        $precio_unitario_anterior_act = $row->precio_unitario_anterior;
                                        $descripcion_act = $row->descripcion;
                                        $stock_anterior_act = $row->stock_anterior;
                                        $cantidad_salida_act = $row->cantidad_salida;
                                        $cantidad_ingreso_act = $row->cantidad_ingreso;
                                        $precio_unitario_actual_act = $row->precio_unitario_actual;
                                        if($descripcion_act == 'ENTRADA'){
                                            if($auxiliar_contador == 0){
                                                // El stock anterior viene a ser el stock actual del movimiento anterior
                                                $new_stock_anterior_act_validacion = $stock_actual_actualizacion_registros; // stock_anterior
                                                $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
                                                $auxiliar_contador++;
                                            }
                                            // validacion
                                            $stock_actual_final = $new_stock_anterior_act_validacion + $cantidad_ingreso_act;
                                        }else if($descripcion_act == 'SALIDA'){
                                            if($auxiliar_contador == 0){
                                                // El stock anterior viene a ser el stock actual del movimiento anterior
                                                $new_stock_anterior_act_validacion = $stock_actual_actualizacion_registros; // stock_anterior
                                                $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
                                                $auxiliar_contador++;
                                            }
                                            // validacion
                                            $stock_actual_final = $new_stock_anterior_act_validacion - $cantidad_salida_act;
                                        }
                                        // validar stock negativo
                                        if($stock_actual_final < 0){
                                            $auxiliar_stock_negatiVo = 'stock_negativo';
                                        }
                                        // Dejar variables con el ultimo registro del stock y precio unitario obtenido
                                        $new_stock_anterior_act_validacion = $stock_actual_final;
                                    }
                                }

                                if($auxiliar_stock_negatiVo == 'stock_negativo'){
                                    $this->model_comercial->descontarStock_regresarstock($id_detalle_producto,$cantidad,$stock_actual_sta_anita,$id_almacen, $id_area);
                                    $this->model_comercial->eliminar_insert_kardex($result_kardex);
                                    $this->model_comercial->eliminar_insert_salida($result_insert);
                                    echo 'no_existe_stock_disponible_actualizacion_negativo';
                                }else{
                                    $id_kardex_producto = "";
                                    $auxiliar_contador = 0;
                                    $this->db->select('id_kardex_producto');
                                    $this->db->where('fecha_registro >',$fecharegistro);
                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                    $this->db->order_by("fecha_registro", "asc");
                                    $this->db->order_by("id_kardex_producto", "asc");
                                    $query_2 = $this->db->get('kardex_producto');
                                    if(count($query_2->result()) > 0){
                                        foreach($query_2->result() as $row_2){
                                            $id_kardex_producto = $row_2->id_kardex_producto; // ID del movimiento en el kardex
                                            // Obtener los datos del movimiento del kardex
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

                                                // Actualización del registro
                                                if($descripcion_act == 'ENTRADA'){
                                                    if($auxiliar_contador == 0){
                                                        // El stock anterior viene a ser el stock actual del movimiento anterior
                                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
                                                        $auxiliar_contador++;
                                                    }
                                                    // Actualizar los datos para una entrada
                                                    $stock_actual_final = $new_stock_anterior_act + $cantidad_ingreso_act;
                                                    $precio_unitario_actual_promedio_final = (($new_stock_anterior_act*$new_precio_unitario_anterior_act)+($cantidad_ingreso_act*$precio_unitario_actual_act))/($new_stock_anterior_act+$cantidad_ingreso_act);
                                                    // Actualizar BD
                                                    $actualizar = array(
                                                        'stock_anterior'=> $new_stock_anterior_act,
                                                        'precio_unitario_anterior'=> $new_precio_unitario_anterior_act,
                                                        'stock_actual'=> $stock_actual_final,
                                                        'precio_unitario_actual_promedio'=> $precio_unitario_actual_promedio_final
                                                    );
                                                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                                                    $this->db->update('kardex_producto', $actualizar);
                                                    // fin de actualizar
                                                    // Actualizar el precio unitario del producto
                                                    $actualizar_p_u_2 = array(
                                                        'precio_unitario'=> $precio_unitario_actual_promedio_final
                                                    );
                                                    $this->db->where('id_detalle_producto',$id_detalle_producto);
                                                    $this->db->update('detalle_producto', $actualizar_p_u_2);
                                                }else if($descripcion_act == 'SALIDA'){
                                                    if($auxiliar_contador == 0){
                                                        // El stock anterior viene a ser el stock actual del movimiento anterior
                                                        $new_stock_anterior_act = $stock_actual_actualizacion_registros; // stock_anterior
                                                        $new_precio_unitario_anterior_act = $precio_unitario_actual_actualizacion_registros; // precio_unitario_anterior
                                                        $auxiliar_contador++;
                                                    }
                                                    // Actualizar los datos para una salida
                                                    $stock_actual_final = $new_stock_anterior_act - $cantidad_salida_act;
                                                    $precio_unitario_actual_final = $new_precio_unitario_anterior_act;
                                                    $precio_unitario_anterior_final = $new_precio_unitario_anterior_act;
                                                    // Actualizar BD
                                                    $actualizar = array(
                                                        'stock_anterior'=> $new_stock_anterior_act,
                                                        'precio_unitario_anterior'=> $precio_unitario_anterior_final,
                                                        'stock_actual'=> $stock_actual_final,
                                                        'precio_unitario_actual'=> $precio_unitario_actual_final
                                                    );
                                                    $this->db->where('id_kardex_producto',$id_kardex_producto);
                                                    $this->db->update('kardex_producto', $actualizar);
                                                    // fin de actualizar
                                                }
                                                // Dejar variables con el ultimo registro del stock y precio unitario obtenido
                                                // Este paso se realizo en la linea 4277 pero solo sirvio para un recorrido
                                                $new_stock_anterior_act = $stock_actual_final;
                                                if($descripcion_act == 'ENTRADA'){
                                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_promedio_final;
                                                }else if($descripcion_act == 'SALIDA'){
                                                    $new_precio_unitario_anterior_act = $precio_unitario_actual_final;
                                                }
                                            }
                                        }
                                    }else{
                                        echo 'error_vacio';
                                    }
                                }
                            }
                            if($auxiliar_stock_negatiVo != 'stock_negativo'){
                                // Creo las variables de sesion para un registro más rapido Area y Fecha, se quedará con el ultimo registro realizado
                                $datasession_area_fecha_salida = array(
                                    'id_area' => $this->security->xss_clean($this->input->post('id_area')),
                                    'fecharegistro' => strtoupper($this->security->xss_clean($this->input->post('fecharegistro'))),
                                );
                                $this->session->set_userdata($datasession_area_fecha_salida);

                                // ENVIAR PARAMETRO
                                // Se cambia el lugar del envio de este parametro ya que se debe actualizar los datos de cierre
                                // echo '1';
                            }
                        }
                    }
                    */
                    // Cuadrar los montos de cierre para salidas cuya fecha ya tiene un cierre
                    // Formateando la Fecha
                    /*
                    $elementos = explode("-", $fecharegistro);
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
                    $fecha_formateada = implode("-", $array);

                    do{
                        // Esta fecha me va a servir para ubicar el cierre del producto del mes posterior para su actualizacion
                        // Realizar la actualización del monto de cierre del producto en funcion de la fecha de registro de la factura
                        $this->db->select('id_saldos_iniciales,stock_inicial,stock_inicial_sta_clara,precio_uni_inicial');
                        $this->db->where('id_pro',$id_pro);
                        $this->db->where('fecha_cierre',$fecha_formateada);
                        $query = $this->db->get('saldos_iniciales');
                        if($query->num_rows()>0){
                            foreach($query->result() as $row){
                                $id_saldos_iniciales = $row->id_saldos_iniciales;
                                $stock_inicial = $row->stock_inicial;
                                $stock_inicial_sta_clara = $row->stock_inicial_sta_clara;
                                $precio_uni_inicial = $row->precio_uni_inicial;
                            }
                            // Actualizar unidades de cierre solo en las unidades del almacen de sta anita
                            $stock_actualizado_cierre = $stock_inicial - $cantidad;
                            $actualizar = array(
                                'stock_inicial'=> $stock_actualizado_cierre
                            );
                            $this->db->where('id_saldos_iniciales',$id_saldos_iniciales);
                            $this->db->update('saldos_iniciales', $actualizar);
                            // Actualizar monto final de cierre del mes
                            $stock_general_cierre_anterior = $stock_inicial + $stock_inicial_sta_clara;
                            $stock_general_cierre = ($stock_inicial-$cantidad) + $stock_inicial_sta_clara;
                            $monto_parcial_producto_anterior = $precio_uni_inicial * $stock_general_cierre_anterior;
                            $monto_parcial_producto_nuevo = $precio_uni_inicial * $stock_general_cierre;
                            // Seleccionar el monto de cierre
                            $this->db->select('fecha_cierre,monto_cierre_sta_anita,monto_cierre_sta_clara,fecha_auxiliar');
                            $this->db->where('fecha_auxiliar',$fecha_formateada);
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
                                $this->db->where('fecha_auxiliar',$fecha_formateada);
                                $this->db->update('monto_cierre',$actualizar);

                                // Aumentar la fecha para la siguiente busqueda de cierre // Ya se tiene la fecha con el formato correcto
                                $elementos_act = explode("-", $fecha_formateada);
                                $anio = $elementos_act[0];
                                $mes = $elementos_act[1];
                                $dia = $elementos_act[2];
                                if($mes == 12){
                                    $anio = $anio + 1;
                                    $mes_siguiente = 01;
                                    $dia = 1;
                                }else if($mes <= 11 ){
                                    $mes_siguiente = $mes + 1;
                                    $dia = 1;
                                }
                                $array = array($anio, $mes_siguiente, $dia);
                                $fecha_formateada = implode("-", $array);
                            }
                        }else{
                            $aux_bucle_saldos_ini = 1;
                        }
                    }while($aux_bucle_saldos_ini == 0);
                    echo '1';
                    */
                }
            }
        }
      
        /* Fin del proceso - transacción */
        $this->db->trans_complete();
    }

    function get_nombre_solicitante_autocomplete_salida($nombre_solicitante){
        try {
            $filtro = "";
            $filtro .= "LIMIT 10";
            $sql = "SELECT DISTINCT salida_producto.solicitante
                    FROM salida_producto
                    WHERE salida_producto.solicitante ILIKE '%".$nombre_solicitante."%'".$filtro;
            $query = $this->db->query($sql);

            if($query->num_rows()>0)
            {
                return $query->result_array();
            }
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
            return false;
        }
    }




}