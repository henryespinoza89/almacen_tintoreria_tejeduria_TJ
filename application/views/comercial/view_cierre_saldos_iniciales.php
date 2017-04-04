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

    	$('#lista_monto_cierre').DataTable();

    	$("#validacion_negative").click(function(){
			$.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>comercial/validacion_negative_controller/",
                success: function(response){
                  	if(response == 1){
                  		$("#div-loader").hide().dialog("destroy");
		                swal({
		                    title: "El Cierre de Almacén ha sido regristado con éxito!",
		                    text: "",
		                    type: "success",
		                    confirmButtonText: "OK"
		                  	},function(isConfirm){
			                    if (isConfirm) {
			                      window.location.href="<?php echo base_url();?>comercial/gestion_cierre_saldos_iniciales";  
			                    }
		                	}
		                );
                  	}else if(response == 'cierre_duplicado'){
                  		$("#div-loader").hide().dialog("destroy");
                  		sweetAlert("!No es posible realizar el cierre del periodo seleccionado. Verificar!", "Ya existe un Cierre de Almacén para ese periodo", "error");
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
    	});

		$("#cierre_almacen_saldos_iniciales").click(function(){
			$("#div-loader").show().dialog({modal: true,position: 'center',width: 300, height: 195,resizable: false,hide: 'blind',show: 'blind',});
    		var fecha_inicial = $("#fecha_inicial").val(); var fecha_final = $("#fecha_final").val();
    		if( fecha_inicial == '' || fecha_final == ''){
    			$("#div-loader").hide().dialog("destroy");
				sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
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
			                swal({
			                    title: "El Cierre de Almacén ha sido regristado con éxito!",
			                    text: "",
			                    type: "success",
			                    confirmButtonText: "OK"
			                  	},function(isConfirm){
				                    if (isConfirm) {
				                      window.location.href="<?php echo base_url();?>comercial/gestion_cierre_saldos_iniciales";  
				                    }
			                	}
			                );
	                  	}else if(response == 'cierre_duplicado'){
	                  		$("#div-loader").hide().dialog("destroy");
	                  		sweetAlert("!No es posible realizar el cierre del periodo seleccionado. Verificar!", "Ya existe un Cierre de Almacén para ese periodo", "error");
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
				sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
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
					        sweetAlert("!No es posible realizar el cierre del periodo seleccionado. Verificar!", "Ya Existe registro de Cierre de Almacén para la Fecha Seleccionada", "error");
	                  	}else if(response == 'no_existe_saldos_iniciales'){
	                  		$("#div-loader").hide().dialog("destroy");
					        sweetAlert("!No es posible realizar el cierre del periodo seleccionado. Verificar!", "No existe registros de Saldos iniciales para el periodo de Cierre seleccionado", "error");
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
<body
>
    <div id="contenedor" style="">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Cierre de Almacén</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 10px;border-bottom: 1px solid #000;margin-bottom: 30px;">
			<table width="1250" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;margin-bottom: 10px;">
				<tr>
	                <td width="150" height="30" style="padding-bottom: 5px;">Fecha de Cierre Inicial:</td>
	                <td width="160" height="30"><?php echo form_input($fecha_inicial);?></td>
	                <td width="150" height="30" style="padding-bottom: 5px;">Fecha de Cierre Final:</td>
	                <td width="160" height="30"><?php echo form_input($fecha_final);?></td>
	                <td width="260" colspan="1"><input name="submit" type="submit" id="cierre_almacen_saldos_iniciales" class="cierre_almacen_saldos_iniciales" value="CIERRE DE SALDOS INICIALES" style="background-color: #FF5722;width: 188px;margin-bottom: 6px;margin-left: 40px;" /></td>
	                <td width="180" colspan="2"><input name="submit" type="submit" id="registrar_monto_cierre" class="registrar_monto_cierre" value="REGISTRAR MONTO DE CIERRE" style="background-color: #FF5722;width: 188px;margin-bottom: 6px;margin-right: 40px;" /></td>
	                <td width="195" colspan="2"><input name="submit" type="submit" id="report_exportar_excel" class="report_exportar_excel" value="EXPORTAR A EXCEL" style="background-color: #4B8A08;width: 160px;margin-bottom: 6px;" /></td>
	                <!--<td width="195" colspan="2"><input name="submit" type="submit" id="validacion_negative" class="validacion_negative" value="VALIDACION RESULT NEGATIVOS" style="background-color: #4B8A08;width: 160px;margin-bottom: 6px;" /></td>-->
	            </tr>
			</table>
		</div>

		<?php 
          	$existe = count($monto);
          	if($existe <= 0){
            	echo 'No existen registros en el Sistema.';
          	}
          	else
            {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="lista_monto_cierre" style="float: left;width:800px;" class="table table-hover table-striped">
          <thead>
            <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
              <td sort="idprod" width="65" height="27">ITEM</td>
              <td sort="idproducto" width="150" height="27">FECHA DE CIERRE</td>
              <td sort="nombreprod" width="150">MES  DE CIERRE</td>
              <td sort="catprod" width="150">MONTO DE CIERRE</td>
            </tr>
          </thead>
          <?php
            $i = 1;
            foreach($monto as $row){
            	// formatear la fecha
            	$elementos = explode("-", $row->fecha_cierre);
		        $anio = $elementos[0];
		        $mes = $elementos[1];
		        $dia = $elementos[2];
		        $array = array($dia, $mes, $anio);
		        $fecha_formateada = implode(" - ", $array);
          ?> 
          <body> 
            <tr class="contentTable" style="font-size: 12px;">
              	<td style="vertical-align: middle;"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
              	<td style="vertical-align: middle;"><?php echo $fecha_formateada; ?></td>
              	<td style="vertical-align: middle;"><?php echo $row->nombre_mes; ?></td>
              	<td style="vertical-align: middle;"><?php echo number_format($row->monto_cierre_sta_anita,2,'.',','); ?></td>
            </tr>
          	<?php
            	$i++;
            } 
          	?>
          	</body>
        </table>
      	<?php }?>
    </div>
    <div id="modalerror"></div>
    <div id="finregistro"></div>
    <div id="div-loader" style="text-align: center;background-color: white;display: none;position:relative !important;">
	    <img src="<?php echo base_url();?>assets/img/ajax-loader.GIF"><br>
	    <img src="<?php echo base_url();?>assets/img/ajax-loader-text.GIF">
	</div>