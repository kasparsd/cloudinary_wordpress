<?php
/**
 * Column UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\UI\Component;

/**
 * Column Component to render components only.
 *
 * @package Cloudinary\UI
 */
class Column extends Component {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'wrap|settings/|/wrap';


	/**
	 * Filter the Wrap parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function wrap( $struct ) {

		$struct = parent::wrap( $struct );
		if ( $this->setting->has_param( 'width' ) ) {
			$struct['attributes']['style'] = 'width:' . $this->setting->get_param( 'width' ) . ';';
		}

		return $struct;
	}

}
