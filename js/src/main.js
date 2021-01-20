/**
 * External dependencies
 */
import 'loading-attribute-polyfill';
import './components/taxonomies';
/**
 * Internal dependencies
 */
import Settings from './components/settings-page';
import Widget from './components/widget';
import GlobalTransformations from './components/global-transformations';
import TermsOrder from './components/terms-order';
import MediaLibrary from './components/media-library';
import Notices from './components/notices';
import UI from './components/ui';

import '../../css/src/main.scss';

// jQuery, because reasons.
window.$ = window.jQuery;

// Global Constants
export const cloudinary = {
	UI,
	Settings,
	Widget,
	GlobalTransformations,
	TermsOrder,
	MediaLibrary,
	Notices,
};
