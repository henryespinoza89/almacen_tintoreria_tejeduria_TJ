<?php
	// Exportamos a Excel los resultados
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=ReporteListadoProductos.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<?php
	$sumatoria = 0;
?>
<table width="1229" border="0" cellspacing="0" cellpadding="0" id="listaVisitadores">
	<tr style="text-align:center; font-weight:bold;vertical-align: middle;border: 1px solid #005197;background-color: #0072C6;color: white;font-size: 12px;">
	    <td sort="idvend" width="107" height="30" >ID Producto </td>
	    <td sort="nomcomp" width="213"><?php echo utf8_decode('Nombre o Descripción'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('Área'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('Categoría'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('Tipo de Producto'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('Procencia'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('Unidad de Medida'); ?></td>
	    <td sort="avpros" width="130"><?php echo utf8_decode('Stock'); ?></td>
	    <td sort="avpros" width="130"><?php echo utf8_decode('Precio Unitario'); ?></td>
	</tr>
	<?php 
		foreach($producto as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_producto); ?></td>
	    <td><?php echo utf8_decode($data->no_producto); ?></td>
	    <td><?php echo utf8_decode($data->no_area); ?></td>
	    <td><?php echo utf8_decode($data->no_categoria); ?></td>
	    <td><?php echo utf8_decode($data->no_tipo_producto); ?></td>
	    <td><?php echo utf8_decode($data->no_procedencia); ?></td>
	    <td><?php echo utf8_decode($data->nom_uni_med); ?></td>
	    <td><?php echo number_format($data->stock,2,'.',','); ?></td>
	    <td><?php echo number_format($data->precio_unitario,2,'.',','); ?></td>
	</tr>
	<?php
		$sumatoria = $sumatoria + ($data->precio_unitario*$data->stock);
		}
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
		<td height="25"><?php echo "" ?></td>
	    <td><?php echo "" ?></td>
	    <td><?php echo "" ?></td>
	    <td><?php echo "" ?></td>
	    <td><?php echo "" ?></td>
	    <td><?php echo "" ?></td>
	    <td><?php echo "" ?></td>
	    <td style="background-color: #0072C6;color: white"><?php echo "TOTAL :" ?></td>
	    <td><?php echo number_format($sumatoria,2,'.',','); ?></td>
	</tr>
</table>