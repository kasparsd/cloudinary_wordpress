<?php
/**
 * Utilities for Cloudinary.
 *
 * @package Cloudinary
 */

namespace Cloudinary;

use Cloudinary\Settings\Setting;

/**
 * Class that includes utility methods.
 *
 * @package Cloudinary
 */
class Utils {

	/**
	 * Filter an array recursively
	 *
	 * @param array         $input    The array to filter.
	 * @param callable|null $callback The callback to run for filtering.
	 *
	 * @return array
	 */
	public static function array_filter_recursive( array $input, $callback = null ) {
		// PHP array_filter does this, so we'll do it too.
		if ( null === $callback ) {
			$callback = static function ( $item ) {
				return ! empty( $item );
			};
		}

		foreach ( $input as &$value ) {
			if ( is_array( $value ) ) {
				$value = self::array_filter_recursive( $value, $callback );
			}
		}

		return array_filter( $input, $callback );
	}

	/**
	 * Gets the active child setting.
	 *
	 * @return Setting
	 */
	public static function get_active_setting() {
		$settings = get_plugin_instance()->settings;
		$setting  = $settings->get_param( 'active_setting', $settings );
		if ( $setting->has_param( 'has_tabs' ) ) {
			$setting = $setting->get_param( 'active_tab', $setting );
		}

		return $setting;
	}
}
