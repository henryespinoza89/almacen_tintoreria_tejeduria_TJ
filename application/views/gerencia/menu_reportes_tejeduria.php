<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/jpg" href="<?php echo base_url(); ?>assets/img/tienda_movistar.jpg">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/estilos.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jTPS.css" />
	<!-- JQuery UI -->
	<script src="<?php echo base_url();?>assets/js/jquery.min.js" type="text/javascript"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/smoothness/jquery-ui-1.9.2.custom.css" />
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.js"></script>
	<!-- Steps Plugin -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/normalize.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.steps.css">
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/lib/modernizr-2.6.2.min.js"></script>
    <!--script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/lib/jquery-1.9.1.min.js"></script-->
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.steps.js"></script>
    <!-- JQuery Validate -->
    
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jshashtable-3.0.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-validate.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jTPS.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/validador.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/funciones.js"></script>	 <!--Valida Contraseña-->
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
			            var user = $('#user').val(); password = $('#password').val(); datacontrasena = $('#datacontrasena').val();
			            
			                //REGISTRO
			                var dataString = 'user='+user+'&password='+password+'&datacontrasena='+datacontrasena+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
		                    $.ajax({
		                      type: "POST",
		                      url: "<?php echo base_url(); ?>gerencia/UpdatePassword/",
		                      data: dataString,
		                      success: function(msg){
		                        if(msg == 1){
		                          $("#finregistro").html('!La Contraseña ha sido regristado con éxito!.').dialog({
		                            modal: true,position: 'center',width: 330,height: 125,resizable: false, title: 'Fin de Registro',
		                            buttons: { Ok: function(){
		                              window.location.href="<?php echo base_url();?>gerencia/redirect_store_tejeduria";
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
		function muestra_seguridad_clave(clave,formulario){
			seguridad=seguridad_clave(clave);
	        $("#valSeguridad").html("Contrase&ntilde;a fuerte: "+ seguridad + "%");
	        $("#securityPass").val(seguridad);
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
	</style>
</head>
<body>
<header style="width: 1300px; margin-top: 20px;">
	<div id="logo"><a href="<?php echo base_url();?>gerencia/index"><img src="<?php echo base_url();?>assets/img/logo_tejidos.jpg" height="72" title="Sistema de Almacén"></a></div>
	<div id="userlogin">
		<img src="<?php echo base_url();?>assets/img/user.jpg" width="45px" height="45px" title="Usuario" class="image">
		<div class="username">
			<span><?php echo $this->session->userdata('nombre') ." ". $this->session->userdata('apaterno') ?></span> <img src="<?php echo base_url();?>assets/img/arrow-down.png" width="20px" height="20px" id="optionsuser">
			<nav>
				<a href="#" class="cambiarcontrasena">Cambiar Contraseña</a>
				<a href="<?php echo base_url();?>comercial/logout">Cerrar Sesión</a>
			</nav>
		</div>
	</div>
	<header>
		<div id="menucomercial" style="margin-left: 30px;">
			<div id="cssmenu">
				<ul class="nav">
					<li><a href='<?php echo base_url();?>gerencia/redirect_store_tejeduria'><span>Reporte de Maquinarias</span></a></li>
					<li><a href='<?php echo base_url();?>gerencia/redirect_store_tejeduria_producto'><span>Reporte de Productos</span></a></li>
					<li><a href='<?php echo base_url();?>gerencia/redirect_store_tejeduria_proveedor'><span>Reporte de Proveedores</span></a></li>
					<li><a href='<?php echo base_url();?>gerencia/redirect_store_tejeduria_ingreso'><span>Reporte de Ingreso de Facturas</span></a></li>
					<li><a href='<?php echo base_url();?>gerencia/redirect_store_tejeduria_ingreso_otros'><span>Reporte de Ingreso Comprobantes</span></a></li>
					<li><a href='<?php echo base_url();?>gerencia/redirect_store_tejeduria_salida'><span>Reporte de Salida de Productos</span></a></li>
				</ul>
			</div>
		</div>
	</header>

<!--<footer><div><h2 align="center">Sistema de Almacén - Repuestos y Suministros</h2></div></footer>-->
<!---  Ventanas modales -->
    <div id="mdlUpPass" style="display:none">
        <div id="contenedor" style="width:320px; height:200px;"> <!--Aumenta el marco interior-->
        <div id="tituloCont">Actualizar Contraseña</div>
	        <div id="formFiltro" style="width:500px;">
	        <?php
		        $name_ln_user = array('name'=>'name_ln_user','id'=>'name_ln_user','maxlength'=>'10', 'style'=>'width:150px', 'readonly'=> 'readonly', 'value'=>$this->session->userdata('nombre')." ".$this->session->userdata('apaterno'));//este es un input
		        $user = array('name'=>'user','id'=>'user','maxlength'=>'60', 'style'=>'width:150px', 'value'=>$this->session->userdata('usuario'),'readonly'=> 'readonly');//este es un input
		        $password = array('name'=>'password','id'=>'password','maxlength'=>'100', 'style'=>'width:150px');//este es un input
		        $datacontrasena = array('name'=>'datacontrasena','id'=>'datacontrasena','maxlength'=>'12','minlength'=>'6','style'=>'width:140px');
		        $jsContrasena = 'onkeyup="muestra_seguridad_clave(this.value, this.form)"';
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
		                <td><?php echo form_password($datacontrasena, '',$jsContrasena); ?></td>
		            </tr>
		            <tr>
		                <td>&nbsp;</td>
		                <td style="float: left;">
		                    <table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
			                    <tr>
			                        <td id="valSeguridad">Escriba una contraseña...</td>
			                    </tr>
			                    <tr>
			                        <td><progress id="securityPass" value="0" max="100" style="width:150px;margin-left:2px"></progress></td>
			                    </tr>
		                    </table>
		                </td>
		            </tr>
	            </table>
	            </form>
	        </div>
        </div>
    </div>