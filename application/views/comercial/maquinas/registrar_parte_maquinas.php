<script type="text/javascript">
  function onlytext(){
    if((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
      event.returnValue = false;
  }

  $(function(){
  
  <?php 
    if ($this->input->post('maquina')){
      $selected_maquina =  (int)$this->input->post('maquina');
    }else{  $selected_maquina = "";
  ?>
    $("#maquina").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
  <?php 
    }
  ?>

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

  $(".newn_parte_maq").click(function(){
    $("#mdlNuevaParteMaquina" ).dialog({
      modal: true,resizable: false,show: "blind",position: 'center',width: 430,height: 250,draggable: false,closeOnEscape: false, //Aumenta el marco general
      buttons: {
        Registrar: function() {
          var parte_maquina_modal = $('#parte_maquina_modal').val(); maquina = $('#maquina').val();
          if(parte_maquina_modal == '' || maquina == ''){
            sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
          }else{
            var dataString = 'parte_maquina_modal='+parte_maquina_modal+'&maquina='+maquina+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>comercial/registrar_parte_maquina/",
              data: dataString,
              success: function(msg){
                if(msg == 1){
                  swal({ title: "La Parte de Máquina ha sido regristada con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                  $("#mdlNuevaParteMaquina").dialog("close");
                  $('#maquina').val('');
                  $('#parte_maquina_modal').val('');
                }else{
                  sweetAlert(msg, "", "error");
                }
              }
            });
          }
        },
        Cancelar: function(){
          $("#mdlNuevaParteMaquina").dialog("close");
        }
      }
    });
  });

  $('#lista_partes_maquina').DataTable();

  $('#listaMaquinas').DataTable();

        $('a.eliminar_parte_maquina').bind('click', function () {
          var ruta = $('#direccionelim_parte_maq').text();
            var id = $(this).attr('id').replace('elim_', '');
            var parent = $(this).parent().parent();
            $("#dialog-confirm-parte-maquina").data({
                  'delid': id,
                  'parent': parent,
                  'ruta': ruta
            }).dialog('open');
            return false;
        });
        $("#dialog-confirm-parte-maquina").dialog({
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          width: 450,
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
              setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestion_parte_maquina"', 200);
            },
            'Cancelar': function () {
              $(this).dialog('close');
            }
          }
        });
  });
  
  //Fuera de $(function(){         });

  function resetear(){
      window.location.href="<?php echo base_url();?>comercial/gestionmaquinas";
  }

  function editar_parte_maquina(id_parte_maquina){
    var urlMaq = '<?php echo base_url();?>comercial/editar_parte_maquina/'+id_parte_maquina;
    $("#mdlEditarMaquina").load(urlMaq).dialog({
      modal: true, position: 'center', width: 430, height: 250, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {           
          var edit_parte_maquina = $('#edit_parte_maquina').val(); edit_maquina = $('#edit_maquina').val();
          if(edit_parte_maquina == '' || edit_maquina == ''){
            sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
          }else{
            var dataString = 'edit_parte_maquina='+edit_parte_maquina+'&edit_maquina='+edit_maquina+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>comercial/actualizar_parte_maquina/"+id_parte_maquina,
              data: dataString,
              success: function(msg){
                if(msg == 1){
                  swal({ title: "Los datos de la Parte de Máquina ha sido Actualizado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
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
    <div id="tituloCont">Gestión de Partes de Máquinas</div>
    <div id="formFiltro">
      <div id="options">
        <div class="newn_parte_maq">Partes de la Máquina</div>
      </div>
      <?php 
        $existe = count($parte_maquina);
        if($existe <= 0){
          echo 'No existen Partes de Máquinas registradas en el Sistema.';
        }
        else
        {
      ?>
      <table border="0" cellspacing="0" cellpadding="0" id="lista_partes_maquina" style="width: 700px;float: left;" class="table table-hover table-striped">
        <thead>
          <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
            <td sort="idproducto" width="140" height="27">ID</td>
            <td sort="nombreprod" width="340" height="27">MÁQUINA</td>
            <td sort="nombreprod" width="400" height="27">PARTE DE MÁQUINA</td>
            <td width="20" style="background-image: none;">&nbsp;</td>
            <td width="20" style="background-image: none;">&nbsp;</td>
          </tr>
        </thead>
        <?php   foreach($parte_maquina as $row){ ?>
        <tr class="contentTable" style="font-size: 12px;">
          <td style="height: 24px;" style="vertical-align: middle;"><?php echo str_pad('PMAQ00'.$row->id_parte_maquina, 5, 0, STR_PAD_LEFT); ?></td>
          <td style="vertical-align: middle;"><?php echo $row->nombre_maquina; ?></td>
          <td style="vertical-align: middle;"><?php echo $row->nombre_parte_maquina; ?></td>
          <td width="20" style="vertical-align: middle;"><img class="editar_parte_maquina" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Máquina" onClick="editar_parte_maquina(<?php echo $row->id_parte_maquina; ?>)" style="cursor: pointer;"/></td>
          <td width="20" align="center">
            <a href="" class="eliminar_parte_maquina" id="elim_<?php echo $row->id_parte_maquina; ?>">
            <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Parte de Máquina"/></a>
          </td>
        </tr>
        <?php } ?>         
      </table>
      <?php }?>
    </div>
  </div>
  <div style="width:120px;float: left;padding-top: 3px;margin-left: 70px;cursor: pointer;">
    <i class="fa fa-arrow-left" style="font-size: 15px;color: blue;">
      <span style="margin-left: 10px;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 12px;font-weight: bold;">
        <a href="<?php echo base_url(); ?>comercial/gestionmaquinas/" style="color: blue;">Regresar</a>
      </span>
    </i>
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
      <div id="contenedor" style="width:380px; height:120px;"> <!--Aumenta el marco interior-->
      <div id="tituloCont">Nueva Parte de Maquinaria</div>
      <div id="formFiltro">
      <?php
        $parte_maquina_modal = array('name'=>'parte_maquina_modal','id'=>'parte_maquina_modal','maxlength'=>'60', 'style'=>'width:180px');
      ?>
          <form method="post" id="nuevo_producto" style=" border-bottom:0px">
            <table>
              <tr>
                <td width="260" style="width: 250px;padding-bottom: 6px;">Máquina:</td>
                <?php
                    $existe = count($lista_maquina);
                    if($existe <= 0){ ?>
                      <td width="200" height="28"><b><?php echo 'Registrar en el Sistema';?></b></td>
                <?php    
                    }
                    else
                    {
                  ?>
                      <td width="200"><?php echo form_dropdown('maquina',$lista_maquina,$selected_maquina,'id="maquina" style="width:158px;margin-left: 0px;"');?></td>
                <?php }?>
              </tr>
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
      <div id="direccionelim_parte_maq"><?php echo site_url('comercial/eliminar_parte_maquina');?></div>
    </div>

    <div id="dialog-confirm-parte-maquina" style="display: none;" title="Eliminar Parte de Máquina">
      <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        ¿Está seguro que quiere eliminar esta Parte de Máquina?<br /><strong>¡Esta acción no se puede revertir!</strong>
      </p>
    </div>