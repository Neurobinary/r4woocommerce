<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class WC_Gateway_KR4_Blocks extends AbstractPaymentMethodType
{
    private $gateway;
    protected $name;
    protected $settings;

    public function __construct(WC_Payment_Gateway $gateway)
    {
        $this->gateway = $gateway;
        $this->name    = $gateway->id;
    }

    public function initialize()
    {
        $this->settings = get_option('woocommerce_'.$this->gateway->id.'_settings', []);
        $this->name = $this->gateway->method_title;
    }

    public function is_active()
    {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles()
    {
        wp_register_script(
            'kr4-payment-blocks-integration',
            plugin_dir_url(__FILE__) . '/checkout-blocks.js',
            [
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
                'wp-html-entities',
            ],
            KR4::VERSION,
            true
        );

        return ['kr4-payment-blocks-integration'];
    }

    public function get_payment_method_data()
    {
        return [
            'id'          => $this->gateway->id,
            'title'       => $this->gateway->title,
            'description' => $this->gateway->description,
            'supports'    => array_filter($this->gateway->supports, [$this->gateway, 'supports']),
            'icon'           => $this->gateway->icon,
        ];
    }
}