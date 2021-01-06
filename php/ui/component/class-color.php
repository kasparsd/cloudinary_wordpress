<?php
/**
 * Color Field UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

/**
 * Class Color Component
 *
 * @package Cloudinary\UI
 */
class Color extends Text {

	/**
	 * Filter the input parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function input( $struct ) {
		$struct                       = parent::input( $struct );
		$struct['attributes']['type'] = 'text';

		return $struct;
	}

}
