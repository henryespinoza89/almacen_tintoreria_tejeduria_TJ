<?php
	if ($this->input->post('fecha_cierre')){
	    $fecha_cierre = array('name'=>'fecha_cierre','id'=>'fecha_cierre','maxlength'=>'10','value'=>$this->input->post('fecha_cierre'), 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}else{
	    $fecha_cierre = array('name'=>'fecha_cierre','id'=>'fecha_cierre','maxlength'=>'10', 'style'=>'width:100px','readonly'=> 'readonly', 'class'=>'required');
	}
?>

<script type="text/javascript">
	$(function(){
		
		$("#div-loader").hide();
		
		$("#report_exportar_excel").click(function(){
	    	url = '<?php echo base_url(); ?>comercial/al_exportar_cierre_excel/';
	    	$(location).attr('href',url);
    	});

		$("#cierre_almacen_butonn").click(function(){
			$("#div-loader").show().dialog({modal: true,position: 'center',width: 300, height: 195,resizable: false,hide: 'blind',show: 'blind',});
    		var fecha_cierre = $("#fecha_cierre").val();
    		if( fecha_cierre == ''){
    			$("#div-loader").hide().dialog("destroy");
				$("#modalerror").html('<strong>!El Campo del Formulario es Obligatorio. Verificar!</strong>').dialog({
		            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación de Registro',hide: 'blind',show: 'blind',
		          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
		        });
			}else{
				$.ajax({
	                type: 'POST',
	                url: "<?php echo base_url(); ?>comercial/cierre_almacen/",
	                data: {
	                  'fecha_cierre' : fecha_cierre
	                },
	                success: function(response){
	                  	if(response == 'ok'){
	                  		$("#div-loader").hide().dialog("destroy");
			                $("#finregistro").html('!El Cierre de Almacén ha sido regristado con éxito!.').dialog({
			                  	modal: true,position: 'center',width: 350,height: 125,resizable: false, title: 'Fin de Registro',
			                  	buttons: { Ok: function(){
			                    	window.location.href="<?php echo base_url();?>comercial/gestioncierrealmacen";
			                  	}}
			                });
	                  	}else if(response == 'error_validacion'){
	                  		$("#div-loader").hide().dialog("destroy");
	                  		$("#modalerror").html('<strong>!Ya Existe registro de Cierre de Almacén para la Fecha Seleccionada. Verifique!</strong>').dialog({
					            modal: true,position: 'center',width: 450, height: 135,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',
					          	buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}
					        });
	                  	}else{
	                  		$("#div-loader").hide().dialog("destroy");
	                  		$("#modalerror").html('<strong>!No se realizo el Registro. Intentelo Nuevamente!</strong>').dialog({
					            modal: true,position: 'center',width: 450, height: 125,resizable: false,title: 'Validación',hide: 'blind',show: 'blind',buttons: { Ok: function() {$(".ui-dialog-buttonpane button:contains('Registrar')").button("enable");$( this ).dialog( "close" );}}});
	                  	}
	                }
	            });
			}
    	});

		//Script para crear la tabla que será el contenedor de los productos registrados
	    $('#listaMontosCierre').jTPS( {perPages:[10,20,30,50,'Todos'],scrollStep:1,scrollDelay:30,clickCallback:function () {     
	        // target table selector
	        var table = '#listaMontosCierre';
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
                            $('#listaMontosCierre .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                    } else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                            $('#listaMontosCierre .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                    } else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                            $('#listaMontosCierre .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                    }
                }
            }
	    }

	    // bind mouseover for each tbody row and change cell (td) hover style
	    $('#listaMontosCierre tbody tr:not(.stubCell)').bind('mouseover mouseout',
            function (e) {
                e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
            }
	    );

		$("#fecha_cierre").datepicker({ 
			dateFormat: 'yy-mm-dd',showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/img/calendar.png",
			buttonImageOnly: true,
		    changeMonth: true,
		    changeYear: true
		});
		$(".ui-datepicker-trigger").css('padding-left','7px');

	});
</script>

</head>
<body>
    <div id="contenedor" style="">
    	<div id="tituloCont" style="margin-bottom:0px;width: 1380px;">Cierre de Almacén</div>
    	<div id="formFiltro" style="background: whitesmoke;padding-top: 5px;padding-left: 15px;padding-bottom: 10px;border-bottom: 1px solid #000;">
			<table width="750" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
				<tr>
	                <td width="180" height="30" style="padding-bottom: 5px;">Seleccione la Fecha de Cierre:</td>
	                <td width="160" height="30"><?php echo form_input($fecha_cierre);?></td>
                    <td width="180"><input name="submit" type="submit" id="cierre_almacen_butonn" class="cierre_almacen_butonn" value="Realizar Cierre de Almacén" style="background-color: #4B8A08;width: 161px;margin-bottom: 6px;" /></td>
                    <td width="195"><input name="submit" type="submit" id="report_exportar_excel" class="report_exportar_excel" value="Exportar a Excel" style="background-color: #4B8A08;width: 120px;margin-bottom: 6px;" /></td>
	            </tr>
			</table>
		</div>
		<!-- Iniciar Listado -->
		<?php 
          	$existe = count($monto);
          	if($existe <= 0){
            	echo 'No existen registros en el Sistema.';
          	}
          	else
            {
        ?>
        	
	        <table border="0" cellspacing="0" cellpadding="0" id="listaMontosCierre" style="width:900px;margin-top: 20px;margin-bottom: 5px;">
	          	<thead>
		            <tr class="tituloTable">
		              	<td sort="idprod" width="60" height="24">Item</td>
		              	<td sort="idproducto" width="150" height="24">Fecha de Cierre</td>
		              	<td sort="idproducto" width="150" height="24">Mes</td>
		              	<td sort="nombreprod" width="180">Monto de Cierre Sta. Anita</td>
		              	<td sort="nombreprod" width="180">Monto de Cierre Sta. Clara</td>
		              	<td sort="nombreprod" width="180">Monto de Cierre General</td>
		            </tr>
	          	</thead>
		        <?php
	            	$i = 1;
	            	foreach($monto as $data){
	            		/*(array)$arr = str_split(date('Y-m-d'), 4);
        				$anio_sistema = $arr[0];
        				/* Formato para la fecha 
						$elementos = explode("-", $data->fecha_cierre);
						$anio_bd = $elementos[0];
						if($anio_sistema == $anio_bd){*/
	          	?>
				          	<tr class="contentTable">
					            <td height="25"><?php echo str_pad($i, 4, 0, STR_PAD_LEFT); ?></td>
					            <td><?php echo $data->fecha_cierre; ?></td>
					            <td><?php echo $data->nombre_mes; ?></td>
					            <td><?php echo number_format($data->monto_cierre_sta_anita,2,'.',','); ?></td>
					            <td><?php echo number_format($data->monto_cierre_sta_clara,2,'.',','); ?></td>
					            <td><?php echo number_format($data->monto_cierre,2,'.',','); ?></td>
					        </tr>
		        <?php
		        		//}
		            	$i++;
		            }
		        ?>
		        <tfoot class="nav">
	                <tr>
	                    <td colspan=6>
	                        <div class="pagination"></div>
	                        <div class="paginationTitle">Página</div>
	                        <div class="selectPerPage"></div>
	                    </td>
	                </tr>                   
	          	</tfoot>
		    </table>
		    
	    <?php }?>
    </div>
    <div id="modalerror"></div>
    <div id="finregistro"></div>
    <div id="div-loader" style="text-align: center;background-color: white;display: none;">
	    <img src="<?php echo base_url();?>assets/img/ajax-loader.GIF"><br>
	    <img src="<?php echo base_url();?>assets/img/ajax-loader-text.GIF">
	</div>