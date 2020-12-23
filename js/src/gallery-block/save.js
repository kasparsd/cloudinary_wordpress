/**
 * Internal dependencies
 */
import { setupAttributesForRendering, sortObject } from './utils';

const Save = ( { attributes } ) => {
	let configString = '';

	if ( attributes.selectedImages.length ) {
		const { mediaAssets, config } = setupAttributesForRendering(
			attributes
		);

		configString = JSON.stringify( {
			cloudName: CLDN.mloptions.cloud_name,
			...sortObject( config ),
			mediaAssets,
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
