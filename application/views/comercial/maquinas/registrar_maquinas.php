<script type="text/javascript">
  function onlytext(){
    if((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
      event.returnValue = false;
  }

  $(function(){
  
  //Validar si existe el tipo de cambio del día registrado en el sistema
  <?php if(isset($tipocambio) && $tipocambio == 1){ ?>
  //Registro del Tipo de Cambio
  $("#compra").mask("9.999");
  $("#venta").mask("9.999");
  $("#datacompra_dol").mask("9.999");
  $("#dataventa_dol").mask("9.999");
  $("#datacompra_eur").mask("9.999");
  $("#dataventa_eur").mask("9.999");
  $( "#newtipocambio" ).dialog({
    modal: true,
    position: 'center',
    draggable: false,
    resizable: false,
    closeOnEscape: false,
    width:750,
    height:'auto',
    buttons: {
      'Guardar': function() {
        $(".ui-dialog-buttonpane button:contains('Guardar')").button("disable");
        var base_url = '<?php echo base_url();?>';
        var compra_dol = $("#datacompra_dol").val();
        var venta_dol = $("#dataventa_dol").val();
        var compra_eur = $("#datacompra_eur").val();
        var venta_eur = $("#dataventa_eur").val();
        var dataString = 'compra_dol=' + compra_dol+ '&venta_dol=' + venta_dol+ '&compra_eur=' + compra_eur+ '&venta_eur=' + venta_eur;
        $.ajax({
          type: "POST",
          url: base_url + "comercial/guardarTipoCambio",
          data: dataString,
          success: function(msg){
            if(msg == 'ok'){
              $("#finregistro").html('!El Tipo de Cambio ha sido regristado con éxito!.').dialog({
                modal: true,position: 'center',width: 350,height: 125,resizable: false, title: 'Fin de Registro',
                buttons: { Ok: function(){
                  window.location.href="<?php echo base_url();?>comercial/gestionmaquinas";
                }}
              });
            }else{
              $("#retorno").empty().append(msg);
              $(".ui-dialog-buttonpane button:contains('Guardar')").button("enable");
            }
          }
        });
      }
    }
  });
  <?php } ?>

    $(".newprospect").click(function(){
      $("#mdlNuevaMaquina" ).dialog({
        modal: true,resizable: false,show: "blind",position: 'center',width: 430,height: 275,draggable: false,closeOnEscape: false, //Aumenta el marco general
        buttons: {
          Registrar: function() {
            var nombre_maquina_modal = $('#nombre_maquina_modal').val(); estado = $('#estado').val(); obser = $('#obser').val(); 
            if(nombre_maquina_modal == '' || estado == ''){
              sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
            }else{
              var dataString = 'nombre_maquina_modal='+nombre_maquina_modal+'&estado='+estado+'&obser='+obser+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/registrarmaquina/",
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    swal({ title: "La Máquina ha sido regristada con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                    $("#mdlNuevaMaquina").dialog("close");
                    $('#nombre_maquina_modal').val('');
                    $('#obser').val('');
                  }else{
                    sweetAlert(msg, "", "error");
                  }
                }
              });
            }
          },
          Cancelar: function(){
            $("#mdlNuevaMaquina").dialog("close");
          }
        }
      });
    });

    $('#listaMaquinas').DataTable();
        
        // Eliminar Máquina
        $('a.eliminar_maquina').bind('click', function () {
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
              $.ajax({
                type: 'get',
                url: ruta,
                data: {
                  'eliminar' : id
                }
              });
              $(this).dialog('close');
              setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionmaquinas"', 200);
            },
            'Cancelar': function () {
              $(this).dialog('close');
            }
          }
        });
        // Fin de Eliminar
  });
  
  //Fuera de $(function(){         });

  function resetear(){
      window.location.href="<?php echo base_url();?>comercial/gestionmaquinas";
  }

  // Editar Máquina
  function editar_maquina(id_maquina){
    var urlMaq = '<?php echo base_url();?>comercial/editarmaquina/'+id_maquina;
    $("#mdlEditarMaquina").load(urlMaq).dialog({
      modal: true, position: 'center', width: 430, height: 275, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {         
          var editnombremaquina = $('#editnombremaquina').val(); editobser = $('#editobser').val(); editestado = $('#editestado').val();
          if(editnombremaquina == '' || editestado == ''){
            sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
          }else{
            var dataString = 'editnombremaquina='+editnombremaquina+'&editestado='+editestado+'&editobser='+editobser+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>comercial/actualizarmaquina/"+id_maquina,
              data: dataString,
              success: function(msg){
                if(msg == 1){
                  swal({ title: "Los datos de la Máquina ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                  $("#mdlEditarMaquina").dialog("close");
                }else{
                  sweetAlert(msg, "", "error");
                }
              }
            });
          }
        },
        Cancelar: function(){
          $("#mdlEditarMaquina").dialog("close");
        }
      }
    });
  }

</script>


</head>
<body>
  <div id="contenedor">
    <?php if($tipocambio == 1){?>
      <div id="newtipocambio" title="Registrar Tipo de Cambio" style="font-size:11px;min-height:47px;padding-left: 90px;width: 700px;height: auto;">
        <?php echo form_open('/comercial/guardarTipoCambio',array('name'=>'tipodecambio')); ?>
          <?php
            $datacompra_dol = array('name'=>'datacompra_dol','id'=>'datacompra_dol','maxlength'=>'5', 'size'=>'10');
            $dataventa_dol = array('name'=>'dataventa_dol','id'=>'dataventa_dol','maxlength'=> '5', 'size'=>'10');
            $datacompra_eur = array('name'=>'datacompra_eur','id'=>'datacompra_eur','maxlength'=>'5', 'size'=>'10');
            $dataventa_eur = array('name'=>'dataventa_eur','id'=>'dataventa_eur','maxlength'=> '5', 'size'=>'10');
          ?>
          <table width="650" border="0" cellspacing="2" cellpadding="2" align="rigth">
            <tr>
              <td width="75" height="30">Fecha Actual:</td>
              <td width="104" height="30"><b><?php echo date('d-m-Y'); ?></b></td>
              <td width="90" height="30">Tipo de Cambio:</td>
              <td width="347" height="30"><a href="http://www.sbs.gob.pe/app/stats/tc-cv.asp" id="tipo_cambio" target="_blank">Superintendencia de Banca, Seguros y AFP</a></td>
            </tr>
          </table>
          <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 15px;margin-bottom:5px;">
            <legend><strong>Tipo de Cambio en Dólares</strong></legend>
            <table width="220" border="0" cellspacing="2" cellpadding="2" align="center">
              <tr>
                <td height="30">Valor de Compra:</td>
                <td height="30"><?php echo form_input($datacompra_dol); ?></td>
              </tr>
              <tr>
                <td height="30">Valor de Venta:</td>
                <td height="30"><?php echo form_input($dataventa_dol); ?></td>
              </tr>
            </table>
          </fieldset>
          <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 15px;margin-bottom:5px;">
            <legend><strong>Tipo de Cambio en Euros</strong></legend>
            <table width="220" border="0" cellspacing="2" cellpadding="2" align="center">
              <tr>
                <td height="30">Valor de Compra:</td>
                <td height="30"><?php echo form_input($datacompra_eur); ?></td>
              </tr>
              <tr>
                <td height="30">Valor de Venta:</td>
                <td height="30"><?php echo form_input($dataventa_eur); ?></td>
              </tr>
            </table>
          </fieldset>
        <?php echo form_close() ?>
        <div id="retorno"></div>
      </div>
    <?php } ?>
    <div id="tituloCont">Gestión de Maquinarias</div>
    <div id="formFiltro">
      <div id="options">
        <div class="newprospect" >Registro de Máquina</div>
        <!--<div class="newn_parte_maq">Partes de la Máquina</div>-->
        <div class="newtp"><a href="<?php echo base_url(); ?>comercial/gestion_parte_maquina/">Partes de la Máquina</a></div>
      </div>
      <!-- LISTADO DE MAQUINAS -->
      <?php 
        $existe = count($maquina);
        if($existe <= 0){
          echo 'No existen Máquinas registradas en el Sistema.';
        }
        else
        {
      ?>
      <table border="0" cellspacing="0" cellpadding="0" id="listaMaquinas" style="float: left;width: 900px;" class="table table-hover table-striped">
        <thead>
          <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
            <td sort="idproducto" width="100" height="27">ID MAQUINA</td>
            <td sort="nombreprod" width="300" height="27">MÁQUINA</td>
            <td sort="obserprod" width="100">ESTADO</td>
            <td sort="obserprod" width="200">OBSERVACIÓN</td>
            <td width="20" style="background-image: none;">&nbsp;</td>
            <td width="20" style="background-image: none;">&nbsp;</td>
          </tr>
        </thead>
        <?php   foreach($maquina as $listamaquinas){ ?>  
        <tr class="contentTable" style="font-size: 12px;">
          <td style="height: 23px;" style="vertical-align: middle;"><?php echo str_pad('MAQ00'.$listamaquinas->id_maquina, 5, 0, STR_PAD_LEFT); ?></td>
          <td style="vertical-align: middle;"><?php echo $listamaquinas->nombre_maquina; ?></td>
          <td style="vertical-align: middle;"><?php echo $listamaquinas->no_estado_maquina; ?></td>
          <td style="vertical-align: middle;"><?php echo $listamaquinas->observacion_maq; ?></td>
          <td width="20" align="center"><img class="editar_maquina" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Máquina" onClick="editar_maquina(<?php echo $listamaquinas->id_maquina; ?>)" style="cursor: pointer;"/></td>
          <td width="20" align="center">
            <a href="" class="eliminar_maquina" id="elim_<?php echo $listamaquinas->id_maquina; ?>">
            <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Máquina"/></a>
          </td>
        </tr>
        <?php } ?>      
      </table>
      <?php }?>
      <!-- LISTADO DE PARTES DE LA MAQUINA 

      <?php 
        $existe = count($parte_maquina);
        if($existe <= 0){
          echo 'No existen Partes de Máquinas registradas en el Sistema.';
        }
        else
        {
      ?>
      <table border="0" cellspacing="0" cellpadding="0" id="lista_partes_maquina" style="width: 600px;float: left;">
        <thead>
          <tr class="tituloTable">
            <td sort="idproducto" width="80" height="25">ID</td>
            <td sort="nombreprod" width="270">Parte de Máquina</td>
            <td width="20">&nbsp;</td>
            <td width="20">&nbsp;</td>
          </tr>
        </thead>
        <?php   foreach($parte_maquina as $row){ ?>  
        <tr class="contentTable">
          <td style="height: 24px;"><?php echo str_pad('PMAQ00'.$row->id_parte_maquina, 5, 0, STR_PAD_LEFT); ?></td>
          <td><?php echo $row->nombre_parte_maquina; ?></td>
          <td width="20" align="center"><img class="editar_parte_maquina" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Máquina" onClick="editar_parte_maquina(<?php echo $row->id_parte_maquina; ?>)" /></td>
          <td width="20" align="center">
            <a href="" class="eliminar_parte_maquina" id="elim_<?php echo $row->id_parte_maquina; ?>">
            <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Parte de Máquina"/></a>
          </td>
        </tr>
        <?php } ?>         
      </table>
      <?php }?>
      -->
    </div>
  </div>
  <!---  Ventanas modales -->
  <div id="mdlNuevaMaquina" style="display:none">
      <div id="contenedor" style="width:380px; height:145px;"> <!--Aumenta el marco interior-->
      <div id="tituloCont">Nueva Maquinaria</div>
      <div id="formFiltro">
      <?php
        $nombre_maquina_modal = array('name'=>'nombre_maquina_modal','id'=>'nombre_maquina_modal','maxlength'=>'60', 'style'=>'width:150px');//este es un input
        $observacion = array('name'=>'obser','id'=>'obser','maxlength'=>'100');//este es un input
      ?>  
          <form method="post" id="nuevo_producto" style=" border-bottom:0px">
            <table>
              <tr>
                <td width="130">Nombre de la Máquina:</td>
                <td width="263"><?php echo form_input($nombre_maquina_modal);?></td>
              </tr>
              <tr>
                <td>Estado:</td>
                <td><?php echo form_dropdown('estado',$estado, '',"id='estado' style='margin-left: 0px;'");?></td>
              </tr>
              <tr>
                <td width="300">Observaciones:</td>
                <td width="300"><?php echo form_input($observacion);?></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>

    <div id="mdlNuevaParteMaquina" style="display:none">
      <div id="contenedor" style="width:380px; height:60px;"> <!--Aumenta el marco interior-->
      <div id="tituloCont">Nueva Parte de Maquinaria</div>
      <div id="formFiltro">
      <?php
        $parte_maquina_modal = array('name'=>'parte_maquina_modal','id'=>'parte_maquina_modal','maxlength'=>'60', 'style'=>'width:180px');
      ?>  
          <form method="post" id="nuevo_producto" style=" border-bottom:0px">
            <table>
              <tr>
                <td width="160" style="padding-bottom: 4px;">Parte de la Máquina:</td>
                <td width="263"><?php echo form_input($parte_maquina_modal);?></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>

    <div id="mdlEditarMaquina"></div>
    <div id="modalerror"></div>
    <div id="finregistro"></div>
      <div style="display:none">
      <div id="direccionelim"><?php echo site_url('comercial/eliminarmaquina');?></div>
    </div>
    <div id="finregistro"></div>
      <div style="display:none">
      <div id="direccionelim_parte_maq"><?php echo site_url('comercial/eliminar_parte_maquina');?></div>
    </div>
    <div id="dialog-confirm" style="display: none;" title="Eliminar Máquina">
      <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        ¿Está seguro que quiere eliminar esta Máquina?<br /><strong>¡Esta acción no se puede revertir!</strong>
      </p>
    </div>

    <div id="dialog-confirm-parte-maquina" style="display: none;" title="Eliminar Parte de Máquina">
      <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        ¿Está seguro que quiere eliminar esta Parte de Máquina?<br /><strong>¡Esta acción no se puede revertir!</strong>
      </p>
    </div>