<?php
namespace AlphaLabsUtilityPack\FrontEnd;

class Buttons {
    public function __construct() {
        add_filter( 'render_block', array( $this, 'special_actions_inject_gtag' ), 10, 2 );
    }

    function special_actions_inject_gtag( $block_content, $block ) {
        // Check if the block is a GenerateBlocks button, Spectra button, or Core WordPress button
        $is_generateblocks_button = isset( $block['blockName'] ) && strpos( $block['blockName'], 'generateblocks/button' ) !== false;
        $is_spectra_button = isset( $block['blockName'] ) && $block['blockName'] === 'uagb/buttons-child';
        $is_wp_button = isset( $block['blockName'] ) && $block['blockName'] === 'core/button';
    
        // Only proceed if it is a button from GenerateBlocks, Spectra, or WordPress core
        if ( $is_generateblocks_button || $is_spectra_button || $is_wp_button ) {
            // Check for existing onclick and href attributes within the block content
            if ( strpos( $block_content, 'onclick="' ) === false && 
                 ( strpos( $block_content, 'tel:' ) !== false || strpos( $block_content, 'api.whatsapp.com' ) !== false ) ) {
                
                $event = strpos( $block_content, 'tel:' ) !== false ? 'Phone' : 'WhatsApp';
    
                // Inject the onclick event into the button
                $block_content = str_replace( '<a', "<a onclick=\"gtag('event', 'click', {'event_category': 'Button', 'event_label': '{$event} Click'});\"", $block_content );
            }
        }
    
        return $block_content;
    }    
}