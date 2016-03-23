<?php
	//Exportamos a Excel los resultados
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=ReporteListadoProveedores.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<!-- TABLA LISTA DE VISITADORES -->
<table width="1229" border="0" cellspacing="0" cellpadding="0" id="listaVisitadores">
	<tr style="text-align:center; font-weight:bold;vertical-align: middle;border: 1px solid #005197;background-color: #0072C6;color: white;font-size: 12px;">
	    <td sort="nomcomp" width="150" height="30"><?php echo utf8_decode('id_proveedor'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('razon_social'); ?></td>
	    <td sort="nomcomp" width="150"><?php echo utf8_decode('ruc'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('pais'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('departamento'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('provincia'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('distrito'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('direccion'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('referencia'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('contacto'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('cargo'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('email'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('telefono1'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('telefono2'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('fax'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('web'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('id_almacen'); ?></td>
	    <td sort="avpros" width="150"><?php echo utf8_decode('fe_registro'); ?></td>
	</tr>
	<?php 
		foreach($t_proveedor as $data){
	?>
	<tr style="text-align:center;vertical-align: middle;border: 1px solid #005197;font-size: 10px;">
	    <td height="25"><?php echo utf8_decode($data->id_proveedor); ?></td>
	    <td><?php echo utf8_decode($data->razon_social); ?></td>
	    <td><?php echo utf8_decode($data->ruc); ?></td>
	    <td><?php echo utf8_decode($data->pais); ?></td>
	    <td><?php echo utf8_decode($data->departamento); ?></td>
	    <td><?php echo utf8_decode($data->provincia); ?></td>
	    <td><?php echo utf8_decode($data->distrito); ?></td>
	    <td><?php echo utf8_decode($data->direccion); ?></td>
	    <td><?php echo utf8_decode($data->referencia); ?></td>
	    <td><?php echo utf8_decode($data->contacto); ?></td>
	    <td><?php echo utf8_decode($data->cargo); ?></td>
	    <td><?php echo utf8_decode($data->email); ?></td>
	    <td><?php echo utf8_decode($data->telefono1); ?></td>
	    <td><?php echo utf8_decode($data->telefono2); ?></td>
	    <td><?php echo utf8_decode($data->fax); ?></td>
	    <td><?php echo utf8_decode($data->web); ?></td>
	    <td><?php echo utf8_decode($data->id_almacen); ?></td>
	    <td><?php echo utf8_decode($data->fe_registro); ?></td>
	</tr>
	<?php
		}
	?>
</table>