<?php
/**
 * Group UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\UI\Component;
use Cloudinary\Settings\Setting;

/**
 * Class Component
 *
 * @package Cloudinary\UI
 */
class Group extends Panel {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'wrap|header|icon/|title/|collapse/|/header|settings/|hr/|/wrap';


	/**
	 * Filter the HR parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function hr( $struct ) {

		$struct['render'] = true;

		return parent::hr( $struct );
	}

	/**
	 * Filter the title parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function title( $struct ) {

		$struct['element'] = 'h3';

		return $struct;
	}
}

