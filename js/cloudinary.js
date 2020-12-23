/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/src/main.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./css/src/main.scss":
/*!***************************!*\
  !*** ./css/src/main.scss ***!
  \***************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/src/components/global-transformations.js":
/*!*****************************************************!*\
  !*** ./js/src/components/global-transformations.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
const GlobalTransformations = {
	sample: {
		image: document.getElementById( 'transformation-sample-image' ),
		video: document.getElementById( 'transformation-sample-video' ),
	},
	preview: {
		image: document.getElementById( 'sample-image' ),
		video: document.getElementById( 'sample-video' ),
	},
	fields: document.getElementsByClassName( 'cld-field' ),
	button: {
		image: document.getElementById( 'refresh-image-preview' ),
		video: document.getElementById( 'refresh-video-preview' ),
	},
	spinner: {
		image: document.getElementById( 'image-loader' ),
		video: document.getElementById( 'video-loader' ),
	},
	error_container: document.getElementById( 'cld-preview-error' ),
	activeItem: null,
	elements: {
		image: [],
		video: [],
	},
	_placeItem( item ) {
		if ( null !== item ) {
			item.style.display = 'block';
			item.style.visibility = 'visible';
			item.style.position = 'absolute';
			item.style.top =
				item.parentElement.clientHeight / 2 -
				item.clientHeight / 2 +
				'px';
			item.style.left =
				item.parentElement.clientWidth / 2 -
				item.clientWidth / 2 +
				'px';
		}
	},
	_setLoading( type ) {
		this.button[ type ].style.display = 'block';
		this._placeItem( this.button[ type ] );
		this.preview[ type ].style.opacity = '0.1';
	},
	_build( type ) {
		this.sample[ type ].innerHTML = '';
		this.elements[ type ] = [];
		for ( const item of this.fields ) {
			if ( type !== item.dataset.context ) {
				continue;
			}
			let value = item.value.trim();
			if ( value.length ) {
				if ( 'select-one' === item.type ) {
					if ( 'none' === value ) {
						continue;
					}
					value = item.dataset.meta + '_' + value;
				} else {
					type = item.dataset.context;
					value = this._transformations( value, type, true );
				}
				// Apply value if valid.
				if ( value ) {
					this.elements[ type ].push( value );
				}
			}
		}
		let transformations = '';
		if ( this.elements[ type ].length ) {
			transformations =
				'/' + this.elements[ type ].join( ',' ).replace( / /g, '%20' );
		}
		this.sample[ type ].textContent = transformations;
		this.sample[ type ].parentElement.href =
			'https://res.cloudinary.com/demo/' +
			this.sample[ type ].parentElement.innerText
				.trim()
				.replace( '../', '' )
				.replace( / /g, '%20' );
	},
	_clearLoading( type ) {
		this.spinner[ type ].style.visibility = 'hidden';
		this.activeItem = null;
		this.preview[ type ].style.opacity = 1;
	},
	_refresh( e, type ) {
		if ( e ) {
			e.preventDefault();
		}
		const self = this;
		const newSrc =
			CLD_GLOBAL_TRANSFORMATIONS[ type ].preview_url +
			self.elements[ type ].join( ',' ) +
			CLD_GLOBAL_TRANSFORMATIONS[ type ].file;
		this.button[ type ].style.display = 'none';
		this._placeItem( this.spinner[ type ] );
		if ( type === 'image' ) {
			const newImg = new Image();
			newImg.onload = function () {
				self.preview[ type ].src = this.src;
				self._clearLoading( type );
				if ( self.error_container ) {
					self.error_container.style.display = 'none';
				}
				newImg.remove();
			};
			newImg.onerror = function () {
				const hasMp4 = self.elements[ type ].includes( 'f_mp4' );

				if ( self.error_container ) {
					self.error_container.style.display = 'block';

					if ( ! hasMp4 ) {
						self.error_container.innerHTML =
							CLD_GLOBAL_TRANSFORMATIONS[ type ].error;
						self.error_container.classList.replace(
							'settings-alert-warning',
							'settings-alert-error'
						);
					} else {
						// temporary, will be replaced with i18n.sprintf instead of .replace
						self.error_container.innerHTML = CLD_GLOBAL_TRANSFORMATIONS[
							type
						].warning.replace( '%s', 'f_mp4' );
						self.error_container.classList.replace(
							'settings-alert-error',
							'settings-alert-warning'
						);
					}
				}

				self._clearLoading( type );
			};
			newImg.src = newSrc;
		} else {
			const transformations = self._transformations(
				self.elements[ type ].join( ',' ),
				type
			);
			samplePlayer.source( {
				publicId: 'dog',
				transformation: transformations,
			} );
			self._clearLoading( type );
		}
	},
	_transformations( input, type, string = false ) {
		const set = CLD_GLOBAL_TRANSFORMATIONS[ type ].valid_types;
		let value = null;
		const elements = input.split( '/' );
		const validElements = [];
		for ( let i = 0; i < elements.length; i++ ) {
			const parts = elements[ i ].split( ',' );
			let validParts;
			if ( true === string ) {
				validParts = [];
			} else {
				validParts = {};
			}
			for ( let p = 0; p < parts.length; p++ ) {
				const keyVal = parts[ p ].trim().split( '_' );
				if (
					keyVal.length <= 1 ||
					typeof set[ keyVal[ 0 ] ] === 'undefined'
				) {
					continue;
				}
				const option = keyVal.shift();
				const instruct = keyVal.join( '_' );
				if ( true === string ) {
					if ( 'f' === option || 'q' === option ) {
						for ( const t in this.elements[ type ] ) {
							if (
								option + '_' ===
								this.elements[ type ][ t ].substr( 0, 2 )
							) {
								this.elements[ type ].splice( t, 1 );
							}
						}
					}
					validParts.push( parts[ p ] );
				} else {
					validParts[ set[ option ] ] = instruct.trim();
				}
			}
			let length = 0;
			if ( true === string ) {
				length = validParts.length;
			} else {
				length = Object.keys( validParts ).length;
			}
			if ( length ) {
				if ( true === string ) {
					validParts = validParts.join( ',' );
				}
				validElements.push( validParts );
			}
		}

		if ( validElements.length ) {
			if ( true === string ) {
				value = validElements.join( '/' ).trim();
			} else {
				value = validElements;
			}
		}

		return value;
	},
	_reset() {
		for ( const item of this.fields ) {
			item.value = null;
		}
		for ( const type in this.button ) {
			this._build( type );
			this._refresh( null, type );
		}
	},
	_input( input ) {
		if (
			typeof input.dataset.context !== 'undefined' &&
			input.dataset.context.length
		) {
			const type = input.dataset.context;
			this._setLoading( type );
			this._build( type );
		}
	},
	_init() {
		if ( typeof CLD_GLOBAL_TRANSFORMATIONS !== 'undefined' ) {
			const self = this;

			document.addEventListener( 'DOMContentLoaded', function () {
				for ( const type in self.button ) {
					if ( self.button[ type ] ) {
						self.button[ type ].addEventListener(
							'click',
							function ( e ) {
								self._refresh( e, type );
							}
						);
					}
				}
				for ( const item of self.fields ) {
					item.addEventListener( 'input', function () {
						self._input( this );
					} );
					item.addEventListener( 'change', function () {
						self._input( this );
					} );
				}
				// Init.
				for ( const type in CLD_GLOBAL_TRANSFORMATIONS ) {
					self._build( type );
					self._refresh( null, type );
				}
			} );
			// listen to AJAX add-tag complete
			jQuery( document ).ajaxComplete( function ( event, xhr, settings ) {
				// bail early if is other ajax call
				if ( settings.data.indexOf( 'action=add-tag' ) === -1 ) {
					return;
				}

				// bail early if response contains error
				if ( xhr.responseText.indexOf( 'wp_error' ) !== -1 ) {
					return;
				}
				self._reset();
			} );
		}
	},
};

// Init.
GlobalTransformations._init();

/* harmony default export */ __webpack_exports__["default"] = (GlobalTransformations);


/***/ }),

/***/ "./js/src/components/media-library.js":
/*!********************************************!*\
  !*** ./js/src/components/media-library.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* global CLD_ML */
const MediaLibrary = {
	wpWrap: document.getElementById( 'wpwrap' ),
	wpContent: document.getElementById( 'wpbody-content' ),
	libraryWrap: document.getElementById( 'cloudinary-embed' ),
	_init() {
		const self = this;
		if ( typeof CLD_ML !== 'undefined' ) {
			cloudinary.openMediaLibrary( CLD_ML.mloptions, {
				insertHandler() {
					// @todo: Determin what to do here.
					alert( 'Import is not yet implemented.' );
				},
			} );

			window.addEventListener( 'resize', function () {
				self._resize();
			} );

			self._resize();
		}
	},
	_resize() {
		const style = getComputedStyle( this.wpContent );
		this.libraryWrap.style.height =
			this.wpWrap.offsetHeight -
			parseInt( style.getPropertyValue( 'padding-bottom' ) ) +
			'px';
	},
};

/* harmony default export */ __webpack_exports__["default"] = (MediaLibrary);

// Init.
MediaLibrary._init();


/***/ }),

/***/ "./js/src/components/notices.js":
/*!**************************************!*\
  !*** ./js/src/components/notices.js ***!
  \**************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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

/* harmony default export */ __webpack_exports__["default"] = (Notices);


/***/ }),

/***/ "./js/src/components/settings-page.js":
/*!********************************************!*\
  !*** ./js/src/components/settings-page.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

( function () {
	// Disable the "off" dropdown option for Autoplay if
	// the player isn't set to Cloudinary or if Show Controls if unchecked.
	const disableAutoplayOff = function () {
		const player = jQuery( '#field-video_player' ).val();
		const showControls = jQuery( '#field-video_controls' ).prop(
			'checked'
		);
		const offSelection = jQuery(
			'#field-video_autoplay_mode option[value="off"]'
		);

		if ( player === 'cld' && ! showControls ) {
			offSelection.prop( 'disabled', true );
			if ( offSelection.prop( 'selected' ) ) {
				offSelection.next().prop( 'selected', true );
			}
		} else {
			offSelection.prop( 'disabled', false );
		}
	};

	disableAutoplayOff();
	jQuery( document ).on(
		'change',
		'#field-video_player',
		disableAutoplayOff
	);
	jQuery( document ).on(
		'change',
		'#field-video_controls',
		disableAutoplayOff
	);

	jQuery( document ).ready( function ( $ ) {
		if ( $.isFunction( $.fn.wpColorPicker ) ) {
			$( '.regular-color' ).wpColorPicker();
		}

		// Initilize instance events
		$( document ).on( 'tabs.init', function () {
			const tabs = $( '.settings-tab-trigger' ),
				sections = $( '.settings-tab-section' );

			// Create instance bindings
			$( this ).on( 'click', '.settings-tab-trigger', function ( e ) {
				const clicked = $( this ),
					target = $( clicked.attr( 'href' ) );

				// Trigger an instance action.
				e.preventDefault();

				tabs.removeClass( 'active' );
				sections.removeClass( 'active' );

				clicked.addClass( 'active' );
				target.addClass( 'active' );

				// Trigger the tabbed event.
				$( document ).trigger( 'settings.tabbed', clicked );
			} );

			// Bind conditions.
			$( '.cld-field' )
				.not( '[data-condition="false"]' )
				.each( function () {
					const field = $( this );
					const condition = field.data( 'condition' );

					for ( const f in condition ) {
						let target = $( '#field-' + f );
						const value = condition[ f ];
						const wrapper = field.closest( 'tr' );

						if ( ! target.length ) {
							target = $( `[id^=field-${ f }-]` );
						}

						let fieldIsSet = false;

						target.on( 'change init', function (
							_,
							isInit = false
						) {
							if ( fieldIsSet && isInit ) {
								return;
							}

							let fieldCondition =
								this.value === value || this.checked;

							if (
								Array.isArray( value ) &&
								value.length === 2
							) {
								switch ( value[ 1 ] ) {
									case 'neq':
										fieldCondition =
											this.value !== value[ 0 ];
										break;
									case 'gt':
										fieldCondition =
											this.value > value[ 0 ];
										break;
									case 'lt':
										fieldCondition =
											this.value < value[ 0 ];
								}
							}

							if ( fieldCondition ) {
								wrapper.show();
							} else {
								wrapper.hide();
							}

							fieldIsSet = true;
						} );

						target.trigger( 'init', true );
					}
				} );

			$( '#field-cloudinary_url' )
				.on( 'input change', function () {
					const field = $( this ),
						value = field.val();

					const reg = new RegExp(
						/^(?:CLOUDINARY_URL=)?(cloudinary:\/\/){1}(\d)*[:]{1}[^:@]*[@]{1}[^@]*$/g
					);
					if ( reg.test( value ) ) {
						field.addClass( 'settings-valid-field' );
						field.removeClass( 'settings-invalid-field' );
					} else {
						field.removeClass( 'settings-valid-field' );
						field.addClass( 'settings-invalid-field' );
					}
				} )
				.trigger( 'change' );

			$( '[name="cloudinary_sync_media[auto_sync]"]' ).change(
				function () {
					if ( $( this ).val() === 'on' ) {
						$( '#auto-sync-alert-btn' ).click();
					}
				}
			);
		} );

		// On Ready, find all render trigger elements and fire their events.
		$( '.render-trigger[data-event]' ).each( function () {
			const trigger = $( this ),
				event = trigger.data( 'event' );
			trigger.trigger( event, this );
		} );
	} );
} )( window, jQuery );


/***/ }),

/***/ "./js/src/components/sync.js":
/*!***********************************!*\
  !*** ./js/src/components/sync.js ***!
  \***********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* global cloudinaryApi */

const Sync = {
	progress: document.getElementById( 'progress-wrapper' ),
	submitButton: document.getElementById( 'submit' ),
	stopButton: document.getElementById( 'stop-sync' ),
	completed: document.getElementById( 'completed-notice' ),
	show: 'inline-block',
	hide: 'none',
	isRunning: false,
	getStatus: function getStatus() {
		const url = cloudinaryApi.restUrl + 'cloudinary/v1/attachments';

		wp.ajax
			.send( {
				url,
				type: 'GET',
				beforeSend( request ) {
					request.setRequestHeader(
						'X-WP-Nonce',
						cloudinaryApi.nonce
					);
				},
			} )
			.done( function ( data ) {
				Sync.isRunning = data.is_running;
				if ( Sync.isRunning ) {
					setTimeout( Sync.getStatus, 10000 );
				}
				Sync._updateUI( data );
			} );
	},
	stopSync: function stopSync() {
		const url = cloudinaryApi.restUrl + 'cloudinary/v1/sync';

		Sync.isRunning = false;

		wp.ajax
			.send( {
				url,
				data: {
					stop: true,
				},
				beforeSend( request ) {
					request.setRequestHeader(
						'X-WP-Nonce',
						cloudinaryApi.nonce
					);
				},
			} )
			.done( function ( data ) {
				Sync._updateUI( data );
			} );
	},
	pushAttachments: function pushAttachments() {
		const url = cloudinaryApi.restUrl + 'cloudinary/v1/sync';

		Sync.isRunning = true;
		Sync.progress.style.display = Sync.show;

		wp.ajax
			.send( {
				url,
				beforeSend( request ) {
					request.setRequestHeader(
						'X-WP-Nonce',
						cloudinaryApi.nonce
					);
				},
			} )
			.done( function () {
				setTimeout( Sync.getStatus, 10000 );
			} );
	},
	_updateUI: function _updateUI( data ) {
		if ( data.percent < 100 && typeof data.started !== 'undefined' ) {
			this.submitButton.style.display = this.hide;
			this.stopButton.style.display = this.show;
		} else if (
			data.percent >= 100 &&
			typeof data.started !== 'undefined'
		) {
			this.submitButton.style.display = this.hide;
			this.stopButton.style.display = this.show;
		} else if ( data.pending > 0 ) {
			this.submitButton.style.display = this.show;
			this.stopButton.style.display = this.hide;
		} else if ( data.processing > 0 ) {
			this.stopButton.style.display = this.show;
		} else {
			this.stopButton.style.display = this.hide;
		}

		if ( data.percent === 100 ) {
			this.completed.style.display = this.show;
		}

		if ( this.isRunning ) {
			this.progress.style.display = this.show;
		} else {
			this.progress.style.display = this.hide;
		}
	},
	_start: function _start( e ) {
		e.preventDefault();
		Sync.stopButton.style.display = Sync.show;
		Sync.submitButton.style.display = Sync.hide;
		Sync.pushAttachments();
	},
	_reset: function _reset() {
		Sync.submitButton.style.display = Sync.hide;
		Sync.getStatus();
	},
	_init( fn ) {
		if ( typeof cloudinaryApi !== 'undefined' ) {
			if (
				document.attachEvent
					? document.readyState === 'complete'
					: document.readyState !== 'loading'
			) {
				fn();
			} else {
				document.addEventListener( 'DOMContentLoaded', fn );
			}
		}
	},
};

/* harmony default export */ __webpack_exports__["default"] = (Sync);

// Add it a trigger watch to stop deactivation.
const triggers = document.getElementsByClassName( 'cld-deactivate' );
[ ...triggers ].forEach( ( trigger ) => {
	trigger.addEventListener( 'click', function ( ev ) {
		if (
			! confirm(
				wp.i18n.__(
					'Caution: Your storage setting is currently set to "Cloudinary only", disabling the plugin will result in broken links to media assets. Are you sure you want to continue?',
					'cloudinary'
				)
			)
		) {
			ev.preventDefault();
		}
	} );
	trigger.addEventListener( 'click', function ( ev ) {
		if (
			! confirm(
				wp.i18n.__(
					'Caution: Your storage setting is currently set to "Cloudinary only", disabling the plugin will result in broken links to media assets. Are you sure you want to continue?',
					'cloudinary'
				)
			)
		) {
			ev.preventDefault();
			// Close the feedback form.
			document.getElementById( 'TB_closeWindowButton' ).click();
		}
	} );
} );

// Init.
Sync._init( function () {
	Sync._reset();
	Sync.submitButton.addEventListener( 'click', Sync._start );
	Sync.stopButton.addEventListener( 'click', Sync.stopSync );
} );


/***/ }),

/***/ "./js/src/components/terms-order.js":
/*!******************************************!*\
  !*** ./js/src/components/terms-order.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* global wpAjax */

const TermsOrder = {
	template: '',
	tags: jQuery( '#cld-tax-items' ),
	tagDelimiter:
		( window.tagsSuggestL10n && window.tagsSuggestL10n.tagDelimiter ) ||
		',',
	startId: null,
	_init() {
		// Check that we found the tax-items.
		if ( ! this.tags.length ) {
			return;
		}

		const self = this;
		this._sortable();

		// Setup ajax overrides.
		if ( typeof wpAjax !== 'undefined' ) {
			wpAjax.procesParseAjaxResponse = wpAjax.parseAjaxResponse;
			wpAjax.parseAjaxResponse = function (
				response,
				settingsResponse,
				element
			) {
				const newResponse = wpAjax.procesParseAjaxResponse(
					response,
					settingsResponse,
					element
				);
				if ( ! newResponse.errors && newResponse.responses[ 0 ] ) {
					if (
						jQuery(
							'[data-taxonomy="' +
								newResponse.responses[ 0 ].what +
								'"]'
						).length
					) {
						const data = jQuery( newResponse.responses[ 0 ].data );
						const text = data.find( 'label' ).last().text().trim();
						self._pushItem( newResponse.responses[ 0 ].what, text );
					}
				}

				return newResponse;
			};
		}

		if ( typeof window.tagBox !== 'undefined' ) {
			window.tagBox.processflushTags = window.tagBox.flushTags;
			window.tagBox.flushTags = function ( el, a, f ) {
				if ( typeof f === 'undefined' ) {
					const taxonomy = el.prop( 'id' );
					const newTag = jQuery( 'input.newtag', el );

					a = a || false;

					const text = a ? jQuery( a ).text() : newTag.val();
					const list = window.tagBox
						.clean( text )
						.split( self.tagDelimiter );

					for ( const i in list ) {
						const tag = taxonomy + ':' + list[ i ];
						if ( ! jQuery( '[data-item="' + tag + '"]' ).length ) {
							self._pushItem( tag, list[ i ] );
						}
					}
				}

				return this.processflushTags( el, a, f );
			};

			window.tagBox.processTags = window.tagBox.parseTags;

			window.tagBox.parseTags = function ( el ) {
				const id = el.id;
				const num = id.split( '-check-num-' )[ 1 ];
				const taxonomy = id.split( '-check-num-' )[ 0 ];
				const taxBox = jQuery( el ).closest( '.tagsdiv' );
				const tagsTextarea = taxBox.find( '.the-tags' );
				const tagToRemove = window.tagBox
					.clean( tagsTextarea.val() )
					.split( self.tagDelimiter )[ num ];

				new wp.api.collections.Tags()
					.fetch( { data: { slug: tagToRemove } } )
					.done( ( tag ) => {
						const tagFromDatabase = tag.length
							? jQuery(
									'[data-item="' +
										taxonomy +
										':' +
										tag[ 0 ].id +
										'"]'
							  )
							: false;

						if ( tagFromDatabase.length ) {
							tagFromDatabase.remove();
						} else {
							jQuery(
								`.cld-tax-order-list-item:contains(${ tagToRemove })`
							).remove();
							--self.startId;
						}
						this.processTags( el );
					} );
			};
		}

		jQuery( 'body' ).on( 'change', '.selectit input', function () {
			const clickedItem = jQuery( this );
			const id = clickedItem.val();
			const checked = clickedItem.is( ':checked' );
			const text = clickedItem.parent().text().trim();

			if ( true === checked ) {
				if (
					! self.tags.find( `[data-item="category:${ id }"]` ).length
				) {
					self._pushItem( `category:${ id }`, text );
				}
			} else {
				self.tags.find( `[data-item="category:${ id }"]` ).remove();
			}
		} );
	},
	_createItem( id, name ) {
		const li = jQuery( '<li/>' );
		const icon = jQuery( '<span/>' );
		const input = jQuery( '<input/>' );

		li.addClass( 'cld-tax-order-list-item' ).attr( 'data-item', id );
		input
			.addClass( 'cld-tax-order-list-item-input' )
			.attr( 'type', 'hidden' )
			.attr( 'name', 'cld_tax_order[]' )
			.val( id );
		icon.addClass(
			'dashicons dashicons-menu cld-tax-order-list-item-handle'
		);

		li.append( icon ).append( name ).append( input ); // phpcs:ignore
		// WordPressVIPMinimum.JS.HTMLExecutingFunctions.append

		return li;
	},
	_pushItem( id, text ) {
		const item = this._createItem( id, text );
		this.tags.append( item ); // phpcs:ignore
		// WordPressVIPMinimum.JS.HTMLExecutingFunctions.append
	},
	_sortable() {
		const items = jQuery( '.cld-tax-order-list' );

		items.sortable( {
			connectWith: '.cld-tax-order',
			axis: 'y',
			handle: '.cld-tax-order-list-item-handle',
			placeholder: 'cld-tax-order-list-item-placeholder',
			forcePlaceholderSize: true,
			helper: 'clone',
		} );
	},
};

if ( typeof window.CLDN !== 'undefined' ) {
	TermsOrder._init();
	// Init checked categories.
	jQuery( '[data-wp-lists] .selectit input[checked]' ).each(
		( ord, check ) => {
			jQuery( check ).trigger( 'change' );
		}
	);
}

// Gutenberg.
if ( wp.data && wp.data.select( 'core/editor' ) ) {
	const orderSet = {};
	wp.data.subscribe( function () {
		const taxonomies = wp.data.select( 'core' ).getTaxonomies();

		if ( taxonomies ) {
			for ( const t in taxonomies ) {
				const set = wp.data
					.select( 'core/editor' )
					.getEditedPostAttribute( taxonomies[ t ].rest_base );
				orderSet[ taxonomies[ t ].slug ] = set;
			}
		}
	} );

	const el = wp.element.createElement;
	const CustomizeTaxonomySelector = ( OriginalComponent ) => {
		class CustomHandler extends OriginalComponent {
			constructor( props ) {
				super( props );

				this.currentItems = jQuery( '.cld-tax-order-list-item' )
					.map( ( _, taxonomy ) => jQuery( taxonomy ).data( 'item' ) )
					.get();
			}

			makeItem( item ) {
				// Prevent duplicates in the tax order box
				if ( this.currentItems.includes( this.getId( item ) ) ) {
					return;
				}

				const row = this.makeElement( item );
				const box = jQuery( '#cld-tax-items' );
				box.append( row ); // phpcs:ignore
				// WordPressVIPMinimum.JS.HTMLExecutingFunctions.append
			}

			removeItem( item ) {
				const elementWithId = jQuery(
					`[data-item="${ this.getId( item ) }"]`
				);

				if ( elementWithId.length ) {
					elementWithId.remove();

					this.currentItems = this.currentItems.filter(
						( taxIdentifier ) => {
							return taxIdentifier !== this.getId( item );
						}
					);
				}
			}

			findOrCreateTerm( termName ) {
				termName = super.findOrCreateTerm( termName );
				termName.then( ( item ) => this.makeItem( item ) );

				return termName;
			}

			onChange( event ) {
				super.onChange( event );
				const item = this.pickItem( event );

				if ( item ) {
					if ( orderSet[ this.props.slug ].includes( item.id ) ) {
						this.makeItem( item );
					} else {
						this.removeItem( item );
					}
				}
			}

			pickItem( event ) {
				if ( typeof event === 'object' ) {
					if ( event.target ) {
						for ( const p in this.state.availableTerms ) {
							if (
								this.state.availableTerms[ p ].id ===
								parseInt( event.target.value )
							) {
								return this.state.availableTerms[ p ];
							}
						}
						// Tags that are already registered need to be selected
						// separately as its expected that they return back
						// with an "id" property.
					} else if ( Array.isArray( event ) ) {
						// Figure out the diff between the current state and
						// the event and determine which tag is getting removed
						let enteredTag = this.state.selectedTerms.filter(
							( flatItem ) => ! event.includes( flatItem )
						)[ 0 ];

						if ( typeof enteredTag === 'undefined' ) {
							// If the above returns undefined, then we presume
							// the user is adding, so reverse the logic to
							// figure out the new item
							enteredTag = event.filter(
								( flatItem ) =>
									! this.state.selectedTerms.includes(
										flatItem
									)
							)[ 0 ];
						}

						return this.state.availableTerms.find(
							( item ) => item.name === enteredTag
						);
					}
				} else if ( typeof event === 'number' ) {
					for ( const p in this.state.availableTerms ) {
						if ( this.state.availableTerms[ p ].id === event ) {
							return this.state.availableTerms[ p ];
						}
					}
				} else {
					let text;

					// add or remove.
					if ( event.length > this.state.selectedTerms.length ) {
						// Added.
						for ( const o in event ) {
							if (
								this.state.selectedTerms.indexOf(
									event[ o ]
								) === -1
							) {
								text = event[ o ];
							}
						}
					} else {
						// removed.
						for ( const o in this.state.selectedTerms ) {
							if (
								event.indexOf(
									this.state.selectedTerms[ o ]
								) === -1
							) {
								text = this.state.selectedTerms[ o ];
							}
						}
					}

					for ( const p in this.state.availableTerms ) {
						if ( this.state.availableTerms[ p ].name === text ) {
							return this.state.availableTerms[ p ];
						}
					}
				}
			}

			getId( item ) {
				return `${ this.props.slug }:${ item.id }`;
			}

			makeElement( item ) {
				const li = jQuery( '<li/>' );
				const icon = jQuery( '<span/>' );
				const input = jQuery( '<input/>' );

				li.addClass( 'cld-tax-order-list-item' ).attr(
					'data-item',
					this.getId( item )
				);

				input
					.addClass( 'cld-tax-order-list-item-input' )
					.attr( 'type', 'hidden' )
					.attr( 'name', 'cld_tax_order[]' )
					.val( this.getId( item ) );

				icon.addClass(
					'dashicons dashicons-menu cld-tax-order-list-item-handle'
				);

				li.append( icon ).append( item.name ).append( input ); // phpcs:ignore
				// WordPressVIPMinimum.JS.HTMLExecutingFunctions.append

				return li;
			}
		}

		return ( props ) => el( CustomHandler, props );
	};

	wp.hooks.addFilter(
		'editor.PostTaxonomyType',
		'cld',
		CustomizeTaxonomySelector
	);
}

/* harmony default export */ __webpack_exports__["default"] = (TermsOrder);


/***/ }),

/***/ "./js/src/components/ui.js":
/*!*********************************!*\
  !*** ./js/src/components/ui.js ***!
  \*********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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

/* harmony default export */ __webpack_exports__["default"] = (UI);


/***/ }),

/***/ "./js/src/components/widget.js":
/*!*************************************!*\
  !*** ./js/src/components/widget.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

if ( wp.media && window.CLDN ) {
	wp.media.events.on( 'editor:image-edit', function ( props ) {
		props.metadata.cldoverwrite = null;
		const classes = props.image.className.split( ' ' );
		if ( classes.indexOf( 'cld-overwrite' ) >= 0 ) {
			props.metadata.cldoverwrite = 'true';
		}
	} );
	wp.media.events.on( 'editor:image-update', function ( props ) {
		const classes = props.image.className.split( ' ' );
		if (
			props.metadata.cldoverwrite &&
			classes.indexOf( 'cld-overwrite' ) === -1
		) {
			classes.push( 'cld-overwrite' );
		} else if (
			! props.metadata.cldoverwrite &&
			classes.indexOf( 'cld-overwrite' ) >= 0
		) {
			delete classes[ classes.indexOf( 'cld-overwrite' ) ];
		}

		props.image.className = classes.join( ' ' );
	} );

	// Intercept props and inject cld-overwrite class.
	let currentOverwrite = null;
	const imageProps = wp.media.string.props;
	wp.media.string.props = function ( props, asset ) {
		if ( props.cldoverwrite ) {
			props.classes = [ 'cld-overwrite' ];
			currentOverwrite = true;
		}
		const newProps = imageProps( props, asset );
		return newProps;
	};
	// Intercept ajax post, and send the cld-overwrite flag, and transformations.
	wp.media.post = function ( action, data ) {
		if ( 'send-attachment-to-editor' === action ) {
			const state = wp.media.editor.get().state();
			const attach = state.get( 'selection' ).get( data.attachment );
			if ( attach.attributes.transformations ) {
				data.attachment.transformations =
					attach.attributes.transformations;
			}
			if (
				data.html.indexOf( 'cld-overwrite' ) > -1 ||
				true === currentOverwrite
			) {
				data.attachment.cldoverwrite = true;
				currentOverwrite = null;
			}
		}
		// Return the original.
		return wp.ajax.post( action, data );
	};

	const MediaFrame = wp.media.view.MediaFrame.Select;
	const MediaFramePost = wp.media.view.MediaFrame.Post;
	const MediaFrameImageDetails = wp.media.view.MediaFrame.ImageDetails;
	const MediaFrameVideoDetails = wp.media.view.MediaFrame.VideoDetails;
	const Cloudinary = wp.media.View.extend( {
		tagName: 'div',
		className: 'cloudinary-widget',
		template: wp.template( 'cloudinary-dam' ),
		active: false,
		toolbar: null,
		frame: null,
		ready() {
			const controller = this.controller;
			const selection = this.model.get( 'selection' );
			const library = this.model.get( 'library' );
			const attachment = wp.media.model.Attachment;
			// Set widget to same as model.
			CLDN.mloptions.multiple = controller.options.multiple;
			if ( this.cid !== this.active ) {
				CLDN.mloptions.inline_container =
					'#cloudinary-dam-' + controller.cid;
				if ( 1 === selection.length ) {
					const att = attachment.get( selection.models[ 0 ].id );
					if ( typeof att.attributes.public_id !== 'undefined' ) {
						CLDN.mloptions.asset = {
							resource_id: att.attributes.public_id,
						};
					}
				} else {
					CLDN.mloptions.asset = null;
				}
				window.ml = cloudinary.openMediaLibrary(
					CLDN.mloptions,
					{
						insertHandler( data ) {
							for ( let i = 0; i < data.assets.length; i++ ) {
								const temp = data.assets[ i ];
								wp.media
									.post( 'cloudinary-down-sync', {
										nonce: CLDN.nonce,
										asset: temp,
									} )
									.done( function ( asset ) {
										const updateAsset = function (
											newAsset,
											attach
										) {
											newAsset.uploading = false;
											attach.set( newAsset );
											wp.Uploader.queue.remove( attach );
											if (
												wp.Uploader.queue.length === 0
											) {
												wp.Uploader.queue.reset();
											}
										};
										if (
											typeof asset.resync !== 'undefined'
										) {
											asset.resync.forEach( function (
												newUpdateAsset
											) {
												const updateAttach = attachment.get(
													newUpdateAsset.id
												);
												updateAttach.set(
													newUpdateAsset
												);
											} );
										}
										if (
											typeof asset.fetch !== 'undefined'
										) {
											const attach = attachment.get(
												asset.attachment_id
											);
											attach.set( asset );
											library.add( attach );
											wp.Uploader.queue.add( attach );
											wp.ajax
												.send( {
													url: asset.fetch,
													beforeSend( request ) {
														request.setRequestHeader(
															'X-WP-Nonce',
															CLDN.nonce
														);
													},
													data: {
														src: asset.url,
														filename:
															asset.filename,
														attachment_id:
															asset.attachment_id,
														transformations:
															asset.transformations,
													},
												} )
												.done( function ( newAsset ) {
													const att = attachment.get(
														newAsset.id
													);
													updateAsset(
														newAsset,
														att
													);
												} )
												.fail( function ( err ) {
													updateAsset(
														asset,
														attach
													);
													library.remove( attach );
													selection.remove( attach );

													if (
														typeof err === 'string'
													) {
														alert( err );
													} else if (
														err.status === 500
													) {
														alert( 'HTTP error.' );
													}
												} );
										} else {
											const attach = attachment.get(
												asset.id
											);
											attach.set( asset );
											selection.add( attach );
										}
										if ( wp.Uploader.queue.length === 0 ) {
											wp.Uploader.queue.reset();
										}
										controller.content.mode( 'browse' );
									} );
							}
						},
					},
					document.querySelectorAll( '.dam-cloudinary' )[ 0 ]
				);
			}
			this.active = this.cid;
			return this;
		},
	} );
	const extendType = function ( type ) {
		const obj = {
			/**
			 * Bind region mode event callbacks.
			 *
			 * @see media.controller.Region.render
			 */
			bindHandlers() {
				type.prototype.bindHandlers.apply( this, arguments );
				this.on(
					'content:render:cloudinary',
					this.cloudinaryContent,
					this
				);
			},

			/**
			 * Render callback for the router region in the `browse` mode.
			 *
			 * @param {wp.media.view.Router} routerView
			 */
			browseRouter( routerView ) {
				type.prototype.browseRouter.apply( this, arguments );
				routerView.set( {
					cloudinary: {
						text: 'Cloudinary',
						priority: 60,
					},
				} );
			},

			/**
			 * Render callback for the content region in the `upload` mode.
			 */
			cloudinaryContent() {
				const state = this.state();
				const view = new Cloudinary( {
					controller: this,
					model: state,
				} ).render();
				this.content.set( view );
			},
		};

		return obj;
	};
	// Extending the current media library frames to add a new tab to each area.
	wp.media.view.MediaFrame.Select = MediaFrame.extend(
		extendType( MediaFrame )
	);
	wp.media.view.MediaFrame.Post = MediaFramePost.extend(
		extendType( MediaFramePost )
	);
	wp.media.view.MediaFrame.ImageDetails = MediaFrameImageDetails.extend(
		extendType( MediaFrameImageDetails )
	);
	wp.media.view.MediaFrame.VideoDetails = MediaFrameVideoDetails.extend(
		extendType( MediaFrameVideoDetails )
	);
}


/***/ }),

/***/ "./js/src/main.js":
/*!************************!*\
  !*** ./js/src/main.js ***!
  \************************/
/*! exports provided: cloudinary */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "cloudinary", function() { return cloudinary; });
/* harmony import */ var loading_attribute_polyfill__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! loading-attribute-polyfill */ "./node_modules/loading-attribute-polyfill/loading-attribute-polyfill.min.js");
/* harmony import */ var loading_attribute_polyfill__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(loading_attribute_polyfill__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_settings_page__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/settings-page */ "./js/src/components/settings-page.js");
/* harmony import */ var _components_settings_page__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_settings_page__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_sync__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/sync */ "./js/src/components/sync.js");
/* harmony import */ var _components_widget__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/widget */ "./js/src/components/widget.js");
/* harmony import */ var _components_widget__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_widget__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_global_transformations__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/global-transformations */ "./js/src/components/global-transformations.js");
/* harmony import */ var _components_terms_order__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/terms-order */ "./js/src/components/terms-order.js");
/* harmony import */ var _components_media_library__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/media-library */ "./js/src/components/media-library.js");
/* harmony import */ var _components_notices__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/notices */ "./js/src/components/notices.js");
/* harmony import */ var _components_ui__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/ui */ "./js/src/components/ui.js");
/* harmony import */ var _css_src_main_scss__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../css/src/main.scss */ "./css/src/main.scss");
/**
 * External dependencies
 */


/**
 * Internal dependencies
 */











// jQuery, because reasons.
window.$ = window.jQuery;

// Global Constants
const cloudinary = {
	UI: _components_ui__WEBPACK_IMPORTED_MODULE_8__["default"],
	Settings: (_components_settings_page__WEBPACK_IMPORTED_MODULE_1___default()),
	Sync: _components_sync__WEBPACK_IMPORTED_MODULE_2__["default"],
	Widget: (_components_widget__WEBPACK_IMPORTED_MODULE_3___default()),
	GlobalTransformations: _components_global_transformations__WEBPACK_IMPORTED_MODULE_4__["default"],
	TermsOrder: _components_terms_order__WEBPACK_IMPORTED_MODULE_5__["default"],
	MediaLibrary: _components_media_library__WEBPACK_IMPORTED_MODULE_6__["default"],
	Notices: _components_notices__WEBPACK_IMPORTED_MODULE_7__["default"],
};


/***/ }),

/***/ "./node_modules/loading-attribute-polyfill/loading-attribute-polyfill.min.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/loading-attribute-polyfill/loading-attribute-polyfill.min.js ***!
  \***********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
 * Loading attribute polyfill - https://github.com/mfranzke/loading-attribute-polyfill
 * @license Copyright(c) 2019 by Maximilian Franzke
 * Credits for the initial kickstarter / script to @Sora2455, and supported by @cbirdsong, @eklingen, @DaPo, @nextgenthemes, @diogoterremoto, @dracos, @Flimm, @TomS- and @vinyfc93 - many thanks for that !
 */
!function(e,t){"use strict";var r,a,o={rootMargin:"256px 0px",threshold:.01,lazyImage:'img[loading="lazy"]',lazyIframe:'iframe[loading="lazy"]'},n={loading:"loading"in HTMLImageElement.prototype&&"loading"in HTMLIFrameElement.prototype,scrolling:"onscroll"in window};function i(e){var t,r,a=[];"picture"===e.parentNode.tagName.toLowerCase()&&(t=e.parentNode,(r=t.querySelector("source[data-lazy-remove]"))&&t.removeChild(r),a=Array.prototype.slice.call(e.parentNode.querySelectorAll("source"))),a.push(e),a.forEach((function(e){e.hasAttribute("data-lazy-srcset")&&(e.setAttribute("srcset",e.getAttribute("data-lazy-srcset")),e.removeAttribute("data-lazy-srcset"))})),e.setAttribute("src",e.getAttribute("data-lazy-src")),e.removeAttribute("data-lazy-src")}function c(e){var t=document.createElement("div");for(t.innerHTML=function(e){var t=e.textContent||e.innerHTML,a="data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 "+((t.match(/width=['"](\d+)['"]/)||!1)[1]||1)+" "+((t.match(/height=['"](\d+)['"]/)||!1)[1]||1)+"%27%3E%3C/svg%3E";return!n.loading&&n.scrolling&&(void 0===r?t=t.replace(/(?:\r\n|\r|\n|\t| )src=/g,' lazyload="1" src='):("picture"===e.parentNode.tagName.toLowerCase()&&(t='<source srcset="'+a+'" data-lazy-remove="true"></source>'+t),t=t.replace(/(?:\r\n|\r|\n|\t| )srcset=/g," data-lazy-srcset=").replace(/(?:\r\n|\r|\n|\t| )src=/g,' src="'+a+'" data-lazy-src='))),t}(e);t.firstChild;)n.loading||!n.scrolling||void 0===r||!t.firstChild.tagName||"img"!==t.firstChild.tagName.toLowerCase()&&"iframe"!==t.firstChild.tagName.toLowerCase()||r.observe(t.firstChild),e.parentNode.insertBefore(t.firstChild,e);e.parentNode.removeChild(e)}function d(){document.querySelectorAll("noscript."+e).forEach(c),void 0!==window.matchMedia&&window.matchMedia("print").addListener((function(e){e.matches&&document.querySelectorAll(o.lazyImage+"[data-lazy-src],"+o.lazyIframe+"[data-lazy-src]").forEach((function(e){i(e)}))}))}"undefined"!=typeof NodeList&&NodeList.prototype&&!NodeList.prototype.forEach&&(NodeList.prototype.forEach=Array.prototype.forEach),"IntersectionObserver"in window&&(r=new IntersectionObserver((function(e,t){e.forEach((function(e){if(0!==e.intersectionRatio){var r=e.target;t.unobserve(r),i(r)}}))}),o)),a="requestAnimationFrame"in window?window.requestAnimationFrame:function(e){e()},/comp|inter/.test(document.readyState)?a(d):"addEventListener"in document?document.addEventListener("DOMContentLoaded",(function(){a(d)})):document.attachEvent("onreadystatechange",(function(){"complete"===document.readyState&&d()}))}("loading-lazy");


/***/ })

/******/ });
//# sourceMappingURL=cloudinary.js.map