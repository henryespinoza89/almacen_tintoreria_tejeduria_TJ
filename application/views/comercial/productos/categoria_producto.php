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

    $(".newprospect").click(function() {
      $("#mdlNuevaCategoria" ).dialog({
        modal: true,resizable: false,show: "blind",hide: "blind",position: 'center',width: 405,height: 220,draggable: false,closeOnEscape: false, //Aumenta el marco general
        buttons: {
        Registrar: function() {
            var categoria_producto_modal = $('#categoria_producto_modal').val();
            if(categoria_producto_modal == ''){
              sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
            }else{
              var dataString = 'categoria_producto_modal='+categoria_producto_modal+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/save_categoria_producto/",
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    swal({ title: "La Categoría del Producto ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                    $("#mdlNuevaCategoria").dialog("close");
                    $('#categoria_producto_modal').val('');
                  }else{
                    sweetAlert(msg, "", "error");
                  }
                }
              });
            }
          },
          Cancelar: function(){
            $("#mdlNuevaCategoria").dialog("close");
          }
          }
      });
    });

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

  function editar_categoria_producto(id_categoria){
    var urlMaq = '<?php echo base_url();?>comercial/editarcategoriaproducto/'+id_categoria;
    $("#mdlEditarCategoriaProducto").load(urlMaq).dialog({
      modal: true, position: 'center', width: 400, height: 220, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {
        var editcatprod = $('#editcatprod').val(); 
        if(editcatprod == ''){
          sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
        }else{
          var dataString = 'editcatprod='+editcatprod+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>comercial/update_categoria_producto/"+id_categoria,
            data: dataString,
            success: function(msg){
              if(msg == 1){
                swal({ title: "La Categoría del Producto ha sido actualizado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                $("#mdlEditarCategoriaProducto").dialog("close");
              }else{
                sweetAlert(msg, "", "error");
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

  function delete_categoria_producto(id_categoria){
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
      var dataString = 'id_categoria='+id_categoria+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/eliminar_categoria_producto/",
        data: dataString,
        success: function(msg){
          if(msg == 'ok'){
            swal("Eliminado!", "La categoría del producto ha sido eliminado.", "success");
          }else if(msg == 'dont_delete'){
            sweetAlert("No se puede eliminar la categoría del producto", "Verificar que productos han sido registrados con esta categoría.", "error");
          }
        }
      });
    });
  }

</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Categoría de Productos</div>
    <div id="formFiltro">
      <div id="options_productos">
        <div class="newprospect" style="width: 250px;">NUEVA CATEGORIA DE PRODUCTO</div>
      </div>
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
          <td width="20" align="center"><img class="delete_categoria_producto" src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Tipo de Producto" onClick="delete_categoria_producto(<?php echo $data->id_categoria; ?>)" style="cursor: pointer;"/></td>
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

  <!---  Ventanas modales -->
  <div id="mdlNuevaCategoria" style="display:none">
    <div id="contenedor" style="width:355px; height:90px;"> <!--Aumenta el marco interior-->
    <div id="tituloCont">Nueva Categoría</div>
    <div id="formFiltro" style="width:500px;">
    <?php
      $categoria_producto_modal = array('name'=>'categoria_producto_modal','id'=>'categoria_producto_modal','maxlength'=>'50', 'class'=>'required');
    ?>  
      <form method="post" id="nueva_maquina" style=" border-bottom:0px">
      <table>
        <tr>
          <td width="152" height="30" style="width: 150px;padding-bottom: 5px;">Categoría del Producto:</td>
          <td width="261" height="30"><?php echo form_input($categoria_producto_modal);?></td>
        </tr>
      </table>
      </form>
    </div>
    </div>
  </div>