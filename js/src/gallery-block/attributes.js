/* global cloudinaryGalleryConfig cloudinaryPostContent */

import Dot from 'dot-object';
import { toBlockAttributes } from './utils';

const dot = new Dot( '_' );

let blockAttributes = toBlockAttributes( dot.dot( cloudinaryGalleryConfig ) );

blockAttributes.selectedImages = {
	type: 'array',
	default: [],
};

if ( ! ( 'displayProps_columns' in blockAttributes ) ) {
	blockAttributes.displayProps_columns = {
		type: 'number',
		default: 1,
	};
}

if ( cloudinaryPostContent ) {
	const htmlDoc = new DOMParser().parseFromString(
		cloudinaryPostContent,
		'text/html'
	);

	const configEl = htmlDoc.querySelector(
		'[data-cloudinary-gallery-config]'
	);
	let currentConfig = '';

	if ( configEl ) {
		currentConfig = configEl.getAttribute(
			'data-cloudinary-gallery-config'
		);
	}

	if ( currentConfig ) {
		currentConfig = JSON.parse( currentConfig );
		delete currentConfig.mediaAssets;
		currentConfig = toBlockAttributes( dot.dot( currentConfig ) );

		blockAttributes = { ...blockAttributes, ...currentConfig };
	}
}

export default blockAttributes;
