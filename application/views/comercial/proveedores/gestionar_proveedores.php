<script type="text/javascript">
  $(function(){

  // Validar si existe el tipo de cambio del día registrado en el sistema
  <?php if(isset($tipocambio) && $tipocambio == 1){ ?>
  // Registro del Tipo de Cambio
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
    width:700,
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
          url: base_url+"comercial/guardarTipoCambio",
          data: dataString,
          success: function(msg){
            if(msg == 'ok'){
              swal({   
                title: "Producto registrado!",
                text: "El registro se realizó con éxito",
                type: "success",
                confirmButtonText: "OK" ,
                timer: 2000
              });
              $("#newtipocambio").dialog("close");
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

  $('#listaProveedores').DataTable();

    // Eliminar Proveedor
    $('a.eliminar_proveedor').bind('click', function () {
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
                  },
                  success: function(msg){
                    if(msg == 1){
                      swal({ title: "El Proveedor ha sido eliminado correctamente!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                      $("#dialog-confirm").dialog("close");
                      window.location.href="<?php echo base_url();?>comercial/gestionproveedores";
                    }else{
                      $("#modalerror").empty().append(msg).dialog({
                        modal: true,position: 'center',width: 500,height: 150,resizable: false,title: '!No se puede eliminar el Proveedor!',hide: 'scale',show: 'scale',
                        buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
                      });
                      $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
                    }
                      }
                });
                $(this).dialog('close');
                //setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionproveedores"', 200);
        },
            'Cancelar': function () {
                  $(this).dialog('close');
            }
        }
    });
    // Fin de Eliminar
    $("#ruc_prov").validCampoFranz('0123456789');
    $(".downpdf_proveedor").click(function() {
      var url = '<?php echo base_url();?>comercial/reporteproveedorespdf';
      $(location).attr('href',url);  
    });
  });

  // Editar Proveedor
  function editar_proveedor(id_proveedor){
        var urlMaq = '<?php echo base_url();?>comercial/editarproveedor/'+id_proveedor;
        //alert(urlMaq);
        $("#mdlEditarProveedor").load(urlMaq).dialog({
          modal: true, position: 'center', width: 460, height: 305, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
            Actualizar: function() {
            var edit_rz = $('#edit_rz').val(); edit_ruc = $('#edit_ruc').val(); edit_pais = $('#edit_pais').val(); edit_direc = $('#edit_direc').val();
            var edit_tel1 = $('#edit_tel1').val();
            if(edit_rz == '' || edit_ruc == '' || edit_pais == '' || edit_direc == ''){
              sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
            }else{
              var dataString = 'edit_rz='+edit_rz+'&edit_ruc='+edit_ruc+'&edit_pais='+edit_pais+'&edit_tel1='+edit_tel1+'&edit_direc='+edit_direc+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>comercial/actualizarproveedor/"+id_proveedor,
                data: dataString,
                success: function(msg){
                  if(msg == 1){
                    swal({ title: "El Proveedor ha sido Actualizado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                    $("#mdlEditarProveedor").dialog("close");
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
            $("#mdlEditarProveedor").dialog("close");
          }
                }
        });
      }

  function resetear(){
      window.location.href="<?php echo base_url();?>comercial/gestionproveedores";
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
    <div id="tituloCont">Gestión de Proveedores</div>
    <div id="formFiltro">      
      <div id="options">
        <div class="newprospect"><a href="<?php echo base_url();?>comercial/registrarproveedor">Nuevo Proveedor</a></div>
      </div>
      <!--Iniciar listar-->
      <?php 
        $existe = count($proveedor);
        if($existe <= 0){
          echo 'No existen Proveedores registrados en el Sistema.';
        }
        else
        {
      ?>
      <table style="width:1362px;" border="0" cellspacing="0" cellpadding="0" id="listaProveedores" class="table table-hover table-striped">
        <thead>
          <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
            <td sort="idprov" width="100" height="25">ITEM</td>
            <td sort="rzprov" width="530">RAZÓN SOCIAL</td>
            <td sort="rucprov" width="170">RUC</td>
            <td sort="paisprov" width="190">PAÍS</td>
            <td sort="contprov" width="560">DIRECCIÓN</td>
            <td sort="telprov" width="130">TELÉFONO</td>
            <td width="20" style="background-image: none;">&nbsp;</td>
            <td width="20" style="background-image: none;">&nbsp;</td>
          </tr>
        </thead>
        <?php
          $i = 1;
          foreach($proveedor as $listaproveedores){ 
        ?>  
          <tr class="contentTable">
            <td style="height: 23px;" style="vertical-align: middle;"><?php echo str_pad($i, 5, 0, STR_PAD_LEFT); ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproveedores->razon_social; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproveedores->ruc; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproveedores->pais; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproveedores->direccion; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproveedores->telefono1; ?></td>
            <td width="20" align="center"><img class="editar_proveedor" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar proveedor" onClick="editar_proveedor(<?php echo $listaproveedores->id_proveedor; ?>)" style="cursor: pointer;"/></td>
            <td width="20" align="center">
              <a href="" class="eliminar_proveedor" id="elim_<?php echo $listaproveedores->id_proveedor; ?>">
              <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Proveedor"/></a>
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
  <div id="mdlEditarProveedor"></div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
    <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarproveedor');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Proveedor">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar este Proveedor?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>