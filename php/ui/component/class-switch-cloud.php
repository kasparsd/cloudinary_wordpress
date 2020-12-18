<?php
/**
 * Switch Cloud UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\UI\Component;

/**
 * Connect Link Component.
 *
 * @package Cloudinary\UI
 */
class Switch_Cloud extends Submit {

	/**
	 * Filter the link parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function submit_button( $struct ) {

		$url = add_query_arg(
			array(
				'page'           => 'dashboard',
				'tab'            => 'connect',
				'switch-account' => true,
			),
			admin_url( 'admin.php' )
		);

		$struct['element']             = 'a';
		$struct['content']             = __( 'Switch Cloud', 'cloudinary' );
		$struct['attributes']['href']  = $url;
		$struct['attributes']['class'] = array(
			'button',
			'button-primary',
		);

		return $struct;
	}
}
