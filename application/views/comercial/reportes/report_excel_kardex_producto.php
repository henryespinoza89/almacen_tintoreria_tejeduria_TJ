<style>
	#style_table_kardex{
		text-align:center; 
		vertical-align: middle;
		border: 1px solid #000;
		font-size: 13px;
		padding-left:10px;
	}
	#style_table_kardex_cabecera{
		text-align:center; 
		vertical-align: middle;
		border: 1px solid #000;
		font-size: 13px;
		padding-left:10px;
		background: antiquewhite;
	}
	#legend_table_kardex{
		text-align:left; 
		font-weight:bold;
		vertical-align: middle;
		border: 0px solid #000;
		font-size: 10px;
		font-family:Arial;
		padding-left:10px;
	}
</style>
<?php
	
	foreach($datos_prod as $data){
		$nombre_producto = $data->no_producto;
		$codigo_producto = $data->id_producto;
		$codigo_unidad_medida = $data->id_unidad_medida;
	}
	//Exportamos a Excel los resultados
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$nombre_producto.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<?php
	$sumatoria_cantidad_entradas = 0;
	$sumatoria_parciales_entradas = 0;

	$sumatoria_cantidad_salidas = 0;
	$sumatoria_parciales_salidas = 0;

	$sumatoria_cantidad_saldos = 0;
	$sumatoria_parciales_saldos = 0;
?>
<table width="2450" border="0" cellspacing="0" cellpadding="0">
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('INVENTARIO PERMANENTE VALORIZADO'); ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('FT: FACTURA'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('PERIODO'); ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('GR: GUÍA DE REMISIÓN'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('RUC: 20101717098'); ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('BV: BOLETA DE VENTA'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('TEJIDOS JORGITO SRL'); ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('NC: NOTA DE CRÉDITO'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('CALLE LOS TELARES No 103-105 URB. VULCANO-ATE'); ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('ND: NOTA DE DÉBITO'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('CÓDIGO: ').$codigo_producto; ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('OS: ORDEN DE SALIDA'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('TIPO: 05'); ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('OI: ORDEN DE INGRESO'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('DESCRIPCIÓN: ').$nombre_producto; ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('CU: COSTO UNITARIO (NUEVOS SOLES)'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('UNIDAD DE MEDIDA: ').$codigo_unidad_medida; ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('CT: COSTO TOTAL (NUEVOS SOLES)'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('MÉTODO DE EVALUACIÓN: COSTO PROMEDIO'); ?></td>
		<td width="450" height="20" colspan="5"><?php echo utf8_decode('SI: SALDO INICIAL'); ?></td>
	</tr>
	<tr id="legend_table_kardex">
		<td width="450" height="20" colspan="5"><?php echo "" ?></td>
	</tr>
	<tr>
	    <td id="style_table_kardex_cabecera" width="360" height="20" colspan="4"><?php echo utf8_decode('DOCUMENTO DE MOVIMIENTO'); ?></td>
	    <td id="style_table_kardex_cabecera" width="270" height="20" colspan="3"><?php echo utf8_decode('ENTRADAS'); ?></td>
	    <td id="style_table_kardex_cabecera" width="270" height="20" colspan="3"><?php echo utf8_decode('SALIDAS'); ?></td>
	    <td id="style_table_kardex_cabecera" width="270" height="20" colspan="3"><?php echo utf8_decode('SALDO FINAL'); ?></td>
	</tr>
	<tr>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('FECHA'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('TIPO'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('SERIE'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('NÚMERO'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CANTIDAD'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CU'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CT'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CANTIDAD'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CU'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CT'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CANTIDAD'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CU'); ?></td>
	    <td id="style_table_kardex" width="90" height="20"><?php echo utf8_decode('CT'); ?></td>
	</tr>
	<?php 
		if( count($result_saldo) > 0 ) {
			foreach($result_saldo as $data){
	?>
		<tr>
		    <td height="20" id="style_table_kardex"><?php echo utf8_decode($data->fecha_cierre); ?></td>
		    <td id="style_table_kardex"><?php echo ""; ?></td>
		    <td id="style_table_kardex"><?php echo "SI"; ?></td>
		    <td id="style_table_kardex"><?php echo ""; ?></td>
		    <!-- DATOS DEL INGRESO DEL PRODUCTO -->
		    <!-- Cantidad deL producto que quedo como saldo inicial -->
		    <td id="style_table_kardex"><?php echo number_format($data->stock_inicial,2,'.',',');?></td>
		    <!-- End -->
		    <!-- Precio unitario con el cual el producto que como saldo inicial -->
		    <td id="style_table_kardex"><?php echo number_format($data->precio_uni_inicial,2,'.',',');?></td>
		    <!-- End -->
		    <!-- Multiplicación precio del producto * cantidad que quedo como saldo inicial -->
		    <td id="style_table_kardex"><?php echo number_format($data->stock_inicial,2,'.',',') * number_format($data->precio_uni_inicial,2,'.',',');?></td>
		    <!-- End -->
		    <!-- DATOS DE LA SALIDA DEL PRODUCTO -->
		    <!-- Cantidad de salida, este dato corresponde a las unidades que se pide de almacen -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- Precio unitario del producto para su salida, cuando es salida este dato no se toca -->
		    <td id="style_table_kardex"><?php echo number_format($data->precio_uni_inicial,2,'.',',');?></td>
		    <!-- End -->
		    <!-- Multiplicación precio unitario del producto * cantidad salida -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- DATOS DE SALDO FINAL DEL PRODUCTO -->
		    <!-- Stock actual corresponde a la cantidad que va quedando del producto, luego de una salida o ingreso -->
		    <td id="style_table_kardex"><?php echo number_format($data->stock_inicial,2,'.',',');?></td>
		    <!-- End -->
		    <!-- Precio unitario del producto luego de un ingreso o salida -->
		    <!-- Cuando es una salida el precio unitario es el mismo -->
		    <td id="style_table_kardex"><?php echo number_format($data->precio_uni_inicial,2,'.',',');?></td>
		    <!-- End -->
		    <!-- Multiplicacion entre la cantidad que queda del producto y el precio unitario,
		    cuando es una salida el precio unitario es el mismo, pero cuando es un ingreso se toma el precio unitario ponderado -->
		    <td id="style_table_kardex"><?php echo number_format($data->stock_inicial,2,'.',',') * number_format($data->precio_uni_inicial,2,'.',',');?></td>
		    <!-- End -->
		</tr>
	<?php
				/* ENTRADAS */
				$sumatoria_cantidad_entradas = $sumatoria_cantidad_entradas + $data->stock_inicial;
				$sumatoria_parciales_entradas = $sumatoria_parciales_entradas + (number_format($data->stock_inicial,2,'.',',') * number_format($data->precio_uni_inicial,2,'.',','));
				/* SALDOS */
				$sumatoria_cantidad_saldos = $sumatoria_cantidad_saldos + $data->stock_inicial;
				$sumatoria_parciales_saldos = $sumatoria_parciales_saldos + (number_format($data->stock_inicial,2,'.',',') * number_format($data->precio_uni_inicial,2,'.',','));
			}
		}else{
	?>
		<tr>
		    <td height="20" id="style_table_kardex"><?php echo utf8_decode($fecha_saldos); ?></td>
		    <td id="style_table_kardex"><?php echo ""; ?></td>
		    <td id="style_table_kardex"><?php echo "SI"; ?></td>
		    <td id="style_table_kardex"><?php echo ""; ?></td>
		    <!-- DATOS DEL INGRESO DEL PRODUCTO -->
		    <!-- Cantidad deL producto que quedo como saldo inicial -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- Precio unitario con el cual el producto que como saldo inicial -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- Multiplicación precio del producto * cantidad que quedo como saldo inicial -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- DATOS DE LA SALIDA DEL PRODUCTO -->
		    <!-- Cantidad de salida, este dato corresponde a las unidades que se pide de almacen -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- Precio unitario del producto para su salida, cuando es salida este dato no se toca -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- Multiplicación precio unitario del producto * cantidad salida -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- DATOS DE SALDO FINAL DEL PRODUCTO -->
		    <!-- Stock actual corresponde a la cantidad que va quedando del producto, luego de una salida o ingreso -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- Precio unitario del producto luego de un ingreso o salida -->
		    <!-- Cuando es una salida el precio unitario es el mismo -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		    <!-- Multiplicacion entre la cantidad que queda del producto y el precio unitario,
		    cuando es una salida el precio unitario es el mismo, pero cuando es un ingreso se toma el precio unitario ponderado -->
		    <td id="style_table_kardex"><?php echo "0.00";?></td>
		    <!-- End -->
		</tr>
	<?php
		}
	?>
	<?php
		$existe = count($producto);
		if($existe > 0){
            foreach($producto as $data){		
	?>
	<tr>
	    <td height="20" id="style_table_kardex"><?php echo utf8_decode($data->fecha_registro); ?></td>
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo "OS";
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo "FT";
		    	}
	    	?>
	    </td>
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo "NIG";
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo $data->serie_comprobante;
		    	}
	    	?>
	    </td>
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo $data->id_kardex_producto;
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo $data->num_comprobante;
		    	}
	    	?>
	    </td>
	    <!-- DATOS DEL INGRESO DEL PRODUCTO -->
	    <!-- Cantidad de ingreso, este dato viene de la factura con la que ingreso el producto -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo "0.00";
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo number_format($data->cantidad_ingreso,2,'.',',');
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- Precio unitario con el cual el producto entro con la factura -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo "0.00";
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo number_format($data->precio_unitario_actual,2,'.',',');
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- Multiplicación precio del producto en la factura * cantidad ingresada -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo "0.00";
		    	}else if($data->descripcion == "ENTRADA"){
		    		$n1 = number_format($data->cantidad_ingreso,2,'.',',');
		    		$n2 = number_format($data->precio_unitario_actual,2,'.',',');
		    		echo number_format( ($n1 * $n2),2,'.',',');
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- DATOS DE LA SALIDA DEL PRODUCTO -->
	    <!-- Cantidad de salida, este dato corresponde a las unidades que se pide de almacen -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo number_format($data->cantidad_salida,2,'.',',');
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo "0.00";
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- Precio unitario del producto para su salida, cuando es salida este dato no se toca -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo number_format($data->precio_unitario_actual,2,'.',',');
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo "0.00";
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- Multiplicación precio unitario del producto * cantidad salida -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo number_format(number_format($data->cantidad_salida,2,'.',',') * number_format($data->precio_unitario_actual,2,'.',','),2,'.',',');
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo "0.00";
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- DATOS DE SALDO FINAL DEL PRODUCTO -->
	    <!-- Stock actual corresponde a la cantidad que va quedando del producto, luego de una salida o ingreso -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo number_format($data->stock_actual,2,'.',',');
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo number_format($data->stock_actual,2,'.',',');
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- Precio unitario del producto luego de un ingreso o salida -->
	    <!-- Cuando es una salida el precio unitario es el mismo -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo number_format($data->precio_unitario_actual,2,'.',',');
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo number_format($data->precio_unitario_actual_promedio,2,'.',',');
		    	}
	    	?>
	    </td>
	    <!-- End -->
	    <!-- Multiplicacion entre la cantidad que queda del producto y el precio unitario,
	    cuando es una salida el precio unitario es el mismo, pero cuando es un ingreso se toma
	    el precio unitario ponderado -->
	    <td id="style_table_kardex"><?php 
		    	if($data->descripcion == "SALIDA"){
		    		echo number_format(number_format($data->precio_unitario_actual,2,'.',',') * number_format($data->stock_actual,2,'.',','),2,'.',',');
		    	}else if($data->descripcion == "ENTRADA"){
		    		echo number_format(number_format($data->precio_unitario_actual_promedio,2,'.',',') * number_format($data->stock_actual,2,'.',','),2,'.',',');
		    	}
	    	?>
	    </td>
	    <!-- End -->
	</tr>
	<?php
				/* ENTRADAS */
				$sumatoria_cantidad_entradas = $sumatoria_cantidad_entradas + number_format($data->cantidad_ingreso,2,'.',',');
				$sumatoria_parciales_entradas = $sumatoria_parciales_entradas + (number_format($data->cantidad_ingreso,2,'.',',') * number_format($data->precio_unitario_actual,2,'.',','));
				/* SALIDAS */
				$sumatoria_cantidad_salidas = $sumatoria_cantidad_salidas + $data->cantidad_salida;
				$sumatoria_parciales_salidas = $sumatoria_parciales_salidas + (number_format($data->cantidad_salida,2,'.',',') * number_format($data->precio_unitario_actual,2,'.',','));
				/* SALDOS */
				$sumatoria_cantidad_saldos = $sumatoria_cantidad_saldos + $data->stock_actual;
				if($data->descripcion == "SALIDA"){
		    		$sumatoria_parciales_saldos = $sumatoria_parciales_saldos + (number_format($data->precio_unitario_actual,2,'.',',') * number_format($data->stock_actual,2,'.',','));
		    	}else if($data->descripcion == "ENTRADA"){
		    		$sumatoria_parciales_saldos = $sumatoria_parciales_saldos + (number_format($data->precio_unitario_actual_promedio,2,'.',',') * number_format($data->stock_actual,2,'.',','));
		    	}
			}
		}
	?>
	<tr>
	    <td height="20"><?php echo "" ?></td>
	    <td height="20"><?php echo "" ?></td>
	    <td height="20"><?php echo "" ?></td>
	    <td height="20" id="style_table_kardex"><?php echo "TOTALES" ?></td>
	    <td id="style_table_kardex"><?php echo number_format($sumatoria_cantidad_entradas,2,'.',','); ?></td>
	    <td id="style_table_kardex" height="20"><?php echo "" ?></td>
	    <td id="style_table_kardex"><?php echo number_format($sumatoria_parciales_entradas,2,'.',','); ?></td>
	    <td id="style_table_kardex"><?php echo number_format($sumatoria_cantidad_salidas,2,'.',','); ?></td>
	    <td id="style_table_kardex" height="20"><?php echo "" ?></td>
	    <td id="style_table_kardex"><?php echo number_format($sumatoria_parciales_salidas,2,'.',','); ?></td>
	    <td id="style_table_kardex"><?php echo number_format($sumatoria_cantidad_saldos,2,'.',','); ?></td>
	    <td id="style_table_kardex" height="20"><?php echo "" ?></td>
	    <td id="style_table_kardex"><?php echo number_format($sumatoria_parciales_saldos,2,'.',','); ?></td>
	</tr>
</table>