<?php
/**
 * On off UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

/**
 * Class On_Off Component
 *
 * @package Cloudinary\UI
 */
class On_Off extends Text {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'label|title|tooltip/|/title|prefix/|/label|wrap|control|false_value/|input/|slider/|/control|description/|/wrap';

	/**
	 * Filter the false_value parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function false_value( $struct ) {

		$struct['element']             = 'input';
		$struct['attributes']['type']  = 'hidden';
		$struct['attributes']['name']  = $this->get_name();
		$struct['attributes']['value'] = 'off';
		unset( $struct['attributes']['class'] );
		unset( $struct['attributes']['data-bound'] );
		$struct['render'] = true;

		return $struct;
	}

	/**
	 * Filter the input parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function input( $struct ) {

		$struct['element']             = 'input';
		$struct['attributes']['type']  = 'checkbox';
		$struct['attributes']['name']  = $this->get_name();
		$struct['attributes']['id']    = $this->setting->get_slug();
		$struct['attributes']['value'] = 'on';
		if ( 'on' === $this->setting->get_value() ) {
			$struct['attributes']['checked'] = 'checked';
		}
		$struct['attributes']['class'] = 'cld-ui-input';
		$struct['render']              = true;

		return $struct;
	}

	/**
	 * Filter the control parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function control( $struct ) {

		$struct['element']             = 'label';
		$struct['attributes']['class'] = array(
			'cld-input-' . $this->type . '-control',
		);

		return $struct;
	}

	/**
	 * Filter the slider parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function slider( $struct ) {
		$struct['element']             = 'span';
		$struct['render']              = true;
		$struct['attributes']['class'] = array(
			'cld-input-' . $this->type . '-control-slider',
		);

		return $struct;
	}

	/**
	 * Sanitize the value.
	 *
	 * @param string $value The value to sanitize.
	 *
	 * @return bool
	 */
	public function sanitize_value( $value ) {
		return 'on' === $value ? 'on' : 'off';
	}
}
