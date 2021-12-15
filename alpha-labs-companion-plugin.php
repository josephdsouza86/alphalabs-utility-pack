<?php
/**
 * @package Alpha Labs Companion Plugin
 * @version 1.0.1
 */
/*
GitHub Plugin URI: josephdsouza86/alphalabs-companion-plugin-prototype
Plugin Name: Alpha Labs companion plugin (prototype)
Plugin URI: https://alphalabs.net/
Description: This is a prototype plugin to store common utility code that is often required in projects developed by Alpha Labs. The prototype version is a place where code can be quickly added, but should eventually be promoted to the formal plugin at a later date.
Author: Joseph D'Souza
Version: 1.0.1
Author URI: https://alphalabs.net/
*/

/**
 * As theme development is done outside of the distribution folder we will include the 
 * source location as a theme path, so that we can develop and debug as usual without
 * a complex Gulp task syncing theme folders.
 */
function use_development_theme_location () {

}

/**
 * Similar to the above theme function, this would point to the development theme folder
 * however, it may not work as we would be operating two plugin folders, one populated 
 * in the distribution folder by composer and then the local development folder. Might
 * just be best to leave this one for now
 */
function use_development_plugins_location () {

}

/**
 * Make it possible to move the uploads folder out of the webroot so that it cannot be
 * overwritten or interferred with by the installation. We also will have the added
 * advantage of being able to reference the live uploads folder from staging copies.
 * Plus, there is a security benefit to accessing uploaded files via a PHP distribution
 * end point, rather than directly from the uploads folder.
 */
function use_external_uploads_folder () {

}
