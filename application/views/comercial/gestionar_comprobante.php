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
      //Script para crear la tabla que será el contenedor de los productos registrados
    $('#listaComprobante').jTPS( {perPages:[10,15,20,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
            // target table selector
            var table = '#listaComprobante';
            // store pagination + sort in cookie 
            document.cookie = 'jTPS=sortasc:' + $(table + ' .sortableHeader').index($(table + ' .sortAsc')) + ',' +
                    'sortdesc:' + $(table + ' .sortableHeader').index($(table + ' .sortDesc')) + ',' +
                    'page:' + $(table + ' .pageSelector').index($(table + ' .hilightPageSelector')) + ';';
            }
        });
    // reinstate sort and pagination if cookie exists
    var cookies = document.cookie.split(';');
    for (var ci = 0, cie = cookies.length; ci < cie; ci++) {
            var cookie = cookies[ci].split('=');
            if (cookie[0] == 'jTPS') {
                    var commands = cookie[1].split(',');
                    for (var cm = 0, cme = commands.length; cm < cme; cm++) {
                            var command = commands[cm].split(':');
                            if (command[0] == 'sortasc' && parseInt(command[1]) >= 0) {
                                    $('#listaComprobante .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                            } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                    $('#listaComprobante .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                            } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                    $('#listaComprobante .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                            }
                    }
            }
    }
    // bind mouseover for each tbody row and change cell (td) hover style
    $('#listaComprobante tbody tr:not(.stubCell)').bind('mouseover mouseout',
            function (e) {
                    // hilight the row
                    e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
            }
    );

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
                        //'idusuario' : idusuario
                      }
                });
                $(this).dialog('close');
                setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestioncomprobante"', 200);
        },
            'Cancelar': function () {
                  $(this).dialog('close');
            }
        }
    });
    // FIN DE ELIMINAR

    
  });
/*
  function mostrar_errores(){
    <?php if(!empty($error))
    {
    ?>
    $("#errordatos").html('Hola').dialog({
      modal: true,position: 'center',width: 400,height: 135,resizable: false, title: 'Falta Completar',
      buttons: { Ok: function(){
        $("#errordatos").dialog("close");
      }}
    });
    <?php } ?>
  }
  */

  // Editar Máquina
  function editar_comprobante(id_comprobante){
        var urlMaq = '<?php echo base_url();?>comercial/editarcomprobante/'+id_comprobante;
        //alert(urlMaq);
        $("#mdlEditarComprobante").load(urlMaq).dialog({
          modal: true, position: 'center', width: 450, height: 190, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
            Actualizar: function() {
            $(".ui-dialog-buttonpane button:contains('Actualizar')").button("disable");
            $(".ui-dialog-buttonpane button:contains('Actualizar')").attr("disabled", true).addClass("ui-state-disabled");
            //CONTROLO LAS VARIABLES
            var editnombrecomprobante = $('#editnombrecomprobante').val(); 
            if(editnombrecomprobante == ''){
              $("#modalerror").html('<b>ERROR:</b> Falta completar el campo del formulario, por favor verifique.').dialog({
                modal: true,position: 'center',width: 450, height: 125,resizable: false,
                buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
              });
            }else{
              var dataString = 'editnombrecomprobante='+editnombrecomprobante+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/actualizarcomprobante/"+id_comprobante,
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    $("#finregistro").html('!El Nombre del Tipo de Comprobante ha sido actualizado con éxito!.').dialog({
                      modal: true,position: 'center',width: 400,height: 135,resizable: false, title: 'Fin de Actualización',
                      buttons: { Ok: function(){
                        window.location.href="<?php echo base_url();?>comercial/gestioncomprobante";
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
            $("#mdlEditarComprobante").dialog("close");
          }
                }
        });
      }
</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Gestionar Tipo de Comprobantes</div>
    <div id="formFiltro">
        <?php echo form_open(base_url()."comercial/registrarcomprobante", 'id="registrar"') ?>
          <table width="979" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="208">Nombre del Tipo de Comprobante:</td>
              <td width="196" style="padding-top: 5px;"><?php echo form_input($nombre);?></td>
              <td width="103" align="left"><input name="submit" type="submit" id="submit" value="Registrar"/></td>
              <td width="472" ><?php if(!empty($error)){ echo $error;} if(!empty($respuesta)){ echo $respuesta;} ?></td>
            </tr>
          </table>
        <?php echo form_close() ?>
        <div id="tituloCont" style="border-bottom-style:none;">Lista de Tipo de Comprobantes</div>
        <!--Iniciar listar-->
        <?php 
          $existe = count($comprobante);
          if($existe <= 0){
            echo 'No existen Tipo de Comprobantes registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaComprobante">
          <thead>
            <tr class="tituloTable">
              <td sort="idproducto" width="100" height="25">Item</td>
              <td sort="nombreprod" width="220">Nombre de Tipo de Comprobante</td>
              <td width="20">&nbsp;</td>
              <!--<td width="20">&nbsp;</td>-->
            </tr>
          </thead>
          <?php
            $i = 1;
            foreach($comprobante as $listaComprobante){ 
          ?>  
          <tr class="contentTable">
            <td height="27"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listaComprobante->no_comprobante; ?></td>
            <td width="20" align="center"><img class="editar_comprobante" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Tipo de Comprobante" onClick="editar_comprobante(<?php echo $listaComprobante->id_comprobante; ?>)" /></td>
            <!--
            <td width="20" align="center">
              <a href="" class="eliminar_registro" id="elim_<?php echo $listaComprobante->id_comprobante; ?>">
              <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Tipo de Comprobante"/></a>
            -->
            </td>
          </tr>
          <?php
            $i++; 
            } 
          ?> 
          <tfoot class="nav">
            <tr>
              <td colspan=8>
                    <div class="pagination"></div>
                    <div class="paginationTitle">Página</div>
                    <div class="selectPerPage"></div>
                </td>
            </tr>                   
          </tfoot>          
        </table>
        <?php }?>
    </div>
  </div>
  <div id="mdlEditarComprobante"></div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
  <div id="errordatos"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarcomprobante');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Tipo de Comprobante">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar este Tipo de Comprobante?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>