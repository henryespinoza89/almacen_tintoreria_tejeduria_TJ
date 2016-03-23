<div id="contenedor" style="width:1075px; min-height:240px; height: auto;">
	<div id="tituloCont">Detalle del Comprobante</div>
	<div id="formFiltro" style="width:500px;">
		<?php 
			$existe = count($detFac);
			if($existe <= 0){
				echo 'El Detalle de la Factura no existe en la Base de Datos.';
			}
			else
			{
		?>
    	<form style="border-bottom:0px; width: 900px; margin-bottom: 0px;">
	    	<table style="width:774px">
	    	<?php
				$i=1;
				foreach($detFac as $detalleFactura){
				  #Datos del USUARIO
					$detProv = array('name'=>'detProv','id'=>'detProv','maxlength'=>'50', 'value'=>$detalleFactura->razon_social, 'style'=>'width: 350px;border-style: initial;margin-bottom: 0;' ,'readonly'=> 'readonly');
					$detNumFact = array('name'=>'detNumFact','id'=>'detNumFact','maxlength'=>'40', 'value'=>$detalleFactura->nro_comprobante, 'style'=>'width:80px;border-style: initial;margin-bottom: 0;','readonly'=> 'readonly');
					$detFecha = array('name'=>'detFecha','id'=>'detFecha','maxlength'=>'10', 'value'=>$detalleFactura->fecha, 'style'=>'width: 80px;border-style: initial;margin-bottom: 0;','readonly'=> 'readonly');
					$detMoneda = array('name'=>'detMoneda','id'=>'detMoneda','maxlength'=>'30', 'value'=>$detalleFactura->nombresimbolo, 'style'=>'width: 80px;border-style: initial;margin-bottom: 0;','readonly'=> 'readonly');
					$detcompro = array('name'=>'detcompro','id'=>'detcompro','maxlength'=>'30', 'value'=>$detalleFactura->no_comprobante, 'style'=>'width:60px;border-style: initial;margin-bottom: 0;','readonly'=> 'readonly');
					$cs_igv = $detalleFactura->cs_igv;
			?>
				<tr>
					<td width="140" height="25">Tipo de Comprobante:</td>
					<td width="83"><?php echo form_input($detcompro); ?></td>
					<td width="123">Número de Factura:</td>
					<td width="51"><?php echo form_input($detNumFact); ?></td>
					<td width="116">Fecha de Registro:</td>
					<td width="84"><?php echo form_input($detFecha); ?></td>
					<td width="58">Moneda:</td>
					<td width="83"><?php echo form_input($detMoneda); ?></td>
				</tr>
			</table>
			<table width="482">
	    		<tr>
					<td width="67" height="25">Proveedor:</td>
					<td width="403"><?php echo form_input($detProv); ?></td>
				</tr>
				<?php }?>
			</table>
	 	</form>
	 	<?php } ?>
	 	<!--Iniciar listar-->
        <?php 
          $existe = count($detProd);
          if($existe <= 0){
            echo 'No existen Productos registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaProductos" style="width:1064px;">
          <thead>
            <tr class="tituloTable" style="font-size: 12px;">
              <td sort="idprod" width="45" height="25">Item</td>
              <td sort="idproducto" width="54" height="25">Cantidad</td>
              <td sort="nombreprod" width="225">Nombre o Descripción</td>
              <td sort="catprod" width="77">ID Producto</td>
              <td sort="procprod" width="74">Precio Unitario</td>
              <td sort="procprod" width="74">Valor Total</td>
              <!--
              <td width="20">&nbsp;</td>
              <td width="20">&nbsp;</td>
              -->
            </tr>
          </thead>
          <?php 
          	$i=1;
          	$sub_total=0;
          	foreach($detProd as $listardetallefactura){ 
          ?>
          <tr class="contentTable" style="font-size: 11px;height: 32px;border-color: #F1EEEE;border-bottom-style: solid;">
            <td><?php echo str_pad($i, 5, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listardetallefactura->unidades; ?></td>
            <td><?php echo $listardetallefactura->no_producto; ?></td>
            <!---->
            <td><?php echo 'PRD'.$listardetallefactura->id_pro; ?></td>
            <td><?php echo number_format($listardetallefactura->precio,3,'.',','); ?></td>
            <td><?php echo number_format($listardetallefactura->valor_total,2,'.',','); ?></td>
          </tr>
        <?php
        	$sub_total = $sub_total + $listardetallefactura->valor_total;
        	$i++;
        	}
        	/* True : Con IGV */
        	/* False : Sin IGV */
        	if($cs_igv == "t"){
                $total = $sub_total;
                $sub_total = $sub_total / 1.18;
            }else if($cs_igv == "f"){
                $total = $sub_total * 1.18;
                $sub_total = $sub_total;
            }
        	$igv = $sub_total * 0.18;
        ?>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;font-size: 11px;"> SUB-TOTAL: </td>
        	<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;font-size: 11px;"><?php echo number_format($sub_total,2,'.',','); ?></td>
        </tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;font-size: 11px;"> I.G.V. 18%: </td>
			<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;font-size: 11px;"><?php echo number_format($igv,2,'.',','); ?></td>
		</tr>
		<tr>
		    <td></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;font-size: 11px;"> TOTAL: </td>
		    <!--<td colspan="5">TOTAL:</td>-->
		    <td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;font-size: 11px;"><?php echo number_format($total,2,'.',','); ?></td>
		</tr>
        </table>
        <?php }?>
	</div>
</div>