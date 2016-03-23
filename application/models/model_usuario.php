<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_usuario extends CI_Model {

	public function __construct(){
		parent::__construct();
        $this->load->library('session');
	}

	public function getProvincias(){
        //Obtenemos la ID del Departamento
        $iddep = $this->security->xss_clean($this->input->post('departamentos'));
        //Realizamos la consulta a la Base de Datos
        $this->db->where('iddep', $iddep);
        $this->db->order_by('nombreprov','ASC');
        $query = $this->db->get('provincias');
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

    public function getDepartamentos(){
        $this->db->order_by('nombredepa','ASC');
        $query = $this->db->get('departamentos');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row) 
                $arrDatos[htmlspecialchars($row->iddep, ENT_QUOTES)] = htmlspecialchars($row->nombredepa, ENT_QUOTES); 
            $query->free_result(); 
            return $arrDatos;
        }
    }

    public function getDistritos(){
        //Obtenemos la ID de la provincia
        $idprov = $this->security->xss_clean($this->input->post('provincias'));
        //Realizamos la consulta a la Base de Datos
        $this->db->where('idprov', $idprov);
        $this->db->order_by('nombredist','ASC');
        $query = $this->db->get('distritos');
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }

}
?>