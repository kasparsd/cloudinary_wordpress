<?php
/**
 * Image Preview UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\UI\Component;

/**
 * Image preview component.
 *
 * @package Cloudinary\UI
 */
class Image_Preview extends Component {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'wrap|notice/|preview_frame|title/|image/|refresh/|spinner/|/preview_frame|url_frame|url/|/url_frame|/wrap';

	/**
	 * Filter the notice parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function notice( $struct ) {

		$struct['element'] = 'span';

		return $struct;
	}

	/**
	 * Filter the preview_frame parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function preview_frame( $struct ) {
		$struct['element']               = 'div';
		$struct['attributes']['class'][] = 'cld-image-preview-wrapper';

		return $struct;
	}


	/**
	 * Filter the image parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function image( $struct ) {
		$struct['element']           = 'img';
		$struct['attributes']['src'] = 'https://res.cloudinary.com/demo/image/upload/sample.jpg';
		$struct['render']            = true;

		return $struct;
	}

	/**
	 * Filter the refresh parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function refresh( $struct ) {
		$struct['element']             = 'button';
		$struct['attributes']['type']  = 'button';
		$struct['attributes']['class'] = array(
			'button-primary',
			'global-transformations-button',
		);
		$struct['render']              = true;

		return $struct;
	}

	/**
	 * Filter the refresh parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function spinner( $struct ) {

		$struct['element']             = 'span';
		$struct['attributes']['id']    = 'image-loader';
		$struct['attributes']['class'] = array(
			'spinner',
			'global-transformations-spinner',
		);
		$struct['render']              = true;

		return $struct;
	}

	/**
	 * Filter the refresh parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function url_frame( $struct ) {
		$struct['element']               = 'div';
		$struct['attributes']['class'][] = 'cld-transformations-url';

		return $struct;
	}

	/**
	 * Filter the url parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function url( $struct ) {
		$struct['content'] = 'asd';

		return $struct;
	}
}
