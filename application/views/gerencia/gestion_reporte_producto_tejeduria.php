<script type="text/javascript">
  $(function(){
    /*
    $("#categoria").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    $("#procedencia").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    $("#moneda").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    */
    $("#maquina").change(function() {
    $("#maquina option:selected").each(function() {
            maquina = $('#maquina').val();
            $.post("<?php echo base_url(); ?>gerencia/traeMarca_tejeduria", {
                maquina : maquina , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function(data) {
                $("#marca").html(data);
                $("#modelo").html('<option value="0">:: SELECCIONE UNA MARCA ::</option>');
            });
        });
    });
    $("#marca").change(function() {
    $("#marca option:selected").each(function() {
            marca = $('#marca').val();
            $.post("<?php echo base_url(); ?>gerencia/traeModelo_tejeduria", {
                marca : marca , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function(data) {
                $("#modelo").html(data);
            });
        });
    });

    $("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
  });
</script>
</head>
<body>
  <div id="contenedor" style="width: 1150px;">
    <div id="tituloCont" style="margin-bottom: 0px;width: 1200px;">Gestión de Reporte de Productos</div>
    <div id="formFiltro">
      <?php echo form_open(base_url()."gerencia/reporteproductospdf_tejeduria", 'id="reporteproductospdf"') ?>
        <table width="320" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Categoria</b></td>
          </tr>
          <tr>
            <td width="94" height="30">Categoria:</td>
            <td width="226"><?php echo form_dropdown('categoria',$listacategoria,'','id="categoria" style="width:140px;"');?></td>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Procedencia</b></td>
          </tr>
          <tr>
            <td width="94" height="30">Procedencia:</td>
            <td width="226"><?php echo form_dropdown('procedencia',$listaprocedencia,'','id="procedencia" style="width:140px;"');?></td>
          </tr>
          <!--
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Máquina</b></td>
          </tr>
          -->
        </table>
        <!--
        <table width="320" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100" height="30">Tipo de Máquina:</td>
            <?php
              $existe = count($listamaquina);
              if($existe <= 0){ ?>
                <td width="174" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
            <?php    
                }
                else
                {
              ?>
                <td width="46" height="30"><?php echo form_dropdown('maquina',$listamaquina,'',"id='maquina' style='width:130px;'");?></td>
            <?php }?>
          </tr>
          <tr>
              <td width="100" valign="middle" height="30">Marca:</td>
              <td>
                <select name="marca" id="marca" class='required' style='width:130px;'></select>
              </td>
          </tr>
          <tr>
            <td width="100" valign="middle">Modelo:</td>
            <td>
              <select name="modelo" id="modelo" class='required' style='width:170px;'></select>
            </td>
          </tr>
          </table>
          -->
          <table width="320" border="0" cellspacing="0" cellpadding="0">
            <tr style="height:30px;"> 
              <td colspan="2" style=" padding-top: 5px; padding-left: 139px;"><input name="submit" type="submit" id="submit" value="Exportar a PDF" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;margin-right: 15px;" /></td>
            </tr>
          </table>
      <?php echo form_close() ?>
  </div>
  </div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>