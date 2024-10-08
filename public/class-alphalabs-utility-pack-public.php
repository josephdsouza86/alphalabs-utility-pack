<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://alphalabs.net
 * @since      1.0.0
 *
 * @package    Alphalabs_Utility_Pack
 * @subpackage Alphalabs_Utility_Pack/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Alphalabs_Utility_Pack
 * @subpackage Alphalabs_Utility_Pack/public
 * @author     Alpha Labs <info@alphalabs.net>
 */
class Alphalabs_Utility_Pack_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		new  \AlphaLabsUtilityPack\FrontEnd\Buttons();		
		new  \AlphaLabsUtilityPack\FrontEnd\Cookies();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Alphalabs_Utility_Pack_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Alphalabs_Utility_Pack_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/alphalabs-utility-pack-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Alphalabs_Utility_Pack_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Alphalabs_Utility_Pack_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/alphalabs-utility-pack-public.js', array( 'jquery' ), $this->version, true );

	}

}
