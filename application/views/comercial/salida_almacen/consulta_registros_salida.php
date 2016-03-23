<script type="text/javascript">
	$(function(){

  $("#nombre_producto").autocomplete({
    source: function (request, respond) {
      $.post("<?php echo base_url('comercial/traer_producto_autocomplete_consultar_salidas'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
      function (response) {
          respond(response);
      }, 'json');
    }, select: function (event, ui) {
      var selectedObj = ui.item;
      var nombre_producto = selectedObj.nombre_producto;

      $("#nombre_producto").val(nombre_producto);
      nombre_producto = $("#nombre_producto").val();
      var ruta = $('#direccion_traer_unidad_medida').text();
      $.ajax({
          type: 'get',
          url: ruta,
          data: {
            'nombre_producto' : nombre_producto
          },
          success: function(response){
            $("#unidadmedida").val(response);
          }
      });
      var ruta2 = $('#direccion_traer_stock').text();
      $.ajax({
          type: 'get',
          url: ruta2,
          data: {
            'nombre_producto' : nombre_producto
          },
          success: function(response){
            $("#stockactual").val(formatNumber.new(response));
          }
      });
      $("#cantidad").focus();
    }
  });

  $("#button_killer").on("click",function(){
    var fechainicial = $("#fechainicial").val();
    var fechafinal = $("#fechafinal").val();
    if(fechafinal == '' || fechainicial == ''){
      $("#modalerror").html('<strong>!Falta Completar algunos Campos del Formulario. Verificar!</strong>').dialog({
        modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
        buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
      });
    }else{
      var dataString = 'fechainicial='+fechainicial+'&fechafinal='+fechafinal+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/procedimiento_eliminacion_salidas/",
        data: dataString,
        success: function(response){
          if(response == 1){
            $("#modalerror").empty().append('<span style="color:black"><b>!Procedimiento realizado con Éxito!</b></span>').dialog({
              modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {
                // window.location.href="<?php echo base_url();?>comercial/gestionconsultarSalidaRegistros";
                $(this).dialog("close");
              }}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
          }
        }
      });
    }
  });

$("#actualizar_saldos_iniciales").on("click",function(){
    var fechainicial = $("#fechainicial").val();
    var fechafinal = $("#fechafinal").val();
    if(fechafinal == '' || fechainicial == ''){
      $("#modalerror").html('<strong>!Falta Completar algunos Campos del Formulario. Verificar!</strong>').dialog({
        modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
        buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
      });
    }else{
      var dataString = 'fechainicial='+fechainicial+'&fechafinal='+fechafinal+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>comercial/actualizar_saldos_iniciales_controller_version_2/",
        data: dataString,
        success: function(response){
          if(response == 1){
            $("#modalerror").empty().append('<span style="color:black"><b>!Procedimiento realizado con Éxito!</b></span>').dialog({
              modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
              buttons: { Ok: function() {
                $(this).dialog("close");
              }}
            });
            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
          }
        }
      });
    }
  });

  $("#actualizar_precio_unitario").on("click",function(){
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>comercial/actualizar_saldos_iniciales_controller_version_3/",      
      success: function(response){
        if(response == 1){
          $("#modalerror").empty().append('<span style="color:black"><b>!Procedimiento realizado con Éxito!</b></span>').dialog({
            modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
            buttons: { Ok: function() {
              $(this).dialog("close");
            }}
          });
          $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
        }
      }
    });
  });

  $("#actualizar_stock").on("click",function(){
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>comercial/actualizar_stock_controller_version_4/",      
      success: function(response){
        if(response == 1){
          $("#modalerror").empty().append('<span style="color:black"><b>!Procedimiento realizado con Éxito!</b></span>').dialog({
            modal: true,position: 'center',width: 400,height: 125,resizable: false,title: 'Registro de Salidas',hide: 'blind',show: 'blind',
            buttons: { Ok: function() {
              $(this).dialog("close");
            }}
          });
          $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
        }
      }
    });
  });

  $("#maquina").change(function() {
  $("#maquina option:selected").each(function() {
          maquina = $('#maquina').val();
          $.post("<?php echo base_url(); ?>comercial/traeMarca", {
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
          $.post("<?php echo base_url(); ?>comercial/traeModelo", {
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
          $.post("<?php echo base_url(); ?>comercial/traeSerie", {
              modelo : modelo , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
          }, function(data) {
              $("#serie").html(data);
              //$("#Serie").html('<option value="0">:: SELECCIONE UN MODELO ::</option>');
          });
      });
  });
  $("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');

  $("#fecharegistro").datepicker({ 
    dateFormat: 'yy-mm-dd',showOn: "button",
    buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true
  });
  $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input
  
  $("#fechainicial").datepicker({ 
    dateFormat: 'yy-mm-dd',showOn: "button",
    buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true
  });
  $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input

  $("#fechafinal").datepicker({ 
    dateFormat: 'yy-mm-dd',showOn: "button",
    buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true
  });
  $(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input

//Script para crear la tabla que será el contenedor de los productos registrados
    $('#listarSalidaProductos').jTPS( {perPages:[10,20,30,50,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
        // target table selector
        var table = '#listarSalidaProductos';
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
                                    $('#listarSalidaProductos .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                            } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                    $('#listarSalidaProductos .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                            } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                    $('#listarSalidaProductos .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                            }
                    }
            }
    }

    // bind mouseover for each tbody row and change cell (td) hover style
    $('#listarSalidaProductos tbody tr:not(.stubCell)').bind('mouseover mouseout',
            function (e) {
                    // hilight the row
                    e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
            }
    );

    /* Eliminar Salida */
    $('a.eliminar_salida').bind('click', function () {
      var ruta = $('#direccionelim').text();
        var id = $(this).attr('id').replace('elim_', '');
        var parent = $(this).parent().parent();
        //var usuario = $('#us123').text();
        $("#dialog-confirm").data({
              'delid': id,
              'parent': parent,
              'ruta': ruta
              //'idusuario': usuario
        }).dialog('open');
        return false;
    });
    $("#dialog-confirm").dialog({
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      width: 400,
      height: "auto",
      zindex: 9998,
      modal: false,
      buttons: {
        'Eliminar': function () {
          var parent = $(this).data('parent');
          var id = $(this).data('delid');
          var ruta = $(this).data('ruta');
          $.ajax({
            type: 'get',
            url: ruta,
            data: {
              'eliminar' : id
            },
            success: function(msg){
              if(msg == 1){
                $("#finregistro").html('<strong>!La Salida del Producto ha sido eliminado correctamente!</strong>').dialog({
                  modal: true,position: 'center',width: 480,height: 125,resizable: false, title: '!Eliminación Conforme!',
                  buttons: { Ok: function(){
                    // window.location.href="<?php echo base_url();?>comercial/gestionconsultarSalidaRegistros";
                    $("#finregistro").dialog('close');
                  }}
                });
              }else{
                $("#modalerror").empty().append(msg).dialog({
                  modal: true,position: 'center',width: 700,height: 125,resizable: false,title: '!No se puede eliminar la Salida!',
                  buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
                });
                $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
              }
            }
          });
          $(this).dialog('close');
          //setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionproductos"', 200);
        },
        'Cancelar': function () {
          $(this).dialog('close');
        }
      }
    });
    /* Fin de Eliminar Salida */

	});

  //Fuera de $(function(){         });
  function resetear(){
      window.location.href="<?php echo base_url();?>comercial/gestionconsultarSalidaRegistros";
  }

</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Consultar Salida de Productos</div>
    <div id="formFiltro">
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
      			$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
        		}else{
        		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
        		}
            //para la fecha de inicio del periodo
            if ($this->input->post('fechainicial')){
            $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10','value'=>$this->input->post('fechainicial'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
            }else{
            $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
            }
            //para la fecha final del periodo
            if ($this->input->post('fechafinal')){
            $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10','value'=>$this->input->post('fechafinal'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
            }else{
            $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
            }
            /* para el producto */
            if ($this->input->post('nombre_producto')){
                $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto','value'=>$this->input->post('nombre_producto'), 'style'=>'width:265px;font-family: verdana;','placeholder'=>' :: Nombre del Producto ::');
            }else{
                $nombre_producto = array('name'=>'nombre_producto','id'=>'nombre_producto', 'style'=>'width:265px;font-family: verdana;','placeholder'=>' :: Nombre del Producto ::'); 
            }
        ?>
        <?php echo form_open(base_url()."comercial/gestionconsultarSalidaRegistros", 'id="buscar"') ?>
          <table width="300" border="0" cellspacing="0" cellpadding="0" style="float: left;">
            <tr>
              <td width="107" style="height:30px; color:#005197;" colspan="2"><b>Filtrar Consulta por Máquina</b></td>
            </tr>
            <tr>
              <td width="62" valign="middle" style="height:30px;">Máquina:</td>
                <?php
                  $existe = count($listamaquina);
                  if($existe <= 0){ ?>
                  <td width="189" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
                <?php    
                  }
                  else
                  {
                  ?>
                  <td width="49"><?php echo form_dropdown('maquina', $listamaquina, '$selected_maq',"id='maquina' class='required' style='width:170px;'");?></td>
                <?php }?>
            </tr>
          </table>
          <div style="width: 580px;float: left;">
            <table width="280" border="0" cellspacing="0" cellpadding="0" style="float: left;margin-right: 500px;">
              <tr>
                <td width="107" style="height:30px;color:#005197;" colspan="2"> <b>Filtrar Consulta por Fecha de Registro</b></td>
              </tr>
              <tr>
                <td width="240" style="height:30px;">Fecha de Registro:</td>
                  <td width="207" style="height:30px;"><?php echo form_input($fecharegistro);?></td>
              </tr>
            </table>
            <table width="583" border="0" cellspacing="0" cellpadding="0" style="float: left;margin-right: 300px;">
              <tr>
                <td style="height:30px;color:#005197;" colspan="4"> <b>Filtrar Consulta por Periodo</b></td>
              </tr>
              <tr>
                <td width="130" style="height:30px;">Fecha Inicial:</td>
                <td width="162"><?php echo form_input($fechainicial);?></td>
                <td width="107">Fecha Final:</td>
                <td width="184"><?php echo form_input($fechafinal);?></td>
              </tr>
            </table>
          </div>
          <div style="height: 150px;width: 200px;float: left;">
            <table width="280" border="0" cellspacing="0" cellpadding="0" style="float: left;margin-right: 500px;">
              <tr>
                <td style="height:30px;color:#005197;" colspan="4"> <b>Filtrar Consulta por Producto</b></td>
              </tr>
              <tr>
                <td width="207" style="height:30px;"><?php echo form_input($nombre_producto);?></td>
              </tr>
            </table>
            <table width="300" border="0" cellspacing="0" cellpadding="0" style="float: left; margin-right: 500px;height: 40px;">
              <tr>
                <td style="height:30px;color:#005197;"><b>Filtar Consulta por Área</b></td>
              </tr>
              <tr>
                <?php
                  $existe = count($listaarea);
                  if($existe <= 0){ ?>
                    <td width="137" height="28"><b><?php echo 'Registrar Área';?></b></td>
                <?php    
                  }
                  else
                  {
                ?>
                    <td width="68"><?php echo form_dropdown('area',$listaarea,'$selected_area',"id='area' style='width:170px;'" );?></td>
                <?php }?>
              </tr>
            </table>
          </div>
          <table>
            <tr style="height:30px;"> 
              <td colspan="2" style=" padding-top: 5px; padding-left: 840px;">
                <input name="submit" type="submit" id="submit" value="Iniciar Búsqueda" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;margin-right: 15px;" />
                <input name="reset" type="button" onclick="resetear()" value="Reestablecer" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; width: 150px;" />
              </td>
            </tr>
          </table>
        <?php echo form_close() ?>
      </form>
      <!--
      <div>
        <input name="submit" type="submit" id="button_killer" value=" Buttom Killer xD" style="padding-bottom:3px; padding-top:3px; margin-bottom: 15px; background-color: #CD0A0A; border-radius:6px; width: 150px;margin-right: 15px;" />
      </div>
      -->
      <!--
      <div>
        <input name="submit" type="submit" id="actualizar_saldos_iniciales" value="Actualizar saldos iniciales" style="padding-bottom:3px; padding-top:3px; margin-bottom: 15px; background-color: #CD0A0A; border-radius:6px; width: 150px;margin-right: 15px;" />
      </div>
      -->
      <!--
      <div>
        <input name="submit" type="submit" id="actualizar_precio_unitario" value="Actualizar Precio Unitario" style="padding-bottom:3px; padding-top:3px; margin-bottom: 15px; background-color: #CD0A0A; border-radius:6px; width: 150px;margin-right: 15px;" />
      </div>
      -->
      <!--
      <div>
        <input name="submit" type="submit" id="actualizar_stock" value="Actualizar Stock" style="padding-bottom:3px; padding-top:3px; margin-bottom: 15px; background-color: #CD0A0A; border-radius:6px; width: 150px;margin-right: 15px;" />
      </div>
      -->
      <?php 
      $existe = count($salidaproducto);
      if($existe <= 0){
        echo 'No existen Registros de Salida en el Sistema.';
      }
      else
      {
      ?>
      <table border="0" cellspacing="0" cellpadding="0" id="listarSalidaProductos" style="width:1383px;">
          <thead>
              <tr class="tituloTable">
                <td sort="idprod" width="75" height="25">Item</td>
                <td sort="idproducto" width="130" height="25">Máquina</td>
                <td sort="nombreprod" width="130">Marca</td>
                <td sort="catprod" width="130">Modelo</td>
                <td sort="procprod" width="130">Serie</td>
                <td sort="procprod" width="130">Área</td>
                <td sort="procprod" width="150">Solicitante</td>
                <td sort="procprod" width="100">Fecha</td>
                <td sort="procprod" width="255">Producto</td>
                <td sort="procprod" width="90">Cantidad</td>
                <!--
                <td width="20">&nbsp;</td>
                
                <td width="20">&nbsp;</td>
                -->
              </tr>
          </thead>
          <?php 
          $i = 1;
          foreach($salidaproducto as $listasalidaproductos){ ?>  
              <tr class="contentTable">
                <td height="27"><?php echo str_pad($i, 3, 0, STR_PAD_LEFT); ?></td>
                <td><?php echo $listasalidaproductos->nombre_maquina; ?></td>
                <td><?php echo $listasalidaproductos->no_marca; ?></td>
                <td><?php echo $listasalidaproductos->no_modelo; ?></td>
                <td><?php echo $listasalidaproductos->no_serie; ?></td>
                <td><?php echo $listasalidaproductos->no_area; ?></td>
                <td><?php echo $listasalidaproductos->solicitante; ?></td>
                <td><?php echo $listasalidaproductos->fecha; ?></td>
                <td><?php echo $listasalidaproductos->no_producto; ?></td>
                <td><?php echo number_format($listasalidaproductos->cantidad_salida,2,'.',',');?></td>
                <!--
                <td width="20" align="center"><img class="editar_producto" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar producto" onClick="editar_producto(<?php echo $listasalidaproductos->id_salida_producto; ?>)" /></td>
                -->
                <!--
                <td width="20" align="center">
                  <a href="" class="eliminar_salida" id="elim_<?php echo $listasalidaproductos->id_salida_producto; ?>">
                  <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Salida"/></a>
                </td>
                -->
              </tr>
          <?php 
            $i++;
            } 
          ?> 
          <tfoot class="nav">
            <tr>
              <td colspan=12>
                <div class="pagination"></div>
                <div class="paginationTitle">Página</div>
                <div class="selectPerPage"></div>
              </td>
            </tr>                   
          </tfoot>          
      </table>
    <?php }?>
    </div>
  </div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarsalidaproducto');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Salida">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar la Salida del producto?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>