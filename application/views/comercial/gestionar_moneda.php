<?php
  //$nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1', 'style'=>'margin-bottom:0px' );

  if ($this->input->post('nombre')){
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'20','value'=>$this->input->post('nombre'), 'style'=>'width:150px', 'class'=>'required');

  }else{
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=>'20', 'style'=>'width:150px', 'class'=>'required');
  }

  if ($this->input->post('simbolo')){
    $simbolo = array('name'=>'simbolo','id'=>'simbolo','maxlength'=>'10','value'=>$this->input->post('simbolo'), 'style'=>'width:150px', 'class'=>'required');

  }else{
    $simbolo = array('name'=>'simbolo','id'=>'simbolo','maxlength'=>'10', 'style'=>'width:150px', 'class'=>'required');
  }
?>

<script type="text/javascript">
  $(function(){
      //Script para crear la tabla que será el contenedor de los productos registrados
    $('#listaTipoMoneda').jTPS( {perPages:[10,15,20,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
            // target table selector
            var table = '#listaTipoMoneda';
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
                                    $('#listaTipoMoneda .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                            } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                    $('#listaTipoMoneda .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                            } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                    $('#listaTipoMoneda .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                            }
                    }
            }
    }
    // bind mouseover for each tbody row and change cell (td) hover style
    $('#listaTipoMoneda tbody tr:not(.stubCell)').bind('mouseover mouseout',
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
                setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionmoneda"', 200);
        },
            'Cancelar': function () {
                  $(this).dialog('close');
            }
        }
    });
    // FIN DE ELIMINAR

  });


  // Editar Máquina
  function editar_moneda(id_moneda){
        var urlMaq = '<?php echo base_url();?>comercial/editarmoneda/'+id_moneda;
        //alert(urlMaq);
        $("#mdlEditarMoneda").load(urlMaq).dialog({
          modal: true, position: 'center', width: 400, height: 220, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
            Actualizar: function() {
            $(".ui-dialog-buttonpane button:contains('Actualizar')").button("disable");
            $(".ui-dialog-buttonpane button:contains('Actualizar')").attr("disabled", true).addClass("ui-state-disabled");
            //CONTROLO LAS VARIABLES
            var editnombremon = $('#editnombremon').val(); editsimbolomon = $('#editsimbolomon').val();
            if(editnombremon == '' || editsimbolomon == ''){
              $("#modalerror").html('<b>ERROR:</b> Faltan completar el campos del formulario, por favor verifique.').dialog({
                modal: true,position: 'center',width: 450, height: 150,resizable: false,
                buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
              });
            }else{
              var dataString = 'editnombremon='+editnombremon+'&editsimbolomon='+editsimbolomon+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/actualizarmoneda/"+id_moneda,
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    alert('¡El Tipo de Moneda ha sido actualizado con éxito!');
                    $("#mdlEditarMoneda").dialog("close");
                    setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionmoneda"', 200);
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
            $("#mdlEditarMoneda").dialog("close");
          }
                }
        });
      }

</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Registrar Nueva Moneda</div>
    <div id="formFiltro">
        <?php echo form_open(base_url()."comercial/registrarmoneda", 'id="registrar"') ?>
          <table width="865" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="144">Nombre de Moneda:</td>
              <td width="189" style="padding-top: 5px;"><?php echo form_input($nombre);?></td>
            </tr>
            <tr>
              <td width="144">Símbolo de Moneda:</td>
              <td width="189" style="padding-top: 5px;"><?php echo form_input($simbolo);?></td>
              <td width="120" align="left"><input name="submit" type="submit" id="submit" value="Registrar" /></td>
              <td width="358" ><?php echo validation_errors(); if(!empty($respuesta)){ echo $respuesta;} ?></td>
            </tr>
          </table>
        <?php echo form_close() ?>
        <div id="tituloCont" style="border-bottom-style:none;">Lista de Tipo de Monedas</div>
        <!--Iniciar listar-->
        <?php 
          $existe = count($listamoneda);
          if($existe <= 0){
            echo 'No existen Tipo de Monedas registradas en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaTipoMoneda">
          <thead>
            <tr class="tituloTable">
              <td sort="idproducto" width="80" height="25">ID Moneda</td>
              <td sort="nombreprod" width="160">Nombre de Moneda</td>
              <td sort="nombreprod" width="140">Símbolo de Moneda</td>
              <td width="20">&nbsp;</td>
              <td width="20">&nbsp;</td>
            </tr>
          </thead>
          <?php foreach($listamoneda as $listatipomoneda){ ?>  
          <tr class="contentTable">
            <td><?php echo str_pad($listatipomoneda->id_moneda, 5, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listatipomoneda->no_moneda; ?></td>
            <td><?php echo $listatipomoneda->simbolo_mon; ?></td>
            <td width="20" align="center"><img class="editar_moneda" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Moneda" onClick="editar_moneda(<?php echo $listatipomoneda->id_moneda; ?>)" /></td>
            <td width="20" align="center">
              <a href="" class="eliminar_registro" id="elim_<?php echo $listatipomoneda->id_moneda; ?>">
              <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Tipo de Moneda"/></a>
            </td>
          </tr>
          <?php } ?> 
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
  <div id="mdlEditarMoneda"></div>
  <div id="modalerror"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarmoneda');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Tipo de Moneda">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar esta Nombre de Máquina?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>