<div id="contenedor" style="width:320px; height:355px;">
	<div id="tituloCont">Editar Usuario</div>
	<div id="formFiltro">
		<?php 
			$existe = count($datosuser);
			if($existe <= 0){
				echo 'El Usuario no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px">
	    	<table>
	    	<?php
				$i=1;
				$jsContrasena = 'onkeyup="muestra_seguridad_clave_editar(this.value, this.form)"';
				foreach($datosuser as $user){
				#Datos del USUARIO
					$editnombres = array('name'=>'editnombres','id'=>'editnombres','maxlength'=>'20', 'value'=>$user->no_usuario,'style'=>'width:140px');
					$editemail = array('name'=>'editemail','id'=>'editemail','maxlength'=>'50', 'value'=>$user->correo_electronico,'style'=>'width:140px');
					$editapellido = array('name'=>'editapellido','id'=>'editapellido','maxlength'=>'20', 'value'=>$user->ape_paterno,'style'=>'width:140px');
					$editusuario = array('name'=>'editusuario','id'=>'editusuario','maxlength'=>'10', 'value'=>$user->tx_usuario ,'style'=>'width:140px');
					$editestado = array('true'=>'Activo', 'false'=>'Inactivo',);
					$editcontrasena = array('name'=>'editcontrasena','id'=>'editcontrasena','maxlength'=>'12','style'=>'width:140px');
					$editrptcontrasena = array('name'=>'editrptcontrasena','id'=>'editrptcontrasena','maxlength'=>'12','minlength'=>'6','style'=>'width:140px');
			?>
				<script type="text/javascript">
             		$("#editipo option[value='<?php echo $user->id_tipo_usuario;?>']").attr("selected",true);
             		$("#editestado option[value='<?php echo $user->fl_estado;?>']").attr("selected",true);
             		$("#editalmacen option[value='<?php echo $user->id_almacen;?>']").attr("selected",true);
             		var tipo = $('#editipo').val();
					if(tipo == 1){
						$("#editalmacen").css('visibility','hidden');
					}

				    //Deshabilitar la opción de almacen si el usuario a registrar es un Administrador
				    $("#editipo").change(function(){
				        $("#editipo option:selected").each(function(){
				          tipousuario = $('#editipo').val();
				          if (tipousuario == 1){
				            $("#editalmacen").css('visibility','hidden');
				          }else{
				            $("#editalmacen").css('visibility','visible');
				          }
				        });
				    });
				    
	            </script>
				<tr>
					<td width="153" height="30">Nombres:</td>
					<td width="200" height="30"><?php echo form_input($editnombres); ?></td>
  				</tr>
	    		<tr>
					<td width="153" height="30">Apellido:</td>
					<td width="200" height="30"><?php echo form_input($editapellido); ?></td>
				</tr>
	    		<tr>
					<td width="153" height="30">Tipo:</td>
					<td width="200" height="30"><?php echo form_dropdown('editipo', $listatipo, '','id="editipo" style="width:148px;"'); ?></td>
				</tr>
	    		<tr>
 	    			<td height="30">Estado:</td>
 	    			<td height="30"><?php echo form_dropdown('editestado', $editestado, '',"id='editestado'"); ?></td>
		 	    </tr>
	    		<tr>
					<td width="153" height="30">Almacén:</td>
					<td width="200" height="30"><?php echo form_dropdown('editalmacen', $almacen, '',"id='editalmacen'"); ?></td>
				</tr>
				<tr>
                	<td width="168" height="30">E-mail:</td>
                	<td width="200" height="30"><?php echo form_input($editemail);?></td>
            	</tr>
				<tr>
 	    			<td height="30">Usuario:</td>
 	    			<td width="200" height="30"><?php echo form_input($editusuario); ?></td>
		 	    </tr>
		 	    <tr>
 	    			<td height="30">Contraseña:</td>
 	    			<td height="30"><?php echo form_password($editcontrasena,'',$jsContrasena); ?></td>
 	    		</tr>
 	    		<tr>
				    <td height="30">Re-contraseña:</td>
				    <td height="30"><?php echo form_password($editrptcontrasena,'',$jsContrasena); ?></td>
				</tr>
 	    		<tr>
			    	<td>&nbsp;</td>
			    	<td><table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
				      <tr>
				        <td id="valSeguridadEdit">Escriba una contraseña...</td>
				      </tr>
				      <tr>
				        <td><progress id="securityPassEdit" value="0" max="100" style="width:150px"></progress></td>
				      </tr></table>
				    </td>
				</tr>
			<?php }?>
	    	</table>
	 	</form>
	 	<?php } ?>
		</div>
	</div>