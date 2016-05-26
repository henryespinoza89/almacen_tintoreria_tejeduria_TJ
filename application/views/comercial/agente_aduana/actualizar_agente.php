<div id="contenedor" style="width:380px; height:90px;">
	<div id="tituloCont">Editar Datos del Agente Aduanero</div>
	<div id="formFiltro">
		<?php 
			$existe = count($agente);
			if($existe <= 0){
				echo 'El Agente Aduanero no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($agente as $agenteaduanero){
				#Datos del Nombre de Máquina
				$editnombreagente = array('name'=>'editnombreagente','id'=>'editnombreagente','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$agenteaduanero->no_agente);
			?>
	    		<tr>
					<td width="500">Nombre del Agente Aduanero:</td>
					<td width="300"><?php echo form_input($editnombreagente); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>