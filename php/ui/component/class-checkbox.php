<?php
/**
 * Checkbox Field UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

/**
 * Class Number Component
 *
 * @package Cloudinary\UI
 */
class Checkbox extends Radio {

	/**
	 * Get the field name.
	 *
	 * @return string
	 */
	protected function get_name() {
		return parent::get_name() . '[]';
	}

}
