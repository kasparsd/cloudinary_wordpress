/* global cloudinaryGalleryConfig */

const configElements = document.querySelectorAll(
	'[data-cloudinary-gallery-config]'
);

if ( configElements.length ) {
	configElements.forEach( function ( el ) {
		const configJson = decodeURIComponent(
			el.getAttribute( 'data-cloudinary-gallery-config' )
		);
		const options = JSON.parse( configJson );
		options.container = '.' + options.container;
		cloudinary.galleryWidget( options ).render();
	} );
} else if (
	document.querySelector( '.woocommerce-page' ) &&
	cloudinaryGalleryConfig
) {
	cloudinary.galleryWidget( cloudinaryGalleryConfig ).render();
}
