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
	    <td sort="nomcomp" width="150" height="30"><?php echo utf8_decode('id_tipo_cambio'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('fecha_actual'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('dolar_compra'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('dolar_venta'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('euro_compra'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('euro_venta'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('fr_compra'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('fr_venta'); ?></td>
	</tr>
	<?php 
		foreach($t_tipo_cambio as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_tipo_cambio); ?></td>
	    <td><?php echo utf8_decode($data->fecha_actual); ?></td>
	    <td><?php echo utf8_decode($data->dolar_compra); ?></td>
	    <td><?php echo utf8_decode($data->dolar_venta); ?></td>
	    <td><?php echo utf8_decode($data->euro_compra); ?></td>
	    <td><?php echo utf8_decode($data->euro_venta); ?></td>
	    <td><?php echo utf8_decode($data->fr_compra); ?></td>
	    <td><?php echo utf8_decode($data->fr_venta); ?></td>
	</tr>
	<?php
		}
	?>
</table>