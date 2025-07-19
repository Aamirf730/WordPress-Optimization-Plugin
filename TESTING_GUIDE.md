# Testing Guide for Easy WordPress Optimization Plugin

This guide will help you test if the "remove bloat" features are working correctly in your WordPress site.

## Testing Disabled Embeds

When you disable embeds using the plugin, it removes the `wp-embed` script. Here are several ways to test if this is working:

### Method 1: Browser Developer Tools (Recommended)

1. **Enable the "Disable Embeds" option** in your WordPress admin:
   - Go to Settings > WP Optimization
   - Navigate to the "Remove Bloats" tab
   - Toggle on "Disable Embeds"
   - Save changes

2. **Check the page source**:
   - Right-click on your WordPress page and select "View Page Source"
   - Search for "wp-embed" (Ctrl+F or Cmd+F)
   - If embeds are properly disabled, you should NOT find any references to `wp-embed.js`

3. **Use Browser Developer Tools**:
   - Open Developer Tools (F12 or right-click > Inspect)
   - Go to the "Network" tab
   - Refresh the page
   - Look for any requests to `wp-embed.js` - there should be none if embeds are disabled

4. **Check the Console**:
   - In Developer Tools, go to the "Console" tab
   - Look for any errors related to `wp-embed` - there should be none

### Method 2: Test Embed Functionality

1. **Try to embed content**:
   - Create a new post or page
   - Try to paste a YouTube URL, Twitter URL, or other embeddable content
   - If embeds are disabled, the content should appear as a plain link instead of being embedded

2. **Test with oEmbed URLs**:
   - Try pasting: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
   - If embeds are disabled, it should remain as a plain URL

### Method 3: Check WordPress Functions

You can also test programmatically by adding this code to your theme's `functions.php` or using a code snippet plugin:

```php
// Add this temporarily to test if wp-embed is deregistered
add_action('wp_footer', function() {
    if (!wp_script_is('wp-embed', 'registered')) {
        echo '<!-- wp-embed script is disabled (good!) -->';
    } else {
        echo '<!-- wp-embed script is still registered (not working) -->';
    }
});
```

## Testing Other Bloat Removal Features

### Disable Emojis
1. **Check page source** for emoji-related scripts:
   - Search for "emoji" in page source
   - Should not find `emoji.js` or `emoji.css`

2. **Check Network tab** in Developer Tools:
   - No requests to `wp-emoji-release.min.js` or similar

### Remove jQuery Migrate
1. **Check page source** for jQuery:
   - Search for "jquery-migrate"
   - Should not find `jquery-migrate.js`

2. **Check Network tab**:
   - Should only see `jquery.js` but not `jquery-migrate.js`

### Disable XML-RPC
1. **Test XML-RPC endpoint**:
   - Try accessing: `https://yoursite.com/xmlrpc.php`
   - Should return an error or be blocked if disabled

2. **Use curl command**:
   ```bash
   curl -X POST https://yoursite.com/xmlrpc.php -d "<?xml version='1.0'?><methodCall><methodName>system.listMethods</methodName><params></params></methodCall>"
   ```
   - Should return an error if XML-RPC is disabled

### Remove Various Links
Check page source for these elements (should be absent if disabled):
- `wlwmanifest` link
- `rsd` link  
- `shortlink` meta tag

## Advanced Testing Methods

### Method 1: WordPress Debug Mode
Enable WordPress debug mode to see more detailed information:

```php
// Add to wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Method 2: Use a Testing Plugin
Install a plugin like "Query Monitor" to see:
- Which scripts are being loaded
- Which hooks are being executed
- Performance metrics

### Method 3: Browser Extensions
Use browser extensions like:
- **Web Developer** (Chrome/Firefox) - to inspect HTTP headers and scripts
- **GTmetrix** - to analyze page performance and see what's being loaded

## Troubleshooting

### If Embeds Still Work After Disabling:
1. **Clear cache** - if using caching plugins
2. **Check for conflicts** - other plugins might be re-registering the script
3. **Test on different pages** - some themes might load scripts differently
4. **Check theme functions** - custom theme code might be loading embeds

### If Settings Don't Save:
1. **Check permissions** - ensure you have 'manage_options' capability
2. **Check for JavaScript errors** - in browser console
3. **Verify database** - check if options are saved in `wp_options` table

## Performance Testing

To measure the impact of your optimizations:

1. **Use PageSpeed Insights** (Google)
2. **Use GTmetrix** for detailed performance analysis
3. **Compare before/after** using the same testing conditions
4. **Monitor Core Web Vitals** in Google Search Console

## Security Testing

For security headers testing:
1. **Use security headers checker** tools online
2. **Check HTTP headers** in browser Developer Tools > Network tab
3. **Use curl to test headers**:
   ```bash
   curl -I https://yoursite.com
   ```

## Quick Test Checklist

- [ ] Disable embeds option is enabled in admin
- [ ] No `wp-embed.js` in page source
- [ ] No `wp-embed.js` requests in Network tab
- [ ] Embed URLs remain as plain links
- [ ] No console errors related to embeds
- [ ] Other bloat removal features work as expected
- [ ] Site functionality remains intact
- [ ] Performance has improved

Remember to test on both the frontend and backend of your WordPress site, and consider testing with different themes and plugins to ensure compatibility.