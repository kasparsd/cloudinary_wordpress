<?php
/**
 * Interface for settings based classes.
 *
 * @package Cloudinary
 */

namespace Cloudinary\Component;

use Cloudinary\Settings\Setting;

/**
 * Defines an object that requires settings.
 */
interface Settings {

	/**
	 * Register Settings.
	 */
	public function register_settings();

	/**
	 * Init Settings Object.
	 *
	 * @param Setting $setting The core setting.
	 */
	public function init_settings( $setting );
}
