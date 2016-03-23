<script type="text/javascript">

  function sendValue(){
    tipo_maquina = $("#tipo_maquina").val();
    $("#tipo").val(tipo_maquina);
    mod_maquina = $("#mod_maquina").val();
    $("#modelo").val(mod_maquina);
    marca_maquina = $("#marca_maquina").val();
    $("#marca").val(marca_maquina);
    serie_maquina = $("#serie_maquina").val();
    $("#serie").val(serie_maquina);
  }

  function onlytext(){
    if((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
      event.returnValue = false;
  }

  $(function(){

    $("#almacen").change(function() {
      $("#almacen option:selected").each(function(){
          almacen = $('#almacen').val();
          $("#almacen_2").val(almacen);
        });
    });

    $("#estado").change(function() {
      $("#estado option:selected").each(function(){
          estado = $('#estado').val();
          $("#estado_2").val(estado);
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
      if ($this->input->post('estado')){
          $selected_estado =  (int)$this->input->post('estado');
        }else{  $selected_estado = "";
      ?>
               $("#estado").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
      <?php 
        } 
    ?>

    

    //Script para crear la tabla que será el contenedor de los productos registrados
    $('#listaMaquinas').jTPS( {perPages:[10,20,30,50,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
            // target table selector
            var table = '#listaMaquinas';
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
                                        $('#listaMaquinas .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                                } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                        $('#listaMaquinas .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                                } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                        $('#listaMaquinas .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                                }
                        }
                }
        }

        // bind mouseover for each tbody row and change cell (td) hover style
        $('#listaMaquinas tbody tr:not(.stubCell)').bind('mouseover mouseout',
                function (e) {
                        // hilight the row
                        e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                }
        );
  });
  
  //Fuera de $(function(){         });

  function resetear(){
      window.location.href="<?php echo base_url();?>administrador/gestionmaquinas_admin";
  }

</script>


</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Gestión de Maquinarias</div>
    <div id="formFiltro">
      <div class="tituloFiltro">Búsqueda</div>
      <form name="filtroBusqueda" action="#" method="post" style="margin-bottom: 0px;">
        <?php
          // para el ID
          if ($this->input->post('marca_maquina')){
            $marca_maquina = array('name'=>'marca_maquina','id'=>'marca_maquina','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('marca_maquina'),'onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }else{
            $marca_maquina = array('name'=>'marca_maquina','id'=>'marca_maquina','maxlength'=>'20','onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }
          // para el tipo de máquina
          if ($this->input->post('tipo_maquina')){
            $tipo_maquina = array('name'=>'tipo_maquina','id'=>'tipo_maquina','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('tipo_maquina'),'onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }else{
            $tipo_maquina = array('name'=>'tipo_maquina','id'=>'tipo_maquina','maxlength'=>'20','onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }
          //para el modelo de máquina
          if ($this->input->post('mod_maquina')){
            $mod_maquina = array('name'=>'mod_maquina','id'=>'mod_maquina','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('mod_maquina'),'onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }else{
            $mod_maquina = array('name'=>'mod_maquina','id'=>'mod_maquina','maxlength'=>'20','onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }
          //para la serie de la máquina
          if ($this->input->post('serie_maquina')){
            $serie_maquina = array('name'=>'serie_maquina','id'=>'serie_maquina','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('serie_maquina'),'onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }else{
            $serie_maquina = array('name'=>'serie_maquina','id'=>'serie_maquina','maxlength'=>'20','onkeypress'=>'onlytext()', 'style'=>'width:110px', 'onkeyup'=>'sendValue()');
          }

        ?>
        <?php echo form_open(base_url()."administrador/gestionmaquinas_admin", 'id="buscar"') ?>
          <table width="1173" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="123" height="30" style="padding-bottom: 5px;">Tipo de Máquina:</td>
              <td width="126" height="30"><?php echo form_input($tipo_maquina);?></td>
              <td width="132" height="30" style="padding-bottom: 5px;">Modelo de Máquina:</td>
              <td width="133" height="30"><?php echo form_input($mod_maquina);?></td>
              <td width="127" height="30">Almacén:</td>
              <?php
                $existe = count($almacen);
                if($existe <= 0){ ?>
                  <td width="237" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
                <?php    
                    }
                    else
                    {
                  ?>
                    <td width="113" height="30"><?php echo form_dropdown('almacen',$almacen,$selected_almacen,'id="almacen" style="width:148px;"');?></td>
              <?php }?>
              <td width="182" style="padding-bottom:4px;padding-left: 20px;" height="30">
                <input name="submit" type="submit" id="submit" value="Buscar" style="width: 100px;" />
                <input name="reset" type="button" onclick="resetear()" value="Reestablecer" style="width: 100px;"/>
              </td>
            </tr>
            <tr>
              <td height="30" style="padding-bottom: 5px;">Marca de Máquina:</td>
              <td height="30"><?php echo form_input($marca_maquina);?></td>
              <td height="30" style="padding-bottom: 5px;">Serie de Máquina:</td>
              <td height="30"><?php echo form_input($serie_maquina);?></td>
              <td width="127" height="30">Estado de Máquina:</td>
              <?php
                $existe = count($estado);
                if($existe <= 0){ ?>
                  <td width="237" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
              <?php    
                  }
                  else
                  {
                ?>
                  <td width="113" height="30"><?php echo form_dropdown('estado',$estado,$selected_estado,"id='estado' style='width:148px;'");?></td>
              <?php }?>
            </tr>
          </table>
        <?php echo form_close() ?>
      </form>
      <?php
        if ($this->input->post('tipo_maquina')){
          $tipo = array('name'=>'tipo','id'=>'tipo','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('tipo_maquina'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $tipo = array('name'=>'tipo','id'=>'tipo','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('marca_maquina')){
          $marca = array('name'=>'marca','id'=>'marca','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('marca_maquina'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $marca = array('name'=>'marca','id'=>'marca','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('mod_maquina')){
          $modelo = array('name'=>'modelo','id'=>'modelo','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('mod_maquina'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $modelo = array('name'=>'modelo','id'=>'modelo','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('serie_maquina')){
          $serie = array('name'=>'serie','id'=>'serie','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('serie_maquina'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $serie = array('name'=>'serie','id'=>'serie','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
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
        if ($this->input->post('estado')){
          $estado_2 = array('name'=>'estado_2','id'=>'estado_2','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('estado'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $estado_2 = array('name'=>'estado_2','id'=>'estado_2','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php echo form_open(base_url()."administrador/reporte_maquina_admin", 'id="reporte_maquina_admin"') ?>
        <table width="1380" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <?php echo form_input($tipo);?>
              <?php echo form_input($marca);?>
              <?php echo form_input($modelo);?>
              <?php echo form_input($serie);?>
              <?php echo form_input($almacen_2);?>
              <?php echo form_input($estado_2);?>
              <input name="actualizar" type="submit" id="submit" value="Exportar a PDF" style="padding-bottom:3px; padding-top:3px; background-color: #005197; border-radius:6px;width: 120px;float: right;" />
            </td>
          </tr>
        </table>
      <?php echo form_close() ?>
      <!--Iniciar listar-->
        <?php 
          $existe = count($maquina);
          if($existe <= 0){
            echo 'No existen Máquinas registradas en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaMaquinas">
          <thead>
            <tr class="tituloTable">
              <td sort="idproducto" width="100" height="25">Item</td>
              <td sort="nombreprod" width="155">Máquina</td>
              <td sort="catprod" width="155">Marca</td>
              <td sort="procprod" width="155">Modelo</td>
              <td sort="procprod" width="155">Serie</td>
              <td sort="obserprod" width="140">Estado</td>
              <td sort="obserprod" width="140">Fecha de Registro</td>
              <td sort="obserprod" width="140">Almacén</td>
              <td sort="obserprod" width="200">Observación</td>
            </tr>
          </thead>
          <?php
            $i = 1;   
            foreach($maquina as $listamaquinas){ 
          ?>  
          <tr class="contentTable">
            <!--<td height="27"><?php //echo str_pad($listamaquinas->id_maquina, 5, 0, STR_PAD_LEFT); ?></td>-->
            <td height="27"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listamaquinas->nombre_maquina; ?></td>
            <td><?php echo $listamaquinas->no_marca; ?></td>
            <td><?php echo $listamaquinas->no_modelo; ?></td>
            <td><?php echo $listamaquinas->no_serie; ?></td>
            <td><?php echo $listamaquinas->no_estado_maquina; ?></td>
            <td><?php echo $listamaquinas->fe_registro; ?></td>
            <td><?php echo $listamaquinas->no_almacen; ?></td>
            <td><?php echo $listamaquinas->observacion_maq; ?></td>
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
        </table>
      <?php }?>
    </div>
  </div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
