<?php
  //$nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1', 'style'=>'margin-bottom:0px' );

  if ($this->input->post('nombre')){
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'40','value'=>$this->input->post('nombre'), 'style'=>'width:150px', 'class'=>'required');
  }else{
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'40', 'style'=>'width:150px', 'class'=>'required');
  }

  if ($this->input->post('area')){
    $area = array('name'=>'area','id'=>'area','maxlength'=>'40','value'=>$this->input->post('area'), 'style'=>'width:150px', 'class'=>'required');
  }else{
    $area = array('name'=>'area','id'=>'area','maxlength'=>'40', 'style'=>'width:150px', 'class'=>'required');
  }

?>

<script type="text/javascript">
  $(function(){
    $("#maquina").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
      //Script para crear la tabla que será el contenedor de los productos registrados
    $('#listaNombreMaquinas').jTPS( {perPages:[10,15,20,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
            // target table selector
            var table = '#listaNombreMaquinas';
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
                                    $('#listaNombreMaquinas .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                            } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                    $('#listaNombreMaquinas .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                            } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                    $('#listaNombreMaquinas .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                            }
                    }
            }
    }
    // bind mouseover for each tbody row and change cell (td) hover style
    $('#listaNombreMaquinas tbody tr:not(.stubCell)').bind('mouseover mouseout',
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
                setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionarea"', 200);
        },
            'Cancelar': function () {
                  $(this).dialog('close');
            }
        }
    });
    // FIN DE ELIMINAR

    $("#empty_area").html('!Falta completar el campo Área!.').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campo Vacio',
      buttons: { Ok: function(){
        //window.location.href="<?php echo base_url();?>comercial/gestionarea";
        $(this).dialog('close');
      }}
    });

    $("#empty_responsable").html('!Falta completar el campo Responsable!.').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campo Vacio',
      buttons: { Ok: function(){
        //window.location.href="<?php echo base_url();?>comercial/gestionarea";
        $(this).dialog('close');
      }}
    });

    $("#empty_area_responsable").html('!Falta completar el campo Área!<br>!Falta completar el campo Responsable!').dialog({
      modal: true,position: 'center',width: 400,height: 135,resizable: false, title: 'Error/Campo Vacio',
      buttons: { Ok: function(){
        //window.location.href="<?php echo base_url();?>comercial/gestionarea";
        $(this).dialog('close');
      }}
    });

    $("#error_validacion").html('<span style="color:red"><b>ERROR:</b> Esta Área ya se encuentra registrada con ese Encargado.</span>').dialog({
      modal: true,position: 'center',width: 450,height: 125,resizable: false, title: 'Error en la validación',
      buttons: { Ok: function(){
        //window.location.href="<?php echo base_url();?>comercial/gestionarea";
        $(this).dialog('close');
      }}
    });

  });


  // Editar Máquina
  function editar_area_encargado(id_area){
        var urlMaq = '<?php echo base_url();?>comercial/editararea/'+id_area;
        //alert(urlMaq);
        $("#mdlEditarNombreMaquina").load(urlMaq).dialog({
          modal: true, position: 'center', width: 360, height: 220, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
            Actualizar: function() {
            $(".ui-dialog-buttonpane button:contains('Actualizar')").button("disable");
            $(".ui-dialog-buttonpane button:contains('Actualizar')").attr("disabled", true).addClass("ui-state-disabled");
            // CONTROLO LAS VARIABLES
            var editarea = $('#editarea').val(); editresponsable = $('#editresponsable').val(); editresponsable_sta_clara = $('#editresponsable_sta_clara').val();
            if(<?php echo $this->session->userdata('almacen'); ?> == 1){
              var nombre_encargado = editresponsable_sta_clara;
            }else if(<?php echo $this->session->userdata('almacen'); ?> == 2){
              var nombre_encargado = editresponsable;
            }
            if(editarea == '' || nombre_encargado == ''){
              $("#modalerror").html('<b>ERROR:</b> Faltan completar el campos del formulario, por favor verifique.').dialog({
                modal: true,position: 'center',width: 500, height: 125,resizable: false,title: 'Validación',
                buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
              });
            }else{
              var dataString = 'editarea='+editarea+'&nombre_encargado='+nombre_encargado+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/actualizararea/"+id_area,
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    $("#finregistro").html('!El Área ha sido actualizada con éxito!.').dialog({
                      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Fin de Registro',
                      buttons: { Ok: function(){
                        window.location.href="<?php echo base_url();?>comercial/gestionarea";
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
            $("#mdlEditarNombreMaquina").dialog("close");
          }
                }
        });
      }

      

</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Registrar el Área y su Encargado</div>
    <div id="formFiltro">
        <?php echo form_open(base_url()."comercial/registrararea", 'id="registrar"') ?>
          <table width="626" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="107">Área:</td>
              <td width="196"><?php echo form_input($area);?></td>
              <td width="323" align="left"><input name="submit" type="submit" id="submit" value="Registrar" /></td>
              <!--<td width="308" ><?php //if(!empty($respuesta)){ echo $respuesta;} ?></td>-->
              <?php //echo validation_errors(); if(!empty($respuesta)){ echo $respuesta;} ?>
            </tr>
            <tr>
              <td width="107">Responsable:</td>
              <td width="196" style="padding-top: 5px;"><?php echo form_input($nombre);?></td>
            </tr>
          </table>
        <?php echo form_close() ?>
        <div id="tituloCont" style="border-bottom-style:none;">Lista de Áreas y su Encargado</div>
        <!--Iniciar listar-->
        <?php 
          $existe = count($listaarea);
          if($existe <= 0){
            echo 'No existen Áreas y Responsables registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaNombreMaquinas">
          <thead>
            <tr class="tituloTable">
              <td sort="idproducto" width="100" height="25">Item</td>
              <td sort="nombreprod" width="180">Área</td>
              <td sort="nombreprod" width="180">Encargado</td>
              <td width="20">&nbsp;</td>
              <!--<td width="20">&nbsp;</td>-->
            </tr>
          </thead>
          <?php
            $i = 1;
            foreach($listaarea as $list){ 
          ?>  
          <tr class="contentTable">
            <td height="27"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $list->no_area; ?></td>
            <td><?php 
                  if($this->session->userdata('almacen') == 1){
                    echo $list->encargado_sta_clara;  
                  }else if($this->session->userdata('almacen') == 2){
                    echo $list->encargado;
                  }
                ?></td>
            <td width="20" align="center"><img class="editar_area_encargado" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Área" onClick="editar_area_encargado(<?php echo $list->id_area; ?>)" /></td>
            <!--
            <td width="20" align="center">
              <a href="" class="eliminar_registro" id="elim_<?php //echo $list->id_area; ?>">
              <img src="<?php //echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Área"/></a>
            </td>
            -->
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
  <div id="mdlEditarNombreMaquina"></div>
  <div id="finregistro"></div>
  <div id="modalerror"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminararea');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Área">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar esta Nombre de Máquina?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>

  <?php if(!empty($respuesta_area)){ ?>
     <div id="empty_area"></div>
  <?php } ?>

  <?php if(!empty($respuesta_responsable)){ ?>
     <div id="empty_responsable"></div>
  <?php } ?>

  <?php if(!empty($respuesta_ambos)){ ?>
     <div id="empty_area_responsable"></div>
  <?php } ?>

  <?php if(!empty($respuesta)){ ?>
     <div id="error_validacion"></div>
  <?php } ?>

