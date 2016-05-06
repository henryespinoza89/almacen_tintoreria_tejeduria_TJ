<?php
  $file = array('name'=>'file','id'=>'file','maxlength'=>'20', 'style'=>'width:300px', 'class'=>'required', 'type'=>'file');
?>
<script type="text/javascript">
  $(function(){

    <?php if(!empty($respuesta_validacion_producto_invalido)){ ?>
      var producto_erroneo = "<?php echo $respuesta_validacion_producto_invalido; ?>" ;
    <?php } ?>

    <?php if(!empty($respuesta_validacion_producto_invalido)){ ?>
      $("#error_respuesta_validacion_producto_invalido").html('<strong>! Se encontro un Error en el Producto en la Fila '+ producto_erroneo + ' !<br> Verificar el Archivo Excel/Csv y volver a Cargar la Data.</strong>').dialog({
        modal: true,position: 'center',width: 500,height: 138, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
        buttons: { Ok: function(){
          $(this).dialog('close');
        }}
      });
    <?php } ?>

    /* Ventana Modal para Registrar el Código de Hilado */
    $(".newprospect").click(function() { //activacion de ventana modal
      $("#mdlNuevaUbicacion" ).dialog({  //declaracion de ventana modal
        modal: true,resizable: false,show: "blind",hide: "blind",position: 'center',width: 405,height: 220,draggable: false,closeOnEscape: false, //Aumenta el marco general
        buttons: {
        Registrar: function() {
            var ubicacion_producto_modal = $('#ubicacion_producto_modal').val();
            if(ubicacion_producto_modal == ''){
              sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
            }else{
              //REGISTRO
              var dataString = 'ubicacion_producto_modal='+ubicacion_producto_modal+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/save_ubicacion_producto/",
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    swal({ title: "La Ubicación del Producto ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                    $("#mdlNuevaUbicacion").dialog("close");
                    $('#ubicacion_producto_modal').val('');
                  }else{
                    sweetAlert(msg, "", "error");
                  }
                }
              });
            }
          },
          Cancelar: function(){
            $("#mdlNuevaUbicacion").dialog("close");
          }
          }
      });
    });
    
    $('#listar_ubicacion_producto').DataTable();

  });
  
  /* Funcion para recargar la pagina */
  function resetear(){
    window.location.href="<?php echo base_url();?>comercial/gestion_ubicacion_productos";
  }

  // Editar Tejido
  function edit_ubicacion_producto(id_ubicacion){
    var urlMaq = '<?php echo base_url();?>comercial/editar_ubicacion_producto/'+id_ubicacion;
    $("#mdlEditarRendimiento").load(urlMaq).dialog({
      modal: true, position: 'center', width: 360, height: 230, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {
        var edit_ubicacion = $('#edit_ubicacion').val();
        if(edit_ubicacion == ''){
          sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
        }else{
          var dataString = 'edit_ubicacion='+edit_ubicacion+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>comercial/update_ubicacion_producto/"+id_ubicacion,
            data: dataString,
            success: function(msg){
              if(msg == 'ok'){
                swal({ title: "La Ubicación del Producto ha sido actualizada con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                $("#mdlEditarRendimiento").dialog("close");
              }else{
                sweetAlert(msg, "", "error");
              }
            }
          });
        }
      },
      Cancelar: function(){
        $("#mdlEditarRendimiento").dialog("close");
      }
      }
    });
  }

  function delete_ubicacion_producto(id_ubicacion){
    swal({   
      title: "Estas seguro?",
      text: "No se podrá recuperar esta información!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Si, eliminar!",
      closeOnConfirm: false 
    },
    function(){
      var dataString = 'id_ubicacion='+id_ubicacion+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/eliminar_ubicacion_producto/",
        data: dataString,
        success: function(msg){
          if(msg == 'ok'){
            swal("Eliminado!", "La ubicación de productos ha sido eliminada.", "success");
          }else if(msg == 'dont_delete'){
            sweetAlert("No se puede eliminar la ubicación", "Verificar que productos han sido registrados con esta ubicación.", "error");
          }
        }
      });
    });
  }

  

</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Ubicación de Productos</div>
    <div id="formFiltro">
        <!--
        <form id="formulario" action="<?php // echo base_url('comercial/registrar_ubicacion_masiva');?>" enctype="multipart/form-data" method="post" style="padding-bottom: 13px;float: left;">
          <table width="625" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="187">Seleccione el Archivo a subir:</td>
              <td width="194" style="padding-top: 5px;"><?php // echo form_input($file);?></td>
              <td><input id="" name="<?php // echo $this->security->get_csrf_token_name(); ?>" value="<?php // echo $this->security->get_csrf_hash(); ?>" type="hidden" /></td>
              <td width="134" align="left"><input name="submit" type="submit" id="submit" value="Subir Archivo" /></td>
            </tr>
          </table>
        </form>
        -->
        <!--
        <form id="formulario" action="<?php // echo base_url('comercial/registrar_productos_opcion_masiva');?>" enctype="multipart/form-data" method="post" style="padding-bottom: 13px;float: left;">
          <table width="625" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="187">Seleccione el Archivo a subir:</td>
              <td width="194" style="padding-top: 5px;"><?php // echo form_input($file);?></td>
              <td><input id="" name="<?php // echo $this->security->get_csrf_token_name(); ?>" value="<?php // echo $this->security->get_csrf_hash(); ?>" type="hidden" /></td>
              <td width="134" align="left"><input name="submit" type="submit" id="submit" value="Subir Archivo" /></td>
            </tr>
          </table>
        </form>
        -->
        <div id="options_productos">
          <div class="newprospect" style="width: 150px;">NUEVA UBICACIÓN</div>
        </div>
    </div>
    <!--<div id="tituloCont" style="border-bottom-style:none;">Lista</div>-->
    <!--Iniciar listar-->
    <?php 
      $existe = count($ubicacion_producto_data);
      if($existe <= 0){
        echo 'No existen Ubicaciones de Productos registrados en el Sistema.';
      }
      else
      {
    ?>
    <table border="0" cellspacing="0" cellpadding="0" id="listar_ubicacion_producto" style="float: left;width: 700px;" class="table table-hover table-striped">
      <thead>
        <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
          <td sort="idproducto" width="60" height="27">Item</td>
          <td sort="nombreprod" width="480">Ubicación del Producto</td>
          <td width="20" style="background-image: none;">&nbsp;</td>
          <td width="20" style="background-image: none;">&nbsp;</td>
        </tr>
      </thead>
      <?php 
        $i=1;
        foreach($ubicacion_producto_data as $data){ ?>  
      <tr class="contentTable" style="font-size: 12px;">
        <td height="27" style="vertical-align: middle;"><?php echo str_pad($i,4,0, STR_PAD_LEFT);?></td>
        <td style="vertical-align: middle;"><?php echo $data->nombre_ubicacion; ?></td>
        <td width="20" align="center"><img class="edit_ubicacion_producto" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Ubicación" onClick="edit_ubicacion_producto(<?php echo $data->id_ubicacion; ?>)" style="cursor: pointer;"/></td>
        
        <td width="20" align="center"><img class="delete_ubicacion_producto" src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Ubicación" onClick="delete_ubicacion_producto(<?php echo $data->id_ubicacion; ?>)" style="cursor: pointer;"/></td>
        <!--
        <td width="20" align="center">
          <a href="" class="eliminar_ubicacion_producto" id="elim_<?php echo $data->id_ubicacion; ?>">
          <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Ubicación"/></a>
        </td>
        -->
      </tr>
      <?php
        $i++;
        } 
      ?>        
    </table>
    <?php }?>
  </div>
  <div id="mdlEditarRendimiento"></div>
  <div id="finregistro"></div>
  <div id="modalerror"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminar_ubicacion_producto');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Ubicación">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar la Ubicación del producto?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>

  <!---  Ventanas modales -->
  <div id="mdlNuevaUbicacion" style="display:none">
    <div id="contenedor" style="width:355px; height:90px;"> <!--Aumenta el marco interior-->
    <div id="tituloCont">Nueva Ubicación</div>
    <div id="formFiltro" style="width:500px;">
    <?php
      $ubicacion_producto_modal = array('name'=>'ubicacion_producto_modal','id'=>'ubicacion_producto_modal','maxlength'=>'50', 'class'=>'required');
    ?>  
      <form method="post" id="nueva_maquina" style=" border-bottom:0px">
      <table>
        <tr>
          <td width="152" height="30" style="width: 150px;">Ubicación del Producto:</td>
          <td width="261" height="30"><?php echo form_input($ubicacion_producto_modal);?></td>
        </tr>
      </table>
      </form>
    </div>
    </div>
  </div>

  <?php if(!empty($respuesta_validacion_producto_invalido)){ ?>
    <div id="error_respuesta_validacion_producto_invalido"></div>
  <?php } ?>