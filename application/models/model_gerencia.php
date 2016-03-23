<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//date_default_timezone_set('MST');
class Model_gerencia extends CI_Model {

	public function __construct(){
		parent::__construct();
        $this->load->library('session');
	}

	public function listarMaquinas(){
        $this->db->where('id_almacen',1);
        $this->db->select('id_nombre_maquina,nombre_maquina');
        $this->db->order_by('nombre_maquina', 'ASC');           
        $query = $this->db->get('nombre_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_nombre_maquina, ENT_QUOTES)] = htmlspecialchars($row->nombre_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarMaquinas_tejeduria(){
        $this->db->where('id_almacen',3);
        $this->db->select('id_nombre_maquina,nombre_maquina');
        $this->db->order_by('nombre_maquina', 'ASC');           
        $query = $this->db->get('nombre_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_nombre_maquina, ENT_QUOTES)] = htmlspecialchars($row->nombre_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listarMaquinas_hilos(){
        $this->db->where('id_almacen',2);
        $this->db->select('id_nombre_maquina,nombre_maquina');
        $this->db->order_by('nombre_maquina', 'ASC');           
        $query = $this->db->get('nombre_maquina');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->id_nombre_maquina, ENT_QUOTES)] = htmlspecialchars($row->nombre_maquina, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function getMarca(){
        //Obtenemos la ID del Departamento
        $almacen = 1;
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

    public function getMarca_tejeduria(){
        //Obtenemos la ID del Departamento
        $almacen = 3;
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

    public function getMarca_hilos(){
        //Obtenemos la ID del Departamento
        $almacen = 2;
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
        $almacen = 1;
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

    public function getModelo_tejeduria(){
        //Obtenemos la ID del Departamento
        $almacen = 3;
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

    public function getModelo_hilos(){
        //Obtenemos la ID del Departamento
        $almacen = 2;
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
        $almacen = 1;
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

    public function getSerie_tejeduria(){
        //Obtenemos la ID del Departamento
        $almacen = 3;
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

    public function getSerie_hilos(){
        //Obtenemos la ID del Departamento
        $almacen = 2;
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

    public function listarMaquinaFiltroPdf(){
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
        $filtro .= " AND maquina.id_almacen =".(int)$this->security->xss_clean(1); 
        
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

    public function listarMaquinaFiltroPdf_tejeduria(){
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
        $filtro .= " AND maquina.id_almacen =".(int)$this->security->xss_clean(3); 
        
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

    public function listarMaquinaFiltroPdf_hilos(){
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
        $filtro .= " AND maquina.id_almacen =".(int)$this->security->xss_clean(2); 
        
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
        $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean(1);

        $sql = "SELECT producto.id_pro,producto.id_producto,detalle_producto.no_producto,detalle_producto.stock,
        categoria.no_categoria,procedencia.no_procedencia,detalle_producto.precio_unitario,
        producto.observacion,producto.unidad_medida,producto.id_almacen
        FROM producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        WHERE producto.id_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarProductoFiltro_tejeduria(){
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
        $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean(3);

       $sql = "SELECT producto.id_pro,producto.id_producto,detalle_producto.no_producto,detalle_producto.stock,
        categoria.no_categoria,procedencia.no_procedencia,detalle_producto.precio_unitario,
        producto.observacion,producto.unidad_medida,producto.id_almacen
        FROM producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        WHERE producto.id_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }


    public function listarProductoFiltro_hilos(){
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
        $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean(2);

        $sql = "SELECT producto.id_pro, producto.id_producto, detalle_producto.no_producto, detalle_producto.stock,
        categoria.no_categoria, procedencia.no_procedencia, detalle_producto.precio_unitario, producto.observacion,
        producto.unidad_medida, producto.id_almacen
        FROM producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        WHERE producto.id_producto IS NOT NULL".$filtro;
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
        $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean(1);
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

    public function listaRegistrosSalidaFiltroPdf_tejeduria(){
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
        $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean(3);
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

    public function listaRegistrosSalidaFiltroPdf_hilos(){
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
        $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean(2);
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

    public function listaRegistrosSalidaFiltroPdf_sta_clara(){
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
        $filtro .= " AND salida_producto.id_almacen =".(int)$this->security->xss_clean(1);
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

    public function listarProveedoresFiltroPdf(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe la razon social del proveedor
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(proveedor.fe_registro) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(proveedor.fe_registro) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean(1); 
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.ruc, proveedor.pais, 
        proveedor.direccion, proveedor.telefono1,proveedor.fe_registro FROM proveedor 
        INNER JOIN almacen ON proveedor.id_almacen = almacen.id_almacen
        WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarProveedoresFiltroPdf_tejeduria(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe la razon social del proveedor
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(proveedor.fe_registro) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(proveedor.fe_registro) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean(3); 
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.ruc, proveedor.pais, 
        proveedor.direccion, proveedor.telefono1,proveedor.fe_registro FROM proveedor 
        INNER JOIN almacen ON proveedor.id_almacen = almacen.id_almacen
        WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarProveedoresFiltroPdf_hilos(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        // si escribe la razon social del proveedor
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(proveedor.fe_registro) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(proveedor.fe_registro) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean(2); 
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.ruc, proveedor.pais, 
        proveedor.direccion, proveedor.telefono1,proveedor.fe_registro FROM proveedor 
        INNER JOIN almacen ON proveedor.id_almacen = almacen.id_almacen
        WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }


    public function listaProveedor(){
        $filtro = "";
        $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean(1);
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.id_almacen
        FROM proveedor WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_proveedor, ENT_QUOTES)] = htmlspecialchars($row->razon_social, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaProveedor_tejeduria(){
        $filtro = "";
        $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean(3);
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.id_almacen
        FROM proveedor WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_proveedor, ENT_QUOTES)] = htmlspecialchars($row->razon_social, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaProveedor_hilos(){
        $filtro = "";
        $filtro .= " AND proveedor.id_almacen =".(int)$this->security->xss_clean(2);
        $sql = "SELECT proveedor.id_proveedor, proveedor.razon_social, proveedor.id_almacen
        FROM proveedor WHERE proveedor.id_proveedor IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_proveedor, ENT_QUOTES)] = htmlspecialchars($row->razon_social, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaAgenteAduana(){
        $filtro = "";
        $filtro .= " AND agente_aduana.id_almacen =".(int)$this->security->xss_clean(1);
        $sql = "SELECT id_agente,no_agente FROM agente_aduana  
                WHERE agente_aduana.id_agente IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_agente, ENT_QUOTES)] = htmlspecialchars($row->no_agente, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaAgenteAduana_tejeduria(){
        $filtro = "";
        $filtro .= " AND agente_aduana.id_almacen =".(int)$this->security->xss_clean(3);
        $sql = "SELECT id_agente,no_agente FROM agente_aduana  
                WHERE agente_aduana.id_agente IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_agente, ENT_QUOTES)] = htmlspecialchars($row->no_agente, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaAgenteAduana_hilos(){
        $filtro = "";
        $filtro .= " AND agente_aduana.id_almacen =".(int)$this->security->xss_clean(2);
        $sql = "SELECT id_agente,no_agente FROM agente_aduana  
                WHERE agente_aduana.id_agente IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_agente, ENT_QUOTES)] = htmlspecialchars($row->no_agente, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaNombreProducto(){
        $filtro = "";
        $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean(1);
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_detalle_producto, ENT_QUOTES)] = htmlspecialchars($row->no_producto, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaNombreProducto_tejeduria(){
        $filtro = "";
        $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean(3);
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_detalle_producto, ENT_QUOTES)] = htmlspecialchars($row->no_producto, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaNombreProducto_staClara(){
        $filtro = "";
        $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean(1);
        $sql = "SELECT detalle_producto.id_detalle_producto,detalle_producto.no_producto FROM producto
                INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
                WHERE detalle_producto.id_detalle_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {  
            foreach($query->result() as $row)
                $arrDatos[htmlspecialchars($row->id_detalle_producto, ENT_QUOTES)] = htmlspecialchars($row->no_producto, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function listaNombreProducto_hilos(){
        $filtro = "";
        $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean(2);
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
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(1);
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


    public function listaRegistrosFiltroPdf_tejeduria(){
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
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(3);
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

    public function listaRegistrosFiltroPdf_hilos(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('fecharegistro')){
            $filtro .= " AND DATE(ingreso_producto.fecha) ='".$this->security->xss_clean($this->input->post('fecharegistro'))."'"; 
        }
        if($this->input->post('proveedor')){
            $filtro .= " AND proveedor.id_proveedor =".(int)$this->security->xss_clean($this->input->post('proveedor')); 
        }
        if($this->input->post('moneda')){
            $filtro .= " AND ingreso_producto.id_moneda =".(int)$this->security->xss_clean($this->input->post('moneda')); 
        }
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(2);
        /*if($this->input->post('num_factura')){
            $filtro .= " AND ingreso_producto.nro_comprobante =".(int)$this->security->xss_clean($this->input->post('num_factura')); 
        }*/
        /*if($this->input->post('nombre')){
            $filtro = " AND (detalle_producto.no_producto ILIKE '%".$this->security->xss_clean($this->input->post('nombre'))."%')";   
        }*/
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante, comprobante.no_comprobante,
        ingreso_producto.nro_comprobante,ingreso_producto.fecha,proveedor.razon_social, moneda.no_moneda,
        ingreso_producto.id_almacen, proveedor.id_proveedor, ingreso_producto.total,ingreso_producto.gastos,
        (simbolo_mon) AS nombresimbolo 
        /*(no_moneda ||' : '|| simbolo_mon) AS nombresimbolo*/
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        WHERE ingreso_producto.id_comprobante = '2' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
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
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(2);
        $sql = "SELECT ingreso_producto.id_ingreso_producto,ingreso_producto.id_comprobante, comprobante.no_comprobante,
        ingreso_producto.nro_comprobante,ingreso_producto.fecha,proveedor.razon_social, moneda.no_moneda,
        ingreso_producto.id_almacen, proveedor.id_proveedor, ingreso_producto.total,
        (no_moneda ||' : '|| simbolo_mon) AS nombresimbolo 
        FROM ingreso_producto
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

    public function listaRegistrosFiltroPdf_otros_tejeduria(){
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
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(3);
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

    public function listaRegistrosFiltroPdf_otros_staClara(){
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
        if($this->input->post('fechainicial') AND $this->input->post('fechafinal')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(1);
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
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(2);
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
        WHERE ingreso_producto.id_comprobante <> '2' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listaRegistros_productoFiltroPdf_otros_tejeduria(){
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
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(3);
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

    public function listaRegistros_productoFiltroPdf_otros_staClara(){
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
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(1);
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

    public function listaRegistros_productoFiltroPdf(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nomproducto')){
            $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$this->security->xss_clean($this->input->post('nomproducto')); 
        }
        if($this->input->post('fechainicial_2') AND $this->input->post('fechafinal_2')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_2'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_2'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(1);
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

    public function listaRegistros_productoFiltroPdf_tejeduria(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nomproducto')){
            $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$this->security->xss_clean($this->input->post('nomproducto')); 
        }
        if($this->input->post('fechainicial_2') AND $this->input->post('fechafinal_2')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_2'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_2'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(3);
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

    public function listaRegistros_productoFiltroPdf_hilos(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('nomproducto')){
            $filtro .= " AND detalle_producto.id_detalle_producto =".(int)$this->security->xss_clean($this->input->post('nomproducto')); 
        }
        if($this->input->post('fechainicial_2') AND $this->input->post('fechafinal_2')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_2'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_2'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(2);
        $sql = "SELECT ingreso_producto.id_ingreso_producto,comprobante.no_comprobante, ingreso_producto.nro_comprobante,
        proveedor.razon_social,ingreso_producto.fecha,detalle_producto.no_producto, moneda.no_moneda,
        detalle_ingreso_producto.precio, detalle_ingreso_producto.unidades,
        (simbolo_mon) AS nombresimbolo
        /*(no_moneda ||' : '|| simbolo_mon) AS nombresimbolo*/
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN comprobante ON ingreso_producto.id_comprobante = comprobante.id_comprobante
        INNER JOIN detalle_ingreso_producto ON detalle_ingreso_producto.id_ingreso_producto = ingreso_producto.id_ingreso_producto
        INNER JOIN detalle_producto ON detalle_ingreso_producto.id_detalle_producto = detalle_producto.id_detalle_producto
        WHERE ingreso_producto.id_comprobante = '2' AND ingreso_producto.id_ingreso_producto IS NOT NULL".$filtro;
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
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(1);
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

    public function listaRegistros_agenteFiltroPdf_tejeduria(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('agente')){
            $filtro .= " AND agente_aduana.id_agente =".(int)$this->security->xss_clean($this->input->post('agente')); 
        }
        if($this->input->post('fechainicial_3') AND $this->input->post('fechafinal_3')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_3'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_3'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(3);
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

    public function listaRegistros_agenteFiltroPdf_hilos(){
        //esta variable filtrará y concatenará los diferentes filtros
        $filtro = "";
        if($this->input->post('agente')){
            $filtro .= " AND agente_aduana.id_agente =".(int)$this->security->xss_clean($this->input->post('agente')); 
        }
        if($this->input->post('fechainicial_3') AND $this->input->post('fechafinal_3')){
            $filtro .= " AND DATE(ingreso_producto.fecha) BETWEEN'".$this->security->xss_clean($this->input->post('fechainicial_3'))."'AND'".$this->security->xss_clean($this->input->post('fechafinal_3'))."'"; 
        }
        $filtro .= " AND ingreso_producto.id_almacen =".(int)$this->security->xss_clean(2);
        $sql = "SELECT ingreso_producto.nro_comprobante,proveedor.razon_social,ingreso_producto.fecha, moneda.no_moneda,
        moneda.no_moneda,moneda.simbolo_mon,ingreso_producto.total,agente_aduana.no_agente,ingreso_producto.gastos,
        (simbolo_mon) AS nombresimbolo
        /*(no_moneda ||' : '|| simbolo_mon) AS nombresimbolo*/
        FROM ingreso_producto
        INNER JOIN proveedor ON ingreso_producto.id_proveedor = proveedor.id_proveedor
        INNER JOIN moneda ON ingreso_producto.id_moneda = moneda.id_moneda
        INNER JOIN agente_aduana ON ingreso_producto.id_agente = agente_aduana.id_agente
        WHERE ingreso_producto.id_comprobante = '2' AND ingreso_producto.nro_comprobante IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function listarArea(){
        $filtro = "";
        $filtro .= " AND area.id_almacen =".(int)$this->security->xss_clean(1);
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

    public function listarArea_tejeduria(){
        $filtro = "";
        $filtro .= " AND area.id_almacen =".(int)$this->security->xss_clean(3);
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

    public function listarArea_hilos(){
        $filtro = "";
        $filtro .= " AND area.id_almacen =".(int)$this->security->xss_clean(2);
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

    public function listarComprobantes_hilos(){
        $filtro = "";
            $filtro = " AND comprobante.id_almacen =".(int)$this->security->xss_clean(2); 
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

    public function listarComprobantes_tejeduria(){
        $filtro = "";
            $filtro = " AND comprobante.id_almacen =".(int)$this->security->xss_clean(3); 
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

    public function listarComprobantes_staClara(){
        $filtro = "";
            $filtro = " AND comprobante.id_almacen =".(int)$this->security->xss_clean(1); 
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
        if($this->session->userdata('almacen') != 5){
            $filtro .= " AND producto.id_almacen =".(int)$this->security->xss_clean($this->session->userdata('almacen')); 
        }
        if($this->input->post('categoria')){
            $filtro .= " AND categoria.id_categoria =".(int)$this->security->xss_clean($this->input->post('categoria')); 
        }
        $sql = "SELECT producto.id_pro,producto.id_producto,
        detalle_producto.no_producto,detalle_producto.stock,categoria.no_categoria,
        procedencia.no_procedencia,detalle_producto.precio_unitario,producto.observacion,
        producto.unidad_medida,producto.id_almacen,almacen.no_almacen FROM producto
        INNER JOIN detalle_producto ON producto.id_detalle_producto = detalle_producto.id_detalle_producto
        INNER JOIN procedencia ON producto.id_procedencia = procedencia.id_procedencia
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
        INNER JOIN almacen ON producto.id_almacen = almacen.id_almacen
        WHERE producto.id_producto IS NOT NULL".$filtro;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            return $query->result();
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











}