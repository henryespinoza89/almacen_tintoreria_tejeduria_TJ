<?php
  if ($this->input->post('nombre')){
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'20','value'=>$this->input->post('nombre'), 'style'=>'width:150px', 'class'=>'required');

  }else{
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'20', 'style'=>'width:150px', 'class'=>'required');
  }
?>

<script type="text/javascript">
  $(function(){

    $('#listaCategoriaProductos').DataTable();

    // ELIMINAR REGISTRO
    $('a.eliminar_registro').bind('click', function () {
      var ruta = $('#direccionelim').text();
        var id = $(this).attr('id').replace('elim_', '');
        var parent = $(this).parent().parent();
        //var usuario = $('#us123').text();
        $("#dialog-confirm").data({
              'delid': id,
              'parent': parent,
              'ruta': ruta
              //'idusuario': usuario
        }).dialog('open');
        return false;
    });
    $("#dialog-confirm").dialog({
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        width: 500,
        height: "auto",
        zindex: 9998,
        modal: false,
        buttons: {
          'Eliminar': function () {
            var parent = $(this).data('parent');
                var id = $(this).data('delid');
                var ruta = $(this).data('ruta');
                //var idusuario = $(this).data('idusuario');
                $.ajax({
                     type: 'get',
                     url: ruta,
                      data: {
                        'eliminar' : id
                        //'idusuario' : idusuario
                      }
                });
                $(this).dialog('close');
                setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestioncategoriaproductos"', 1200);
        },
            'Cancelar': function () {
                  $(this).dialog('close');
            }
        }
    });
    // FIN DE ELIMINAR

  });


  // Editar Máquina
  function editar_categoria_producto(id_categoria){
    var urlMaq = '<?php echo base_url();?>comercial/editarcategoriaproducto/'+id_categoria;
    //alert(urlMaq);
    $("#mdlEditarCategoriaProducto").load(urlMaq).dialog({
      modal: true, position: 'center', width: 400, height: 220, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {
        $(".ui-dialog-buttonpane button:contains('Actualizar')").button("disable");
        $(".ui-dialog-buttonpane button:contains('Actualizar')").attr("disabled", true).addClass("ui-state-disabled");
        //CONTROLO LAS VARIABLES
        var editcatprod = $('#editcatprod').val(); 
        if(editcatprod == ''){
          $("#modalerror").html('<b>ERROR:</b> Faltan completar el campos del formulario, por favor verifique.').dialog({
            modal: true,position: 'center',width: 450, height: 145,resizable: false,
            buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
          });
        }else{
          var dataString = 'editcatprod='+editcatprod+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>comercial/actualizarcategoriaproducto/"+id_categoria,
            data: dataString,
            success: function(msg){
              if(msg == 1){
                $("#finregistro").html('!La Categoría de Producto ha sido actualizada con éxito!.').dialog({
                  modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Fin de Registro',
                  buttons: { Ok: function(){
                    window.location.href="<?php echo base_url();?>comercial/gestioncategoriaproductos";
                  }}
                });
              }else{
                $("#modalerror").empty().append(msg).dialog({
                  modal: true,position: 'center',width: 500,height: 125,resizable: false,
                  buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
                });
                $(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");
              }
            }
          });
        }
      },
      Cancelar: function(){
        $("#mdlEditarCategoriaProducto").dialog("close");
      }
      }
    });
  }

</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont" style="margin-bottom: 10px;">Registrar Categoría de Producto</div>
    <div id="formFiltro">
        <?php echo form_open(base_url()."comercial/registrarcategoriaproducto", 'id="registrar"') ?>
          <table width="800" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="208" style="width: 270px;">Nombre de la Categoría de Producto:</td>
              <td width="196" style="padding-top: 5px;"><?php echo form_input($nombre);?></td>
              <td width="103" align="left"><input name="submit" type="submit" id="submit" value="Registrar" /></td>
              <td width="472" ><?php echo validation_errors(); if(!empty($respuesta)){ echo $respuesta;} ?></td>
            </tr>
          </table>
        <?php echo form_close() ?>
        <!--<div id="tituloCont" style="border-bottom-style:none;">Lista de Categorías de Productos</div>-->
        <!--Iniciar listar-->
        <?php 
          $existe = count($categoriaproducto);
          if($existe <= 0){
            echo 'No existen Categorías de Productos registradas en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaCategoriaProductos" style="float: left;width: 700px;" class="table table-hover table-striped">
          <thead>
            <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
              <td sort="idproducto" width="60" height="27">ITEM</td>
              <td sort="nombreprod" width="480">CATEGORÍA DE PRODUCTO</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
            </tr>
          </thead>
          <?php 
            $i=1;
            foreach($categoriaproducto as $data){ ?>  
          <tr class="contentTable" style="font-size: 12px;">
            <td height="27" style="vertical-align: middle;"><?php echo str_pad($i,4,0, STR_PAD_LEFT);?></td>
            <td style="vertical-align: middle;"><?php echo $data->no_categoria; ?></td>
            <td width="20" align="center"><img class="editar_categoria_producto" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Categoría de Producto" onClick="editar_categoria_producto(<?php echo $data->id_categoria; ?>)" style="cursor: pointer;"/></td>
            <td width="20" align="center">
              <a href="" class="eliminar_registro" id="elim_<?php echo $data->id_categoria; ?>">
              <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Categoría de Producto"/></a>
            </td>
          </tr>
          <?php
            $i++;
            } 
          ?>         
        </table>
        <?php }?>
    </div>
  </div>
  <div id="mdlEditarCategoriaProducto"></div>
  <div id="finregistro"></div>
  <div id="modalerror"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarcategoriaproducto');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Categoría de Producto">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar esta Categoría de Producto?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>