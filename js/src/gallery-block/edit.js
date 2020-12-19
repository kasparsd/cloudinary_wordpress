/* global cloudinaryGalleryApi */

/**
 * External dependencies
 */
import Dot from 'dot-object';
import cloneDeep from 'lodash/cloneDeep';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import '@wordpress/components/build-style/style.css';
import { useEffect, useState } from '@wordpress/element';
import { InspectorControls, MediaPlaceholder } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import '../../../css/src/gallery.scss';
import Controls from './controls';
import { ALLOWED_MEDIA_TYPES } from './options';
import { generateId, showNotice } from './utils';

const dot = new Dot( '_' );

const PLACEHOLDER_TEXT = __(
	'Drag images, upload new ones or select files from your library.',
	'cloudinary'
);

const Edit = ( { setAttributes, attributes, className, isSelected } ) => {
	const [ errorMessage, setErrorMessage ] = useState( null );

	const onSelect = ( images ) => {
		fetch( cloudinaryGalleryApi.endpoint, {
			method: 'POST',
			body: JSON.stringify( { images } ),
			headers: {
				'X-WP-Nonce': cloudinaryGalleryApi.nonce,
			},
		} )
			.then( ( res ) => res.json() )
			.then( ( selectedImages ) => setAttributes( { selectedImages } ) )
			.catch( () =>
				setErrorMessage(
					__(
						'Could not load selected images. Please try again.',
						'cloudinary'
					)
				)
			);
	};

	useEffect( () => {
		if ( errorMessage ) {
			showNotice( { status: 'error', message: errorMessage } );
			setErrorMessage( null );
		}

		if ( attributes.selectedImages.length ) {
			const attributesClone = cloneDeep( attributes );
			const { selectedImages, ...config } = dot.object(
				attributesClone,
				{}
			);

			if ( config.displayProps.mode !== 'classic' ) {
				delete config.transition;
			} else {
				delete config.displayProps.columns;
			}

			if ( ! attributes.container ) {
				setAttributes( {
					container: `${ className }${ generateId( 15 ) }`,
				} );
			}

			const gallery = cloudinary.galleryWidget( {
				cloudName: CLDN.mloptions.cloud_name,
				...config,
				mediaAssets: selectedImages,
				container: '.' + attributes.container,
				zoom: false,
			} );

			gallery.render();

			return () => gallery.destroy();
		}
	}, [ errorMessage, attributes, setAttributes, className ] );

	const hasImages = !! attributes.selectedImages.length;

	return (
		<>
			<>
				<div className={ attributes.container || className }></div>
				<div className="wp-block-cloudinary-gallery">
					<MediaPlaceholder
						labels={ {
							title:
								! hasImages &&
								__( 'Cloudinary Gallery', 'cloudinary' ),
							instructions: ! hasImages && PLACEHOLDER_TEXT,
						} }
						icon="format-gallery"
						disableMediaButtons={ hasImages && ! isSelected }
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						addToGallery={ hasImages }
						isAppender={ hasImages }
						onSelect={ onSelect }
						multiple
					/>
				</div>
			</>
			<InspectorControls>
				<Controls
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
		</>
	);
};

export default Edit;
