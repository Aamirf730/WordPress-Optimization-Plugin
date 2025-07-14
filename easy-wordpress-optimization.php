<?php
/*
Plugin Name: Easy WordPress Optimization
Description: Adds security headers, WordPress optimization features, and JavaScript delay loading to your website.
Version: 1.2
Author: <a href="https://wpgeared.com/">WPGeared</a>
*/

include('settings.php');

function add_security_headers() {
    $options = get_option('security_headers_settings');
    
    // Security headers
    if(isset($options['x_frame_options']) && $options['x_frame_options']) {
        header('X-Frame-Options: SAMEORIGIN');
    }
    
    if(isset($options['x_xss_protection']) && $options['x_xss_protection']) {
        header('X-XSS-Protection: 1; mode=block');
    }

    if(isset($options['x_content_type_options']) && $options['x_content_type_options']) {
        header('X-Content-Type-Options: nosniff');
    }

    if(isset($options['x_permitted_cross_domain_policies']) && $options['x_permitted_cross_domain_policies']) {
        header('X-Permitted-Cross-Domain-Policies: none');
    }

    if(isset($options['strict_transport_security']) && $options['strict_transport_security']) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }

    if(isset($options['content_security_policy']) && $options['content_security_policy']) {
        header("Content-Security-Policy: default-src 'self'");
    }

    if(isset($options['referrer_policy']) && $options['referrer_policy']) {
        header('Referrer-Policy: no-referrer');
    }

    if(isset($options['feature_policy']) && $options['feature_policy']) {
        header("Feature-Policy: microphone 'none'; geolocation 'none'");
    }

    if(isset($options['expect_ct']) && $options['expect_ct']) {
        header('Expect-CT: max-age=86400, enforce');
    }
}

function remove_bloats() {
    $options = get_option('remove_bloat_settings');
    
    // WordPress bloat removal
    if(isset($options['disable_emojis']) && $options['disable_emojis']) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
    }
    
    if(isset($options['disable_dashicons']) && $options['disable_dashicons']) {
        wp_deregister_style('dashicons');
    }

    if(isset($options['disable_embeds']) && $options['disable_embeds']) {
        wp_deregister_script('wp-embed');
    }

    if(isset($options['disable_xmlrpc']) && $options['disable_xmlrpc']) {
        add_filter('xmlrpc_enabled', '__return_false');
    }

    if(isset($options['remove_jquery_migrate']) && $options['remove_jquery_migrate']) {
        function remove_jquery_migrate_function($scripts) {
            $scripts->remove('jquery');
            $scripts->add('jquery', false, array('jquery-core'), '1.12.4');
        }
        add_action('wp_default_scripts', 'remove_jquery_migrate_function');
    }

    if(isset($options['remove_wlwmanifest_link']) && $options['remove_wlwmanifest_link']) {
        remove_action('wp_head', 'wlwmanifest_link');
    }

    if(isset($options['remove_rsd_link']) && $options['remove_rsd_link']) {
        remove_action('wp_head', 'rsd_link');
    }

    if(isset($options['remove_shortlink']) && $options['remove_shortlink']) {
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }

    if(isset($options['disable_self_pingbacks']) && $options['disable_self_pingbacks']) {
        function disable_self_pingbacks_function( &$links ) {
            foreach ( $links as $l => $link ) {
                if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
                    unset($links[$l]);
                }
            }
        }
        add_action('pre_ping', 'disable_self_pingbacks_function');
    }
}

function delay_js_loading() {
    $options = get_option('js_delay_settings');
    
    if(isset($options['enable_js_delay']) && $options['enable_js_delay']) {
        // Add JavaScript to handle delayed loading
        add_action('wp_footer', 'add_delay_js_script');
        
        // Modify script loading to add data attributes
        add_filter('script_loader_tag', 'add_delay_attributes_to_scripts', 10, 3);
    }
}

function add_delay_attributes_to_scripts($tag, $handle, $src) {
    $options = get_option('js_delay_settings');
    $delay_scripts = isset($options['delay_scripts']) ? $options['delay_scripts'] : array();
    
    // Check if this script should be delayed
    if (in_array($handle, $delay_scripts)) {
        // Add data attributes for delayed loading
        $tag = str_replace('<script ', '<script data-delay="true" ', $tag);
    }
    
    return $tag;
}

function add_delay_js_script() {
    $options = get_option('js_delay_settings');
    $delay_time = isset($options['delay_time']) ? intval($options['delay_time']) : 2000;
    $load_on_interaction = isset($options['load_on_interaction']) ? $options['load_on_interaction'] : false;
    
    ?>
    <script>
    (function() {
        'use strict';
        
        // Configuration
        const delayTime = <?php echo $delay_time; ?>;
        const loadOnInteraction = <?php echo $load_on_interaction ? 'true' : 'false'; ?>;
        
        // Store delayed scripts
        const delayedScripts = [];
        let scriptsLoaded = false;
        
        // Function to load delayed scripts
        function loadDelayedScripts() {
            if (scriptsLoaded) return;
            scriptsLoaded = true;
            
            delayedScripts.forEach(function(script) {
                const newScript = document.createElement('script');
                
                // Copy all attributes from original script
                Array.from(script.attributes).forEach(function(attr) {
                    if (attr.name !== 'data-delay') {
                        newScript.setAttribute(attr.name, attr.value);
                    }
                });
                
                // Replace the delayed script with the new one
                script.parentNode.replaceChild(newScript, script);
            });
        }
        
        // Function to handle user interaction
        function handleUserInteraction() {
            if (loadOnInteraction && !scriptsLoaded) {
                loadDelayedScripts();
                // Remove event listeners after loading
                document.removeEventListener('scroll', handleUserInteraction, { passive: true });
                document.removeEventListener('mousemove', handleUserInteraction, { passive: true });
                document.removeEventListener('click', handleUserInteraction, { passive: true });
                document.removeEventListener('keydown', handleUserInteraction, { passive: true });
            }
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDelay);
        } else {
            initDelay();
        }
        
        function initDelay() {
            // Find all scripts with data-delay attribute
            const scripts = document.querySelectorAll('script[data-delay="true"]');
            
            if (scripts.length === 0) return;
            
            // Store scripts for later loading
            scripts.forEach(function(script) {
                delayedScripts.push(script);
            });
            
            // Set up loading strategy
            if (loadOnInteraction) {
                // Load on first user interaction
                document.addEventListener('scroll', handleUserInteraction, { passive: true });
                document.addEventListener('mousemove', handleUserInteraction, { passive: true });
                document.addEventListener('click', handleUserInteraction, { passive: true });
                document.addEventListener('keydown', handleUserInteraction, { passive: true });
            } else {
                // Load after delay time
                setTimeout(loadDelayedScripts, delayTime);
            }
            
            // Fallback: load scripts after 5 seconds regardless
            setTimeout(function() {
                if (!scriptsLoaded) {
                    loadDelayedScripts();
                }
            }, 5000);
        }
    })();
    </script>
    <?php
}

add_action('send_headers', 'add_security_headers');
add_action('init', 'remove_bloats');
add_action('init', 'delay_js_loading');
