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
	    <td sort="nomcomp" width="150" height="30"><?php echo utf8_decode('id_pro'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('id_producto'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('observacion'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_almacen'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_procedencia'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_categoria'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_detalle_producto'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_tipo_producto'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_unidad_medida'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('estado'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('column_temp'); ?></td>
	</tr>
	<?php 
		foreach($t_producto as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_pro); ?></td>
	    <td><?php echo utf8_decode($data->id_producto); ?></td>
	    <td><?php echo utf8_decode($data->observacion); ?></td>
	    <td><?php echo utf8_decode($data->id_almacen); ?></td>
	    <td><?php echo utf8_decode($data->id_procedencia); ?></td>
	    <td><?php echo utf8_decode($data->id_categoria); ?></td>
	    <td><?php echo utf8_decode($data->id_detalle_producto); ?></td>
	    <td><?php echo utf8_decode($data->id_tipo_producto); ?></td>
	    <td><?php echo utf8_decode($data->id_unidad_medida); ?></td>
	    <td><?php echo utf8_decode($data->estado); ?></td>
	    <td><?php echo utf8_decode($data->column_temp); ?></td>
	</tr>
	<?php
		}
	?>
</table>