<?php
class PersonalBridge_Helper {
	protected static $_instance = null;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function is_flatsome() {
		if ( function_exists( 'flatsome_option' ) ) {
			return true;
		}

		$theme_slug = 'flatsome';
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

	public function is_shoptimizer() {
		if ( defined( 'SHOPTIMIZER_VERSION' ) ) {
			return true;
		}
		$theme_slug = 'shoptimizer';
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

function personalbridge_helper() {
	return PersonalBridge_Helper::get_instance();
}

