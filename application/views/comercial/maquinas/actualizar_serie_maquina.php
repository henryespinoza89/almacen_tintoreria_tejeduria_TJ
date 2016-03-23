<div id="contenedor" style="width:360px; height:100px;">
	<div id="tituloCont">Editar Modelo de Máquina</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datossermaq);
			if($existe <= 0){
				echo 'El Modelo de la Máquina no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
		    	<?php
					$i=1;
					foreach($datossermaq as $modmaq){
					#Datos de la Marca de Máquina
					$editseriemaq = array('name'=>'editseriemaq','id'=>'editseriemaq','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$modmaq->no_serie);
				?>
				<script type="text/javascript">
					$("#editmodelomaq option[value='<?php echo $modmaq->id_modelo_maquina;?>']").attr("selected",true);
				</script>
					<tr>
						<td width="332">Modelo de Máquina:</td>
						<td width="325"><?php echo form_dropdown('editmodelomaq', $listamodelo, '',"id='editmodelomaq' style='width:120px;'"); ?></td>
					</tr>
		    		<tr>
						<td width="332">Serie de Máquina:</td>
						<td width="325"><?php echo form_input($editseriemaq); ?></td>
					</tr>
				<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>