<script type="text/javascript">
  function onlytext(){
    if((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
      event.returnValue = false;
  }
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
    <?php 
      if ($this->input->post('listatipousuarios')){
          $selected_tipousuarios =  (int)$this->input->post('listatipousuarios');
        }else{  $selected_tipousuarios = "";
      ?>
          $("#listatipousuarios").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
      <?php 
        } 
    ?>
    $("#tiposusuarios").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    $("#almacen_3").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    $("#estado").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
    //Venta Modal Registrar Producto
    $(".newprospect").click(function() { //activacion de ventana modal
      $("#mdlNuevoUsuario" ).dialog({  //declaracion de ventana modal
          modal: true,resizable: false,show: "blind",position: 'center',width: 350,height: 480,draggable: false,closeOnEscape: false,
          buttons: {
          Registrar: function() {
              $(".ui-dialog-buttonpane button:contains('Registrar')").button("disable");
              $(".ui-dialog-buttonpane button:contains('Registrar')").attr("disabled", true).addClass("ui-state-disabled");
              //CONTROLO LAS VARIABLES
              var nombreusu = $('#nombreusu').val(); apellidousu = $('#apellidousu').val(); estado = $('#estado').val(); email = $('#email').val();
              var usuario = $('#usuario').val(); tiposusuarios = $('#tiposusuarios').val(); almacen = $('#almacen_3').val(); 
              datacontrasena = $('#datacontrasena').val(); rptcontrasena = $('#rptcontrasena').val();
              if(nombreusu == '' || apellidousu == '' || estado == ''|| usuario == '' || tiposusuarios == ''|| datacontrasena == '' || rptcontrasena == '' || email == ''){
                $("#modalerror").html('<b>ERROR:</b> Faltan completar algunos campos del formulario, por favor verifique.').dialog({
                  modal: true,position: 'center',width: 450, height: 150,resizable: false,
                  buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
                });
              }else{
                if(tiposusuarios == 2){
                  if(nombreusu == '' || apellidousu == '' || estado == ''|| usuario == '' || tiposusuarios == '' || almacen == '' || datacontrasena == '' || rptcontrasena == '' || email == ''){
                    $("#modalerror").html('<b>ERROR:</b> Faltan completar algunos campos del formulario, por favor verifique.').dialog({
                      modal: true,position: 'center',width: 450, height: 150,resizable: false,
                      buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
                    });
                  }else{
                    if((datacontrasena == rptcontrasena) && seguridad >= 50){
                      //REGISTRO
                      var dataString = 'nombreusu='+nombreusu+'&apellidousu='+apellidousu+'&estado='+estado+'&usuario='+usuario+'&tiposusuarios='+tiposusuarios+'&almacen='+almacen+'&datacontrasena='+datacontrasena+'&email='+email+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';                      
                      $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>administrador/registrarusuario/",
                        data: dataString,
                        success: function(msg){
                          if(msg == 1){
                            $("#finregistro").html('!El Usuario ha sido regristado con éxito!.').dialog({
                            modal: true,position: 'center',width: 300,height: 125,resizable: false, title: 'Fin de Registro',
                            buttons: { Ok: function(){
                              window.location.href="<?php echo base_url();?>administrador/gestionusuarios_admin";
                              }}
                            });
                          }else{
                            $("#modalerror").empty().append(msg).dialog({
                              modal: true,position: 'center',width: 500,height: 120,resizable: false,
                              buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
                            });
                            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
                          }
                        }
                      });
                    }else{
                            $("#modalerror").html('<b>ERROR:</b> La contraseña no es lo suficientemente segura, debe ser mayor a 50%.').dialog({
                               modal: true,position: 'center',width: 480, height: 150,resizable: false,
                               buttons: {
                                  Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}
                               }
                            });
                          }
                  }
                }else if(tiposusuarios == 1 || tiposusuarios == 3){
                  if(nombreusu == '' || apellidousu == '' || estado == ''|| usuario == '' || tiposusuarios == '' || datacontrasena == '' || rptcontrasena == '' || email == ''){
                    $("#modalerror").html('<b>ERROR:</b> Faltan completar algunos campos del formulario, por favor verifique.').dialog({
                      modal: true,position: 'center',width: 450, height: 150,resizable: false,
                      buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
                    });
                  }else{
                    if((datacontrasena == rptcontrasena) && seguridad >= 50){
                      //REGISTRO
                      if(tiposusuarios == 1){
                        var dataString = 'nombreusu='+nombreusu+'&apellidousu='+apellidousu+'&estado='+estado+'&usuario='+usuario+'&tiposusuarios='+tiposusuarios+'&almacen='+4+'&datacontrasena='+datacontrasena+'&email='+email+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
                      }else if(tiposusuarios == 3){
                        var dataString = 'nombreusu='+nombreusu+'&apellidousu='+apellidousu+'&estado='+estado+'&usuario='+usuario+'&tiposusuarios='+tiposusuarios+'&almacen='+5+'&datacontrasena='+datacontrasena+'&email='+email+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';                      
                      }
                      $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>administrador/registrarusuario/",
                        data: dataString,
                        success: function(msg){
                          if(msg == 1){
                            $("#finregistro").html('!El Usuario ha sido regristado con éxito!.').dialog({
                            modal: true,position: 'center',width: 300,height: 125,resizable: false, title: 'Fin de Registro',
                            buttons: { Ok: function(){
                              window.location.href="<?php echo base_url();?>administrador/gestionusuarios_admin";
                              }}
                            });
                          }else{
                            $("#modalerror").empty().append(msg).dialog({
                              modal: true,position: 'center',width: 500,height: 120,resizable: false,
                              buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
                            });
                            $(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");
                          }
                        }
                      });
                    }else{
                            $("#modalerror").html('<b>ERROR:</b> La contraseña no es lo suficientemente segura, debe ser mayor a 50%.').dialog({
                               modal: true,position: 'center',width: 480, height: 150,resizable: false,
                               buttons: {
                                  Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}
                               }
                            });
                          }
                  }
                }
              }
            },
            Cancelar: function(){
              $("#mdlNuevoUsuario").dialog("close");
            }
          }
      });
    });

    //Script para crear la tabla que será el contenedor de los productos registrados
    $('#listaUsuarios').jTPS( {perPages:[10,20,30,50,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
            // target table selector
            var table = '#listaUsuarios';
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
                                        $('#listaUsuarios .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                                } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                                        $('#listaUsuarios .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                                } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                                        $('#listaUsuarios .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                                }
                        }
                }
        }

        // bind mouseover for each tbody row and change cell (td) hover style
        $('#listaUsuarios tbody tr:not(.stubCell)').bind('mouseover mouseout',
                function (e) {
                        // hilight the row
                        e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                }
        );

      //Deshabilitar la opción de almacen si el usuario a registrar es un Administrador
      $("#tiposusuarios").change(function(){
        $("#tiposusuarios option:selected").each(function(){
          tipousuario = $('#tiposusuarios').val();
          if (tipousuario == 1 || tipousuario == 3){
            $("#almacen_3").css('visibility','hidden');
          }else{
            $("#almacen_3").css('visibility','visible');
          }
        });
      });
      $("#id_usuario").validCampoFranz('0123456789');

  });
  
  function resetear(){
      window.location.href="<?php echo base_url();?>administrador/gestionusuarios_admin";
  }

  function muestra_seguridad_clave(clave,formulario){
    seguridad=seguridad_clave(clave);
    if($("#datacontrasena").val() == $("#rptcontrasena").val()){
      $("#valSeguridad").html("Contrase&ntilde;a fuerte: "+ seguridad + "%");
      $("#securityPass").val(seguridad);
    }else{
      $("#valSeguridad").html("Las contraseñas no coinciden.");
    }
  }

  function muestra_seguridad_clave_editar(clave,formulario){
      seguridadedit=seguridad_clave(clave);
      if($("#editcontrasena").val() == $("#editrptcontrasena").val()){
        $("#valSeguridadEdit").html("Contrase&ntilde;a fuerte: "+ seguridadedit + "%");
        $("#securityPassEdit").val(seguridadedit);
      }else{
        $("#valSeguridadEdit").html("Las contraseñas no coinciden.");
      }
    }

  // editar usuario
  function editar_usuario(id_usuario){
        var urlVend = '<?php echo base_url();?>administrador/editarusuario/'+id_usuario;
        //alert(urlVend);
        $("#mdlEditarUsuario").load(urlVend).dialog({
          modal: true, position: 'center', width: 360, height: 480, draggable: false, resizable: false, closeOnEscape: false,
          buttons: {
            Actualizar: function() {
              $(".ui-dialog-buttonpane button:contains('Actualizar')").button("disable");
              $(".ui-dialog-buttonpane button:contains('Actualizar')").attr("disabled", true).addClass("ui-state-disabled");
              //CONTROLO LAS VARIABLES
              var editnombres = $('#editnombres').val(); editapellido = $('#editapellido').val(); editipo = $('#editipo').val(); editestado = $('#editestado').val(); editemail = $('#editemail').val();
              var editusuario=$('#editusuario').val(); editcontrasena = $('#editcontrasena').val();editrptcontrasena = $('#editrptcontrasena').val(); editalmacen = $('#editalmacen').val();
              if(editnombres == '' || editapellido == '' || editipo == '' || editestado == '' || editusuario=='' || editalmacen=='' || editemail==''){
                $("#modalerror").html('<b>ERROR:</b> Faltan completar algunos campos del formulario, por favor verifique.').dialog({
                  modal: true,position: 'center',width: 450, height: 150,resizable: false,
                  buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
                });
              }else{
                //var dataString = 'editnombres='+editnombres+'&editapellido='+editapellido+'&editipo='+editipo+'&editestado='+editestado+'&editalmacen='+editalmacen+'&editusuario='+editusuario+'&editcontrasena='+editcontrasena+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
                if(editipo == 1){
                  var dataString = 'editnombres='+editnombres+'&editapellido='+editapellido+'&editipo='+editipo+'&editestado='+editestado+'&editalmacen='+4+'&editusuario='+editusuario+'&editcontrasena='+editcontrasena+'&editemail='+editemail+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
                }else{
                  var dataString = 'editnombres='+editnombres+'&editapellido='+editapellido+'&editipo='+editipo+'&editestado='+editestado+'&editalmacen='+editalmacen+'&editusuario='+editusuario+'&editcontrasena='+editcontrasena+'&editemail='+editemail+'&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
                  //alert(estado);
                }
                if(editcontrasena == '' && editrptcontrasena == ''){
                  //alert(editestado);
                  $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>administrador/actualizarusuario/"+id_usuario,
                    data: dataString,
                    success: function(msg){
                      if(msg == 1){
                        $("#finregistro").html('!El Usuario ha sido Actualizado con éxito!.').dialog({
                          modal: true,position: 'center',width: 300,height: 125,resizable: false, title: 'Fin de Actualización',
                          buttons: { Ok: function(){
                            window.location.href="<?php echo base_url();?>administrador/gestionusuarios_admin";
                          }}
                        });
                      }else{
                        $("#modalerror").empty().append(msg).dialog({
                          modal: true,position: 'center',width: 500,height: 220,resizable: false,
                          buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
                        });
                        $(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");
                      }
                    }
                  });
                }else{
                  if((editcontrasena == editrptcontrasena) && seguridadedit >= 50){
                    $.ajax({
                      type: "POST",
                      url: "<?php echo base_url(); ?>administrador/actualizarusuario/"+id_usuario,
                      data: dataString,
                      success: function(msg){
                        if(msg == 1){
                          $("#finregistro").html('!El Usuario ha sido Actualizado con éxito!.').dialog({
                            modal: true,position: 'center',width: 300,height: 125,resizable: false, title: 'Fin de Actualización',
                            buttons: { Ok: function(){
                              window.location.href="<?php echo base_url();?>administrador/gestionusuarios_admin";
                            }}
                          });
                        }else{
                          $("#modalerror").empty().append(msg).dialog({
                            modal: true,position: 'center',width: 500,height: 220,resizable: false,
                            buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}}
                          });
                          $(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");
                        }
                      }
                    });
                  }else{
                    $("#modalerror").html('<b>ERROR:</b> La contraseña no es lo suficientemente segura, debe ser mayor a 50%.').dialog({
                      modal: true,position: 'center',width: 480, height: 145,resizable: false,
                      buttons: {
                        Ok: function() {$(".ui-dialog-buttonpane button:contains('Actualizar')").button("enable");$( this ).dialog( "close" );}
                      }
                    });
                  }
                }
              }
            },
            Cancelar: function(){
              $("#mdlEditarUsuario").dialog("close");
            }
          }
        });
  }
  // ************* Fin Editar Usuario *************
</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Gestión de Usuarios</div>
    <div id="formFiltro">
      <div class="tituloFiltro">Búsqueda</div>
      <form name="filtroBusqueda" action="#" method="post" style="margin-bottom: 10px;">
        <?php
          // para el ID
          if ($this->input->post('txt_usuario')){
            $txt_usuario = array('name'=>'txt_usuario','id'=>'txt_usuario','maxlength'=>'15','value'=>$this->input->post('txt_usuario'),'style'=>'width:150px');
          }else{
            $txt_usuario = array('name'=>'txt_usuario','id'=>'txt_usuario','maxlength'=>'15','style'=>'width:150px');
          }
          // para el NOMBRE Y APELLIDO
          if ($this->input->post('nombre')){
            $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1' , 'value' => $this->input->post('nombre'),'onkeypress'=>'onlytext()','style'=>'width:150px');
          }else{
            $nombre = array('name'=>'nombre','id'=>'nombre','maxlength'=> '50','minlength'=>'1','onkeypress'=>'onlytext()','style'=>'width:150px');
          }
        ?>
        <?php echo form_open(base_url()."comercial/gestionusuario", 'id="buscar"') ?>
          <table width="977" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="122" height="30" style="padding-bottom: 5px;">Nombre de Usuario:</td>
              <td width="152" height="30"><?php echo form_input($txt_usuario);?></td>
              <td width="105" height="30">Almacén:</td>
              <?php
                $existe = count($almacen);
                if($existe <= 0){ ?>
                  <td width="218" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
                <?php    
                    }
                    else
                    {
                  ?>
                    <td width="128"><?php echo form_dropdown('almacen',$almacen,$selected_almacen,'id="almacen" style="width:158px;"');?></td>
              <?php }?>
              <td width="252" style="padding-bottom:4px;">
                <input name="submit" type="submit" id="submit" value="Buscar" style="width: 100px;"/>
                <input name="reset" type="button" onclick="resetear()" value="Reestablecer" style="width: 100px;"/>
              </td>
            </tr>
            <tr>
                <td height="30" style="padding-bottom: 5px;">Nombre y Apellido:</td>
                <td height="30"><?php echo form_input($nombre);?></td>
                <td width="105" height="30">Tipo de Usuario:</td>
                <?php
                  $existe = count($listatipousuarios);
                  if($existe <= 0){ ?>
                    <td width="218" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
                  <?php    
                      }
                      else
                      {
                    ?>
                      <td width="128"><?php echo form_dropdown('listatipousuarios',$listatipousuarios,$selected_tipousuarios,'id="listatipousuarios" style="width:158px;"');?></td>
                <?php }?>
            </tr>
          </table>
        <?php echo form_close() ?>
      </form>
      <?php
        if ($this->input->post('txt_usuario')){
          $no_usu = array('name'=>'no_usu','id'=>'no_usu','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('txt_usuario'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $no_usu = array('name'=>'no_usu','id'=>'no_usu','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php
        if ($this->input->post('nombre')){
          $nombre_ape = array('name'=>'nombre_ape','id'=>'nombre_ape','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('nombre'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $nombre_ape = array('name'=>'nombre_ape','id'=>'nombre_ape','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
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
        if ($this->input->post('listatipousuarios')){
          $tipo_2 = array('name'=>'tipo_2','id'=>'tipo_2','minlength'=>'1' ,'maxlength'=>'20','value'=>$this->input->post('listatipousuarios'), 'style'=>'width:110px', 'type'=>'hidden');
        }else{
          $tipo_2 = array('name'=>'tipo_2','id'=>'tipo_2','maxlength'=>'20', 'style'=>'width:110px', 'type'=>'hidden');
        }
      ?>
      <?php echo form_open(base_url()."administrador/reporte_usuario_admin", 'id="reporte_usuario_admin"') ?>
        <table width="1380" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <div id="options" style="float:left;margin-bottom:5px;"><div class="newprospect">Nuevo Usuario</div></div>
              <?php echo form_input($no_usu);?>
              <?php echo form_input($nombre_ape);?>
              <?php echo form_input($almacen_2);?>
              <?php echo form_input($tipo_2);?>
              <input name="actualizar" type="submit" id="submit" value="Exportar a PDF" style="padding-bottom:3px; padding-top:3px; background-color: #005197; border-radius:6px;width: 120px;float: right;" />
            </td>
          </tr>
        </table>
      <?php echo form_close() ?>
      <!--Iniciar listar-->
        <?php 
          $existe = count($usuario);
          if($existe <= 0){
            echo 'No existen Usuarios registrados en el Sistema.';
          }
          else
          {
        ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaUsuarios">
          <thead>
            <tr class="tituloTable">
              <td sort="idprod" width="100" height="25">Item</td>
              <td sort="idproducto" width="135" height="25">Nombre</td>
              <td sort="idproducto" width="135" height="25">Apellido</td>
              <td sort="idproducto" width="230" height="25">Correo Electrónico</td>
              <td sort="nombreprod" width="100">Estado</td>
              <td sort="catprod" width="140">Usuario</td>
              <td sort="procprod" width="156">Tipo de Usuario</td>
              <td sort="procprod" width="160">Almacen</td>
              <td sort="procprod" width="160">Fecha de Registro</td>
              <td width="20">&nbsp;</td>
            </tr>
          </thead>
          <?php
            $i = 1;
            foreach($usuario as $listausuarios){ 
          ?>  
          <tr class="contentTable">
            <!--<td><?php //echo str_pad($listausuarios->id_usuario, 6, 0, STR_PAD_LEFT); ?></td>-->
            <td height="27"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
            <td><?php echo $listausuarios->no_usuario; ?></td>
            <td><?php echo $listausuarios->ape_paterno; ?></td>
            <td><?php echo $listausuarios->correo_electronico; ?></td>
            <td><?php echo $listausuarios->fl_estado; ?></td>
            <td><?php echo $listausuarios->tx_usuario; ?></td>
            <td><?php echo $listausuarios->no_tipo_usuario; ?></td>
            <td><?php echo $listausuarios->no_almacen; ?></td>
            <td><?php echo $listausuarios->fe_registro; ?></td>
            <td width="20" align="center"><img class="editar_usuario" src="<?php echo base_url();?>assets/img/edit.png" width="20" height="20" title="Editar Usuario" onClick="editar_usuario(<?php echo $listausuarios->id_usuario; ?>)" /></td>
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
  <!---  Ventanas modales -->
  <div id="mdlNuevoUsuario" style="display:none; width:250px:">
      <div id="contenedor" style="width:300px; height:354px;">
      <div id="tituloCont">Nuevo Usuario</div>
      <div id="formFiltro">
      <?php
        $nombreusu = array('name'=>'nombreusu','id'=>'nombreusu','maxlength'=>'50','style'=>'width:140px');//este es un input
        $apellidousu = array('name'=>'apellidousu','id'=>'apellidousu','maxlength'=>'50','style'=>'width:140px');//este es un input
        $email = array('name'=>'email','id'=>'email','maxlength'=>'50','style'=>'width:140px');//este es un input
        $estado  = array( 'true'=>'Activo', 'false'=>'Inactivo');
        $usuario = array('name'=>'usuario','id'=>'usuario','maxlength'=>'20','style'=>'width:140px');//este es un input
        $datacontrasena = array('name'=>'datacontrasena','id'=>'datacontrasena','maxlength'=>'12','minlength'=>'6','style'=>'width:140px');
        $datarptcontrasena = array('name'=>'rptcontrasena','id'=>'rptcontrasena','maxlength'=>'12','minlength'=>'6','style'=>'width:140px');
        $jsContrasena = 'onkeyup="muestra_seguridad_clave(this.value, this.form)"';
      ?>  
          <form method="post" id="nuevo_usuario" style=" border-bottom:0px; margin-bottom: 0px;">
          <table>
            <tr>
                <td width="168" height="30">Nombre:</td>
                <td width="200" style="padding-left: 15px;" height="30"><?php echo form_input($nombreusu);?></td>
            </tr>
            <tr>
              <td width="168" height="30">Apellido:</td>
                <td width="200" style="padding-left: 15px;" height="30"><?php echo form_input($apellidousu);?></td>
            </tr>
            <tr> 
                <td height="30">Tipo:</td>
                <td style="padding-left: 15px;" height="30"><?php echo form_dropdown('tiposusuarios',$listatipousuarios,'','id="tiposusuarios" style="width:148px;"');?></td>
            </tr>
            <tr>
                <td height="30">Estado:</td>
                <td style="padding-left: 15px;" height="30"><?php echo form_dropdown('estado',$estado, '','id="estado" style="width:120px;"');?></td>
            </tr>
            <tr>
                <td height="30">Almacen:</td>
                <td style="padding-left: 15px;" height="30"><?php echo form_dropdown('almacen_3',$almacen, '','id="almacen_3" style="width:120px;"');?></td>
            </tr>
            <tr>
                <td width="168" height="30">E-mail:</td>
                <td width="200" style="padding-left: 15px;" height="30"><?php echo form_input($email);?></td>
            </tr>
            <tr>
                <td width="168" height="30">Usuario:</td>
                <td width="200" style="padding-left: 15px;" height="30"><?php echo form_input($usuario);?></td>
            </tr>
            <tr>
                <td height="30">Contraseña:</td>
                <td style="padding-left: 15px;" height="30"><?php echo form_password($datacontrasena, '',$jsContrasena); ?></td>
            </tr>
            <tr>
                <td height="30">Re-contrase&ntilde;a:</td>
                <td style="padding-left: 15px;" height="30"><?php echo form_password($datarptcontrasena, '',$jsContrasena); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                  <table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td id="valSeguridad" style="padding-left:13px">Escriba una contraseña...</td>
                    </tr>
                    <tr>
                      <td><progress id="securityPass" value="0" max="100" style="width:150px;margin-left:17px"></progress></td>
                    </tr>
                  </table>
                </td>
            </tr>
          </table>
          </form>
        </div>
      </div>
    </div>
    <div id="mdlEditarUsuario"></div>
    <div id="modalerror"></div>
    <div id="finregistro"></div>