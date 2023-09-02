<?php
/*
Plugin Name: NopCommerce To WooComerce plugin
Version: 1.0.0
Author: Ali Gharachorloo
*/

if ( ! defined( 'NTW_PLUGIN_DIR' ) ) {
    define( 'NTW_PLUGIN_FILE', __FILE__ );
    define( 'NTW_PLUGIN_DIR', untrailingslashit( dirname( NTW_PLUGIN_FILE ) ) );
}

require __DIR__ . '/includes/main.php';

