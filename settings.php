<?php

// Add the menu page for the plugin settings
function ewo_menu() {
    add_options_page('Easy WordPress Optimization', 'WP Optimization', 'manage_options', 'easy-wordpress-optimization', 'ewo_options_page');
}
add_action('admin_menu', 'ewo_menu');

// Register and display the settings for the plugin
function ewo_settings_init() {
    register_setting('ewoPluginPage', 'security_headers_settings');
    register_setting('ewoPluginPage', 'remove_bloat_settings');

    // Security headers settings
    add_settings_section('security_headers_section', '', 'security_headers_section_callback', 'securityHeadersPage');

    $headers = [
        'x_frame_options' => 'X Frame Options',
        'x_xss_protection' => 'X-XSS-Protection',
        'x_content_type_options' => 'X Content Type Options',
        'x_permitted_cross_domain_policies' => 'X Permitted Cross Domain Policies',
        'strict_transport_security' => 'Strict Transport Security',
        'content_security_policy' => 'Content Security Policy',
        'referrer_policy' => 'Referrer Policy',
        'feature_policy' => 'Feature Policy',
        'expect_ct' => 'Expect CT'
    ];

    foreach ($headers as $key => $label) {
        add_settings_field($key, $label, create_render_function($key, 'security_headers_settings'), 'securityHeadersPage', 'security_headers_section');
    }

    // Bloat removal settings
    add_settings_section('remove_bloat_section', '', 'remove_bloat_section_callback', 'removeBloatPage');

    $bloats = [
        'disable_emojis' => 'Disable Emojis',
        'disable_embeds' => 'Disable Embeds',
        'disable_xmlrpc' => 'Disable XML-RPC',
        'remove_jquery_migrate' => 'Remove jQuery Migrate',
        'remove_wlwmanifest_link' => 'Remove wlwmanifest Link',
        'remove_rsd_link' => 'Remove RSD Link',
        'remove_shortlink' => 'Remove Shortlink',
        'disable_self_pingbacks' => 'Disable Self Pingbacks'
    ];

    foreach ($bloats as $key => $label) {
        add_settings_field($key, $label, create_render_function($key, 'remove_bloat_settings'), 'removeBloatPage', 'remove_bloat_section');
    }
}

function create_render_function($name, $option_name) {
    return function() use ($name, $option_name) {
        $options = get_option($option_name);
        $isChecked = isset($options[$name]) && $options[$name] == 1;
        ?>
        <label class="toggle-switch">
            <input type='checkbox' name='<?php echo $option_name; ?>[<?php echo $name; ?>]' <?php checked($isChecked, true); ?> value='1'>
            <span class="slider"></span>
        </label>
        <?php
    };
}

function security_headers_section_callback() {
    echo __('Configure which security headers to enable:', 'wordpress');
}

function remove_bloat_section_callback() {
    echo __('Choose the WordPress bloat you want to remove:', 'wordpress');
}

function ewo_options_page() {
    ?>
    <style>
        .wrap {
            background-color: white;
            padding: 30px 20px 20px 20px;
            margin: 0px 0px 0px -17px;
        }

        .wrap h1 {
            color: green;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .nav-tab-wrapper {
            margin-bottom: 20px;
        }
    </style>

    <div class="wrap">
        <h1>Easy WordPress Optimization</h1>
        <form action='options.php' method='post'>
            <?php
            settings_fields('ewoPluginPage');
            
            echo '<h2 class="nav-tab-wrapper">';
            echo '<a href="?page=easy-wordpress-optimization&tab=securityHeaders" class="nav-tab ' . ($_GET['tab'] == 'securityHeaders' ? 'nav-tab-active' : '') . '">Security Headers</a>';
            echo '<a href="?page=easy-wordpress-optimization&tab=removeBloat" class="nav-tab ' . ($_GET['tab'] == 'removeBloat' ? 'nav-tab-active' : '') . '">Remove Bloats</a>';
            echo '</h2>';

            if ($_GET['tab'] == 'securityHeaders') {
                do_settings_sections('securityHeadersPage');
            } else {
                do_settings_sections('removeBloatPage');
            }

            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'ewo_settings_init');

