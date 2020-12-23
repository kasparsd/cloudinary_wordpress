<?php
/**
 * Panel UI Component.
 *
 * @package Cloudinary
 */

namespace Cloudinary\UI\Component;

use Cloudinary\UI\Component;
use Cloudinary\Settings\Setting;

/**
 * Class Component
 *
 * @package Cloudinary\UI
 */
class Panel extends Component {

	/**
	 * Holds the components build blueprint.
	 *
	 * @var string
	 */
	protected $blueprint = 'header|icon/|title/|collapse/|/header|wrap|body|/body|section/|/wrap';

	/**
	 * Filter the header parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function header( $struct ) {

		$struct['attributes']['class'][] = 'cld-' . $this->type . '-heading';

		return parent::header( $struct );
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
		if ( $this->setting->has_param( 'collapsible' ) ) {
			$struct['attributes']['class'][]  = 'collapsible';
			$struct['attributes']['data-for'] = $this->setting->get_slug();
		}

		return $struct;
	}


	/**
	 * Filter the collapsible parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function collapse( $struct ) {

		if ( $this->setting->has_param( 'collapsible' ) ) {
			$struct['element']                   = 'span';
			$struct['render']                    = true;
			$struct['attributes']['class'][]     = 'dashicons';
			$state                               = $this->setting->get_param( 'collapsible' );
			$struct['attributes']['class'][]     = 'open' === $state ? 'dashicons-arrow-up-alt2' : 'dashicons-arrow-down-alt2';
			$struct['attributes']['data-toggle'] = $this->setting->get_slug();
			$struct['attributes']['id']          = $this->setting->get_slug();
		}

		return $struct;
	}

	/**
	 * Filter the wrap parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function wrap( $struct ) {

		if ( $this->setting->has_param( 'title' ) ) {
			$struct['attributes']['class'][] = 'has-heading';

			if ( $this->setting->has_param( 'collapsible' ) ) {
				$struct['attributes']['class'][]   = $this->setting->get_param( 'collapsible' );
				$struct['attributes']['data-wrap'] = $this->setting->get_slug();
			}
		}

		return parent::wrap( $struct );
	}

	/**
	 * Gets the active child setting.
	 *
	 * @return Setting
	 */
	protected function get_active_setting() {
		$active_setting = '_tmp_fallback';
		if ( $this->setting->has_settings() ) {
			$active_setting = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			if ( ! $this->setting->has_setting( $active_setting ) ) { // Tab is invalid or not set, check if in a POST.
				$active_setting = filter_input( INPUT_POST, 'tab', FILTER_SANITIZE_STRING );
				if ( ! $this->setting->has_setting( $active_setting ) ) { // Tab is invalid or not set, load the default/first tab.
					$settings       = $this->setting->get_setting_slugs();
					$active_setting = array_shift( $settings );
				}
			}
		}

		return $this->setting->get_setting( $active_setting );
	}

	/**
	 * Filter the section parts structure.
	 *
	 * @param array $struct The array structure.
	 *
	 * @return array
	 */
	protected function section( $struct ) {
		$struct            = parent::settings( $struct );
		$struct['element'] = null;

		return $struct;
	}
}

