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
 * Version:           1.0.0
 * Author:            Alpha Labs
 * Author URI:        https://alphalabs.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alphalabs-utility-pack
 * Domain Path:       /languages
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
define( 'ALPHALABS_UTILITY_PACK_VERSION', '1.0.0' );
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

	if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
		$config = array(
			'slug' => ALPHALABS_UTILITY_PACK_SLUG, // this is the slug of your plugin
			'proper_folder_name' => ALPHALABS_UTILITY_PACK_SLUG, // this is the name of the folder your plugin lives in
			'api_url' => 'https://api.github.com/repos/josephdsouza86/alphalabs-utility-pack', // the GitHub API url of your GitHub repo
			'raw_url' => 'https://raw.github.com/josephdsouza86/alphalabs-utility-pack/master', // the GitHub raw url of your GitHub repo
			'github_url' => 'https://github.com/josephdsouza86/alphalabs-utility-pack', // the GitHub url of your GitHub repo
			'zip_url' => 'https://github.com/josephdsouza86/alphalabs-utility-pack/zipball/master', // the zip url of the GitHub repo
			'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
			'requires' => '6.4', // which version of WordPress does your plugin require?
			'tested' => '6.4', // which version of WordPress is your plugin tested up to?
			'readme' => 'README.md', // which file to use as the readme for the version number
			'access_token' => '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', // Access private repositories by authorizing under Plugins > GitHub Updates when this example plugin is installed
		);
		new WP_GitHub_Updater($config);
	}

	$alphalabs_utility_pack->run();

}
run_alphalabs_utility_pack();

function is_utility_pack_available() {
	global $alphalabs_utility_pack;

	return isset( $alphalabs_utility_pack );
}