<?php
class PersonalBridge_Theme_Shoptimizer extends PersonalBridge_Theme_Base {
	protected static $_instance = null;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function custom_hooks() {
		remove_action( 'woocommerce_before_single_product_summary', array( $this, 'woo_before_single_product_summary' ), 0 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'woo_before_single_product_summary' ), 6 );
		add_action( 'personalbridge_before_single_summary', array( $this, 'before_single_summary' ), 20 );
	}

	public function woo_before_single_product_summary() {
		if ( ! $this->is_pb() ) {
			return;
		}
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

		$wrapper_class = apply_filters( 'personalbridge_wrapper_class', 'nf-product-single' );
		$wrapper_id    = apply_filters( 'personalbridge_wrapper_id', 'personalbridge-product-section' );

		echo '<div class="' . esc_attr( $wrapper_class ) . ' woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 1; transition: opacity 0.25s ease-in-out 0s;" id="' . esc_attr( $wrapper_id ) . '">';
			echo '<div id="artwork-preview"></div>';
		do_action( 'personalbridge_before_single_summary' );
	}

	public function before_single_summary() {
		echo '</div>';
	}

}

function personalbridge_theme_shoptimizer() {
	return PersonalBridge_Theme_Shoptimizer::get_instance();
}
