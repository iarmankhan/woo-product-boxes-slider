<?php

/**
 * Plugin Name:       Products Boxes & Slider for Woocommerce
 * Plugin URI:        https://zypacinfotech.com/wp/woo-products
 * Description:       Products Boxes & Slider for Woocommerce
 * Version:           1.0.0
 * Author:            Zypac
 * Author URI:        https://zypacinfotech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-box-slider
 */

if (!defined('ABSPATH'))
    exit;

if (!class_exists('WOO_BOXES_SLIDER')) {

    class WOO_BOXES_SLIDER
    {
        private static $instance;

        public function init()
        {
            $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
            if (!stripos(implode($all_plugins), 'woocommerce.php')) {
                add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
                return;
            }
        }

        public function admin_notice_missing_main_plugin()
        {

            if (isset($_GET['activate'])) unset($_GET['activate']);

            $message = sprintf(
                esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'woo-box-slider'),
                '<strong>' . esc_html__('Products Boxes & Slider for Woocommerce', 'woo-box-slider') . '</strong>',
                '<strong>' . esc_html__('WooCommerce', 'woo-box-slider') . '</strong>'
            );

            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
        }

        function includes()
        {
            include_once('includes/woo-product-box.php');
            include_once('includes/woo-product-caption-slider.php');
            include_once('includes/woo-product-card-slider.php');
            include_once('includes/woo-product-filter-boxes.php');
            include_once('includes/woo-product-slider.php');
        }


        function woo_enqueue_scripts_styles()
        {
            wp_enqueue_style('uikit-css', plugins_url('css/uikit.min.css', __FILE__));
            wp_enqueue_style('woo-custom-css', plugins_url('css/woo-custom.css', __FILE__));
            wp_enqueue_script('uikit_js', plugins_url('js/uikit.min.js', __FILE__), array(), '1.0.0');
            wp_enqueue_script('uikit_icons_js', plugins_url('js/uikit-icons.min.js', __FILE__), array(), '1.0.0');
        }

        public static function instance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->includes();
                self::$instance->woo_enqueue_scripts_styles();
                self::$instance->init();
            }
            return self::$instance;
        }
    }

    add_action('plugins_loaded', array('WOO_BOXES_SLIDER', 'instance'));
}

