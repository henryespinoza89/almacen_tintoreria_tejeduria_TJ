<?php
  //$nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1', 'style'=>'margin-bottom:0px' );
  if ($this->input->post('nombre')){
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'20','value'=>$this->input->post('nombre'), 'style'=>'width:150px', 'class'=>'required');
  }else{
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'20', 'style'=>'width:150px', 'class'=>'required');
  }
?>

<script type="text/javascript">
  $(function(){

    <?php 
      if ($this->input->post('categoria')){
        $selected_categoria =  (int)$this->input->post('categoria');
      }else{  $selected_categoria = "";
    ?>
        $("#categoria").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>

    $('#listaTiposProductos').DataTable();

    $(".newprospect").click(function(){
      $("#mdlTipoProducto" ).dialog({
        modal: true,resizable: false,show: "blind",hide: "blind",position: 'center',width: 405,height: 220,draggable: false,closeOnEscape: false, //Aumenta el marco general
        buttons: {
        Registrar: function() {
            var tipo_producto_modal = $('#tipo_producto_modal').val();
            if(tipo_producto_modal == ''){
              sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
            }else{
              //REGISTRO
              var dataString = 'tipo_producto_modal='+tipo_producto_modal+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/save_tipo_producto/",
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    swal({ title: "El Tipo de Producto ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                    $("#mdlTipoProducto").dialog("close");
                    $('#tipo_producto_modal').val('');
                  }else{
                    sweetAlert(msg, "", "error");
                  }
                }
              });
            }
          },
          Cancelar: function(){
            $("#mdlTipoProducto").dialog("close");
          }
          }
      });
    });

  });

  function editar_tipo_producto(id_tipo_producto){
    var urlMaq = '<?php echo base_url();?>comercial/editartipoproducto/'+id_tipo_producto;
    $("#mdlEditarTipoProducto").load(urlMaq).dialog({
      modal: true, position: 'center', width: 410, height: 230, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {
        var edittipprod = $('#edittipprod').val();
        if(edittipprod == ''){
          sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
        }else{
          var dataString = 'edittipprod='+edittipprod+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>comercial/update_tipo_producto/"+id_tipo_producto,
            data: dataString,
            success: function(msg){
              if(msg == 1){
                swal({ title: "El Tipo de Producto ha sido actualizado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                $("#mdlEditarTipoProducto").dialog("close");
              }else{
                sweetAlert(msg, "", "error");
              }
            }
          });
        }
      },
      Cancelar: function(){
        $("#mdlEditarTipoProducto").dialog("close");
      }
      }
    });
  }

  function delete_tipo_producto(id_tipo_producto){
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
      var dataString = 'id_tipo_producto='+id_tipo_producto+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/eliminar_tipo_producto/",
        data: dataString,
        success: function(msg){
          if(msg == 'ok'){
            swal("Eliminado!", "El Tipo de producto ha sido eliminado.", "success");
          }else if(msg == 'dont_delete'){
            sweetAlert("No se puede eliminar el tipo de producto", "Verificar que productos han sido registrados con este tipo de producto.", "error");
          }
        }
      });
    });
  }



</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Tipo de Producto</div>
    <div id="formFiltro">
      <div id="options_productos">
        <div class="newprospect" style="width: 220px;">NUEVO TIPO DE PRODUCTO</div>
      </div>
        <!--Iniciar listar-->
        <?php 
          $existe = count($listatipoproducto);
          if($existe <= 0){
            echo 'No existen Tipos de Productos registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaTiposProductos" style="float: left;width: 700px;" class="table table-hover table-striped">
          <thead>
            <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
              <td sort="idproducto" width="60" height="27">ITEM</td>
              <td sort="nombreprod" width="480">TIPOS DE PRODUCTO</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
            </tr>
          </thead>
          <?php
            $i=1;
            foreach($listatipoproducto as $listartipoproducto){ 
          ?>  
          <tr class="contentTable" style="font-size: 12px;">            
            <td height="27" style="vertical-align: middle;"><?php echo str_pad($i,4,0, STR_PAD_LEFT);?></td>
            <td style="vertical-align: middle;"><?php echo $listartipoproducto->no_tipo_producto; ?></td>
            <td width="20" align="center"><img class="editar_tipo_producto" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Tipo de Producto" onClick="editar_tipo_producto(<?php echo $listartipoproducto->id_tipo_producto; ?>)" style="cursor: pointer;"/></td>
            <td width="20" align="center"><img class="delete_tipo_producto" src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Tipo de Producto" onClick="delete_tipo_producto(<?php echo $listartipoproducto->id_tipo_producto; ?>)" style="cursor: pointer;"/></td>
            <!--
            <td width="20" align="center">
              <a href="" class="eliminar_registro" id="elim_<?php echo $listartipoproducto->id_tipo_producto; ?>">
              <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Tipo de Producto"/></a>
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
  </div>
  <div id="mdlEditarTipoProducto"></div>
  <div id="finregistro"></div>
  <div id="modalerror"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminartipoproducto');?></div>
  </div>


    <!---  Ventanas modales -->
  <div id="mdlTipoProducto" style="display:none">
    <div id="contenedor" style="width:355px; height:90px;"> <!--Aumenta el marco interior-->
    <div id="tituloCont">Nuevo Tipo de Producto</div>
    <div id="formFiltro" style="width:500px;">
    <?php
      $tipo_producto_modal = array('name'=>'tipo_producto_modal','id'=>'tipo_producto_modal','maxlength'=>'50', 'class'=>'required');
    ?>  
      <form method="post" id="nueva_maquina" style=" border-bottom:0px">
      <table>
        <tr>
          <td width="152" height="30" style="width: 120px;padding-bottom: 5px;">Tipo de Producto:</td>
          <td width="261" height="30"><?php echo form_input($tipo_producto_modal);?></td>
        </tr>
      </table>
      </form>
    </div>
    </div>
  </div>


  <div id="dialog-confirm" style="display: none;" title="Eliminar Tipo de Producto">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar este Tipo de Producto?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>

