<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://alphalabs.net
 * @since             1.0.0
 * @package           Alphalabs_Utility_Pack
 *
 * @wordpress-plugin
 * Plugin Name:       Utility Pack
 * Plugin URI:        https://alphalabs.net
 * Description:       This plugin contains common utility code and functionality that is often required to support projects developed by Alpha Labs.
 * Version:           1.0.2.3
 * Author:            Alpha Labs
 * Author URI:        https://alphalabs.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alphalabs-utility-pack
 * Domain Path:       /languages
 * 
 * GitHub Plugin URI: https://github.com/josephdsouza86/alphalabs-utility-pack
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ALPHALABS_UTILITY_PACK_VERSION', '1.0.2.3' );
define( 'ALPHALABS_UTILITY_PACK_SLUG', 'alphalabs-utility-pack' );
define( 'ALPHALABS_UTILITY_PACK_PLUGIN_PATH', dirname( __FILE__ ) . '/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-alphalabs-utility-pack-activator.php
 */
function activate_alphalabs_utility_pack() {
	require_once plugin_dir_path( __FILE__ ) . 'lib/includes/class-alphalabs-utility-pack-activator.php';
	Alphalabs_Utility_Pack_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-alphalabs-utility-pack-deactivator.php
 */
function deactivate_alphalabs_utility_pack() {
	require_once plugin_dir_path( __FILE__ ) . 'lib/includes/class-alphalabs-utility-pack-deactivator.php';
	Alphalabs_Utility_Pack_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_alphalabs_utility_pack' );
register_deactivation_hook( __FILE__, 'deactivate_alphalabs_utility_pack' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'lib/includes/class-alphalabs-utility-pack.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_alphalabs_utility_pack() {
	global $alphalabs_utility_pack;

	$alphalabs_utility_pack = new Alphalabs_Utility_Pack();
	$alphalabs_utility_pack->run();

}
run_alphalabs_utility_pack();

function is_utility_pack_available() {
	global $alphalabs_utility_pack;

	return isset( $alphalabs_utility_pack );
}
