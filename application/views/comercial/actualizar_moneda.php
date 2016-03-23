<div id="contenedor" style="width:350px; height:90px;">
	<div id="tituloCont">Editar Datos del Tipo de Moneda</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datosmoneda);
			if($existe <= 0){
				echo 'El Tipo de Moneda no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($datosmoneda as $moneda){
				#Datos del Nombre de Máquina
				$editnombremon = array('name'=>'editnombremon','id'=>'editnombremon','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$moneda->no_moneda);
				$editsimbolomon = array('name'=>'editsimbolomon','id'=>'editsimbolomon','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$moneda->simbolo_mon);
			?>
	    		<tr>
					<td width="300">Nombre de Moneda:</td>
					<td width="300"><?php echo form_input($editnombremon); ?></td>
				</tr>
				<tr>
					<td width="300">Símbolo de Moneda:</td>
					<td width="300"><?php echo form_input($editsimbolomon); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>