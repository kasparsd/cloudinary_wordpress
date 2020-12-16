<?php
/**
 * Settings class for Cloudinary.
 *
 * @package Cloudinary
 */

namespace Cloudinary;

use Cloudinary\Settings\Setting;
use WP_Screen;

/**
 * Settings Class.
 */
class Settings {

	/**
	 * The single instance of the class.
	 *
	 * @var Settings
	 */
	protected static $instance = null;
	/**
	 * The setting to test.
	 *
	 * @var Setting[]
	 */
	protected $settings;

	/**
	 * List of Page handles.
	 *
	 * @var array
	 */
	public $handles = array();

	/**
	 * Holds the current Page.
	 *
	 * @var Setting
	 */
	protected $current_page;

	/**
	 * Holds the current tab.
	 *
	 * @var Setting
	 */
	protected $current_tab;

	/**
	 * Option name for settings based internal data.
	 *
	 * @var string
	 */
	const SETTINGS_DATA = '_settings_version';

	/**
	 * Initiate the settings object.
	 */
	protected function __construct() {
		add_action( 'admin_menu', array( $this, 'build_menus' ) );
	}

	/**
	 * Check settings version to allow settings to update or upgrade.
	 *
	 * @param string $slug The slug for the settings set to check.
	 */
	protected static function check_version( $slug ) {
		$key              = '_' . $slug . self::SETTINGS_DATA;
		$settings_version = get_option( $key, 2.4 );
		$plugin_version   = get_plugin_instance()->version;
		if ( version_compare( $settings_version, $plugin_version, '<' ) ) {
			// Allow for updating.
			do_action( "{$slug}_settings_upgrade", $settings_version, $plugin_version );
			// Update version.
			update_option( $key, $plugin_version );
		}
	}

	/**
	 * Register the page.
	 */
	public function build_menus() {
		foreach ( $this->settings as $setting ) {
			$this->register_admin( $setting );
		}
	}

	/**
	 * Register the page.
	 *
	 * @param Setting $setting The settings to create pages.
	 */
	public function register_admin( $setting ) {
		$render_function = array( $this, 'render' );

		// Setup the main page.
		$page_handle                   = add_menu_page( $setting->get_param( 'page_title' ), $setting->get_param( 'menu_title' ), $setting->get_param( 'capability' ), $setting->get_slug(), $render_function, $setting->get_param( 'icon' ) );
		$this->handles[ $page_handle ] = $setting;
		$setting->set_param( 'page_handle', $page_handle );
		// Setup the Child page handles.
		foreach ( $setting->get_settings() as $sub_setting ) {
			if ( 'page' !== $sub_setting->get_param( 'type' ) ) {
				continue;
			}
			$sub_setting->set_param( 'page_header', $setting->get_param( 'page_header' ) );
			$sub_setting->set_param( 'page_footer', $setting->get_param( 'page_footer' ) );
			$capability                    = $sub_setting->get_param( 'capability', $setting->get_param( 'capability' ) );
			$page_handle                   = add_submenu_page( $setting->get_slug(), $sub_setting->get_param( 'page_title', $setting->get_param( 'page_title' ) ), $sub_setting->get_param( 'menu_title', $sub_setting->get_param( 'page_title' ) ), $capability, $sub_setting->get_slug(), $render_function, $sub_setting->get_param( 'position' ) );
			$this->handles[ $page_handle ] = $sub_setting;
			$sub_setting->set_param( 'page_handle', $page_handle );
		}

	}

	/**
	 * Render a page.
	 */
	public function render() {
		$setting = $this->get_active_page();
		if ( $setting->has_param( 'page_footer' ) ) {
			add_action( 'in_admin_footer', array( $this, 'bind_footer' ) );
		}
		echo $setting->get_component()->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get the currently active page.
	 *
	 * @return Setting
	 */
	public function get_active_page() {
		$screen = get_current_screen();
		$page   = $this->settings;
		if ( $screen instanceof WP_Screen && isset( $this->handles[ $screen->id ] ) ) {
			$page = $this->handles[ $screen->id ];
		}

		return $page;
	}

	/**
	 * Bind the footer to the admin page.
	 */
	public function bind_footer() {
		ob_start();
		add_action( 'admin_footer', array( $this, 'render_footer' ) );
	}

	/**
	 * Render the footer in admin page.
	 */
	public function render_footer() {
		ob_get_clean();
		echo $this->get_active_page()->get_param( 'page_footer' )->get_component()->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Register a setting.
	 *
	 * @param string $slug   The new setting slug.
	 * @param array  $params The setting parameters.
	 *
	 * @return Setting
	 */
	protected function register_setting( $slug, $params = array() ) {

		// Create new setting instance.
		$setting = new Setting( $slug, $params );

		// Register internals.
		$this->settings[ $slug ] = $setting;

		return $setting;
	}

	/**
	 * Create a new setting on the Settings object.
	 *
	 * @param string $slug   The setting slug.
	 * @param array  $params The settings params.
	 *
	 * @return Setting
	 */
	public static function create_setting( $slug, $params = array() ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		$params['option_name'] = $slug; // Root option.
		$params['type']        = 'page';

		return self::$instance->register_setting( $slug, $params );
	}

	/**
	 * Initialise the registered settings by loading the data and registering the settings with WordPress.
	 *
	 * @param string $slug The setting slug to initialise.
	 */
	public static function init_setting( $slug ) {
		if ( ! is_null( self::$instance ) && ! empty( self::$instance->settings[ $slug ] ) ) {
			self::check_version( $slug );
			$settings = self::$instance->settings[ $slug ];
			$settings->register_settings();
			$settings->setup_component();
			$settings->load_value();
		}
	}
}
