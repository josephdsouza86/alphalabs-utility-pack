<?php

namespace AlphaLabsUtilityPack\Admin;

class Customise {
    public function __construct() {
        add_action( 'admin_init', array( $this, 'disable_comments_post_types_support' ) );
        add_action( 'admin_menu', array( $this, 'disable_comments_admin_menu' ) );
        add_action( 'wp_before_admin_bar_render', array( $this, 'disable_comments_admin_bar_render' ) );

    }

    // Disable comments
    function disable_comments_post_types_support() {
        $post_types = get_post_types();
        foreach ( $post_types as $post_type ) {
            if ( post_type_supports( $post_type, 'comments' ) ) {
                remove_post_type_support( $post_type, 'comments' );
                remove_post_type_support( $post_type, 'trackbacks' );
            }
        }
    }

    // Remove comments from admin bar
    function disable_comments_admin_bar_render() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu( 'comments' );
    }

    // Remove comments from menu
    function disable_comments_admin_menu() {
        remove_menu_page( 'edit-comments.php' );
    }
}