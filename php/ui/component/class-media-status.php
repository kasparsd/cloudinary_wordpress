<?php
/**
 * Media Status UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use function Cloudinary\get_plugin_instance;
use Cloudinary\UI\Component;
use Cloudinary\Settings\Setting;

/**
 * Media Sync Status Component to render plan status.
 *
 * @package Cloudinary\UI
 */
class Media_Status extends Component {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'title/|box_status/';

	/**
	 * Holds the plugin url.
	 *
	 * @var string
	 */
	protected $dir_url;

	/**
	 * Holds the media instance
	 *
	 * @var string
	 */
	protected $media;

	/**
	 * Holds the media sync data setting.
	 *
	 * @var string
	 */
	protected $data;

	/**
	 * Plan constructor.
	 *
	 * @param Setting $setting The parent Setting.
	 */
	public function __construct( $setting ) {
		$plugin        = get_plugin_instance();
		$this->media   = $plugin->get_component( 'media' );
		$this->dir_url = $plugin->dir_url;
		$this->data    = $this->media->get_settings()->get_setting( 'media_status' );

		parent::__construct( $setting );
	}

	/**
	 * Filter the title parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function title( $struct ) {
		$struct['element'] = 'h2';

		return parent::title( $struct );
	}

	/**
	 * Filter the plan box part structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function box_status( $struct ) {

		if ( $this->is_all_sync() ) {
			$message            = $this->get_part( 'span' );
			$message['content'] = __( 'All assets are synced', 'cloudinary' );

			$struct['element']             = 'div';
			$struct['attributes']['class'] = array(
				'notification-success',
				'dashicons-before',
				'dashicons-yes-alt',
			);
			$struct['children']['message'] = $message;

		} else {
			$title            = $this->get_part( 'h3' );
			$title['content'] = __( 'Media assets are synced to Cloudinary', 'cloudinary' );

			$icon                      = $this->get_part( 'icon' );
			$icon['element']           = 'img';
			$icon['attributes']['src'] = $this->dir_url . 'css/upload.svg';
			$icon['render']            = true;

			$status                        = $this->get_part( 'span' );
			$status['attributes']['class'] = array(
				'status',
			);
			$status['content']             = sprintf(
			// translators: number of synced media of all.
				__( '%1$d of %2$d', 'cloudinary' ),
				$this->get_media_to_synced(),
				$this->get_total_of_media()
			);

			$struct['element']             = 'div';
			$struct['attributes']['class'] = array(
				'media-status-box',
			);
			$struct['children']['icon']    = $icon;
			$struct['children']['title']   = $title;
			$struct['children']['status']  = $status;
		}

		return $struct;
	}

	/**
	 * Check if all media is synced.
	 *
	 * @return bool
	 */
	protected function is_all_sync() {
		return ! ( $this->get_media_to_synced() < $this->get_total_of_media() );
	}

	/**
	 * Get number of media items synced.
	 *
	 * @return int
	 */
	protected function get_media_to_synced() {
		// todo: implement logic.
		$data = $this->data->get_value();

		return count( $data['synced'] );
	}

	/**
	 * Get the total of media items registered in WordPress.
	 *
	 * @return int
	 */
	protected function get_total_of_media() {
		global $wpdb;

		$data = $this->data->get_value();

		$exclude = array_merge( $to_sync, $synced );
		$params  = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 10,
			'paged'          => 0,
			'post__not_in'   => $exclude,
		);

		$run   = true;
		$pages = null;

		while ( true === $run ) {

			$query = new \WP_Query( $params );
			$posts = $query->get_posts();
			if ( is_null( $pages ) ) {
				$pages = $query->max_num_pages;
			}

			$params['paged'] ++;
			if ( $params['paged'] > $pages ) {
				$run = false;
			}
			foreach ( $posts as $post ) {
				if ( $this->media->is_media( $post->ID ) && ! in_array( $post->ID, $data['to_sync'], true ) ) {
					$data['total'] ++;
					$type = 'to_sync';
					if ( $this->media->sync->is_synced( $post->ID ) ) {
						$type = 'synced';
					}
					$data[ $type ][] = $post->ID;
				}
			}
		}

		$this->data->save_value( $data );

		return $data['total'];
	}
}
