<?php
/**
 * Sync Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use function Cloudinary\get_plugin_instance;

/**
 * Sync Component to hold data.
 *
 * @package Cloudinary\UI
 */
class Sync extends Text {

	/**
	 * Filter the input parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function input( $struct ) {

		$to_sync = $this->count_to_sync();
		if ( empty( $to_sync ) ) {

			$message            = $this->get_part( 'span' );
			$message['content'] = __( 'All assets are synced', 'cloudinary' );

			$struct['element']             = 'div';
			$struct['attributes']['class'] = array(
				'notification-success',
				'dashicons-before',
				'dashicons-yes-alt',
			);
			$struct['children']['message'] = $message;

			return $struct;
		}

		$struct['element'] = 'a';
		$href              = $this->setting->find_setting( 'sync_media' )->get_component()->get_url();
		$args              = array();
		if ( ! $this->setting->get_param( 'queue' )->is_enabled() ) {
			$args['enable-bulk'] = true;
			$struct['content']   = $this->setting->get_param( 'enable_text', __( 'Sync Now', 'cloudinary' ) );
		} else {
			$args['disable-bulk'] = true;
			$struct['content']    = $this->setting->get_param( 'disable_text', __( 'Stop Sync', 'cloudinary' ) );
		}

		$struct['attributes']['class'][] = 'button';
		if ( 'off' === $this->setting->find_setting( 'auto_sync' )->get_value() ) {
			$struct['attributes']['disabled'] = 'disabled';
		} else {
			$href                         = add_query_arg( $args, $href );
			$struct['attributes']['href'] = $href;
		}
		$struct['render'] = true;

		return $struct;
	}

	/**
	 * Filter the suffix part structure.
	 *
	 * @param array $struct The part structure.
	 *
	 * @return array
	 */
	protected function suffix( $struct ) {

		if ( $this->setting->get_param( 'queue' )->is_enabled() ) {
			$struct['element']             = 'span';
			$struct['attributes']['class'] = 'cld-syncing';
			$struct['render']              = true;
		}

		return $struct;
	}

	/**
	 * Filter the label parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function label( $struct ) {
		$struct = parent::label( $struct );

		if ( 'off' === $this->setting->find_setting( 'auto_sync' )->get_value() ) {
			$struct['attributes']['class'][] = 'disabled';
		}

		return $struct;
	}

	/**
	 * Filter the tooltip parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function tooltip( $struct ) {
		$param = 'tooltip_on';
		if ( 'off' === $this->setting->find_setting( 'auto_sync' )->get_value() ) {
			$param = 'tooltip_off';
		}
		$this->setting->set_param( 'tooltip_text', $this->setting->get_param( $param ) );

		return parent::tooltip( $struct );
	}

	/**
	 * Get the total of unsynced assets.
	 *
	 * @return int
	 */
	protected function count_to_sync() {
		$params = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'fields'         => 'ids',
			'post_mime_type' => array( 'image', 'video' ),
			'posts_per_page' => 1,
			'meta_query'     => array( // phpcs:ignore
				array(
					'key'     => \Cloudinary\Sync::META_KEYS['public_id'],
					'compare' => 'NOT EXISTS',
				),

			),
		);
		$query = new \WP_Query( $params );

		return $query->found_posts;
	}
}
