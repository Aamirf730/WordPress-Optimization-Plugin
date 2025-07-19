<?php
/**
 * Bloat Functions Testing Script
 * 
 * This script helps test if all the "Remove Bloats" functions are working correctly.
 * Add this to your theme's functions.php temporarily or use a code snippet plugin.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add testing functions
function test_bloat_functions() {
    // Only run for administrators
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $options = get_option('remove_bloat_settings');
    $test_results = array();
    
    // Test 1: Check if emojis are disabled
    if (isset($options['disable_emojis']) && $options['disable_emojis']) {
        $test_results['emojis'] = array(
            'status' => 'enabled',
            'test' => 'Check page source for emoji.js and emoji.css - should be absent',
            'action' => 'Search for "emoji" in page source'
        );
    } else {
        $test_results['emojis'] = array(
            'status' => 'disabled',
            'test' => 'Emoji removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 2: Check if dashicons are disabled
    if (isset($options['disable_dashicons']) && $options['disable_dashicons']) {
        $test_results['dashicons'] = array(
            'status' => 'enabled',
            'test' => 'Check if dashicons.css is loaded - should be absent',
            'action' => 'Check Network tab for dashicons.css'
        );
    } else {
        $test_results['dashicons'] = array(
            'status' => 'disabled',
            'test' => 'Dashicons removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 3: Check if embeds are disabled
    if (isset($options['disable_embeds']) && $options['disable_embeds']) {
        $test_results['embeds'] = array(
            'status' => 'enabled',
            'test' => 'Check for wp-embed.js in page source - should be absent',
            'action' => 'Search for "wp-embed" in page source'
        );
    } else {
        $test_results['embeds'] = array(
            'status' => 'disabled',
            'test' => 'Embed removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 4: Check if XML-RPC is disabled
    if (isset($options['disable_xmlrpc']) && $options['disable_xmlrpc']) {
        $test_results['xmlrpc'] = array(
            'status' => 'enabled',
            'test' => 'Try accessing /xmlrpc.php - should return error',
            'action' => 'Visit: ' . home_url('/xmlrpc.php')
        );
    } else {
        $test_results['xmlrpc'] = array(
            'status' => 'disabled',
            'test' => 'XML-RPC removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 5: Check if jQuery migrate is removed
    if (isset($options['remove_jquery_migrate']) && $options['remove_jquery_migrate']) {
        $test_results['jquery_migrate'] = array(
            'status' => 'enabled',
            'test' => 'Check for jquery-migrate.js in page source - should be absent',
            'action' => 'Search for "jquery-migrate" in page source'
        );
    } else {
        $test_results['jquery_migrate'] = array(
            'status' => 'disabled',
            'test' => 'jQuery migrate removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 6: Check if wlwmanifest link is removed
    if (isset($options['remove_wlwmanifest_link']) && $options['remove_wlwmanifest_link']) {
        $test_results['wlwmanifest'] = array(
            'status' => 'enabled',
            'test' => 'Check for wlwmanifest link in page source - should be absent',
            'action' => 'Search for "wlwmanifest" in page source'
        );
    } else {
        $test_results['wlwmanifest'] = array(
            'status' => 'disabled',
            'test' => 'wlwmanifest removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 7: Check if RSD link is removed
    if (isset($options['remove_rsd_link']) && $options['remove_rsd_link']) {
        $test_results['rsd'] = array(
            'status' => 'enabled',
            'test' => 'Check for RSD link in page source - should be absent',
            'action' => 'Search for "rsd" in page source'
        );
    } else {
        $test_results['rsd'] = array(
            'status' => 'disabled',
            'test' => 'RSD removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 8: Check if shortlink is removed
    if (isset($options['remove_shortlink']) && $options['remove_shortlink']) {
        $test_results['shortlink'] = array(
            'status' => 'enabled',
            'test' => 'Check for shortlink meta tag in page source - should be absent',
            'action' => 'Search for "shortlink" in page source'
        );
    } else {
        $test_results['shortlink'] = array(
            'status' => 'disabled',
            'test' => 'Shortlink removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    // Test 9: Check if self pingbacks are disabled
    if (isset($options['disable_self_pingbacks']) && $options['disable_self_pingbacks']) {
        $test_results['self_pingbacks'] = array(
            'status' => 'enabled',
            'test' => 'Self pingbacks should be disabled (hard to test manually)',
            'action' => 'This is working if enabled in settings'
        );
    } else {
        $test_results['self_pingbacks'] = array(
            'status' => 'disabled',
            'test' => 'Self pingback removal is not enabled',
            'action' => 'Enable in Settings > WP Optimization'
        );
    }
    
    return $test_results;
}

// Display test results in admin footer
function display_bloat_test_results() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $test_results = test_bloat_functions();
    
    echo '<div style="background: #fff; border: 1px solid #ccc; padding: 20px; margin: 20px; border-radius: 5px;">';
    echo '<h3>🔄 Bloat Functions Test Results</h3>';
    echo '<p><strong>Instructions:</strong> Use these tests to verify if your bloat removal features are working.</p>';
    
    foreach ($test_results as $feature => $result) {
        $status_color = $result['status'] === 'enabled' ? '#28a745' : '#dc3545';
        $status_icon = $result['status'] === 'enabled' ? '✅' : '❌';
        
        echo '<div style="margin: 10px 0; padding: 10px; border-left: 4px solid ' . $status_color . '; background: #f8f9fa;">';
        echo '<strong>' . ucfirst(str_replace('_', ' ', $feature)) . '</strong> ' . $status_icon . '<br>';
        echo '<small><strong>Test:</strong> ' . $result['test'] . '</small><br>';
        echo '<small><strong>Action:</strong> ' . $result['action'] . '</small>';
        echo '</div>';
    }
    
    echo '<div style="margin-top: 20px; padding: 10px; background: #e7f3ff; border-radius: 3px;">';
    echo '<strong>💡 Quick Testing Tips:</strong><br>';
    echo '• Use browser Developer Tools (F12) to check Network tab<br>';
    echo '• Search page source (Ctrl+F) for specific terms<br>';
    echo '• Test on both frontend and admin pages<br>';
    echo '• Clear cache if using caching plugins';
    echo '</div>';
    
    echo '</div>';
}

// Add to admin footer
add_action('admin_footer', 'display_bloat_test_results');

// Add to frontend footer (only for logged-in admins)
function display_frontend_test_results() {
    if (is_user_logged_in() && current_user_can('manage_options')) {
        $test_results = test_bloat_functions();
        
        echo '<div style="position: fixed; bottom: 20px; right: 20px; background: #fff; border: 1px solid #ccc; padding: 15px; border-radius: 5px; max-width: 300px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
        echo '<h4 style="margin: 0 0 10px 0;">🔍 Bloat Test</h4>';
        
        $enabled_count = 0;
        foreach ($test_results as $feature => $result) {
            if ($result['status'] === 'enabled') {
                $enabled_count++;
            }
        }
        
        echo '<p style="margin: 0; font-size: 12px;">' . $enabled_count . ' of ' . count($test_results) . ' features enabled</p>';
        echo '<p style="margin: 5px 0 0 0; font-size: 11px; color: #666;">Check page source for removed elements</p>';
        echo '</div>';
    }
}

add_action('wp_footer', 'display_frontend_test_results');

// Add a simple test endpoint
function test_bloat_endpoint() {
    if (isset($_GET['test_bloat']) && current_user_can('manage_options')) {
        $test_results = test_bloat_functions();
        header('Content-Type: application/json');
        echo json_encode($test_results, JSON_PRETTY_PRINT);
        exit;
    }
}

add_action('init', 'test_bloat_endpoint');