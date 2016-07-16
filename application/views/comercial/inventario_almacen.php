<script type="text/javascript">
	$(function(){
		$("#report_inventario_excel").click(function(){
    		url = '<?php echo base_url(); ?>comercial/al_exportar_inventario_almacen/';
    		$(location).attr('href',url);
    	});
	});
</script>

<style>
	#mycanvas{
		width: 1345px;
		height: 460px ;
	}

	.chart{
		zoom:125%;
	}
</style>

</head>
<body>
    <div id="contenedor" style="">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Inventario Valorizado de Almacén</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 15px;border-bottom: 1px solid #000;">
			<table width="900" border="0" cellspacing="0" cellpadding="0" style="margin-top: 25px;margin-bottom: 20px;">
				<tr>
	                <td width="370" height="30" style="padding-bottom: 4px;font-size: 16px;font-weight: bold;color: black;">La Fecha considerada para el reporte es la del sistema: <?php echo date('d-m-y');?></td>
                    <td width="195"><input name="submit" type="submit" id="report_inventario_excel" class="report_inventario_excel" value="EXPORTAR REPORTE DE INVENTARIO A EXCEL" style="background-color: #FF5722;width: 270px;margin-bottom: 6px;" /></td>
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
	      	url: "<?php echo base_url(); ?>comercial/get_data_inventario_almacen_categoria/",
	      	success: function(msg_1){
	          	var variable_1 = JSON.parse(msg_1);
				var data = {
				    labels: ["Repuestos", "Suministros", "Maestranza", "Activo Fijo"],
				    datasets: [
				        {
				            label: "Total de 0compras en S/. - Año 2016   ", // optional
				            backgroundColor: [ "#FF6384","#36A2EB","#FFCE56","#4BC0C0"],
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
				var myFirstChart = new Chart(chrt,{ type: 'pie', data: data});
	      	}
	    });
	</script>