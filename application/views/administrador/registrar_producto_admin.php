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

    $("#export_excel").click(function(){
      //var id_categoria = $("#categoria").val();
      url = '<?php echo base_url(); ?>administrador/ad_exportar_producto_excel';
      $(location).attr('href',url);
    });

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

    $('#listaProductosAdmin').DataTable();

  });

  //Fuera de $(function(){         });
  function resetear(){
      window.location.href="<?php echo base_url();?>administrador/gestionproductos_admin";
  }

</script>
</head>
<body>
  <div id="contenedor">
    <div id="tituloCont">Gestión de Productos - Repuestos y Suministros</div>
    <div id="formFiltro">
      <?php 
        $existe = count($producto);
        if($existe <= 0){
          echo 'No existen Productos registrados en el Sistema.';
        }
        else
        {
      ?>
        <table border="0" cellspacing="0" cellpadding="0" id="listaProductosAdmin" style="width:1370px;" class="table table-hover table-striped">
          <thead>
            <tr class="tituloTable" style="font-family: Helvetica Neu,Helvetica,Arial,sans-serif;font-size: 12px;height: 35px;">
              <td sort="idproducto" width="120" height="27">ID PRODUCTO</td>
              <td sort="nombreprod" width="330">NOMBRE O DESCRIPCIÓN</td>
              <td sort="catprod" width="110">UBICACIÓN</td>
              <td sort="catprod" width="125">CATEGORIA</td>
              <td sort="catprod" width="150">TIPO PRODUCTO</td>
              <td sort="procprod" width="95">MEDIDA</td>
              <td sort="procprod" width="90">STOCK</td>
              <td sort="procprod" width="65">P.U.</td>
            </tr>
          </thead>
          <?php
            $i = 1;
            foreach($producto as $listaproductos){ 
          ?>  
          <tr class="contentTable" style="font-size: 12px;">
            <!--<td height="27"><?php //echo str_pad($listaproductos->id_pro, 5, 0, STR_PAD_LEFT); ?></td>-->
            <td style="vertical-align: middle;height: 23px;"><?php echo 'PRD'.$listaproductos->id_pro; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproductos->no_producto; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproductos->nombre_ubicacion; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproductos->no_categoria; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproductos->no_tipo_producto; ?></td>
            <!--<td><?php // echo $listaproductos->no_procedencia; ?></td>-->
            <td style="vertical-align: middle;"><?php echo $listaproductos->nom_uni_med; ?></td>
            <td style="vertical-align: middle;"><?php echo $listaproductos->stock; ?></td>
            <td style="vertical-align: middle;"><?php echo @number_format($listaproductos->precio_unitario, 2, '.', ''); ?></td>
          </tr>
          <?php
            $i++;
            } 
          ?>         
        </table>
      <?php }?>
    </div>
  </div>
  <div id="modalerror"></div>
  <div id="finregistro"></div>

    