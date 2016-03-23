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
?>
<script type="text/javascript">
  $(function(){
    $("#fecharegistro").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechainicial").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechafinal").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
  });
</script>
</head>
<body>
  <div id="contenedor" style="width: 1150px;">
    <div id="tituloCont" style="margin-bottom: 0px;width: 1200px;">Gestión de Reporte de Proveedores</div>
    <div id="formFiltro">
      <?php echo form_open(base_url()."gerencia/reporteproveedorespdf_tejeduria", 'id="reporteproveedorpdf"') ?>
        <table width="611" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Fecha de Registro</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Fecha de Registro:</td>
            <td width="167" height="30"><?php echo form_input($fecharegistro);?></td>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Periodo</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Fecha de Inicio:</td>
            <td width="167" height="30"><?php echo form_input($fechainicial);?></td>
            <td width="102" height="30">Fecha Final:</td>
            <td width="217" height="30"><?php echo form_input($fechafinal);?></td>
          </tr>
          <tr width="89" height="30"> 
            <td colspan="4" style=" padding-top: 5px;"><input name="submit" type="submit" id="submit" class="submit_report_prov" value="Exportar a PDF"/></td>
          </tr>
        </table>
      <?php echo form_close() ?>
  </div>
  </div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>