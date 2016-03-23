<div id="contenedor" style="width:360px; height:100px;">
	<div id="tituloCont">Editar Tipo de Producto</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datostipprod);
			if($existe <= 0){
				echo 'El Tipo de Producto no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
		    	<?php
					$i=1;
					foreach($datostipprod as $tipprod){
						$edittipprod = array('name'=>'edittipprod','id'=>'edittipprod','maxlength'=>'30', 'style'=>'width:150px', 'value'=>$tipprod->no_tipo_producto);
				?>
		    		<tr>
						<td width="200" height="40" style="padding-bottom: 4px;">Tipo de Producto:</td>
						<td width="300" height="40"><?php echo form_input($edittipprod); ?></td>
					</tr>
				<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
	</div>
</div>