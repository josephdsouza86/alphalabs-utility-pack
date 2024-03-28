<?php

namespace AlphaLabsUtilityPack\Admin;

class Customise {
    public function __construct() {
        add_action ( 'admin_menu', array($this, 'add_reusable_blocks_admin_menu_item') );        
    }

    function add_reusable_blocks_admin_menu_item() {
        add_theme_page( 
            'Reusable Blocks', // Page title
            'Reusable Blocks', // Menu title
            'edit_posts',      // Capability required to see the link
            'edit.php?post_type=wp_block' // The 'slug' - file to display when clicking the link
        );
    }
}