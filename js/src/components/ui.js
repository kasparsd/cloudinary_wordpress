/* global */

const UI = {
	_init() {
		const conditions = document.querySelectorAll( '[data-bind]' );
		const toggles = document.querySelectorAll( '[data-toggle]' );
		const aliases = document.querySelectorAll( '[data-for]' );
		const self = this;
		conditions.forEach( self._bind );
		toggles.forEach( self._toggle );
		aliases.forEach( self._alias );
	},
	_bind( element ) {
		const condition = JSON.parse( element.dataset.condition );
		const input = document.querySelector(
			'input[data-bound="' + element.dataset.bind + '"]'
		);
		input.addEventListener( 'change', function () {
			const id = input.id;
			let check = false;
			let action = 'closed';
			if ( input.type === 'checkbox' || input.type === 'radio' ) {
				check = input.checked === condition[ id ];
			} else {
				check = input.value === condition[ id ];
			}

			if ( true === check ) {
				action = 'open';
			}
			UI.toggle( element, input, action );
		} );
	},
	_alias( element ) {
		element.addEventListener( 'click', function () {
			const aliasOf = document.getElementById( element.dataset.for );
			aliasOf.dispatchEvent( new Event( 'click' ) );
		} );
	},
	_toggle( element ) {
		element.addEventListener( 'click', function () {
			const wrap = document.querySelector(
				'[data-wrap="' + element.dataset.toggle + '"]'
			);
			const action = wrap.classList.contains( 'open' )
				? 'closed'
				: 'open';
			UI.toggle( wrap, element, action );
		} );
	},
	toggle( wrap, element, action ) {
		if ( 'closed' === action ) {
			wrap.classList.remove( 'open' );
			wrap.classList.add( 'closed' );
			if ( element.classList.contains( 'dashicons' ) ) {
				element.classList.remove( 'dashicons-arrow-up-alt2' );
				element.classList.add( 'dashicons-arrow-down-alt2' );
			}
		} else {
			wrap.classList.remove( 'closed' );
			wrap.classList.add( 'open' );
			if ( element.classList.contains( 'dashicons' ) ) {
				element.classList.remove( 'dashicons-arrow-down-alt2' );
				element.classList.add( 'dashicons-arrow-up-alt2' );
			}
		}
	},
};
// Init.
window.addEventListener( 'load', UI._init() );

export default UI;
