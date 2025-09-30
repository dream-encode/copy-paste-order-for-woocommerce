# Copy & Paste Order for WooCommerce

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/copy-paste-order-for-woocommerce.svg)](https://wordpress.org/plugins/copy-paste-order-for-woocommerce/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/copy-paste-order-for-woocommerce.svg)](https://wordpress.org/plugins/copy-paste-order-for-woocommerce/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/r/copy-paste-order-for-woocommerce.svg)](https://wordpress.org/plugins/copy-paste-order-for-woocommerce/)

Easily copy WooCommerce orders between sites with just a few clicks. Perfect for migrating orders, testing, or transferring customer data.

## Description

Copy & Paste Order for WooCommerce is a powerful utility plugin that allows you to seamlessly transfer WooCommerce orders from one WordPress site to another. Whether you're migrating to a new site, setting up a staging environment, or need to transfer specific orders between different WooCommerce installations, this plugin makes the process simple and efficient.

### Key Features

- **One-Click Order Copying**: Copy any WooCommerce order with a single click
- **Complete Order Data**: Transfers all order details including customer information, products, shipping, taxes, and meta data
- **Cross-Site Transfer**: Move orders between different WordPress/WooCommerce installations
- **Secure Transfer**: Uses secure JSON format for data transfer
- **Order History Tracking**: Maintains a record of copied orders with source information
- **Easy Setup**: Simple installation on both source and destination sites

### Perfect For

- **Testing & Development**: Copying real order data to staging environments
- **Multi-Site Management**: Transferring orders between different store locations
- **Data Consolidation**: Merging orders from multiple sites into one central location
- **Backup & Recovery**: Creating order backups that can be restored on another site

## How It Works

1. **Install on Both Sites**: The plugin must be installed and activated on both the source site (where you're copying from) and the destination site (where you're copying to).
2. **Copy Order**: On the source site, go to WooCommerce > Orders, find the order you want to copy, hover over the order, and click the "Copy Order" icon.
3. **Get JSON Data**: The plugin generates a secure JSON representation of the order data.
4. **Paste Order**: On the destination site, go to WooCommerce > Orders, click "Paste Order", and paste the JSON data.
5. **Done**: The order is recreated on the destination site with all original data intact.

## Requirements

- WordPress 6.2 or higher.
- WooCommerce plugin installed and activated.
- PHP 8.2 or higher.
- **Plugin must be installed on BOTH source and destination sites**

## Installation

### Automatic Installation

1. Log in to your WordPress admin panel.
2. Go to Plugins > Add New.
3. Search for "Copy & Paste Order for WooCommerce".
4. Click "Install Now" and then "Activate".
5. **Repeat this process on both your source and destination sites**

### Manual Installation

1. Download the plugin ZIP file.
2. Log in to your WordPress admin panel.
3. Go to Plugins > Add New > Upload Plugin.
4. Choose the ZIP file and click "Install Now".
5. Activate the plugin.
6. **Repeat this process on both your source and destination sites**

### After Installation

1. Ensure WooCommerce is installed and activated on both sites.
2. The plugin will automatically add copy/paste functionality to your WooCommerce orders page.
3. No additional configuration is required.

## Frequently Asked Questions

### Do I need to install this plugin on both sites?

**Yes**, the plugin must be installed and activated on both the source site (where you're copying orders from) and the destination site (where you're pasting orders to).

### What order data is copied?

The plugin copies all order data including:
- Customer information (name, email, addresses)
- All order items and quantities
- Pricing and totals
- Shipping information
- Tax details
- Order status and dates
- Custom order meta data
- Order notes

### Will this work with custom order fields?

Yes, the plugin copies all order meta data, including custom fields added by other plugins or themes.

### Can I copy orders between different WooCommerce versions?

The plugin works best when both sites are running similar versions of WooCommerce. While it may work across different versions, we recommend keeping both sites updated for optimal compatibility.

### Is the transfer secure?

Yes, the order data is transferred using secure JSON format. However, you should always ensure you're copying data between trusted sites and avoid sharing the JSON data with unauthorized parties.

### What happens if a product doesn't exist on the destination site?

If a product from the copied order doesn't exist on the destination site, the plugin will create a placeholder product to maintain order integrity.

## Development

This plugin is built using WordPress's [JS Tooling](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/), [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer), and [PHPStan](https://phpstan.org/). It strictly adheres to WordPress's [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) and [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/javascript/).

### Development Setup

1. Clone the repository
2. Run `composer install` to install PHP dependencies
3. Run `yarn install` to install JavaScript dependencies
4. Run `yarn dev` to start the development server
5. Run `yarn test` to run the tests

### Build Process

- `yarn build` - Build production assets
- `yarn dev` - Build development assets and watch for changes
- `composer test` - Run PHP tests and code quality checks
- `yarn test:e2e` - Run end-to-end tests

## Changelog

### 1.0.0
- Initial release
- Copy order functionality with one-click copying
- Paste order functionality with JSON data input
- Complete order data transfer including customer info, products, and meta data
- Order source tracking and notes
- Cross-site compatibility

## Support

For support, feature requests, or bug reports, please visit our [GitHub repository](https://github.com/dream-encode/copy-paste-order-for-woocommerce) or contact us through our [website](https://dream-encode.com/).

## License

This plugin is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.
>
> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
>
> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

## Contributing

We welcome contributions! Please see our [contributing guidelines](CONTRIBUTING.md) for details on how to contribute to this project.