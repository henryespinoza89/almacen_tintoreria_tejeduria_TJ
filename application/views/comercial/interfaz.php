<?php
  $file = array('name'=>'file','id'=>'file','maxlength'=>'20', 'style'=>'width:300px', 'class'=>'required', 'type'=>'file');
?>

<script type="text/javascript">
  $(function(){



</script>

</head>
<body>
  <div id="contenedor" style="padding-top: 10px;">
    <div id="tituloCont">Gestión de Interfaz - Crudo</div>
    <div id="formFiltro">
        
        <form id="formulario" action="<?php echo base_url('comercial/guardar_informacion_productos');?>" enctype="multipart/form-data" method="post">
          <table width="515" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="187">Seleccione el Archivo a subir:</td>
              <td width="194" style="padding-top: 5px;"><?php echo form_input($file);?></td>
              <td><input id="" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" type="text" /></td>
              <td width="134" align="left"><input name="submit" type="submit" id="submit" value="Subir Archivo" /></td>
            </tr>
          </table>
        </form>
    </div>
  </div>
  <div id="mdlEditarColor"></div>
  <div id="finregistro"></div>
  <div id="modalerror"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarcolor');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Color">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar este Color?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>

  <?php if(!empty($respuesta_codigo)){ ?>
    <div id="error_codigo"></div>
  <?php } ?>

  <?php if(!empty($respuesta_nombre)){ ?>
    <div id="error_nombre"></div>
  <?php } ?>

  <?php if(!empty($respuesta_validacion)){ ?>
    <div id="error_validacion"></div>
  <?php } ?>

