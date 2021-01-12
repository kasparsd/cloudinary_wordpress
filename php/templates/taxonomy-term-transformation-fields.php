<?php
/**
 * Edit term, global transformations template.
 *
 * @package Cloudinary
 */

$this->init_term_transformations();
?>
<tr>
	<td colspan="2"><h2><?php esc_html_e( 'Global Transformations', 'cloudinary' ); ?></h2></td>
</tr>
<?php foreach ( $this->taxonomy_fields as $context => $set ) : ?>
	<?php foreach ( $set as $setting ) : ?>
		<tr class="form-field term-<?php echo esc_attr( $setting->get_slug() ); ?>-wrap">
			<th scope="row">
				<label for="cloudinary_<?php echo esc_attr( $setting->get_slug() ); ?>"><?php echo esc_html( $setting->get_param( 'title' ) ); ?></label>
			</th>
			<td>
				<?php $setting->get_component()->render( true ); ?>
			</td>
		</tr>
	<?php endforeach; ?>
<?php endforeach; ?>
