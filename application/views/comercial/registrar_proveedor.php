	<?php
		// INFORMACION BÁSICA
		$ruc = array('name'=>'ruc','id'=>'ruc','maxlength'=> '11','minlength'=>'11');
		$rz = array('name'=>'rz','id'=>'rz','maxlength'=>'50', 'class'=>'required');
		// UBICACION
		$pais = array('name'=>'pais','id'=>'pais','maxlength'=>'40','class'=>'required');
        $direccion = array('name'=>'direccion','id'=>'direccion','maxlength'=>'100' ,'style'=>'width:384px;','class'=>'required');
        $telefono1 = array('name'=>'telefono1','id'=>'telefono1','maxlength'=> '14','minlength'=>'7');
	?>
	<script type="text/javascript">
	$(function() {
        //Codigó para la creación de los steps
		//$("#form-2").validate();
		$("#wizard").steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex)
            {
                $("#form-2").validate().settings.ignore = ":disabled,:hidden";
                return $("#form-2").valid();
            },
            onFinishing: function (event, currentIndex)
            {
                $("#form-2").validate().settings.ignore = ":disabled";
                return $("#form-2").valid();
            },
            onFinished: function (event, currentIndex)
            {
                //$("#form-2").submit();
                var dataString = $("#form-2").serialize();
                $('.actions.clearfix').css("display","none");

                var nroruc = $('#ruc').val();
                if( nroruc == ""){
                	nroruc = 0;
                }
                $.post("<?php echo base_url(); ?>comercial/existeRuc", {
					nroruc : nroruc , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
				}, function(data){
					//alert("la info que se resive es : "+data);
					if(data == '1'){
						$.post("<?php echo base_url(); ?>comercial/datosRucExiste", {
						    nroruc : nroruc , <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
						}, function(datos) {
				        	var info = '<p>El número de RUC que ha indicado ya se encuentra registrado en el sistema. ¿Desea continuar?</p>';
			        		var datosruc = info+datos;
				        	$("#modalexiste").html(datosruc).dialog({
								modal: true,position: 'center',width: 790,resizable: false,
								buttons: { 
									Volver: function() {
										$('.actions.clearfix').css("float","left");
				            			$('.actions.clearfix').css("display","inline");
				            			$("#modalexiste").dialog("close");
				            			return false;
									},
									Cancelar: function(){
										$('.actions.clearfix').css("float","left");
				            			$('.actions.clearfix').css("display","inline");
				            			$("#modalexiste").dialog("close");
				            			setTimeout('window.location.href="gestionproveedores/"', 800);
				            			return false;
									}
								}
							});
						});
					}else{
						$.ajax({
							type: "POST",
							url: "<?php echo base_url(); ?>comercial/nuevo_proveedor",
							data: dataString,
							success: function(msg){
								if(msg == '0'){
									alert('No se registro al Proveedor :( ' +dataString );
								}else{
                    				swal({ title: "El Proveedor ha sido regristado con éxito!",text: "",type: "success",confirmButtonText: "OK",timer: 18000 });
                    				window.location.href="<?php echo base_url();?>comercial/gestionproveedores";
								}
							}
						});
					}
				});
            }
        });
        //Validaciones
        $("#codprov").validCampoFranz('0123456789');
		$("#ruc").validCampoFranz('0123456789');
		$("#telefono1").validCampoFranz('0123456789- ');            	
		//Agregamos SELECIONE a los combos
    	$("select").append('<option value="" selected="selected">:: SELECCIONE ::</option>');
	}); // Final del $(function() {
	
	function pad(n, length){
	   n = n.toString();
	   while(n.length < length) n = "0" + n;
	   return n;
	}

	function finalizar(){
		window.location.href="<?php echo base_url();?>comercial";
	}

	function regresar(){
		window.location.href="<?php echo base_url();?>comercial";
	}
	</script>
</header>
<div id="contenedor">
	<!--<div id="tituloCont">Nuevo Proveedor</div>-->
	<div id="tituloCont">Registro de Proveedores</div>
	<div id="formFiltro">
		<?php echo form_open("comercial/nuevo_proveedor", 'id="form-2" style="border:none"') ?>
			<div id="wizard">
                <h2>Información básica</h2>
                <section>
                    <table width="666" border="0" cellspacing="2" cellpadding="2">
					  <tr>
					    <td>Razón Social: (*)</td>
					    <td colspan="2"><?php echo form_input($rz);?></td>
					  </tr>
					  <tr>
					    <td width="167">RUC : (*)</td>
					    <td colspan="2"><?php echo form_input($ruc);?></td>
					  </tr>
					</table>
                </section>
                <h2>Ubicación</h2>
                <section>
					<table width="745" border="0" cellspacing="2" cellpadding="2">
					  	<tr>
                  			<td width="120">País: (*) </td>
                  			<td width="508" colspan="2"><?php echo form_input($pais);?></td>
              			</tr>
					    <tr>
		                  	<td>Dirección: (*)</td>
		                  	<td colspan="2"><?php echo form_input($direccion);?></td>
              			</tr>
              			<tr>
						    <td width="131">Teléfono:</td>
						    <td colspan="2"><?php echo form_input($telefono1);?></td>
					  	</tr>
					</table>
                </section>
		    </div>
	    <?php echo form_close() ?>
	</div>
</div>
<div id="finregistro"></div>
<div id="modalexiste" title="AVISO:" style="display:none">
	<p>El número de RUC que ha indicado ya se encuentra registrado en el sistema para este periodo.</p>
</div>
</body>
</html>