<?php
  // para el ID
  if ($this->input->post('id_producto')){
    $id_producto = array('name'=>'id_producto','id'=>'id_producto','maxlength'=>'10','value'=>$this->input->post('id_producto'),'style'=>'width:150px', 'onkeyup'=>'sendValue()');
  }else{
    $id_producto = array('name'=>'id_producto','id'=>'id_producto','maxlength'=>'10','style'=>'width:150px', 'onkeyup'=>'sendValue()');
  }
  // para el NOMBRE Y APELLIDO
  if ($this->input->post('nombre')){
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1' , 'value' => $this->input->post('nombre'),'style'=>'width:150px', 'onkeyup'=>'sendValue()');
  }else{
    $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1','style'=>'width:150px', 'onkeyup'=>'sendValue()');
  }
?>
<script type="text/javascript">

  function sendValue(){
    id_producto = $("#id_producto").val();
    $("#id_pro").val(id_producto);
    nombre = $("#nombre").val();
    $("#nombre_pro").val(nombre);
  }

  $(function(){

    $("#almacen").change(function() {
      $("#almacen option:selected").each(function(){
          almacen = $('#almacen').val();
          $("#almacen_2").val(almacen);
        });
    });

    $("#categoria").change(function() {
      $("#categoria option:selected").each(function(){
          categoria = $('#categoria').val();
          $("#categoria_2").val(categoria);
        });
    });

    //Mantener el valor del almacen cuando se hace una busqueda en el desplegable
    <?php 
      if ($this->input->post('almacen')){
          $selected_almacen =  (int)$this->input->post('almacen');
        }else{  $selected_almacen = "";
      ?>
               $("#almacen").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
      <?php 
        } 
    ?>

    <?php 
      if ($this->input->post('categoria')){
          $selected_categoria =  (int)$this->input->post('categoria');
        }else{  $selected_categoria = "";
      ?>
               $("#categoria").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
      <?php 
        } 
    ?>
    //Script para crear la tabla que será el contenedor de los productos registrados
    $('#listaProductosAdmin').jTPS( {perPages:[10,20,30,50,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
        // target table selector
        var table = '#listaProductosAdmin';
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
                                    $('#listaProductosAdmin .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                            } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                    $('#listaProductosAdmin .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                            } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                    $('#listaProductosAdmin .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                            }
                    }
            }
    }

    // bind mouseover for each tbody row and change cell (td) hover style
    $('#listaProductosAdmin tbody tr:not(.stubCell)').bind('mouseover mouseout',
            function (e) {
                    // hilight the row
                    e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
            }
    );
  });

  //Fuera de $(function(){         });
  function resetear(){
      window.location.href="<?php echo base_url();?>gerencia/redirect_stock";
  }

</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Gestión de Productos - Repuestos y Suministros</div>
    <div id="formFiltro">
      <div class="tituloFiltro" style="width:1400px; float:left;">Búsqueda</div>
      <form name="filtroBusqueda" action="#" method="post" style="width:1380px; float:left; margin-bottom: 10px;">
        <?php echo form_open(base_url()."administrador/gestionproductos_admin", 'id="buscar" style="width:780px;"') ?>
          <table width="886" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="118" height="30" style="padding-bottom: 5px;">ID de Producto:</td>
              <td width="162"><?php echo form_input($id_producto);?></td>
              <td width="89" height="30">Almacén:</td>
              <?php
                $existe = count($almacen);
                if($existe <= 0){ ?>
                  <td width="160" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
                <?php    
                    }
                    else
                    {
                  ?>
                    <td width="138"><?php echo form_dropdown('almacen',$almacen,$selected_almacen,'id="almacen" style="width:158px;"');?></td>
                <?php }?>
              <td width="219" style="padding-bottom:4px;padding-left: 10px;">
                <input name="submit" type="submit" id="submit" value="Buscar" style="width: 100px;"/>
                <input name="reset" type="button" onclick="resetear()" value="Reestablecer" style="width: 100px;"/>
              </td>
            </tr>
            <tr>
              <td height="30" style="padding-bottom: 5px;">Nom. de Producto:</td>
              <td><?php echo form_input($nombre);?></td>
              <td width="89" height="30">Categoria:</td>
              <td width="160"><?php echo form_dropdown('categoria',$listacategoria,$selected_categoria,'id="categoria" style="width:158px;"');?></td>
            </tr>
          </table>
        <?php echo form_close() ?>
      </form>
      <!--   , 'type'=>'hidden'    -->
      <?php
        if ($this->input->post('id_producto')){
          $id_pro = array('name'=>'id_pro','id'=>'id_pro','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('id_producto'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $id_pro = array('name'=>'id_pro','id'=>'id_pro','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('nombre')){
          $nombre_pro = array('name'=>'nombre_pro','id'=>'nombre_pro','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('nombre'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $nombre_pro = array('name'=>'nombre_pro','id'=>'nombre_pro','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('almacen')){
          $almacen_2 = array('name'=>'almacen_2','id'=>'almacen_2','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('almacen'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $almacen_2 = array('name'=>'almacen_2','id'=>'almacen_2','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('categoria')){
          $categoria_2 = array('name'=>'categoria_2','id'=>'categoria_2','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('categoria'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $categoria_2 = array('name'=>'categoria_2','id'=>'categoria_2','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php echo form_open(base_url()."administrador/reporte_producto_admin", 'id="reporte_producto_admin"') ?>
        <table width="1380" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <?php echo form_input($id_pro);?>
              <?php echo form_input($nombre_pro);?>
              <?php echo form_input($almacen_2);?>
              <?php echo form_input($categoria_2);?>
              <input name="actualizar" type="submit" id="submit" value="Exportar a PDF" style="padding-bottom:3px; padding-top:3px; background-color: #005197; border-radius:6px;width: 120px;float: right;" />
            </td>
          </tr>
        </table>
      <?php echo form_close() ?>
      <!--Iniciar listar-->
      <?php 
        $existe = count($producto);
        if($existe <= 0){
          echo 'No existen Productos registrados en el Sistema.';
        }
        else
        {
      ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaProductosAdmin" style="width:1380px;">
          <thead>
            <tr class="tituloTable">
              <td sort="idprod" width="85" height="25">Item</td>
              <td sort="idproducto" width="134" height="25">ID Producto</td>
              <td sort="nombreprod" width="285">Nombre o Descripción</td>
              <td sort="catprod" width="147">Categoria</td>
              <td sort="procprod" width="147">Procedencia</td>
              <td sort="procprod" width="147">Unidad de Medida</td>
              <td sort="obserprod" width="101">Stock</td>
              <td sort="procprod" width="147">Almacén</td>
            </tr>
          </thead>
          <?php
            $i = 1;
            foreach($producto as $listaproductos){ 
          ?>  
          <tr class="contentTable">
            <!--<td height="27"><?php //echo str_pad($listaproductos->id_pro, 5, 0, STR_PAD_LEFT); ?></td>-->
            <td height="27"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listaproductos->id_producto; ?></td>
            <td><?php echo $listaproductos->no_producto; ?></td>
            <td><?php echo $listaproductos->no_categoria; ?></td>
            <td><?php echo $listaproductos->no_procedencia; ?></td>
            <td><?php echo $listaproductos->unidad_medida; ?></td>
            <td><?php echo $listaproductos->stock; ?></td>
            <td><?php echo $listaproductos->no_almacen; ?></td>
          </tr>
          <?php
            $i++;
            } 
          ?> 
          <tfoot class="nav">
                  <tr>
                    <td colspan=8>
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

    