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

class PersonalBridge {
	public function __construct() {
		add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'woo_after_add_to_cart_form' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_filter( 'woocommerce_get_price_html', array( $this, 'woo_price_html' ), 20, 1 );
		add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'before_add_to_cart' ) );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'woo_before_single_product_summary' ), 0 );
		add_action( 'woocommerce_product_meta_end', array( $this, 'woo_product_meta_end' ), 11 );
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'woo_add_cart_item_data' ), 25, 2 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'woo_get_item_data' ), 25, 2 );
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'woo_add_order_item_meta' ), 10, 2 );

		// woocommerce_before_main_content.
		add_action( 'woocommerce_single_product_summary', array( $this, 'woo_single_product_summary_open' ), 0 );

		// woocommerce_after_main_content.
		add_action( 'woocommerce_single_product_summary', array( $this, 'woo_single_product_summary_close' ), PHP_INT_MAX );
	}

	public function woo_single_product_summary_open() {
		if ( $this->is_flatsome() && $this->is_pb() ) {
			echo '<div class="nf-product-single" id="personalbridge-product-section">';
		}
	}

	public function woo_single_product_summary_close() {
		if ( $this->is_flatsome() && $this->is_pb() ) {
			echo '</div>';
		}
	}

	function woo_add_order_item_meta( $item_id, $values ) {
		if ( isset( $values['_personalbridgecustomid'] ) && ! empty( $values['_personalbridgecustomid'] ) ) {
			wc_add_order_item_meta( $item_id, '_personalbridgecustomid', $values['_personalbridgecustomid'] );
		}
		if ( isset( $values['_personalbridge_preview'] ) && ! empty( $values['_personalbridge_preview'] ) ) {
			wc_add_order_item_meta( $item_id, '_personalbridge_preview', $values['_personalbridge_preview'] );
		}
	}

	function woo_get_item_data( $other_data, $cart_item ) {
		if ( isset( $cart_item ['_personalbridge_preview'] ) && ! empty( $cart_item ['_personalbridge_preview'] ) ) {
			$other_data[] = array(
				'name'    => 'Preview',
				'display' => '<img src="' . esc_url( $cart_item['_personalbridge_preview'] ) . '"/>',
			);
		}
		return $other_data;
	}

	public function woo_add_cart_item_data( $cart_item_meta, $product_id ) {
		if ( isset( $_POST['properties'] ) && is_array( $_POST['properties'] ) ) {
			if ( isset( $_POST['properties']['_personalbridgecustomid'] ) && ! empty( $_POST['properties']['_personalbridgecustomid'] ) ) {
				$customid                                  = sanitize_key( wp_unslash( $_POST['properties']['_personalbridgecustomid'] ) );
				$cart_item_meta['_personalbridgecustomid'] = stripslashes( $customid );
			}
			if ( isset( $_POST['properties']['_personalbridge_preview'] ) && ! empty( $_POST['properties']['_personalbridge_preview'] ) ) {
				$preview                                   = esc_url_raw( wp_unslash( $_POST['properties']['_personalbridge_preview'] ) );
				$cart_item_meta['_personalbridge_preview'] = stripslashes( $preview );
			}
		}
		return $cart_item_meta;
	}

	public function woo_product_meta_end() {
		if ( ! $this->is_pb() ) {
			return;
		}
		echo '</div>';
	}

	public function woo_before_single_product_summary() {
		if ( ! $this->is_pb() ) {
			return;
		}
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

		$wrapper_class = 'nf-product-single';
		$wrapper_id    = 'personalbridge-product-section';
		if ( $this->is_flatsome() ) {
			$wrapper_class = 'nf-product-single-gallery';
			$wrapper_id    = 'personalbridge-product-section-gallery';
		}

		echo '<div class="' . esc_attr( $wrapper_class ) . '" id="' . esc_attr( $wrapper_id ) . '">';
		echo '<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images">
					<div id="artwork-preview">
					</div>
			  </div>';
		if ( $this->is_flatsome() ) { // is using flatsome theme.
			echo '</div>';
		}
	}

	public function before_add_to_cart() {
		if ( ! $this->is_pb() ) {
			return;
		}
		echo '<div class="personalbridge-atc-form-wrapper"><div style="display:none">';
	}

	public function woo_price_html( $price ) {
		if ( $this->is_pb() ) {
			return;
		}
		return $price;
	}

	public function wp_enqueue_scripts() {
		if ( function_exists( 'is_product' ) && is_product() ) {
			wp_enqueue_style( 'personalbridge-style', PERSONALBRIDGE_APIS . 'style.css', array(), PERSONALBRIDGE_THEME_VER );
			wp_enqueue_style( 'personalbridge-frontstore', PERSONALBRIDGE_URL . 'assets/css/style.css', array(), PERSONALBRIDGE_THEME_VER );

			if ( get_the_ID() > 0 ) {
				$site_url = get_site_url();
				$site_url = str_replace( array( 'http://', 'https://' ), '', $site_url );
				wp_enqueue_script( 'personalbridge-script', PERSONALBRIDGE_APIS . $site_url . '/' . get_the_ID(), array(), PERSONALBRIDGE_THEME_VER, true );

				if ( function_exists( 'get_woocommerce_currency' ) ) {
					$price_html          = wc_price( 0 );
					$symbols             = get_woocommerce_currency_symbols();
					$code                = get_woocommerce_currency();
					$symbol_code         = '';
					$replace_symbol_back = false;
					if ( is_array( $symbols ) && ! empty( $code ) && isset( $symbols[ $code ] ) ) {
						$symbol_code = $symbols[ $code ];
						if ( false !== strpos( $price_html, $symbol_code ) ) {
							$price_html          = str_replace( $symbol_code, '{{symbol_code}}', $price_html );
							$replace_symbol_back = true;
						}
					}

					$money_format = preg_replace( '/([0-9|.|,]+)/', '{{amount}}', $price_html );
					if ( $replace_symbol_back ) {
						$money_format = str_replace( '{{symbol_code}}', $symbol_code, $money_format );
					}
					$js_code .= 'window.money_format=\'' . $money_format . '\';';
					wp_add_inline_script( 'personalbridge-script', $js_code );
				}
			}
		}
	}

	public function woo_after_add_to_cart_form() {
		if ( ! $this->is_pb() ) {
			return;
		}
		echo '</div>';
		echo '<div id="personalized-form"></div>';
		echo '</div>';
	}

	public function is_pb() {
		if ( class_exists( 'WC_Product' ) ) {
			global $product;
			if ( is_object( $product ) && method_exists( $product, 'get_meta' ) ) {
				$pb_meta = $product->get_meta( 'personalbridge' );
				if ( ! empty( $pb_meta ) ) {
					return true;
				}
			}
		}
		return false;
	}

	public function is_flatsome() {
		$theme_slug = 'flatsome';
		if ( function_exists( 'flatsome_option' ) ) {
			return true;
		}

		if ( false !== strpos( get_option( 'template' ), $theme_slug ) ) {
			return true;
		}

		$theme = wp_get_theme();
		if ( is_object( $theme ) && property_exists( $theme, 'name' ) ) {
			$theme_name        = strtolower( $theme->name );
			$theme_parent_name = strtolower( $theme->parent_theme );
			if ( false !== strpos( $theme_name, $theme_slug ) || false !== strpos( $theme_parent_name, $theme_slug ) ) {
				return true;
			}
		}
		return false;
	}
}

new PersonalBridge();

