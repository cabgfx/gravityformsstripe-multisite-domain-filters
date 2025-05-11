# Gravity Forms Stripe Multisite Domain Filters

A WordPress plugin that fixes domain issues with the Gravity Forms Stripe addon in multisite setups.

## Description

This plugin addresses an issue where the Gravity Forms Stripe addon redirects users to the wrong domain in multisite WordPress installations. It ensures that users are redirected back to the correct subsite domain after completing or canceling a payment.

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- Gravity Forms 2.5 or higher
- Gravity Forms Stripe Add-On 3.0 or higher
- WordPress Multisite installation

## Installation

1. Upload the `gravityformsstripe-multisite-domain-filters` folder to the `/wp-content/plugins/` directory
2. Activate the plugin either:
   - Network-wide through the Network Admin → Plugins menu (recommended)
   - Per-site through the site's Plugins menu
3. The plugin will automatically check for required dependencies and display appropriate notices if they are missing

## Usage

The plugin works automatically once activated. No configuration is required.

### Debugging

To enable debug logging:

1. Add the following to your wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

2. Check the debug.log file in wp-content/ for messages prefixed with '[GF Stripe Multisite Domain Filters]'

## Multisite Compatibility

This plugin is fully compatible with WordPress multisite installations:

- **Network Activation**: The plugin can be activated network-wide, meaning it will run on all sites in your multisite network. This is the recommended approach as it:
  - Ensures consistent behavior across all sites
  - Reduces server load by loading the plugin files only once
  - Simplifies maintenance and updates
  - Prevents individual site administrators from deactivating the fix

- **URL Handling**: The plugin properly handles:
  - Subdomain multisite setups (e.g., site1.example.com, site2.example.com)
  - Subdirectory multisite setups (e.g., example.com/site1, example.com/site2)
  - Different URL structures across subsites
  - Preserves all paths and query parameters during redirects

- **Domain Fixes**: Automatically corrects:
  - Success URLs after payment completion
  - Cancel URLs when payment is cancelled
  - Maintains the correct domain for each subsite
  - Preserves all form data and parameters

## Support

For support, please [open an issue](https://github.com/yourusername/gravityformsstripe-multisite-domain-filters/issues) on GitHub.

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by [Your Name](https://yourwebsite.com)
