<script type="text/javascript">
  $(function(){

    $('#listaAgenteAduana').DataTable();

    /* Ventana Modal para Registrar el Código de Hilado */
    $(".newprospect").click(function() { //activacion de ventana modal
      $("#mdlNuevoAgente" ).dialog({  //declaracion de ventana modal
        modal: true,resizable: false,show: "blind",hide: "blind",position: 'center',width: 405,height: 220,draggable: false,closeOnEscape: false, //Aumenta el marco general
        buttons: {
        Registrar: function() {
            var agente_aduana_modal = $('#agente_aduana_modal').val();
            if(agente_aduana_modal == ''){
              sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
            }else{
              var dataString = 'agente_aduana_modal='+agente_aduana_modal+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/save_agente_aduana/",
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    swal({ title: "El Agente de Aduana ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                    $("#mdlNuevoAgente").dialog("close");
                    $('#agente_aduana_modal').val('');
                  }else{
                    sweetAlert(msg, "", "error");
                  }
                }
              });
            }
          },
          Cancelar: function(){
            $("#mdlNuevoAgente").dialog("close");
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
                setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionaduana"', 200);
        },
            'Cancelar': function () {
                  $(this).dialog('close');
            }
        }
    });
    // FIN DE ELIMINAR

    
  });

  // Editar Máquina
  function editar_agente(id_agente){
        var urlMaq = '<?php echo base_url();?>comercial/editaragente/'+id_agente;
        $("#mdlEditarAgenteAduana").load(urlMaq).dialog({
          modal: true, position: 'center', width: 430, height: 220, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
            Actualizar: function() {
            var editnombreagente = $('#editnombreagente').val(); 
            if(editnombreagente == ''){
              sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
            }else{
              var dataString = 'editnombreagente='+editnombreagente+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/update_agente_aduana/"+id_agente,
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    swal({ title: "El Agente de Aduanas ha sido actualizado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                    $("#mdlEditarAgenteAduana").dialog("close");
                  }else{
                    sweetAlert(msg, "", "error");
                  }
                }
              });
            }
          },
          Cancelar: function(){
            $("#mdlEditarAgenteAduana").dialog("close");
          }
          }
        });
      }
</script>

</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Agente de Aduana</div>
    <div id="formFiltro">
      <div id="options_productos">
        <div class="newprospect" style="width: 150px;">NUEVO AGENTE</div>
      </div>
    </div>
    <!-- iniciar listar -->
    <?php 
      $existe = count($aduana);
      if($existe <= 0){
        echo 'No existen Agentes Aduaneros registrados en el Sistema.';
      }
      else
      {
    ?>
    <table border="0" cellspacing="0" cellpadding="0" id="listaAgenteAduana" style="float: left;width: 700px;" class="table table-hover table-striped">
      <thead>
        <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
          <td sort="idproducto" width="60" height="27">ITEM</td>
          <td sort="nombreprod" width="480">AGENTE DE ADUANA</td>
          <td width="20" style="background-image: none;">&nbsp;</td>
          <td width="20" style="background-image: none;">&nbsp;</td>
        </tr>
      </thead>
      <?php 
        $i = 1;
        foreach($aduana as $listaagenteaduana){
      ?>
      <tr class="contentTable" style="font-size: 12px;">
        <td height="27" style="vertical-align: middle;"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
        <td style="vertical-align: middle;"><?php echo $listaagenteaduana->no_agente; ?></td>
        <td width="20" align="center"><img class="editar_agente" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Agente de Aduana" onClick="editar_agente(<?php echo $listaagenteaduana->id_agente; ?>)" style="cursor:pointer;"/></td>
        <td width="20" align="center">
          <a href="" class="eliminar_registro" id="elim_<?php echo $listaagenteaduana->id_agente; ?>">
          <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Agente de Aduana"/></a>
        </td>
      </tr>
      <?php 
        $i++;
        } 
      ?>        
    </table>
    <?php 
      }
    ?>
  </div>
  <div id="mdlEditarAgenteAduana"></div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
  <div id="errordatos"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminaragente');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Nombre de Máquina">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar el siguiente Agente Aduanero?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>

  <!---  Ventanas modales -->
  <div id="mdlNuevoAgente" style="display:none">
    <div id="contenedor" style="width:355px; height:90px;"> <!--Aumenta el marco interior-->
    <div id="tituloCont">Nuevo Agente</div>
    <div id="formFiltro" style="width:500px;">
    <?php
      $agente_aduana_modal = array('name'=>'agente_aduana_modal','id'=>'agente_aduana_modal','maxlength'=>'50', 'class'=>'required');
    ?>  
      <form method="post" id="nueva_maquina" style=" border-bottom:0px">
      <table>
        <tr>
          <td width="152" height="30" style="width: 150px;">Agente de aduana:</td>
          <td width="261" height="30"><?php echo form_input($agente_aduana_modal);?></td>
        </tr>
      </table>
      </form>
    </div>
    </div>
  </div>