<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/includes
 */

namespace Dream_Encode\Copy_Paste_Order_WooCommerce\Core;

use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Copy_Paste_Order_For_Woocommerce_Loader;
use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Copy_Paste_Order_For_Woocommerce_I18n;
use Dream_Encode\Copy_Paste_Order_WooCommerce\Admin\Copy_Paste_Order_For_Woocommerce_Admin;
use Dream_Encode\Copy_Paste_Order_WooCommerce\Frontend\Copy_Paste_Order_For_Woocommerce_Public;
use Dream_Encode\Copy_Paste_Order_WooCommerce\Core\Upgrade\Copy_Paste_Order_For_Woocommerce_Upgrader;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Copy_Paste_Order_For_Woocommerce
 * @subpackage Copy_Paste_Order_For_Woocommerce/includes
 * @author     David Baumwald <david@dream-encode.com>
 */
class Copy_Paste_Order_For_Woocommerce {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     Copy_Paste_Order_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'copy-paste-order-for-woocommerce';

		$this->load_dependencies();
		$this->define_tables();
		$this->set_locale();

		$this->define_public_hooks();

		if ( is_admin() ) {
			$this->define_admin_hooks();
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Copy_Paste_Order_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Copy_Paste_Order_For_Woocommerce_I18n. Defines internationalization functionality.
	 * - Copy_Paste_Order_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Copy_Paste_Order_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		/**
		 * Logger
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/abstracts/abstract-wc-logger.php';
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/log/class-copy-paste-order-for-woocommerce-wc-logger.php';

		/**
		 * Upgrader
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/upgrade/class-copy-paste-order-for-woocommerce-upgrader.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/class-copy-paste-order-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/class-copy-paste-order-for-woocommerce-i18n.php';

		/**
		 * REST API
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/rest-api/class-copy-paste-order-for-woocommerce-rest-response.php';
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/abstracts/abstract-rest-api.php';
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/abstracts/abstract-rest-controller.php';
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/rest-api/class-copy-paste-order-for-woocommerce-core-api.php';

		/**
		 * Default filters.
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'includes/copy-paste-order-for-woocommerce-default-filters.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'admin/class-copy-paste-order-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once COPY_PASTE_ORDER_FOR_WOOCOMMERCE_PLUGIN_PATH . 'public/class-copy-paste-order-for-woocommerce-public.php';

		Copy_Paste_Order_For_Woocommerce_Upgrader::init();

		$this->loader = new Copy_Paste_Order_For_Woocommerce_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Copy_Paste_Order_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function set_locale() {
		$plugin_i18n = new Copy_Paste_Order_For_Woocommerce_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Define custom databases tables.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function define_tables() {
		Copy_Paste_Order_For_Woocommerce_Upgrader::define_tables();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Copy_Paste_Order_For_Woocommerce_Admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'add_paste_order_button' );

		$this->loader->add_action( 'admin_footer', $plugin_admin, 'add_copy_order_container' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function define_public_hooks() {
		$plugin_public = new Copy_Paste_Order_For_Woocommerce_Public();

		$this->loader->add_action( 'init', $plugin_public, 'rest_init' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string  The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Copy_Paste_Order_For_Woocommerce_Loader  Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string  The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
