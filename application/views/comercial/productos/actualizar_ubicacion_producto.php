<div id="contenedor" style="width:320px; height:100px;">
	<div id="tituloCont">Actualizar ubicación</div>
	<div id="formFiltro" style="width:500px;">
		<?php 
			$existe = count($ubicacion_producto_data);
			if($existe <= 0){
				echo 'El Código de Rendimiento no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($ubicacion_producto_data as $data){
				$edit_ubicacion = array('name'=>'edit_ubicacion','id'=>'edit_ubicacion', 'value'=>$data->nombre_ubicacion, 'style'=>'width:150px');
			?>
				<tr>
                    <td width="150" height="30" style="padding-bottom: 5px;">Ubicación del Producto:</td>
                    <td width="264" height="30"><?php echo form_input($edit_ubicacion);?></td>
                </tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
	</div>
</div>