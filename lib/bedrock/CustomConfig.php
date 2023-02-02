<?php

namespace AlphaLabsUtilityPack\Bedrock;

use Roots\WPConfig\Config;
use function Env\env;

class CustomConfig {
    private $custom_env_keys = [
        'AL4_DEBUG_IP_ADDRESS',
        'AL4_AS3CF_KEY',
        'AL4_AS3CF_SECRET'
    ];

	public function __construct() {
        // Define WP hooks
    }

    public function load () {
        // Load custom env settings
        $this->load_custom_env();

        // Load config settings
        $this->remote_debug();
        $this->enable_s3();
    }

    /**
     * Load up custom env key names and values
     */
    private function load_custom_env () {
        foreach ($this->custom_env_keys as $key) {            
            Config::define( $key, env( $key ) );            
        }
    }

    /**
     * Check the environment settings to see if we have remote debugging enabled
     */
    protected function remote_debug () {
        if ( Config::get('AL4_DEBUG_IP_ADDRESS') && $_SERVER['REMOTE_ADDR'] == Config::get('AL4_DEBUG_IP_ADDRESS') ) {            
            // If defined and matching the current remote address, enable
            Config::define('WP_DEBUG_DISPLAY', true);
            Config::define('WP_DEBUG_LOG', true);
            Config::define('SCRIPT_DEBUG', true);
            ini_set('display_errors', '1');
        }
    }

    /**
     * Add WP OffLoad S3 Bucket Settings (Digital Ocean)
     */
    protected function enable_s3() {
        if ( Config::get( 'AL4_AS3CF_KEY' ) && Config::get( 'AL4_AS3CF_SECRET' ) ) {
            Config::define( 'AS3CF_SETTINGS', serialize( array(
                'provider' => 'do',
                'access-key-id' => Config::get( 'AL4_AS3CF_KEY' ),
                'secret-access-key' => Config::get( 'AL4_AS3CF_SECRET' ),
            ) ) );
        }
    }
}