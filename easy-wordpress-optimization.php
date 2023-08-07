<?php
/*
Plugin Name: Easy WordPress Optimization
Description: Adds security headers and WordPress optimization features to your website.
Version: 1.1
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

add_action('send_headers', 'add_security_headers');
add_action('init', 'remove_bloats');
