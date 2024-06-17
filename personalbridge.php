<?php
/**
 * Plugin Name: PersonalBridge
 * Description: Design, sell, and print personal products faster plugin for WooCommerce.
 * Version: 1.0.6
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
	define( 'PERSONALBRIDGE_THEME_VER', '1.0.6' );
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
require_once PERSONALBRIDGE_DIR . 'inc/theme-shoptimizer.php';

class PersonalBridge {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ), 10 );
	}

	public function init() {

		if ( personalbridge_helper()->is_flatsome() ) {
			personalbridge_theme_flatsome();
		} else if ( personalbridge_helper()->is_shoptimizer() ) {
			personalbridge_theme_shoptimizer();
		} else {
			personalbridge_theme_normal();
		}
	}

}

new PersonalBridge();
