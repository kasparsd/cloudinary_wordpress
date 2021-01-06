<?php
/**
 * Manages Gallery Widget and Block settings.
 *
 * @package Cloudinary
 */

namespace Cloudinary\Media;

/**
 * Class WooCommerceGallery.
 *
 * Handles gallery for woo.
 */
class WooCommerceGallery {
	/**
	 * The gallery instance.
	 *
	 * @var Gallery
	 */
	private $gallery;

	/**
	 * Init woo gallery.
	 *
	 * @param Gallery $gallery Gallery instance.
	 */
	public function __construct( Gallery $gallery ) {
		$this->gallery = $gallery;

		if ( $this->woocommerce_active() && $this->gallery->gallery_enabled() ) {
			$this->setup_hooks();
		}
	}

	/**
	 * Register frontend assets for the gallery.
	 */
	public function enqueue_gallery_library() {
		$product = wc_get_product();
		$assets  = $product ? $this->gallery->get_image_data( $product->get_gallery_image_ids() ) : null;

		if ( $assets ) {
			$json_assets = wp_json_encode( $assets );
			wp_add_inline_script( Gallery::GALLERY_LIBRARY_HANDLE, "cloudinaryGalleryConfig.mediaAssets = JSON.parse( '{$json_assets}' );" );
		}
	}

	/**
	 * Check if WooCommerce is active.
	 *
	 * @return bool
	 */
	protected function woocommerce_active() {
		return class_exists( 'WooCommerce' ) && function_exists( 'wc_get_product' );
	}

	/**
	 * Setup hooks for the gallery.
	 */
	public function setup_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_gallery_library' ) );

		if ( ! is_admin() && $this->woocommerce_active() ) {
			add_filter( 'woocommerce_single_product_image_thumbnail_html', '__return_empty_string' );
		}
	}
}
