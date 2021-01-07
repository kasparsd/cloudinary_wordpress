/* global CLDIS */
const Notices = {
	_init() {
		const self = this;
		if ( typeof CLDIS !== 'undefined' ) {
			const notices = document.getElementsByClassName( 'cld-notice' );
			[ ...notices ].forEach( ( notice ) => {
				notice.addEventListener( 'click', ( ev ) => {
					// WordPress has an onClick that cannot be unbound.
					// So, we have the click on our Notice, and act on the
					// button as a target.
					if ( 'notice-dismiss' === ev.target.className ) {
						self._dismiss( notice );
					}
				} );
			} );
		}
	},
	_dismiss( notice ) {
		const token = notice.dataset.dismiss;
		const duration = notice.dataset.duration;
		wp.ajax.send( {
			url: CLDIS.url,
			data: {
				token,
				duration,
				_wpnonce: CLDIS.nonce,
			},
		} );
	},
};

// Init.
window.addEventListener( 'load', Notices._init() );

export default Notices;
