<?php
	if ($this->input->post('fecharegistro')){
		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:145px','readonly'=> 'readonly', 'class'=>'required');
	}else{
		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:145px','readonly'=> 'readonly', 'class'=>'required');
	}
	
	if ($this->input->post('fecha_vencimiento')){
		$fecha_vencimiento = array('name'=>'fecha_vencimiento','id'=>'fecha_vencimiento','maxlength'=>'10','value'=>$this->input->post('fecha_vencimiento'), 'style'=>'width:150px','readonly'=> 'readonly', 'class'=>'required');
	}else{
		$fecha_vencimiento = array('name'=>'fecha_vencimiento','id'=>'fecha_vencimiento','maxlength'=>'10', 'style'=>'width:150px','readonly'=> 'readonly', 'class'=>'required');
	}

	if ($this->input->post('numcomprobante')){
		$numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'12','value'=>$this->input->post('numcomprobante'), 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false');
	}else{
		$numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'12', 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false');
	}

	if ($this->input->post('num_letra')){
		$num_letra = array('name'=>'num_letra','id'=>'num_letra','maxlength'=>'15','value'=>$this->input->post('num_letra'), 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false');
	}else{
		$num_letra = array('name'=>'num_letra','id'=>'num_letra','maxlength'=>'15', 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false');
	}

	if ($this->input->post('num_letra_enable')){
		$num_letra_enable = array('name'=>'num_letra_enable','id'=>'num_letra_enable','maxlength'=>'15','value'=>$this->input->post('num_letra_enable'), 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false','readonly'=> 'readonly');
	}else{
		$num_letra_enable = array('name'=>'num_letra_enable','id'=>'num_letra_enable','maxlength'=>'15', 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false','readonly'=> 'readonly');
	}

	if ($this->input->post('numcomprobante_enable')){
		$numcomprobante_enable = array('name'=>'numcomprobante_enable','id'=>'numcomprobante_enable','maxlength'=>'10','value'=>$this->input->post('numcomprobante_enable'), 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false','readonly'=> 'readonly');
	}else{
		$numcomprobante_enable = array('name'=>'numcomprobante_enable','id'=>'numcomprobante_enable','maxlength'=>'10', 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false','readonly'=> 'readonly');
	}
	
	if ($this->input->post('seriecomprobante_enable')){
		$seriecomprobante_enable = array('name'=>'seriecomprobante_enable','id'=>'seriecomprobante_enable','maxlength'=>'10','value'=>$this->input->post('seriecomprobante_enable'), 'style'=>'width:50px', 'class'=>'required','onpaste'=>'return false','readonly'=> 'readonly');
	}else{
		$seriecomprobante_enable = array('name'=>'seriecomprobante_enable','id'=>'seriecomprobante_enable','maxlength'=>'10', 'style'=>'width:50px', 'class'=>'required','onpaste'=>'return false','readonly'=> 'readonly');
	}

	if ($this->input->post('seriecomprobante')){
		$seriecomprobante = array('name'=>'seriecomprobante','id'=>'seriecomprobante','maxlength'=>'10','value'=>$this->input->post('seriecomprobante'), 'style'=>'width:50px');
	}else{
		$seriecomprobante = array('name'=>'seriecomprobante','id'=>'seriecomprobante','maxlength'=>'10', 'style'=>'width:50px');
	}

	if ($this->input->post('stock_sta_clara')){
		$stock_sta_clara = array('name'=>'stock_sta_clara','id'=>'stock_sta_clara','maxlength'=>'10','value'=>$this->input->post('stock_sta_clara'), 'style'=>'width:70px','readonly'=> 'readonly');
	}else{
		$stock_sta_clara = array('name'=>'stock_sta_clara','id'=>'stock_sta_clara','maxlength'=>'10', 'style'=>'width:70px','readonly'=> 'readonly');
	}

	if ($this->input->post('stock_sta_anita')){
		$stock_sta_anita = array('name'=>'stock_sta_anita','id'=>'stock_sta_anita','maxlength'=>'10','value'=>$this->input->post('stock_sta_anita'), 'style'=>'width:70px','readonly'=> 'readonly');
	}else{
		$stock_sta_anita = array('name'=>'stock_sta_anita','id'=>'stock_sta_anita','maxlength'=>'10', 'style'=>'width:70px','readonly'=> 'readonly');
	}

	/*******************************************************************************/
	$csigv  = array( 'true'=>'FACTURA CON IGV', 'false'=>'FACTURA SIN IGV');
	/*******************************************************************************/
	$categoria = array('name'=>'categoria','id'=>'categoria','readonly'=> 'readonly', 'style'=>'width:100px'); 
	$tipo_producto = array('name'=>'tipo_producto','id'=>'tipo_producto','readonly'=> 'readonly', 'style'=>'width:100px'); 
	$stockactual = array('name'=>'stockactual','id'=>'stockactual','readonly'=> 'readonly', 'style'=>'width:50px');
	$proveedor_back = array('name'=>'proveedor_back','id'=>'proveedor_back','readonly'=> 'readonly', 'style'=>'width:50px');
	/*******************************************************************************/
	if ($this->input->post('cantidad')){
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10','value'=>$this->input->post('cantidad'), 'style'=>'width:70px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}else{
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10', 'style'=>'width:70px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}

	if ($this->input->post('peso_unidad')){
		$peso_unidad = array('name'=>'peso_unidad','id'=>'peso_unidad','maxlength'=>'10','value'=>$this->input->post('peso_unidad'), 'style'=>'width:50px', 'class'=>'required', 'onkeyup'=>'calcular_peso_total()','onpaste'=>'return false');
	}else{
		$peso_unidad = array('name'=>'peso_unidad','id'=>'peso_unidad','maxlength'=>'10', 'style'=>'width:50px', 'class'=>'required', 'onkeyup'=>'calcular_peso_total()','onpaste'=>'return false');
	}

	if ($this->input->post('peso_total')){
		$peso_total = array('name'=>'peso_total','id'=>'peso_total','maxlength'=>'10','value'=>$this->input->post('peso_total'), 'style'=>'width:50px', 'readonly'=> 'readonly','onpaste'=>'return false');
	}else{
		$peso_total = array('name'=>'peso_total','id'=>'peso_total','maxlength'=>'10', 'style'=>'width:50px', 'readonly'=> 'readonly','onpaste'=>'return false');
	}
	/*******************************************************************************/
	if ($this->input->post('pu')){
		$pu = array('name'=>'pu','id'=>'pu','maxlength'=>'10','value'=>$this->input->post('pu'),'style'=>'width:50px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}else{
		$pu = array('name'=>'pu','id'=>'pu','maxlength'=>'10', 'style'=>'width:50px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}

	if ($this->input->post('pt')){
		$pt = array('name'=>'pt','id'=>'pt','maxlength'=>'10','value'=>$this->input->post('pt'),'style'=>'width:50px', 'readonly'=> 'readonly','onpaste'=>'return false');
	}else{
		$pt = array('name'=>'pt','id'=>'pt','maxlength'=>'10', 'style'=>'width:50px', 'readonly'=> 'readonly','onpaste'=>'return false');
	}
	
	if ($this->input->post('nombre_producto')){
		$nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto','value'=>$this->input->post('nombre_producto'),'style'=>'width:265px;font-family:verdana;','placeholder'=>' :: Nombre del Producto ::');
	}else{
		$nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto', 'style'=>'width:265px;font-family:verdana;','placeholder'=>' :: Nombre del Producto ::');
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

	function calcular(){

		unitario = $("#pu").val();
		if(unitario == '') unitario = 0;

		cantidad = $("#cantidad").val();
		if(cantidad == '') cantidad = 0;

		peso_unidad = $("#peso_unidad").val();
		if(peso_unidad == '') peso_unidad = 0;
		
		presentacion = $("#presentacion").val();
		if(presentacion == 12) {
			total = unitario * cantidad * 1;
		}else{
			total = unitario * cantidad * peso_unidad;
		}
		$("#pt").val(formatNumber.new(total));
	}

	function calcular_peso_total(){
		cantidad = $("#cantidad").val();
		if(cantidad == '') cantidad = 0;
		peso_unidad = $("#peso_unidad").val();
		if(peso_unidad == '') peso_unidad = 0;
		peso_total = cantidad * peso_unidad;
		$("#peso_total").val(formatNumber.new(peso_total));
	}

	$(function(){

		$("#nombre_producto").focus();

		<?php 
			if ($this->input->post('area')){
				$selected_area =  (int)$this->input->post('area');
			}else{	$selected_area = "";
		?>
	   			$("#area").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
			}	
		?>

		$("#imprimir_traslado").click(function(){
			var almacen_partida = $("#almacen_partida").val();
			var almacen_llegada = $("#almacen_llegada").val();
			var fecharegistro = $("#fecharegistro").val();
			if( almacen_partida == '' || almacen_llegada == '' || fecharegistro == ''){
				$("#modalerror").html('<strong>!Todos los Campos del Formulario son Obligatorios. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			}else{
				var array_json = Array();

				array_json[0] = almacen_partida;
	    		array_json[1] = almacen_llegada;
	    		array_json[2] = fecharegistro;

	    		// Convert to Object
			    var jObject = {};

			    for(i in array_json)
			    {
			        jObject[i] = array_json[i];
			    }
			    // Luego lo paso por JSON  a un archivo php llamado js.php
			    jObject= JSON.stringify(jObject);
		      	
		      	url = '<?php echo base_url(); ?>controller_traslado/exportar_doc_traslado/'+jObject;
		      	$(location).attr('href',url);
			}
	    });

		$("#submit_agregar_detalle_model_carrito").click(function(){
			if($("#nombre_producto").val() == '' || $("#cantidad").val() == '' || $("#area").val() == ''){
		        $("#modalerror").html('<strong>!Falta Completar algunos Campos del Formulario. Verificar!</strong>').dialog({
		          modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
		    }else{
	    		$.ajax({
			        type: 'POST',
			        url: "<?php echo base_url(); ?>comercial/agregar_detalle_producto_traslado_ajax/",
			        data: {
			          'nombre_producto' : $("#nombre_producto").val(),
			          'cantidad' : $("#cantidad").val(),
			          'id_area' : $("#area").val(),
			        },
			        success: function(response){
			        	if(response == 'successfull'){
			        		window.location.href="<?php echo base_url();?>comercial/gestiontraslados";
			        	}else if(response == 'stock_insuficiente'){
			        		$("#modalerror").html('<strong>!Stock Insuficiente para el Traslado. Verificar!</strong>').dialog({
			        		  modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
			        		  buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
			        		});
			        	}else if(response == 'error_get_data'){
			        		$("#modalerror").html('<strong>!No se obtuve los datos correctos del producto. Verificar!</strong>').dialog({
			        		  modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
			        		  buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
			        		});
			        	}
			        }
			    });
		    }
		});

		$("#div-loader").hide();

		$("#submit_finalizar_registro").on("click",function(){
			$("#div-loader").show().dialog({modal: true,position: 'center',width: 300, height: 195,resizable: false,hide: 'blind',show: 'blind',});
			var id_almacen_partida = $("#almacen_partida").val();
			var id_almacen_llegada = $("#almacen_llegada").val();
			var fecharegistro = $("#fecharegistro").val();
			<?php
				$existe = $this->cart->total_items();
				if( $existe > 0 ){
			?>
				if( fecharegistro == '' || id_almacen_partida == '' || id_almacen_llegada == ''){
					$("#div-loader").hide().dialog("destroy");
					$("#modalerror").html('<strong>!Falta Completar campos del Formulario. Verificar!</strong>').dialog({
			            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
			          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
			        });
				}else{
					var dataString = 'id_almacen_partida='+id_almacen_partida+'&id_almacen_llegada='+id_almacen_llegada+'&fecharegistro='+fecharegistro+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
					$.ajax({
			            type: "POST",
			            url: "<?php echo base_url(); ?>comercial/finalizar_registro_traslado/",
			          	data: dataString,
			          	success: function(response){
				            if(response == 1){
				            	$("#div-loader").hide().dialog("destroy");
				            	$("#finregistro").html('<span style="color:black"><b>!El Traslado de productos ha sido regristado con éxito!</b></span>').dialog({
				            	  	modal: true,position: 'center',width: 430,height: 125,resizable: false,show: "blind",hide: "blind", title: 'Fin de Registro',
				            	  	buttons: { Ok: function(){
				            	    	window.location.href="<?php echo base_url();?>comercial/gestiontraslados";
				            	  	}}
				            	});
				            }else if(response == 3){
				            	$("#div-loader").hide().dialog("destroy");
				              	$("#modalerror").html('<strong>!Error al insertar Cabecera de traslado!</strong>').dialog({
						            modal: true,position: 'center',width: 450, height: 135,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
						          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
						        });
				            }else if(response == 2){
				            	$("#div-loader").hide().dialog("destroy");
				              	$("#modalerror").html('<strong>!No Existe Stock disponible para el Traslado!</strong>').dialog({
						            modal: true,position: 'center',width: 450, height: 135,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
						          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
						        });
				            }
			            }
			       	});
				}
			<?php }else{ ?>
				$("#div-loader").hide().dialog("destroy");
				$("#modalerror").html('<strong>!Debe Registrar un Producto como mínimo a la Factura. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 480, height: 135,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			<?php } ?>
		});

	    /** Función de Autocompletado para el Nombre del Producto **/
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
			        $.post("<?php echo base_url('comercial/traer_producto_autocomplete_traslado'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term, a: id_area},
			        function (response) {
			            respond(response);
			        }, 'json');
				}
	      	}, select: function (event, ui) {
		      	var id_area = $("#area").val();

		        var selectedObj = ui.item;
		        $("#nombre_producto").val(selectedObj.no_producto);
		        $("#stock_sta_anita").val(selectedObj.stock_sta_anita);
		        $("#stock_sta_clara").val(selectedObj.stock_sta_clara);
	      }
	    });
	    /** Fin de la Función **/

		//Validar si existe el tipo de cambio del día registrado en el sistema
	    <?php if(isset($tipocambio) && $tipocambio == 1){ ?>
	    //Registro del Tipo de Cambio
	    $("#datacompra_dol").mask("9.999");
	    $("#dataventa_dol").mask("9.999");
	    $("#datacompra_eur").mask("9.999");
	    $("#dataventa_eur").mask("9.999");
	    $( "#newtipocambio" ).dialog({
	      modal: true,
	      position: 'center',
	      show: "blind",
	      draggable: false,
	      resizable: false,
	      closeOnEscape: false,
	      width:600,
	      height:'auto',
	      buttons: {
	        'Guardar': function() {
	          $(".ui-dialog-buttonpane button:contains('Guardar')").button("disable");
	          //$(".ui-dialog-buttonpane button:contains('Ok')").attr("disabled", true).addClass("ui-state-disabled");
	          var base_url = '<?php echo base_url();?>';

	          var compra_dol = $("#datacompra_dol").val();
	          var venta_dol = $("#dataventa_dol").val();
	          var compra_eur = $("#datacompra_eur").val();
	          var venta_eur = $("#dataventa_eur").val();
	          var dataString = 'compra_dol=' + compra_dol+ '&venta_dol=' + venta_dol+ '&compra_eur=' + compra_eur+ '&venta_eur=' + venta_eur;
	          $.ajax({
	            type: "POST",
	            url: base_url+"almacen/guardarTipoCambio",
	            data: dataString,
	            success: function(msg){
	              if(msg == 'ok'){
	                $("#finregistro").html('!El Tipo de Cambio ha sido regristado con éxito!.').dialog({
	                  modal: true,position: 'center',width: 350,height: 125,resizable: false, title: 'Fin de Registro',
	                  buttons: { Ok: function(){
	                    window.location.href="<?php echo base_url();?>almacen/gestionproveedores";
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

		<?php 
			if ($this->input->post('almacen_partida')){
				$selected_almacen_partida =  (int)$this->input->post('almacen_partida');
			}else{	$selected_almacen_partida = "";
		?>
       		//$("#almacen_partida").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
			}	
		?>

		<?php 
			if ($this->input->post('almacen_llegada')){
				$selected_almacen_llegada =  (int)$this->input->post('almacen_llegada');
			}else{	$selected_almacen_llegada = "";
		?>
       		//$("#almacen_llegada").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
			}	
		?>

       	$("#nomproducto").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
        //Validar campos, sólo numérico
        $("#numcomprobante").validCampoFranz('0123456789-');
        $("#cantidad").validCampoFranz('0123456789.');

		$("#fecharegistro").datepicker({ 
			dateFormat: 'yy-mm-dd',showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true
		});
		$(".ui-datepicker-trigger").css('padding-left','7px');

	});

</script>

<div id="contenedor">
	<?php if($tipocambio == 1){?>
      <div id="newtipocambio" title="Registrar Tipo de Cambio" style="font-size:11px;min-height:240px;">
        <?php echo form_open('/almacen/guardarTipoCambio',array('name'=>'tipodecambio')); ?>
          <?php
          $datacompra_dol = array('name'=>'datacompra_dol','id'=>'datacompra_dol','maxlength'=>'5', 'size'=>'10');
          $dataventa_dol = array('name'=>'dataventa_dol','id'=>'dataventa_dol','maxlength'=> '5', 'size'=>'10');
          $datacompra_eur = array('name'=>'datacompra_eur','id'=>'datacompra_eur','maxlength'=>'5', 'size'=>'10');
          $dataventa_eur = array('name'=>'dataventa_eur','id'=>'dataventa_eur','maxlength'=> '5', 'size'=>'10');
          ?>
          <table width="580" border="0" cellspacing="2" cellpadding="2" align="rigth">
            <tr>
              <td width="90" height="30">Fecha Actual:</td>
              <td width="100" height="30"><b><?php echo date('d-m-Y'); ?></b></td>
              <td width="110" height="30">Tipo de Cambio:</td>
              <td width="340" height="30"><a href="http://www.sbs.gob.pe/app/stats/tc-cv.asp" target="_blank" id="tipo_cambio">Superintendencia de Banca, Seguros y AFP</a></td>
            </tr>
          </table>
          <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 27px;margin-bottom:5px;">
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
	<div id="tituloCont" style="margin-bottom: 7px;padding-bottom: 7px;">Traslado de Productos</div>
	<div id="formFiltro">
		<div id="options" style="border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 0;">
        	<div class="newagente"><a href="<?php echo base_url(); ?>comercial/consultar_traslado_productos/">Consultar Traslados</a></div>
      	</div>
		<div style="width: 400px;float: left;background: whitesmoke;border-bottom: 1px solid #000;height: 170px;padding-top: 15px;padding-left: 15px;">
			<table width="518" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		          	<td width="148" valign="middle" height="30">Punto de Partida:</td>
		          	<?php
		          		$existe = count($listaalmacen_partida);
		          		if($existe <= 0){ ?>
			            	<td width="330" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
			        <?php    
			            }
			            else
			            {
		          	?>
		          		<td width="370" height="30"><?php echo form_dropdown('almacen_partida',$listaalmacen_partida,$selected_almacen_partida,"id='almacen_partida' disabled='disabled' style='width:180px;'");?></td>
		          	<?php }?>
		        </tr>
		    </table>
		    <table width="518" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		          	<td width="148" valign="middle" height="30">Punto de Llegada:</td>
		          	<?php
		          		$existe = count($listaalmacen_llegada);
		          		if($existe <= 0){ ?>
			            	<td width="330" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
			        <?php    
			            }
			            else
			            {
		          	?>
		          		<td width="370" height="30"><?php echo form_dropdown('almacen_llegada',$listaalmacen_llegada,$selected_almacen_llegada,"id='almacen_llegada' disabled='disabled' style='width:180px;'");?></td>
		          	<?php }?>
		        </tr>
		    </table>
		    <table width="518" border="0" cellspacing="0" cellpadding="0">
				<tr>
		          	<td width="148" valign="middle" height="30">Fecha de Registro:</td>
		          	<td width="370" height="30"><?php echo form_input($fecharegistro);?></td>
		        </tr>			        
		    </table>
		    <table width="556" border="0" cellspacing="0" cellpadding="0">			        
				<tr> 
		        	<td height="30" colspan="2" style="padding-top: 5px; padding-left: 170px;"><input name="submit" type="submit" id="submit_finalizar_registro" value="Finalizar Registro" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #F5A700; border-radius:6px; width:163px;padding-left: 13px;" /></td>
		        </tr>						        
			</table>
		</div>
		<div style="width: 965px;height: 170px;float: left;background: whitesmoke;border-bottom: 1px solid #000;padding-top: 15px;margin-bottom: 15px;">
			<!--
			<table width="518" border="0" cellspacing="0" cellpadding="0">
				<tr>
	          		<td width="180" valign="middle" height="30" style="padding-top: 4px;">N° de Comprobante:</td>
	          		<td width="70" height="30"><?php //echo form_input($seriecomprobante);?></td>
	          		<td width="370" height="30"><?php //echo form_input($numcomprobante);?></td>
				</tr>
			</table>
			-->
			<table width="446" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 3px;">
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
			<table width="457" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		          	<td width="128" valign="middle" height="30" style="width: 132px;">Producto:</td>
		          	<td width="228" height="30"><?php echo form_input($nombre_producto)?></td>
		        </tr>
			</table>
			<table width="227" border="0" cellspacing="0" cellpadding="0">
				<tr>
	          		<td width="180" valign="middle" height="30" style="padding-top: 4px;">Stock Sta. Anita:</td>
	          		<td width="70" height="30"><?php echo form_input($stock_sta_anita);?></td>
				</tr>
				<tr>
	          		<td width="180" valign="middle" height="30" style="padding-top: 4px;">Stock Sta. Clara:</td>
	          		<td width="70" height="30"><?php echo form_input($stock_sta_clara);?></td>
				</tr>
			</table>
			<table width="490">
				<tr>
					<td width="146" valign="middle" height="30">Cantidad:</td>
		          	<td width="120" height="30"><?php echo form_input($cantidad);?></td>
		          	<td style="padding-top: 3px;" colspan="6"><input name="submit" type="submit" id="submit_agregar_detalle_model_carrito" value="Agregar Producto" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;visibility: visible;height: 20px;" /></td>
				</tr>
			</table>
		</div>
	</div>

	<div style="float: left;margin-top: 5px;">
		<?php 
            $existe = $this->cart->total_items();
            if($existe <= 0){
            	echo 'Ingresar los Datos del Comprobante y sus Productos Respectivamente';
            }
            else
            {
        ?>
	</div>

	<div id="tabla_detalle_producto">
		<?php echo form_open("almacen/actualizar_carrito", 'id="actualizar" style="border-bottom: none; float: left;"') ?>
	        <table border="0" cellspacing="0" cellpadding="0" id="listaProductos">
		        <thead>
		            <tr class="tituloTable">
		              <td sort="idprod" width="80" height="25">Item</td>
		              <td sort="catprod" width="120">Cantidad</td>
		              <td sort="catprod" width="120">Área</td>
		              <td sort="nombreprod" width="380">Producto o Descripción</td>
		              <td sort="procprod" width="20">&nbsp;</td>
		            </tr>
		        </thead>
	            <?php 
	            $i = 1;
	            foreach($this->cart->contents() as $item){
	            	?>
	            	<input type="hidden" name="<?php echo $i; ?>[rowid]" value="<?php echo $item['rowid']; ?>" >

			        <tr class="contentTable" style="height: 32px; border-color: #F1EEEE;border-bottom-style: solid;">
			            <td><?php echo str_pad($i, 3, 0, STR_PAD_LEFT); ?></td>
			            <td><?php echo number_format($item['qty'],2,'.',','); ?></td>
        	            <?php 
        	            	if($this->cart->has_options($item['rowid']) === TRUE){
        		            	foreach ($this->cart->product_options($item['rowid']) as $option_name => $option_value){
        				?>
        	            		<td><?php echo $option_value; ?></td>
        	            	<?php } ?>
                    	<?php } ?>
			            <td><?php echo $item['name']; ?></td>
			            <td width="20" align="center">
			            	<?php echo anchor('comercial/remove_traslados/'.$item['rowid'],'X',array('style'=>'text-decoration: none; color:#898989;')); ?>
			            </td>
			        </tr>
	            <?php 
					$i++;
					} 
				?>
	            <tr>
	            	<td>&nbsp;</td>
	            	<td>&nbsp;</td>
	            	<td>&nbsp;</td>
	            	<td>&nbsp;</td>
	            </tr>
	        </table>
	        <table border="0" cellspacing="0" cellpadding="0" id="listaProductos">
	            <tr>
	            	<td style="width: 605px;"><?php echo anchor('comercial/vaciar_listado_traslado', 'Vaciar Listado de Productos', array('style'=>'text-decoration: none; background-color: #005197; color: white; font-family: tahoma; border-radius: 6px; padding: 3px 15px; font-size: 11px;')); ?></td>
	            	<td style="padding-top: 3px;width: 180px;">
	            		<i id="imprimir_traslado"><span id="text_print">Imprimir Documento<span class="fa fa-file-pdf-o" id="icon_print"></span></span></i>
	            	</td>
	            	<td>&nbsp;</td>
	            	<td>&nbsp;</td>
	            </tr>
	        </table>
	    <?php echo form_close() ?>
        <?php }?>
	</div>

</div>	
<div id="finregistro"></div>
<div id="modalerror"></div>

<div style="display:none">
	<div id="dir_get_index_producto"><?php echo site_url('almacen/traerIndex_Autocompletado');?></div>
	<div id="dir_get_categoria_producto"><?php echo site_url('almacen/traerCategoria');?></div>
	<div id="dir_get_tipo_producto"><?php echo site_url('almacen/traerTipo');?></div>
	<div id="dir_get_stock_producto"><?php echo site_url('almacen/traerStock');?></div>
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

<?php if(!empty($error_tipo_cambio)){ ?>
    <div id="error_tipo_cambio"></div>
<?php } ?>

<?php if(!empty($respuesta_csigv)){ ?>
    <div id="error_csigv"></div>
<?php } ?>

<?php if(!empty($respuesta_presentacion)){ ?>
    <div id="error_presentacion"></div>
<?php } ?>

<?php if(!empty($respuesta_carrito_peso_unidad)){ ?>
    <div id="error_peso_unidad"></div>
<?php } ?>

<div id="div-loader" style="text-align: center;background-color: white;display: none;">
    <img src="<?php echo base_url();?>assets/img/ajax-loader.gif"><br>
    <img src="<?php echo base_url();?>assets/img/ajax-loader-text.gif">
</div>
