module.exports = function( grunt ) {

	// Load all Grunt plugins.
	require( 'load-grunt-tasks' )( grunt );

	grunt.initConfig( {

		dist_dir: 'build',

		clean: {
			build: [ '<%= dist_dir %>' ],
		},

		copy: {
			dist: {
				src: [
					'css/**',
					'js/**',
					'php/**',
					'ui-definitions/**',
					'*.php',
					'readme.txt',
				],
				dest: '<%= dist_dir %>',
				expand: true,
			},
		},

		compress: {
			release: {
				options: {
					archive: 'cloudinary-image-management-and-manipulation-in-the-cloud-cdn.zip',
				},
				cwd: 'build',
				dest: 'cloudinary-image-management-and-manipulation-in-the-cloud-cdn',
				src: [
					'**/*',
				],
			},
		},

		wp_deploy: {
			options: {
				plugin_slug: 'cloudinary-image-management-and-manipulation-in-the-cloud-cdn',
				plugin_main_file: 'cloudinary.php',
				build_dir: '<%= dist_dir %>',
				assets_dir: 'assets',
			},
			default: {
				// Default deploy to trunk and a tag release.
			},
			assets: {
				// Deploy only screenshots and icons.
				deploy_trunk: false,
				deploy_tag: false,
			},
		},

	} );

	grunt.registerTask(
		'build', [
			'clean',
			'copy',
			'compress',
		]
	);

	grunt.registerTask(
		'deploy', [
			'build',
			'wp_deploy',
		]
	);

	grunt.registerTask(
		'deploy-assets', [
			'wp_deploy:assets',
		]
	);
};
