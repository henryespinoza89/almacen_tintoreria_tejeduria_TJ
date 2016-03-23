<?php
  $file = array('name'=>'file','id'=>'file','maxlength'=>'20', 'style'=>'width:300px;padding-left: 0px;', 'class'=>'required', 'type'=>'file');
?>


<script type="text/javascript">
  $(function(){


    $("#export_excel").click(function(){
      url = '<?php echo base_url(); ?>administrador/export_tabla_saldos_iniciales';
      $(location).attr('href',url);
    });

    /* ------------ VALIDACIÓN DE DATOS SI EXISTE EL ID DEL PRODUCTO ------------ */
    <?php if(!empty($respuesta_registro_satisfactorio)){ ?>
      var fila_producto = "<?php echo $respuesta_registro_satisfactorio; ?>" ;
    <?php } ?>

    <?php if(!empty($respuesta_registro_satisfactorio)){ ?>
      $("#error_respuesta_registro_satisfactorio").html('<strong>! Se realizo satisfactoriamente el Registro de '+ fila_producto + ' Filas !</strong>').dialog({
        modal: true,position: 'center',width: 490,height: 140, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
        buttons: { Ok: function(){
          window.location.href="<?php echo base_url();?>comercial/gestion_saldos_iniciales";
        }}
      });
    <?php } ?>
    /* ------------ FIN DE VALIDACIÓN SI EXISTE EL ID DEL PRODUCTO ------------ */
        
  });



</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Exportar Tabla saldos_inicales</div>
    <div id="formFiltro">
      <div id="options_productos">
      <form id="formulario" action="<?php echo base_url('administrador/insert_tabla_saldos_iniciales');?>" enctype="multipart/form-data" method="post" style="background: whitesmoke;padding-left: 15px;padding-top: 12px;margin-top: 0px;">
        <input name="export_excel" type="button" id="export_excel" value="Exportar a Excel" style="padding-bottom:3px; padding-top:3px; background-color: #0B610B; border-radius:6px;width: 155px;margin-top: 15px;" />
          <table>
            <tr>
                <td width="129" height="30">Seleccione el Archivo a subir:</td>
                <td width="194" height="30" style="padding-top: 5px;"><?php echo form_input($file);?></td>
            </tr>
            <input name="registrar_factura_masiva" type="submit" id="registrar_factura_masiva"  value="Levantar Interfaz" style="border-radius: 0px;margin-bottom: 6px;height: 24px;border-radius: 6px;" />
          </table>
        </form>
      </div>
    </div>
  </div>

  <?php if(!empty($respuesta_registro_satisfactorio)){ ?>
    <div id="error_respuesta_registro_satisfactorio"></div>
  <?php } ?>

    