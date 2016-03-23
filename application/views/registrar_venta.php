<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/main.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jTPS.css" />
	<!-- JQuery UI -->
	<script src="<?php echo base_url();?>assets/js/jquery.min.js" type="text/javascript"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/smoothness/jquery-ui-1.9.2.custom.css" />
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.maskedinput.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.price_format.1.8.js"></script>	
	<!-- Steps Plugin -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/normalize.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.steps.css">
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/lib/modernizr-2.6.2.min.js"></script>
    <!--script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/lib/jquery-1.9.1.min.js"></script-->
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.steps.js"></script>
    <!-- JQuery Validate -->
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-validate.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jTPS.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/validador.js"></script>	
	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.cookie.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo base_url();?>assets/js/ttip.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/ttip.css" />	

	<script type="text/javascript">
		$(function() {		
			$("#optionsuser").click(function() { 
				$('nav').fadeToggle("fast");
		    }); 		    
		});
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
	<header>
		<div id="logo"><a href="<?php echo base_url();?>vendedor"><img src="<?php echo base_url();?>assets/img/logo_sistema_interior.png" height="45" title="VisaNet Peru"></a></div>
		<div id="userlogin">
			<img src="<?php echo base_url();?>assets/img/user.jpg" width="45px" height="45px" title="Usuario" class="image">
			<div class="username">
				<span><?php echo $this->session->userdata('nombre') ." ". $this->session->userdata('apaterno') ?></span> <img src="<?php echo base_url();?>assets/img/arrow-down.png" width="20px" height="20px" id="optionsuser">
				<nav>
					<a href="#">Cambiar Contraseña</a>
					<a href="<?php echo base_url();?>vendedor/logout">Cerrar Sesión</a>
				</nav>
			</div>
		</div>

<?php
	//INFORMACION BÁSICA
	$nombres = array('name'=>'nombres','id'=>'nombres','maxlength'=> '20','minlength'=>'2');//este es un input , 'class'=>'required'
	$apellidos = array('name'=>'apellidos','id'=>'apellidos','maxlength'=>'20');//este es un input
	$email = array('name'=>'email','id'=>'email','maxlength'=>'50','minlength'=>'1');//este es un input
	$dni = array('name'=>'dni','id'=>'dni','maxlength'=> '8','minlength'=>'8');//este es un input
	$ruc = array('name'=>'ruc','id'=>'ruc','maxlength'=> '10','minlength'=>'10');//este es un input
	//$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','readonly'=> 'readonly');
	//UBICACION
	$direccion = array('name'=>'direccion','id'=>'direccion','maxlength'=>'100','minlength'=>'1','style'=>'width:350px;');//este es un combo
	$referencia=array('name'=>'referencia','id'=>'referencia','maxlength'=> '150','minlength'=>'1','style'=>'width:584px;');//este es un input
	$telefono1 = array('name'=>'telefono1','id'=>'telefono1','maxlength'=> '11','minlength'=>'7');//este es un input
	$telefono2 = array('name'=>'telefono2','id'=>'telefono2','maxlength'=> '11','minlength'=>'7');//este es un input
	$contacto = array('name'=>'contacto','id'=>'contacto','maxlength'=> '100','minlength'=>'1');//este es un input	
	//FORMAS DE PAGO
	$mediopago = array('name'=>'mediopago','id'=>'mediopago');

	$numero = array('name'=>'numero','id'=>'numero','maxlength'=>'10');//este es un input
	$interior = array('name'=>'interior','id'=>'interior','maxlength'=> '11','minlength'=>'1');//este es un INPUT	
	$kilometro=array('name'=>'kilometro','id'=>'kilometro','maxlength'=> '8','minlength'=>'1');//este es un input
	$manzana=array('name'=>'manzana','id'=>'manzana','maxlength'=> '2','minlength'=> '1');//este es un input
	$lote=array('name'=>'lote','id'=>'lote','maxlength'=> '2','minlength'=> '1');//este es un input
	
	$nombrehabitacionurbana=array('name'=>'nombrehabitacionurbana','id'=>'nombrehabitacionurbana','maxlength'=> '100','minlength'=>'1');//este es un input
	$nombreconglomerado=array('name'=>'nombreconglomerado','id'=>'nombreconglomerado','maxlength'=> '100','minlength'=> '1');//este es un combo		
	//INFORMACION DE CONTACTO
	
	//INFORMACION BANCARIA
	//$banco=array('1'=>'valor1','2'=>'valor2');//este es un combo
	$ncuenta=array('name'=>'ncuenta','id'=>'ncuenta','maxlength'=> '25','minlength'=>'25', 'size'=>'25');
?>

<script type="text/javascript">
		$(function() {    
			//$("#form-2").validate();
			$("#wizard").steps({
	            headerTag: "h2",
	            bodyTag: "section",
	            transitionEffect: "slideLeft",
	            onStepChanging: function (event, currentIndex, newIndex)
	            {
	                $("#form-2").validate().settings.ignore = ":disabled,:hidden";
	                return $("#form-2").valid();
	            },
	            onFinishing: function (event, currentIndex)
	            {
	                $("#form-2").validate().settings.ignore = ":disabled";
	                return $("#form-2").valid();
	            },
	            onFinished: function (event, currentIndex)
	            {
	                //$("#form-2").submit();
	                var dataString = $("#form-2").serialize();
	                $('.actions.clearfix').css("display","none");
	                $.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>vendedor/nuevoprosp",
						data: dataString,
						success: function(msg){
							if(msg == '0'){
								alert('No se registro al prospecto :( ' +dataString );
									$('#msgRegistro').css('display','none');
							}else{
								$('#msgRegistro').css('display','none');
								alert('Prospecto registrado satisfactoriamente.');
							   	$("#idRegistro").html(pad(msg.toString(), 7));
							   	$("#rucRegistro").html($("#ruc").val());
								$("#noComeRegistro").html($("#nombrecomercial").val());
								$("#resultadoRegistro").css("display","inline");
								$('.actions.clearfix').css("display","none");
								//setTimeout('window.location.href="vendedor/"', 800);
							}
						}
					});
	            }

	        });
				$("#ruc").mask('9999999999');
				$("#dni").mask('99999999');             
				$("#interior").validCampoFranz('0123456789');
				$("#kilometro").validCampoFranz('0123456789.');
				$("#ncuenta").validCampoFranz('0123456789-');	
				$("#numero").validCampoFranz('0123456789-');	
				$("#telefono1").validCampoFranz('0123456789-');	
				$("#telefono2").validCampoFranz('0123456789-');	
				$("#idprospecto").validCampoFranz('0123456789');									
				$("#departamentos").change(function() {
					$("#departamentos option:selected").each(function() {
					        departamentos = $('#departamentos').val();
					        $.post("<?php echo base_url(); ?>usuario/traeProvincias", {
					            departamentos : departamentos , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
					        }, function(data) {
					            $("#provincias").html(data);
					            $("#distrito").html('<option value="0">Seleccione una Provincia</option>');
					        });
					    });
				});
				$("#provincias").change(function() {
				    $("#provincias option:selected").each(function() {
				        provincias = $('#provincias').val();
				        $.post("<?php echo base_url(); ?>usuario/traeDistritos", {
				            provincias : provincias, <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
				        }, function(data) {
				            $("#distrito").html(data);
				        });
				    });
				});

				 //Agregamos SELECIONE a los combos
		    	$("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');

		    	$("#fecharegistro").datepicker({ 
				dateFormat: 'yy-mm-dd',showOn: "button",
				buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
				buttonImageOnly: true
			});
		});

		function pad(n, length){
		   n = n.toString();
		   while(n.length < length) n = "0" + n;
		   return n;
		}
		function finalizar(){
			window.location.href="<?php echo base_url();?>vendedor";
		}

		function regresar(){
			window.location.href="<?php echo base_url();?>vendedor";
		}

	</script>
	</header>
	<div id="contenedor">
		<div id="tituloCont">Registrar Compra</div>
		<div id="formFiltro">
			<?php echo form_open("vendedor/nuevoprosp", 'id="form-2" style="border:none"') ?>
				<div id="wizard">
	                <h2>Datos Personales</h2>
	                <section>
                        <table width="1058" border="0" cellspacing="2" cellpadding="2">
						  <tr>
						    <td width="272">Nombres: (*)</td>
						    <td colspan="2"><?php echo form_input($nombres);?></td>
						  </tr>
						  <tr>
						    <td> Apellidos: (*)</td>
						    <td colspan="2"><?php echo form_input($apellidos);?></td>
						  </tr>
						  <tr>
						    <td>E-mail: (*)</td>
						    <td colspan="2"><?php echo form_input($email);?></td>
						  </tr>
						  <tr>
						    <td>DNI (Personas y Organizaciones): (*)</td>
						    <td colspan="2"><?php echo form_input($dni);?></td>
					      </tr>
						  <tr>
						    <td>RUC (Sólo Organizaciones):</td>
						    <td colspan="2"><?php echo form_input($ruc);?></td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td height="116">&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td height="26">&nbsp;</td>
						    <td width="579">&nbsp;</td>
						    <td width="187" style="padding-left:115px;"><button type="button" onclick="regresar()">Cancelar</button></td>
						  </tr>
						</table>
	                </section>
	                <h2>Datos de la Entrega</h2>
	                <section>
	                    <table width="959" border="0" cellspacing="2" cellpadding="2">
						    <tr>
							    <td width="226">Departamento: (*)</td>
							    <td width="719" colspan="4"><?php echo form_dropdown('departamentos', $departamentos, '0',"id='departamentos' class='required' style='width:150px;'");?> </td>
						    </tr>
						    <tr>
							    <td>Provincia: (*)</td>
							    <td colspan="4"><select name="provincias" id="provincias" class='required' style="width:150px;"></select></td>
						    </tr>
						    <tr>
							    <td>Distrito: (*)</td>
							    <td colspan="4"><select name="distrito" id="distrito"  class='required' style="width:150px;"></select></td>
						    </tr>
						    <tr>
							    <td width="226">Dirección: (*)</td>
							    <td colspan="2"><?php echo form_input($direccion);?></td>
							</tr>
						    <tr>
							    <td width="226">Número Fijo de Contacto: (*)</td>
							    <td colspan="2"><?php echo form_input($telefono1);?></td>
						    </tr>
						    <tr>
							    <td width="226">Número Celular de Contacto: (*)</td>
							    <td colspan="2"><?php echo form_input($telefono2);?></td>
						    </tr>
						    <tr>
							    <td width="226">Persona de Contacto: (*)</td>
							    <td colspan="2"><?php echo form_input($contacto);?></td>
						    </tr>
							<tr>
							    <td width="226">Referencia de Dirección: </td>
							    <td colspan="2"><?php echo form_input($referencia);?></td>
						    </tr>
						    <tr>
							    <td>&nbsp;</td>
							    <td><button type="button" onclick="regresar()"><div align="left">Cancelar</div></button></td>
						    </tr>
						</table>
	                </section>
	                <h2>Formas de Pago</h2>
	                <section>
	                    <div id="bloq1">
		                    <table width="325" border="0" cellspacing="2" cellpadding="2">
							  <tr>
							  	<td width="57"><img src="<?php echo base_url();?>assets/img/efectivo.png"/></td>
							    <td width="213" height="40">Pago Contra Entrega en Efectivo</td>
							    <td width="35"><?php echo form_radio($mediopago);?></td>
						      </tr>
							  <tr>
							  	<td><img src="<?php echo base_url();?>assets/img/tarjeta.png"/></td>
							    <td height="40">Pago Contra Entrega con Tarjeta</td>
							    <td><?php echo form_radio($mediopago);?></td>
						      </tr>
							  <tr>
							  	<td><img src="<?php echo base_url();?>assets/img/tarjeta_credito.png"/></td>
							    <td height="40">Tarjetas de Crédito</td>
							    <td><?php echo form_radio($mediopago);?></td>
						      </tr>
							  <tr>
							  	<td><img src="<?php echo base_url();?>assets/img/paypal.png"/></td>
							    <td height="40">PAYPAL Tarjeta de Crédito</td>
							    <td><?php echo form_radio($mediopago);?></td>
						      </tr>
							  <tr>
							  	<td><img src="<?php echo base_url();?>assets/img/bcp.png"/></td>
							    <td height="40">Agencia BCP</td>
							    <td><?php echo form_radio($mediopago);?></td>
						      </tr>
							  <tr>
							  	<td><img src="<?php echo base_url();?>assets/img/bbva.png"/></td>
							    <td height="40">Agencia BBVA</td>
							    <td><?php echo form_radio($mediopago);?></td>
						      </tr>
							</table>
	                    </div>
	                    <div id="bloq2">
	                    	
	                    </div>
	                </section>
	                <h2>Resumen de Compra</h2>
	                <section>
	                    <table width="1058" border="0" cellspacing="2" cellpadding="2">
						  <tr>
						    <td width="272">Banco: </td>
						    <td colspan="2"><?php echo form_dropdown('banco',$banco);?></td>
						  </tr>
						  <tr>
						    <td>Tipo de Cuenta: </td>
						    <td colspan="2"><?php echo form_dropdown('tipodecuenta',$tipodecuenta);?></td>
						  </tr>
						  <tr>
						    <td>Nro. de cuenta:</td>
						    <td colspan="2"><?php echo form_input($ncuenta);?></td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td height="30">&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td height="116">&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td height="26">&nbsp;</td>
						    <td width="579">&nbsp;</td>
						    <td width="187" style="padding-left:115px;"><button type="button" onclick="regresar()">Cancelar</button></td>
						  </tr>
						</table>
	                </section>

	                <h2>Confirmación</h2>
	                <section>
	                	<div id="msgRegistro" class="tituloFiltro">
		                	<table width="1050" border="0" cellspacing="2" cellpadding="2">
						  <tr>
						    <td colspan="4">Haga click en "Registrar" para crear el nuevo prospecto...</td>
						  </tr>
						  <tr>
						    <td width="159">&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
						    <td height="27">&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td height="110">&nbsp;</td>
						    <td colspan="2">&nbsp;</td>
					      </tr>
						  <tr>
						    <td height="26">&nbsp;</td>
						    <td width="692">&nbsp;</td>
						    <td width="187" style="padding-left:115px;"><button type="button" onclick="regresar()">Cancelar</button></td>
						  </tr>
						</table>
		                	</table>
	                	</div>
	                	<div id="resultadoRegistro" style="display:none">
	                		<div class="tituloFiltro">Prospecto registrado con éxito.</div>
		                    <table width="750" border="0" cellspacing="2" cellpadding="2" style="margin-top:15px">
							  <tr>
							    <td width="130">ID de Prospecto:</td>
							    <td><strong><div class="tituloFiltro" id="idRegistro"></div></strong></td>
							  </tr>
							  <tr>
							    <td>Fecha de registro: </td>
							    <td><?php echo date('d-m-Y');?></td>
							  </tr>
							  <tr>
							    <td>RUC: </td>
							    <td id="rucRegistro"></td>
							  </tr>
							  <tr>
							    <td>Nombre Comercial: </td>
							    <td id="noComeRegistro"></td>
							  </tr>
							  <tr>
							    <td colspan="2" align="right"><button type="button" onclick="finalizar()">Finalizar</button></td>
							  </tr>
							</table>
	                	</div>
	                </section>
	            </div>
           	<?php echo form_close() ?>
		</div>
	</div>
</body>
</html>