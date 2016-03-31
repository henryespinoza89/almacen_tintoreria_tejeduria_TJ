<?php
  $file = array('name'=>'file','id'=>'file','maxlength'=>'20', 'style'=>'width:300px;padding-left: 0px;', 'class'=>'required', 'type'=>'file');
  //$total_factura_contabilidad = array('name'=>'total_factura_contabilidad','id'=>'total_factura_contabilidad', 'style'=>'width:80px');
  //$monto_total_factura = array('name'=>'monto_total_factura','id'=>'monto_total_factura', 'style'=>'width:80px');
  if ($this->input->post('seriecomprobante')){
    $seriecomprobante = array('name'=>'seriecomprobante','id'=>'seriecomprobante','maxlength'=>'5','value'=>$this->input->post('seriecomprobante'), 'style'=>'width:30px;margin-right: 2px;', 'class'=>'required','onpaste'=>'return false');
  }else{
    $seriecomprobante = array('name'=>'seriecomprobante','id'=>'seriecomprobante','maxlength'=>'5', 'style'=>'width:30px;margin-right: 2px;', 'class'=>'required','onpaste'=>'return false');
  }

  if ($this->input->post('total_factura_contabilidad')){
    $total_factura_contabilidad = array('name'=>'total_factura_contabilidad','id'=>'total_factura_contabilidad','value'=>$this->input->post('total_factura_contabilidad'), 'style'=>'width:80px;');
  }else{
    $total_factura_contabilidad = array('name'=>'total_factura_contabilidad','id'=>'total_factura_contabilidad', 'style'=>'width:80px;');
  }

  if ($this->input->post('numcomprobante')){
    $numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'20','value'=>$this->input->post('numcomprobante'), 'style'=>'width:108px', 'class'=>'required');
  }else{
    $numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'20', 'style'=>'width:108px', 'class'=>'required');
  }
  // ,'onpaste'=>'return false' // No permite utilizar el ctr v en el input

  if ($this->input->post('fecharegistro')){
    $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:150px','readonly'=> 'readonly', 'class'=>'required');
  }else{
    $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:150px','readonly'=> 'readonly', 'class'=>'required');
  }

  if ($this->input->post('nombre_proveedor')){
      $nombre_proveedor = array('name'=>'nombre_proveedor','id'=>'nombre_proveedor','value'=>$this->input->post('nombre_proveedor'), 'style'=>'width:200px;font-family: verdana;','placeholder'=>' :: Nombre del Proveedor ::');
  }else{
      $nombre_proveedor = array('name'=>'nombre_proveedor','id'=>'nombre_proveedor', 'style'=>'width:200px;font-family: verdana;','placeholder'=>' :: Nombre del Proveedor ::'); 
  }

?>
<script type="text/javascript">
  $(function(){

    $("#nombre_proveedor").autocomplete({
      source: function (request, respond) {
        $.post("<?php echo base_url('comercial/traer_proveedor_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
        function (response) {
            respond(response);
        }, 'json');
      }, select: function (event, ui) {
        var selectedObj = ui.item;
        var razon_social = selectedObj.razon_social;
        $("#nombre_proveedor").val(razon_social);
      }
    });

    $("#seriecomprobante").keyup(function(){
      var longitud = $("#seriecomprobante").val().length;
      if(longitud == 3){
        $("#numcomprobante").focus();
      }
    });

    $("#test_masiva_informacion").click(function() {

      var dataFrm = new FormData();

      dataFrm.append("file", $("#file").val());

      /* Selecionar variables */
      /*var id_comprobante = $("#comprobante").val();
      var numcomprobante = $("#numcomprobante").val();
      var seriecomprobante = $("#seriecomprobante").val();
      var id_moneda = $("#moneda").val();
      var id_proveedor = $("#proveedor").val();
      var fecharegistro = $("#fecharegistro").val();
      var id_agente = $("#agente").val();
      var total_factura_contabilidad = $("#total_factura_contabilidad").val();
      var documento_file = $("#file").val();
      /* Data serializada */
      //var data_serialize = 'id_comprobante='+id_comprobante+'&numcomprobante='+numcomprobante+'&seriecomprobante='+seriecomprobante+'&id_moneda='+id_moneda+'&id_proveedor='+id_proveedor+'&fecharegistro='+fecharegistro+'&id_agente='+id_agente+'&total_factura_contabilidad='+total_factura_contabilidad+'&documento_file='+documento_file+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';  
      /*  */
      $.ajax({
        type: 'POST',
        url: "<?php echo base_url(); ?>comercial/test_factura_importada/",
        //data: data_serialize,
        data: dataFrm,
        dataType: 'json',
        contentType:false,
        processData:false,
        cache:false,
        success: function(response){
          if(response == 'no_existe_receta'){

          }else{

          }
        }
      });
      /* Habilitar boton para registro */
      registrar_factura_masiva.disabled=false;
    });

    <?php 
      if ($this->input->post('moneda')){
        $selected_moneda =  (int)$this->input->post('moneda');
      }else{  $selected_moneda = "";
    ?>
          $("#moneda").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>

    <?php 
      if ($this->input->post('comprobante')){
        $selected_comprobante =  (int)$this->input->post('comprobante');
      }else{  $selected_comprobante = "";
    ?>
          $("#comprobante").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>

    <?php
      if ($this->input->post('agente')){
        $selected_agente =  (int)$this->input->post('agente');
      }else{  
        $selected_agente = "";
    ?>
        $("#agente").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>

    $("#fecharegistro").datepicker({
      dateFormat: 'yy-mm-dd',showOn: "button",
      buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
      buttonImageOnly: true,
      changeMonth: true,
      changeYear: true
    });
    $(".ui-datepicker-trigger").css('padding-left','7px');

    $("#error_general").html('!Falta Seleccionar el Tipo de Comprobante!<br>!Falta completar el campo N° de Comprobante!<br>!Falta completar el campo Serie de Comprobante!<br>!Falta seleccionar el campo Moneda!<br>!Falta seleccionar el campo Proveedor!<br>!Falta seleccionar el campo Fecha de Registro!<br>!Falta seleccionar el campo Agente de Aduana!').dialog({
      modal: true,position: 'center',width: 400,height: 215,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    $("#error_compro").html('!Falta completar el N° de Comprobante!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });
    
    $("#respuesta_compro_seleccion").html('!Falta Seleccionar el Tipo de Comprobante!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    $("#error_serie").html('!Falta completar la Serie de Comprobante!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    $("#error_prov").html('!Falta completar el campo Proveedor!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    $("#error_fe").html('!Falta completar el campo Fecha de Registro!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    $("#error_moneda").html('!Falta completar el campo Moneda!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    $("#error_agente").html('!Falta completar el campo Agente!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });
    
    $("#error_total_factura").html('!Falta completar el campo Total Factura!').dialog({
      modal: true,position: 'center',width: 400,height: 125,resizable: false, title: 'Error/Campos Vacios',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });
    
    $("#error_respuesta_validacion_actualizacion_importadas").html('!Los Datos de la Factura ingresada no coinciden para realizar la Actualización!').dialog({
      modal: true,position: 'center',width: 450,height: 125,resizable: false, title: 'No se realizo la Actualización',
      buttons: { Ok: function(){
        $(this).dialog('close');
      }}
    });

    <?php if(!empty($validacion_no_existe_tipo_cambio)){ ?>
      $("#error_validacion_no_existe_tipo_cambio").html('<strong>! No existe Tipo de Cambio para la fecha seleccionada.</strong>').dialog({
        modal: true,position: 'center',width: 490,height: 140, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
        buttons: { Ok: function(){
          $(this).dialog('close');
        }}
      });
    <?php } ?>

    <?php if(!empty($respuesta_validacion_areas_productos_importadas)){ ?>
      var fila_area = "<?php echo $respuesta_validacion_areas_productos_importadas; ?>" ;
    <?php } ?>

    <?php if(!empty($respuesta_validacion_areas_productos_importadas)){ ?>
      $("#error_respuesta_validacion_areas_productos_importadas").html('<strong>! Se encontro un Error en los Datos del Área de la Fila '+ fila_area + ' !<br> Verificar el Archivo Excel/CSV y volver a Cargar la Data.</strong>').dialog({
        modal: true,position: 'center',width: 490,height: 140, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
        buttons: { Ok: function(){
          $(this).dialog('close');
        }}
      });
    <?php } ?>

    /* ------------ VALIDACIÓN DE DATOS SI EXISTE EL ID DEL PRODUCTO ------------ */
    <?php if(!empty($respuesta_validacion_facturas_importadas)){ ?>
      var fila_producto = "<?php echo $respuesta_validacion_facturas_importadas; ?>" ;
    <?php } ?>

    <?php if(!empty($respuesta_validacion_facturas_importadas)){ ?>
      $("#error_respuesta_validacion_facturas_importadas").html('<strong>! Se encontro un Error en los Datos del Producto de la Fila '+ fila_producto + ' !<br> Verificar el Archivo Excel/CSV y volver a Cargar la Data.</strong>').dialog({
        modal: true,position: 'center',width: 490,height: 140, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
        buttons: { Ok: function(){
          $(this).dialog('close');
        }}
      });
    <?php } ?>
    /* ------------ FIN DE VALIDACIÓN SI EXISTE EL ID DEL PRODUCTO ------------ */

    /* ------------ VALIDACIÓN DE DATOS SI EXISTE EL ID DEL PRODUCTO ------------ */
    <?php if(!empty($respuesta_registro_satisfactorio)){ ?>
      var fila_producto = "<?php echo $respuesta_registro_satisfactorio; ?>" ;
    <?php } ?>

    <?php if(!empty($respuesta_registro_satisfactorio)){ ?>
      $("#error_respuesta_registro_satisfactorio").html('<strong>! Se realizo satisfactoriamente el Registro de '+ fila_producto + ' Filas !</strong>').dialog({
        modal: true,position: 'center',width: 490,height: 140, resizable: false, title: 'Error de Validación',hide: 'scale',show: 'scale',
        buttons: { Ok: function(){
          window.location.href="<?php echo base_url();?>comercial/gestionfacturasmasivas";
        }}
      });
    <?php } ?>
    /* ------------ FIN DE VALIDACIÓN SI EXISTE EL ID DEL PRODUCTO ------------ */
    $("#comprobante").change(function() {
    $("#comprobante option:selected").each(function() {
        categoria = $('#comprobante').val();
        if(categoria == 4){ /* Guia */
          $("#total_factura_contabilidad").prop('disabled', true);
        }else if(categoria == 2){ /* Factura */
          $("#total_factura_contabilidad").prop('disabled', false);
        }
      });
    });

  });
</script>
</head>
<body>
  <div id="contenedor" style="padding-top: 10px;">
    <div id="tituloCont" style="margin-bottom: 10px;">Cargar Facturas Importadas</div>
    <div id="formFiltro">
      <div id="options" style="border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 0;">
        <div class="newagente"><a href="<?php echo base_url(); ?>comercial/gestionaduana/">Gestionar Datos del Agente Aduanero</a></div>
      </div>
      <form id="formulario" action="<?php echo base_url('comercial/guardar_informacion_factura_importada');?>" enctype="multipart/form-data" method="post" style="background: whitesmoke;padding-left: 15px;padding-top: 12px;margin-top: 0px;">
        <div style="float: left;width: 400px;"> 
          <table width="360" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="120" valign="middle" height="30">Comprobante:</td>
              <td width="194" height="30"><?php echo form_dropdown('comprobante',$listacomprobante,$selected_comprobante,'id="comprobante" style="width:158px;"');?></td>
            </tr>
            <tr>
          </table>
          <table width="360" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="120" valign="middle" height="30">N° de Comprobante</td>
              <td width="30" height="30"><?php echo form_input($seriecomprobante);?></td>
              <td width="154" height="30"><?php echo form_input($numcomprobante);?></td>
            </tr>
            <tr>
          </table>
          <table width="360" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="120" valign="middle" height="30">Moneda</td>
              <td width="194" height="30"><?php echo form_dropdown('moneda',$listasimmon,$selected_moneda,'id="moneda" style="width:158px;"');?></td>
            </tr>
            <tr>
          </table>
          <table width="360" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="130" valign="middle" height="30">Proveedor</td>
              <td width="194" height="30"><?php echo form_input($nombre_proveedor);?></td>
            </tr>
            <tr>
          </table>
          <table width="360" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="120" valign="middle" height="30">Fecha de Registro</td>
              <td width="194" height="30"><?php echo form_input($fecharegistro);?></td>
            </tr>
            <tr>
          </table>
        </div>
        <div>
          <table width="486" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td width="120" valign="middle" height="30">Agente de Aduana</td>
              <td width="194" height="30"><?php echo form_dropdown('agente',$listaagente,$selected_agente,'id="agente" style="width:158px;"');?></td>
            </tr>
            <tr>
          </table>
          <table width="379" border="0" cellspacing="0" cellpadding="0" style="margin-top: 4px;">
            <tr>
              <td>
                <input id="" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" type="hidden" />
              </td>
              <td width="188">Total de la Factura:</td>
              <td width="194" height="30"><?php echo form_input($total_factura_contabilidad);?></td>
            </tr>
          </table>
          <table width="625" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="129" height="30">Seleccione el Archivo a subir:</td>
              <td width="194" height="30" style="padding-top: 5px;"><?php echo form_input($file);?></td>
            </tr>
            <tr>
          </table>
          <table width="625" border="0" cellspacing="0" cellpadding="0">
            <td width="134" align="left">
              <!--<input name="test_masiva_informacion" type="button" id="test_masiva_informacion" value="Realizar Test" style="border-radius: 0px;margin-top: 6px;height: 24px;margin-left: 227px;" />-->
              <input name="registrar_factura_masiva" type="submit" id="registrar_factura_masiva"  value="Registrar Factura" style="border-radius: 0px;margin-bottom: 6px;height: 24px;margin-left: 330px;border-radius: 6px;" />
            </td>
          </table>
        </div>
      </form>
    </div>
  </div>
  <div id="finregistro"></div>
  <div id="modalerror"></div>

  <?php if(!empty($validacion_no_existe_tipo_cambio)){ ?>
    <div id="error_validacion_no_existe_tipo_cambio"></div>
  <?php } ?>

  <?php if(!empty($respuesta_validacion_facturas_importadas)){ ?>
    <div id="error_respuesta_validacion_facturas_importadas"></div>
  <?php } ?>

  <?php if(!empty($respuesta_validacion_areas_productos_importadas)){ ?>
    <div id="error_respuesta_validacion_areas_productos_importadas"></div>
  <?php } ?>

  <?php if(!empty($respuesta_validacion_actualizacion_importadas)){ ?>
    <div id="error_respuesta_validacion_actualizacion_importadas"></div>
  <?php } ?>

  <?php if(!empty($respuesta_registro_satisfactorio)){ ?>
    <div id="error_respuesta_registro_satisfactorio"></div>
  <?php } ?>

  <?php if(!empty($respuesta_general)){ ?>
    <div id="error_general"></div>
  <?php } ?>

  <?php if(!empty($respuesta_compro)){ ?>
    <div id="error_compro"></div>
  <?php } ?>

  <?php if(!empty($respuesta_compro_seleccion)){ ?>
    <div id="respuesta_compro_seleccion"></div>
  <?php } ?>

  <?php if(!empty($respuesta_serie)){ ?>
    <div id="error_serie"></div>
  <?php } ?>

  <?php if(!empty($respuesta_prov)){ ?>
    <div id="error_prov"></div>
  <?php } ?>

  <?php if(!empty($respuesta_fe)){ ?>
      <div id="error_fe"></div>
  <?php } ?>

  <?php if(!empty($respuesta_moneda)){ ?>
    <div id="error_moneda"></div>
  <?php } ?>

  <?php if(!empty($respuesta_agente)){ ?>
    <div id="error_agente"></div>
  <?php } ?>

  <?php if(!empty($respuesta_total_factura)){ ?>
    <div id="error_total_factura"></div>
  <?php } ?>
