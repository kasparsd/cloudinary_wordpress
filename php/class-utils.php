<?php
/**
 * Utilities for Cloudinary.
 *
 * @package Cloudinary
 */

namespace Cloudinary;

/**
 * Class that includes utility methods.
 *
 * @package Cloudinary
 */
class Utils {
	/**
	 * Detects array keys with dot notation and expands them to form a new multi-dimensional array.
	 *
	 * @param  array $input The array that will be processed.
	 *
	 * @return array
	 */
	public static function expand_dot_notation( array $input ) {
		$result = array();
		foreach ( $input as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = self::expand_dot_notation( $value );
			}

			foreach ( array_reverse( explode( '.', $key ) ) as $inner_key ) {
				$value = array( $inner_key => $value );
			}

			// phpcs:disable Generic.Commenting.DocComment.MissingShort
			/** @noinspection SlowArrayOperationsInLoopInspection */
			// phpcs:enable Generic.Commenting.DocComment.MissingShort
			$result = array_merge_recursive( $result, $value );
		}

		return $result;
	}

	/**
	 * Filter an array recursively
	 *
	 * @param array         $input    The array to filter.
	 * @param callable|null $callback The callback to run for filtering.
	 *
	 * @return array
	 */
	public static function array_filter_recursive( array $input, $callback = null ) {
		foreach ( $input as &$value ) {
			if ( is_array( $value ) ) {
				$value = self::array_filter_recursive( $value, $callback );
			}
		}

		return array_filter( $input, $callback );
	}
}
