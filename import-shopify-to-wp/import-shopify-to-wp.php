<?php
/**
 * Plugin Name: Import Shopify To WP
 * Version: 1.1.0
 * Author: WPBeginner
 * Description: Easily transfer your Shopify Store to WooCommerce
 * Plugin URI: https://shopifytowp.com/
 * Requires PHP: 7.4
 * Requires at least: 6.2
 * WC requires at least: 8.0
 * WC tested up to: 11.1
 * Text Domain: import-shopify-to-wp
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

use S2WPImporter\AdminNotice;
use S2WPImporter\AdminPage;
use S2WPImporter\Process\Importer;
use S2WPImporter\Plugins\Installer as PluginsInstaller;

if (!defined('ABSPATH')) {
    exit;
}

// Constants
// ----------------------------------------------------------------------------
define('S2WP_IMPORTER_VERSION', '1.1.0');
define('S2WP_IMPORTER_DIR', plugin_dir_path(__FILE__));
define('S2WP_IMPORTER_URI', plugin_dir_url(__FILE__));

// Activation hook: Create custom tables
// ----------------------------------------------------------------------------
register_activation_hook(__FILE__, function () {
    (new \S2WPImporter\VariationsLog())->createTable();
});

// WooCommerce HPOS (custom order tables) compatibility
// ----------------------------------------------------------------------------
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

// Includes
// ----------------------------------------------------------------------------
require_once S2WP_IMPORTER_DIR . 'vendor/autoload.php';

add_action('plugins_loaded', function () {
    (new AdminNotice())->init();
    (new AdminPage())->init();
    (new Importer())->init();
    (new PluginsInstaller())->init();
});
