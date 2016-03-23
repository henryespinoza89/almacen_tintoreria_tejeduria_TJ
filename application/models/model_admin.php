<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//date_default_timezone_set('MST');
class Model_admin extends CI_Model {

	public function __construct(){
		parent::__construct();
        $this->load->library('session');
	}


	public function listarAlmacen(){
        $query = $this->db->query("SELECT id_almacen,no_almacen FROM almacen WHERE almacen.no_almacen <> 'ADMINISTRADOR' AND almacen.no_almacen <> 'GERENCIA' ORDER BY no_almacen");
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_almacen, ENT_QUOTES)] = htmlspecialchars($row->no_almacen, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarProducto(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe el nombre del producto
        if($this->input->post('nombre')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }
        // si esribe ID
        if($this->input->post('id_producto')){
            $filtro .= " AND (producto.id_producto ILIKE '%".$this->security->xss_clean($this->input->post('id_producto'))."%')"; // El ILIKE va cuando el valor a pasar no es un integer  
        }
        if($this->input->post('almacen')){
            $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen')); 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        if($this->input->post('categoria')){
            $filtro .= " AND categoria.id_categoria =".(int)$this->security->xss_clean($this->input->post('categoria')); 
        }
        $sql = "SELECT producto.id_pro,producto.id_producto,detalle_producto.no_producto,almacen.no_almacen,categoria.no_categoria,
        procedencia.no_procedencia,detalle_producto.stock,detalle_producto.precio_unitario,producto.observacion,
        unidad_medida.nom_uni_med 
        FROM producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN almacen ON producto.id_almacen = almacen.id_almacen
        INNER JOIN unidad_medida ON producto.id_unidad_medida = unidad_medida.id_unidad_medida
        WHERE producto.id_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarProducto_admin(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe el nombre del producto
        if($this->input->post('nombre_pro')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre_pro'))."%')";   
        }
        // si esribe ID
        if($this->input->post('id_pro')){
            $filtro .= " AND (producto.id_producto ILIKE '%".$this->security->xss_clean($this->input->post('id_pro'))."%')"; // El ILIKE va cuando el valor a pasar no es un integer  
        }
        if($this->input->post('almacen_2')){
            $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen_2')); 
        }
        if($this->input->post('categoria_2')){
            $filtro .= " AND categoria.id_categoria =".(int)$this->security->xss_clean($this->input->post('categoria_2')); 
        }
        $sql = "SELECT producto.id_pro, producto.id_producto,detalle_producto.no_producto,almacen.no_almacen,
        categoria.no_categoria, procedencia.no_procedencia,detalle_producto.stock,detalle_producto.precio_unitario,
        producto.observacion, producto.unidad_medida FROM producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN almacen ON producto.id_almacen = almacen.id_almacen
        WHERE producto.id_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarMaquinaRegistradas(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('tipo_maquina')){
            $filtro = " AND (nombre_maquina.nombre_maquina ILIKE '%".$this->security->xss_clean($this->input->post('tipo_maquina'))."%')";
        }
        if($this->input->post('marca_maquina')){
            $filtro = " AND (marca_maquina.no_marca ILIKE '%".$this->security->xss_clean($this->input->post('marca_maquina'))."%')";   
        }
        if($this->input->post('mod_maquina')){
            $filtro .= " AND (modelo_maquina.no_modelo ILIKE '%".$this->security->xss_clean($this->input->post('mod_maquina'))."%')";   
        }
        if($this->input->post('serie_maquina')){
            $filtro .= " AND (serie_maquina.no_serie ILIKE '%".$this->security->xss_clean($this->input->post('serie_maquina'))."%')";   
        }
        if($this->input->post('almacen')){
            $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen')); 
        }
        if($this->input->post('estado')){
            $filtro .= " AND estado_maquina.id_estado_maquina =".(int)$this->security->xss_clean($this->input->post('estado')); 
        }
        if($this->session->userdata('almacen') != 4){
            $filtro .= " AND maquina.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        } 
        $sql = "SELECT maquina.id_maquina, nombre_maquina.nombre_maquina, marca_maquina.no_marca, modelo_maquina.no_modelo,
        serie_maquina.no_serie, estado_maquina.no_estado_maquina, maquina.observacion_maq, maquina.fe_registro, almacen.no_almacen
        FROM maquina 
        INNER JOIN nombre_maquina ON maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        INNER JOIN modelo_maquina ON maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina
        INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina AND maquina.id_marca_maquina = marca_maquina.id_marca_maquina AND modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina
        INNER JOIN serie_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina AND maquina.id_serie_maquina = serie_maquina.id_serie_maquina
        INNER JOIN estado_maquina ON maquina.id_estado_maquina = estado_maquina.id_estado_maquina
        INNER JOIN almacen ON maquina.id_almacen = almacen.id_almacen AND marca_maquina.id_almacen = almacen.id_almacen AND modelo_maquina.id_almacen = almacen.id_almacen AND serie_maquina.id_almacen = almacen.id_almacen
        WHERE maquina.id_maquina IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarMaquina_admin(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('tipo')){
            $filtro = " AND (nombre_maquina.nombre_maquina ILIKE '%".$this->security->xss_clean($this->input->post('tipo'))."%')";
        }
        if($this->input->post('marca')){
            $filtro = " AND (marca_maquina.no_marca ILIKE '%".$this->security->xss_clean($this->input->post('marca'))."%')";   
        }
        if($this->input->post('modelo')){
            $filtro .= " AND (modelo_maquina.no_modelo ILIKE '%".$this->security->xss_clean($this->input->post('modelo'))."%')";   
        }
        if($this->input->post('serie')){
            $filtro .= " AND (serie_maquina.no_serie ILIKE '%".$this->security->xss_clean($this->input->post('serie'))."%')";   
        }
        if($this->input->post('almacen_2')){
            $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen_2')); 
        }
        if($this->input->post('estado_2')){
            $filtro .= " AND estado_maquina.id_estado_maquina =".(int)$this->security->xss_clean($this->input->post('estado_2')); 
        } 
        $sql = "SELECT maquina.id_maquina, nombre_maquina.nombre_maquina, marca_maquina.no_marca, modelo_maquina.no_modelo,
        serie_maquina.no_serie, estado_maquina.no_estado_maquina, maquina.observacion_maq, maquina.fe_registro, almacen.no_almacen
        FROM maquina 
        INNER JOIN nombre_maquina ON maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina
        INNER JOIN modelo_maquina ON maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina
        INNER JOIN marca_maquina ON marca_maquina.id_nombre_maquina = nombre_maquina.id_nombre_maquina AND maquina.id_marca_maquina = marca_maquina.id_marca_maquina AND modelo_maquina.id_marca_maquina = marca_maquina.id_marca_maquina
        INNER JOIN serie_maquina ON serie_maquina.id_modelo_maquina = modelo_maquina.id_modelo_maquina AND maquina.id_serie_maquina = serie_maquina.id_serie_maquina
        INNER JOIN estado_maquina ON maquina.id_estado_maquina = estado_maquina.id_estado_maquina
        INNER JOIN almacen ON maquina.id_almacen = almacen.id_almacen AND marca_maquina.id_almacen = almacen.id_almacen AND modelo_maquina.id_almacen = almacen.id_almacen AND serie_maquina.id_almacen = almacen.id_almacen
        WHERE maquina.id_maquina IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarUsuario_admin(){
            //esta variable filtrará y concatenará los diferentes filtros
            // CASE usuario.id_almacen WHEN '1' THEN 'Santa Clara' WHEN '2' THEN 'Hilos' WHEN '3' THEN 'Tejedurias' END as id_almacen
            $filtro = "";
            // si escribe el nombre 
            if($this->input->post('nombre')){
                $filtro = " AND (usuario.no_usuario ||' '|| usuario.ape_paterno ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
            }
            if($this->input->post('nombre_ape')){
                $filtro = " AND (usuario.no_usuario ||' '|| usuario.ape_paterno ILIKE '%".$this->security->xss_clean($this->input->post('nombre_ape'))."%')";   
            }
            if($this->input->post('txt_usuario')){
                $filtro .= " AND (usuario.tx_usuario ILIKE '%".$this->security->xss_clean($this->input->post('txt_usuario'))."%')";   
            }
            if($this->input->post('no_usu')){
                $filtro .= " AND (usuario.tx_usuario ILIKE '%".$this->security->xss_clean($this->input->post('no_usu'))."%')";   
            }
            if($this->input->post('almacen')){
                $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen'));
            }
            if($this->input->post('almacen_2')){
                $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen_2'));
            }
            if($this->input->post('listatipousuarios')){
                $filtro .= " AND tipo_usuario.id_tipo_usuario =".(int)$this->security->xss_clean($this->input->post('listatipousuarios'));
            } 
            if($this->input->post('tipo_2')){
                $filtro .= " AND tipo_usuario.id_tipo_usuario =".(int)$this->security->xss_clean($this->input->post('tipo_2'));
            }
            $sql = "SELECT usuario.id_usuario, usuario.no_usuario, usuario.ape_paterno, 
            CASE usuario.fl_estado WHEN 't' THEN 'Activo' WHEN 'f' THEN 'Inactivo' END as fl_estado, 
            usuario.tx_usuario, tipo_usuario.no_tipo_usuario, almacen.no_almacen, usuario.fe_registro, usuario.correo_electronico
            FROM usuario 
            INNER JOIN tipo_usuario ON usuario.id_tipo_usuario = tipo_usuario.id_tipo_usuario
            INNER JOIN almacen ON usuario.id_almacen = almacen.id_almacen
            WHERE usuario.id_usuario IS NOT NULL".$filtro;
            $query = $this->db->query($sql);
            if($query->num_rows()>0)
            {
                return $query->result();
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

    public function listarProveedores(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe la razon social del proveedor
        if($this->input->post('nombre')){
            $filtro = " AND (proveedor.razon_social ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }
        if($this->input->post('nombre_filtro')){
            $filtro = " AND (proveedor.razon_social ILIKE '%".$this->security->xss_clean($this->input->post('nombre_filtro'))."%')";   
        }
        if($this->input->post('ruc_prov')){
            $filtro .= " AND proveedor.RUC =".$this->security->xss_clean($this->input->post('ruc_prov'));
        }
        if($this->input->post('ruc_filtro')){
            $filtro .= " AND proveedor.RUC =".$this->security->xss_clean($this->input->post('ruc_filtro'));
        }
        if($this->input->post('almacen')){
            $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen'));
        }
        if($this->input->post('almacen_2')){
            $filtro .= " AND almacen.id_almacen =".(int)$this->security->xss_clean($this->input->post('almacen_2'));
        }
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.ruc, proveedor.pais, 
        proveedor.direccion, proveedor.telefono1, proveedor.fe_registro, almacen.no_almacen FROM proveedor
        INNER JOIN almacen ON proveedor.id_almacen = almacen.id_almacen
        WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function saveUsuario(){
        $nombreusu = strtoupper($this->security->xss_clean($this->input->post('nombreusu')));
        $apellidousu = strtoupper($this->security->xss_clean($this->input->post('apellidousu')));
        $estado = $this->security->xss_clean($this->input->post('estado'));
        $usuario = $this->security->xss_clean($this->input->post('usuario'));
        $tiposusuarios = $this->security->xss_clean($this->input->post('tiposusuarios'));
        $almacen = $this->security->xss_clean($this->input->post('almacen'));
        $datacontrasena = $this->security->xss_clean($this->input->post('datacontrasena'));
        $email = $this->security->xss_clean($this->input->post('email'));
        //Verifico si existe
        $this->db->where('tx_usuario',$usuario);
        $query = $this->db->get('usuario');
        if($query->num_rows()>0){
            return false;
        }else{
            //Registro de Periodo
            $registro = array(
                'no_usuario'=> $nombreusu,
                'ape_paterno'=> $apellidousu,
                'fl_estado'=> $estado,  
                'tx_usuario'=> $usuario,
                'tx_contrasena'=> $datacontrasena,
                'id_tipo_usuario'=> $tiposusuarios,
                'id_almacen'=> $almacen,
                'correo_electronico'=> $email,
                'fe_registro'=> date('Y-m-d')
            );
            $this->db->insert('usuario', $registro);
            return true;
        }
    }

    public function actualizaUsuario(){
        //Recuperamos el ID  -> 
        $id_usuario = $this->security->xss_clean($this->uri->segment(3));
        $nombreusu = strtoupper($this->security->xss_clean($this->input->post('editnombres')));
        $apellidousu = strtoupper($this->security->xss_clean($this->input->post('editapellido')));
        $tiposusuarios = $this->security->xss_clean($this->input->post('editipo'));
        $estado = $this->security->xss_clean($this->input->post('editestado'));
        $almacen = $this->security->xss_clean($this->input->post('editalmacen'));
        $usuario = strtoupper($this->security->xss_clean($this->input->post('editusuario')));
        $datacontrasena = $this->security->xss_clean($this->input->post('editcontrasena'));
        $editemail = $this->security->xss_clean($this->input->post('editemail'));
        if(empty($datacontrasena)){
            $actualizar = array(
                'no_usuario' => $nombreusu,
                'ape_paterno' => $apellidousu,
                'fl_estado' => $estado,
                'tx_usuario' => $usuario,
                'id_tipo_usuario'=>$tiposusuarios,
                'correo_electronico'=>$editemail,
                'id_almacen'=>$almacen                
            );
        }else{
            $actualizar = array(
                'no_usuario' => $nombreusu,
                'ape_paterno' => $apellidousu,
                'fl_estado' => $estado,
                'tx_usuario' => $usuario,
                'id_tipo_usuario'=>$tiposusuarios,
                'id_almacen'=>$almacen,
                'correo_electronico'=>$editemail,
                'tx_contrasena' =>$datacontrasena
            );
        }
            $this->db->where('id_usuario',$id_usuario);
            $this->db->update('usuario', $actualizar);
            return true; 
    }

    public function UpdatePassword(){
        //Recuperamos los datos
        $user = $this->security->xss_clean($this->input->post('user'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $datacontrasena = $this->security->xss_clean($this->input->post('datacontrasena_actualizar'));

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

    function listarProductos_report_excel(){
        $sql = "SELECT producto.id_producto,detalle_producto.no_producto,categoria.no_categoria,procedencia.no_procedencia,producto.unidad_medida
        FROM
        producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        WHERE producto.id_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaIngresoProducto(){
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante,ingreso_producto.nro_comprobante,ingreso_producto.fecha,
        ingreso_producto.id_moneda,ingreso_producto.id_proveedor,ingreso_producto.total,ingreso_producto.gastos,ingreso_producto.id_almacen,
        ingreso_producto.id_agente,ingreso_producto.cs_igv,ingreso_producto.serie_comprobante
        FROM ingreso_producto
        WHERE ingreso_producto.id_ingreso_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaDetalleIngresoProducto(){
        $sql = "SELECT detalle_ingreso_producto.id_detalle_ing_prod,detalle_ingreso_producto.unidades,detalle_ingreso_producto.id_detalle_producto,
        detalle_ingreso_producto.precio,detalle_ingreso_producto.id_ingreso_producto
        FROM detalle_ingreso_producto
        WHERE detalle_ingreso_producto.id_detalle_ing_prod IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaTipoCambio(){
        $sql = "SELECT tipo_cambio.id_tipo_cambio,tipo_cambio.fecha_actual,tipo_cambio.dolar_compra,tipo_cambio.dolar_venta,tipo_cambio.euro_compra,
        tipo_cambio.euro_venta,tipo_cambio.fr_compra,tipo_cambio.fr_venta
        FROM tipo_cambio
        WHERE tipo_cambio.id_tipo_cambio IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaSaldosIniciales(){
        $sql = "SELECT saldos_iniciales.id_saldos_iniciales,saldos_iniciales.id_pro,saldos_iniciales.fecha_cierre,saldos_iniciales.stock_inicial,saldos_iniciales.precio_uni_inicial
        FROM saldos_iniciales
        WHERE saldos_iniciales.id_saldos_iniciales IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaSalidaProducto(){
        $sql = "SELECT salida_producto.id_salida_producto,salida_producto.id_nombre_maquina,salida_producto.id_marca,salida_producto.id_modelo,salida_producto.id_serie,salida_producto.id_area,
        salida_producto.solicitante,salida_producto.fecha,salida_producto.id_detalle_producto,salida_producto.cantidad_salida,salida_producto.id_almacen,salida_producto.p_u_salida
        FROM salida_producto
        WHERE salida_producto.id_salida_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaKardexProducto(){
        $sql = "SELECT kardex_producto.id_kardex_producto,kardex_producto.descripcion,kardex_producto.id_detalle_producto,kardex_producto.stock_anterior,kardex_producto.precio_unitario_anterior,
        kardex_producto.cantidad_salida,kardex_producto.stock_actual,kardex_producto.precio_unitario_actual,kardex_producto.fecha_registro,kardex_producto.cantidad_ingreso,
        kardex_producto.precio_unitario_actual_promedio,kardex_producto.serie_comprobante,kardex_producto.num_comprobante
        FROM kardex_producto
        WHERE kardex_producto.id_kardex_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaDetalleProducto(){
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto,detalle_producto.stock,detalle_producto.precio_unitario
        FROM detalle_producto
        WHERE detalle_producto.id_detalle_producto IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }
    
    function getTablaProducto(){
        $sql = "SELECT producto.id_pro,producto.id_producto,producto.observacion,producto.id_almacen,producto.id_procedencia,producto.id_categoria,
        producto.id_detalle_producto,producto.id_tipo_producto,producto.id_unidad_medida,producto.estado,producto.column_temp
        FROM producto
        WHERE producto.id_pro IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    function getTablaProveedor(){
        $sql = "SELECT proveedor.id_proveedor,proveedor.razon_social,proveedor.ruc,proveedor.pais,proveedor.departamento,proveedor.provincia,
        proveedor.distrito,proveedor.direccion,proveedor.referencia,proveedor.contacto,proveedor.cargo,proveedor.email,proveedor.telefono1,
        proveedor.telefono2,proveedor.fax,proveedor.web,proveedor.id_almacen,proveedor.fe_registro
        FROM proveedor
        WHERE proveedor.id_proveedor IS NOT NULL";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function insert_tabla_detalle_ingreso_producto($a_data)
    {
        $insert = $this->db->insert('detalle_ingreso_producto', $a_data);
        return true;
    }

    public function insert_tabla_ingreso_producto($a_data)
    {
        $insert = $this->db->insert('ingreso_producto', $a_data);
        return true;
    }

    public function insert_tabla_tipo_cambio($a_data)
    {
        $insert = $this->db->insert('tipo_cambio', $a_data);
        return true;
    }

    public function insert_tabla_saldos_iniciales($a_data)
    {
        $insert = $this->db->insert('saldos_iniciales', $a_data);
        return true;
    }

    public function insert_tabla_salida_producto($a_data)
    {
        $insert = $this->db->insert('salida_producto', $a_data);
        return true;
    }

    public function insert_tabla_kardex_producto($a_data)
    {
        $insert = $this->db->insert('kardex_producto', $a_data);
        return true;
    }

    public function insert_tabla_detalle_producto($a_data)
    {
        $insert = $this->db->insert('detalle_producto', $a_data);
        return true;
    }

    public function insert_tabla_producto($a_data)
    {
        $insert = $this->db->insert('producto', $a_data);
        return true;
    }

    public function insert_tabla_proveedor($a_data)
    {
        $insert = $this->db->insert('proveedor', $a_data);
        return true;
    }





}