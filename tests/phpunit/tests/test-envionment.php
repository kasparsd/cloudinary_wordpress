<?php
/**
 * Sample PHPUnit test case.
 *
 * @package Cloudinary
 */

/**
 * Sample phpunit test case.
 */
class Environment extends WP_UnitTestCase {

	/**
	 * Ensure that core WordPress APIs are available.
	 */
	public function test_wordpress_and_plugin_are_loaded() {
		$this->assertTrue( function_exists( 'do_action' ) );
	}

}
