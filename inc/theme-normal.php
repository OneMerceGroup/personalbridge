<?php
class PersonalBridge_Theme_Normal extends PersonalBridge_Theme_Base {
	protected static $_instance = null;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function custom_hooks() {

	}
}

function personalbridge_theme_normal() {
	return PersonalBridge_Theme_Normal::get_instance();
}

