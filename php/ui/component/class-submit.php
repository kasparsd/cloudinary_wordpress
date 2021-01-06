<?php
/**
 * Submit UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\UI\Component;

/**
 * Class Component
 *
 * @package Cloudinary\UI
 */
class Submit extends Component {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'wrap|submit_button/|settings/|/wrap';

	/**
	 * Filter the link parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function submit_button( $struct ) {

		$struct['element']             = 'button';
		$struct['content']             = $this->setting->get_param( 'label', __( 'Save Changes', 'cloudinary' ) );
		$struct['attributes']['type']  = $this->type;
		$struct['attributes']['class'] = array(
			'button',
			'button-primary',
		);

		return $struct;
	}
}
