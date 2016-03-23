<div id="contenedor" style="width:360px; height:100px;">
	<div id="tituloCont">Editar Marca del Tipo de Máquina</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datosmarmaq);
			if($existe <= 0){
				echo 'La Marca de la Máquina no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($datosmarmaq as $marmaq){
				#Datos de la Marca de Máquina
				$editmarcamaq = array('name'=>'editmarcamaq','id'=>'editmarcamaq','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$marmaq->no_marca);
			?>
			<script type="text/javascript">
				$("#editnombremaq option[value='<?php echo $marmaq->id_nombre_maquina;?>']").attr("selected",true);
			</script>
				<tr>
					<td width="1300">Tipo Máquina:</td>
					<td width="300"><?php echo form_dropdown('editnombremaq', $listamaquina, '',"id='editnombremaq' style='width:120px;'"); ?></td>
				</tr>
	    		<tr>
					<td width="1300">Marca del Tipo de Máquina:</td>
					<td width="300"><?php echo form_input($editmarcamaq); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>