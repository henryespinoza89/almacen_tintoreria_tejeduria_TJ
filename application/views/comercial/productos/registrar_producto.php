<script type="text/javascript">
  $(function(){

    /** Función de Autocompletado para el Tipo de Proceso **/
    $("#uni_med").autocomplete({
      source: function (request, respond) {
        $.post("<?php echo base_url('comercial/traer_unidad_medida_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
        function (response) {
          respond(response);
        }, 'json');
      }, select: function (event, ui) {
        var selectedObj = ui.item;
        $("#uni_med").val(selectedObj.nom_uni_med);
        /* Fin del código */
      }
    });
    /** Fin de la Función **/

    // Autocompletar ubicacion de productos
    $("#ubicacion_producto").autocomplete({
      source: function (request, respond) {
        $.post("<?php echo base_url('comercial/traer_ubicacion_producto_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
        function (response) {
          respond(response);
        }, 'json');
      }, select: function (event, ui) {
        var selectedObj = ui.item;
        $("#ubicacion_producto").val(selectedObj.nombre_ubicacion);
      }
    });

    /* Función Autocompletar para el Nombre del Producto */
    $("#producto_asociado").autocomplete({
        source: function (request, respond) {
          $.post("<?php echo base_url('comercial/traer_producto_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
          function (response) {
              respond(response);
          }, 'json');
        }, select: function (event, ui) {
          var selectedObj = ui.item;
          $("#nombre_producto").val(selectedObj.nombre_producto);
        }
    });
    /** Fin de la Función **/

    $("#agregar_indice").click(function(){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/agregar_indice/",
        success: function(response){
        if(response == 1){
            $("#modalerror").empty().append('<span style="color:black"><b>!Indices Agregados con éxito!</b></span>').dialog({
              modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {
                window.location.href="<?php echo base_url();?>comercial/gestionproductos";
              }}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
        }else{
          $("#modalerror").empty().append('<span style="color:red"><b>!ERROR!</b></span>').dialog({
              modal: true,position: 'center',width: 480,height: 125,resizable: false,title: 'Error de Validación',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
            }
        }
      });
    });
    
    $("#eliminar_registros").click(function(){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/eliminar_salidas_2014/",
        success: function(response){
        if(response == 1){
            $("#modalerror").empty().append('<span style="color:black"><b>!Registros de Salida Eliminados correctamente!</b></span>').dialog({
              modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {
                window.location.href="<?php echo base_url();?>comercial/gestionproductos";
              }}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
        }else{
          $("#modalerror").empty().append('<span style="color:red"><b>!ERROR!</b></span>').dialog({
              modal: true,position: 'center',width: 480,height: 125,resizable: false,title: 'Error de Validación',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
            }
        }
      });
    });

    $("#actualizar_saldos_iniciales").click(function(){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/actualizar_saldos_iniciales/",
        success: function(response){
        if(response == 1){
            $("#modalerror").empty().append('<span style="color:black"><b>!Datos Actualizados!</b></span>').dialog({
              modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {
                window.location.href="<?php echo base_url();?>comercial/gestionproductos";
              }}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
        }else{
          $("#modalerror").empty().append('<span style="color:red"><b>!ERROR!</b></span>').dialog({
              modal: true,position: 'center',width: 480,height: 125,resizable: false,title: 'Error de Validación',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
            }
        }
      });
    });

    $("#export_excel").click(function(){
      url = '<?php echo base_url(); ?>comercial/co_exportar_resumen_producto_excel';
      $(location).attr('href',url);
    });

    $("#consolidar").click(function(){
      url = '<?php echo base_url(); ?>comercial/consolidar_stock';
      $(location).attr('href',url);
    });

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
                  title: "Tipo de Cambio registrado!",
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

    // Venta Modal Registrar Producto
    $(".newprospect").click(function(){
      $("#mdlNuevoProducto" ).dialog({
          modal: true,resizable: false,show: "blind",position: 'center',width: 525,height: 375,draggable: false,closeOnEscape: false,
          buttons: {
          Registrar: function() {
              var nombrepro = $('#nombrepro').val(); categoria = $('#categoriaN').val(); tipo_producto = $('#tipo_producto').val(); area = $('#area').val(); ubicacion_producto = $('#ubicacion_producto').val();
              var procedencia = $('#procedenciaN').val(); obser = $('#obser').val(); uni_med = $('#uni_med').val(); producto_asociado = $('#producto_asociado').val();
              if(nombrepro == '' || categoria == ''|| procedencia == '' || uni_med == '' || tipo_producto == '' || area == '' || ubicacion_producto == ''){
                sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
              }else{
                var dataString = 'nombrepro='+nombrepro+'&categoria='+categoria+'&procedencia='+procedencia+'&uni_med='+uni_med+'&obser='+obser+'&tipo_producto='+tipo_producto+'&area='+area+'&producto_asociado='+producto_asociado+'&ubicacion_producto='+ubicacion_producto+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
                $.ajax({
                  type: "POST",
                  url: "<?php echo base_url(); ?>comercial/registrarproducto/",
                  data: dataString,
                  success: function(msg){
                    if(msg == 1){
                      swal({ title: "El Producto ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                      $("#mdlNuevoProducto").dialog("close");

                      $('#nombrepro').val('');
                      $('#ubicacion_producto').val('');
                      $('#uni_med').val('');
                      $('#obser').val('');
                      $('#categoriaN').val('');
                      $('#tipo_producto').val('');
                      $('#procedenciaN').val('');
                      /*
                      var dataString_reload_table = 'nombrepro='+nombrepro+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
                      $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>comercial/get_all_productos_insert/",
                        data: dataString_reload_table,
                        success: function(data_get_all){
                          data_get_all = JSON.parse(data_get_all);
                          var table = $('#listaProductos').DataTable();
                          table.row.add({
                            "0": "<td height='23' style='text-align: center;'>"+table.data.length+999,
                            "1": 'PRD'+data_get_all.result[0].id_pro,
                            "2": data_get_all.result[0].no_producto,
                            "3": data_get_all.result[0].nombre_ubicacion,
                            "4": data_get_all.result[0].no_categoria,
                            "5": data_get_all.result[0].no_tipo_producto,
                            "6": data_get_all.result[0].nom_uni_med,
                            "7": data_get_all.result[0].stock,
                            "8": '<img class="editar_producto" src="http://localhost/almacen_tintoreria_tejeduria_TJ/assets/img/edit.png" width="20" height="20" title="Editar producto" onclick="editar_producto('+data_get_all.result[0].id_pro+')" style="cursor: pointer;">',
                            "9": '<img class="eliminar_producto" src="http://localhost/almacen_tintoreria_tejeduria_TJ/assets/img/trash.png" width="20" height="20" title="Eliminar Producto" id="elim_'+data_get_all.result[0].id_pro+'" onclick="eliminar_producto('+data_get_all.result[0].id_pro+')" style="cursor: pointer;">'
                          }).draw();
                        }
                      });
                      */
                    }else if(msg == 'unidad_no_existe'){
                      sweetAlert("!La Unidad de Medida ingresada no es correcta. Verificar!", "", "error");
                    }else if(msg == 'nombre_producto'){
                      sweetAlert("!El Nombre del Producto ya se encuentra registrado. Verificar!", "", "error");
                    }else if(msg == 'ubicacion_no_existe'){
                      sweetAlert("!La Ubicación del Producto ingresada no es Correcta. Verificar!", "", "error");
                    }else if(msg == 'error_registro'){
                      sweetAlert("!Se ha producto un error. Intentelo Nuevamente!", "", "error");
                    }
                  }
                });
              }
          },
          Cancelar: function(){
            $("#mdlNuevoProducto").dialog("close");
          }
          }
      });
    });

    $("#categoriaN").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    $("#tipo_producto").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    $("#procedenciaN").append('<option value="" selected="selected">:: SELECCIONE ::</option>');

    <?php 
      if ($this->input->post('area')){
        $selected_area =  (int)$this->input->post('area');
      }else{  $selected_area = "";
    ?>
      $("#area").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>

    $('#listaProductos').DataTable();

    $("#almacen").append('<option value="" selected="selected">:: SELECCIONE ::</option>');

    $(".downpdf").click(function() {
      var url = '<?php echo base_url();?>comercial/reporteproductospdf';
      $(location).attr('href',url);  
    });

   
  });

  function resetear(){
    window.location.href="<?php echo base_url();?>comercial/gestionproductos";
  }

  // Editar Producto
  function editar_producto(id_pro){
    var urlMaq = '<?php echo base_url();?>comercial/editarproducto/'+id_pro;
    $("#mdlEditarProducto").load(urlMaq).dialog({
      modal: true, position: 'center', width: 500, height: 378, draggable: false, resizable: false, closeOnEscape: false,
      buttons: {
        Actualizar: function() {
          var editnombreprod = $('#editnombreprod').val(); editcat = $('#editcat').val(); editunid_med = $('#editunid_med').val(); 
          var editobser = $('#editobser').val(); editprocedencia = $('#editprocedencia').val(); edittipoprod = $('#edittipoprod').val();
          var edit_ubicacion = $('#edit_ubicacion').val();
          if(edit_ubicacion == '' || editnombreprod == '' || editcat == '' || editprocedencia == '' || editunid_med == '' || edittipoprod == ''){
            sweetAlert("Falta completar campos obligatorios del formulario, por favor verifique!", "", "error");
          }else{
            var dataString = 'edit_ubicacion='+edit_ubicacion+'&editnombreprod='+editnombreprod+'&edittipoprod='+edittipoprod+'&editcat='+editcat+'&editunid_med='+editunid_med+'&editprocedencia='+editprocedencia+'&editobser='+editobser+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>comercial/actualizarproducto/"+id_pro,
              data: dataString,
              success: function(msg){
                if(msg == 1){
                  swal({ title: "El Producto ha sido Actualizado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 2000 });
                  $("#mdlEditarProducto").dialog("close");
                }else{
                  sweetAlert(msg, "", "error");
                }
              }
            });
          }
        },
        Cancelar: function(){
          $("#mdlEditarProducto").dialog("close");
        }
      }
    });
  }

  function delete_producto(id_pro){
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
      var dataString = 'id_pro='+id_pro+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/eliminar_producto/",
        data: dataString,
        success: function(msg){
          if(msg == 'ok'){
            swal("Eliminado!", "El producto ha sido eliminado.", "success");
          }else if(msg == 'dont_delete'){
            sweetAlert("No se puede eliminar el producto", "El producto debe estar asociado a una factura, salida o cierre de almacén.", "error");
          }
        }
      });
    });
  }


</script>
</head>
<body>
  <div id="contenedor">
    <?php if($tipocambio == 1){?>
      <div id="newtipocambio" title="Registrar Tipo de Cambio" style="font-size:11px;min-height:20px;padding-left: 50px;width: 700px;height: auto;text-align: center;">
        <?php echo form_open('/comercial/guardarTipoCambio',array('name'=>'tipodecambio')); ?>
          <?php
            $datacompra_dol = array('name'=>'datacompra_dol','id'=>'datacompra_dol','maxlength'=>'5', 'size'=>'10');
            $dataventa_dol = array('name'=>'dataventa_dol','id'=>'dataventa_dol','maxlength'=> '5', 'size'=>'10');
            $datacompra_eur = array('name'=>'datacompra_eur','id'=>'datacompra_eur','maxlength'=>'5', 'size'=>'10');
            $dataventa_eur = array('name'=>'dataventa_eur','id'=>'dataventa_eur','maxlength'=> '5', 'size'=>'10');
          ?>
          <table width="600" border="0" cellspacing="2" cellpadding="2" align="rigth">
            <tr>
              <td width="85" height="30">Fecha Actual:</td>
              <td width="104" height="30"><b><?php echo date('d-m-Y'); ?></b></td>
              <td width="110" height="30">Tipo de Cambio:</td>
              <td width="220" height="30"><a href="http://www.sbs.gob.pe/app/stats/tc-cv.asp" id="tipo_cambio" target="_blank">Superintendencia de Banca, Seguros y AFP</a></td>
            </tr>
          </table>
          <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 50px;margin-bottom:5px;margin-left: 13px;padding-bottom: 8px;padding-right: 5px;">
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
          <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 55px;margin-bottom:5px;padding-bottom: 8px;padding-right: 5px;">
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
        <div id="retorno" style="float: left;margin-left: 13px;margin-top: 8px;"></div>
      </div>
    <?php } ?>
    <div id="tituloCont">Gestión de Productos - Repuestos y Suministros</div>
    <div id="formFiltro">
      <div id="options_productos">
        <div class="newprospect">Nuevo Producto</div>
        <div class="newct"><a href="<?php echo base_url(); ?>comercial/gestioncategoriaproductos/">Categoria de Producto</a></div>
        <div class="newtp"><a href="<?php echo base_url(); ?>comercial/gestiontipoproductos/">Tipo de Producto</a></div>
        <div class="newtp_ubicacion"><a href="<?php echo base_url(); ?>comercial/gestion_ubicacion_productos/">Ubicación de Producto</a></div>
        <!--<input name="export_excel" type="submit" id="export_excel" value="Exportar Resumen a Excel" style="padding-bottom:3px; padding-top:3px; background-color: #0B610B; border-radius:6px;width: 155px;float: right;" />-->
        <!--<input name="eliminar_registros" type="submit" id="eliminar_registros" value="Eliminar registros" style="padding-bottom:3px; padding-top:3px; background-color: #0B610B; border-radius:6px;width: 155px;float: right;" />
        <input name="actualizar_saldos_iniciales" type="submit" id="actualizar_saldos_iniciales" value="Actualizar saldos iniciales" style="padding-bottom:3px; padding-top:3px; background-color: #0B610B; border-radius:6px;width: 155px;float: right;" />
        <!--<input name="consolidar" type="submit" id="consolidar" value="Consolidar Stock" style="padding-bottom:3px; padding-top:3px; background-color: #0B610B; border-radius:6px;width: 155px;float: right;" />-->
        <!--<input name="agregar_indice" type="submit" id="agregar_indice" value="Agregar Indice" style="padding-bottom:3px; padding-top:3px; background-color: #0B610B; border-radius:6px;width: 155px;float: right;" />-->
      </div>
      <!--Iniciar listar-->
        <?php
          $existe = count($producto);
          if($existe <= 0){
            echo 'No existen Productos registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaProductos" style="width:1370px;" class="table table-hover table-striped">
          <thead>
            <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
              <td sort="idprod" width="65" height="27">ITEM</td>
              <td sort="idproducto" width="120" height="27">ID PRODUCTO</td>
              <td sort="nombreprod" width="330">NOMBRE O DESCRIPCIÓN</td>
              <td sort="catprod" width="110">UBICACIÓN</td>
              <td sort="catprod" width="125">CATEGORIA</td>
              <td sort="catprod" width="150">TIPO PRODUCTO</td>
              <!--<td sort="procprod" width="125">Procedencia</td>-->
              <td sort="procprod" width="95">MEDIDA</td>
              <td sort="procprod" width="90">STOCK</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
            </tr>
          </thead>
          <?php
            $i = 1;
            foreach($producto as $listaproductos){
              $vacio = 0;
          ?> 
          <body> 
            <tr class="contentTable" style="font-size: 12px;">
              <td height="23" style="vertical-align: middle;"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
              <td style="vertical-align: middle;"><?php echo 'PRD'.$listaproductos->id_pro; ?></td>
              <td style="vertical-align: middle;"><?php echo $listaproductos->no_producto; ?></td>
              <td style="vertical-align: middle;"><?php echo $listaproductos->nombre_ubicacion; ?></td>
              <td style="vertical-align: middle;"><?php echo $listaproductos->no_categoria; ?></td>
              <td style="vertical-align: middle;"><?php echo $listaproductos->no_tipo_producto; ?></td>
              <!--<td><?php // echo $listaproductos->no_procedencia; ?></td>-->
              <td style="vertical-align: middle;"><?php echo $listaproductos->nom_uni_med; ?></td>
              <td style="vertical-align: middle;"><?php echo $listaproductos->stock; ?></td>
              <td width="20" align="center">
                <img class="editar_producto" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar producto" onClick="editar_producto(<?php echo $listaproductos->id_pro;?>)" style="cursor: pointer;" />
              </td>
              <td width="20" align="center"><img class="delete_producto" src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Ubicación" onClick="delete_producto(<?php echo $listaproductos->id_pro; ?>)" style="cursor: pointer;"/></td>
            </tr>
          <?php
            $i++;
            } 
          ?>
          </body>
        </table>
      <?php }?>
    </div>
  </div>
  <!---  Ventanas modales -->
  <div id="mdlNuevoProducto" style="display:none">
      <div id="contenedor" style="width:470px; height:245px;"> <!--Aumenta el marco interior-->
      <div id="tituloCont" style="margin-bottom: 10px;">Nuevo Producto</div>
      <div id="formFiltro" style="width:500px;">
      <?php
        $nombrepro = array('name'=>'nombrepro','id'=>'nombrepro','maxlength'=>'60', 'style'=>'width:300px');//este es un input
        $ubicacion_producto = array('name'=>'ubicacion_producto','id'=>'ubicacion_producto','maxlength'=>'60', 'style'=>'width:150px');//este es un input
        $observacion = array('name'=>'obser','id'=>'obser','maxlength'=>'100', 'style'=>'width:150px');//este es un input
        $uni_med = array('name'=>'uni_med','id'=>'uni_med','maxlength'=>'30', 'style'=>'width:150px');//este es un input
        $precio_unitario = array('name'=>'precio_unitario','id'=>'precio_unitario','maxlength'=>'30', 'style'=>'width:150px');//este es un input
        $stock = array('name'=>'stock','id'=>'stock','maxlength'=>'30', 'style'=>'width:150px');//este es un input
        $producto_asociado = array('name'=>'producto_asociado','id'=>'producto_asociado','maxlength'=>'100', 'style'=>'width:150px');//este es un input
      ?>  
        <form method="post" id="nuevo_producto" style=" border-bottom:0px">
          <table>
            <tr>
              <td width="130">Descripción:</td>
              <td width="263"><?php echo form_input($nombrepro);?></td>
            </tr>
            <tr>
              <td>Ubicación:</td>
              <td width="263"><?php echo form_input($ubicacion_producto);?></td>
            </tr>
            <tr> 
              <td>Categoria:</td>
              <td width="263"><?php echo form_dropdown('categoriaN',$listacategoria,'',"id='categoriaN' style='margin-left: 0px;'");?></td>
            </tr>
            <tr> 
              <td>Tipo de Producto:</td>
              <td width="263"><?php echo form_dropdown('tipo_producto',$lista_tipo_producto,'',"id='tipo_producto' style='margin-left: 0px;'");?></td>
            </tr>
            <tr>
              <td>Procedencia:</td>
              <td><?php echo form_dropdown('procedenciaN',$listaprocedencia,'',"id='procedenciaN' style='margin-left: 0px;'");?></td>
            </tr>
            <tr>
              <td>Unidad de Medida:</td>
               <td><?php echo form_input($uni_med);?></td>
            </tr>
            <tr>
              <td width="130">Observaciones:</td>
              <td width="263"><?php echo form_input($observacion);?></td>
            </tr>
          </table>
        </form>
        </div>
      </div>
    </div>
    <div id="mdlEditarProducto"></div>
    <div id="modalerror"></div>
    <div id="finregistro"></div>
    <div style="display:none">
      <div id="direccionelim"><?php echo site_url('comercial/eliminarproducto');?></div>
    </div>
    <div id="dialog-confirm" style="display: none;" title="Eliminar Producto">
      <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        ¿Está seguro que quiere eliminar el producto?<br /><strong>¡Esta acción no se puede revertir!</strong>
      </p>
    </div>