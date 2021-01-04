<?php
/**
 * Cloudinary CLI.
 *
 * @package Cloudinary
 */

namespace Cloudinary;

/**
 * CLI class.
 */
class CLI {

	/**
	 * Holds the plugin instance.
	 *
	 * @since   0.1
	 *
	 * @var     Plugin Instance of the global plugin.
	 */
	public $plugin;

	/**
	 * CLI constructor.
	 *
	 * @param \Cloudinary\Plugin $plugin The plugin instance.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

	}

	/**
	 * Syncs assets with Cloudinary.
	 *
	 * ## OPTIONS
	 * [--assets=<assets>]
	 * : Optional list of assets, comma separated.
	 *
	 * ## EXAMPLES
	 *
	 *     wp cloudinary sync
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Ignored.
	 * @param array $assoc_args Passed parameters.
	 */
	public function sync( $args, $assoc_args ) {

		\WP_CLI::log( '' );
		\WP_CLI::log( '╔═╗┬  ┌─┐┬ ┬┌┬┐┬┌┐┌┌─┐┬─┐┬ ┬  ╔═╗╦  ╦' );
		\WP_CLI::log( '║  │  │ ││ │ ││││││├─┤├┬┘└┬┘  ║  ║  ║' );
		\WP_CLI::log( '╚═╝┴─┘└─┘└─┘─┴┘┴┘└┘┴ ┴┴└─ ┴   ╚═╝╩═╝╩' );

		$assets = null;
		if ( ! empty( $assoc_args['assets'] ) ) {
			$assets = explode( ',', $assoc_args['assets'] );
			$assets = array_map( 'trim', $assets );
		}
		$assets = $this->get_all_unsynced( $assets );

		if ( empty( $assets ) ) {
			return;
		}
		$total = count( $assets );
		\WP_CLI::log( '' );
		\WP_CLI::log( \WP_CLI::colorize( '%gStarting Sync:%n' ) );
		$bar = \WP_CLI\Utils\make_progress_bar( 'Syncing ' . $total . ' assets', $total, 10 );
		foreach ( $assets as $index => $asset ) {
			$file = get_attached_file( $asset );
			$bar->tick( 0, 'Syncing: ' . basename( $file ) . ' (' . ( $index + 1 ) . ' of ' . $total . ')' );
			$this->plugin->get_component( 'sync' )->managers['push']->process_assets( $asset, $bar );
			$bar->tick();
		}
		$bar->tick( 0, 'Sync Completed.' );
		$bar->finish();
		$this->get_all_unsynced();
		\WP_CLI::success( 'Assets all synced' );
	}

	/**
	 * Allocate unsynced items to the queue threads.
	 *
	 * @param array|bool $include Include already found items.
	 *
	 * @return array
	 */
	private function get_all_unsynced( $include = false ) {

		$params = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'fields'         => 'ids',
			'posts_per_page' => - 1,
		);

		if ( ! empty( $include ) ) {
			$params['post__in'] = $include;
		}
		$ids = array();

		$query = new \WP_Query( $params );
		$posts = $query->get_posts();
		\WP_CLI::log( '' );
		\WP_CLI::log( \WP_CLI::colorize( '%yAnalysing assets:%n' ) );
		$bar  = \WP_CLI\Utils\make_progress_bar( '', $query->found_posts, 10 );
		$info = array(
			'_cld_unsupported' => 0,
			'_cld_synced'      => 0,
			'_cld_unsynced'    => 0,
		);
		foreach ( $posts as $index => $post ) {
			delete_post_meta( $post, '_cld_unsupported', true );
			delete_post_meta( $post, '_cld_synced', true );
			delete_post_meta( $post, '_cld_unsynced', true );
			$key = '_cld_unsupported';
			if ( $this->plugin->get_component( 'media' )->is_media( $post ) ) {
				// Add a key.
				$key = '_cld_synced';
				if ( ! $this->plugin->get_component( 'sync' )->is_synced( $post ) ) {
					$key   = '_cld_unsynced';
					$ids[] = $post;
				}
			}
			$info[ $key ] ++;
			add_post_meta( $post, $key, true, true );
			$bar->tick( 1, $index . ' of ' . $query->found_posts . ' |' );
		}
		$bar->tick( 0, ' ' );
		$bar->finish();

		\WP_CLI::success( $query->found_posts . ' analysed.' );
		\WP_CLI::log( '' );
		\WP_CLI::log( \WP_CLI::colorize( '%gSynced%n      :' ) . ' ' . $info['_cld_synced'] );
		\WP_CLI::log( \WP_CLI::colorize( '%yUn-synced%n   :' ) . ' ' . $info['_cld_unsynced'] );
		\WP_CLI::log( \WP_CLI::colorize( '%rUnsupported%n :' ) . ' ' . $info['_cld_unsupported'] );
		\WP_CLI::log( '' );
		if ( ! empty( $info['_cld_unsynced'] ) ) {
			\WP_CLI::confirm( 'Sync ' . count( $ids ) . ' assets?' );
		} else {
			return;
		}

		return $ids;
	}
}
