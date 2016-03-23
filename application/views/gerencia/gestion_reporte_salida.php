<?php
  $solicitante = array('name'=>'solicitante','id'=>'solicitante','maxlength'=>'30', 'style'=>'width:150px', 'class'=>'required');
  //Ingresar Fecha de consultar
  if ($this->input->post('fecharegistro')){
    $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }else{
    $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
  if ($this->input->post('fecharegistro_X')){
    $fecharegistro = array('name'=>'fecharegistro_X','id'=>'fecharegistro_X','maxlength'=>'10','value'=>$this->input->post('fecharegistro_X'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }else{
    $fecharegistro_X = array('name'=>'fecharegistro_X','id'=>'fecharegistro_X','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
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
  if ($this->input->post('fechainicial_3')){
    $fechainicial_3 = array('name'=>'fechainicial_3','id'=>'fechainicial_3','maxlength'=>'10','value'=>$this->input->post('fechainicial_3'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechainicial_3 = array('name'=>'fechainicial_3','id'=>'fechainicial_3','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
  //Ingresa fecha inicial de consulta por periodo
  if ($this->input->post('fechafinal_3')){
    $fechafinal_3 = array('name'=>'fechafinal_3','id'=>'fechafinal_3','maxlength'=>'10','value'=>$this->input->post('fechafinal_3'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechafinal_3 = array('name'=>'fechafinal_3','id'=>'fechafinal_3','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }


  if ($this->input->post('fechainicial_X')){
    $fechainicial_X = array('name'=>'fechainicial_X','id'=>'fechainicial_X','maxlength'=>'10','value'=>$this->input->post('fechainicial_X'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechainicial_X = array('name'=>'fechainicial_X','id'=>'fechainicial_X','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
  }
  //Ingresa fecha inicial de consulta por periodo
  if ($this->input->post('fechafinal_X')){
    $fechafinal_X = array('name'=>'fechafinal_X','id'=>'fechafinal_X','maxlength'=>'10','value'=>$this->input->post('fechafinal_X'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');

  }else{
    $fechafinal_X = array('name'=>'fechafinal_X','id'=>'fechafinal_X','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
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
    $("#fecharegistro_X").datepicker({ 
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
    $("#fechainicial_2").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechainicial_3").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechainicial_X").datepicker({ 
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
    $("#fechafinal_2").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechafinal_3").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#fechafinal_X").datepicker({ 
        dateFormat: 'yy-mm-dd',showOn: "button",
        buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
        buttonImageOnly: true
      });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    $("#maquina").change(function() {
    $("#maquina option:selected").each(function() {
            maquina = $('#maquina').val();
            $.post("<?php echo base_url(); ?>gerencia/traeMarca", {
                maquina : maquina , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function(data) {
                $("#marca").html(data);
                $("#modelo").html('<option value="0">:: SELECCIONE UNA MARCA ::</option>');
                $("#serie").html('<option value="0">:: SELECCIONE UN MODELO ::</option>');
            });
        });
    });

    $("#marca").change(function() {
    $("#marca option:selected").each(function() {
            marca = $('#marca').val();
            $.post("<?php echo base_url(); ?>gerencia/traeModelo", {
                marca : marca , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function(data) {
                $("#modelo").html(data);
                $("#serie").html('<option value="0">:: SELECCIONE UN MODELO ::</option>');
            });
        });
    });

    $("#modelo").change(function() {
    $("#modelo option:selected").each(function() {
            modelo = $('#modelo').val();
            $.post("<?php echo base_url(); ?>gerencia/traeSerie", {
                modelo : modelo , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function(data) {
                $("#serie").html(data);
                //$("#Serie").html('<option value="0">:: SELECCIONE UN MODELO ::</option>');
            });
        });
    });
    $("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
  });
</script>
</head>
<body>
  <div id="contenedor" style="width: 1150px;">
    <div id="tituloCont" style="margin-bottom:0px;width: 1150px;">Gestión de Reporte de Salida de Productos</div>
    <div id="formFiltro">
      <?php echo form_open(base_url()."gerencia/reportesalidapdf", 'id="reportesalidapdf"') ?>
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
        </table>
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
        <table style="float: left; margin-top: 5px; width: 300px; margin-left: 0px;">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Máquina</b></td>
          </tr>
          <tr>
            <td width="148" valign="middle">Máquina:</td>
                  <?php
                    $existe = count($listamaquina);
                    if($existe <= 0){ ?>
                      <td width="330" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
                <?php    
                    }
                    else
                    {
                  ?>
                      <td width="370"><?php echo form_dropdown('maquina', $listamaquina,'$selected_maquina',"id='maquina' class='required' style='width:170px;'");?></td>
                  <?php }?>
          </tr>   
          <tr>
            <td width="148" valign="middle" height="30">Marca:</td>
            <td>
              <select name="marca" id="marca" class='required' style='width:170px;'></select>
              <?php //echo form_dropdown('marca','',$selected_marca,"id='marca' class='required' style='width:170px;'");?>
            </td>
          </tr>
          <tr>
            <td width="148" valign="middle" height="30">Modelo:</td>
            <td>
              <select name="modelo" id="modelo" class='required' style='width:170px;'></select>
            </td>
          </tr>
          <tr>
            <td width="148" valign="middle" height="30">Serie:</td>
            <td>
              <select name="serie" id="serie" class='required' style='width:170px;'></select>
            </td>
          </tr>
        </table>
        <table style="float: left; margin-top: 5px; width: 300px; margin-left: 0px;">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Máquina</b></td>
          </tr>
          <tr>
            <td width="148" valign="middle">Área:</td>
              <?php
                $existe = count($listaarea);
                if($existe <= 0){ ?>
                  <td width="330" height="28"><b><?php echo 'Registrar Área';?></b></td>
              <?php    
                  }
                  else
                  {
                ?>
                  <td width="330"><?php echo form_dropdown('area',$listaarea,'$selected_area',"id='area' style='width:150px;'" );?></td>
                <?php }?>
          </tr>
        </table>
        <table style="width:500px;">
          <tr width="89" height="30"> 
            <td colspan="4" style=" padding-top: 5px;"><input name="submit" type="submit" id="submit" class="submit_report_salida" value="Exportar a PDF"/></td>
          </tr>
        </table>
        <?php echo form_close() ?>
        <?php echo form_open(base_url()."gerencia/reportesalida_solicitante_pdf_sta_clara", 'id="reporte_filtro_asistente"') ?>
        <table width="387" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Solicitante</b></td>
          </tr>
          <tr>
            <td width="81" height="30">Solicitante:</td>
            <td width="310" height="30"><?php echo form_input($solicitante);?></td>
          </tr>
        </table>      
        <table width="611" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Fecha de Registro</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Fecha de Registro:</td>
            <td width="148" height="30"><?php echo form_input($fecharegistro_X);?></td>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Periodo</b></td>
          </tr>
          <tr>
            <td width="125" height="30">Fecha de Inicio:</td>
            <td width="148" height="30"><?php echo form_input($fechainicial_X);?></td>
            <td width="84" height="30">Fecha Final:</td>
            <td width="254" height="30"><?php echo form_input($fechafinal_X);?></td>
          </tr>
        </table>
        <table style="width:500px;">
          <tr width="89" height="30"> 
            <td colspan="4" style=" padding-top: 5px;"><input name="submit" type="submit" id="submit" class="submit_report_sal_asis" value="Exportar a PDF"/></td>
          </tr>
        </table>
      <?php echo form_close() ?>
  </div>
  </div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
