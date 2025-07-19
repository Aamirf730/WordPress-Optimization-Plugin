<?php
/*
Plugin Name: Easy WordPress Optimization
Plugin URI: https://wpgeared.com/
Description: Adds security headers, WordPress optimization features, and JavaScript delay loading to your website. Features include dual-mode JS delay loading (selective or delay-all), comprehensive security headers, and bloat removal options.
Version: 1.2
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Author: WPGeared
Author URI: https://wpgeared.com/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: easy-wordpress-optimization
Domain Path: /languages
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EWO_PLUGIN_VERSION', '1.2');
define('EWO_PLUGIN_FILE', __FILE__);
define('EWO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EWO_PLUGIN_URL', plugin_dir_url(__FILE__));

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
    $delay_mode = isset($options['delay_mode']) ? $options['delay_mode'] : 'selective';
    
    if ($delay_mode === 'selective') {
        // Selective mode: only delay specified scripts
        $delay_scripts = isset($options['delay_scripts']) ? $options['delay_scripts'] : array();
        $custom_delay_scripts_text = isset($options['custom_delay_scripts']) ? $options['custom_delay_scripts'] : '';
        
        // Add custom scripts to the delay list
        if (!empty($custom_delay_scripts_text)) {
            $custom_delay_scripts = array_map('trim', explode(',', $custom_delay_scripts_text));
            $delay_scripts = array_merge($delay_scripts, $custom_delay_scripts);
        }
        
        // Check if script should be delayed by handle
        if (in_array($handle, $delay_scripts)) {
            $tag = str_replace('<script ', '<script data-delay="true" ', $tag);
        }
        
        // Check if script should be delayed by src path
        foreach ($delay_scripts as $delay_script) {
            if (strpos($delay_script, '/') !== false && strpos($src, $delay_script) !== false) {
                $tag = str_replace('<script ', '<script data-delay="true" ', $tag);
                break;
            }
        }
    } else {
        // All mode: delay all scripts except excluded ones
        $exclude_scripts_text = isset($options['exclude_scripts']) ? $options['exclude_scripts'] : '';
        $exclude_scripts = array();
        
        if (!empty($exclude_scripts_text)) {
            $exclude_scripts = array_map('trim', explode(',', $exclude_scripts_text));
        }
        
        // Check if script should be excluded by handle
        if (in_array($handle, $exclude_scripts)) {
            return $tag; // Don't delay this script
        }
        
        // Check if script should be excluded by src path
        foreach ($exclude_scripts as $exclude) {
            if (strpos($exclude, '/') !== false && strpos($src, $exclude) !== false) {
                return $tag; // Don't delay this script
            }
        }
        
        // Delay this script
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

// Plugin activation hook
register_activation_hook(__FILE__, 'ewo_activate_plugin');

function ewo_activate_plugin() {
    // Set default options if they don't exist
    if (!get_option('js_delay_settings')) {
        $default_js_settings = array(
            'enable_js_delay' => 0,
            'delay_mode' => 'selective',
            'delay_time' => 2000,
            'load_on_interaction' => 0,
            'delay_scripts' => array(),
            'custom_delay_scripts' => '',
            'exclude_scripts' => ''
        );
        add_option('js_delay_settings', $default_js_settings);
    }
    
    if (!get_option('security_headers_settings')) {
        $default_security_settings = array(
            'x_frame_options' => 1,
            'x_xss_protection' => 1,
            'x_content_type_options' => 1,
            'x_permitted_cross_domain_policies' => 0,
            'strict_transport_security' => 0,
            'content_security_policy' => 0,
            'referrer_policy' => 0,
            'feature_policy' => 0,
            'expect_ct' => 0
        );
        add_option('security_headers_settings', $default_security_settings);
    }
    
    if (!get_option('remove_bloat_settings')) {
        $default_bloat_settings = array(
            'disable_emojis' => 0,
            'disable_dashicons' => 0,
            'disable_embeds' => 0,
            'disable_xmlrpc' => 0,
            'remove_jquery_migrate' => 0,
            'remove_wlwmanifest_link' => 0,
            'remove_rsd_link' => 0,
            'remove_shortlink' => 0,
            'disable_self_pingbacks' => 0
        );
        add_option('remove_bloat_settings', $default_bloat_settings);
    }
}

add_action('send_headers', 'add_security_headers');
add_action('init', 'remove_bloats');
add_action('init', 'delay_js_loading');
