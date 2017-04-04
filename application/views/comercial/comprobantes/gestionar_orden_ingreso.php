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

    $('#listaOrdenIngreso').DataTable();

  });

  function resetear(){
    window.location.href="<?php echo base_url();?>comercial/gestionproveedores";
  }

  function editar_orden_ingreso(id_kardex_producto){
    var urlMaq = '<?php echo base_url();?>comercial/editarordeningreso/'+id_kardex_producto;
    $("#mdlEditarOrdenIngreso").load(urlMaq).dialog({
      modal: true, position: 'center', width: 550, height: 288, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {
          var edit_precio_unitario = $('#edit_precio_unitario').val();
          if(edit_precio_unitario == '' || edit_precio_unitario == 0 || edit_precio_unitario < 0){
            sweetAlert("El precio unitario del producto debe ser diferente de cero, por favor verifique!", "", "error");
          }else{
            var dataString = 'edit_precio_unitario='+edit_precio_unitario+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>comercial/actualizar_orden_ingreso/"+id_kardex_producto,
              data: dataString,
              success: function(msg){
                if(msg == 1){
                  swal({
                    title: "El registro de la Orden de Ingreso ha sido Actualizado con éxito!",
                    text: "",
                    type: "success",
                    confirmButtonText: "OK"
                  },function(isConfirm){
                    if (isConfirm) {
                      window.location.href="<?php echo base_url();?>comercial/gestionordeningreso";  
                    }
                  });

                }else{
                  sweetAlert(msg, "", "error");
                }
              }
            });
          }
        },
        Cancelar: function(){
          $("#mdlEditarOrdenIngreso").dialog("close");
        }
      }
    });
  }

</script>
</head>
<body>
  <div id="contenedor">
    <?php $this->view('modal_tipo_cambio'); ?>
    <div id="tituloCont">Ordenes de Ingreso</div>
    <div id="formFiltro">
      <?php 
        $existe = count($ordeningreso);
        if($existe <= 0){
          echo 'Todas las ordenes de ingreso estan registradas correctamente.';
        }
        else
        {
      ?>
      <table style="width:1362px;" border="0" cellspacing="0" cellpadding="0" id="listaOrdenIngreso" class="table table-hover table-striped">
        <thead>
          <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
            <td sort="idprov" width="100" height="25">ITEM</td>
            <td sort="rzprov" width="170">NUM. COMPROBANTE</td>
            <td sort="rzprov" width="170">FECHA DE REGISTRO</td>
            <td sort="paisprov" width="170">CÓDIGO DEL PRODUCTO</td>
            <td sort="paisprov" width="400">NOMBRE DEL PRODUCTO</td>
            <td sort="rucprov" width="170">CANTIDAD DE INGRESO</td>
            <td width="40" style="background-image: none;">&nbsp;</td>
          </tr>
        </thead>
        <?php
          $i = 1;
          foreach($ordeningreso as $row){ 
        ?>  
          <tr class="contentTable">
            <td style="height: 23px;" style="vertical-align: middle;"><?php echo str_pad($i, 5, 0, STR_PAD_LEFT); ?></td>
            <td style="vertical-align: middle;"><?php echo $row->num_comprobante; ?></td>
            <td style="vertical-align: middle;"><?php echo $row->fecha_registro; ?></td>
            <td style="vertical-align: middle;"><?php echo $row->id_detalle_producto; ?></td>
            <td style="vertical-align: middle;"><?php echo $row->no_producto; ?></td>
            <td style="vertical-align: middle;"><?php echo $row->cantidad_ingreso; ?></td>
            <td width="40" align="center"><img class="editar_orden_ingreso" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Orden de Ingreso" onClick="editar_orden_ingreso(<?php echo $row->id_kardex_producto; ?>)" style="cursor: pointer;"/></td>
          </tr>
        <?php 
          $i++;
          } 
        ?>    
      </table>
      <?php }?>
    </div>
  </div>
  <div id="mdlEditarOrdenIngreso"></div>
  <div id="modalerror"></div>