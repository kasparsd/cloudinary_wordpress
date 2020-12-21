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
