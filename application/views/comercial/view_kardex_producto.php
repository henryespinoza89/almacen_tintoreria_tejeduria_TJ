<?php
	if ($this->input->post('nombre_producto_autocomplete')){
        $nombre_producto_autocomplete = array('name'=>'nombre_producto_autocomplete','id'=>'nombre_producto_autocomplete','value'=>$this->input->post('nombre_producto_autocomplete'), 'style'=>'width:230px;font-family: verdana;float: left;margin-bottom: 0;','placeholder'=>' :: Nombre del Producto ::');
    }else{
        $nombre_producto_autocomplete = array('name'=>'nombre_producto_autocomplete','id'=>'nombre_producto_autocomplete', 'style'=>'width:230px;font-family: verdana;float: left;margin-bottom: 0;','placeholder'=>' :: Nombre del Producto ::'); 
    }

    if ($this->input->post('fechainicial')){
	    $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10','value'=>$this->input->post('fechainicial'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}else{
	    $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}

	if ($this->input->post('fechafinal')){
	    $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10','value'=>$this->input->post('fechafinal'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}else{
	    $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}
?>

<script type="text/javascript">
	$(function(){

		$("#report_kardex_excel").click(function(){
    		var fechainicial = $("#fechainicial").val();
    		var fechafinal = $("#fechafinal").val();
    		var id_detalle_producto = $("#id_detalle_producto").val();

    		if( fechainicial == '' || fechafinal == '' || id_detalle_producto == ''){
				$("#modalerror").html('<strong>!Todos los Campos del Formulario son Obligatorios. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			}else{
				var array_json = Array();

	    		array_json[0] = id_detalle_producto;
	    		array_json[1] = fechainicial;
	    		array_json[2] = fechafinal;
	    		// Lo convierto a objeto
			    var jObject = {};

			    for(i in array_json)
			    {
			        jObject[i] = array_json[i];
			    }
			    //Luego lo paso por JSON  a un archivo php llamado js.php
			    jObject= JSON.stringify(jObject);
	    		
	    		url = '<?php echo base_url(); ?>comercial/al_exportar_kardex_producto_excel/'+jObject;
	    		$(location).attr('href',url);
				/* Validar si existe saldo inicial del producto en la fecha indicada 
	    		var dataString = 'id_detalle_producto='+id_detalle_producto+'&fechainicial='+fechainicial+'&fechafinal='+fechafinal+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
		    	$.ajax({
		            type: "POST",
		            url: "<?php echo base_url(); ?>comercial/validar_existencia_saldo_inicial/",
		          	data: dataString,
		          	success: function(response){
		            if(response == 1){
		              	$("#modalerror").html('<span style="color:red"><strong>!No Existe Registro de Saldo Inicial para ese producto en la Fecha de Inicio seleccionada. Verificar!</strong></span>').dialog({
				            modal: true,position: 'center',width: 450, height: 140,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
				          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
				        });
		              	$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
		            }else{
			            	var array_json = Array();

				    		array_json[0] = id_detalle_producto;
				    		array_json[1] = fechainicial;
				    		array_json[2] = fechafinal;
				    		// Lo convierto a objeto
						    var jObject = {};

						    for(i in array_json)
						    {
						        jObject[i] = array_json[i];
						    }
						    //Luego lo paso por JSON  a un archivo php llamado js.php
						    jObject= JSON.stringify(jObject);
				    		
				    		url = '<?php echo base_url(); ?>comercial/al_exportar_kardex_producto_excel/'+jObject;
				    		$(location).attr('href',url);
		                }
		            }
		        });
		        */
			}
    	});

		$("#script_precio_unitario").click(function(){
			$.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>comercial/actualizarPrecioUnitarioProducto/",
              success: function(msg){
				msg = JSON.parse(msg);
                if(msg.message == '1'){
                  swal({ title: `El Precio unitario Producto de ${msg.count} productos ha sido Actualizado con éxito!`,text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                  $("#mdlEditarProducto").dialog("close");
                }else{
                  sweetAlert(msg, "", "error");
                }
              }
            });
		});

		$("#report_kardex_excel_v2").click(function(){
    		var fechainicial = $("#fechainicial").val();
    		var fechafinal = $("#fechafinal").val();
    		var id_detalle_producto = $("#id_detalle_producto").val();

    		if( fechainicial == '' || fechafinal == '' || id_detalle_producto == ''){
				$("#modalerror").html('<strong>!Todos los Campos del Formulario son Obligatorios. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			}else{
				var array_json = Array();

	    		array_json[0] = id_detalle_producto;
	    		array_json[1] = fechainicial;
	    		array_json[2] = fechafinal;
	    		
			    var jObject = {}; // Lo convierto a objeto

			    for(i in array_json){
			        jObject[i] = array_json[i];
			    }

			    jObject= JSON.stringify(jObject);
	    		
	    		url = '<?php echo base_url(); ?>comercial/al_exportar_kardex_producto_excel_v2/'+jObject;
	    		$(location).attr('href',url);
			}
    	});

		$("#nombre_producto_autocomplete").autocomplete({
	        source: function (request, respond) {
	        	$.post("<?php echo base_url('comercial/traer_producto_autocomplete_with_id'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
		        function (response) {
		            respond(response);
		        }, 'json');
	        }, select: function (event, ui) {
		        var selectedObj = ui.item;
		        $("#nombre_producto_autocomplete").val(selectedObj.nombre_producto);
		        $("#id_detalle_producto").val(selectedObj.id_detalle_producto);
	        }
	    });

	    $("#fechainicial").datepicker({ 
			dateFormat: 'yy-mm-dd',showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
			buttonImageOnly: true,
		    changeMonth: true,
		    changeYear: true
		});
		$(".ui-datepicker-trigger").css('padding-left','7px');

		$("#fechafinal").datepicker({ 
			dateFormat: 'yy-mm-dd',showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
			buttonImageOnly: true,
		    changeMonth: true,
		    changeYear: true
		});
		$(".ui-datepicker-trigger").css('padding-left','7px');
	    
	});
</script>

</head>
<body>
    <div id="contenedor" style="">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Gestión de Kardex por Producto</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 15px;border-bottom: 1px solid #000;">
	        
		        <table width="440" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
		            <tr>
		            	<td width="115" height="30">Nombre del Producto:</td>
		                <td width="193" height="30"><?php echo form_input($nombre_producto_autocomplete);?></td>
		                <td><input type="hidden" name="id_detalle_producto" id="id_detalle_producto" value=""></td>
		            </tr>
				</table>
				<table width="1003" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
					<tr>
		                <td width="103" height="30">Fecha de Inicio:</td>
		                <td width="156" height="30"><?php echo form_input($fechainicial);?></td>
		                <td width="81" height="30">Fecha Final:</td>
		                <td width="168" height="30"><?php echo form_input($fechafinal);?></td>
	                    <!--<td width="195"><input name="submit" type="submit" id="report_kardex_excel" class="report_kardex_excel" value="Generar Kardex del Producto" style="background-color: #4B8A08;width: 170px;margin-bottom: 6px;" /></td>-->
	                    <td width="195"><input name="submit" type="submit" id="report_kardex_excel_v2" class="report_kardex_excel_v2" value="KARDEX DE PRODUCTO" style="background-color: #4B8A08;width: 170px;margin-bottom: 6px;" /></td>
						<!-- El siguiente boton y su funcionalidad permitio regularizar productos que tenian stock pero no precio -->
						<!-- <td width="195"><input name="submit" type="submit" id="script_precio_unitario" class="script_precio_unitario" value="CORRER SCRIPT - PRECIO UNITARIO" /></td> -->
		            </tr>
				</table>
			
		</div>
    </div>
    <div id="modalerror"></div>