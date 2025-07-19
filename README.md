# Easy WordPress Optimization Plugin

A comprehensive WordPress optimization plugin that improves your website's performance, security, and user experience through multiple optimization techniques.

## Features

### 🔒 Security Headers
- **X-Frame-Options**: Prevents clickjacking attacks
- **X-XSS-Protection**: Enables browser's XSS filtering
- **X-Content-Type-Options**: Prevents MIME type sniffing
- **Strict-Transport-Security**: Enforces HTTPS connections
- **Content-Security-Policy**: Controls resource loading
- **Referrer-Policy**: Controls referrer information
- **Feature-Policy**: Controls browser features
- **Expect-CT**: Certificate Transparency enforcement

### 🚀 Performance Optimization
- **JavaScript Delay Loading**: Delays non-critical JavaScript files to improve initial page load speed
- **WordPress Bloat Removal**: Removes unnecessary WordPress features and scripts

### 📦 Bloat Removal
- Disable WordPress emojis
- Remove jQuery Migrate
- Disable WordPress embeds
- Remove XML-RPC functionality
- Remove unnecessary meta links (wlwmanifest, RSD, shortlink)
- Disable self pingbacks

## 🆕 JavaScript Delay Loading Feature

The JavaScript delay loading feature is designed to improve your website's initial page load speed by delaying non-critical JavaScript files.

### How It Works

1. **Script Identification**: The plugin identifies scripts that should be delayed based on your configuration
2. **Attribute Addition**: Adds `data-delay="true"` attributes to selected scripts
3. **Delayed Loading**: Scripts are loaded either after a specified delay time or on first user interaction
4. **Fallback Protection**: Scripts are automatically loaded after 5 seconds regardless of other conditions

### Configuration Options

#### Enable JavaScript Delay Loading
Toggle to enable/disable the JavaScript delay loading feature.

#### Delay Mode
Choose between two delay strategies:
- **Selective Delay**: Only delay specific scripts that you select
- **Delay All JS Files**: Delay all JavaScript files except those you explicitly exclude

#### Delay Time (milliseconds)
Set the time to wait before loading delayed scripts (500-10000ms). Default: 2000ms

#### Load on User Interaction
When enabled, delayed scripts will load on the first user interaction (scroll, click, mousemove, keydown) instead of after the delay time.

#### Scripts to Delay (Selective Mode)
Select which WordPress scripts should be delayed:
- **jQuery**: WordPress's jQuery library
- **jQuery Migrate**: jQuery compatibility layer
- **WordPress Embeds**: Embed functionality
- **Comment Reply**: Comment threading scripts
- **Emoji Script**: WordPress emoji support
- **WordPress REST API**: REST API scripts
- **WordPress Utilities**: Utility functions

#### Custom Scripts to Delay (Selective Mode)
Enter additional script handles or file paths to delay. Examples:
- Script handles: `my-custom-script, another-script`
- File paths: `/wp-content/themes/my-theme/script.js, /wp-content/plugins/my-plugin/assets/script.js`

#### Scripts to Exclude (Delay All Mode)
Enter script handles or file paths to exclude from delay. Only these scripts will load immediately:
- Script handles: `jquery, wp-embed`
- File paths: `/wp-content/plugins/critical-plugin/script.js`

### Best Practices

1. **Only delay non-critical scripts**: Don't delay scripts that are essential for page functionality
2. **Test thoroughly**: Ensure your website works correctly with delayed scripts
3. **Monitor performance**: Use tools like Google PageSpeed Insights to measure improvements
4. **Consider user experience**: Balance speed improvements with functionality

### Technical Implementation

The plugin uses a sophisticated JavaScript implementation that:
- Supports two delay modes: selective and delay-all
- Preserves all original script attributes
- Handles both time-based and interaction-based loading
- Includes fallback mechanisms for reliability
- Uses passive event listeners for performance
- Maintains script execution order
- Supports both script handles and file path matching
- Provides dynamic UI that adapts to the selected mode

## Installation

1. Upload the plugin files to `/wp-content/plugins/easy-wordpress-optimization/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > WP Optimization to configure the plugin

## Configuration

### Security Headers
Navigate to the "Security Headers" tab to enable/disable various security headers. Each header provides specific security benefits and can be toggled independently.

### Bloat Removal
Use the "Remove Bloats" tab to select which WordPress features to disable. Be cautious when removing features as some may be required by your theme or other plugins.

### JavaScript Delay Loading
Configure JavaScript delay loading in the "JS Delay Loading" tab:
- Enable the feature
- Set delay time
- Choose loading strategy (time-based or interaction-based)
- Select scripts to delay

## Compatibility

- WordPress 5.0+
- PHP 7.4+
- All major browsers (Chrome, Firefox, Safari, Edge)

## Performance Impact

- **Security Headers**: Minimal impact, headers are sent early in the request
- **Bloat Removal**: Positive impact, reduces HTTP requests and file sizes
- **JavaScript Delay Loading**: Significant positive impact on initial page load speed

## Troubleshooting

### JavaScript Delay Issues
If you experience issues with delayed scripts:
1. Check that critical scripts are not being delayed
2. Verify script dependencies are maintained
3. Test with different delay times
4. Consider disabling interaction-based loading

### Security Header Conflicts
Some security headers may conflict with certain plugins or themes:
1. Disable conflicting headers individually
2. Test thoroughly after changes
3. Check browser console for errors

## Support

For support and feature requests, please visit [WPGeared](https://wpgeared.com/).

## Changelog

### Version 1.2
- Added JavaScript delay loading feature
- Improved admin interface with tabbed navigation
- Enhanced documentation and user guidance

### Version 1.1
- Added comprehensive bloat removal features
- Improved security header implementation
- Enhanced admin interface

### Version 1.0
- Initial release with security headers
- Basic WordPress optimization features

## License

This plugin is licensed under the GPL v2 or later.

## Contributing

Contributions are welcome! Please ensure your code follows WordPress coding standards and includes appropriate documentation.
