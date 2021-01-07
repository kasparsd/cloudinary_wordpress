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
	protected $blueprint = 'input/|app/|scripts/';

	/**
	 * Filter the app part structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function app( $struct ) {
		$struct['attributes']['id'] = 'app_gallery_' . $this->setting->get_slug();
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
		$struct                       = parent::input( $struct );
		$struct['attributes']['id']   = 'gallery_settings_input';
		$struct['attributes']['type'] = 'hidden';

		$struct['attributes']['value'] = $this->setting->get_value();

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

			$color_palette = wp_json_encode( current( (array) get_theme_support( 'editor-color-palette' ) ) );

			$script = wp_parse_args( $this->setting->get_param( 'script' ), $script_default );
			$asset  = $this->get_asset();

			$gallery = new Gallery( get_plugin_instance()->get_component( 'media' ) );
			$gallery->block_editor_scripts_styles();
			wp_enqueue_script( $script['slug'], $script['src'], $asset['dependencies'], $asset['version'], $script['in_footer'] );
			wp_add_inline_script( $script['slug'], "var CLD_THEME_COLORS = JSON.parse( '$color_palette' );", 'before' );
		}

		return $struct;
	}

	/**
	 * Retrieve asset dependencies.
	 *
	 * @return array
	 */
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
