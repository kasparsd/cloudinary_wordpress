import { dispatch } from '@wordpress/data';

export const showNotice = ( { status, message, options = {} } ) => {
	dispatch( 'core/notices' ).createNotice(
		status, // Can be one of: success, info, warning, error.
		message,
		{ isDismissible: true, ...options }
	);
};

const dec2hex = ( dec ) => {
	return dec < 10 ? '0' + String( dec ) : dec.toString( 16 );
};

export const generateId = ( len ) => {
	const arr = new Uint8Array( ( len || 40 ) / 2 );
	window.crypto.getRandomValues( arr );
	return Array.from( arr, dec2hex ).join( '' );
};

export const sortObject = ( object ) => {
	const sortedObj = {};
	const keys = Object.keys( object );

	keys.sort();

	for ( const index in keys ) {
		const key = keys[ index ];
		if (
			typeof object[ key ] === 'object' &&
			! ( object[ key ] instanceof Array )
		) {
			sortedObj[ key ] = sortObject( object[ key ] );
		} else {
			sortedObj[ key ] = object[ key ];
		}
	}

	return sortedObj;
};

export const toBlockAttributes = ( object ) => {
	const blockAttributes = {};

	Object.keys( object ).forEach( ( key ) => {
		blockAttributes[ key ] = {
			type: typeof object[ key ],
			default: object[ key ],
		};
	} );

	return blockAttributes;
};
