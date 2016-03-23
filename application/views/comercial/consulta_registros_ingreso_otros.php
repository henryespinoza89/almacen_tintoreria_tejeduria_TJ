<script type="text/javascript">
	$(function(){
		
    //Mostrar ::SELECCIONE:: en los combobox
    <?php 
      if ($this->input->post('proveedor')){
        $selected_prov =  (int)$this->input->post('proveedor');
      }else{  $selected_prov = "";
    ?>
             $("#proveedor").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>
    <?php 
      if ($this->input->post('tipocom')){
        $selected_tipocom =  (int)$this->input->post('tipocom');
      }else{  $selected_tipocom = "";
    ?>
             $("#tipocom").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>
    //Estilos del calendario
    $("#fecharegistro").datepicker({ 
      dateFormat: 'yy-mm-dd',showOn: "button",
      buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
      buttonImageOnly: true
    });
    $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
    //Script para crear la tabla que será el contenedor de los productos registrados
  	$('#listaRegistros').jTPS( {perPages:[10,20,30,50,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
          // target table selector
          var table = '#listaRegistros';
          // store pagination + sort in cookie 
          document.cookie = 'jTPS=sortasc:' + $(table + ' .sortableHeader').index($(table + ' .sortAsc')) + ',' +
                  'sortdesc:' + $(table + ' .sortableHeader').index($(table + ' .sortDesc')) + ',' +
                  'page:' + $(table + ' .pageSelector').index($(table + ' .hilightPageSelector')) + ';';
          }
      });

      // reinstate sort and pagination if cookie exists
      var cookies = document.cookie.split(';');
      for (var ci = 0, cie = cookies.length; ci < cie; ci++) {
              var cookie = cookies[ci].split('=');
              if (cookie[0] == 'jTPS') {
                      var commands = cookie[1].split(',');
                      for (var cm = 0, cme = commands.length; cm < cme; cm++) {
                              var command = commands[cm].split(':');
                              if (command[0] == 'sortasc' && parseInt(command[1]) >= 0) {
                                      $('#listaRegistros .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                              } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                      $('#listaRegistros .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                              } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                      $('#listaRegistros .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                              }
                      }
              }
      }

      // bind mouseover for each tbody row and change cell (td) hover style
      $('#listaRegistros tbody tr:not(.stubCell)').bind('mouseover mouseout',
              function (e) {
                      // hilight the row
                      e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
              }
      );
	});

  //Fuera de $(function(){         });
  function resetear(){
      window.location.href="<?php echo base_url();?>comercial/gestionconsultarRegistros_otros";
  }

  // Mostrar Detalle
  function mostrar_detalle(id_ingreso_producto){
        var urlMaq = '<?php echo base_url();?>comercial/mostrardetalle/'+id_ingreso_producto;
        //alert(urlMaq);
        $("#mdlMostrarDetalle").load(urlMaq).dialog({
          modal: true, position: 'center', width: 1175, height: 570, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
          Volver: function(){
            $("#mdlMostrarDetalle").dialog("close");
          }
          }
        });
      }

</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont"><em>Consultar los Registros de Ingreso de Productos - Otros</em></div>
    <div id="formFiltro">
      <div class="tituloFiltro">Filtrar Búsqueda</div>
      <form name="filtroBusqueda" action="#" method="post">
        <?php
          	// para el numero de factura
          	if ($this->input->post('num_factura')){
            $num_factura = array('name'=>'num_factura','id'=>'num_factura','maxlength'=>'11','value'=>$this->input->post('num_factura'), 'style'=>'width:130px');
          	}else{
            $num_factura = array('name'=>'num_factura','id'=>'num_factura','maxlength'=>'11', 'style'=>'width:130px');
            }
            //para la Fecha de Registro
          	if ($this->input->post('fecharegistro')){
      			$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
        		}else{
        		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
        		}
        ?>
        <?php echo form_open(base_url()."comercial/gestionconsultarRegistros_otros", 'id="buscar"') ?>
          <table width="1150" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="140">N° de Factura:</td>
                <td width="210"><?php echo form_input($num_factura);?></td>
                <td width="170" valign="middle">Proveedor:</td>
				    <?php
	          		$existe = count($listaproveedor);
	          		if($existe <= 0){ ?>
		            	<td width="330" height="28"><b><?php echo 'Registrar en el Sistema';?></b></td>
		        <?php    
		            }
		            else
		            {
	          	?>
	          			<td width="220"><?php echo form_dropdown('proveedor',$listaproveedor,$selected_prov,'id="proveedor" style="width:158px;"');?></td>
	          	<?php }?>
	            <td width="420" style="padding-bottom:4px;"><input name="submit" type="submit" id="submit" value="Buscar" />
	                  <input name="reset" type="button" onclick="resetear()" value="Reestablecer" />
	            </td>
            </tr>
            <tr>
              <td>Fecha de Registro:</td>
              <td><?php echo form_input($fecharegistro);?></td>
              <td>Tipo de Comprobante:</td>
              <?php
              $existe = count($listacomprobante);
              if($existe <= 0){ ?>
                <td width="330" height="28"><b><?php echo 'Registrar en el Sistema';?></b></td>
              <?php    
                  }
                  else
                  {
                ?>
                <td width="220"><?php echo form_dropdown('tipocom',$listacomprobante,$selected_tipocom,'id="tipocom" style="width:158px;"');?></td>
              <?php }?>
            </tr>
          </table>
        <?php echo form_close() ?>
      </form>
      <!--Iniciar listar-->
        <?php 
          $existe = count($registros);
          if($existe <= 0){
            echo 'No existen Registros de Ingreso de Productos registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaRegistros" style="width:1290px;">
          <thead>
            <tr class="tituloTable">
              <td sort="idprod" width="55" height="25">Item</td>
              <td sort="idproducto" width="120" height="25">ID Comprobante</td>
              <td sort="idproducto" width="190" height="25">Tipo de Comprobante</td>
              <td sort="idproducto" width="190" height="25">Número de Comprobante</td>
              <td sort="nombreprod" width="325">Proveedor</td>
              <td sort="catprod" width="150">Fecha de Registro</td>
              <td sort="procprod" width="120">Monto Total</td>
              <td sort="procprod" width="120">Moneda</td>
              <td width="20">&nbsp;</td>
            </tr>
          </thead>
          <?php
          	$i=1; 
          	foreach($registros as $listaregistros){
          ?>  
          <tr class="contentTable">
            <td><?php echo str_pad($i, 5, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listaregistros->id_ingreso_producto; ?></td>
            <td><?php echo $listaregistros->no_comprobante; ?></td>
            <td><?php echo $listaregistros->nro_comprobante; ?></td>
            <!---->
            <td><?php echo $listaregistros->razon_social; ?></td>
            <td><?php echo $listaregistros->fecha; ?></td>
            <td><?php echo $listaregistros->total; ?></td>
            <td><?php echo $listaregistros->nombresimbolo; ?></td>
            <td width="20" align="center"><img class="mostrar_detalle" src="<?php echo base_url();?>assets/img/view.png" width="20" height="20" title="Mostrar Detalle" onClick="mostrar_detalle(<?php echo $listaregistros->id_ingreso_producto; ?>)" /></td>
          </tr>
          <?php 
          	$i++;
          	} 
          ?> 
          <tfoot class="nav">
                  <tr>
                    <td colspan=10>
                          <div class="pagination"></div>
                          <div class="paginationTitle">Página</div>
                          <div class="selectPerPage"></div>
                      </td>
                  </tr>                   
          </tfoot>
          <table style="width:1304px;">
            <tr>
              <td colspan="9" style="float: right;margin-top: 15px;"><?php echo anchor('comercial/gestionotrosDoc', '<== Regresar', array('style'=>'text-decoration: none; background-color: #005197; color: white; font-family: tahoma; border-radius: 6px; padding: 3px 15px; font-size: 11px;')); ?></td>
            </tr>
          </table>          
        </table>
      <?php }?>

    </div>
  </div>
  <div id="mdlMostrarDetalle"></div>