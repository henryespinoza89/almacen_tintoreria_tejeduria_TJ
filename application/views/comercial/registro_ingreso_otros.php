<?php
	if ($this->input->post('stockactual')){
		$stockactual = array('name'=>'stockactual','id'=>'stockactual','value'=>$this->input->post('stockactual'), 'style'=>'width:50px', 'class'=>'required','readonly'=> 'readonly');
	}else{
		$stockactual = array('name'=>'stockactual','id'=>'stockactual','style'=>'width:50px', 'class'=>'required','readonly'=> 'readonly');
	}
	if ($this->input->post('fecharegistro')){
		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10','value'=>$this->input->post('fecharegistro'), 'style'=>'width:150px','readonly'=> 'readonly', 'class'=>'required');
	}else{
		$fecharegistro = array('name'=>'fecharegistro','id'=>'fecharegistro','maxlength'=>'10', 'style'=>'width:150px','readonly'=> 'readonly', 'class'=>'required');
	}
	if ($this->input->post('numcomprobante')){
		$numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'10','value'=>$this->input->post('numcomprobante'), 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false');
	}else{
		$numcomprobante = array('name'=>'numcomprobante','id'=>'numcomprobante','maxlength'=>'10', 'style'=>'width:150px', 'class'=>'required','onpaste'=>'return false');
	}
	if ($this->input->post('unidadmedida')){
		$unidadmedida = array('name'=>'unidadmedida','id'=>'unidadmedida','value'=>$this->input->post('unidadmedida'), 'style'=>'width:100px', 'class'=>'required','readonly'=> 'readonly');
	}else{
		$unidadmedida = array('name'=>'unidadmedida','id'=>'unidadmedida','style'=>'width:100px', 'class'=>'required','readonly'=> 'readonly');
	}
	if ($this->input->post('cantidad')){
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10','value'=>$this->input->post('cantidad'), 'style'=>'width:70px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}else{
		$cantidad = array('name'=>'cantidad','id'=>'cantidad','maxlength'=>'10', 'style'=>'width:70px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}
	if ($this->input->post('pu')){
		$pu = array('name'=>'pu','id'=>'pu','maxlength'=>'10','value'=>$this->input->post('pu'),'style'=>'width:70px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}else{
		$pu = array('name'=>'pu','id'=>'pu','maxlength'=>'10', 'style'=>'width:70px', 'class'=>'required', 'onkeyup'=>'calcular()','onpaste'=>'return false');
	}
	$igvpu = array('name'=>'igvpu','id'=>'igvpu', 'style'=>'width:40px','readonly'=> 'readonly'); 
	$pusinigv = array('name'=>'pusinigv','id'=>'pusinigv', 'style'=>'width:40px','readonly'=> 'readonly');
	$pt = array('name'=>'pt','id'=>'pt', 'style'=>'width:70px','readonly'=> 'readonly'); 
	$igvpt = array('name'=>'igvpt','id'=>'igvpt', 'style'=>'width:40px','readonly'=> 'readonly'); 
	$ptsinigv = array('name'=>'ptsinigv','id'=>'ptsinigv', 'style'=>'width:40px','readonly'=> 'readonly');
	$porcentaje = array('name'=>'porcentaje','id'=>'porcentaje', 'style'=>'width:60px', 'onkeyup'=>'calculargastos()');
	$gastosaduanero = array('name'=>'gastosaduanero','id'=>'gastosaduanero', 'style'=>'width:60px','readonly'=> 'readonly'); 
?>

<script type="text/javascript">

	function calculargastos(){
		porcentaje = $("#porcentaje").val();
		if(porcentaje == '') porcentaje = 0;

		<?php 
            $existe = $this->cart->total_items();
            if($existe <= 0){
        ?>
            	gastosaduanero = 0;
        <?php 
    		}
              else
            {
        ?>
        		gastosaduanero = (<?php echo ($this->cart->total()+($this->cart->total()*0.18));?>)*(porcentaje/100);
        <?php
        	}
        ?>
        porcent = (porcentaje/100);
        $("#gastosaduanero").val(gastosaduanero); 
        $("#porcent").val(porcent);
	}

	function calcular(){
		unitario = $("#pu").val();
		if(unitario == '') unitario = 0;

		cantidad = $("#cantidad").val();
		if(cantidad == '') cantidad = 0;

		total = unitario * cantidad;

		igv_unitario = unitario / 1.18;
		pusinigv = unitario - igv_unitario;

		igv_total = total / 1.18;
		ptsinigv = total - igv_total;

		$("#pt").val(total);
		$("#igvpu").val(pusinigv);
		$("#pusinigv").val(igv_unitario);

		$("#igvpt").val(ptsinigv);
		$("#ptsinigv").val(igv_total);
	}


	$(function(){

		<?php 
			if ($this->input->post('comprobante')){
				$selected_compro =  (int)$this->input->post('comprobante');
			}else{	$selected_compro = "";
		?>
       			 $("#comprobante").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
			}	
		?>

		<?php 
			if ($this->input->post('moneda')){
				$selected_moneda =  (int)$this->input->post('moneda');
			}else{	$selected_moneda = "";
		?>
       			 $("#moneda").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
			}	
		?>

		<?php 
			if ($this->input->post('proveedor')){
				$selected_prov =  (int)$this->input->post('proveedor');
			}else{	$selected_prov = "";
		?>
       			 $("#proveedor").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
			}	
		?>

		<?php 
			if ($this->input->post('nomproducto')){
				$selected_prod =  (int)$this->input->post('nomproducto');
			}else{	$selected_prod = "";
		?>
       			 $("#nomproducto").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
		<?php 
			}	
		?>

		$("#fecharegistro").datepicker({ 
			dateFormat: 'dd-mm-yy',showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
			buttonImageOnly: true
		});
		$(".ui-datepicker-trigger").css('padding-left','7px'); // esta linea separa la imagen del calendario del input

		$("#nomproducto").change(function() {
			$("#nomproducto option:selected").each(function() {
		        nomproducto = $('#nomproducto').val();
		        $.post("<?php echo base_url(); ?>comercial/traerUnidadMedida", {
		            nomproducto : nomproducto , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
		        }, function(data) {
		            $("#unidadmedida").val(data);
		        });
		        $.post("<?php echo base_url(); ?>comercial/traerStock", {
		            nomproducto : nomproducto , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
		        }, function(data) {
		            $("#stockactual").val(data);
		        });
		    });
		});

		//Mostrar ::SELECCIONE:: en los combobox
       	//$("#nomproducto").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
       	//$("#comprobante").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
        //Validar campos, sólo numérico
        $("#numcomprobante").validCampoFranz('0123456789');
        $("#porcentaje").validCampoFranz('0123456789.');
        $("#cantidad").validCampoFranz('0123456789.');
        $("#pu").validCampoFranz('0123456789.');
	});

</script>

<div id="contenedor">
	<div id="tituloCont" style="margin-bottom:10px;"><em>Registro de Ingreso de Productos - Otros</em></div>
	<div id="formFiltro">
		<div id="options" style="border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 0;">
        	<div class="newtipocompro"><a href="<?php echo base_url(); ?>comercial/gestioncomprobante/">Gestionar Tipo de Comprobante</a></div>
        	<div class="newconsultotros"><a href="<?php echo base_url(); ?>comercial/gestionconsultarRegistros_otros/">Consultar Registros de Ingreso - Otros</a></div>
        	<!--<div class="newreporteotros"><a href="<?php echo base_url(); ?>comercial/gestionreporteingreso_otros/">Gestionar Reporte de Ingresos</a></div>-->
        	<div class="volver"><td colspan="2"><?php echo anchor('comercial/vaciar_listado', '<== Regresar', array('style'=>'text-decoration: none; background-color: #005197; color: white; font-family: tahoma; border-radius: 6px; padding: 3px 15px; font-size: 11px;')); ?></td></div>
      	</div>
		<!--<div><img src="<?php //echo base_url();?>assets/img/titulo_entrada_1.png" style="margin-top:3px;position:absolute;margin-left:10px;"></div>-->
		<!--<div><img src="<?php //echo base_url();?>assets/img/titulo_entrada_3.png" style="margin-top:3px;position:absolute;margin-left:397px;"></div>-->
			<?php echo form_open("comercial/finalizar_registro_otros", 'id="finalizar_registro_otros"') ?>
				<div id="datoscompro_otros">
					<table width="518" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;">
						<input type="hidden" name="porcent" id="porcent" value="0">
				        <tr>
				          	<td width="148" valign="middle" height="30">Comprobante:</td>
				          	<?php
				          		$existe = count($listacomprobante);
				          		if($existe <= 0){ ?>
					            	<td width="370" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
					        <?php    
					            }
					            else
					            {
				          	?>
				          			<td width="370" height="30"><?php echo form_dropdown('comprobante',$listacomprobante,$selected_compro,'id="comprobante" style="width:158px; "');?></td>
				          	<?php }?>
				        </tr>
						<tr>
							<td width="148" valign="middle" height="30">N° de Comprobante:</td>
				          	<td width="370" height="30"><?php echo form_input($numcomprobante);?></td>
						</tr>
						<tr>
							<td width="148" valign="middle" height="30">Moneda:</td>
				          	<td width="370" height="30"><?php echo form_dropdown('moneda',$listasimmon,$selected_moneda,'id="moneda" style="width:158px;"');?></td>
				          	
						</tr>		
						<tr>
							<td width="148" valign="middle" height="30">Proveedor:</td>
							<?php
				          		$existe = count($listaproveedor);
				          		if($existe <= 0){ ?>
					            	<td width="330" height="30"><b><?php echo 'Registrar en el Sistema';?></b></td>
					        <?php    
					            }
					            else
					            {
				          	?>
				          			<td width="370" height="30"><?php echo form_dropdown('proveedor',$listaproveedor,$selected_prov,'id="proveedor" style="width:158px;"');?></td>
				          	<?php }?>
						</tr>						        
						<tr>
				          	<td width="148" valign="middle" height="30">Fecha de Registro:</td>
				          	<td width="370" height="30"><?php echo form_input($fecharegistro);?></td>
				        </tr>
				        <tr> 
				        	<td colspan="2" style=" padding-top: 5px; padding-left: 182px;" height="30"><input name="submit" type="submit" id="submit" value="Finalizar Registro" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #F5A700; border-radius:6px;width: 150px;" /></td>
				        </tr>
				    </table>
				</div>
			<?php echo form_close() ?>
			<?php echo form_open("comercial/agregarcarrito_otros", 'id="agregarcarrito_otros"') ?>
				<div id="datosprod_otros" style="width:752px;">
					<table width="497" border="0" cellspacing="0" cellpadding="0">
				        <tr>
				          	<td width="114" valign="middle" height="30">Produto:</td>
				          	<?php
				          		$existe = count($listanombreproducto);
				          		if($existe <= 0){ ?>
					            	<td width="330" height="30"><b><?php echo 'No Existen Productos Registrados en el Sistema';?></b></td>
					        <?php    
					            }
					            else
					            {
				          	?>
				          			<td width="330" height="30"><?php echo form_dropdown('nomproducto',$listanombreproducto,$selected_prod,'id="nomproducto" style="width:324px;"');?></td>
				          	<?php }?>
				        </tr>
					</table>
					<table width="497" border="0" cellspacing="0" cellpadding="0">			        
				        <tr>
				        	<td width="127" valign="middle" style="color:#005197;" height="30">Unidad de Medida:</td>
				          	<td width="128" height="30"><?php echo form_input($unidadmedida);?></td>
							<td width="87" valign="middle" style="color:#005197;" height="30">Stock Actual:</td>
				          	<td width="155" height="30"><?php echo form_input($stockactual);?></td>
				        </tr>	
						<tr>
							<td width="127" valign="middle" height="30">Cantidad:</td>
				          	<td width="128" height="30"><?php echo form_input($cantidad);?></td>
						</tr>						        
					</table>
					<table width="450" border="0" cellspacing="0" cellpadding="0" style="margin-top: 3px;">			        
						<tr>
						    <td width="127" valign="middle" height="30">Precio Unitario:</td>
						    <td width="65" height="30"><?php echo form_input($pu);?></td>
						</tr>
						<tr>
						    <td width="127" valign="middle" style="color:#005197;" height="30">Precio Total:</td>
						    <td width="55" height="30"><?php echo form_input($pt);?></td>
						</tr>
					    <tr>
					       	<td width="117" style="padding-top: 3px;" colspan="6" height="30"><input name="submit" type="submit" id="submit" value="Agregar Producto" style="padding-bottom:3px; padding-top:3px; margin-bottom: 4px; background-color: #005197; border-radius:6px; margin-left: 305px;width: 150px;" /></td>
					    </tr>						        
					</table>
				</div>	
			<?php echo form_close() ?>
			<!--Iniciar listar-->
			<!---->
			<div style="float: left;margin-right: 100px;">
				<?php 
		             $existe = $this->cart->total_items();
		            if($existe <= 0){
		            	echo 'Ingresar los Datos del Comprobante y sus Productos Respectivamente';
		            }
		            else
		            {
		        ?>
			</div>

	        <?php echo form_open("comercial/actualizar_carrito_otros", 'id="actualizar" style="border-bottom: none; float: left;"') ?>
		        <table border="0" cellspacing="0" cellpadding="0" id="listaProductos"> <!--style="margin-left: 90px;"-->
			        <thead>
			            <tr class="tituloTable">
			              <td sort="idprod" width="80" height="25">Item</td>
			              <td sort="idproducto" width="80" height="25">Cantidad</td>
			              <td sort="nombreprod" width="380">Producto o Descripción</td>
			              <td sort="catprod" width="120">ID Producto</td>
			              <td sort="procprod" width="136">Precio Unitario</td>
			              <td sort="procprod" width="136">Valor Total</td>
			              <td sort="procprod" width="20">&nbsp;</td>
			            </tr>
			        </thead>
		            <?php 
		            $i = 1;
		            foreach($this->cart->contents() as $item){ 
		            	?>
		            	<input type="hidden" name="<?php echo $i; ?>[rowid]" value="<?php echo $item['rowid']; ?>" >

				        <tr class="contentTable" style="height: 32px; border-color: #F1EEEE;border-bottom-style: solid;">
				            <td><?php echo str_pad($i, 3, 0, STR_PAD_LEFT); ?></td>
				            <td>
				            	<input type="text" name="<?php echo $i; ?>[qty]" value="<?php echo $item['qty']; ?>" style="border-style: inherit; color: #898989; margin-bottom: 0px; padding: 0px; font-size: 11px; font-family: verdana; width: 80px; text-align: center;" >
				            </td>
				            <td><?php echo $item['name']; ?></td>
				            <td><?php echo $item['id']; ?></td>
				            <td><?php echo number_format($item['price'],2,',','.'); ?></td>
				            <td><?php echo number_format($item['subtotal'],2,',','.'); ?></td>
				            <td width="20" align="center">
				            	<?php echo anchor('comercial/remove_otros/'.$item['rowid'],'X',array('style'=>'text-decoration: none; color:#898989;')); ?>
				            </td>
				        </tr>
		            <?php 
						$i++;
						} 
					?>
					<tr>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;"> SUB-TOTAL: </td>
		            	<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;"><?php echo number_format($this->cart->total(),2,',','.'); ?></td>
		            </tr>
					<tr>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;"> I.G.V. 18%: </td>
		            	<td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;"><?php echo number_format(($this->cart->total()*0.18),2,',','.'); ?></td>
		            </tr>
		            <tr>
		                <td></td>
		                <td>&nbsp;</td>
		                <td>&nbsp;</td>
		                <td>&nbsp;</td>
		                <td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;"> TOTAL: </td>
		                <!--<td colspan="5">TOTAL:</td>-->
		                <td style="text-align:center; padding:2px; color:#898989; height: 25px; border-color: #F1EEEE;border-bottom-style: solid;"><?php echo number_format(($this->cart->total()+($this->cart->total()*0.18)),2,',','.'); ?></td>
		            </tr>
		            <tr>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>
		            </tr>
		            <tr>
		            	<td><input name="actualizar" type="submit" id="submit" value="Actualizar" style="padding-bottom:3px; padding-top:3px; background-color: #005197; border-radius:6px;" /></td>
		            	<td colspan="2"><?php echo anchor('comercial/vaciar_listado_otros', 'Vaciar Listado de Productos', array('style'=>'text-decoration: none; background-color: #005197; color: white; font-family: tahoma; border-radius: 6px; padding: 3px 15px; font-size: 11px;')); ?></td>
		            	<td>&nbsp;</td>
		            	<td>&nbsp;</td>            	
		            </tr>
		        </table>
		    <?php echo form_close() ?>
	        <?php }?>
	</div>
</div>