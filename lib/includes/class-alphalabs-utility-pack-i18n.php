<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://alphalabs.net
 * @since      1.0.0
 *
 * @package    Alphalabs_Utility_Pack
 * @subpackage Alphalabs_Utility_Pack/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Alphalabs_Utility_Pack
 * @subpackage Alphalabs_Utility_Pack/includes
 * @author     Alpha Labs <info@alphalabs.net>
 */
class Alphalabs_Utility_Pack_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'alphalabs-utility-pack',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
