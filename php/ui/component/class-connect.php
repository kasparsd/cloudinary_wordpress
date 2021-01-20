<?php
/**
 * Connect UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use function Cloudinary\get_plugin_instance;
use Cloudinary\UI\Component;
use Cloudinary\Settings\Setting;

/**
 * Connect Component.
 *
 * @package Cloudinary\UI
 */
class Connect extends Component {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'connect';

	/**
	 * Holder the Connect object.
	 *
	 * @var Connect
	 */
	protected $connect;

	/**
	 * Plan constructor.
	 *
	 * @param Setting $setting The parent Setting.
	 */
	public function __construct( $setting ) {
		$plugin = get_plugin_instance();

		$this->connect = $plugin->get_component( 'connect' );
		$this->capture = true;

		parent::__construct( $setting );
	}

	/**
	 * Filter the connected message.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function connect( array $struct ) {
		$message            = $this->get_part( 'span' );
		$message['content'] = sprintf(
			// translators: The connected cloud name.
			__( '%s Connected to Cloudinary', 'cloudinary' ),
			$this->connect->get_cloud_name()
		);

		$struct['attributes']['class'] = array(
			'notification',
			'notification-success',
			'dashicons-before',
			'dashicons-yes-alt',
		);
		$struct['children']['message'] = $message;

		return $struct;
	}
}

