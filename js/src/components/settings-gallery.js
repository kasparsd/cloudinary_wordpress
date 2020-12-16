import React from 'react';
import { render } from '@wordpress/element';
import GalleryControls from '../gallery-block/controls';

import '../../../css/src/gallery.scss';
import '@wordpress/components/build-style/style.css';

const attributes = {};

render(
	<div className="interface-interface-skeleton__sidebar">
		<div className="interface-complementary-area edit-post-sidebar">
			<div className="components-panel">
				<div className="block-editor-block-inspector">
					<GalleryControls
						attributes={ attributes }
						setAttributes={ () => {} }
					/>
				</div>
			</div>
		</div>
	</div>,
	document.getElementById( 'app_gallery' )
);
