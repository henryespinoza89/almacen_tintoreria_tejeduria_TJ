<!DOCTYPE html>
<html lang='es'>
<head>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/font_awesome_icon/fonts/style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="shortcut icon" type="image/jpg" href="<?php echo base_url(); ?>assets/img/tienda_movistar.jpg">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sweetalert.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jTPS.css" />
	<!-- JQuery UI -->
	<script src="<?php echo base_url();?>assets/js/jquery.min.js" type="text/javascript"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/smoothness/jquery-ui-1.9.2.custom.css" />
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.js"></script>

	<!-- Steps Plugin -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/normalize.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.steps.css">
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/lib/modernizr-2.6.2.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.steps.js"></script>
    <!-- JQuery Validate -->
    
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jshashtable-3.0.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-validate.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jTPS.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/validador.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/funciones.js"></script>	 <!--Valida Contraseña-->
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
	<!-- JQuery Autocomplete -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.dataTables.css">
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.dataTables.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
	<!--
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css">
    <!--<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>-->
    
	<!--<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js'></script>-->
	<!--<script language="JavaScript" type="text/javascript" src="<?php //echo base_url();?>assets/js/funciones_2.js"></script> <!-- Valida Contraseña -->
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.cookie.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.maskedinput.min.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/ttip.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/ttip.css" />	

	<title>Sistema de Almacén - Repuestos y Suministros</title>
	<meta charset="utf-8">
	<script type="text/javascript">
	
	$(function(){
      	
      	var auxiliar = 0; // Hide

		$.ajax({
        	type: 'POST',
        	url: "<?php echo base_url(); ?>comercial/traerFacturasImportadas/",
        	success: function(response){
              	// Actions
              	if(response == 0){
              		$("#notification").css("display","none");
              	}else{
              		$("#notification").css("display","block");
              		$("#view_invoice").css("display","none");
              		$("#view_invoice").html(response);
              	}
        	}
      	});

      	$("#icon_notification").on('click',function(){
      		if(auxiliar == 0){
      			$("#view_invoice").hide();
      			auxiliar = 1;
      		}else if(auxiliar == 1){
      			$("#view_invoice").show();
      			auxiliar = 0;
      		}
      	});

      	$("#view_invoice").mouseleave(function(){
            $("#view_invoice").hide();
            auxiliar = 0;
        });
	
		$("#error_view_complete").html('!Falta completar el campo Contraseña Actual.!').dialog({
	        modal: true,position: 'center',width: 400,height: 145,resizable: false, title: 'Error/Campos Vacios',
	        buttons: { Ok: function(){
		        //window.location.href="<?php echo base_url();?>comercial/gestionarea";
		        $(this).dialog('close');
	        }}
    	});

		$(".cambiarcontrasena").click(function() { //activacion de ventana modal
			$("#mdlUpPass" ).dialog({  //declaracion de ventana modal
				modal: true,resizable: false,show: "blind",position: 'center',width: 370,height: 323,draggable: false,closeOnEscape: false, //Aumenta el marco general
		        buttons: {
			        Actualizar: function() {
			            $(".ui-dialog-buttonpane button:contains('Registrar')").button("disable");
			            $(".ui-dialog-buttonpane button:contains('Registrar')").attr("disabled", true).addClass("ui-state-disabled");
			            // CONTROLO LAS VARIABLES
			            var user = $('#user').val(); password = $('#password').val(); datacontrasena = $('#datacontrasena').val();
		                // REGISTRO
		                var dataString = 'user='+user+'&password='+password+'&datacontrasena='+datacontrasena+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
	                    $.ajax({
	                      	type: "POST",
	                      	url: "<?php echo base_url(); ?>comercial/UpdatePassword/",
	                      	data: dataString,
	                      	success: function(msg){
		                        if(msg == 1){
		                          	$("#finregistro").html('!La Contraseña ha sido regristado con éxito!.').dialog({
			                            modal: true,position: 'center',width: 330,height: 125,resizable: false, title: 'Fin de Registro',
			                            buttons: { Ok: function(){
			                              	window.location.href="<?php echo base_url();?>comercial/gestioningreso";
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

	function gestionar_factura_importada(e, id_row){
	    e.preventDefault();
	    window.location.href="<?php echo base_url();?>comercial/gestionfacturasmasivas";
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

		#menucomercial ul li a:hover {
			background: #8A888B;
			color: #fff;
			-moz-transition: .4s linear;
			-webkit-transition: .4s ease-out;
			transition: .4s linear;
		}

		body{
			zoom: 80% !important;
		}
	</style>
</head>
<body>
<header>
	<div id="logo"><a href="<?php echo base_url();?>comercial/gestionproductos"><img src="<?php echo base_url();?>assets/img/logo_tejidos.jpg" height="72" title="Sistema de Almacén"></a></div>
	<!--
	<div class="notification" id="notification" style="float: left;margin-top: 38px;margin-left: 620px;">
		<i><span class="fa fa-bell" id="icon_notification"></span></i>
	</div>
	<div class="view_invoice" id="view_invoice"></div>-->
	<div id="userlogin">
		<img src="<?php echo base_url();?>assets/img/user.png" width="50px" height="50px" title="Usuario" class="image" style="border-radius: 50%;margin-left: 10px;margin-top: 5px;margin-right: 15px;">
		<div class="username">
			<span><?php echo $this->session->userdata('nombre') ." ". $this->session->userdata('apaterno') ?></span> <img src="<?php echo base_url();?>assets/img/arrow-down.png" width="20px" height="20px" id="optionsuser" style="margin-left: 10px;">
			<nav style="display: none; position: absolute; z-index: 99;width: 150px;">
				<a class="cambiarcontrasena">Cambiar Contraseña</a>
				<a href="<?php echo base_url();?>comercial/logout">Cerrar Sesión</a>
			</nav>
		</div>
	</div>
	<header>
		<div id="menucomercial">
			<div id="cssmenu">
				<ul class="nav">
					<li><a href='<?php echo base_url();?>comercial/gestionmaquinas'><span>Gestión de Maquinarias</span></a></li>
					<li class='has-sub'><a href='<?php echo base_url();?>comercial/gestionproductos'><span>Gestión de Productos</span></a>
						<!-- <ul> -->
							<!--<li><a href='<?php //echo base_url();?>comercial/gestionreporteproducto'><span>Gestionar Reportes</span></a></li>-->
							<!--<li><a href=""><span>Actualizar</span></a></li>-->
							<!--<li style="width: 146px;"><a href='<?php // echo base_url();?>comercial/gestiontraslados'><span>Traslados</span></a></li>-->
						<!--	
							<li style="width: 146px;"><a href='<?php //echo base_url();?>comercial/gestioncuadreinventario'><span>Cuadre de Inventario</span></a></li>
						</ul>
						-->
					</li>
					<li><a href='<?php echo base_url();?>comercial/gestionproveedores'><span>Gestión de Proveedores</span></a>
						<!--
						<ul>
							<li><a href=""><span>Registrar Proveedores</span></a></li>
							<li><a href=""><span>Actualizar</span></a></li>
						</ul>
						-->
					</li>
					<!--<li><a href='<?php //echo base_url();?>comercial/gestionusuario'><span>Usuarios</span></a></li>-->
					<li style="width: 191px;">
						<a href='<?php echo base_url();?>comercial/gestioningreso'><span>Gestión Ingreso de Facturas</span></a>
						<ul>
							<li style="width: 191px;"><a href='<?php echo base_url();?>comercial/gestioncierrealmacen'><span style="padding-left: 6px;">Cierre de Almacén</span></a></li>
							<li style="width: 191px;"><a href='<?php echo base_url();?>comercial/gestion_cierre_saldos_iniciales'><span style="padding-left: 6px;">Cierre de Saldos Iniciales</span></a></li>
						</ul>
					</li>
					<li><a href='<?php echo base_url();?>comercial/gestionsalida'><span>Gestión Salida de Almacén</span></a></li>
					<li><a href='<?php echo base_url();?>comercial/gestiontipocambio'><span>Gestión Tipo de Cambio</span></a></li>
					<li><a href="" style="width: 140px;"><span>Gestión Kardex</span></a>
						<ul>
							<li><a href='<?php echo base_url();?>comercial/gestionreportkardexproducto'><span>Kardex por Producto</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestionreportkardexgeneral'><span>Kardex General</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestionreportsunat'><span>Reporte SUNAT</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestioninventario'><span>Inventario de Cierre</span></a>
						</ul>
					</li>
					<!--<li><a href='<?php echo base_url();?>comercial/gestioninterfaz'><span>Interfaz</span></a></li>-->
					<!--<li><a href='<?php //echo base_url();?>comercial/backup'><span>Backup de la BD</span></a></li>-->
					<li><a href="" style="width: 193px;"><span>Gestión Reportes</span></a>
						<ul>
							<li><a href='<?php echo base_url();?>comercial/gestionreportentrada' style="width: 150px;"><span>Reporte de Facturas</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestionreportsalida'><span>Reporte de Salidas</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestioninventarioalmacen'><span>Inventario Valorizado de Almacén</span></a>
							<!--<li><a href='<?php //echo base_url();?>comercial/gestionreportmensual'><span>Reporte Mensual</span></a>-->
						</ul>
					</li>
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

    <?php if(!empty($error_complete)){ ?>
    	<div id="error_view_complete">Hola</div>
	<?php } ?>