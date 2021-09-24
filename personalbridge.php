<?php
/**
 * Plugin Name: PersonalBridge
 * Description: Design, sell, and print personal products faster plugin for WooCommerce.
 * Version: 1.0.0
 * Text Domain: personalbridge
 * Domain Path: /languages
 * Author: PersonalBridge
 * Author URI: https://www.personalbridge.com
 * License: GPL2
 * Network: true
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
if ( ! defined( 'PERSONALBRIDGE_APIS' ) ) {
	define( 'PERSONALBRIDGE_APIS', 'https://apis.personalbridge.com/' );
}
if ( ! defined( 'PERSONALBRIDGE_THEME_VER' ) ) {
	define( 'PERSONALBRIDGE_THEME_VER', '1.0.0' );
}

if ( ! defined( 'PERSONALBRIDGE_URL' ) ) {
	define( 'PERSONALBRIDGE_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PERSONALBRIDGE_DIR' ) ) {
	define( 'PERSONALBRIDGE_DIR', plugin_dir_path( __FILE__ ) );
}

require_once PERSONALBRIDGE_DIR . 'inc/helper.php';
require_once PERSONALBRIDGE_DIR . 'inc/base.php';
require_once PERSONALBRIDGE_DIR . 'inc/theme-normal.php';
require_once PERSONALBRIDGE_DIR . 'inc/theme-flatsome.php';

class PersonalBridge {
	public function __construct() {
		add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'woo_after_add_to_cart_form' ), 10, 2 );
	}

}

new PersonalBridge();

