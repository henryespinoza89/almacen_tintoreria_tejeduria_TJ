<?php
	
	if ($this->input->post('solicitante')){
		$solicitante = array('name'=>'solicitante','id'=>'solicitante','value'=>$this->input->post('solicitante'), 'style'=>'width:142px', 'onkeypress'=>'onlytext()');
	}else{
		$solicitante = array('name'=>'solicitante','id'=>'solicitante', 'style'=>'width:142px','onkeypress'=>'onlytext()');
	}
	if ($this->input->post('stockactual')){
		$stockactual = array('name'=>'stockactual','id'=>'stockactual','value'=>$this->input->post('stockactual'), 'style'=>'width:70px','readonly'=> 'readonly');
	}else{
		$stockactual = array('name'=>'stockactual','id'=>'stockactual', 'style'=>'width:70px','readonly'=> 'readonly');
	}
	if ($this->input->post('encargado')){
		$encargado = array('name'=>'encargado','id'=>'encargado','value'=>$this->input->post('encargado'), 'style'=>'width:142px', 'class'=>'required','readonly'=> 'readonly');
	}else{
		$encargado = array('name'=>'encargado','id'=>'encargado', 'style'=>'width:142px', 'class'=>'required','readonly'=> 'readonly');
	}
	if ($this->input->post('cantidad')){
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10','value'=>$this->input->post('cantidad'), 'style'=>'width:70px', 'class'=>'required', 'onpaste'=>'return false');
	}else{
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10', 'style'=>'width:70px', 'class'=>'required', 'onpaste'=>'return false');
	}

	if ($this->input->post('unidades_devolucion')){
		$unidades_devolucion = array('name'=>'unidades_devolucion','id'=>'unidades_devolucion','maxlength'=>'10','value'=>$this->input->post('unidades_devolucion'), 'style'=>'width:70px');
	}else{
		$unidades_devolucion = array('name'=>'unidades_devolucion','id'=>'unidades_devolucion','maxlength'=>'10', 'style'=>'width:70px');
	}
	
	if ($this->input->post('fecharegistro')){
		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:142px','readonly'=> 'readonly', 'class'=>'required');
	}else{
		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:142px','readonly'=> 'readonly', 'class'=>'required');
	}

	if ($this->input->post('nombre_producto')){
	    $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto','value'=>$this->input->post('nombre_producto'), 'style'=>'width:285px;font-family: verdana;height: 24px;','placeholder'=>' :: Nombre del Producto ::');
	}else{
	    $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto', 'style'=>'width:285px;font-family: verdana;height: 24px;','placeholder'=>' :: Nombre del Producto ::'); 
	}

	if ($this->input->post('unidadmedida')){
	    $unidadmedida = array('name'=>'unidadmedida','id'=>'unidadmedida','maxlength'=> '10', 'value' => $this->input->post('unidadmedida'), 'style'=>'width:70px','readonly'=> 'readonly');
	}else{
	    $unidadmedida = array('name'=>'unidadmedida','id'=>'unidadmedida','maxlength'=> '10', 'style'=>'width:70px','readonly'=> 'readonly');
	}

	if ($this->input->post('observacion')){
		$observacion = array('name'=>'observacion','id'=>'observacion','value'=>$this->input->post('observacion'), 'style'=>'width: 242px;height: 60px;margin-left: 5px;');
	}else{
		$observacion = array('name'=>'observacion','id'=>'observacion', 'style'=>'width: 242px;height: 60px;margin-left: 5px;');
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

function onlytext(){
	if((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
		event.returnValue = false;
}

$(function() {

	$('#listarSalidaProductos').DataTable();

	$("#nombre_producto").focus();

	$("#submit_finalizar").on("click",function(){
		// Selecciono las variables para registro de salida
		// Datos de la maquina
		var id_maquina = $("#maquina").val();
		var id_parte_maquina = $("#parte_maquina").val();
		/* Solicitante y fecha */
		var id_area = $("#area").val();
		//var encargado = $("#encargado").val();
		var solicitante = $("#solicitante").val();
		var fecharegistro = $("#fecharegistro").val();
		/* Datos del producto */
		var nombre_producto = $("#nombre_producto").val();
		var cantidad = $("#cantidad").val();
		var observacion = $("#observacion").val();
		// Validación contra campos vacios valores nulos
		if(id_area == '' || fecharegistro == '' || nombre_producto == '' || cantidad == '' || id_maquina == ''){
	        $("#modalerror").html('<strong>!Falta Completar algunos Campos del Formulario. Verificar!</strong>').dialog({
	            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'slide',show: 'slide',
	          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	        });
	    }else{
	    	var dataString = 'id_area='+id_area+'&fecharegistro='+fecharegistro+'&solicitante='+solicitante+'&nombre_producto='+nombre_producto+'&cantidad='+cantidad+'&id_maquina='+id_maquina+'&id_parte_maquina='+id_parte_maquina+'&observacion='+observacion+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
	    	$.ajax({
	            type: "POST",
	            url: "<?php echo base_url(); ?>comercial/finalizar_salida_before_13/",
	          	data: dataString,
	          	success: function(response){
	            if(response == 1){
	            	/*
	              	$("#modalerror").empty().append('<span style="color:black"><b>!Salida Registrada con Éxito!</b></span>').dialog({
	                	modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'slide',show: 'slide',
	                	buttons: { Ok: function() {
	                		window.location.href="<?php echo base_url();?>comercial/gestionsalida";
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	              	*/
					$('#maquina').val('');
					$('#parte_maquina').val('');
					$('#observacion').val('');
	              	$('#area').val('');
					$('#solicitante').val('');
					$('#fecharegistro').val('');
					$('#nombre_producto').val('');
					$('#stockactual').val('');
					$('#unidadmedida').val('');
					$('#cantidad').val('');
	              	swal({ title: "Salida Registrada con Éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
	            }else if(response == "error_stock"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!No existe Stock Disponible!</b><br><b>Verificar la Cantidad Solicitada.</b></span>').dialog({
	                	modal: true,position: 'center',width: 350,height: 145,resizable: false,title: 'Validación',hide: 'slide',show: 'slide',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else if(response == "error_cierre"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!No se puede realizar el registro!</b><br><b>La Fecha seleccionada corresponde a un Periodo de Cierre Anterior</b></span>').dialog({
	                	modal: true,position: 'center',width: 500,height: 135,resizable: false,title: 'Validación',hide: 'slide',show: 'slide',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else if(response == "no_existe_stock_disponible"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!No se puede realizar el registro!</b><br><b>No Existe Stock disponible para la Fecha seleccionada</b><br><b>Verificar Kardex del Producto</b></span>').dialog({
	                	modal: true,position: 'center',width: 500,height: 165,resizable: false,title: 'Validación',hide: 'slide',show: 'slide',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else if(response == "no_existe_stock_disponible_actualizacion_negativo"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!No se puede realizar el registro!</b><br><b>La Salida genera un Stock Negativo</b><br><b>Analizar Kardex del Producto</b></span>').dialog({
	                	modal: true,position: 'center',width: 500,height: 165,resizable: false,title: 'Validación',hide: 'slide',show: 'slide',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else{
	            	console.log(response);
	            	$("#modalerror").empty().append('<span style="color:red"><b>!ERROR!</b></span>').dialog({
	                	modal: true,position: 'center',width: 480,height: 125,resizable: false,title: 'Error de Validación',hide: 'slide',show: 'slide',
	                	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	                }
	            }
	       	});
	    }
	});

	$("#submit_devolucion_producto").on("click",function(){
		var unidades_devolucion = $("#unidades_devolucion").val();
		var id_salida_producto = $("#id_salida_producto_hidden").val();
		var cantidad = $("#cantidad").val();
		if(unidades_devolucion > cantidad){
			sweetAlert("!La cantidad de devolución es mayor a la cantidad de salida. Verificar!", "", "error");
		}else{
			var dataString = 'id_salida_producto='+id_salida_producto+'&unidades_devolucion='+unidades_devolucion+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
	            type: "POST",
	            url: "<?php echo base_url(); ?>comercial/registrar_devolucion/",
	          	data: dataString,
	          	success: function(response){
		            if(response == 1){
		            	swal({ title: "El Devolución del Producto ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
		            	$('#area').val('');
						$('#maquina').val('');
						$('#parte_maquina').val('');
						$('#solicitante').val('');
						$('#fecharegistro').val('');
						$('#nombre_producto').val('');
						$('#stockactual').val('');
						$('#unidadmedida').val('');
						$('#cantidad').val('');
						$('#cantidad_devolucion').val('');
						$('#unidades_devolucion').val('');
		            	$("#cantidad_devolucion").css('display','none');
						$("#table_button_finalizar_salida").css('display','block');
		            }
		        }
	        });
		}
	});

	$("#submit_finalizar_cuadre").on("click",function(){
		var id_area = $("#area").val();
		var fecharegistro = $("#fecharegistro").val();
		/* Datos del producto */
		var nombre_producto = $("#nombre_producto").val();
		var cantidad = $("#cantidad").val();
		// Validación contra campos vacios valores nulos
		if(fecharegistro == '' || nombre_producto == '' || cantidad == ''){
	        $("#modalerror").html('<strong>!Falta Completar algunos Campos del Formulario. Verificar!</strong>').dialog({
	            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
	          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
	        });
	    }else{
	    	var dataString = 'fecharegistro='+fecharegistro+'&nombre_producto='+nombre_producto+'&cantidad='+cantidad+'&id_area='+id_area+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
	    	$.ajax({
	            type: "POST",
	            url: "<?php echo base_url(); ?>comercial/finalizar_salida_cuadre_contabilidad/",
	          	data: dataString,
	          	success: function(response){
	            if(response == 1){
	            	/*
	              	$("#modalerror").empty().append('<span style="color:black"><b>!Salida Registrada con Éxito!</b></span>').dialog({
	                	modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
	                	buttons: { Ok: function() {
	                		window.location.href="<?php echo base_url();?>comercial/gestionsalida";
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	              	*/
	              	swal({ title: "Salida Registrada con Éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
	            }else if(response == "error_stock"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!No existe Stock Disponible!</b><br><b>Verificar la Cantidad Solicitada.</b></span>').dialog({
	                	modal: true,position: 'center',width: 350,height: 145,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else if(response == "no_existe_stock_disponible"){
	              	$("#modalerror").empty().append('<span style="color:red"><b>!No se puede realizar el registro!</b><br><b>No Existe Stock disponible para la Fecha seleccionada</b><br><b>Verificar Kardex del Producto</b></span>').dialog({
	                	modal: true,position: 'center',width: 500,height: 165,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
	                	buttons: { Ok: function() {
	                		$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );
	                	}}
	              	});
	              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
	            }else{
	            	console.log(response);
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
	
	$("#nombre_producto").autocomplete({
        source: function (request, respond) {
        	$.post("<?php echo base_url('comercial/traer_producto_autocomplete_salida'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
	        function (response) {
	            respond(response);
	        }, 'json');
        }, select: function (event, ui) {
	        var selectedObj = ui.item;
	        $("#nombre_producto").val(selectedObj.nombre_producto);
	        nombre_producto = $("#nombre_producto").val();
	        var ruta = $('#direccion_traer_unidad_medida').text();
	        $.ajax({
	            type: 'get',
	            url: ruta,
	            data: {
	            	'nombre_producto' : nombre_producto
	            },
	            success: function(response){
	            	$("#unidadmedida").val(response);
	            }
	        });
	        var ruta2 = $('#direccion_traer_stock').text();
	        $.ajax({
	          	type: 'get',
	          	url: ruta2,
	          	data: {
	            	'nombre_producto' : nombre_producto
	          	},
	          	success: function(response){
	            	$("#stockactual").val(formatNumber.new(response));
	          	}
	        });
	        $("#cantidad").focus();
        }
    });

	$("#error_general").html('!Falta seleccionar el campo Máquina!<br>!Falta seleccionar el campo Marca!<br>!Falta seleccionar el campo Modelo!<br>!Falta seleccionar el campo Serie!<br>!Falta seleccionar el campo Área!<br>!Falta completar el campo Solicitante!<br>!Falta completar el campo Fecha de Registro!<br>!Falta seleccionar el campo Producto!<br>!Falta completar el campo Cantidad Solicitada!').dialog({
      modal: true,position: 'center',width: 400,height: 250,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    $("#respuesta_maquina").html('!Falta seleccionar el campo Máquina!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_marca").html('!Falta seleccionar el campo Marca!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_modelo").html('!Falta seleccionar el campo Modelo!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_serie").html('!Falta seleccionar el campo Serie!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_area").html('!Falta seleccionar el campo Área!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_solicitante").html('!Falta completar el campo Solicitante!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_fecha").html('!Falta completar el campo Fecha de Registro!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_prod").html('!Falta seleccionar el campo Producto!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});

	$("#respuesta_qty").html('!Falta completar el campo Cantidad!').dialog({
	  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
	  buttons: { Ok: function(){
	    $(this).dialog('close');
	  }}
	});
	
	//Validar si existe el tipo de cambio del día registrado en el sistema
  <?php if(isset($tipocambio) && $tipocambio == 1){ ?>
  //Registro del Tipo de Cambio
  $("#compra").mask("9.999");
  $("#venta").mask("9.999");
  $("#datacompra_dol").mask("9.999");
  $("#dataventa_dol").mask("9.999");
  $("#datacompra_eur").mask("9.999");
  $("#dataventa_eur").mask("9.999");
  $( "#newtipocambio" ).dialog({
    modal: true,
    position: 'center',
    draggable: false,
    resizable: false,
    closeOnEscape: false,
    width:750,
    height:'auto',
    buttons: {
      'Guardar': function() {
        $(".ui-dialog-buttonpane button:contains('Guardar')").button("disable");
        var base_url = '<?php echo base_url();?>';
        var compra_dol = $("#datacompra_dol").val();
        var venta_dol = $("#dataventa_dol").val();
        var compra_eur = $("#datacompra_eur").val();
        var venta_eur = $("#dataventa_eur").val();
        var dataString = 'compra_dol=' + compra_dol+ '&venta_dol=' + venta_dol+ '&compra_eur=' + compra_eur+ '&venta_eur=' + venta_eur;
        $.ajax({
          type: "POST",
          url: base_url + "comercial/guardarTipoCambio",
          data: dataString,
          success: function(msg){
            if(msg == 'ok'){
              $("#finregistro").html('!El Tipo de Cambio ha sido regristado con éxito!.').dialog({
                modal: true,position: 'center',width: 350,height: 125,resizable: false, title: 'Fin de Registro',
                buttons: { Ok: function(){
                  window.location.href="<?php echo base_url();?>comercial/gestionmaquinas";
                }}
              });
            }else{
              $("#retorno").empty().append(msg);
              $(".ui-dialog-buttonpane button:contains('Guardar')").button("enable");
            }
          }
        });
      }
    }
  });
  <?php } ?>

	$("#cantidad").validCampoFranz('0123456789.');
	$("#unidades_devolucion").validCampoFranz('0123456789.');

	<?php 
		if ($this->input->post('area')){
			$selected_area =  (int)$this->input->post('area');
		}else{	$selected_area = "";
	?>
   			$("#area").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
	<?php 
		}	
	?>

	<?php 
		if ($this->input->post('maquina')){
			$selected_maquina =  (int)$this->input->post('maquina');
		}else{	$selected_maquina = "";
	?>
   			 $("#maquina").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
	<?php 
		}	
	?>

	<?php 
		if ($this->input->post('parte_maquina')){
			$selected_parte_maquina =  (int)$this->input->post('parte_maquina');
		}else{	$selected_parte_maquina = "";
	?>
   			$("#parte_maquina").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
	<?php 
		}	
	?>
	
	<?php 
		if ($this->input->post('nomproducto')){
			$selected_prod =  (int)$this->input->post('nomproducto');
		}else{	$selected_prod = "";
	?>
   			 $("#nomproducto").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
	<?php 
		}	
	?>
	
	$("#nomproducto").change(function() {
		$("#nomproducto option:selected").each(function() {
	        nomproducto = $('#nomproducto').val();
	        $.post("<?php echo base_url(); ?>comercial/traerStock", {
	            nomproducto : nomproducto , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
	        }, function(data) {
	            $("#stockactual").val(data);
	        });
	    });
	});

	$("#maquina").change(function() {
    $("#maquina option:selected").each(function() {
        id_maquina = $('#maquina').val();
        $.post("<?php echo base_url(); ?>comercial/get_parte_maquina", {
            id_maquina : id_maquina , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
        }, function(data) {
            $("#parte_maquina").html(data);
        });
      });
    });

	$("#area").change(function() {
		$("#fecharegistro").addClass();
		/*
		$("#area option:selected").each(function() {
	        area = $('#area').val();
	        $.post("<?php echo base_url(); ?>comercial/traerEncargado", {
	            area : area , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
	        }, function(data) {
	            $("#encargado").val(data);
	        });
	    });
		*/		
	});

	$("#cancelar_devolucion").on("click",function(){
		$('#area').val('');
		$('#maquina').val('');
		$('#parte_maquina').val('');
		$('#solicitante').val('');
		$('#fecharegistro').val('');
		$('#nombre_producto').val('');
		$('#stockactual').val('');
		$('#unidadmedida').val('');
		$('#cantidad').val('');
		$('#cantidad_devolucion').val('');
		$('#unidades_devolucion').val('');

		$("#cantidad_devolucion").css('display','none');
		$("#table_button_finalizar_salida").css('display','block');
	});

	$("#fecharegistro").datepicker({
		dateFormat: 'yy-mm-dd',showOn: "button",
		buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
		buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true
	});
	$(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input

    // ELIMINAR REGISTRO
    $('a.eliminar_salida').bind('click', function () {
      var ruta = $('#direccionelim').text();
        var id = $(this).attr('id').replace('elim_', '');
        var parent = $(this).parent().parent();
        //var usuario = $('#us123').text();
        $("#dialog-confirm").data({
              'delid': id,
              'parent': parent,
              'ruta': ruta
              //'idusuario': usuario
        }).dialog('open');
        return false;
    });

    $("#dialog-confirm").dialog({
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      width: 400,
      height: "auto",
      zindex: 9998,
      modal: false,
      buttons: {
        'Eliminar': function () {
          var parent = $(this).data('parent');
              var id = $(this).data('delid');
              var ruta = $(this).data('ruta');
              //var idusuario = $(this).data('idusuario');
              $.ajax({
                   type: 'get',
                   url: ruta,
                    data: {
                      'eliminar' : id
                    }
              });
              $(this).dialog('close');
              setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionsalida"', 200);
        },
        'Cancelar': function () {
              $(this).dialog('close');
        }
      }
    });
    // FIN DE ELIMINAR
});	

function view_salidas(id_area){
    var urlMaq = '<?php echo base_url();?>comercial/mostrarDetalleSalidas/'+id_area;
    $("#mdlVerSalidas").load(urlMaq).dialog({
      	modal: true, position: 'center', width: 765, height: 'auto', draggable: false, resizable: false, closeOnEscape: false,
      	buttons: {
          	Imprimir: function(){
            	url = '<?php echo base_url(); ?>comercial/print_pdf_salidas_area/'+id_area;
            	$(location).attr('href',url);
          	},
          	Volver: function(){
            	$("#mdlVerSalidas").dialog("close");
          	}
      	}
    });
}

function fill_inputs(id_salida_producto){
	/* Llenar datos del pedido en los inputs del formulario */
	$.ajax({
        type: 'POST',
        url: "<?php echo base_url(); ?>comercial/obtener_datos_salida/",
        data:{
          	'id_salida_producto' : id_salida_producto
        },
        success: function(data){
        	var dataJson = JSON.parse(data);
            var id_area = dataJson.id_area;
            var id_maquina = dataJson.id_maquina;
            var id_parte_maquina = dataJson.id_parte_maquina;
            $("#solicitante").val(dataJson.solicitante);
            $("#nombre_producto").val(dataJson.no_producto);
            $("#stockactual").val(dataJson.stock);
            $("#unidadmedida").val(dataJson.nom_uni_med);
            $("#cantidad").val(dataJson.cantidad);
            $("#fecharegistro").val(dataJson.fecha);
            $("#id_salida_producto_hidden").val(id_salida_producto);
            $("#area option[value="+id_area+"]").attr("selected",true);
            $("#maquina option[value="+id_maquina+"]").attr("selected",true);
            $("#parte_maquina option[value="+id_parte_maquina+"]").attr("selected",true);
        }
    });
	$("#cantidad_devolucion").css('display','block');
	$("#table_button_finalizar_salida").css('display','none');
}

</script>

<div id="contenedor">
	<?php if($tipocambio == 1){?>
      <div id="newtipocambio" title="Registrar Tipo de Cambio" style="font-size:11px;min-height:47px;padding-left: 90px;width: 700px;height: auto;">
        <?php echo form_open('/comercial/guardarTipoCambio',array('name'=>'tipodecambio')); ?>
          <?php
            $datacompra_dol = array('name'=>'datacompra_dol','id'=>'datacompra_dol','maxlength'=>'5', 'size'=>'10');
            $dataventa_dol = array('name'=>'dataventa_dol','id'=>'dataventa_dol','maxlength'=> '5', 'size'=>'10');
            $datacompra_eur = array('name'=>'datacompra_eur','id'=>'datacompra_eur','maxlength'=>'5', 'size'=>'10');
            $dataventa_eur = array('name'=>'dataventa_eur','id'=>'dataventa_eur','maxlength'=> '5', 'size'=>'10');
          ?>
          <table width="650" border="0" cellspacing="2" cellpadding="2" align="rigth">
            <tr>
              <td width="75" height="30">Fecha Actual:</td>
              <td width="104" height="30"><b><?php echo date('d-m-Y'); ?></b></td>
              <td width="90" height="30">Tipo de Cambio:</td>
              <td width="347" height="30"><a href="http://www.sbs.gob.pe/app/stats/tc-cv.asp" id="tipo_cambio" target="_blank">Superintendencia de Banca, Seguros y AFP</a></td>
            </tr>
          </table>
          <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 15px;margin-bottom:5px;">
            <legend><strong>Tipo de Cambio en Dólares</strong></legend>
            <table width="220" border="0" cellspacing="2" cellpadding="2" align="center">
              <tr>
                <td height="30">Valor de Compra:</td>
                <td height="30"><?php echo form_input($datacompra_dol); ?></td>
              </tr>
              <tr>
                <td height="30">Valor de Venta:</td>
                <td height="30"><?php echo form_input($dataventa_dol); ?></td>
              </tr>
            </table>
          </fieldset>
          <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 15px;margin-bottom:5px;">
            <legend><strong>Tipo de Cambio en Euros</strong></legend>
            <table width="220" border="0" cellspacing="2" cellpadding="2" align="center">
              <tr>
                <td height="30">Valor de Compra:</td>
                <td height="30"><?php echo form_input($datacompra_eur); ?></td>
              </tr>
              <tr>
                <td height="30">Valor de Venta:</td>
                <td height="30"><?php echo form_input($dataventa_eur); ?></td>
              </tr>
            </table>
          </fieldset>
        <?php echo form_close() ?>
        <div id="retorno"></div>
      </div>
    <?php } ?>
	<div id="tituloCont" style="margin-bottom: 10px;">Registro de Salida de Productos</div>
	<div id="formFiltro">
		<div id="options" style="border-bottom: 1px solid #000; padding-bottom: 15px;margin-bottom: 0px;">
			<div class="newarea"><a href="<?php echo base_url(); ?>comercial/gestionarea/">Gestionar Área y Responsable</a></div>
			<!--<div class="newsalida"><a href="<?php // echo base_url(); ?>comercial/gestionconsultarSalidaRegistros/">Consultar Registro de Salida</a></div>-->
			<!--<div class="reportOut"><a href="<?php //echo base_url(); ?>comercial/gestionreportesalida/">Gestionar Reporte de Salidas</a></div>-->
		</div>
		<div id="datosalida">
			<input type="hidden" name="id_salida_producto_hidden" id="id_salida_producto_hidden" value="">
			<table style="float: left; margin-top: 5px; width: 380px; margin-left: 0px;">
				<tr>
					<td width="285" valign="middle" style="height: 33px;">Máquina:</td>
		          	<?php
		          		$existe = count($listamaquina);
		          		if($existe <= 0){ ?>
			            	<td width="330" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
			        <?php    
			            }
			            else
			            {
		          	?>
		          			<td width="370"><?php echo form_dropdown('maquina', $listamaquina,$selected_maquina,"id='maquina' class='required' style='width:170px;'");?></td>
		          	<?php }?>
				</tr>
				<tr>
					<td width="148" valign="middle">Parte de Máquina:</td>
					<td>
                  		<select name="tipo" id="parte_maquina" class='required' style='width:170px;'></select>
                	</td>
				</tr>
				<tr>
	                <td width="148" valign="middle" height="30" style="padding-bottom: 4px;">Observación:</td>
	                <td width="228" height="30" colspan="5" style="padding-top: 7px;padding-bottom: 5px;"><?php echo form_textarea($observacion);?></td>
	            </tr>
		    </table>
		    <table width="400" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px; float: left; margin-left: 10px;width: 320px;margin-right: 45px;">
		        <tr>
		          	<td width="370" valign="middle" style="height:30px;">Área:</td>
		          	<?php
		          		$existe = count($listaarea);
		          		if($existe <= 0){ ?>
			            	<td width="330" height="28"><b><?php echo 'Registrar Área';?></b></td>
			        <?php    
			            }
			            else
			            {
		          	?>
		          			<td width="330"><?php echo form_dropdown('area',$listaarea,$selected_area,"id='area' style='width:150px;margin-left: 0px;'" );?></td>
		          	<?php }?>
			    </tr>
				<tr style="height:30px;">
					<td width="127" valign="middle">Solicitante:</td>
			        <td width="128"><?php echo form_input($solicitante);?></td>
				</tr>
				<tr style="height:30px;">
					<td width="127" valign="middle">Fecha de Registro:</td>
			        <td width="128"><?php echo form_input($fecharegistro);?></td>
				</tr>
			</table>
			<table width="460" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px; float: left;">
		        <tr>
	                <td width="133" valign="middle" height="30" style="padding-bottom: 4px;">Producto:</td>
	                <td height="30" colspan="5"><?php echo form_input($nombre_producto);?></td>
	            </tr>
				<tr>
					<td width="133" valign="middle" style="color:#005197;">Stock Actual:</td>
			        <td width="109"><?php echo form_input($stockactual);?></td>
			        <td width="107" valign="middle" style="color:#005197;">Unidad Medida:</td>
			        <td width="111"><?php echo form_input($unidadmedida);?></td>
				</tr>
				<tr style="height:30px;" id="cantidad_solicitada">
					<td width="133" valign="middle">Cantidad Solicitada:</td>
			        <td width="109"><?php echo form_input($cantidad);?></td>
				</tr>
				<!---
				<tr style="height:30px;"> 
		        	<td colspan="5" style=" padding-top: 5px; padding-left: 277px;"><input name="submit" type="submit" id="submit_finalizar" value="Registrar Salida" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;" /></td>
		        </tr>
		        -->
		        <!---
		        <tr style="height:30px;"> 
		        	<td colspan="5" style=" padding-top: 5px; padding-left: 277px;"><input name="submit" type="submit" id="submit_finalizar_cuadre" value="Registrar Salida Cuadre" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;" /></td>
		        </tr>
		        -->
			</table>
			<table width="580" border="0" cellspacing="0" cellpadding="0" style="float: left;margin-left: 375px;">
				<tr style="height:30px;" id="cantidad_devolucion">
					<td width="131" valign="middle" colspan="2">Cantidad Devolución:</td>
			        <td width="109"><?php echo form_input($unidades_devolucion);?></td>
			        <td width="109"><input name="submit" type="submit" id="submit_devolucion_producto" value="Registrar Devolución" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;" /></td>
			        <td width="109" style="padding-left: 25px;;"><input name="submit" type="submit" id="cancelar_devolucion" value="Cancelar Devolución" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;" /></td>
				</tr>
			</table>
			<table width="460" border="0" cellspacing="0" cellpadding="0" style="float: left;" id="table_button_finalizar_salida">
				<tr style="height:30px;"> 
		        	<td colspan="5" style=" padding-top: 5px; padding-left: 640px;"><input name="submit" type="submit" id="submit_finalizar" value="Registrar Salida" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;" /></td>
		        </tr>
			</table>
		</div>
		
		<table style="float:left; width: 1380px; margin-bottom: 10px;">
        	<tr>
        		<td width="472" ><span style="color:red;"><?php if(!empty($error)){ echo $error;} ?></span></td>
        	</tr>
	    </table>
	</div>
	
	<?php 
      $existe = count($salidaproducto);
      if($existe <= 0){
        echo 'No existen Registros de Salida en el Sistema.';
      }
      else
      {
    ?>
    <table border="0" cellspacing="0" cellpadding="0" id="listarSalidaProductos" style="width:1360px;" class="table table-hover table-striped">
          <thead>
              <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
                <td sort="idprod" width="50" height="27">ITEM</td>
                <td sort="idproducto" width="110" height="27">MÁQUINA</td>
                <td sort="procprod" width="110">ÁREA</td>
                <td sort="procprod" width="150">SOLICITANTE</td>
                <td sort="procprod" width="80">FECHA</td>
                <td sort="procprod" width="340">PRODUCTO</td>
                <td sort="procprod" width="70">CANTIDAD</td>
                
                <td width="20" style="background-image: none;">&nbsp;</td>
                <!--
                <td width="20">&nbsp;</td>
                -->
              </tr>
          </thead>
          <?php 
          $i = 1;
          foreach($salidaproducto as $listasalidaproductos){ ?>  
              <tr class="contentTable">
                <td height="23" style="vertical-align: middle;"><?php echo str_pad($i, 3, 0, STR_PAD_LEFT); ?></td>
                <td style="vertical-align: middle;"><?php echo $listasalidaproductos->nombre_maquina; ?></td>
                <td style="vertical-align: middle;"><?php echo $listasalidaproductos->no_area; ?></td>
                <td style="vertical-align: middle;"><?php echo $listasalidaproductos->solicitante; ?></td>
                <td style="vertical-align: middle;"><?php echo $listasalidaproductos->fecha; ?></td>
                <td style="vertical-align: middle;"><?php echo $listasalidaproductos->no_producto; ?></td>
                <td style="vertical-align: middle;"><?php echo number_format($listasalidaproductos->cantidad_salida,2,'.',',');?></td>
                <td width="20" align="center"><input type="radio" name="newsletter" onClick="fill_inputs(<?php echo $listasalidaproductos->id_salida_producto; ?>)" style="cursor: pointer;" title="Devolución"/></td>
                
				<!--
                <td width="20" align="center"><img class="editar_producto" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar producto" onClick="editar_producto(<?php echo $listasalidaproductos->id_salida_producto; ?>)" style="cursor: pointer;"/></td>
                -->
                <!--
                <td width="20" align="center">
                  <a href="" class="eliminar_salida" id="elim_<?php echo $listasalidaproductos->id_salida_producto; ?>">
                  <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Salida"/></a>
                </td>
                -->
              </tr>
          <?php 
            $i++;
            } 
          ?>        
      </table>
    <?php }?>

</div>
<div id="finregistro"></div>
<div id="modalerror"></div>
<div id="mdlVerSalidas"></div>

<div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarregistrosalida');?></div>
    <div id="direccion_traer_stock"><?php echo site_url('comercial/traerStock_Autocompletado');?></div>
    <div id="direccion_traer_unidad_medida"><?php echo site_url('comercial/traerUnidadMedida_Autocompletado');?></div>
</div>
<div id="dialog-confirm" style="display: none;" title="Eliminar Registro">
	<p>
	    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
	    ¿Está seguro que quiere eliminar este Registro de Salida?<br /><strong>¡Esta acción no se puede revertir!</strong>
	</p>
</div>

<?php if(!empty($respuesta_general)){ ?>
    <div id="error_general"></div>
<?php } ?>

<?php if(!empty($respuesta_maquina)){ ?>
    <div id="respuesta_maquina"></div>
<?php } ?>

<?php if(!empty($respuesta_marca)){ ?>
    <div id="respuesta_marca"></div>
<?php } ?>

<?php if(!empty($respuesta_modelo)){ ?>
    <div id="respuesta_modelo"></div>
<?php } ?>

<?php if(!empty($respuesta_serie)){ ?>
    <div id="respuesta_serie"></div>
<?php } ?>

<?php if(!empty($respuesta_area)){ ?>
    <div id="respuesta_area"></div>
<?php } ?>

<?php if(!empty($respuesta_solicitante)){ ?>
    <div id="respuesta_solicitante"></div>
<?php } ?>

<?php if(!empty($respuesta_fecha)){ ?>
    <div id="respuesta_fecha"></div>
<?php } ?>

<?php if(!empty($respuesta_prod)){ ?>
    <div id="respuesta_prod"></div>
<?php } ?>

<?php if(!empty($respuesta_qty)){ ?>
    <div id="respuesta_qty"></div>
<?php } ?>