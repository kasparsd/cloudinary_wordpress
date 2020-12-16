import React from 'react';
import Dot from 'dot-object';
import cloneDeep from 'lodash/cloneDeep';
import { render, useEffect, useState } from '@wordpress/element';
import attributes from '../gallery-block/attributes';
import GalleryControls from '../gallery-block/controls';

const dot = new Dot( '_' );

const StatefulGalleryControls = () => {
	const [ statefulAttrs, setStatefulAttrs ] = useState( {} );

	const setAttributes = ( attrs ) => {
		setStatefulAttrs( {
			...statefulAttrs,
			...attrs,
		} );
	};

	Object.keys( attributes ).forEach( ( attr ) => {
		if ( ! ( attr in statefulAttrs ) ) {
			setAttributes( { [ attr ]: attributes[ attr ].default } );
		}
	} );

	useEffect( () => {
		const attributesClone = cloneDeep( statefulAttrs );
		const config = dot.object( attributesClone, {} );

		if ( config.displayProps.mode !== 'classic' ) {
			delete config.transition;
		} else {
			delete config.displayProps.columns;
		}

		const gallery = cloudinary.galleryWidget( {
			cloudName: 'demo',
			mediaAssets: [
				{
					tag: 'shoes_product_gallery_demo',
					mediaType: 'image',
				},
			],
			...config,
			container: '.gallery-preview',
		} );

		gallery.render();

		return () => gallery.destroy();
	} );

	return (
		<div className="cld-gallery-settings">
			<div className="interface-interface-skeleton__sidebar cld-gallery-settings__column">
				<div className="interface-complementary-area edit-post-sidebar">
					<div className="components-panel">
						<div className="block-editor-block-inspector">
							<GalleryControls
								attributes={ statefulAttrs }
								setAttributes={ setAttributes }
							/>
						</div>
					</div>
				</div>
			</div>
			<div className="gallery-preview cld-gallery-settings__column"></div>
		</div>
	);
};

render( <StatefulGalleryControls />, document.getElementById( 'app_gallery' ) );
