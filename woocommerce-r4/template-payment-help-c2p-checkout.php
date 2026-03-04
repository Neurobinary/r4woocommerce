<?php
$order_total = 0;

$order = $this->order;

$order_total        = method_exists( $order, 'get_total' )?$order->get_total():$order->order_total;
$rate               = $this->get_convertion_rate( get_woocommerce_currency(), 'VES' );
$order_total        = round( $rate * $order_total, 2 );

$order_total_format = number_format( $order_total, 2, '.', '' );
$order_total_format_display = number_format( $order_total, 2, ',', '.' );
?>
<form method="POST" onsubmit="document.getElementById('br4_p2c_submit').disabled=true;document.getElementById('br4_p2c_cancel').disabled=true;">
<div class="br4_p2c_reference_field" id="br4_p2c_reference_field">
	<style>
		.kbr4_logo_c2p {
			width: 100% !important;
			max-width: 70px !important;
			margin-bottom: 10px !important;
			height: unset !important;
			max-height: unset !important;
		}
	</style>
	<p class="br4_p2c_total_payment">
		<b><?php echo __( 'Monto a pagar', 'woocommerce-kr4' ); ?>:</b> <span style="color:red"><?php echo $order_total_format_display; ?> Bs.</span><br />
	</b>
	<label for="br4_p2c_phone" class="pya-grid-item"><?php echo __( 'Número de teléfono', 'woocommerce-kr4' ); ?></label>
	<input value="<?php echo $_POST['br4_p2c_phone'] ?? ''; ?>" type="text" id="br4_p2c_phone" name="br4_p2c_phone" placeholder="0412-1234567" class="pya-grid-item" />
	<label for="br4_p2c_reference" class="pya-grid-item"><?php echo __( 'Token de Pago', 'woocommerce-kr4' ); ?></label>
	<input type="password" id="br4_p2c_reference" name="br4_p2c_reference" maxlength="9" placeholder="000000" class="pya-grid-item" />
	<label for="br4_p2c_bank" class="pya-grid-item"><?php echo __( 'Banco', 'woocommerce-kr4' ); ?></label>
	<select id="br4_p2c_bank" name="br4_p2c_bank" class="pya-grid-item">
		<option value="0102">BANCO DE VENEZUELA</option>
		<option value="0134">BANESCO</option>  
		<option value="0105">BANCO MERCANTIL</option>
		<option value="0108">BANCO PROVINCIAL BBVA</option>
		<option value="0172">BANCAMIGA</option>
		<option value="0191">BANCO NACIONAL DE CREDITO</option>
		<option value="0138">BANCO PLAZA</option>
		<option value="0104">BANCO VENEZOLANO DE CREDITO</option>
		<option value="0156">100%BANCO</option>
		<option value="0196">ABN AMRO BANK</option>
		<option value="0114">BANCARIBE - BANCO DEL CARIBE C.A.</option>
		<option value="0171">BANCO ACTIVO BANCO COMERCIAL, C.A.</option>
		<option value="0166">BANCO AGRICOLA</option>
		<option value="0175">BANCO BICENTENARIO</option>
		<option value="0128">BANCO CARONI, C.A. BANCO UNIVERSAL</option>
		<option value="0164">BANCO DE DESARROLLO DEL MICROEMPRESARIO</option>
		<option value="0149">BANCO DEL PUEBLO SOBERANO C.A.</option>
		<option value="0163">BANCO DEL TESORO</option>
		<option value="0176">BANCO ESPIRITO SANTO, S.A.</option>
		<option value="0115">BANCO EXTERIOR C.A.</option>
		<option value="0003">BANCO INDUSTRIAL DE VENEZUELA.</option>
		<option value="0173">BANCO INTERNACIONAL DE DESARROLLO, C.A.</option>
		<option value="0191">BANCO NACIONAL DE CREDITO</option>
		<option value="0116">BANCO OCCIDENTAL DE DESCUENTO.</option>
		<option value="0168">BANCRECER S.A. BANCO DE DESARROLLO</option>
		<option value="0177">BANFANB</option>
		<option value="0146">BANGENTE</option>
		<option value="0174">BANPLUS BANCO COMERCIAL C.A</option>
		<option value="0190">CITIBANK.</option>
		<option value="0121">CORP BANCA.</option>
		<option value="0157">DELSUR BANCO UNIVERSAL</option>
		<option value="0151">FONDO COMUN</option>
		<option value="0601">INSTITUTO MUNICIPAL DE CR&#201;DITO POPULAR</option>
		<option value="0169">R4 - MIBANCO BANCO DE DESARROLLO, C.A.</option>
		<option value="0137">SOFITASA</option>
	</select>
	<label for="br4_p2c_dni_type" class="pya-grid-item"><?php echo __( 'Documento de Identidad', 'woocommerce-kr4' ); ?></label>
	<div class="pya-grid-item pya-dni-container" style="display: flex; flex-direction: row;">
		<select id="br4_p2c_dni_type" name="br4_p2c_dni_type" style="width: 50px;max-width: 50px;margin-right: 5px;">
			<option value="V">V</option>
			<option value="E">E</option>
			<option value="P">P</option>
		</select>
		<input value="<?php echo $_POST['br4_p2c_dni'] ?? ''; ?>" type="text" id="br4_p2c_dni" name="br4_p2c_dni" placeholder="Cédula" class="pya-grid-item" style="width: 100%;" />
	</div>
	<script>
		document.getElementById('br4_p2c_bank').value = '<?php echo $_POST['br4_p2c_bank'] ?? ''; ?>';
		document.getElementById('br4_p2c_dni_type').value = '<?php echo $_POST['br4_p2c_dni_type'] ?? ''; ?>';
	</script>
	<br />
	<p class="br4_p2c_total_payment">
		<a href="javascript:void(0);" onclick="document.getElementById('help-c2p-btpayment').style.display = 'block';"><?php echo __('Guia para obtener Token de Pago', 'woocommerce-kr4' ); ?></a>
		<br />
		<div class="row divform7" id="help-c2p-btpayment" style="display: none;"><div class="col-md-12">
			<b>Banco Banesco:</b> Envia un SMS al 2846 con la palabra "clave dinamica", el tipo de cédula (V ó E) y el número de cédula. Ejemplo: clave dinamica V 11222333<br />
			<b>Banco Mercantil:</b> Envia un SMS al 24024 con la palabra SCP<br />
			<b>Banco Provincial:</b> Ingresa al APP Dinero Rápido, ubica la opcion en el menu Generar clave de Compra.<br />
			<b>Banco de Venezuela:</b> Envía un SMS al 2662 con las palabras CLAVE DE PAGO desde tu numero afiliado a Pago Móvil.<br />
			<b>Banco del Tesoro - R4:</b> Ingresa al APP Tesoro Pago Móvil o envia un SMS al 2383 con el formato "comercio TIPO_CEDULA CEDULA COORDENAD" (Sin las comillas).<br />
			<b>Bancamiga:</b> Ingresa al APP Bancamiga Suite y ubica la opcion de clave OTP.<br />
			<b>Banco BNC:</b> Ingresa al app BNC Móvil Personas, Pago Móvil -> Generar Token y sigue los pasos.<br />
			<b>Otros Bancos:</b> Solicite a un operador de soporte de su banco información sobre obtener una clave de pago para C2P.<br />
			<br style="clear:both;" />
		</div></div>
	</p>
</div>
	<table><tr>
		<td><input type="submit" name="br4_p2c_submit" id="br4_p2c_submit" class="button alt" value="Validar Pago" onclick="this.disabled=true;document.getElementById('br4_p2c_cancel').disabled=true;this.form.submit();" /></td>
		<td><input type="button" name="br4_p2c_cancel" id="br4_p2c_cancel" class="button" value="Cancelar" onclick="this.disabled=true;document.getElementById('br4_p2c_submit').disabled=true;document.location.href = '<?php echo esc_url( wc_get_checkout_url() ); ?>';" /></td>
	</tr></table>
	
</form>
<style>
	.payment_method_kr4_gateway p {
		margin: 0 !important;
	}
	.pya-date-container > select {
		width: 80px;
		max-width: 80px;
		margin-right: 5px;
	}
	#br4_p2c_reference_field button {
		margin: 0;
		padding: 4px;
		line-height: 12px;
		border: red;
		border-radius: 4px;
		background: red;
		color: white;
		font-size: 12px;
		font-weight: bold;
	}
	.br4_p2c_reference_field input, .br4_p2c_reference_field select {
		padding: 8px;
		border: 1px solid #ccc;
		border-radius: 12px;
		font-family: sans-serif;
		font-size: 14px !important;
	}
	.br4_p2c_reference_field {
		display: flex;
		gap: 0px;
		width: 100%;
		flex-direction: column;
		align-content: flex-start;
		justify-content: flex-start;
		align-items: flex
	}
	.pya-grid-item {
		width: 100%;
		margin: 0 !important;
		box-sizing: border-box;
	}
	.pya-dni-container * {
		display: inline;
	}
	#br4_p2c_reference_info > ul > li {
		background-color: transparent !important;
		list-style: none !important;
		line-height: 20px !important;
		font-size: 14px;
	}
	#br4_p2c_reference_info ul, #br4_p2c_reference_info  li {
		margin: 0 !important;
		padding: 0 !important;
	}
	#br4_p2c_reference_info > ul > li::before {
		display: none;
	}
	.br4_p2c_reference_info * {
		font-family: sans-serif;
	}
</style>
