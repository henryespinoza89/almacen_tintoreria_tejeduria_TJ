<?php
	if ($this->input->post('cantidad')){
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10','value'=>$this->input->post('cantidad'), 'style'=>'width:100px;visibility: visible;height: 24px;', 'class'=>'required', 'onpaste'=>'return false');
	}else{
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10', 'style'=>'width:100px;visibility: visible;height: 24px;', 'class'=>'required', 'onpaste'=>'return false');
	}

	if ($this->input->post('nombre_producto')){
	    $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto','value'=>$this->input->post('nombre_producto'), 'style'=>'width:300px;font-family: verdana;visibility: visible;height: 24px;','placeholder'=>' :: Nombre del Producto ::');
	}else{
	    $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto', 'style'=>'width:300px;font-family: verdana;visibility: visible;height: 24px;','placeholder'=>' :: Nombre del Producto ::'); 
	}

	if ($this->input->post('stockactual_general')){
	    $stockactual_general = array('name'=>'stockactual_general','id'=>'stockactual_general','maxlength'=> '10', 'value' => $this->input->post('stockactual_general'), 'style'=>'width:100px;visibility: visible;height: 24px;','readonly'=> 'readonly');
	}else{
	    $stockactual_general = array('name'=>'stockactual_general','id'=>'stockactual_general','maxlength'=> '10', 'style'=>'width:100px;visibility: visible;height: 24px;','readonly'=> 'readonly');
	}
?>

<script type="text/javascript">

$(function(){

	$("#nombre_producto").focus();

	$("#nombre_producto").autocomplete({
        source: function (request, respond) {
        	$.post("<?php echo base_url('comercial/traer_producto_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
	        function (response) {
	            respond(response);
	        }, 'json');
        }, select: function (event, ui) {
	        var id_area = $("#area").val();

	        var selectedObj = ui.item;
	        var nombre_producto = selectedObj.nombre_producto;

	        $("#nombre_producto").val(nombre_producto);
	        nombre_producto = $("#nombre_producto").val();

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

    $("#cuadre_almacen").click(function(){
		var nombre_producto = $("#nombre_producto").val();
		var stockactual = $("#stockactual").val();
		var cantidad = $("#cantidad").val();
		if(nombre_producto == '' || cantidad == ''){
			$("#modalerror").html('<strong>!Todos los Campos del Formulario son Obligatorios. Verificar!</strong>').dialog({
	            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
	          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	        });
		}else{
			var dataString = 'nombre_producto='+nombre_producto+'&stockactual='+stockactual+'&cantidad='+cantidad+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
	    	$.ajax({
	            type: "POST",
	            url: "<?php echo base_url(); ?>comercial/cuadrar_producto_area_almacen/",
	          	data: dataString,
	          	success: function(response){
		            if(response == 1){
		              	swal({ title: "El Cuadre del Producto se realizó con Éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
		              	$('#nombre_producto').val('');
	                    $('#stockactual_general').val('');
	                    $('#cantidad').val('');
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
		<div id="datos_factura_importada" style="background: whitesmoke;border-bottom: 1px solid #000;height: 160px;padding-top: 25px;padding-left: 25px;color: #000;">
				<table width="520" border="0" cellspacing="0" cellpadding="0">			        
			        <tr>
		                <td width="145" valign="middle" height="30" style="padding-bottom: 4px;">Nombre del Producto:</td>
		                <td width="228" height="30" colspan="5"><?php echo form_input($nombre_producto);?></td>
		            </tr>
		            <tr>
						<td width="127" valign="middle" style="color:#005197;padding-bottom: 4px;" height="30">Stock Actual sistema:</td>
			          	<td width="228" height="30"><?php echo form_input($stockactual_general);?></td>
			        </tr>
					<tr>
						<td width="127" valign="middle" height="30" style="padding-bottom: 4px;">Stock Físico de Inventario:</td>
			          	<td width="228" height="30"><?php echo form_input($cantidad);?></td>
					</tr>						        
				</table>
			    <table width="614" border="0" cellspacing="0" cellpadding="0" style="margin-top: 3px;">			        
				    <tr>
				       	<td width="117" style="padding-top: 3px;" colspan="6"><input type="submit" id="cuadre_almacen" name="cuadre_almacen" value="CUADRAR INVENTARIO" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #FF5722; border-radius:6px; margin-left: 318px; width: 150px;visibility: visible;height: 20px;" /></td>
				    </tr>						        
				</table>
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