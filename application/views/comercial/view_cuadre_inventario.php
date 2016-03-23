<?php
	if ($this->input->post('cantidad')){
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10','value'=>$this->input->post('cantidad'), 'style'=>'width:70px;visibility: visible;height: 14px;', 'class'=>'required', 'onpaste'=>'return false');
	}else{
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10', 'style'=>'width:70px;visibility: visible;height: 14px;', 'class'=>'required', 'onpaste'=>'return false');
	}

	if ($this->input->post('nombre_producto')){
	    $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto','value'=>$this->input->post('nombre_producto'), 'style'=>'width:265px;font-family: verdana;visibility: visible;height: 14px;','placeholder'=>' :: Nombre del Producto ::');
	}else{
	    $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto', 'style'=>'width:265px;font-family: verdana;visibility: visible;height: 14px;','placeholder'=>' :: Nombre del Producto ::'); 
	}

	if ($this->input->post('stockactual')){
	    $stockactual = array('name'=>'stockactual','id'=>'stockactual','maxlength'=> '10', 'value' => $this->input->post('stockactual'), 'style'=>'width:100px;visibility: visible;height: 14px;','readonly'=> 'readonly');
	}else{
	    $stockactual = array('name'=>'stockactual','id'=>'stockactual','maxlength'=> '10', 'style'=>'width:100px;visibility: visible;height: 14px;','readonly'=> 'readonly');
	}

	if ($this->input->post('stockactual_general')){
	    $stockactual_general = array('name'=>'stockactual_general','id'=>'stockactual_general','maxlength'=> '10', 'value' => $this->input->post('stockactual_general'), 'style'=>'width:100px;visibility: visible;height: 14px;','readonly'=> 'readonly');
	}else{
	    $stockactual_general = array('name'=>'stockactual_general','id'=>'stockactual_general','maxlength'=> '10', 'style'=>'width:100px;visibility: visible;height: 14px;','readonly'=> 'readonly');
	}

	if ($this->input->post('nombre_producto_o_i')){
	    $nombre_producto_o_i = array('name'=>'nombre_producto_o_i','id'=>'nombre_producto_o_i','value'=>$this->input->post('nombre_producto_o_i'), 'style'=>'width:265px;font-family: verdana;visibility: visible;height: 14px;','placeholder'=>' :: Nombre del Producto ::');
	}else{
	    $nombre_producto_o_i = array('name'=>'nombre_producto_o_i','id'=>'nombre_producto_o_i', 'style'=>'width:265px;font-family: verdana;visibility: visible;height: 14px;','placeholder'=>' :: Nombre del Producto ::'); 
	}

	if ($this->input->post('cantidad_o_i')){
		$cantidad_o_i = array('name'=>'cantidad_o_i','id'=>'cantidad_o_i','maxlength'=>'10','value'=>$this->input->post('cantidad_o_i'), 'style'=>'width:70px;visibility: visible;height: 14px;', 'class'=>'required', 'onpaste'=>'return false');
	}else{
		$cantidad_o_i = array('name'=>'cantidad_o_i','id'=>'cantidad_o_i','maxlength'=>'10', 'style'=>'width:70px;visibility: visible;height: 14px;', 'class'=>'required', 'onpaste'=>'return false');
	}

	if ($this->input->post('stockactual_o_i')){
	    $stockactual_o_i = array('name'=>'stockactual_o_i','id'=>'stockactual_o_i','maxlength'=> '10', 'value' => $this->input->post('stockactual_o_i'), 'style'=>'width:100px;visibility: visible;height: 14px;','readonly'=> 'readonly');
	}else{
	    $stockactual_o_i = array('name'=>'stockactual_o_i','id'=>'stockactual_o_i','maxlength'=> '10', 'style'=>'width:100px;visibility: visible;height: 14px;','readonly'=> 'readonly');
	}
?>

<script type="text/javascript">

var formatNumber = {
	separador: ",", // separador para los miles
	sepDecimal: '.', // separador para los decimales
    formatear:function (num){
	    num =  Math.round(num*100)/100; // permite redondear el valor a dos decimales
	    num +='';
	    var splitStr = num.split('.');
	    var splitLeft = splitStr[0];
	    var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
	    var regx = /(\d+)(\d{3})/;
	    while (regx.test(splitLeft)) {
	        splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
	    }
	    return this.simbol + splitLeft  +splitRight;
	    //return Math.round((this.simbol + splitLeft  +splitRight)*100)/100;
	    //return parseFloat(this.simbol + splitLeft  +splitRight).toFixed(2);
    },
	    new:function(num, simbol){
	        this.simbol = simbol ||'';
	      	return this.formatear(num);
	    }
	}

$(function(){

	<?php 
		if ($this->input->post('area')){
			$selected_area =  (int)$this->input->post('area');
		}else{	$selected_area = "";
	?>
   			$("#area").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
	<?php 
		}	
	?>

	$("#nombre_producto").focus();

	$("#nombre_producto").autocomplete({
        source: function (request, respond) {

        	var id_area = $("#area").val();
        	if(id_area == ""){
        		$("#nombre_producto").val("");
				$("#modalerror").html('<strong>!Seleccionar el Área del Producto. Verificar!</strong>').dialog({
                	modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
                	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
              	});
			}else{
	        	$.post("<?php echo base_url('comercial/traer_producto_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term, a: id_area},
		        function (response) {
		            respond(response);
		        }, 'json');
			}
        }, select: function (event, ui) {
	        var id_area = $("#area").val();

	        var selectedObj = ui.item;
	        var nombre_producto = selectedObj.nombre_producto;

	        $("#nombre_producto").val(nombre_producto);
	        nombre_producto = $("#nombre_producto").val();

	        // Traer stock por area del producto
	        var ruta = $('#direccion_traer_stock').text();
	        $.ajax({
	          	type: 'get',
	          	url: ruta,
	          	data: {
	            	'nombre_producto' : nombre_producto,
	            	'id_area' : id_area
	          	},
	          	success: function(response){
	            	$("#stockactual").val(response);
	          	}
	        });

	        // Traer stock general del producto
	        $.ajax({
	          	type: 'get',
	          	url: "<?php echo base_url(); ?>comercial/traer_stock_general_cuadre/",
	          	data: {
	            	'nombre_producto' : nombre_producto
	          	},
	          	success: function(response){
	            	$("#stockactual_general").val(response);
	          	}
	        });

	        $("#cantidad").focus();
        }
    });

    $("#nombre_producto_o_i").autocomplete({
        source: function (request, respond) {
        	$.post("<?php echo base_url('comercial/traer_producto_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
	        function (response) {
	            respond(response);
	        }, 'json');
        }, select: function (event, ui) {
	        var selectedObj = ui.item;
	        var nombre_producto = selectedObj.nombre_producto;

	        $("#nombre_producto_o_i").val(nombre_producto);
	        nombre_producto = $("#nombre_producto_o_i").val();
	        var ruta = $('#direccion_traer_stock').text();
	        $.ajax({
	          	type: 'get',
	          	url: ruta,
	          	data: {
	            	'nombre_producto' : nombre_producto
	          	},
	          	success: function(response){
	            	$("#stockactual_o_i").val(response);
	          	}
	        });
	        $("#cantidad_o_i").focus();
        }
    });

    /*
    $("#orden_ingreso").click(function(){
    	var nombre_producto = $("#nombre_producto_o_i").val();
		var stockactual = $("#stockactual_o_i").val();
		var cantidad = $("#cantidad_o_i").val();
		if(nombre_producto == '' || cantidad == '' || area == ''){
			$("#modalerror").html('<strong>!Todos los Campos del Formulario son Obligatorios. Verificar!</strong>').dialog({
	            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
	          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	        });
		}else{
			var dataString = 'nombre_producto='+nombre_producto+'&stockactual='+stockactual+'&cantidad='+cantidad+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
	            type: "POST",
	            url: "<?php echo base_url(); ?>comercial/cuadrar_orden_ingreso/",
	          	data: dataString,
	          	success: function(response){
	            if(response == 1){
	              	$("#modalerror").empty().append('<span style="color:black"><b>!El Registro de la Órden de Ingreso se realizó con Éxito!</b></span>').dialog({
	                	modal: true,position: 'center',width: 500,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
	                	buttons: { Ok: function() {
	                		window.location.href="<?php echo base_url();?>comercial/gestioncuadreinventario";
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else if(response == "error_inesperado"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!Se produjo un Error al momento de realizar el Registro!</b><br><b>Verificar los datos del Formulario.</b></span>').dialog({
	                	modal: true,position: 'center',width: 490,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else if(response == "cantidad_negativa"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!No se pudo realizar el Registro!</b><br><b>Verificar la cantidad de Cuadre.</b></span>').dialog({
	                	modal: true,position: 'center',width: 490,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else if(response == "error_kardex"){
	            	$("#modalerror").empty().append('<span style="color:red"><b>!Se produjo un Error en la Actualización del Stock del Producto!</b></span>').dialog({
	                	modal: true,position: 'center',width: 480,height: 125,resizable: false,title: 'Error de Validación',hide: 'blind',show: 'blind',
	                	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	                }
	            }
	       	});
		}
    });
	*/

    $("#cuadre_almacen").click(function(){
		var nombre_producto = $("#nombre_producto").val();
		var stockactual = $("#stockactual").val();
		var cantidad = $("#cantidad").val();
		var area = $("#area").val();
		if(nombre_producto == '' || cantidad == '' || area == ''){
			$("#modalerror").html('<strong>!Todos los Campos del Formulario son Obligatorios. Verificar!</strong>').dialog({
	            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
	          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	        });
		}else{
			var dataString = 'nombre_producto='+nombre_producto+'&stockactual='+stockactual+'&cantidad='+cantidad+'&area='+area+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
	    	$.ajax({
	            type: "POST",
	            url: "<?php echo base_url(); ?>comercial/cuadrar_producto_area_almacen/",
	          	data: dataString,
	          	success: function(response){
		            if(response == 1){
		              	$("#modalerror").empty().append('<span style="color:black"><b>!El Cuadre del Producto en Almacén se realizó con Éxito!</b></span>').dialog({
		                	modal: true,position: 'center',width: 500,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {
		                		window.location.href="<?php echo base_url();?>comercial/gestioncuadreinventario";
		                	}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else if(response == "no_existe_salida_disponible"){
		              	$("#modalerror").empty().append('<span style="color:red"><b>!No existen Salidas disponibles para realizar el cuadre del producto!</b><br><b>Verificar Kardex del Producto.</b></span>').dialog({
		                	modal: true,position: 'center',width: 490,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {
		                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
		                	}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else if(response == "cantidad_erronea_salidas"){
		              	$("#modalerror").empty().append('<span style="color:red"><b>!No se pudo realizar el Registro Completo del Cuadre en Almacén!</b><br><b>Verificar Kardex del Producto.</b></span>').dialog({
		                	modal: true,position: 'center',width: 490,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {
		                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
		                	}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else if(response == "orden_ingreso"){
		              	$("#modalerror").empty().append('<span style="color:red"><b>!Generar Orden de Ingreso!</b><br><b>Verificar Kardex del Producto.</b></span>').dialog({
		                	modal: true,position: 'center',width: 490,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {
		                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
		                	}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else if(response == "error_inesperado"){
		              	$("#modalerror").empty().append('<span style="color:red"><b>!Se produjo un Error al momento de realizar el Registro!</b><br><b>Verificar los datos del Formulario.</b></span>').dialog({
		                	modal: true,position: 'center',width: 490,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {
		                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
		                	}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else if(response == "cantidad_negativa"){
		              	$("#modalerror").empty().append('<span style="color:red"><b>!No se pudo realizar el Registro!</b><br><b>Verificar la cantidad de Cuadre.</b></span>').dialog({
		                	modal: true,position: 'center',width: 490,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {
		                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
		                	}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else if(response == "error_kardex"){
		            	$("#modalerror").empty().append('<span style="color:red"><b>!Se produjo un Error en la Actualización del Stock del Producto!</b></span>').dialog({
		                	modal: true,position: 'center',width: 480,height: 125,resizable: false,title: 'Error de Validación',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else{
		            	$("#modalerror").empty().append('<span style="color:red"><b>!ERROR!</b></span>').dialog({
		                	modal: true,position: 'center',width: 480,height: 125,resizable: false,title: 'Error de Validación',hide: 'blind',show: 'blind',
		                	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		              	});
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }
		        }
	       	});
		}
	});

    $("#cantidad").validCampoFranz('0123456789.');

});

</script>

<div id="contenedor">
	<div id="tituloCont" style="margin-bottom: 0;">Cuadre de Inventario</div>
	<div id="formFiltro">
		<!--Contenedor-->
		<div id="datos_factura_importada" style="background: whitesmoke;width: 1370px;border-bottom: 1px solid #000;padding-left: 10px;margin-bottom: 15px;height: 280px;">
			<div id="container_tabs" style="margin-bottom: 10px;">
				<!--Pestaña 1 activa por defecto-->
			    <input id="tab-1" type="radio" name="tab-group" checked="checked" />
			    <!--<label for="tab-1">Opción 1 - Unidades disponibles</label>-->
			    <!--Pestaña 2 inactiva por defecto-->
			    <!--
			    <input id="tab-2" type="radio" name="tab-group" />
			    <label for="tab-2">Opción 2 - Orden de Ingreso</label>
			    -->
			    <!--Contenido a mostrar/ocultar-->
			    <div id="content">
			    	<!--Contenido de la Pestaña 1-->
			        <div id="content-1">
						<div id="data_general" style="opacity: inherit;padding: 0.1em;width: 400px;float: left;">
							<table width="518" border="0" cellspacing="0" cellpadding="0">
						        <tr>
						          	<td width="175" valign="middle" height="30">Área:</td>
						          	<?php
						          		$existe = count($listaarea);
						          		if($existe <= 0){ ?>
							            	<td width="330" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
							        <?php    
							            }
							            else
							            {
						          	?>
						          		<td width="330"><?php echo form_dropdown('area',$listaarea,$selected_area,"id='area' style='width:150px;'" );?></td>
						          	<?php }?>
						        </tr>
						    </table>
							<table width="520" border="0" cellspacing="0" cellpadding="0">			        
						        <tr>
					                <td width="145" valign="middle" height="30" style="padding-bottom: 4px;">Nombre del Producto:</td>
					                <td width="228" height="30" colspan="5"><?php echo form_input($nombre_producto);?></td>
					            </tr>
					            <tr>
									<td width="127" valign="middle" style="color:#005197;padding-bottom: 4px;" height="30">Stock Actual General:</td>
						          	<td width="228" height="30"><?php echo form_input($stockactual_general);?></td>
						        </tr>
						        <tr>
									<td width="127" valign="middle" style="color:#005197;padding-bottom: 4px;" height="30">Stock Actual por Área:</td>
						          	<td width="228" height="30"><?php echo form_input($stockactual);?></td>
						        </tr>	
								<tr>
									<td width="127" valign="middle" height="30" style="padding-bottom: 4px;">Stock Físico de Inventario:</td>
						          	<td width="228" height="30"><?php echo form_input($cantidad);?></td>
								</tr>						        
							</table>
						    <table width="614" border="0" cellspacing="0" cellpadding="0" style="margin-top: 3px;">			        
							    <tr>
							       	<td width="117" style="padding-top: 3px;" colspan="6"><input type="submit" id="cuadre_almacen" name="cuadre_almacen" value="Cuadrar Inventario" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; margin-left: 300px; width: 150px;visibility: visible;height: 20px;" /></td>
							    </tr>						        
							</table>
						</div>
					</div>
					<!--Contenido de la Pestaña 2 Finanzas -->
					<!--
			        <div id="content-2">
						<table width="520" border="0" cellspacing="0" cellpadding="0">			        
					        <tr>
				                <td width="145" valign="middle" height="30" style="padding-bottom: 4px;">Nombre del Producto:</td>
				                <td width="228" height="30" colspan="5"><?php // echo form_input($nombre_producto_o_i);?></td>
				            </tr>
					        <tr>
								<td width="127" valign="middle" style="color:#005197;padding-bottom: 4px;" height="30">Stock Actual del sistema:</td>
					          	<td width="228" height="30"><?php // echo form_input($stockactual_o_i);?></td>
					        </tr>	
							<tr>
								<td width="127" valign="middle" height="30" style="padding-bottom: 4px;">Stock Físico de Inventario:</td>
					          	<td width="228" height="30"><?php // echo form_input($cantidad_o_i);?></td>
							</tr>						        
						</table>
						<table width="614" border="0" cellspacing="0" cellpadding="0" style="margin-top: 3px;">			        
						    <tr>
						       	<td width="117" style="padding-top: 3px;" colspan="6"><input type="submit" id="orden_ingreso" name="orden_ingreso" value="Orden de Ingreso" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; margin-left: 300px; width: 150px;visibility: visible;height: 20px;" /></td>
						    </tr>						        
						</table>
			        </div>
			        -->
				</div>
			</div>
		</div>
	</div>
</div>
<div id="finregistro"></div>
<div id="modalerror"></div>

<div style="display:none">
    <div id="direccion_traer_stock"><?php echo site_url('comercial/traerStock_Autocompletado');?></div>
</div>

<?php if(!empty($respuesta_general)){ ?>
    <div id="error_general"></div>
<?php } ?>

<?php if(!empty($respuesta_compro)){ ?>
    <div id="error_compro"></div>
<?php } ?>

<?php if(!empty($respuesta_fe)){ ?>
    <div id="error_fe"></div>
<?php } ?>

<?php if(!empty($respuesta_moneda)){ ?>
    <div id="error_moneda"></div>
<?php } ?>

<?php if(!empty($respuesta_prov)){ ?>
    <div id="error_prov"></div>
<?php } ?>

<?php if(!empty($respuesta_agente)){ ?>
    <div id="error_agente"></div>
<?php } ?>

<?php if(!empty($respuesta_general_carrito)){ ?>
    <div id="error_general_carrito"></div>
<?php } ?>

<?php if(!empty($respuesta_carrito_prod)){ ?>
    <div id="error_carrito_prod"></div>
<?php } ?>

<?php if(!empty($respuesta_carrito_qty)){ ?>
    <div id="error_carrito_qty"></div>
<?php } ?>

<?php if(!empty($respuesta_carrito_pu)){ ?>
    <div id="error_carrito_pu"></div>
<?php } ?>

<?php if(!empty($mensaje_registro_correcto)){ ?>
    <div id="mensaje_registro_correcto"></div>
<?php } ?>