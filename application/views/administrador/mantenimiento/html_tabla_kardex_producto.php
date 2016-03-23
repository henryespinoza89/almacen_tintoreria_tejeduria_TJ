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
	    <td sort="nomcomp" width="150" height="30"><?php echo utf8_decode('id_kardex_producto'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('descripcion'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('id_detalle_producto'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('stock_anterior'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('precio_unitario_anterior'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('cantidad_salida'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('stock_actual'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('precio_unitario_actual'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('fecha_registro'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('cantidad_ingreso'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('precio_unitario_actual_promedio'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('serie_comprobante'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('num_comprobante'); ?></td>
	</tr>
	<?php 
		foreach($t_kardex_producto as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_kardex_producto); ?></td>
	    <td><?php echo utf8_decode($data->descripcion); ?></td>
	    <td><?php echo utf8_decode($data->id_detalle_producto); ?></td>
	    <td><?php echo utf8_decode($data->stock_anterior); ?></td>
	    <td><?php echo utf8_decode($data->precio_unitario_anterior); ?></td>
	    <td><?php echo utf8_decode($data->cantidad_salida); ?></td>
	    <td><?php echo utf8_decode($data->stock_actual); ?></td>
	    <td><?php echo utf8_decode($data->precio_unitario_actual); ?></td>
	    <td><?php echo utf8_decode($data->fecha_registro); ?></td>
	    <td><?php echo utf8_decode($data->cantidad_ingreso); ?></td>
	    <td><?php echo utf8_decode($data->precio_unitario_actual_promedio); ?></td>
	    <td><?php echo utf8_decode($data->serie_comprobante); ?></td>
	    <td><?php echo utf8_decode($data->num_comprobante); ?></td>
	</tr>
	<?php
		}
	?>
</table>