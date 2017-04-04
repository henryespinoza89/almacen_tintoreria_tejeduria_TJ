<?php if($tipocambio == 1){?>
  <div id="newtipocambio" title="Registrar Tipo de Cambio" style="font-size:11px;min-height:47px;padding-left: 90px;width: 750px !important;height: auto;">
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
          <td width="90" height="30">Tipo de Cambio:</td>
          <td width="347" height="30"><a href="http://www.sbs.gob.pe/app/stats/tc-cv.asp" id="tipo_cambio" target="_blank">Superintendencia de Banca, Seguros y AFP</a></td>
        </tr>
      </table>
      <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 15px;margin-bottom:5px;margin-top: 5px;">
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
      <fieldset style="border: 1px dashed #999999;width: 240px;float: left;margin-right: 15px;margin-bottom:5px;margin-top: 5px;">
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