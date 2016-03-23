<div id="contenedor" style="width:400px; height:60px;">
	<div id="tituloCont">Editar Datos del Tipo de Comprobante</div>
	<div id="formFiltro">
		<?php 
			$existe = count($comprobante);
			if($existe <= 0){
				echo 'El Tipo de Comprobante no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($comprobante as $getComprobante){
				#Datos del Nombre de Máquina
				$editnombrecomprobante = array('name'=>'editnombrecomprobante','id'=>'editnombrecomprobante','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$getComprobante->no_comprobante);
			?>
	    		<tr>
					<td width="800">Nombre del Tipo de Comprobante:</td>
					<td width="300"><?php echo form_input($editnombrecomprobante); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>