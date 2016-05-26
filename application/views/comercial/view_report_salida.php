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

		//$("#div-loader").hide();

		$("#report_registro_salidas").click(function(){
			//$("#div-loader").show().dialog({modal: true,position: 'center',width: 300, height: 195,resizable: false,hide: 'blind',show: 'blind',});
    		var fechainicial = $("#fechainicial").val();
    		var fechafinal = $("#fechafinal").val();
    		if( fechainicial == '' || fechafinal == ''){
    			//$("#div-loader").hide().dialog("destroy");
				$("#modalerror").html('<strong>!Todos los Campos del Formulario son Obligatorios. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			}else{
	    		var array_json = Array();
	    		array_json[0] = fechainicial;
	    		array_json[1] = fechafinal;
			    var jObject = {};
			    for(i in array_json){ 
			    	jObject[i] = array_json[i]; 
			   	}
			    jObject= JSON.stringify(jObject);
			    /* Opción 1 */
	    		url = '<?php echo base_url(); ?>comercial/al_exportar_report_salidas/'+jObject;
	    		$(location).attr('href',url);
	    		/* Opción 2 */
	    		/*
	    		$.ajax({ 
					url: location.href ='<?php echo base_url(); ?>comercial/al_exportar_report_salidas/'+jObject,
				    success: function(response){	$("#div-loader").hide().dialog("destroy"); }
				});
				*/
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
		height: 460px !important;
	}

	.chart{
		zoom:125%;
	}
</style>

</head>
<body>
    <div id="contenedor">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Reporte de Salida de Productos</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 15px;border-bottom: 1px solid #000;">
			<table width="703" border="0" cellspacing="0" cellpadding="0" style="margin-top: 25px;margin-bottom: 20px;">
				<tr>
	                <td width="103" height="30">Fecha de Inicio:</td>
	                <td width="156" height="30"><?php echo form_input($fechainicial);?></td>
	                <td width="81" height="30">Fecha Final:</td>
	                <td width="168" height="30"><?php echo form_input($fechafinal);?></td>
                    <td width="195"><input name="submit" type="submit" id="report_registro_salidas" class="report_registro_salidas" value="EXPORTE REPORTE A EXCEL" style="background-color: #FF5722;width: 180px;margin-bottom: 6px;" /></td>
	            </tr>
			</table>
			<div class="chart">
				<canvas id="mycanvas"></canvas>
			</div>
		</div>
    </div>
    <div id="modalerror"></div>

    <script type="text/javascript">
    	
    	var chrt = document.getElementById("mycanvas").getContext("2d");

    	$.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>comercial/get_data_report_consumos_2016/",
          success: function(msg){
          	var variable = JSON.parse(msg);
			var data = {
			    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
			    datasets: [
			        {
			            label: "Total de consumos en S/. - Año 2016   ", // optional
			            backgroundColor: "#007CC1",
			            borderColor: "#FFF",
			            borderWidth: 1,
			            pointHoverBorderColor: "rgba(255,99,132,1)",
			            pointRadius: 0,
			            pointHitRadius: 0,
			            pointHoverBackgroundColor: "#303F9F",
			            pointHoverBorderWidth: 2,
			            data: variable // y-axis
			        }
			    ]
			};

			var myFirstChart = new Chart(
										chrt,{
									    	type: 'bar',
									    	data: data
										});
          }
        });
	
    </script>