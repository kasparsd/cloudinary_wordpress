<?php
/**
 * Cloudinary CLI.
 *
 * @package Cloudinary
 */

namespace Cloudinary;

use Cloudinary\Plugin;

/**
 * CLI class.
 *
 * @since   2.5.1
 */
class CLI {

	/**
	 * Holds the plugin instance.
	 *
	 * @since   2.5.1
	 *
	 * @var     Plugin Instance of the global plugin.
	 */
	public $plugin;

	/**
	 * Holds the base query args.
	 *
	 * @since   2.5.1
	 *
	 * @var array
	 */
	protected $base_query_args = array(
		'post_type'              => 'attachment',
		'post_status'            => 'inherit',
		'fields'                 => 'ids',
		'posts_per_page'         => 100,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'paged'                  => 1,
	);

	/**
	 * CLI constructor.
	 *
	 * @since   2.5.1
	 *
	 * @param Plugin $plugin The plugin instance.
	 */
	public function __construct( $plugin ) {
		\WP_CLI::log( '' );
		\WP_CLI::log( '╔═╗┬  ┌─┐┬ ┬┌┬┐┬┌┐┌┌─┐┬─┐┬ ┬  ╔═╗╦  ╦' );
		\WP_CLI::log( '║  │  │ ││ │ ││││││├─┤├┬┘└┬┘  ║  ║  ║' );
		\WP_CLI::log( '╚═╝┴─┘└─┘└─┘─┴┘┴┘└┘┴ ┴┴└─ ┴   ╚═╝╩═╝╩' );
		$this->plugin = $plugin;
	}

	/**
	 * Syncs assets with Cloudinary.
	 * ## EXAMPLES
	 *
	 *     Wp cloudinary sync
	 *
	 * @when    after_wp_load
	 * @since   2.5.1
	 *
	 * @param array $args       Ignored.
	 * @param array $assoc_args Ignored.
	 *
	 * @return void
	 */
	public function sync( $args, $assoc_args ) {

		// Check if analyzed first.
		if ( empty( get_option( '_cld_cli_analyzed' ) ) ) {
			$this->analyze();
		}

		// Initial Query.
		$query_args = $this->base_query_args;
		// phpcs:ignore WordPress.DB.SlowDBQuery
		$query_args['meta_query'] = array(
			'AND',
			array(
				'key'     => '_cld_unsynced',
				'compare' => 'EXISTS',
			),
		);

		// Get assets that need to be synced.
		$query = new \WP_Query( $query_args );
		$this->do_process( $query, 'sync' );
		if ( ! $query->have_posts() ) {
			\WP_CLI::log( \WP_CLI::colorize( '%gAll assets synced.%n' ) );
		}

	}

	/**
	 * Analyze assets with Cloudinary.
	 * ## EXAMPLES
	 *
	 *     Wp cloudinary analyze
	 *
	 * @when    after_wp_load
	 * @since   2.5.1
	 *
	 * @return void
	 */
	public function analyze() {

		// Initial query.
		$query_args = $this->base_query_args;
		$query = new \WP_Query( $query_args );

		// Kill all _cld_ related meta.
		delete_post_meta_by_key( '_cld_unsynced' );
		delete_option( '_cld_cli_analyzed' );

		// Do process.
		$this->do_process( $query, 'analyze' );
	}

	/**
	 * Do a process on the query.
	 *
	 * @since   2.5.1
	 *
	 * @param \WP_Query $query   The initial query object.
	 * @param string    $process The process to do.
	 */
	protected function do_process( &$query, $process ) {
		// Bail early.
		if ( ! method_exists( $this, "process_{$process}" ) ) {
			\WP_CLI::log( \WP_CLI::colorize( "%Invalid Process: {$process}.%n" ) );

			return;
		}
		if ( method_exists( $this, $process ) ) {
			// Setup process.
			$total   = $query->found_posts;
			$process = "process_{$process}";
			do {
				$posts = $query->get_posts();
				$this->{$process}( $posts, $total );

				// Free up memory.
				if ( method_exists( $this, 'stop_the_insanity' ) ) {
					$this->stop_the_insanity();
				}

				// Paginate.
				$query_args = $query->query_vars;
				$query_args['paged'] ++;
				$query = new \WP_Query( $query_args );
			} while ( $query->have_posts() );
		}
		\WP_CLI::line( '' );
	}

	/**
	 * Sync Assets.
	 *
	 * @param array $posts Array of Post IDs to process.
	 * @param int   $total Count of total posts to process.
	 */
	protected function process_sync( $posts, $total ) {
		static $bar, $done;
		if ( ! $bar && ! empty( $posts ) ) {
			\WP_CLI::log( \WP_CLI::colorize( '%gSyncing assets%n' ) );
			$bar  = \WP_CLI\Utils\make_progress_bar( 'Syncing ' . $total . ' assets', $total, 10 );
			$done = 0;
		}
		foreach ( $posts as $index => $asset ) {
			$done ++; // Set $done early to not show 0 of x.
			$file = get_attached_file( $asset );
			$bar->tick( 0, 'Syncing: ' . basename( $file ) . ' (' . ( $done ) . ' of ' . $total . ')' );
			if ( ! $this->plugin->get_component( 'sync' )->is_synced( $asset ) ) {
				$this->plugin->get_component( 'sync' )->managers['push']->process_assets( $asset, $bar );
			}
			delete_post_meta( $asset, '_cld_unsynced', true );
			$bar->tick();
		}
		// Done message - reanalyze.
		if ( $done === $total ) {
			$bar->tick( 0, 'Sync Completed.' );
			$bar->finish();
			$bar = null;
			\WP_CLI::line( '' );
			$this->analyze();
			delete_option( '_cld_cli_analyzed' );
		}
	}

	/**
	 * Analyze and mark assets that need to be synced.
	 *
	 * @since   2.5.1
	 *
	 * @param array $posts Array of Post IDs to process.
	 * @param int   $total Count of total posts to process.
	 */
	protected function process_analyze( $posts, $total ) {
		static $bar, $done, $info;

		if ( ! $bar ) {
			\WP_CLI::log( \WP_CLI::colorize( '%gAnalyzing ' . $total . ' assets:%n' ) );
			$bar  = \WP_CLI\Utils\make_progress_bar( '', $total, 10 );
			$done = 0;
			$info = array(
				'_cld_unsupported' => 0,
				'_cld_synced'      => 0,
				'_cld_unsynced'    => 0,
			);
		}
		foreach ( $posts as $index => $asset ) {
			$done ++;
			$key = '_cld_unsupported';
			if ( $this->plugin->get_component( 'media' )->is_media( $asset ) ) {
				// Add a key.
				$key = '_cld_synced';
				if ( ! $this->plugin->get_component( 'sync' )->is_synced( $asset ) ) {
					$key = '_cld_unsynced';
					add_post_meta( $asset, $key, true, true );
				}
			}
			$info[ $key ] ++;
			$bar->tick( 1, $done . ' of ' . $total . ' |' );
		}
		// Done message.
		if ( $done === $total ) {
			$bar->tick( 0, $total . ' Analyzed |' );
			$bar->finish();
			$bar = null;
			\WP_CLI::log( '' );
			\WP_CLI::log( \WP_CLI::colorize( '%gSynced%n      :' ) . ' ' . $info['_cld_synced'] );
			\WP_CLI::log( \WP_CLI::colorize( '%yUn-synced%n   :' ) . ' ' . $info['_cld_unsynced'] );
			\WP_CLI::log( \WP_CLI::colorize( '%rUnsupported%n :' ) . ' ' . $info['_cld_unsupported'] );
			update_option( '_cld_cli_analyzed', true, false );
		}
	}
}
