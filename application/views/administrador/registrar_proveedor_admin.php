<script type="text/javascript">
  $(function(){

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

    //Script para crear la tabla que será el contenedor de los productos registrados
    $('#listaProveedores').jTPS( {perPages:[10,20,30,50,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
            // target table selector
            var table = '#listaProveedores';
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
                                        $('#listaProveedores .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                                } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                        $('#listaProveedores .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                                } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                        $('#listaProveedores .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                                }
                        }
                }
        }

        // bind mouseover for each tbody row and change cell (td) hover style
        $('#listaProveedores tbody tr:not(.stubCell)').bind('mouseover mouseout',
                function (e) {
                        // hilight the row
                        e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                }
        );
    // Fin de Eliminar
    $("#ruc_prov").validCampoFranz('0123456789');
  });

  function resetear(){
      window.location.href="<?php echo base_url();?>administrador/gestionproveedores_admin";
  }

</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Gestión de Proveedores</div>
    <div id="formFiltro">
      <div class="tituloFiltro">Búsqueda</div>
      <form name="filtroBusqueda" action="#" method="post" style="margin-bottom: 0px;">
        <?php
          // para el ID
          if ($this->input->post('ruc_prov')){
            $ruc_prov = array('name'=>'ruc_prov','id'=>'ruc_prov','maxlength'=>'11','value'=>$this->input->post('ruc_prov'), 'style'=>'width:140px','onpaste'=>'return false');
          }else{
            $ruc_prov = array('name'=>'ruc_prov','id'=>'ruc_prov','maxlength'=>'11', 'style'=>'width:140px','onpaste'=>'return false');
          }
          // para el NOMBRE Y APELLIDO
          if ($this->input->post('nombre')){
            $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1' , 'value' => $this->input->post('nombre'), 'style'=>'width:140px');
          }else{
            $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1', 'style'=>'width:140px');
          }
        ?>
        <?php echo form_open(base_url()."administrador/gestionproveedores_admin", 'id="buscar"') ?>
          <table width="808" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="75" style="padding-bottom: 5px;">RUC:</td>
              <td width="154"><?php echo form_input($ruc_prov);?></td>
              <td width="66" height="30">Almacén:</td>
              <?php
                $existe = count($almacen);
                if($existe <= 0){ ?>
                  <td width="151" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
                <?php    
                    }
                    else
                    {
                  ?>
                    <td width="154" height="30"><?php echo form_dropdown('almacen',$almacen,$selected_almacen,'id="almacen" style="width:148px;"');?></td>
              <?php }?>
              <td width="208" style="padding-bottom:4px;">
                <input name="submit" type="submit" id="submit" value="Buscar" style="width: 100px;"/>
                <input name="reset" type="button" onclick="resetear()" value="Reestablecer" style="width: 100px;"/>
              </td>
            </tr>
            <tr>
              <td style="padding-bottom: 5px;">Proveedor:</td>
              <td><?php echo form_input($nombre);?></td>
            </tr>
</table>
        <?php echo form_close() ?>
      </form>
      <?php
        if ($this->input->post('almacen')){
          $almacen_2 = array('name'=>'almacen_2','id'=>'almacen_2','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('almacen'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $almacen_2 = array('name'=>'almacen_2','id'=>'almacen_2','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('nombre')){
          $nombre_filtro = array('name'=>'nombre_filtro','id'=>'nombre_filtro','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('nombre'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $nombre_filtro = array('name'=>'nombre_filtro','id'=>'nombre_filtro','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('ruc_prov')){
          $ruc_filtro = array('name'=>'ruc_filtro','id'=>'ruc_filtro','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('ruc_prov'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $ruc_filtro = array('name'=>'ruc_filtro','id'=>'ruc_filtro','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php echo form_open(base_url()."administrador/reporte_proveedor_admin", 'id="reporte_maquina_admin"') ?>
        <table width="1380" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <?php echo form_input($ruc_filtro);?>
              <?php echo form_input($nombre_filtro);?>
              <?php echo form_input($almacen_2);?>
              <input name="actualizar" type="submit" id="submit" value="Exportar a PDF" style="padding-bottom:3px; padding-top:3px; background-color: #005197; border-radius:6px;width: 120px;float: right;" />
            </td>
          </tr>
        </table>
      <?php echo form_close() ?>
      <!--Iniciar listar-->
        <?php 
          $existe = count($proveedor);
          if($existe <= 0){
            echo 'No existen Proveedores registrados en el Sistema.';
          }
          else
          {
        ?>
      <table style="width:1264px;" border="0" cellspacing="0" cellpadding="0" id="listaProveedores">
        <thead>
          <tr class="tituloTable">
            <td sort="idprov" width="140" height="25">Item</td>
            <td sort="rzprov" width="330">Razón Social</td>
            <td sort="rucprov" width="170">RUC</td>
            <td sort="paisprov" width="190">País</td>
            <td sort="contprov" width="460">Dirección</td>
            <td sort="telprov" width="120">Teléfono</td>
            <td sort="telprov" width="154">Fecha de Registro</td>
            <td sort="telprov" width="150">Almacén</td>
          </tr>
        </thead>
        <?php
          $i = 1;  
          foreach($proveedor as $listaproveedores){ 
        ?>  
          <tr class="contentTable">
            <!--<td><?php //echo str_pad($listaproveedores->id_proveedor, 5, 0, STR_PAD_LEFT); ?></td>-->
            <td height="27"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listaproveedores->razon_social; ?></td>
            <td><?php echo $listaproveedores->ruc; ?></td>
            <td><?php echo $listaproveedores->pais; ?></td>
            <td><?php echo $listaproveedores->direccion; ?></td>
            <td><?php echo $listaproveedores->telefono1; ?></td>
            <td><?php echo $listaproveedores->fe_registro; ?></td>
            <td><?php echo $listaproveedores->no_almacen; ?></td>
          </tr>
        <?php
          $i++;
          } 
        ?>
        <tfoot class="nav">
                <tr>
                  <td colspan=9>
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
  <div id="mdlEditarProveedor"></div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>
    <div style="display:none">
    <div id="direccionelim"><?php echo site_url('comercial/eliminarproveedor');?></div>
  </div>
  <div id="dialog-confirm" style="display: none;" title="Eliminar Proveedor">
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      ¿Está seguro que quiere eliminar este Proveedor?<br /><strong>¡Esta acción no se puede revertir!</strong>
    </p>
  </div>