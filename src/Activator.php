<?php

namespace P4\MasterTheme;

/**
 * Class Activator.
 * The main class that has activation/deactivation hooks for planet4 master-theme.
 */
class Activator {

	/**
	 * Activator constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks the activator functions.
	 */
	protected function hooks(): void {
		add_action( 'after_switch_theme', [ self::class, 'run' ] );
	}

	/**
	 * Run activation functions.
	 */
	public static function run(): void {
		Campaigner::register_role_and_add_capabilities();
		Migrator::migrate();

		// Temporary PWA sample code
		$theme_dir = get_template_directory_uri();
		$public_dir = $theme_dir . '/../../../';
		copy($theme_dir . '/static/sw.js', $public_dir . '/sw.js')l
	}

}
