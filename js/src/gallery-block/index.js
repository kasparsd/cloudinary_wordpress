/* global cloudinaryGalleryConfig */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import edit from './edit';
import save from './save';

const {
	aspectRatio,
	carouselLocation,
	carouselOffset,
	carouselThumbnailHeight,
	carouselThumbnailSelectedStyle,
	carouselThumbnailWidth,
	carouselButtonShape,
	zoomTrigger,
	zoomType,
	zoomViewerPosition,
	activeColor,
	onPrimaryColor,
	primaryColor,
	transition,
	carouselStyle,
} = cloudinaryGalleryConfig;

registerBlockType( 'cloudinary/gallery', {
	title: __( 'Cloudinary Gallery', 'cloudinary' ),
	description: __(
		'Add a gallery powered by the Cloudinary Gallery Widget to your post.',
		'cloudinary'
	),
	category: 'widgets',
	icon: 'format-gallery',
	attributes: {
		displayProps_mode: {
			type: 'string',
			default: 'classic',
		},
		displayProps_columns: {
			type: 'number',
			default: 1,
		},
		themeProps_primary: {
			type: 'string',
			default: primaryColor,
		},
		themeProps_onPrimary: {
			type: 'string',
			default: onPrimaryColor,
		},
		themeProps_active: {
			type: 'string',
			default: activeColor,
		},
		transition: {
			type: 'string',
			default: transition,
		},
		aspectRatio: {
			type: 'string',
			default: aspectRatio,
		},
		navigation: {
			type: 'string',
			default: 'always',
		},
		zoom: {
			type: 'boolean',
			default: zoomTrigger && zoomTrigger !== 'none',
		},
		zoomProps_type: {
			type: 'string',
			default: zoomType,
		},
		zoomProps_viewerPosition: {
			type: 'string',
			default: zoomViewerPosition,
		},
		zoomProps_trigger: {
			type: 'string',
			default: zoomTrigger !== 'none' ? zoomTrigger : 'click',
		},
		carouselLocation: {
			type: 'string',
			default: carouselLocation,
		},
		carouselOffset: {
			type: 'number',
			default: carouselOffset,
		},
		carouselStyle: {
			type: 'string',
			default: carouselStyle,
		},
		thumbnailProps_width: {
			type: 'number',
			default: carouselThumbnailWidth,
		},
		thumbnailProps_height: {
			type: 'number',
			default: carouselThumbnailHeight,
		},
		thumbnailProps_navigationShape: {
			type: 'string',
			default: carouselButtonShape,
		},
		thumbnailProps_selectedStyle: {
			type: 'string',
			default: carouselThumbnailSelectedStyle,
		},
		thumbnailProps_selectedBorderPosition: {
			type: 'string',
			default: 'all',
		},
		thumbnailProps_selectedBorderWidth: {
			type: 'number',
			default: 4,
		},
		thumbnailProps_mediaSymbolShape: {
			type: 'string',
			default: 'round',
		},
		indicatorProps_shape: {
			type: 'string',
			default: 'round',
		},
		selectedImages: {
			type: 'array',
			default: [],
		},
		container: {
			type: 'string',
		},
	},
	edit,
	save,
} );
