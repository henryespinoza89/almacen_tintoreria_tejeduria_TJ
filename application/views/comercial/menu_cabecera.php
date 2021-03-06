<title>Sistema de Almacén - Repuestos y Suministros</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		$(function(){
			
			var auxiliar = 0; // Hide

			$.ajax({
	        	type: 'POST',
	        	url: "<?php echo base_url(); ?>comercial/traerProductosStockMinimo/",
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

	      	$("#icon_notification").mouseover(function(){
	      		if(auxiliar == 0){
	      			$("#view_invoice").show();
	      			auxiliar = 1;
	      		}else if(auxiliar == 1){
	      			$("#view_invoice").hide();
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
			        // window.location.href="<?php echo base_url();?>comercial/gestionarea";
			        $(this).dialog('close');
		        }}
	    	});

			$(".cambiarcontrasena").click(function() {
				$("#mdlUpPass" ).dialog({
					modal: true,resizable: false,show: "blind",position: 'center',width: 370,height: 343,draggable: false,closeOnEscape: false, //Aumenta el marco general
			        buttons: {
				        Actualizar: function() {
				            var user = $('#user').val(); password = $('#password').val(); datacontrasena = $('#datacontrasena').val();
				            if (user == "" || password == "" || datacontrasena == ""){
				            	sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
				            }else{
				                var dataString = 'user='+user+'&password='+password+'&datacontrasena_actualizar='+datacontrasena+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
			                    $.ajax({
			                      	type: "POST",
			                      	url: "<?php echo base_url(); ?>administrador/UpdatePassword/",
			                      	data: dataString,
			                      	success: function(msg){
			                        if(msg == 1){
			                          	swal({ title: "!La Contraseña ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
			                          	$('#password').val("");
			                          	$('#datacontrasena').val("");
			                          	$("#mdlUpPass").dialog("close");
			                        }else{
			                          	sweetAlert("Su Contraseña Actual no Coincide. Verificar!", "", "error");
			                        }
			                      	}
			                    });
				            }
				        },
				        Cancelar: function(){
				        	$('#password').val("");
			                $('#datacontrasena_actualizar').val("");
				            $("#mdlUpPass").dialog("close");
				        }
			        }
				});
			});

			$("#optionsuser").click(function(){
				$('nav').fadeToggle("fast");
			});

			$("#notification").click(function(){
				window.location.href="<?php echo base_url();?>comercial/gestionproductostockminimo";
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

		function get_list_producto_minimo_view(e){
			e.preventDefault();
			window.location.href="<?php echo base_url();?>comercial/gestionproductostockminimo";
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

		body {
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
	<div class="notification" id="notification" style="float: left;margin-top: 38px;margin-left: 620px;">
		<i><span class="fa fa-bell" id="icon_notification"></span></i>
	</div>
	<div class="view_invoice" id="view_invoice"></div>
	<div id="userlogin">
		<img src="<?php echo base_url();?>assets/img/user.png" width="50px" height="50px" title="Usuario" class="image" style="border-radius: 50%;margin-left: 10px;margin-top: 5px;margin-right: 15px;">
		<div class="username" style="padding-top: 5px;position: absolute;margin-left: 75px;">
			<span><?php echo $this->session->userdata('nombre') ." ". $this->session->userdata('apaterno') ?></span> <img src="<?php echo base_url();?>assets/img/arrow-down.png" width="20px" height="20px" id="optionsuser" style="float: right;">
			<nav style="display: none; z-index: 99;position: relative;">
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
					<li style="width: 191px;" class='has-sub'><a href='<?php echo base_url();?>comercial/gestionproductos'><span>Gestión de Productos</span></a>
						<ul>
							<!--<li><a href='<?php //echo base_url();?>comercial/gestionreporteproducto'><span>Gestionar Reportes</span></a></li>-->
							<!--<li><a href=""><span>Actualizar</span></a></li>-->
							<!--<li style="width: 146px;"><a href='<?php // echo base_url();?>comercial/gestiontraslados'><span>Traslados</span></a></li>-->
							<li style="width: 191px;"><a href='<?php echo base_url();?>comercial/gestioncuadreinventario'><span style="padding-left: 28px;">Cuadre de Inventario</span></a></li>
							<li style="width: 191px;"><a href='<?php echo base_url();?>comercial/gestionordeningreso'><span style="padding-left: 28px;">Ordenes de Ingreso</span></a></li>
						</ul>
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
							<!--<li style="width: 191px;"><a href='<?php // echo base_url();?>comercial/gestioncierrealmacen'><span style="padding-left: 6px;">Cierre de Almacén</span></a></li>-->
							<li style="width: 191px;"><a href='<?php echo base_url();?>comercial/gestion_cierre_saldos_iniciales'><span style="padding-left: 12px;">Cierre de Almacén</span></a></li>
						</ul>
					</li>
					<li><a href='<?php echo base_url();?>comercial/gestionsalida'><span>Gestión Salida de Almacén</span></a></li>
					<li><a href='<?php echo base_url();?>comercial/gestiontipocambio'><span>Gestión Tipo de Cambio</span></a></li>
					<li><a href="" style="width: 140px;"><span>Gestión Kardex</span></a>
						<ul>
							<li><a href='<?php echo base_url();?>comercial/gestionreportkardexproducto'><span>Kardex por Producto</span></a>
							<!--<li><a href='<?php // echo base_url();?>comercial/gestionreportkardexgeneral'><span>Kardex General</span></a>-->
							<li><a href='<?php echo base_url();?>comercial/gestionreportsunat'><span>Reporte SUNAT</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestioninventario'><span>Inventario de Cierre</span></a>
						</ul>
					</li>
					<!--<li><a href='<?php echo base_url();?>comercial/gestioninterfaz'><span>Interfaz</span></a></li>-->
					<!--<li><a href='<?php //echo base_url();?>comercial/backup'><span>Backup de la BD</span></a></li>-->
					<li><a href="" style="width: 193px;"><span>Gestión Reportes</span></a>
						<ul>
							<li><a href='<?php echo base_url();?>comercial/gestionreportentrada' style="width: 193px;"><span>Reporte de Facturas</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestionreportsalida'><span>Reporte de Salidas</span></a>
							<li><a href='<?php echo base_url();?>comercial/gestioninventarioalmacen'><span>Inventario Valorizado</span></a>
							<li><a href='<?php echo base_url();?>comercial/reportConsumoProducto'><span>Consumos por producto</span></a>
							<li><a href='<?php echo base_url();?>comercial/reportRotacionInventario'><span>Rotación de inventario</span></a>
							<!--<li><a href='<?php // echo base_url();?>comercial/gestionreportconsumopormaquina'><span>Consumos por máquina</span></a>-->
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
        <div id="contenedor" style="width:320px; height:220px;"> <!--Aumenta el marco interior-->
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