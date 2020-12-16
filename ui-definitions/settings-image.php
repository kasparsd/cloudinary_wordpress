<?php
/**
 * Defines the settings structure for images.
 *
 * @package Cloudinary
 */

$settings = array(
	array(
		'type'  => 'panel',
		'title' => __( 'Image - Global Settings', 'cloudinary' ),
		'icon'  => $this->plugin->dir_url . 'css/image.svg',
		array(
			'type' => 'column',
			array(
				'type'  => 'column',
				'width' => '40%',
				array(
					'type' => 'group',
					array(
						'type'        => 'on_off',
						'slug'        => 'image_optimization',
						'title'       => __( 'Image Optimization', 'cloudinary' ),
						'description' => __( 'Optimize images on my site.', 'cloudinary' ),
					),

				),
				array(
					'type'        => 'group',
					'title'       => __( 'Advanced Optimization', 'cloudinary' ),
					'collapsible' => 'closed',
					array(
						'type'         => 'select',
						'slug'         => 'image_format',
						'title'        => __( 'Image format', 'cloudinary' ),
						'tooltip_text' => __( 'Optimize images on my site.', 'cloudinary' ),
						'default'      => 'auto',
						'options'      => array(
							'none' => __( 'Not Set', 'cloudinary' ),
							'auto' => __( 'Auto', 'cloudinary' ),
							'png'  => __( 'PNG', 'cloudinary' ),
							'jpg'  => __( 'JPG', 'cloudinary' ),
							'gif'  => __( 'GIF', 'cloudinary' ),
							'webp' => __( 'WebP', 'cloudinary' ),
						),
						'attributes'   => array(
							'data-context' => 'image',
							'data-meta'    => 'f',
						),
					),
					array(
						'type'         => 'select',
						'slug'         => 'image_quality',
						'title'        => __( 'Image quality', 'cloudinary' ),
						'tooltip_text' => __( 'Optimize images on my site.', 'cloudinary' ),
						'default'      => 'auto',
						'options'      => array(
							'none'      => __( 'Not Set', 'cloudinary' ),
							'auto'      => __( 'Auto', 'cloudinary' ),
							'auto:best' => __( 'Auto Best', 'cloudinary' ),
							'auto:good' => __( 'Auto Good', 'cloudinary' ),
							'auto:eco'  => __( 'Auto Eco', 'cloudinary' ),
							'auto:low'  => __( 'Auto Low', 'cloudinary' ),
							'100'       => '100',
							'80'        => '80',
							'60'        => '60',
							'40'        => '40',
							'20'        => '20',
						),
						'attributes'   => array(
							'data-context' => 'image',
							'data-meta'    => 'q',
						),
					),

				),
				array(
					'type' => 'group',
					array(
						'type'        => 'on_off',
						'slug'        => 'enable_breakpoints',
						'title'       => __( 'Image Breakpoints', 'cloudinary' ),
						'description' => __( 'Enable responsive images.', 'cloudinary' ),
					),
					array(
						'type'      => 'group',
						'title'     => __( 'Image Breakpoints', 'cloudinary' ),
						'condition' => array(
							'enable_breakpoints' => true,
						),
						array(
							'type'       => 'number',
							'slug'       => 'breakpoints',
							'title'      => __( 'Max breakpoints', 'cloudinary' ),
							'suffix'     => __( 'Valid values: 3-200', 'cloudinary' ),
							'default'    => 3,
							'attributes' => array(
								'min' => 3,
								'max' => 200,
							),
						),
						array(
							'type'    => 'number',
							'slug'    => 'bytes_step',
							'title'   => __( 'Byte step', 'cloudinary' ),
							'suffix'  => __( 'bytes', 'cloudinary' ),
							'default' => 200,
						),
						array(
							'type'    => 'number',
							'slug'    => 'max_width',
							'title'   => __( 'Image width limit', 'cloudinary' ),
							'prefix'  => __( 'Max', 'cloudinary' ),
							'suffix'  => __( 'px', 'cloudinary' ),
							'default' => $this->default_max_width(),
						),
						array(
							'type'    => 'number',
							'slug'    => 'min_width',
							'prefix'  => __( 'Min', 'cloudinary' ),
							'suffix'  => __( 'px', 'cloudinary' ),
							'default' => 800,
						),
					),
				),
				array(
					'type'       => 'text',
					'slug'       => 'image_freeform',
					'title'      => __( 'Custom Transformation', 'cloudinary' ),
					'attributes' => array(
						'data-context' => 'image',
						'placeholder'  => __( 'w_90,r_max' ),
					),
				),
			),
			array(
				'type'  => 'column',
				'width' => '55%',
				array(
					'type'  => 'image_preview',
					'title' => __( 'Image Preview', 'cloudinary' ),
				),
			),

		),
	),
	array(
		'type' => 'submit',
	),
);

return apply_filters( 'cloudinary_admin_tab_global_transformations', $settings );
