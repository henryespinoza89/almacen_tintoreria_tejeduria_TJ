<script type="text/javascript">
	$("#edit_ruc").validCampoFranz('0123456789');
	$("#edit_tel1").validCampoFranz('0123456789- ');            	
</script>
<div id="contenedor" style="width:410px; height:175px;">
	<div id="tituloCont">Editar Proveedor</div>
	<div id="formFiltro" style="width:500px;">
		<?php 
			$existe = count($datosprov);
			if($existe <= 0){
				echo 'El Proveedor no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($datosprov as $prov){
				#Datos del proveedor
				$edit_rz = array('name'=>'edit_rz','id'=>'edit_rz','maxlength'=>'50', 'value'=>$prov->razon_social, 'style'=>'width:280px');
				$edit_ruc = array('name'=>'edit_ruc','id'=>'edit_ruc','maxlength'=>'11', 'value'=>$prov->ruc, 'style'=>'width:180px');
				$edit_pais = array('name'=>'edit_pais','id'=>'edit_pais','maxlength'=>'40', 'value'=>$prov->pais, 'style'=>'width:180px');
				$edit_direc = array('name'=>'edit_direc','id'=>'edit_direc','maxlength'=>'100', 'value'=>$prov->direccion, 'style'=>'width:280px');
				$edit_tel1 = array('name'=>'edit_tel1','id'=>'edit_tel1','maxlength'=>'14', 'value'=>$prov->telefono1, 'style'=>'width:180px');
			?>
				<tr>
					<td width="120">Razón Social: (*)</td>
					<td width="232"><?php echo form_input($edit_rz); ?></td>
			  	</tr>
	    		<tr>
					<td width="120">RUC: (*)</td>
					<td width="232"><?php echo form_input($edit_ruc); ?></td>
				</tr>
	    		<tr>
					<td width="120">País: (*)</td>
					<td width="232"><?php echo form_input($edit_pais); ?></td>
				</tr>
				<tr>
	    			<td width="120">Dirección: (*)</td>
	    			<td width="232"><?php echo form_input($edit_direc); ?></td>
	    		</tr>								
	    		<tr>
	    			<td width="120">Teléfono:</td>
	    			<td width="232"><?php echo form_input($edit_tel1); ?></td>
	    		</tr>				
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>