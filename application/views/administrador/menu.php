<!DOCTYPE html>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/font_awesome_icon/fonts/style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="shortcut icon" type="image/jpg" href="<?php echo base_url(); ?>assets/img/tienda_movistar.jpg">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sweetalert.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jTPS.css" />
	<!-- JQuery UI -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/smoothness/jquery-ui-1.9.2.custom.css" />
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.js"></script>

	<!-- Steps Plugin -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/normalize.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.steps.css">
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/lib/modernizr-2.6.2.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.steps.js"></script>

	<!-- Steps Plugin -->
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jshashtable-3.0.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-validate.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jTPS.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/validador.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/funciones.js"></script>	 <!--Valida Contraseña-->
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/chart.js"></script>
	<!--
		Este archivo validador.js permite validar los caracteres de los campos, por ejemplo para que sólo sean númericos.
	-->
	<!--
		Cristalab: En términos simples Normalize.css es un archivo .css que pone en cero todas las etiquetas HTML con el fin de que el sitio web se vea
		igual en todos los navegadores. Si uno no tiene definida una etiqueta, automáticamente el navegador le pone un valor.
		Los navegadores tienen sus propios CSS por default, esta herramienta permite resetear estos CSS a cero manteniendo algunos defaults de los
		navegadores que pueden ser útiles.
	-->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.dataTables.css">
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.dataTables.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
	
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.cookie.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.maskedinput.min.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/ttip.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/ttip.css" />

	<title>Sistema de Almacén - Repuestos y Suministros</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		$(function(){

			$(".cambiarcontrasena").click(function() { //activacion de ventana modal
				$("#mdlUpPass" ).dialog({  //declaracion de ventana modal
					modal: true,resizable: false,show: "blind",position: 'center',width: 370,height: 323,draggable: false,closeOnEscape: false, //Aumenta el marco general
			        buttons: {
			        Actualizar: function() {
			            $(".ui-dialog-buttonpane button:contains('Registrar')").button("disable");
			            $(".ui-dialog-buttonpane button:contains('Registrar')").attr("disabled", true).addClass("ui-state-disabled");
			            //CONTROLO LAS VARIABLES
			            var user = $('#user').val(); password = $('#password').val(); datacontrasena_actualizar = $('#datacontrasena_actualizar').val();
			            
			                //REGISTRO
			                var dataString = 'user='+user+'&password='+password+'&datacontrasena_actualizar='+datacontrasena_actualizar+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
		                    $.ajax({
		                      type: "POST",
		                      url: "<?php echo base_url(); ?>administrador/UpdatePassword/",
		                      data: dataString,
		                      success: function(msg){
		                        if(msg == 1){
		                          $("#finregistro").html('!La Contraseña ha sido regristado con éxito!.').dialog({
		                            modal: true,position: 'center',width: 330,height: 125,resizable: false, title: 'Fin de Registro',
		                            buttons: { Ok: function(){
		                              window.location.href="<?php echo base_url();?>administrador/gestionusuarios_admin";
		                            }}
		                          });
		                        }else{
		                          $("#modalerror").empty().append(msg).dialog({
		                            modal: true,position: 'center',width: 500,height: 140,resizable: false,
		                            buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		                          });
		                          $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		                        }
		                      }
		                    });
			            
			        },
			        Cancelar: function(){
			             $("#mdlUpPass").dialog("close");
			        }
			        }
				});
			});

			$("#optionsuser").click(function(){
				$('nav').fadeToggle("fast");
			});
		});

		function seguridad(clave,formulario){
			seguridad_actualizar=seguridad_clave(clave);
	        $("#valSeguridad_act").html("Contrase&ntilde;a fuerte: "+ seguridad_actualizar + "%");
	        $("#securityPass_act").val(seguridad_actualizar);
		}

	</script>
	<style>
	#bloq1{
		width: 330px;
		float: left;
	}
	#bloq2{
		width: 330px;
		height: 274px;
		float: right;
		background: red;
		color: #FFF;
		margin-right: 260px;
	}
	body{
		zoom: 80% !important;
	}
	</style>
</head>
<body>
<header>
	<div id="logo"><a href="<?php echo base_url();?>administrador/gestionproductos_admin"><img src="<?php echo base_url();?>assets/img/logo_tejidos.jpg" height="72" title="Sistema de Almacén"></a></div>
	<div id="userlogin">
		<img src="<?php echo base_url();?>assets/img/user.jpg" width="45px" height="45px" title="Usuario" class="image">
		<div class="username">
			<span><?php echo $this->session->userdata('nombre') ." ". $this->session->userdata('apaterno') ?></span> <img src="<?php echo base_url();?>assets/img/arrow-down.png" width="20px" height="20px" id="optionsuser">
			<nav>
				<a class="cambiarcontrasena">Cambiar Contraseña</a>
				<a href="<?php echo base_url();?>comercial/logout">Cerrar Sesión</a>
			</nav>
		</div>
	</div>
	<header>
		<div id="menucomercial">
			<div id="cssmenu">
				<ul class="nav">
					<li><a href='<?php echo base_url();?>administrador/gestionmaquinas_admin'><span>Gestión de Maquinarias</span></a></li>
					<li><a href='<?php echo base_url();?>administrador/gestionproductos_admin'><span>Gestión de Productos</span></a></li>
					<li><a href='<?php echo base_url();?>administrador/gestionproveedores_admin'><span>Gestión de Proveedores</span></a></li>
					<li><a href='<?php echo base_url();?>administrador/gestionusuarios_admin'><span>Gestión de Usuarios</span></a></li>
					<li><a href='<?php echo base_url();?>administrador/gestionusuarios_admin'><span>Gestión de Interfaces</span></a>
						<ul>
							<li><a href='<?php echo base_url();?>administrador/gestion_ingreso_producto'><span>Tabla ingreso_producto</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_detalle_ingreso_producto'><span>Tabla detalle_ingreso_producto</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_tipo_cambio'><span>Tabla tipo_cambio</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_saldos_iniciales'><span>Tabla saldos_iniciales</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_salida_producto'><span>Tabla salida_producto</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_kardex_producto'><span>Tabla kardex_producto</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_detalle_producto'><span>Tabla detalle_producto</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_producto'><span>Tabla producto</span></a>
							<li><a href='<?php echo base_url();?>administrador/gestion_proveedor'><span>Tabla proveedor</span></a>
						</ul>
					</li>
					<!--<li><a href='<?php //echo base_url();?>administrador/gestioningreso'><span>Registro de Ingreso</span></a></li>
					<li><a href='<?php //echo base_url();?>administrador/gestionsalida'><span>Registro de Salida</span></a></li>-->
				</ul>
			</div>
		</div>
	</header>
<!--<footer><div><h2 align="center">Sistema de Almacén - Repuestos y Suministros</h2></div></footer>-->
<div id="mdlUpPass" style="display:none">
        <div id="contenedor" style="width:320px; height:200px;"> <!--Aumenta el marco interior-->
        <div id="tituloCont">Actualizar Contraseña</div>
	        <div id="formFiltro" style="width:500px;">
	        <?php
		        $name_ln_user = array('name'=>'name_ln_user','id'=>'name_ln_user','maxlength'=>'10', 'style'=>'width:150px', 'readonly'=> 'readonly', 'value'=>$this->session->userdata('nombre')." ".$this->session->userdata('apaterno'));//este es un input
		        $user = array('name'=>'user','id'=>'user','maxlength'=>'60', 'style'=>'width:150px', 'value'=>$this->session->userdata('usuario'),'readonly'=> 'readonly');//este es un input
		        $password = array('name'=>'password','id'=>'password','maxlength'=>'12', 'style'=>'width:150px');//este es un input
		        $datacontrasena_actualizar = array('name'=>'datacontrasena_actualizar','id'=>'datacontrasena_actualizar','maxlength'=>'12','minlength'=>'6','style'=>'width:140px');
		        $jsContrasena_actualizar = 'onkeyup="seguridad(this.value, this.form)"';
	        ?>  
	            <form method="post" id="nuevo_producto" style=" border-bottom:0px">
	            <table>
	                <tr>
	                    <td width="130" height="30">Nombre y Apellido:</td>
	                    <td width="263"><?php echo form_input($name_ln_user);?></td>
	                </tr>
	                <tr>
	                    <td width="130" height="30">Usuario:</td>
	                    <td width="263"><?php echo form_input($user);?></td>
	                </tr>
	                <tr>
	                    <td width="130" height="30">Contraseña Actual:</td>
	                    <td width="263"><?php echo form_password($password);?></td>
	                </tr>
	                <tr>
		                <td>Nueva Contraseña:</td>
		                <td><?php echo form_password($datacontrasena_actualizar, '',$jsContrasena_actualizar); ?></td>
		            </tr>
		            <tr>
		                <td>&nbsp;</td>
		                <td style="float: left;">
		                    <table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
			                    <tr>
			                        <td id="valSeguridad_act">Escriba una contraseña...</td>
			                    </tr>
			                    <tr>
			                        <td><progress id="securityPass_act" value="0" max="100" style="width:150px;margin-left:2px"></progress></td>
			                    </tr>
		                    </table>
		                </td>
		            </tr>
	            </table>
	            </form>
	        </div>
        </div>
    </div>