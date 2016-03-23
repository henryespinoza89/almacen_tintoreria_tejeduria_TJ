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
          width: 400,
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
                }
              });
              $(this).dialog('close');
              setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestiontipoproductos"', 1200);
            },
            'Cancelar': function () {
              $(this).dialog('close');
            }
        }
    });
    // FIN DE ELIMINAR

  });


  // Editar Máquina
  function editar_tipo_producto(id_tipo_producto){
        var urlMaq = '<?php echo base_url();?>comercial/editartipoproducto/'+id_tipo_producto;
        //alert(urlMaq);
        $("#mdlEditarTipoProducto").load(urlMaq).dialog({
          modal: true, position: 'center', width: 410, height: 230, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
            Actualizar: function() {
            $(".ui-dialog-buttonpane button:contains('Actualizar')").button("disable");
            $(".ui-dialog-buttonpane button:contains('Actualizar')").attr("disabled", true).addClass("ui-state-disabled");
            //CONTROLO LAS VARIABLES
            var edittipprod = $('#edittipprod').val();
            if(edittipprod == ''){
              $("#modalerror").html('<b>Faltan completar algunos campos del formulario, por favor verifique!</b>').dialog({
                modal: true,position: 'center',width: 500, height: 120,resizable: false,title: 'Validación/Campos Vacios',hide: 'scale',show: 'scale',
                buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
              });
            }else{
              var dataString = 'edittipprod='+edittipprod+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/actualizartipoproducto/"+id_tipo_producto,
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    $("#finregistro").html('!El Tipo de Producto ha sido actualizada con éxito!.').dialog({
                      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Fin de Registro',
                      buttons: { Ok: function(){
                        window.location.href="<?php echo base_url();?>comercial/gestiontipoproductos";
                      }}
                    });
                  }else{
                    $("#modalerror").html('<strong>!El Tipo de Producto ya existe. Verificar!</strong>').dialog({
                      modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
                      buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
                    });
                    $(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");
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



</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont" style="margin-bottom: 10px;">Registrar Tipo de Producto</div>
    <div id="formFiltro">
        <?php echo form_open(base_url()."comercial/registrartipoproducto", 'id="registrar"') ?>
          <table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="208" style="padding-bottom: 2px;">Ingrese el Tipo de Producto:</td>
              <td width="196" style="padding-top: 5px;"><?php echo form_input($nombre);?></td>
              <td width="103" align="left"><input name="submit" type="submit" id="submit" value="Registrar" /></td>
              <td width="472" ><?php echo validation_errors(); if(!empty($respuesta)){ echo $respuesta;} ?></td>
            </tr>
          </table>
        <?php echo form_close() ?>
        <!--<div id="tituloCont" style="border-bottom-style:none;">Listado de Tipos de Producto</div>-->
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
            <td width="20" align="center">
              <a href="" class="eliminar_registro" id="elim_<?php echo $listartipoproducto->id_tipo_producto; ?>">
              <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Tipo de Producto"/></a>
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
  <div id="mdlEditarTipoProducto"></div>
  <div id="finregistro"></div>
  <div id="modalerror"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminartipoproducto');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Tipo de Producto">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar este Tipo de Producto?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>

