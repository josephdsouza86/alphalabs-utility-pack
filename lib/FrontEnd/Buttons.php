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
                var buttons = document.querySelectorAll('a[href^="tel:"], a[href*="api.whatsapp.com"],a[href^="mailto"]');
                buttons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var href = button.getAttribute('href');
                        var eventLabel = '';

                        if (href.indexOf('tel:') !== -1) {
                            eventLabel = 'Phone';
                        } else if (href.indexOf('api.whatsapp.com') !== -1) {
                            eventLabel = 'WhatsApp';
                        } else if (href.indexOf('mailto') !== -1) {
                            eventLabel = 'Email';
                        }

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