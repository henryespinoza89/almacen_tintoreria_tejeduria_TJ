<script type="text/javascript">
  $(function(){
    $("#maquina").change(function() {
    $("#maquina option:selected").each(function() {
            maquina = $('#maquina').val();
            $.post("<?php echo base_url(); ?>comercial/traeMarca", {
                maquina : maquina , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function(data) {
                $("#marca").html(data);
            });
        });
    });
    $("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
  });
</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont" style="margin-bottom: 0px;">Gestión de Reporte de Máquinas</div>
    <div id="formFiltro">
      <?php echo form_open(base_url()."comercial/reportemaquinaspdf", 'id="reportemaquinapdf"') ?>
        <table width="320" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Tipo de Máquina</b></td>
          </tr>
          <tr>
            <td width="61" height="30">Tipo de Máquina:</td>
            <?php
              $existe = count($listamaquina);
              if($existe <= 0){ ?>
                <td width="213" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
            <?php    
                }
                else
                {
              ?>
                <td width="46" height="30"><?php echo form_dropdown('maquinaS',$listamaquina,'',"id='maquinaS' style='width:130px;'");?></td>
            <?php }?>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Marca de Máquina</b></td>
          </tr>
          <tr>
            <td width="61" height="30">Tipo de Máquina:</td>
            <?php
              $existe = count($listamaquina);
              if($existe <= 0){ ?>
                <td width="213" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
            <?php    
                }
                else
                {
              ?>
                <td width="46" height="30"><?php echo form_dropdown('maquina',$listamaquina,'',"id='maquina' style='width:130px;'");?></td>
            <?php }?>
          </tr>
          <tr>
            <td width="61" valign="middle" height="30">Marca:</td>
            <td>
              <select name="marca" id="marca" class='required' style='width:130px;'></select>
            </td>
          </tr>
          <tr>
            <td style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Estado de Máquina</b></td>
          </tr>
          <tr>
            <td width="61" height="30">Estado de Máquina:</td>
            <?php
              $existe = count($estado);
              if($existe <= 0){ ?>
                <td width="213" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
            <?php    
                }
                else
                {
              ?>
                <td width="46" height="30"><?php echo form_dropdown('estado',$estado, '',"id='estado' style='width:130px;'");?></td>
            <?php }?>
          </tr>
          <tr width="89" height="30"> 
            <td colspan="2" style=" padding-top: 5px;"><input name="submit" type="submit" id="submit" class="submit_report_maq" value="Exportar a PDF"/></td>
          </tr>
        </table>
      <?php echo form_close() ?>
  </div>
  </div>