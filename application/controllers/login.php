<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Login extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('model_login');
			$this->load->library('email');
		}

		public function index()
		{
			$this->load->view('login');
		}

		public function validar()
		{
			$this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|xss_clean');
	    	$this->form_validation->set_rules('contrasena', 'Contraseña', 'trim|required|xss_clean');

	    	if($this->form_validation->run() == FALSE)
			{
				/*
				if($this->input->post('contrasena') == "" && $this->input->post('usuario') == ""){
					$data['respuesta_validacion_usuario'] = '<span><b>ERROR:</b></span>';
				}else if($this->input->post('contrasena') == ""){
					$data['respuesta_validacion_contrasena'] = '<span><b>ERROR:</b></span>';
				}else if($this->input->post('usuario') == ""){
					$data['respuesta_validacion_total'] = '<span><b>ERROR:</b></span>';
				}
		        $this->load->view('login', $data);
		        */
		        echo 1;
			}else{
				$carpeta = "";
		        //Validamos el login de usuario
		        $result = $this->model_login->login();
		        // Verificamos que existan resultados
		        if(!$result){
		            /* 
			            $data['respuesta'] = '<span style="color:red">ERROR: El usuario y/o contrasena no son los correctos.</span>';
			            $this->load->view('login', $data);
		            */
			        echo 'validacion_incorrecta';
		        }else{
		        	//Registramos la sesion del usuario
		            foreach ($result as $row)
					{
							$datasession = array(
								'nombre' => $row->no_usuario,
								'apaterno' => $row->ape_paterno,
								'tipo' => $row->id_tipo_usuario,
								'usuario' => $row->tx_usuario,
								'almacen' => $row->id_almacen
			 				);
							$this->session->set_userdata($datasession);
					}		
					if ($this->session->userdata('tipo') == 2){ $carpeta="comercial"; }//GESTOR COEMRCIAL
					if ($this->session->userdata('tipo') == 1){ $carpeta="administrador"; }//GESTOR ADMINISTRATIVA
					if ($this->session->userdata('tipo') == 3){ $carpeta="gerencia"; }//GESTOR GERENCIAL
					echo $carpeta;
		        }
			}
	    }

	    public function enviarcorreos(){
	    	$this->form_validation->set_rules('txt_usuario', 'Usuario', 'trim|required|xss_clean');
	    	$this->form_validation->set_rules('correo_mail', 'Correo Electrónico', 'trim|required|xss_clean');
	    	//Mensajes
			$this->form_validation->set_message('required','<b>ERROR:</b> Falta completar el campo: %s.');
			//Delimitadores de ERROR:
			$this->form_validation->set_error_delimiters('<span>', '</span><br>');
			if($this->form_validation->run() == FALSE)
			{
				echo validation_errors();
			}
			else
			{
				$result = $this->model_login->enviarCorreos();	       
		        // Verificamos que existan resultados
		        if(!$result){
		            //Sí no se encotnraron datos.
		            echo '<span style="color:red"><b>ERROR:</b> No se realizo el envio del correo.</span>';
		        }else{
		        	//Registramos la sesion del usuario
		        	echo '1';
	        	}
			}
	    }

	}
?>
