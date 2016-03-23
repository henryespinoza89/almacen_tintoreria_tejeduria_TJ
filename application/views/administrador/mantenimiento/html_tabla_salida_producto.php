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
	    <td sort="nomcomp" width="150" height="30"><?php echo utf8_decode('id_salida_producto'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('id_nombre_maquina'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('id_marca'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_modelo'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_serie'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_area'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('solicitante'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('fecha'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_detalle_producto'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('cantidad_salida'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_almacen'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('p_u_salida'); ?></td>
	</tr>
	<?php 
		foreach($t_salida_producto as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_salida_producto); ?></td>
	    <td><?php echo utf8_decode($data->id_nombre_maquina); ?></td>
	    <td><?php echo utf8_decode($data->id_marca); ?></td>
	    <td><?php echo utf8_decode($data->id_modelo); ?></td>
	    <td><?php echo utf8_decode($data->id_serie); ?></td>
	    <td><?php echo utf8_decode($data->id_area); ?></td>
	    <td><?php echo utf8_decode($data->solicitante); ?></td>
	    <td><?php echo utf8_decode($data->fecha); ?></td>
	    <td><?php echo utf8_decode($data->id_detalle_producto); ?></td>
	    <td><?php echo utf8_decode($data->cantidad_salida); ?></td>
	    <td><?php echo utf8_decode($data->id_almacen); ?></td>
	    <td><?php echo utf8_decode($data->p_u_salida); ?></td>
	</tr>
	<?php
		}
	?>
</table>