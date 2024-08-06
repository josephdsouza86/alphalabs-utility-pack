<?php

namespace AlphaLabsUtilityPack\Compatability;

class GeneratePress {
    public function __construct() {
        add_filter( 'generate_element_display', array( $this, 'generate_element_display' ), 10, 2 );
    }
    
    /**
     * Enqueue Spectra CSS assets within Post Elements. This is a compatibility fix 
     * for GeneratePress Premium.
     */
    function generate_element_display( $display, $element_id ) {
        if ( class_exists( 'UAGB_Post_Assets' ) && $display ) {
            // Create Instance. Pass the Post ID.
            $post_assets_instance = new UAGB_Post_Assets( $element_id );

            // Enqueue the Assets.
            $post_assets_instance->enqueue_scripts();
        }

        return $display;
    }
}