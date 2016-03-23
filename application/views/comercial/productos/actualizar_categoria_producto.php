<div id="contenedor" style="width:350px; height:100px;">
	<div id="tituloCont">Editar Categoría de Producto</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datoscatprod);
			if($existe <= 0){
				echo 'La Categoría de Producto no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($datoscatprod as $catprod){
				#Datos del Nombre de Máquina
				$editcatprod = array('name'=>'editcatprod','id'=>'editcatprod','maxlength'=>'20', 'style'=>'width:150px', 'value'=>$catprod->no_categoria);
			?>
	    		<tr>
					<td width="300" style="width: 570px;">Categoría de Producto:</td>
					<td width="300"><?php echo form_input($editcatprod); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>