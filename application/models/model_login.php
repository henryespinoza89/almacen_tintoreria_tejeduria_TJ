<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_login extends CI_Model {

	public function __construct(){
		parent::__construct();
        $this->load->library('email');
	}

    //============================== LOGIN DE USUARIO ==============================//
	public function login(){
        // grab user input
        $username = $this->security->xss_clean($this->input->post('usuario'));
        $contrasena = $this->security->xss_clean($this->input->post('contrasena'));
        //$this->db->select('tipo_usuario.no_tipo_usuario,usuario.id_tipo_usuario,usuario.id_usuario,usuario.no_usuario,usuario.ape_paterno');
        $this->db->select("usuario.no_usuario,usuario.ape_paterno,usuario.id_tipo_usuario,usuario.id_almacen, usuario.tx_usuario");
        $this->db->from("usuario");
        $this->db->where('tx_usuario', $username);
        $this->db->where('tx_contrasena', $contrasena);
        $this->db->where('fl_estado', 'TRUE');
        //$this->db->join('tipo_usuario','tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario');
        $query = $this->db->get();
        // Let's check if there are any results
        if ($query->num_rows() > 0)
        {     
            return $query->result();
        }
        return false;
    }
    //============================== FIN DEL LOGIN DE USUARIO ==============================//

    public function enviarCorreos(){
        $id_usuario = $this->security->xss_clean($this->input->post('txt_usuario'));
        $destinatario = $this->security->xss_clean($this->input->post('correo_mail'));

        $this->db->select("usuario.tx_usuario");
        $this->db->from("usuario");
        $this->db->where('tx_usuario', $id_usuario);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {

            $this->db->select('usuario.tx_contrasena');
            $this->db->where('tx_usuario', $id_usuario);
            $query = $this->db->get('usuario');
            foreach($query->result() as $row){
                $contrasena = $row->tx_contrasena;
            }

            $this->email->from('administrador@tejidosjorgito.com','Administrador');
            $this->email->to($destinatario);
            $this->email->subject('Envio de Contraseña');
            $this->email->message('La contraseña para el usuario: '.$id_usuario.
                                  " es: ".$contrasena);
            $this->email->send();
            return true;
        }
        return false;
    }

}