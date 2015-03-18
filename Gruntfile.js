module.exports = function (grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		// @todo .scss -> .css + .min.css
		// @todo checktextdomains
		// @todo makepot/pot

		bowercopy: {
			options: {
				// Bower components folder will be removed afterwards
				clean: true
			},
			libs: {
				options: {
					destPrefix: 'libs'
				},
				files: {
					'Google': 'google-api-php-client/src/Google'
				}
			}
		},

		// Generate README.md from readme.txt
		wp_readme_to_markdown: {
			readme: {
				files: {
					'README.md': 'readme.txt'
				},
				options: {
//					screenshot_url: 'https://raw.githubusercontent.com/WPStore/{plugin}/master/.assets/{screenshot}.png'
				}
			}
		}

	}); // END grunt.initConfig()

	// register tasks
	grunt.registerTask( 'default', [ 'bowercopy' ] );
	grunt.registerTask( 'update', [ 'bowercopy' ] );
	grunt.registerTask( 'readme', [ 'wp_readme_to_markdown' ] );

};
