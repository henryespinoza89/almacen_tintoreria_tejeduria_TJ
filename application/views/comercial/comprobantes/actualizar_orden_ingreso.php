<script type="text/javascript">
	$(function(){
		$("#edit_precio_unitario").validCampoFranz('0123456789.');
	});
</script>

<div id="contenedor" style="width:500px; height:160px;">
	<div id="tituloCont" style="margin-bottom: 10px;">Editar Orden de Ingreso</div>
	<div id="formFiltro" style="width:500px;">
		<?php 
			$existe = count($ordeningreso);
			if($existe <= 0){
				echo 'La Orden de Ingreso no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px;margin-top: 25px;">
	    	<table>
	    	<?php
				$i=1;
				foreach($ordeningreso as $row){
				$editnombreprod = array('name'=>'editnombreprod','id'=>'editnombreprod','maxlength'=>'100', 'value'=>$row->no_producto, 'style'=>'width:300px', 'readonly'=> 'readonly');
				$edit_cantidad_ingreso = array('name'=>'edit_cantidad_ingreso','id'=>'edit_cantidad_ingreso','maxlength'=>'100', 'value'=>$row->cantidad_ingreso, 'style'=>'width:150px', 'readonly'=> 'readonly');
				$edit_precio_unitario = array('name'=>'edit_precio_unitario','id'=>'edit_precio_unitario','maxlength'=>'60', 'value'=>$row->precio_unitario_actual, 'style'=>'width:150px');
			?>
	    		<tr>
					<td width="150">Nombre del Producto:</td>
					<td width="245"><?php echo form_input($editnombreprod); ?></td>
				</tr>
				<tr>
					<td width="127">Cantidad de Ingreso:</td>
					<td width="245"><?php echo form_input($edit_cantidad_ingreso); ?></td>
				</tr>
	    		<tr>
					<td width="127">Precio Unitario:</td>
					<td width="245"><?php echo form_input($edit_precio_unitario); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>