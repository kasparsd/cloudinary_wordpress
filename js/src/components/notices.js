/* global CLDIS */
const Notices = {
	_init() {
		const self = this;
		if ( typeof CLDIS !== 'undefined' ) {
			const notices = document.getElementsByClassName( 'cld-notice-box' );
			[ ...notices ].forEach( ( notice ) => {
				notice.style.height = notice.offsetHeight + 'px';
				const dismiss = notice.getElementsByClassName(
					'notice-dismiss'
				);
				if ( dismiss.length ) {
					dismiss[ 0 ].addEventListener( 'click', ( ev ) => {
						ev.preventDefault();
						self._dismiss( notice );
					} );
				}
			} );
		}
	},
	_dismiss( notice ) {
		const token = notice.dataset.dismiss;
		const duration = parseInt( notice.dataset.duration );
		notice.classList.add( 'dismissed' );
		notice.style.height = '0px';
		if ( 0 < duration ) {
			wp.ajax
				.send( {
					url: CLDIS.url,
					data: {
						token,
						duration,
						_wpnonce: CLDIS.nonce,
					},
				} )
				.always( function () {
					notice.remove();
				} );
		}
	},
};

// Init.
window.addEventListener( 'load', Notices._init() );

export default Notices;
