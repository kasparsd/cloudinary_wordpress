<?php
/**
 * Add new taxonomy, global transformations template.
 *
 * @package Cloudinary
 */

wp_enqueue_style( 'cld-player' );
wp_enqueue_script( 'cld-player' );

wp_add_inline_script( 'cloudinary', 'var CLD_GLOBAL_TRANSFORMATIONS = CLD_GLOBAL_TRANSFORMATIONS ? CLD_GLOBAL_TRANSFORMATIONS : {};', 'before' );
?>
<div class="cloudinary-collapsible">
	<div class="cloudinary-collapsible__toggle">
		<h2><?php esc_html_e( 'Cloudinary Global Transformations', 'cloudinary' ); ?></h2>
		<button type="button"><i class="dashicons dashicons-arrow-down-alt2"></i></button>
	</div>
	<div class="cloudinary-collapsible__content" style="display:none;">
		<?php foreach ( $this->taxonomy_fields as $context => $set ) : ?>
			<?php foreach ( $set as $setting ) : ?>
				<?php $setting->get_component()->render( true ); ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</div>
