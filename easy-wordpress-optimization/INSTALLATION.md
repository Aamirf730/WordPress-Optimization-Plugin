# Easy WordPress Optimization Plugin - Installation Guide

## Quick Installation

### Method 1: WordPress Admin Panel (Recommended)
1. Download the `easy-wordpress-optimization-v1.2.zip` file
2. Log in to your WordPress admin panel
3. Go to **Plugins > Add New**
4. Click **Upload Plugin** at the top of the page
5. Choose the zip file and click **Install Now**
6. Click **Activate Plugin**

### Method 2: FTP Upload
1. Extract the zip file on your computer
2. Upload the `easy-wordpress-optimization` folder to `/wp-content/plugins/`
3. Go to **Plugins** in your WordPress admin
4. Find "Easy WordPress Optimization" and click **Activate**

## Configuration

After activation, go to **Settings > WP Optimization** to configure:

### Security Headers Tab
- Enable/disable various security headers
- Recommended: Enable X-Frame-Options, X-XSS-Protection, and X-Content-Type-Options

### Remove Bloats Tab
- Select WordPress features to disable
- Be cautious - some features may be required by your theme

### JS Delay Loading Tab
- **Enable JavaScript Delay Loading**: Turn the feature on/off
- **Delay Mode**: Choose between "Selective Delay" or "Delay All JS Files"
- **Delay Time**: Set how long to wait before loading scripts (500-10000ms)
- **Load on User Interaction**: Load scripts on first user interaction instead of delay time
- **Scripts to Delay**: Select specific scripts to delay (Selective mode only)
- **Custom Scripts to Delay**: Enter additional script handles or file paths
- **Scripts to Exclude**: Enter scripts to exclude from delay (Delay All mode only)

## Testing

1. Configure your desired settings
2. Visit your website's frontend
3. Open browser developer tools (F12)
4. Check the Console tab for delay loading messages
5. Test user interaction (scroll, click) to see scripts load

## Troubleshooting

### Scripts Not Loading
- Check that critical scripts are not being delayed
- Verify script dependencies are maintained
- Try different delay times

### Security Header Conflicts
- Disable conflicting headers individually
- Test thoroughly after changes
- Check browser console for errors

### Performance Issues
- Start with conservative settings
- Monitor page load times
- Use tools like Google PageSpeed Insights

## Support

For support and feature requests, visit [WPGeared](https://wpgeared.com/).

## Version History

### Version 1.2
- Added dual-mode JavaScript delay loading
- Enhanced admin interface with tabbed navigation
- Improved documentation and user guidance
- Added activation hooks and default settings