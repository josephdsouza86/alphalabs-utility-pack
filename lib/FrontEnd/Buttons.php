<?php
namespace AlphaLabsUtilityPack\FrontEnd;

class Buttons {
    public function __construct() {
        add_action( 'wp_footer', array( $this, 'add_custom_button_tracking_script' ) );
    }

    /**
     * Add custom tracking script for buttons
     */
    function add_custom_button_tracking_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof gtag === 'function') {
                var buttons = document.querySelectorAll('a[href^="tel:"], a[href*="api.whatsapp.com"]');
                buttons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var href = button.getAttribute('href');
                        var eventLabel = href.startsWith('tel:') ? 'Phone' : 'WhatsApp';
                        gtag('event', eventLabel.toLowerCase() + '_click', {
                            'event_category': eventLabel + ' Engagement',
                            'event_action': 'Click',
                            'event_label': eventLabel + ' Button Click'
                        });
                    });
                });
            }
        });
        </script>
        <?php
}
}