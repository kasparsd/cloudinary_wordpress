module.exports = function ( grunt ) {
	// Load all Grunt plugins.
	require( 'load-grunt-tasks' )( grunt );

	const pluginVersion = grunt.file
		.read( 'cloudinary.php' )
		.match( /Version:\s*(.+)$/im )[ 1 ];

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
					'!**/src/**',
				],
				dest: '<%= dist_dir %>',
				expand: true,
			},
		},

		replace: {
			version: {
				src: '<%= dist_dir %>/readme.txt',
				overwrite: true,
				replacements: [
					{
						from: 'STABLETAG',
						to: pluginVersion,
					},
				],
			},
		},

		compress: {
			release: {
				options: {
					archive:
						'cloudinary-image-management-and-manipulation-in-the-cloud-cdn.zip',
				},
				cwd: 'build',
				dest:
					'cloudinary-image-management-and-manipulation-in-the-cloud-cdn',
				src: [ '**/*' ],
			},
		},

		wp_deploy: {
			options: {
				plugin_slug:
					'cloudinary-image-management-and-manipulation-in-the-cloud-cdn',
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

	grunt.registerTask( 'package', [ 'clean', 'copy', 'replace', 'compress' ] );

	grunt.registerTask( 'deploy', [ 'package', 'wp_deploy' ] );

	grunt.registerTask( 'deploy-assets', [ 'wp_deploy:assets' ] );
};
