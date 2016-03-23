<script type="text/javascript">
$(function(){
	$("#edit_dolar_compra").mask("9.999");
	$("#edit_dolar_venta").mask("9.999");
	$("#edit_euro_compra").mask("9.999");
	$("#edit_euro_venta").mask("9.999");
});
</script>

<div id="contenedor" style="width:240px; height:205px;">
	<div id="tituloCont">Editar Tipo de Cambio</div>
	<div id="formFiltro" style="width:500px;">
		<?php 
			$existe = count($datosTC);
			if($existe <= 0){
				echo 'El Tipo de Cambio no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($datosTC as $TC){
					$edit_fecha_actual = array('name'=>'edit_fecha_actual','id'=>'edit_fecha_actual', 'value'=>$TC->fecha_actual, 'style'=>'width:70px','readonly'=> 'readonly');
					$edit_dolar_compra = array('name'=>'edit_dolar_compra','id'=>'edit_dolar_compra','maxlength'=>'5', 'value'=>$TC->dolar_compra, 'style'=>'width:50px');
					$edit_dolar_venta = array('name'=>'edit_dolar_venta','id'=>'edit_dolar_venta','maxlength'=>'5', 'value'=>$TC->dolar_venta, 'style'=>'width:50px');
					$edit_euro_compra = array('name'=>'edit_euro_compra','id'=>'edit_euro_compra','maxlength'=>'5', 'value'=>$TC->euro_compra, 'style'=>'width:50px');
					$edit_euro_venta = array('name'=>'edit_euro_venta','id'=>'edit_euro_venta','maxlength'=>'5', 'value'=>$TC->euro_venta, 'style'=>'width:50px');
			?>
	    		<tr>
					<td width="127">Fecha de Registro:</td>
					<td width="245"><?php echo form_input($edit_fecha_actual); ?></td>
				</tr>
	    		<tr>
					<td width="127">Compra Dólar:</td>
					<td width="245"><?php echo form_input($edit_dolar_compra); ?></td>
				</tr>
				<tr>
					<td width="127">Venta Dólar:</td>
					<td width="245"><?php echo form_input($edit_dolar_venta); ?></td>
				</tr>
	    		<tr>
					<td width="127">Compra Euro:</td>
					<td width="245"><?php echo form_input($edit_euro_compra); ?></td>
				</tr>
				<tr>
					<td width="127">Venta Euro:</td>
					<td width="245"><?php echo form_input($edit_euro_venta); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>