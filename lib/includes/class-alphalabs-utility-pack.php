<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://alphalabs.net
 * @since      1.0.0
 *
 * @package    Alphalabs_Utility_Pack
 * @subpackage Alphalabs_Utility_Pack/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Alphalabs_Utility_Pack
 * @subpackage Alphalabs_Utility_Pack/includes
 * @author     Alpha Labs <info@alphalabs.net>
 */
class Alphalabs_Utility_Pack {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Alphalabs_Utility_Pack_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ALPHALABS_UTILITY_PACK_VERSION' ) ) {
			$this->version = ALPHALABS_UTILITY_PACK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'alphalabs-utility-pack';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Alphalabs_Utility_Pack_Loader. Orchestrates the hooks of the plugin.
	 * - Alphalabs_Utility_Pack_i18n. Defines internationalization functionality.
	 * - Alphalabs_Utility_Pack_Admin. Defines all hooks for the admin area.
	 * - Alphalabs_Utility_Pack_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Allow our local classes to be automatically included when requested
		 */
		$this->init_local_auto_loader();

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once ALPHALABS_UTILITY_PACK_PLUGIN_PATH . 'lib/includes/class-alphalabs-utility-pack-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once ALPHALABS_UTILITY_PACK_PLUGIN_PATH . 'lib/includes/class-alphalabs-utility-pack-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once ALPHALABS_UTILITY_PACK_PLUGIN_PATH . 'admin/class-alphalabs-utility-pack-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once ALPHALABS_UTILITY_PACK_PLUGIN_PATH . 'public/class-alphalabs-utility-pack-public.php';

		$this->loader = new Alphalabs_Utility_Pack_Loader();

		$this->init_global_load();
	}

	private function init_global_load () { 
		new \AlphaLabsUtilityPack\Compatability\Astra();
		new \AlphaLabsUtilityPack\Compatability\GeneratePress();
	}

	/**
	 * PS4 loader which searches for class files by namespace and class structure
	 */
	private function init_local_auto_loader () {
		// Auto Loader
		include ALPHALABS_UTILITY_PACK_PLUGIN_PATH . '/lib/Psr4AutoLoader.php';
		$loader = new \AlphaLabsUtilityPack\Psr4Autoloader();
		$loader->register();
		$loader->addNamespace( 'AlphaLabsUtilityPack', ALPHALABS_UTILITY_PACK_PLUGIN_PATH . '/lib' );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Alphalabs_Utility_Pack_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Alphalabs_Utility_Pack_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Alphalabs_Utility_Pack_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'init_customiser');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Alphalabs_Utility_Pack_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Alphalabs_Utility_Pack_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
