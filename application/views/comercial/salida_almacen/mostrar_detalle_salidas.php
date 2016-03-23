  <div id="contenedor" style="width:665px; min-height:240px; height: auto;">
   	<!-- Iniciar listado -->
    <?php 
      $existe = count($dataSalidas);
      if($existe <= 0){
    ?>
        <div id="tituloCont">Registro de Salidas por Área</div>
        <div id="formFiltro" style="width:500px;">
        <?php echo 'No existen Productos registrados en el Sistema.';?>
    <?php
      }
      else
      {
        foreach($dataSalidas as $data){
          $nombre_area = $data->no_area;
        }
    ?>
      <div id="tituloCont">Registro de Salidas por Área: <?php echo $nombre_area; ?></div>
        <div id="formFiltro" style="width:500px;">
          <table border="0" cellspacing="0" cellpadding="0" id="listaProductos" style="width:664px;">
            <thead>
              <tr class="tituloTable" style="font-size: 12px;">
                <td sort="idprod" width="45" height="25">Item</td>
                <td sort="idproducto" width="54" height="25">Fecha</td>
                <td sort="nombreprod" width="225">Nombre o Descripción</td>
                <td sort="catprod" width="77">Cantidad de Salida</td>
              </tr>
            </thead>
            <?php
              $i=1;
              $sumatoria=0;
            	foreach($dataSalidas as $data){
            ?>
              <tr class="contentTable" style="font-size: 11px;height: 32px;border-color: darkgray;border-bottom-style: solid;border-width: 3px;">
                <td><?php echo str_pad($i, 5, 0, STR_PAD_LEFT); ?></td>
                <td><?php echo $data->fecha; ?></td>
                <td><?php echo $data->no_producto; ?></td>
                <td><?php echo number_format($data->cantidad_salida,2,'.',','); ?></td>
              </tr>
          <?php
          	 $i++;
             $sumatoria = $sumatoria + number_format(number_format($data->cantidad_salida,2,'.',',')*number_format($data->p_u_salida,2,'.',','),2,'.',',');
          	}
          ?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: darkgray;border-bottom-style: solid;font-size: 11px;border-width: 3px;"> RESUMEN VALORIZADO : </td>
            <td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: darkgray;border-bottom-style: solid;font-size: 11px;border-width: 3px;"><?php echo "S/. ".number_format($sumatoria,2,'.',','); ?></td>
          </tr>
        </table>
    <?php } ?>
	</div>
</div>