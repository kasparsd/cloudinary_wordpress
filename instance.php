<?php
/**
 * Instantiates the Cloudinary plugin
 *
 * @package Cloudinary
 */

namespace Cloudinary;

global $cloudinary_plugin;

define( 'CLDN_ASSET_DEBUG', ! defined( 'DEBUG_SCRIPTS' ) ? '.min' : '' );

require_once __DIR__ . '/php/class-plugin.php';

$cloudinary_plugin = new Plugin();

/**
 * Cloudinary Plugin Instance
 *
 * @return Plugin
 */
function get_plugin_instance() {
	global $cloudinary_plugin;

	return $cloudinary_plugin;
}
