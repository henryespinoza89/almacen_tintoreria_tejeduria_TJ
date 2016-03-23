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
	    <td sort="nomcomp" width="150" height="30"><?php echo utf8_decode('id_detalle_ing_prod'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('unidades'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('id_detalle_producto'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('precio'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_ingreso_producto'); ?></td>
	</tr>
	<?php 
		foreach($t_detalle_ingreso_producto as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_detalle_ing_prod); ?></td>
	    <td><?php echo utf8_decode($data->unidades); ?></td>
	    <td><?php echo utf8_decode($data->id_detalle_producto); ?></td>
	    <td><?php echo utf8_decode($data->precio); ?></td>
	    <td><?php echo utf8_decode($data->id_ingreso_producto); ?></td>
	</tr>
	<?php
		}
	?>
</table>