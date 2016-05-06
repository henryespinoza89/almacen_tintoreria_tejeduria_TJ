<script type="text/javascript">
  $(function(){
  	// Autocompletar ubicacion de productos
    $("#edit_ubicacion").autocomplete({
      source: function (request, respond) {
        $.post("<?php echo base_url('comercial/traer_ubicacion_producto_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
        function (response) {
          respond(response);
        }, 'json');
      }, select: function (event, ui) {
        var selectedObj = ui.item;
        $("#edit_ubicacion").val(selectedObj.nombre_ubicacion);
      }
    });
  });
</script>
<div id="contenedor" style="width:450px; height:250px;">
	<div id="tituloCont" style="margin-bottom: 10px;">Editar Producto</div>
	<div id="formFiltro" style="width:500px;">
		<?php 
			$existe = count($datosprod);
			if($existe <= 0){
				echo 'El Producto no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				foreach($datosprod as $prod){
				$editnombreprod = array('name'=>'editnombreprod','id'=>'editnombreprod','maxlength'=>'100', 'value'=>$prod->no_producto, 'style'=>'width:300px');
				$edit_ubicacion = array('name'=>'edit_ubicacion','id'=>'edit_ubicacion','maxlength'=>'100', 'value'=>$prod->nombre_ubicacion, 'style'=>'width:150px');
				$editobser = array('name'=>'editobser','id'=>'editobser','maxlength'=>'60', 'value'=>$prod->observacion, 'style'=>'width:150px');
			?>
				<script type="text/javascript">
             		$("#editcat option[value='<?php echo $prod->id_categoria;?>']").attr("selected",true);
             		$("#editprocedencia option[value='<?php echo $prod->id_procedencia;?>']").attr("selected",true);
             		$("#edittipoprod option[value='<?php echo $prod->id_tipo_producto;?>']").attr("selected",true);
             		$("#editunid_med option[value='<?php echo $prod->id_unidad_medida;?>']").attr("selected",true);
	            </script>
	    		<tr>
					<td width="127">Descripción:</td>
					<td width="245"><?php echo form_input($editnombreprod); ?></td>
				</tr>
				<tr>
					<td width="127">Ubicación:</td>
					<td width="245"><?php echo form_input($edit_ubicacion); ?></td>
				</tr>
	    		<tr>
					<td width="127">Categoria:</td>
					<td><?php echo form_dropdown('editcat', $listacat, '',"id='editcat' style='margin-left: 0px;'"); ?></td>
				</tr>
				<tr>
					<td width="127">Tipo de Producto:</td>
					<td>
						<?php echo form_dropdown('edittipoprod', $listatipop, '',"id='edittipoprod' style='margin-left: 0px;'"); ?>
					</td>
				</tr>
	    		<tr>
					<td width="127">Procedencia:</td>
					<td><?php echo form_dropdown('editprocedencia', $listaproce, '',"id='editprocedencia' style='margin-left: 0px;'"); ?></td>
				</tr>
				<tr>
					<td width="127">Unidad de Medida:</td>
					<td width="245"><?php echo form_dropdown('editunid_med', $listaunimed, '',"id='editunid_med' style='margin-left: 0px;'"); ?></td>
				</tr>
	    		<tr>
					<td width="127">Observación:</td>
					<td width="245"><?php echo form_input($editobser); ?></td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>