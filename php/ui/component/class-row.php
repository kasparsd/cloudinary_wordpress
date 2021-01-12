<?php
/**
 * Row UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\UI\Component;

/**
 * Row Component to render components only.
 *
 * @package Cloudinary\UI
 */
class Row extends Component {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'wrap|settings/|/wrap';

}
