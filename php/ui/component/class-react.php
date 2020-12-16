<?php
/**
 * Frame UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\Media\Gallery;
use function Cloudinary\get_plugin_instance;

/**
 * Frame Component to render components only.
 *
 * @package Cloudinary\UI
 */
class React extends Text {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'input/|app/|template/|scripts/';


	/**
	 * Filter the app part structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function app( $struct ) {
		$struct['attributes']['id'] = 'app_' . $this->setting->get_slug();
		$struct['render']           = true;

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

		$struct                        = parent::input( $struct );
		$struct['attributes']['type']  = 'hidden';
		$struct['attributes']['value'] = $this->setting->get_value();

		// @todo Complete value output.

		return $struct;

	}

	/**
	 * Filter the template part structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function template( $struct ) {

		$struct['element'] = 'script';
		$struct['content'] = $this->setting->get_value();

		// @todo Complete template structures..

		return $struct;

	}

	/**
	 * Filter the script parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function scripts( $struct ) {

		$struct['element'] = null;
		if ( $this->setting->has_param( 'script' ) ) {
			$script_default = array(
				'handle'    => $this->setting->get_slug(),
				'src'       => '',
				'depts'     => array(),
				'ver'       => $this->setting->get_root_setting()->get_param( 'version' ),
				'in_footer' => true,
			);

			$script = wp_parse_args( $this->setting->get_param( 'script' ), $script_default );
			$asset  = $this->get_asset();

			// @todo Perhaps make the script param be an array or scripts to allow for multiples.
			$gallery = new Gallery( get_plugin_instance()->get_component( 'media' ) );
			$gallery->block_editor_scripts_styles();
			wp_enqueue_script( $script['slug'], $script['src'], $asset['dependencies'], $asset['version'], $script['in_footer'] );
		}

		return $struct;

	}

	private function get_asset() {
		$asset = require __DIR__ . '/../../../js/gallery.asset.php';

		$asset['dependencies'] = array_filter(
			$asset['dependencies'],
			static function ( $dependency ) {
				return false === strpos( $dependency, '/' );
			}
		);

		return $asset;
	}
}
