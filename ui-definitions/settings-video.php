<?php
/**
 * Defines the settings structure for video.
 *
 * @package Cloudinary
 */

$settings = array(
	array(
		'type'  => 'panel',
		'title' => __( 'Video - Global Settings', 'cloudinary' ),
		'icon'  => $this->plugin->dir_url . 'css/video.svg',
		array(
			'type' => 'row',
			array(
				'type'  => 'column',
				'width' => '45%',
				array(
					'type' => 'group',
					array(
						'type'         => 'select',
						'slug'         => 'video_player',
						'title'        => __( 'Video player', 'cloudinary' ),
						'tooltip_text' => __( 'Which video player to use on all videos.', 'cloudinary' ),
						'default'      => 'wp',
						'options'      => array(
							'wp'  => __( 'WordPress Player', 'cloudinary' ),
							'cld' => __( 'Cloudinary Player', 'cloudinary' ),
						),
					),
					array(
						'type'      => 'group',
						'title'     => __( 'Player options', 'cloudinary' ),
						'condition' => array(
							'video_player' => 'cld',
						),
						array(
							'slug'        => 'video_controls',
							'description' => __( 'Show controls', 'cloudinary' ),
							'type'        => 'on_off',
							'default'     => 'on',
						),
						array(
							'slug'        => 'video_loop',
							'description' => __( ' Repeat video', 'cloudinary' ),
							'type'        => 'on_off',
							'default'     => false,
						),
						array(
							'slug'        => 'video_autoplay_mode',
							'title'       => __( 'Autoplay', 'cloudinary' ),
							'type'        => 'radio',
							'default'     => 'off',
							'options'     => array(
								'off'       => __( 'Off', 'cloudinary' ),
								'always'    => __( 'Always', 'cloudinary' ),
								'on-scroll' => __( 'On-Scroll (Autoplay when in view)', 'cloudinary' ),
							),
							'description' => sprintf(
								// translators: Placeholders are <a> tags.
								__( 'Please note that when choosing "always", the video will autoplay without sound (muted). This is a built-in browser feature and applies to all major browsers.%1$sRead more about muted autoplay%2$s', 'cloudinary' ),
								'<br><a href="https://developers.google.com/web/updates/2016/07/autoplay" target="_blank">',
								'</a>'
							),
						),
					),
					array(
						'type'         => 'checkbox',
						'slug'         => 'video_limit_bitrate',
						'title'        => __( 'Bitrate', 'cloudinary' ),
						'tooltip_text' => __( 'If set, all videos will be delivered in the defined bitrate.', 'cloudinary' ),
						'options'      => array(
							'1' => __( 'Limit bitrate', 'cloudinary' ),
						),
						'attributes'   => array(
							'data-context' => 'video',
						),
					),

				),
				array(
					'type' => 'group',
					array(
						'type'        => 'on_off',
						'slug'        => 'video_optimization',
						'title'       => __( 'Video Optimization', 'cloudinary' ),
						'description' => __( 'Optimize videos on my site.', 'cloudinary' ),
					),
				),
				array(
					'type'        => 'group',
					'title'       => __( 'Advanced Optimization', 'cloudinary' ),
					'collapsible' => 'closed',
					array(
						'type'         => 'select',
						'slug'         => 'video_format',
						'title'        => __( 'Video format', 'cloudinary' ),
						'tooltip_text' => __( 'Optimize videos on my site.', 'cloudinary' ),
						'default'      => 'auto',
						'options'      => array(
							'none' => __( 'Not Set', 'cloudinary' ),
							'auto' => __( 'Auto', 'cloudinary' ),
						),
						'attributes'   => array(
							'data-context' => 'video',
							'data-meta'    => 'f',
						),
					),
					array(
						'type'         => 'select',
						'slug'         => 'video_quality',
						'title'        => __( 'Video quality', 'cloudinary' ),
						'tooltip_text' => __( 'Optimize videos on my site.', 'cloudinary' ),
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
							'data-context' => 'video',
							'data-meta'    => 'q',
						),
					),

				),
				array(
					'type'       => 'text',
					'slug'       => 'video_freeform',
					'title'      => __( 'Custom Transformation', 'cloudinary' ),
					'attributes' => array(
						'data-context' => 'video',
						'placeholder'  => __( 'w_90,r_max' ),
					),
				),
			),
			array(
				'type'  => 'column',
				'width' => '55%',
				array(
					'type'  => 'video_preview',
					'title' => __( 'Video Preview', 'cloudinary' ),
				),
			),

		),
	),
	array(
		'type' => 'submit',
	),
);

return apply_filters( 'cloudinary_admin_tab_global_transformations', $settings );
