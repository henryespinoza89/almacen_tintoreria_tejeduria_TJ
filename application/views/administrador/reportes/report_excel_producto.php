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
	    <td sort="idvend" width="107" height="50" >ID Producto </td>
	    <td sort="nomcomp" width="213"><?php echo utf8_decode('Nombre o Descripción'); ?></td>
	    <td sort="nomcomp" width="213"><?php echo utf8_decode('Categoría'); ?></td>
	    <td sort="avpros" width="183"><?php echo utf8_decode('Procencia'); ?></td>
	    <td sort="avpros" width="193"><?php echo utf8_decode('Unidad de Medida'); ?></td>
	</tr>
	<?php 
		foreach($producto as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_producto); ?></td>
	    <td><?php echo utf8_decode($data->no_producto); ?></td>
	    <td><?php echo utf8_decode($data->no_categoria); ?></td>
	    <td><?php echo utf8_decode($data->no_procedencia); ?></td>
	    <td><?php echo utf8_decode($data->unidad_medida); ?></td>
	</tr>
	<?php }?>
</table>