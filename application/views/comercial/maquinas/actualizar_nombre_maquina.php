<div id="contenedor" style="width:350px; height:60px;">
	<div id="tituloCont">Editar Nombre Máquina</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datosnommaq);
			if($existe <= 0){
				echo 'El Nombre de la Máquina no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($datosnommaq as $nommaq){
				#Datos del Nombre de Máquina
				$editnombremaq = array('name'=>'editnombremaq','id'=>'editnombremaq','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$nommaq->nombre_maquina);
			?>
	    		<tr>
					<td width="300">Nombre de Máquina:</td>
					<td width="300"><?php echo form_input($editnombremaq); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>