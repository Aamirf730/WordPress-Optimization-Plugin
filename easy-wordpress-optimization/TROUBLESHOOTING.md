# Troubleshooting JavaScript Delay Loading

## Script Exclusion Not Working

If scripts are not being excluded properly in "Delay All JS Files" mode, follow these steps:

### Step 1: Enable Debug Mode
1. Go to **Settings > WP Optimization > JS Delay Loading**
2. Enable **Debug Mode**
3. Save settings

### Step 2: Check Debug Logs
1. Enable WordPress debug logging by adding to wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```
2. Visit your website
3. Check the debug log at `wp-content/debug.log`
4. Look for entries starting with "EWO Debug"

### Step 3: Verify Script Information
The debug log will show:
- Script handle (e.g., "jquery")
- Script source URL (e.g., "https://example.com/wp-includes/js/jquery/jquery.min.js")
- Current delay mode
- Whether scripts are being excluded or delayed

### Step 4: Common Exclusion Patterns

#### Script Handles
Enter the exact script handle:
```
jquery
wp-embed
comment-reply
```

#### File Paths
Enter partial or full file paths:
```
/wp-content/themes/my-theme/
/wp-content/plugins/my-plugin/
jquery.min.js
```

#### Mixed Examples
```
jquery, /wp-content/themes/my-theme/, wp-embed
```

### Step 5: Testing Exclusion

1. **For Script Handles**: Use the exact handle name
   - Example: `jquery` for jQuery
   - Example: `wp-embed` for WordPress embeds

2. **For File Paths**: Use partial paths that appear in the script URL
   - Example: `/wp-content/themes/` to exclude all theme scripts
   - Example: `jquery.min.js` to exclude jQuery specifically

3. **Check Browser Developer Tools**:
   - Press F12 to open developer tools
   - Go to Network tab
   - Reload the page
   - Look for scripts with `data-delay="true"` attribute
   - Scripts without this attribute should load immediately

### Common Issues and Solutions

#### Issue: Script still being delayed
**Solution**: 
- Check the debug log for the exact script handle and path
- Make sure your exclusion pattern matches exactly
- Try using both handle and path patterns

#### Issue: No debug logs appearing
**Solution**:
- Ensure WordPress debug logging is enabled
- Check file permissions on wp-content/debug.log
- Verify debug mode is enabled in plugin settings

#### Issue: Scripts not loading at all
**Solution**:
- Check browser console for JavaScript errors
- Verify the delay time is not too long
- Try disabling "Load on User Interaction" temporarily

### Example Debug Log Output
```
EWO Debug - Handle: jquery, Src: https://example.com/wp-includes/js/jquery/jquery.min.js, Mode: all
EWO Debug - Excluded by handle: jquery
EWO Debug - Handle: wp-embed, Src: https://example.com/wp-includes/js/wp-embed.min.js, Mode: all
EWO Debug - Delaying script: wp-embed (https://example.com/wp-includes/js/wp-embed.min.js)
```

### Still Having Issues?

1. Check the debug log output
2. Verify your exclusion patterns match the actual script handles/paths
3. Try using more specific path patterns
4. Test with a single exclusion first, then add more

For additional support, please provide:
- Debug log output
- Your exclusion settings
- Browser developer tools screenshot
- WordPress version and theme information