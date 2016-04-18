<script type="text/javascript">
	$(function(){
		$("#report_kardex_excel").click(function(){
    		url = '<?php echo base_url(); ?>comercial/al_exportar_inventario_almacen/';
    		$(location).attr('href',url);
    	});
	});
</script>

</head>
<body>
    <div id="contenedor" style="">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Inventario Valorizado de Almacén</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 15px;border-bottom: 1px solid #000;">
			<table width="600" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
				<tr>
	                <td width="370" height="30" style="padding-bottom: 4px;">La Fecha considerada para el reporte es la del sistema: <?php echo date('d-m-y');?></td>
                    <td width="195"><input name="submit" type="submit" id="report_kardex_excel" class="report_kardex_excel" value="Exportar Inventario" style="background-color: #4B8A08;width: 130px;margin-bottom: 6px;" /></td>
	            </tr>
			</table>
		</div>
    </div>
    <div id="modalerror"></div>