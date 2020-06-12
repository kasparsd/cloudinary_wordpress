
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

		wp_deploy: {
			options: {
				plugin_slug: 'cloudinary-image-management-and-manipulation-in-the-cloud-cdn',
				plugin_main_file: 'cloudinary.php',
				build_dir: '<%= dist_dir %>',
				assets_dir: 'assets',
			},
			default: {},
		},
	} );

	grunt.registerTask(
		'build', [
			'clean',
			'copy',
		]
	);

	grunt.registerTask(
		'deploy', [
			'build',
			'wp_deploy',
		]
	);
};
