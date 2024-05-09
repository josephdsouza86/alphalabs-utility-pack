<?php
namespace AlphaLabsUtilityPack\FrontEnd;

class Cookies {
    public function __construct() {
        add_action('wp_footer', array($this, 'wt_cli_delay_cookie_banner'), 10);
    }

    function wt_cli_delay_cookie_banner()
    {
        if (class_exists('\CookieYes\Lite\Autoloader')) {
        ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Function to handle the visibility of CKY elements
                function handleCkyElements(nodes) {
                    nodes.forEach(node => {
                        if (jQuery(node).is(':visible')) {
                            jQuery(node).hide(); // Hide the node immediately
                            
                            setTimeout(() => {
                                jQuery(node).fadeIn(); // Fade in after a delay
                            }, 3000); // Delay time of 3 seconds
                        }
                    });
                }
    
                // Mutation observer callback
                function mutationCallback(mutations) {
                    mutations.forEach(mutation => {
                        let affectedNodes = [];
                        mutation.addedNodes.forEach(node => {
                            // Check if the node is an element and has a class starting with 'cky-'
                            if (node.nodeType === Node.ELEMENT_NODE && node.className.includes('cky-')) {
                                affectedNodes.push(node);
                            }
                        });
                        if (affectedNodes.length > 0) {
                            handleCkyElements(affectedNodes);
                            observer.disconnect();
                        }
                    });
                }
    
                // Create a new observer
                const observer = new MutationObserver(mutationCallback);
    
                // Configuration: observe direct children additions/removals, no subtree
                const config = {
                    childList: true,
                    subtree: false // Not observing descendants, only direct children of the body
                };
    
                // Start observing the body for changes in children
                observer.observe(document.body, config);
            });
    
            </script>
    <?php
        }
    }
}