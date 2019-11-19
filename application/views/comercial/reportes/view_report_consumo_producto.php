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

		$("#report_consumo_producto_maquina").click(function(){
    		var fechainicial = $("#fechainicial").val();
    		var fechafinal = $("#fechafinal").val();
    		var id_detalle_producto = $("#id_detalle_producto").val();
    		if( fechainicial == '' || fechafinal == '' || id_detalle_producto == ''){
				sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
			}else{
				$.ajax({
	                type: 'POST',
	                url: "<?php echo base_url(); ?>comercial/al_exportar_report_consumo_maquina_view/",
	                data: {
	                  'fechainicial' : fechainicial,
	                  'fechafinal' : fechafinal,
	                  'id_detalle_producto' : id_detalle_producto
	                },
	                success: function(response){
	                  $("#view-response").html(response);
	                  $("#view-btn").css("display","block");
	                }
              	});
			}
    	});

		
		$("#report_consumo_export_excel").click(function(){
    		var fechainicial = $("#fechainicial").val();
    		var fechafinal = $("#fechafinal").val();
    		var id_detalle_producto = $("#id_detalle_producto").val();

    		if( fechainicial == '' || fechafinal == '' || id_detalle_producto == ''){
				sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
			}else{
				var array_json = Array();

	    		array_json[0] = fechainicial;
	    		array_json[1] = fechafinal;
	    		array_json[2] = id_detalle_producto;
	    		// Lo convierto a objeto
			    var jObject = {};

			    for(i in array_json){
			        jObject[i] = array_json[i];
			    }
			    //Luego lo paso por JSON  a un archivo php llamado js.php
			    jObject= JSON.stringify(jObject);
	    		
	    		url = '<?php echo base_url(); ?>comercial/al_exportar_report_consumo_maquina_excel/' + jObject;
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

<style>
	#mycanvas{
		width: 1345px;
		height: 460px;
	}

	.chart{
		zoom:125%;
	}
</style>

</head>
<body>
    <div id="contenedor">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Resumen de productos por máquina</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 15px;border-bottom: 1px solid #000;">
			<table width="600" border="0" cellspacing="0" cellpadding="0" style="margin-top: 25px;margin-bottom: 20px;margin-left: 25px;">
				<tr>
	            	<td width="115" height="35">Nombre del Producto:</td>
	                <td width="193" height="30"><?php echo form_input($nombre_producto_autocomplete);?></td>
	                <td style="display: none;"><input type="hidden" name="id_detalle_producto" id="id_detalle_producto" value=""></td>
	            </tr>
	            <tr>
	            	<td width="103" height="35">Fecha de Inicio:</td>
	                <td width="156" height="30"><?php echo form_input($fechainicial);?></td>
	            </tr>
				<tr>
	                <td width="81" height="35">Fecha Final:</td>
	                <td width="168" height="30"><?php echo form_input($fechafinal);?></td>
	            </tr>
			</table>
			<table style="margin-left: 342px;">
				<tr>
					<td width="195">
                    	<input name="submit" type="submit" id="report_consumo_producto_maquina" class="report_consumo_producto_maquina" value="BUSCAR INFORMACIÓN" style="background-color: #FF5722;width: 180px;margin-bottom: 6px;" />
                    </td>
				</tr>
			</table>
			<!--
			<div class="chart">
				<canvas id="mycanvas"></canvas>
			</div>
			-->
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

    <script type="text/javascript">
    	var chrt = document.getElementById("mycanvas").getContext("2d");
    	$.ajax({
          	type: "POST",
          	url: "<?php echo base_url(); ?>comercial/get_data_report_facturas_2016/",
          	success: function(msg_1){
	          	var variable_1 = JSON.parse(msg_1);
	          	console.log(variable_1);
				var data = {
				    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
				    datasets: [
				        {
				            label: "Total de compras en S/. - Año 2016   ", // optional
				            backgroundColor: "#007CC1",
				            borderColor: "#FFF",
				            borderWidth: 1,
				            pointHoverBorderColor: "rgba(255,99,132,1)",
				            pointRadius: 0,
				            pointHitRadius: 0,
				            pointHoverBackgroundColor: "#303F9F",
				            pointHoverBorderWidth: 2,
				            data: variable_1 // y-axis
				        }
				    ]
				};
				var myFirstChart = new Chart(chrt,{ type: 'bar', data: data});
          	}
        });
	
    </script>