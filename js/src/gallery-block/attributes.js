/* global cloudinaryGalleryConfig */

import Dot from 'dot-object';

const dot = new Dot( '_' );

const blockAttributes = {};
const flattenedConfig = dot.dot( cloudinaryGalleryConfig );

Object.keys( flattenedConfig ).forEach( ( key ) => {
	blockAttributes[ key ] = {
		type: typeof flattenedConfig[ key ],
		default: flattenedConfig[ key ],
	};
} );

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

export default blockAttributes;
