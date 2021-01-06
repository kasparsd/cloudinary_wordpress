/**
 * External dependencies
 */
import Dot from 'dot-object';
import cloneDeep from 'lodash/cloneDeep';

/**
 * Internal dependencies
 */

const dot = new Dot( '_' );

const Save = ( { attributes } ) => {
	let configString = '';

	if ( attributes.selectedImages.length ) {
		const attributesClone = cloneDeep( attributes );
		const { selectedImages, ...config } = dot.object( attributesClone, {} );

		if ( config?.displayProps?.mode !== 'classic' ) {
			delete config.transition;
		} else {
			delete config.displayProps.columns;
		}

		configString = JSON.stringify( {
			cloudName: CLDN.mloptions.cloud_name,
			mediaAssets: selectedImages,
			...config,
		} );
	}

	return (
		<div
			className={ attributes.container }
			data-cloudinary-gallery-config={ configString }
		></div>
	);
};

export default Save;
