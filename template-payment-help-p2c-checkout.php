<?php
$order_total = 0;
$order = $this->order;
$order_total        = method_exists( $order, 'get_total' )?$order->get_total():$order->order_total;
$rate               = $this->get_convertion_rate( get_woocommerce_currency(), 'VES' );
$order_total        = round( $rate * $order_total, 2 );

$order_total_format = number_format( $order_total, 2, '.', '' );
$order_total_format_display = number_format( $order_total, 2, ',', '.' );

$datetime_gmt = new DateTime('now', new DateTimeZone('GMT'));

$datetime_caracas = $datetime_gmt->setTimezone(new DateTimeZone('America/Caracas'));
$get_date_yyyy_mm_dd_caracas = $datetime_caracas->format('Y-m-d H:i:s');

?>
<form method="POST" onsubmit="document.getElementById('br4_p2c_submit').disabled=true;document.getElementById('br4_p2c_cancel').disabled=true;">
	<div class="br4_p2c_reference_field" id="br4_p2c_reference_field">
		<style>
			.kbr4_logo {
				width: 100% !important;
				max-width: 200px !important;
				margin-bottom: 10px !important;
				height: unset !important;
				max-height: unset !important;
			}
		</style>
		<p class="br4_p2c_total_payment">
			<b><?php echo __( 'Teléfono Pago Móvil', 'woocommerce-kr4' ); ?>:</b> <?php echo $this->phone; ?><br />
			<b><?php echo __( 'Nro. de Identificación', 'woocommerce-kr4' ); ?>:</b> <span style="color:red">IMPORTANTE: Ingresa tu Cédula de Identidad personal (no el RIF de nuestra empresa)</span><br />
			<b><?php echo __( 'Banco', 'woocommerce-kr4' ); ?>:</b> Mi Banco - R4 (0169)<br />
			<b><?php echo __( 'Concepto de pago', 'woocommerce-kr4' ); ?>:</b> <span style="color:red">Orden <?php echo $order->get_id(); ?></span><br />
			<b><?php echo __( 'Monto a pagar', 'woocommerce-kr4' ); ?>:</b> <span style="color:red"><?php echo $order_total_format_display; ?> Bs.</span><br />
		</p>
		<!-- <a href="javascript:void(0)" onclick="jQuery('.br4_p2c_form').show();jQuery(this).hide();"><?php echo __( 'Cometiste un error? Reportalo manualmente', 'woocommerce-kr4' ); ?></a> -->
		<div class="br4_p2c_form" style="xdisplay: none;">
			<p><b><?php echo __( 'Reporta tu Pago:', 'woocommerce-kr4' ); ?></b></p>
			<label for="br4_p2c_dni" class="pya-grid-item"><?php echo __( 'Cédula de la persona que paga', 'woocommerce-kr4' ); ?></label>
			<input value="<?php echo $_POST['br4_p2c_dni'] ?? ''; ?>" type="text" id="br4_p2c_dni" name="br4_p2c_dni" placeholder="17888999" class="pya-grid-item" />
			<label for="br4_p2c_phone" class="pya-grid-item"><?php echo __( 'Número de teléfono', 'woocommerce-kr4' ); ?></label>
			<input value="<?php echo $_POST['br4_p2c_phone'] ?? ''; ?>" type="text" id="br4_p2c_phone" name="br4_p2c_phone" placeholder="0412-1234567" class="pya-grid-item" />
			<label for="br4_p2c_reference" class="pya-grid-item"><?php echo __( 'Últimos 4 dígitos de su referencia', 'woocommerce-kr4' ); ?></label>
			<input value="<?php echo $_POST['br4_p2c_reference'] ?? ''; ?>" type="text" id="br4_p2c_reference" name="br4_p2c_reference" maxlength="9" placeholder="000000" class="pya-grid-item" />
		</div>
	</div>
	<table class="br4_p2c_form" style="xdisplay: none;"><tr>
		<td><input type="submit" name="br4_p2c_submit" id="br4_p2c_submit" class="button alt" value="Validar Pago" onclick="this.disabled=true;document.getElementById('br4_p2c_cancel').disabled=true;this.form.submit();" /></td>
		<td><input type="button" name="br4_p2c_cancel" id="br4_p2c_cancel" class="button" value="Cancelar" onclick="this.disabled=true;document.getElementById('br4_p2c_submit').disabled=true;document.location.href = '<?php echo esc_url( wc_get_checkout_url() ); ?>';" /></td>
	</tr></table>
</form>
<style>
	.payment_method_kr4_gateway p {
		margin: 0 !important;
	}
	.br4_p2c_qr_image_container {
		width: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: row;
	}
	.br4_p2c_qr_image {
		height: auto !important;
		margin: 0 auto !important;
		max-width: 290px !important;
		width: 100% !important;
		max-height: unset !important;
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

<script>
jQuery(document).ready(function($) {
    var orderId = <?php echo $order->get_id(); ?>;
    var checkOrderStatus = function() {
        $.ajax({
            url: wc_checkout_params.ajax_url,
            type: 'POST',
            data: {
                action: 'kr4_check_order_status',
                order_id: orderId,
            },
            success: function(response) {
                if (response.success && (response.data.status === 'processing' || response.data.status === 'completed')) {
                    window.location.href = response.data.redirect_url;
                }
            },
            error: function(xhr, status, error) {
                console.log('Error checking order status:', error);
                // Si hay un error de autenticación, intentamos refrescar la página
                if (xhr.status === 403) {
                    window.location.reload();
                }
            }
        });
    };

    setInterval(checkOrderStatus, 6000);
});
</script>
