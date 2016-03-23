<?php
  if ($this->input->post('nombre_proveedor')){
      $nombre_proveedor = array('name'=>'nombre_proveedor','id'=>'nombre_proveedor','value'=>$this->input->post('nombre_proveedor'), 'style'=>'width:280px;font-family: verdana;','placeholder'=>' :: Nombre del Proveedor ::');
  }else{
      $nombre_proveedor = array('name'=>'nombre_proveedor','id'=>'nombre_proveedor', 'style'=>'width:280px;font-family: verdana;','placeholder'=>' :: Nombre del Proveedor ::'); 
  }
?>

<script type="text/javascript">
	$(function(){

    $("#nombre_proveedor").autocomplete({
      source: function (request, respond) {
        $.post("<?php echo base_url('comercial/traer_proveedor_autocomplete'); ?>", {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>", q: request.term},
        function (response) {
            respond(response);
        }, 'json');
      }, select: function (event, ui) {
        var selectedObj = ui.item;
        var razon_social = selectedObj.razon_social;
        $("#nombre_proveedor").val(razon_social);
      }
    });

    //Estilos del calendario
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

    $("#num_factura").validCampoFranz('0123456789-');

    <?php 
    if ($this->input->post('proveedor')){
      $selected_proveedor =  (int)$this->input->post('proveedor');
    }else{  $selected_proveedor = "";
    ?>
           $("#proveedor").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    <?php 
      } 
    ?>

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

    $('#listaRegistros').DataTable();

    // Eliminar registro
    $('a.eliminar_registro').bind('click', function () {
      var ruta = $('#direccionelim').text();
      var id = $(this).attr('id').replace('elim_', '');
      var parent = $(this).parent().parent();
      $("#dialog-confirm").data({
        'delid': id,
        'parent': parent,
        'ruta': ruta
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
                $("#finregistro").html('<strong>!La Factura ha sido eliminada correctamente!</strong>').dialog({
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
          //setTimeout('window.location.href="<?php echo base_url(); ?>comercial/gestionconsultarRegistros_optionsAdvanced"', 200);
        },
        'Cancelar': function(){
          $(this).dialog('close');
        }
      }
    });
    // FIN DE ELIMINAR
  });

  //Fuera de $(function(){         });
  function resetear(){
      window.location.href="<?php echo base_url();?>comercial/gestionconsultarRegistros_optionsAdvanced";
  }

  // Mostrar Detalle
  function mostrar_detalle(id_ingreso_producto){
        var urlMaq = '<?php echo base_url();?>comercial/mostrardetalle/'+id_ingreso_producto;
        //alert(urlMaq);
        $("#mdlMostrarDetalle").load(urlMaq).dialog({
          modal: true, position: 'center', width: 1175, height: 'auto', draggable: false, resizable: false, closeOnEscape: false,
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
    <div id="tituloCont">Eliminar Registros de Ingreso de Facturas - Opciones Avanzadas</div>
    <div id="formFiltro">
      <div class="tituloFiltro">Filtrar Búsqueda</div>
      <form name="filtroBusqueda" action="#" method="post">
        <?php
          	// para el numero de factura
          	if ($this->input->post('num_factura')){
            $num_factura = array('name'=>'num_factura','id'=>'num_factura','maxlength'=>'12','value'=>$this->input->post('num_factura'), 'style'=>'width:130px');
          	}else{
            $num_factura = array('name'=>'num_factura','id'=>'num_factura','maxlength'=>'12', 'style'=>'width:130px');
            }
            //para la Fecha de Registro
          	if ($this->input->post('fecharegistro')){
      			 $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
        		}else{
        		  $fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
        		}
            if ($this->input->post('fechainicial')){
              $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10','value'=>$this->input->post('fechainicial'), 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
            }else{
              $fechainicial = array('name'=>'fechainicial','id'=>'fechainicial','maxlength'=>'10', 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
            }
            //para la fecha final del periodo
            if ($this->input->post('fechafinal')){
              $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10','value'=>$this->input->post('fechafinal'), 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
            }else{
              $fechafinal = array('name'=>'fechafinal','id'=>'fechafinal','maxlength'=>'10', 'style'=>'width:130px','readonly'=> 'readonly', 'class'=>'required');
            }
        ?>
        <?php echo form_open(base_url()."comercial/gestionconsultarRegistros_optionsAdvanced", 'id="buscar"') ?>
          <table width="1000" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="110">N° de Factura:</td>
                <td width="160"><?php echo form_input($num_factura);?></td>
                <td width="70" valign="middle">Proveedor:</td>
                <td width="211"><?php echo form_input($nombre_proveedor);?></td>
              <td width="81" align="center" style="padding-bottom:4px;">
                <input name="submit" type="submit" id="submit" value="Buscar" />
              </td>
              <td width="137" align="left" style="padding-bottom:4px;">
                <input name="reset" type="button" onclick="resetear()" value="Reestablecer" />
              </td>
            <tr>
                <td>Fecha de Registro:</td>
                <td><?php echo form_input($fecharegistro);?></td>
            </tr>
            <tr>
              <td>Fecha Inicial:</td>
              <td><?php echo form_input($fechainicial);?></td>
              <td>Fecha Final:</td>
              <td><?php echo form_input($fechafinal);?></td>
            </tr>
          </table>
        <?php echo form_close() ?>
      </form>
      <!--
      <div>
        <input name="submit" type="submit" id="button_killer" value=" Buttom Killer xD" style="padding-bottom:3px; padding-top:3px; margin-bottom: 15px; background-color: #CD0A0A; border-radius:6px; width: 150px;margin-right: 15px;" />
      </div>
      -->
      <!--Iniciar listar-->
        <?php 
          $existe = count($registros);
          if($existe <= 0){
            echo 'No existen Registros de Ingreso de Productos registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaRegistros" style="width:1370px;" class="table table-hover table-striped">
          <thead>
            <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
              <td sort="idprod" width="65" height="25">ITEM</td>
              <td sort="idproducto" width="140" height="25">COMPROBANTE</td>
              <td sort="idproducto" width="200" height="25">SERIE - NUMERO FACTURA</td>
              <td sort="nombreprod" width="465">PROVEEDOR</td>
              <td sort="catprod" width="160">FECHA DE REGISTRO</td>
              <td sort="procprod" width="120">MONTO TOTAL</td>
              <td sort="procprod" width="120">MONEDA</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
              <td width="20" style="background-image: none;">&nbsp;</td>
            </tr>
          </thead>
          <?php
          	$i=1; 
          	foreach($registros as $listaregistros){
          ?>  
          <tr class="contentTable" style="font-size: 12px;">
            <td height="23" style="vertical-align: middle;"><?php echo str_pad($i, 5, 0, STR_PAD_LEFT); ?></td>
            <td style="vertical-align: middle;"><?php echo $listaregistros->no_comprobante; ?></td>
            <td style="vertical-align: middle;"><?php echo str_pad($listaregistros->serie_comprobante, 3, 0, STR_PAD_LEFT)." - ".str_pad($listaregistros->nro_comprobante, 8, 0, STR_PAD_LEFT); ?></td>
            <td style="vertical-align: middle;"><?php echo $listaregistros->razon_social; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaregistros->fecha; ?></td>
            <td style="vertical-align: middle;"><?php echo number_format($listaregistros->total,2,'.',','); ?></td>
            <td style="vertical-align: middle;"><?php echo $listaregistros->nombresimbolo; ?></td>
            <td width="20" align="center"><img class="mostrar_detalle" src="<?php echo base_url();?>assets/img/view.png" width="20" height="20" title="Mostrar Detalle" onClick="mostrar_detalle(<?php echo $listaregistros->id_ingreso_producto; ?>)" /></td>
            <td width="20" align="center">
              <a href="" class="eliminar_registro" id="elim_<?php echo $listaregistros->id_ingreso_producto; ?>">
              <img src="<?php echo base_url();?>assets/img/trash.png" width="20" height="20" title="Eliminar Registro"/></a>
            </td>
          </tr>
          <?php 
          	$i++;
          	} 
          ?>        
        </table>
      <?php }?>
    </div>
  </div>
  <div id="mdlMostrarDetalle"></div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
  <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarregistroingreso');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Factura">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar esta Factura?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>