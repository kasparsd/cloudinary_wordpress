<?php
/**
 * Cloudinary Setting.
 *
 * @package Cloudinary
 */

namespace Cloudinary\Settings;

use Cloudinary\Settings;
use Cloudinary\UI\Component;

/**
 * Class Setting.
 *
 * Setting for Cloudinary.
 */
class Setting {

	/**
	 * The setting params.
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Root settings.
	 *
	 * @var Setting|null
	 */
	protected $root_setting;

	/**
	 * Parent of the setting.
	 *
	 * @var Setting|Settings|null
	 */
	protected $parent;

	/**
	 * Holds this settings child settings.
	 *
	 * @var Setting[]
	 */
	protected $settings = array();

	/**
	 * Setting slug.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Setting Value.
	 *
	 * @var mixed
	 */
	protected $value = null;

	/**
	 * This settings component object.
	 *
	 * @var Component
	 */
	protected $component;

	/**
	 * Holds a list of dynamic setting params.
	 *
	 * @var array
	 */
	protected $setting_params;

	/**
	 * Setting constructor.
	 *
	 * @param string       $slug   The setting slug.
	 * @param array        $params The setting params.
	 * @param null|Setting $parent $the parent setting.
	 */
	public function __construct( $slug, $params = array(), $parent = null ) {
		$this->slug           = $slug;
		$this->setting_params = $this->get_settings_params();
		$root                 = $this;
		if ( ! is_null( $parent ) ) {
			$root = $parent->get_root_setting();
			$this->set_parent( $parent );
		}
		$this->root_setting = $root;
		if ( ! empty( $params ) ) {
			$this->setup_setting( $params );
		}
	}

	/**
	 * Get the settings parameter and callback list.
	 *
	 * @return array
	 */
	protected function get_settings_params() {
		$default_setting_params = array(
			'components'  => array( $this, 'add_child_settings' ),
			'settings'    => array( $this, 'add_child_settings' ),
			'pages'       => array( $this, 'add_child_pages' ),
			'tabs'        => array( $this, 'add_tab_pages' ),
			'page_header' => array( $this, 'add_header' ),
			'page_footer' => array( $this, 'add_footer' ),
		);
		/**
		 * Filters the list of params that indicate a child setting to allow registering dynamically.
		 *
		 * @param array $setting_params The array of params.
		 *
		 * @return array
		 */
		$setting_params = apply_filters( 'get_setting_params', $default_setting_params, $this );

		return $setting_params;
	}

	/**
	 * Get all the param keys.
	 *
	 * @return array
	 */
	public function get_param_keys() {
		return array_keys( $this->params );
	}

	/**
	 * Set a parameter and value to the setting.
	 *
	 * @param string $param Param key to set.
	 * @param mixed  $value The value to set.
	 */
	public function set_param( $param, $value ) {
		$this->params[ $param ] = $value;
		if ( is_null( $value ) ) {
			$this->remove_param( $param );
		}
	}

	/**
	 * Remove a parameter.
	 *
	 * @param string $param Param key to set.
	 */
	public function remove_param( $param ) {
		unset( $this->params[ $param ] );
	}

	/**
	 *
	 * Check if a param exists.
	 *
	 * @param string $param The param to check.
	 *
	 * @return bool
	 */
	public function has_param( $param ) {
		return isset( $this->params[ $param ] );
	}

	/**
	 * Get params param.
	 *
	 * @param string $param   The param to get.
	 * @param mixed  $default The default value for this param is a value is not found.
	 *
	 * @return string|array|bool|Setting
	 */
	public function get_param( $param, $default = null ) {
		return isset( $this->params[ $param ] ) ? $this->params[ $param ] : $default;
	}

	/**
	 * Get the whole params.
	 *
	 * @return array
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * Check if setting has a parent.
	 *
	 * @return bool
	 */
	public function has_parent() {
		return ! empty( $this->parent );
	}

	/**
	 * Check if setting has settings.
	 *
	 * @return bool
	 */
	public function has_settings() {
		return ! empty( $this->settings );
	}

	/**
	 * Check if setting has settings.
	 *
	 * @param string $setting_slug The setting slug to check.
	 *
	 * @return bool
	 */
	public function has_setting( $setting_slug ) {
		$setting_slugs = $this->get_setting_slugs();

		return in_array( $setting_slug, $setting_slugs, true );
	}

	/**
	 * Get the parent setting.
	 *
	 * @return Setting|null
	 */
	public function get_parent() {
		$parent = null;
		if ( $this->has_parent() ) {
			$parent = $this->parent;
		}

		return $parent;
	}

	/**
	 * Get all settings settings.
	 *
	 * @return Setting[]
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Get all slugs of settings.
	 *
	 * @return array
	 */
	public function get_setting_slugs() {
		return array_keys( $this->settings );
	}

	/**
	 * Get a setting setting.
	 *
	 * @param string $slug The setting slug to get.
	 *
	 * @return Setting
	 */
	public function get_setting( $slug ) {
		$setting = null;
		if ( $this->has_settings() ) {
			if ( $this->has_setting( $slug ) ) {
				return $this->settings[ $slug ];
			}
			$setting = $this->find_setting_recursively( $slug );
		}

		return ! is_null( $setting ) ? $setting : $this->create_setting( $slug, null, $this ); // return a dynamic setting.
	}

	/**
	 * Find a setting from the root.
	 *
	 * @param string $slug The setting slug to get.
	 *
	 * @return Setting
	 */
	public function find_setting( $slug ) {
		if ( ! $this->is_root_setting() ) {
			return $this->get_root_setting()->find_setting( $slug );
		}

		return $this->get_setting( $slug );
	}

	/**
	 * Recursively find a setting.
	 *
	 * @param string $slug The setting slug to get.
	 *
	 * @return Setting|null
	 */
	protected function find_setting_recursively( $slug ) {
		$found = null;
		// loop through settings to find it.
		foreach ( $this->get_settings() as $sub_setting ) {
			if ( $sub_setting->has_settings() ) {
				if ( $sub_setting->has_setting( $slug ) ) {
					$found = $sub_setting->get_setting( $slug );
					break;
				}
				$found = $sub_setting->find_setting_recursively( $slug );
				if ( ! is_null( $found ) ) {
					break;
				}
			}
		}

		return $found;
	}

	/**
	 * Register a setting.
	 *
	 * @param array $params The setting params.
	 */
	public function setup_setting( array $params ) {

		$dynamic_params = array_filter( $params, array( $this, 'is_setting_param' ), ARRAY_FILTER_USE_KEY );
		foreach ( $params as $param => $value ) {

			if ( $this->is_setting_param( $param ) ) {
				continue;
			}
			// Set params.
			$this->set_param( $param, $value );
		}

		// Register dynamics.
		$this->register_dynamic_settings( $dynamic_params );
	}

	/**
	 * Register the setting with WordPress.
	 */
	protected function register_setting() {
		$option_group = $this->get_option_name();
		$root_group   = $this->get_root_setting()->get_option_name();
		if ( $option_group !== $root_group ) { // Dont save the core setting.
			$args = array(
				'type'              => 'array',
				'description'       => $this->get_param( 'description' ),
				'sanitize_callback' => array( $this, 'prepare_sanitizer' ),
				'show_in_rest'      => false,
			);
			register_setting( $option_group, $option_group, $args );
			add_filter( 'pre_update_site_option_' . $option_group, array( $this, 'set_notices' ), 10, 3 );
			add_filter( 'pre_update_option_' . $option_group, array( $this, 'set_notices' ), 10, 3 );
			$this->set_param( 'setting_registered', true );
		}
	}

	/**
	 * Prepare the setting option group to be sanitized by each component.
	 *
	 * @param array $data Array of values to sanitize.
	 *
	 * @return array
	 */
	public function prepare_sanitizer( $data ) {

		foreach ( $data as $slug => $value ) {
			$setting       = $this->find_setting( $slug );
			$data[ $slug ] = $setting->get_component()->sanitize_value( $value );
		}

		return $data;
	}

	/**
	 * Set notices on successful update of settings.
	 *
	 * @param mixed  $value        The new Value.
	 * @param mixed  $old_value    The old value.
	 * @param string $setting_slug The setting key.
	 *
	 * @return mixed
	 */
	public function set_notices( $value, $old_value, $setting_slug ) {
		static $set_errors = array();
		if ( ! isset( $set_errors[ $setting_slug ] ) ) {
			if ( $value !== $old_value ) {
				if ( is_wp_error( $value ) ) {
					add_settings_error( $setting_slug, 'setting_notice', $value->get_error_message(), 'error' );
					$value = $old_value;
				} else {
					$setting = $this->get_root_setting()->find_setting( $setting_slug );
					$notice  = $setting->get_param( 'success_notice', __( 'Settings updated successfully', 'cloudinary' ) );
					add_settings_error( $setting_slug, 'setting_notice', $notice, 'updated' );
				}
			}
			$set_errors[ $setting_slug ] = true;
		}

		return $value;
	}

	/**
	 * Register dynamic settings from params.
	 *
	 * @param array $params Array of params to create dynamic settings from.
	 */
	protected function register_dynamic_settings( $params ) {
		foreach ( $params as $param => $value ) {

			// Dynamic array based without a key slug.
			if ( is_int( $param ) && is_array( $value ) ) {
				$slug = isset( $value['slug'] ) ? $value['slug'] : $this->slug . '_' . $param;
				$this->create_setting( $slug, $value, $this );
				continue;
			}

			$callback = $this->get_setting_param_callback( $param );
			$callable = is_callable( $callback );
			if ( $callable ) {
				call_user_func( $callback, $value );
			}
		}
	}

	/**
	 * Get a callback to handle a dynamic child setting creation.
	 *
	 * @param string $param Param name to get callback for.
	 *
	 * @return string
	 */
	protected function get_setting_param_callback( $param ) {
		return $this->is_setting_param( $param ) ? $this->setting_params[ $param ] : '__return_null';
	}

	/**
	 * Checks if a param is a dynamic child setting array.s
	 *
	 * @param string $param Param to check for.
	 *
	 * @return bool
	 */
	protected function is_setting_param( $param ) {
		return isset( $this->setting_params[ $param ] ) || is_int( $param );
	}

	/**
	 * Create child tabs.
	 *
	 * @param array $tab_pages Array of tabs to create.
	 */
	public function add_tab_pages( $tab_pages ) {

		$this->set_param( 'has_tabs', true );
		foreach ( $tab_pages as $tab_page => $params ) {
			$params['type']        = 'page';
			$params['option_name'] = $this->build_option_name( $tab_page );
			$this->create_setting( $tab_page, $params, $this );
		}

	}

	/**
	 * Add a page header.
	 *
	 * @param array $params The header config.
	 */
	public function add_header( $params ) {
		$this->add_param_setting( 'page_header', $params );
	}

	/**
	 * Add a page footer.
	 *
	 * @param array $params The footer config.
	 */
	public function add_footer( $params ) {
		$this->add_param_setting( 'page_footer', $params );
	}

	/**
	 * Add a setting as a param.
	 *
	 * @param string $param  The param slug to add.
	 * @param array  $params The setting parameters.
	 */
	public function add_param_setting( $param, $params ) {
		$params['type'] = $param;
		$slug           = $this->get_slug() . '_' . $param;
		$setting        = new Setting( $slug, $params, $this );
		$this->set_param( $param, $setting );
	}

	/**
	 * Create child pages on this setting.
	 *
	 * @param array $pages Page setting params.
	 */
	public function add_child_pages( $pages ) {

		foreach ( $pages as $slug => $params ) {
			$params['option_name'] = $this->build_option_name( $slug );
			$params['type']        = 'page';
			$this->create_setting( $slug, $params, $this );
		}
	}

	/**
	 * Get a specific attribute from the setting.
	 *
	 * @param string $attribute_point The attribute point to get.
	 *
	 * @return mixed
	 */
	public function get_attributes( $attribute_point ) {
		$return     = array();
		$attributes = $this->get_param( 'attributes', array() );
		if ( isset( $attributes[ $attribute_point ] ) ) {
			$return = $attributes[ $attribute_point ];
		}

		return $return;
	}

	/**
	 * Setup the settings component.
	 */
	public function setup_component() {
		$this->component = Component::init( $this );
		if ( $this->has_settings() ) {
			foreach ( $this->get_settings() as $setting ) {
				$setting->setup_component();
			}
		}
	}

	/**
	 *  Register Settings with WordPress.
	 */
	public function register_settings() {
		// Register WordPress Settings only if has a capture component.
		if ( $this->is_capture() ) {
			if ( $this->has_param( 'option_name' ) ) {
				$this->register_setting();
			}
			foreach ( $this->get_settings() as $setting ) {
				$setting->register_settings();
			}
		}
	}

	/**
	 * Register sub settings.
	 *
	 * @param array       $settings The array of sub settings.
	 * @param null|string $type     Forced type of the child settings.
	 */
	public function add_child_settings( $settings, $type = null ) {
		foreach ( $settings as $setting => $params ) {
			if ( ! is_null( $type ) ) {
				$params['option_name'] = $this->build_option_name( $setting );
				$params['type']        = $type;
			}
			$this->create_setting( $setting, $params, $this );
		}
	}

	/**
	 * Get the settings Component.
	 *
	 * @return \Cloudinary\UI\Component
	 */
	public function get_component() {
		if ( is_null( $this->component ) ) {
			$this->setup_component();
		}

		return $this->component;
	}

	/**
	 * Render the settings Component.
	 *
	 * @return string
	 */
	public function render_component() {
		return $this->get_component()->render();
	}

	/**
	 * Get the setting slug.
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Get the option slug.
	 *
	 * @return string
	 */
	public function get_option_name() {
		$option_slug = null;
		if ( $this->has_param( 'option_name' ) ) {
			return $this->get_param( 'option_name' );
		} elseif ( $this->has_parent() ) {
			$option_slug = $this->get_parent()->get_option_name();
		}

		if ( is_null( $option_slug ) && $this->has_parent() && ! $this->get_parent()->has_parent() ) {
			// Set an auto option slug if the parent is the root item.
			$option_slug = $this->get_parent()->get_slug() . '_' . $this->get_slug();
		}

		return $option_slug;
	}

	/**
	 * Build a new option name.
	 *
	 * @param string $slug The slug to build for.
	 *
	 * @return string
	 */
	protected function build_option_name( $slug ) {
		$option_path = array(
			$this->get_option_name(),
			$slug,
		);
		$option_path = array_unique( $option_path );

		return implode( '_', array_filter( $option_path ) );
	}

	/**
	 * Set the settings value.
	 *
	 * @param mixed $value The value to set.
	 */
	public function set_value( $value ) {
		if ( is_array( $value ) ) {
			// Attempt to match array keys to settings settings.
			foreach ( $value as $key => $val ) {
				$this->find_setting( $key )->set_value( $val );
			}
		}
		$this->value = $value;
	}

	/**
	 * Save the value of a setting to the first lower options slug.
	 *
	 * @return bool
	 */
	public function save_value() {
		if ( $this->has_param( 'option_name' ) ) {
			$slug = $this->get_option_name();

			return update_option( $slug, $this->get_value() );
		} elseif ( $this->has_parent() ) {
			return $this->get_parent()->save_value();
		}

		return false;
	}

	/**
	 * Get all option names in settings.
	 *
	 * @return array
	 */
	protected function get_option_names() {
		static $names = array();

		if ( $this->has_param( 'option_name' ) && $this->has_param( 'setting_registered' ) ) {
			$names[ $this->get_slug() ] = $this->get_param( 'option_name' );
		}
		if ( $this->has_settings() ) {
			foreach ( $this->get_settings() as $setting ) {
				$setting->get_option_names();
			}
		}

		return $names;
	}

	/**
	 * Load settings value.
	 */
	public function load_value() {

		$names       = $this->get_option_names();
		$this->value = array();
		foreach ( $names as $slug => $name ) {
			$data    = get_option( $name );
			$setting = $this->find_setting( $slug );
			$setting->set_value( $data );
			$this->value[ $slug ] = $data;
		}
	}

	/**
	 * Check if setting has a value.
	 *
	 * @return bool
	 */
	public function has_value() {
		return ! is_null( $this->value );
	}

	/**
	 * Get the value for a setting.
	 *
	 * @return mixed
	 */
	public function get_value() {
		if ( is_null( $this->value ) ) {
			$this->value = $this->get_param( 'default' );
		}

		return $this->value;
	}

	/**
	 * Create a setting.
	 *
	 * @param string  $slug   The setting slag to add.
	 * @param array   $params Settings params.
	 * @param Setting $parent The optional parent to add new setting to.
	 *
	 * @return Setting
	 */
	public function create_setting( $slug, $params = array(), $parent = null ) {

		$new_setting = new Setting( $slug, $params, $this->root_setting );
		$new_setting->set_value( null ); // Set value to null.
		if ( $parent ) {
			$parent->add_setting( $new_setting );
		}

		return $new_setting;
	}

	/**
	 * Add a setting to setting, if it already exists, move setting.
	 *
	 * @param Setting $setting The setting to add.
	 *
	 * @return Setting
	 */
	public function add_setting( $setting ) {
		$setting->set_parent( $this );
		$this->settings[ $setting->get_slug() ] = $setting;

		return $setting;
	}

	/**
	 * Set a settings parent.
	 *
	 * @param Setting $parent The parent to set as.
	 */
	public function set_parent( $parent ) {

		// Remove old parent.
		if ( ! $this->is_root_setting() && $this->has_parent() && $this->get_parent() !== $parent ) {
			$this->get_parent()->remove_setting( $this->get_slug() );
		}
		$this->parent = $parent;
	}

	/**
	 * Remove a setting.
	 *
	 * @param string $slug The setting slug to remove.
	 */
	public function remove_setting( $slug ) {
		if ( $this->has_setting( $slug ) ) {
			unset( $this->settings[ $slug ] );
		}
	}

	/**
	 * Get the root setting.
	 *
	 * @return Setting
	 */
	public function get_root_setting() {
		if ( ! is_null( $this->root_setting ) ) {
			return $this->root_setting;
		}
		$parent = $this;
		if ( $this->has_parent() && ! $this->get_parent() instanceof Settings ) {
			$parent = $this->get_parent()->get_root_setting();
		}

		return $parent;
	}

	/**
	 * Set the root setting.
	 *
	 * @param Setting $setting The root setting to set.
	 */
	public function set_root_setting( $setting ) {
		$this->root_setting = $setting;
	}

	/**
	 * Check if this is the root setting.
	 *
	 * @return bool
	 */
	public function is_root_setting() {
		return $this === $this->get_root_setting();
	}

	/**
	 * Check if the setting has a capture component recursively.
	 *
	 * @return bool
	 */
	public function is_capture() {
		$capture = $this->get_component()->capture;
		if ( false === $capture && $this->has_settings() ) {
			foreach ( $this->get_settings() as $setting ) {
				if ( $setting->is_capture() ) {
					$capture = true;
					break;
				}
			}
		}

		return $capture;
	}
}
