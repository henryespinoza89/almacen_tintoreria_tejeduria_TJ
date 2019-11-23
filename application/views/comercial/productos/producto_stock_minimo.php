<?php
  $file = array('name'=>'file','id'=>'file','maxlength'=>'20', 'style'=>'width:300px;padding-left: 0px;', 'class'=>'required', 'type'=>'file');
  //$total_factura_contabilidad = array('name'=>'total_factura_contabilidad','id'=>'total_factura_contabilidad', 'style'=>'width:80px');
  //$monto_total_factura = array('name'=>'monto_total_factura','id'=>'monto_total_factura', 'style'=>'width:80px');
  if ($this->input->post('seriecomprobante')){
    $seriecomprobante = array('name'=>'seriecomprobante','id'=>'seriecomprobante','maxlength'=>'5','value'=>$this->input->post('seriecomprobante'), 'style'=>'width:40px;margin-right: 2px;', 'class'=>'required','onpaste'=>'return false');
  }else{
    $seriecomprobante = array('name'=>'seriecomprobante','id'=>'seriecomprobante','maxlength'=>'5', 'style'=>'width:40px;margin-right: 2px;', 'class'=>'required','onpaste'=>'return false');
  }

  if ($this->input->post('total_factura_contabilidad')){
    $total_factura_contabilidad = array('name'=>'total_factura_contabilidad','id'=>'total_factura_contabilidad','value'=>$this->input->post('total_factura_contabilidad'), 'style'=>'width:80px;');
  }else{
    $total_factura_contabilidad = array('name'=>'total_factura_contabilidad','id'=>'total_factura_contabilidad', 'style'=>'width:80px;');
  }

  if ($this->input->post('numcomprobante')){
    $numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'20','value'=>$this->input->post('numcomprobante'), 'style'=>'width:111px', 'class'=>'required');
  }else{
    $numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'20', 'style'=>'width:111px', 'class'=>'required');
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
    $('#lista_producto_stock_minimo').DataTable();
    // Exportar reporte a excel
    $("#report_inventario_excel").click(function(){
      url = '<?php echo base_url(); ?>comercial/al_exportar_alertas_producto/';
      $(location).attr('href',url);
    });
  });
</script>
</head>
<body>
  <div id="contenedor" style="padding-top: 10px;">
    <div id="tituloCont" style="margin-bottom: 25px;">Producto con alerta de stock mínimo</div>
    <?php 
        $existe = count($pro_stock_minimo);
      if($existe <= 0){
          echo 'No existen productos con alerta mínima ';
      }
      else{
    ?>
    <div id="formFiltro">
      <table width="900" border="0" cellspacing="0" cellpadding="0" style="margin-top: 25px;margin-bottom: 20px;">
        <tr>
          <td width="195"><input name="submit" type="submit" id="report_inventario_excel" class="report_inventario_excel" value="EXPORTAR REPORTE DE ALERTA A EXCEL" style="background-color: #FF5722;width: 270px;margin-bottom: 6px;" /></td>
        </tr>
      </table>
    </div>
    <table border="0" cellspacing="0" cellpadding="0" id="lista_producto_stock_minimo" style="float: left;width:930px;" class="table table-hover table-striped">
      <thead>
        <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
          <td sort="idprod" width="80" height="27">ITEM</td>
          <td sort="idproducto" width="450">PRODUCTO</td>
          <td sort="idproducto" width="160">STOCK KARDEX</td>
          <td sort="idproducto" width="160">STOCK INTERNO</td>
          <td sort="idproducto" width="160">STOCK MÍNIMO</td>
        </tr>
      </thead>
      <?php 
        $i = 1;
        foreach($pro_stock_minimo as $data){ ?>  
            <tr class="contentTable" style="font-size: 12px;">
                <td height="27" style="vertical-align: middle;"><?php echo str_pad($i, 3, 0, STR_PAD_LEFT); ?></td>
                <td style="vertical-align: middle;"><?php echo $data->no_producto; ?></td>
                <td style="vertical-align: middle;"><?php echo $data->stock; ?></td>
                <td style="vertical-align: middle;"><?php echo $data->stock_interno; ?></td>
                <td style="vertical-align: middle;"><?php echo $data->stock_minimo; ?></td>
            </tr>
        <?php 
          $i++;
        } 
      ?>    
    </table>
    <?php }?>
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
