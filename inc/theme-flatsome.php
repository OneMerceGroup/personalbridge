<?php
class PersonalBridge_Theme_Flatsome extends PersonalBridge_Theme_Base {
	protected static $_instance = null;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function custom_hooks() {
		// woocommerce_before_main_content.
		add_action( 'woocommerce_single_product_summary', array( $this, 'woo_single_product_summary_open' ), 0 );

		// woocommerce_after_main_content.
		add_action( 'woocommerce_single_product_summary', array( $this, 'woo_single_product_summary_close' ), PHP_INT_MAX );

		add_filter( 'personalbridge_wrapper_class', array( $this, 'modify_wrapper_class' ), 20, 1 );
		add_filter( 'personalbridge_wrapper_id', array( $this, 'modify_wrapper_id' ), 20, 1 );

		add_action( 'personalbridge_before_single_summary', array( $this, 'before_single_summary' ), 20 );
	}

	public function before_single_summary() {
		echo '</div>';
	}

	public function modify_wrapper_class( $class ) {
		$class = 'nf-product-single-gallery';
		return $class;
	}

	public function modify_wrapper_id( $id ) {
		$id = 'personalbridge-product-section-gallery';
		return $id;
	}

	public function woo_single_product_summary_open() {
		if ( $this->is_pb() ) {
			echo '<div class="nf-product-single" id="personalbridge-product-section">';
		}
	}

	public function woo_single_product_summary_close() {
		if ( $this->is_pb() ) {
			echo '</div>';
		}
	}

}

function personalbridge_theme_flatsome() {
	return PersonalBridge_Theme_Flatsome::get_instance();
}

