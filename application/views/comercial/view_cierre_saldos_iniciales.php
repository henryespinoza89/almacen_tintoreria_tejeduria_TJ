<?php
	if ($this->input->post('fecha_inicial')){
	    $fecha_inicial = array('name'=>'fecha_inicial','id'=>'fecha_inicial','maxlength'=>'10','value'=>$this->input->post('fecha_inicial'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}else{
	    $fecha_inicial = array('name'=>'fecha_inicial','id'=>'fecha_inicial','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}

	if ($this->input->post('fecha_final')){
	    $fecha_final = array('name'=>'fecha_final','id'=>'fecha_final','maxlength'=>'10','value'=>$this->input->post('fecha_final'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}else{
	    $fecha_final = array('name'=>'fecha_final','id'=>'fecha_final','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}
?>

<script type="text/javascript">
	$(function(){
		
		$("#div-loader").hide();
		
		$("#report_exportar_excel").click(function(){
	    	url = '<?php echo base_url(); ?>comercial/al_exportar_cierre_excel/';
	    	$(location).attr('href',url);
    	});

		$("#cierre_almacen_saldos_iniciales").click(function(){
			$("#div-loader").show().dialog({modal: true,position: 'center',width: 300, height: 195,resizable: false,hide: 'blind',show: 'blind',});
    		var fecha_inicial = $("#fecha_inicial").val(); var fecha_final = $("#fecha_final").val();
    		if( fecha_inicial == '' || fecha_final == ''){
    			$("#div-loader").hide().dialog("destroy");
				$("#modalerror").html('<strong>!El Campo del Formulario es Obligatorio. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			}else{
				$.ajax({
	                type: 'POST',
	                url: "<?php echo base_url(); ?>comercial/actualizar_saldos_iniciales_controller_version_6/",
	                data: {
	                  'fecha_inicial' : fecha_inicial,
	                  'fecha_final' : fecha_final
	                },
	                success: function(response){
	                  	if(response == 1){
	                  		$("#div-loader").hide().dialog("destroy");
			                $("#finregistro").html('!El Cierre de Almacén ha sido regristado con éxito!.').dialog({
			                  	modal: true,position: 'center',width: 350,height: 125,resizable: false, title: 'Fin de Registro',
			                  	buttons: { Ok: function(){
			                    	window.location.href="<?php echo base_url();?>comercial/gestion_cierre_saldos_iniciales";
			                  	}}
			                });
	                  	}else if(response == 'error_validacion'){
	                  		$("#div-loader").hide().dialog("destroy");
	                  		$("#modalerror").html('<strong>!Ya Existe registro de Cierre de Almacén para la Fecha Seleccionada. Verifique!</strong>').dialog({
					            modal: true,position: 'center',width: 450, height: 135,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
					          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
					        });
	                  	}else{
	                  		$("#div-loader").hide().dialog("destroy");
	                  		$("#modalerror").html('<strong>!No se realizo el Registro. Intentelo Nuevamente!</strong>').dialog({
					            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}});
	                  	}
	                }
	            });
			}
    	});

		$("#registrar_monto_cierre").click(function(){
			$("#div-loader").show().dialog({modal: true,position: 'center',width: 300, height: 195,resizable: false,hide: 'blind',show: 'blind',});
    		var fecha_inicial = $("#fecha_inicial").val(); var fecha_final = $("#fecha_final").val();
    		if( fecha_inicial == '' || fecha_final == ''){
    			$("#div-loader").hide().dialog("destroy");
				$("#modalerror").html('<strong>!El Campo del Formulario es Obligatorio. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			}else{
				$.ajax({
	                type: 'POST',
	                url: "<?php echo base_url(); ?>comercial/registrar_cierre_mes/",
	                data: {
	                  'fecha_inicial' : fecha_inicial,
	                  'fecha_final' : fecha_final
	                },
	                success: function(response){
	                  	if(response == 1){
	                  		$("#div-loader").hide().dialog("destroy");
			                $("#finregistro").html('!El Cierre de Almacén ha sido regristado con éxito!.').dialog({
			                  	modal: true,position: 'center',width: 350,height: 125,resizable: false, title: 'Fin de Registro',
			                  	buttons: { Ok: function(){
			                    	window.location.href="<?php echo base_url();?>comercial/gestion_cierre_saldos_iniciales";
			                  	}}
			                });
	                  	}else if(response == 'error_validacion'){
	                  		$("#div-loader").hide().dialog("destroy");
	                  		$("#modalerror").html('<strong>!Ya Existe registro de Cierre de Almacén para la Fecha Seleccionada. Verifique!</strong>').dialog({
					            modal: true,position: 'center',width: 450, height: 135,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
					          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
					        });
	                  	}else{
	                  		$("#div-loader").hide().dialog("destroy");
	                  		$("#modalerror").html('<strong>!No se realizo el Registro. Intentelo Nuevamente!</strong>').dialog({
					            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
					        });
	                  	}
	                }
	            });
			}
    	});

		$("#fecha_inicial").datepicker({ 
			dateFormat: 'yy-mm-dd',showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
			buttonImageOnly: true,
		    changeMonth: true,
		    changeYear: true
		});
		$(".ui-datepicker-trigger").css('padding-left','7px');

		$("#fecha_final").datepicker({ 
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
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Cierre de Saldos Iniciales</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 10px;border-bottom: 1px solid #000;">
			<table width="350" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
				<tr>
	                <td width="100" height="30" style="padding-bottom: 5px;">Fecha de Cierre Inicial:</td>
	                <td width="160" height="30"><?php echo form_input($fecha_inicial);?></td>
	            </tr>
	            <tr>
	                <td width="130" height="30" style="padding-bottom: 5px;">Fecha de Cierre Final:</td>
	                <td width="160" height="30"><?php echo form_input($fecha_final);?></td>
	            </tr>
	            <tr>
	            	<td width="180" colspan="2"><input name="submit" type="submit" id="cierre_almacen_saldos_iniciales" class="cierre_almacen_saldos_iniciales" value="CIERRE DE SALDOS INICIALES" style="background-color: #4B8A08;width: 188px;margin-bottom: 6px;margin-top: 10px;margin-left: 100px;" /></td>
	            </tr>
	            <tr>
	            	<td width="180" colspan="2"><input name="submit" type="submit" id="registrar_monto_cierre" class="registrar_monto_cierre" value="REGISTRAR MONTO DE CIERRE" style="background-color: #4B8A08;width: 188px;margin-bottom: 6px;margin-top: 10px;margin-left: 100px;" /></td>
	            </tr>
			</table>
		</div>


		    
	  
    </div>
    <div id="modalerror"></div>
    <div id="finregistro"></div>
    <div id="div-loader" style="text-align: center;background-color: white;display: none;">
	    <img src="<?php echo base_url();?>assets/img/ajax-loader.GIF"><br>
	    <img src="<?php echo base_url();?>assets/img/ajax-loader-text.GIF">
	</div>