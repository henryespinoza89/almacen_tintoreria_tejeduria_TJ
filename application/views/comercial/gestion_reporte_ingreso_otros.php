<?php
  //Ingresar Fecha de consultar
  if ($this->input->post('fecharegistro')){
    $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
  //Ingresa fecha inicial de consulta por periodo
  if ($this->input->post('fechainicial')){
    $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10','value'=>$this->input->post('fechainicial'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
  //Ingresa fecha inicial de consulta por periodo
  if ($this->input->post('fechafinal')){
    $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10','value'=>$this->input->post('fechafinal'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
  if ($this->input->post('fechainicial_2')){
    $fechainicial_2 = array('name'=>'fechainicial_2','id'=>'fechainicial_2','maxlength'=>'10','value'=>$this->input->post('fechainicial_2'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechainicial_2 = array('name'=>'fechainicial_2','id'=>'fechainicial_2','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
  //Ingresa fecha inicial de consulta por periodo
  if ($this->input->post('fechafinal_2')){
    $fechafinal_2 = array('name'=>'fechafinal_2','id'=>'fechafinal_2','maxlength'=>'10','value'=>$this->input->post('fechafinal_2'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechafinal_2 = array('name'=>'fechafinal_2','id'=>'fechafinal_2','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
?>
<script type="text/javascript">
  $(function(){
    $("#fecharegistro").datepicker({ 
        dateFormat: 'dd-mm-yy',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechainicial").datepicker({ 
        dateFormat: 'dd-mm-yy',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechainicial_2").datepicker({ 
        dateFormat: 'dd-mm-yy',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechafinal").datepicker({ 
        dateFormat: 'dd-mm-yy',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechafinal_2").datepicker({ 
        dateFormat: 'dd-mm-yy',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
  });
</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont" style="margin-bottom:0px;">Gestión de Reporte de Ingresos de Productos</div>
    <div id="formFiltro">
      <?php echo form_open(base_url()."comercial/reporteingresospdf_otros", 'id="reporteingresospdf_otros"') ?>
        <table width="611" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Fecha de Registro</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Fecha de Registro:</td>
            <td width="148" height="30"><?php echo form_input($fecharegistro);?></td>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Periodo</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Fecha de Inicio:</td>
            <td width="148" height="30"><?php echo form_input($fechainicial);?></td>
            <td width="84" height="30">Fecha Final:</td>
            <td width="254" height="30"><?php echo form_input($fechafinal);?></td>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Tipo de Moneda</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Moneda:</td>
            <?php
                $existe = count($listasimmon);
                if($existe <= 0){ ?>
                  <td width="148" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
            <?php    
                }
                else
                {
              ?>
                <td width="84" height="30"><?php echo form_dropdown('moneda',$listasimmon,'$selected_moneda','id="moneda" style="width:158px;"');?></td>
            <?php }?>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Tipo de Comprobante</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Comprobante:</td>
            <?php
                $existe = count($listacomprobante);
                if($existe <= 0){ ?>
                  <td width="148" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
            <?php    
                }
                else
                {
              ?>
                  <td width="84" height="30"><?php echo form_dropdown('comprobante',$listacomprobante,'$selected_compro','id="comprobante" style="width:158px; "');?></td>
              <?php }?>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Proveedor</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Proveedor:</td>
            <?php
                $existe = count($listaproveedor);
                if($existe <= 0){ ?>
                  <td width="148" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
            <?php    
                }
                else
                {
              ?>
                  <td width="84" height="30"><?php echo form_dropdown('proveedor',$listaproveedor,'$selected_prov','id="proveedor" style="width:158px;"');?></td>
              <?php }?>
          </tr>
          <tr width="89" height="30"> 
            <td colspan="4" style=" padding-top: 5px;"><input name="submit" type="submit" id="submit" class="submit_report_ing_ag" value="Exportar a PDF"/></td>
          </tr>
        </table>
      <?php echo form_close() ?>
      <?php echo form_open(base_url()."comercial/reporteingreso_producto_pdf_otros", 'id="reporte_filtro_producto_otros"') ?>
          <table width="481" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Producto</b></td>
            </tr>
            <tr>
              <td width="63" height="30">Produto:</td>
              <?php
                $existe = count($listanombreproducto);
                if($existe <= 0){ ?>
                  <td width="239" height="30"><b><?php echo 'No Existen Productos Registrados en el Sistema';?></b></td>
              <?php    
                }
                else
                {
              ?>
                  <td width="179" height="30"><?php echo form_dropdown('nomproducto',$listanombreproducto,'','id="nomproducto" style="width:324px;"');?></td>
              <?php }?>
            </tr>
          </table>
          <table width="521" border="0" cellspacing="0" cellpadding="0">  
            <tr>
              <td style="height:30px; color:#005197;" colspan="4"><b>Filtrar Consulta de Productos Registrados por Periodo</b></td>
            </tr>
            <tr>
              <td width="109" height="30">Fecha de Inicio:</td>
              <td width="149" height="30"><?php echo form_input($fechainicial_2);?></td>
              <td width="85" height="30">Fecha Final:</td>
              <td width="178" height="30"><?php echo form_input($fechafinal_2);?></td>
            </tr>
            <tr>
              <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Tipo de Comprobante</b></td>
            </tr>
            <tr>
              <td width="125" height="30">Comprobante:</td>
              <?php
                  $existe = count($listacomprobante);
                  if($existe <= 0){ ?>
                    <td width="148" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
              <?php    
                  }
                  else
                  {
                ?>
                    <td width="84" height="30"><?php echo form_dropdown('comprobante',$listacomprobante,'$selected_compro','id="comprobante" style="width:158px; "');?></td>
                <?php }?>
            </tr>
            <tr width="89" height="30"> 
              <td colspan="4" style=" padding-top: 5px;"><input name="submit" type="submit" id="submit" class="submit_report_ingr_prod" value="Exportar a PDF"/></td>
            </tr>
          </table>
        <?php echo form_close() ?>
  </div>
  </div>