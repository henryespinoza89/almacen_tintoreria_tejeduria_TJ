<body>
	<div id="contenedor" style="width: 1160px;">
		<div id="tituloCont_gerencia">Reportes a Nivel Gerencial</div>
		<div id="acceso_almacen">
			<div id="mensage"><span>SELECCIONAR EL ALMACÉN AL QUE DESEA ACCEDER</span></div>
			<div id="first_bottom" style="height: 50px;background-color: gray;width: 300px;padding-bottom: 2px;padding-right: 2px;padding-top: 0.4px;margin-bottom: 10px;">
				<?php echo form_open(base_url()."gerencia/redirect_store_staClara", 'id="buscar"') ?>
					<table>
						<tr>
							<td><input name="submit" type="submit" id="submit" value="ALMACÉN SANTA CLARA" style="width: 300px; height: 50px;font-size: 17px;background-color: #d4eef7;color: #352116;font-weight: 800;border: none;border-radius: 10px;font-family: 'BebasNeueRegular,Ubuntu-C,MS-Gothic';" /></td>
						</tr>
					</table>
				<?php echo form_close() ?>
			</div>
			<div id="first_bottom" style="height: 50px;background-color: gray;width: 300px;padding-bottom: 2px;padding-right: 2px;padding-top: 0.1px;margin-bottom: 10px;">
				<?php echo form_open(base_url()."gerencia/redirect_store_tejeduria", 'id="buscar"') ?>
					<table>
						<tr>
							<td><input name="submit" type="submit" id="submit" value="ALMACÉN TEJEDURÍA" style="width: 300px; height: 50px;font-size: 17px;background-color: #d4eef7;color: #352116;font-weight: 800;border: none;border-radius: 10px;font-family: 'BebasNeueRegular,Ubuntu-C,MS-Gothic';" /></td>
						</tr>
					</table>
				<?php echo form_close() ?>
			</div>
			<div id="first_bottom" style="height: 50px;background-color: gray;width: 300px;padding-bottom: 2px;padding-right: 2px;margin-bottom: 15px;">
				<?php echo form_open(base_url()."gerencia/redirect_store_hilos", 'id="buscar"') ?>
					<table>
						<tr>
							<td><input name="submit" type="submit" id="submit" value="ALMACÉN SANTA ANITA" style="width: 300px; height: 50px;font-size: 17px;background-color: #d4eef7;color: #352116;font-weight: 800;border: none;border-radius: 10px;font-family: 'BebasNeueRegular,Ubuntu-C,MS-Gothic';" /></td>
						</tr>
					</table>
				<?php echo form_close() ?>
			</div>
			<div id="mensage"><span>VERIFICAR STOCK DISPONIBLE</span></div>
			<div id="first_bottom" style="height: 50px;background-color: gray;width: 300px;padding-bottom: 2px;padding-right: 2px;margin-bottom: 15px;">
				<?php echo form_open(base_url()."gerencia/redirect_stock", 'id="buscar"') ?>
					<table>
						<tr>
							<td><input name="submit" type="submit" id="submit" value="REPORTE DE STOCK" style="width: 300px; height: 50px;font-size: 17px;background-color: #d4eef7;color: #352116;font-weight: 800;border: none;border-radius: 10px;font-family: 'BebasNeueRegular,Ubuntu-C,MS-Gothic';" /></td>
						</tr>
					</table>
				<?php echo form_close() ?>
			</div>
		</div>
	</div>
	<div id="modalerror"></div>
	<div id="finregistro"></div>
