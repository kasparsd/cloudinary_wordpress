/**
 * External dependencies
 */
import classNames from 'classnames';

export default ( { value, children, icon, onChange, current } ) => {
	const isActive =
		typeof value === 'object'
			? JSON.stringify( value ) === JSON.stringify( current )
			: current === value;

	return (
		<button
			onClick={ () => onChange( value ) }
			className={ classNames( 'radio-select', {
				'radio-select--active': isActive,
			} ) }
		>
			{ icon }
			<div className="radio-select__label">{ children }</div>
		</button>
	);
};
