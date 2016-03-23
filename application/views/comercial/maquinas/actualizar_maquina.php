<script type="text/javascript">
    $("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
</script>
<div id="contenedor" style="width:380px; height:145px;">
	<div id="tituloCont">Editar Máquina</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datosmaq);
			if($existe <= 0){
				echo 'La Máquina no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
		    	<?php
					$i=1;
					foreach($datosmaq as $maq){
						$editobser = array('name'=>'editobser','id'=>'editobser','maxlength'=>'50', 'value'=>$maq->observacion_maq);
						$editnombremaquina = array('name'=>'editnombremaquina','id'=>'editnombremaquina','maxlength'=>'100', 'value'=>$maq->nombre_maquina, 'style'=>'width:150px');
				?>
				<script type="text/javascript">
             		$("#editestado option[value='<?php echo $maq->id_estado_maquina;?>']").attr("selected",true);
	            </script>
	    		<tr>
					<td width="380">Nombre de la Máquina:</td>
					<td width="245"><?php echo form_input($editnombremaquina); ?></td>
				</tr>
	    		<tr>
	    			<td>Estado:</td>
	    			<td><?php echo form_dropdown('editestado', $editestado, '',"id='editestado' style='margin-left: 0px;'"); ?></td>
	    		</tr>
	    		<tr>
					<td width="300">Observación:</td>
					<td width="300"><?php echo form_input($editobser); ?></td>
				</tr>
				<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
	</div>
</div>