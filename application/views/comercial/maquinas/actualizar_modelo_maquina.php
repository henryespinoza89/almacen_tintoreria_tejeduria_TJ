<div id="contenedor" style="width:360px; height:100px;">
	<div id="tituloCont">Editar Modelo de Máquina</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datosmodmaq);
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
					foreach($datosmodmaq as $modmaq){
					#Datos de la Marca de Máquina
					$editmodelomaq = array('name'=>'editmodelomaq','id'=>'editmodelomaq','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$modmaq->no_modelo);
				?>
				<script type="text/javascript">
					$("#editmarcamaq option[value='<?php echo $modmaq->id_marca_maquina;?>']").attr("selected",true);
				</script>
					<tr>
						<td width="332">Marca de Máquina:</td>
						<td width="325"><?php echo form_dropdown('editmarcamaq', $listamarca, '',"id='editmarcamaq' style='width:120px;'"); ?></td>
					</tr>
		    		<tr>
						<td width="332">Modelo de Máquina:</td>
						<td width="325"><?php echo form_input($editmodelomaq); ?></td>
					</tr>
				<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>