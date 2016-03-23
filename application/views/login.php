<!DOCTYPE html> <!-- Se utiliza para HTML5 -->

<?php 
	$usuario = array('class'=>'input', 'name'=>'usuario','id'=>'usuario','maxlength'=>'12','placeholder'=>' User','style'=>'width:200px');//este es un input
	$contrasena = array('class'=>'input', 'name'=>'contrasena','id'=>'contrasena','maxlength'=>'12','placeholder'=>' Password','style'=>'width:200px');//este es un input
?>

<html>
<head>
	<meta charset="utf-8">
	<title>Sistema de Almacén - Repuestos y Suministros</title>

	<!-- Titulo de las ventanas modales / Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Alegreya+SC:700,400' rel='stylesheet' type='text/css'>

	<!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-responsive.css">
	<!-- Font Awesome -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css">

	<link rel="shortcut icon" type="image/jpg" href="<?php echo base_url(); ?>assets/img/tienda_movistar.jpg">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/estilos_inicio.css">
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

	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.cookie.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.maskedinput.min.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/ttip.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/ttip.css" />

	<style type="text/css">
		.fa-cog{
			color: white;
		}
	</style>

<script type="text/javascript">

	$(function(){

		$("#respuesta_validacion_usuario").html('<strong>!Falta Completar el Campo USUARIO!</strong>').dialog({
	        modal: true,position: 'center',width: 440,height: 135, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
	        buttons: { Ok: function(){
	        	$(this).dialog('close');
	        }}
	    });

	    $("#respuesta_validacion_contrasena").html('<strong>!Falta Completar el Campo CONTRASEÑA!</strong>').dialog({
	        modal: true,position: 'center',width: 440,height: 135, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
	        buttons: { Ok: function(){
	        	$(this).dialog('close');
	        }}
	    });
	    /*
	    $("#respuesta_validacion_total").html('<strong>!Falta Completar el Campo TOTAL!</strong>').dialog({
	        modal: true,position: 'center',width: 440,height: 135, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
	        buttons: { Ok: function(){
	        	$(this).dialog('close');
	        }}
	    });
		*/
		$(".recall_password").click(function() { //activacion de ventana modal
			$("#mdlPasswordBack" ).dialog({  //declaracion de ventana modal
	          	modal: true,resizable: false,show: "blind",hide: "blind",position: 'center',width: 410,height: 253,draggable: false,closeOnEscape: false, //Aumenta el marco general
	          	buttons: {
	         	Enviar: function() {
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("disable");
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").attr("disabled", true).addClass("ui-state-disabled");
	              	//CONTROLO LAS VARIABLES
	              	var txt_usuario = $('#txt_usuario').val(); correo_mail = $('#correo_mail').val();
	              	if(txt_usuario == '' || correo_mail == '' ){
	                	$("#modalerror").html('<b>ERROR:</b> Faltan completar algunos campos del formulario, por favor verifique.').dialog({
	                  	modal: true,position: 'center',width: 450, height: 150,resizable: false,
	                  	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	                });
	              	}else{
	                    var dataString = 'txt_usuario='+txt_usuario+'&correo_mail='+correo_mail+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
	                    //alert(nombrepro); //Esta pasando el valor de procedencia como un integer
	                    $.ajax({
	                      	type: "POST",
	                      	url: "<?php echo base_url(); ?>login/enviarcorreos/",
	                      	data: dataString,
	                      	success: function(msg){
	                        if(msg == 1){
	                          	//alert('¡El Producto ha sido registrado con éxito!');
	                          	$("#finregistro").html('!El Correo se ha enviado con éxito!.').dialog({
	                            	modal: true,position: 'center',width: 300,height: 125,resizable: false, title: 'Fin de Registro',
	                            	buttons: { Ok: function(){
	                             	window.location.href="<?php echo base_url();?>login/";
	                            	}}
	                          	});
	                        }else{
	                          	$("#modalerror").empty().append(msg).dialog({
	                            	modal: true,position: 'center',width: 500,height: 125,resizable: false,
	                            	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	                          	});
	                          	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	                        }
	                      	}
	                    });
	              	}
	          	},
		        Cancelar: function(){
		            $("#mdlPasswordBack").dialog("close");
		        }
		        }
	      	});
		});

		$("#send_validar").click(function(){
	        var usuario = $("#usuario").val();
	        var contrasena = $('#contrasena').val();
	        $.ajax({
		        type: 'post',
		        url: "<?php echo base_url(); ?>login/validar/",
		        data: {
		          'usuario' : usuario,
		          'contrasena' : contrasena
		        },
		        success: function(response){
		        	if(response == 1){
		        		console.log('time');
		        		$("#respuesta_validacion_total").html('<strong>!Falta Completar los Campos del Formulario. Verifique!</strong>').dialog({
					        modal: true,position: 'center',width: 430,height: 125, resizable: false, title: 'Validación de Campos',hide: 'scale',show: 'scale',
					      	buttons: { Ok: function(){
					        	$(this).dialog('close');
					      	}}
					    });
		        	}else if(response == 'validacion_incorrecta'){
		        		console.log('time');
		        		$("#respuesta_validacion_total").html('<strong>!Usuario y/o Contraseña Incorrecta. Verifique!</strong>').dialog({
					        modal: true,position: 'center',width: 390,height: 125, resizable: false, title: 'Validación de Campos',hide: 'scale',show: 'scale',
					      	buttons: { Ok: function(){
					        	$(this).dialog('close');
					      	}}
					    });
		        	}else{
		        		result = response;
		            	window.location.href="<?php echo base_url();?>"+result;
		        	}
		        }
	        });
	    });

	});
</script>
</head>
<body>
	<table width="700" height="400" border="0" align="center" cellpadding="0" cellspacing="0" style="position: absolute;top: 50%;left: 50%;padding: 5px;margin-left: -400px;margin-top: -450px;">
	    <tbody>
		    <!--<div id="logo_login"><a href="<?php //echo base_url();?>comercial/#"><img src="<?php //echo base_url();?>assets/img/logo_tejidos_login.jpg" height="72" title="Sistema de Almacén"></a></div>-->
		    <tr>
		        <td width="500" height="270" align="left" valign="bottom">
	      			<!--<legend style="height: 17px;font-family: 'PT Serif', serif;font-weight: bold;font-size: 24px;"><span style="color:#F5A700";>SISTEMA DE ALMACÉN</span><span style="color:#002a80;"> DE REPUESTOS DE SUMINISTROS</span></legend>-->
	      			<img src="<?php echo base_url(); ?>assets/img/titulo_logo2.jpg">
	      		</td>
		    </tr>
		    <tr>
		        <td width="15">
			        <table id="tabla_principal" width="700" height="400" border="0" cellspacing="0" cellpadding="0">
				        <tbody>
				            <tr><td width="910" height="55"></td></tr>
				          	<tr>
					            <td height="50" align="left">
					                <table width="426" border="0" cellpadding="0" cellspacing="0" class="tabla_titulo">
						                <tbody>
						                	<tr>
							                    <td width="15" height="50"><div class="cont_pestana"><div class="bg_pestana"><!--<div style="padding-top:289px; padding-left:72px;"><img src="<?php echo base_url(); ?>assets/img/titulo_logo2.jpg"></div>--></div></div></td>
							                    <td width="411" align="left" class="titulo">Iniciar <span class="titulo_blanco">sesión</span></td>
						                	</tr>
						              	</tbody>
					              	</table>
					            </td>
				            </tr>
				            <tr>
					            <td align="center">
							        <?php echo form_open('/login/validar') ?>
							            <div id="login" style="min-height:200px;">
							                <!--<form action="javascript:void(0);" method="post">-->
								                <table border="0" cellpadding="0" cellspacing="0" width="378">
								                    <tbody>
									                    <tr>
									                        <td width="100" align="left" style="font-size: 14px;padding-bottom: 7px;">Usuario:</td>
									                        <td>
									                        	<div class="input-append">
										                        	<?php echo form_input($usuario); ?>
										                        	<span class="add-on"><i class="fa fa-user"></i></span>
										                        </div>
									                        </td>
									                    </tr>
									                    <tr>
									                        <td height="50" align="left" style="font-size: 14px;padding-bottom: 7px;">Contraseña:</td>
									                        <td width="250" align="left">
									                        	<div class="input-append">
										                        	<?php echo form_password($contrasena); ?>
										                        	<span class="add-on"><i class="fa fa-key"></i></span>
										                        </div>
									                        </td>
									                    </tr>
									                    <tr>
									                        <td height="45" colspan="2" style="padding-left:108px;">
									                        	<div  id="send_validar" style="width: 112px;">
									                        		<a type="submit" class="btn btn-primary">
										                        		<i class="fa fa-cog"></i>
										                        		Ingresar
										                        	</a>
									                        	</div>
									                        </td>
									                    </tr>
								                    </tbody>
								                </table>
							                <!--</form>-->
							                <div id="options_productos">
							                	<div  id="recall_password" class="recall_password" style="width: 250px;margin-left: 75px;font-size: 14px;">¿No puedes acceder a tu cuenta?</div>
							                </div>
							                <?php echo form_close() ?>
							        		<?php echo '<br>'.validation_errors(); if(!empty($respuesta)){ echo $respuesta;} ?>
							            </div>
							    </td>
				            </tr>
				        </tbody>
			        </table>
		        </td>
		    </tr>
		    <tr>
		    	<td height="20"></td>
		    </tr>
	    </tbody>
	</table>

<!---  Ventanas modales -->
<div id="mdlPasswordBack" style="display:none; margin-top: 10px;height: 115px;padding-bottom: 0px;margin-left: 15px;">
    <div id="contenedor" style="width:360px; height:100px;"> <!--Aumenta el marco interior-->
    <div id="tituloCont" style="font-family: 'Alegreya SC', serif;font-size: 24px;">Recuperar Contraseña</div>
    <div id="formFiltro" style="width:350px;">
    <?php
        $txt_usuario = array('name'=>'txt_usuario','id'=>'txt_usuario','maxlength'=>'10', 'style'=>'width:170px;height: 22px;', 'placeholder'=>' User');//este es un input
        $correo_mail = array('name'=>'correo_mail','id'=>'correo_mail','maxlength'=>'60', 'style'=>'width:170px;height: 22px;', 'placeholder'=>' E-mail');//este es un input
    ?>  
        <form method="post" id="back_password" style=" border-bottom:0px;padding-top: 9px;">
        <table>
          	<tr>
              	<td width="133" height="30" style="color: #898989;font-size: 12px;padding-bottom: 10px;">Usuario:</td>
              	<td width="212" height="30" align="left">
	              	<div class="input-append">
	                	<?php echo form_input($txt_usuario);?>
	                	<span class="add-on"><i class="fa fa-user"></i></span>
	                </div>
            	</td>
          	</tr>
          	<tr>
	            <td width="133" height="30" style="color: #898989;font-size: 12px;padding-bottom: 10px;">Correo Electrónico:</td>
	            <td width="212" height="30">
	              	<div class="input-append">
	                	<?php echo form_input($correo_mail);?>
	                	<span class="add-on"><i class="fa fa-envelope-o"></i></span>
	                </div>
	            </td>
          	</tr>
        </table>
        </form>
    </div>
    </div>
</div>
<div id="modalerror"></div>
<div id="finregistro"></div>
<div id="respuesta_validacion_total"></div>

<?php if(!empty($respuesta_validacion_usuario)){ ?>
    <div id="respuesta_validacion_usuario"></div>
<?php } ?>

<?php if(!empty($respuesta_validacion_contrasena)){ ?>
    <div id="respuesta_validacion_contrasena"></div>
<?php } ?>




</body>
</html>