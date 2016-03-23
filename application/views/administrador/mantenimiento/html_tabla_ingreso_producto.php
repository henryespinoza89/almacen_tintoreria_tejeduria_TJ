<?php
	//Exportamos a Excel los resultados
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=ReporteListadoProductos.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<!-- TABLA LISTA DE VISITADORES -->
<table width="1229" border="0" cellspacing="0" cellpadding="0" id="listaVisitadores">
	<tr style="text-align:center; font-weight:bold;vertical-align: middle;border: 1px solid #005197;background-color: #0072C6;color: white;font-size: 12px;">
	    <td sort="nomcomp" width="150" height="30"><?php echo utf8_decode('id_ingreso_producto'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('id_comprobante'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('nro_comprobante'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('fecha'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_moneda'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_proveedor'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('total'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('gastos'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_almacen'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_agente'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('cs_igv'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('serie_comprobante'); ?></td>
	</tr>
	<?php 
		foreach($t_ingreso_producto as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_ingreso_producto); ?></td>
	    <td><?php echo utf8_decode($data->id_comprobante); ?></td>
	    <td><?php echo utf8_decode($data->nro_comprobante); ?></td>
	    <td><?php echo utf8_decode($data->fecha); ?></td>
	    <td><?php echo utf8_decode($data->id_moneda); ?></td>
	    <td><?php echo utf8_decode($data->id_proveedor); ?></td>
	    <td><?php echo utf8_decode($data->total); ?></td>
	    <td><?php echo utf8_decode($data->gastos); ?></td>
	    <td><?php echo utf8_decode($data->id_almacen); ?></td>
	    <td><?php echo utf8_decode($data->id_agente); ?></td>
	    <td><?php echo utf8_decode($data->cs_igv); ?></td>
	    <td><?php echo utf8_decode($data->serie_comprobante); ?></td>
	</tr>
	<?php
		}
	?>
</table>