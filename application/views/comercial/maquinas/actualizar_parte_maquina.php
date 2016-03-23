<script type="text/javascript">
  $(function(){
  	<?php 
	    if ($this->input->post('edit_maquina')){
	      	$selected_edit_maquina =  (int)$this->input->post('edit_maquina');
	    }else{  $selected_edit_maquina = "";
	?>
	    $("#edit_maquina").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
	<?php 
	    } 
	?>
  });
</script>
<div id="contenedor" style="width:380px; height:120px;">
	<div id="tituloCont">Editar Parte de Máquina</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datos_parte_maq);
			if($existe <= 0){
				echo 'La Parte de la Máquina no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
		    	<?php
					$i=1;
					foreach($datos_parte_maq as $maq){
						$edit_parte_maquina = array('name'=>'edit_parte_maquina','id'=>'edit_parte_maquina','maxlength'=>'100', 'value'=>$maq->nombre_parte_maquina, 'style'=>'width:180px');
				?>
				<script type="text/javascript">
					$("#edit_maquina option[value='<?php echo $maq->id_maquina;?>']").attr("selected",true);
				</script>
				<tr>
					<td width="1300" height="30">Máquina:</td>
					<td width="300" height="30"><?php echo form_dropdown('edit_maquina', $lista_maquina, $selected_edit_maquina,"id='edit_maquina' style='width:120px;margin-left: 0px;'"); ?></td>
				</tr>
	    		<tr>
					<td width="150" style="padding-bottom: 4px;">Parte de la Máquina:</td>
					<td width="245"><?php echo form_input($edit_parte_maquina); ?></td>
				</tr>
				<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
	</div>
</div>
