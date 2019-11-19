<?php
	if ($this->input->post('nombre_producto_autocomplete')){
        $nombre_producto_autocomplete = array('name'=>'nombre_producto_autocomplete','id'=>'nombre_producto_autocomplete','value'=>$this->input->post('nombre_producto_autocomplete'), 'style'=>'width:350px;font-family: verdana;float: left;margin-bottom: 11px;height: 27px;','placeholder'=>' :: Nombre del Producto ::');
    }else{
        $nombre_producto_autocomplete = array('name'=>'nombre_producto_autocomplete','id'=>'nombre_producto_autocomplete', 'style'=>'width:350px;font-family: verdana;float: left;margin-bottom: 11px;height: 27px;','placeholder'=>' :: Nombre del Producto ::'); 
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
		
		$("#report_rotacion_inventario").click(function(){
    		var anio = $("#anios").val();
    		if( anio == ''){
				sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
			}else{
				var array_json = Array();
	    		array_json[0] = anio;
			    var jObject = {};

			    for(i in array_json){
			        jObject[i] = array_json[i];
			    }
			    jObject= JSON.stringify(jObject);
	    		url = '<?php echo base_url(); ?>comercial/al_exportar_report_rotacion_inventario_excel/' + jObject;
	    		$(location).attr('href',url);
			}
    	});

    	<?php
				if ($this->input->post('anios')){
					$selected_anio =  (int)$this->input->post('anios');
				}else{	$selected_anio = "";
		?>
					$("#anios").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
				}
		?>

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
    <div id="contenedor">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Rotación de inventario</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 15px;border-bottom: 1px solid #000;">
			<table width="600" border="0" cellspacing="0" cellpadding="0" style="margin-top: 25px;margin-bottom: 20px;margin-left: 25px;">
				<tr>
	            	<td width="150" height="35" style="font-weight: bold;">Exportar productos que no tuvieron movimiento en el periodo:</td>
	            	<?php
		          		$existe = count($lista_anios_registros);
		          		if($existe <= 0){ ?>
			            	<td width="250" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
			        <?php    
			            } else {
		          	?>
		          		<td width="220"><?php echo form_dropdown('anios', $lista_anios_registros,$selected_anio,"id='anios' class='required' style='width:200px;margin-left: 15px;height: 28px;'");?></td>
		          	<?php }?>
		          	<td width="195">
                    	<input name="submit" type="submit" id="report_rotacion_inventario" class="report_rotacion_inventario" value="EXPORTAR INFORMACIÓN" style="background-color: #FF5722;width: 180px;margin-bottom: 6px;" />
                    </td>
	            </tr>
			</table>
		</div>
    </div>

    <div class="view-response" id="view-response"></div>

    <div id="formFiltro">
	    <div class="view-btn" id="view-btn" style="display: none;">
	    	<table style="margin-left: 1242px;">
				<tr>
					<td width="195">
	                	<input name="submit" type="submit" id="report_consumo_export_excel" class="report_consumo_export_excel" value="EXPORTAR A EXCEL" style="background-color: #FF5722;width: 180px;margin-bottom: 6px;" />
	                </td>
				</tr>
			</table>
	    </div>
	</div>