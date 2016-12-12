'use strict';

module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        theme: grunt.file.readJSON('themes.json'),
        replace: { },
        watch: {
            template: {
                files: ['sass/colors.scss.tpl', 'index.php.tpl'],
                tasks: ['template:blue']
            }
        },
        shell: { },
        template: { },
        sass: {
            dist: {
                options: {
                    style: 'compressed',
                    compass: true
                },
                files: {
                    'css/main.css': 'sass/main.scss'
                }
            }
        },
        copy: { }
    });

    var themeObj = grunt.config.get('theme');
    var pkg = grunt.config.get('pkg');
    for ( var key in themeObj ) {
        var theme = themeObj[key];

        grunt.config( 'template.' + key, {
            options: {
                data: {
                    theme: themeObj[key],
                    pkg: pkg
                }
            },
            files: {
                'sass/colors.scss': ['sass/colors.scss.tpl'],
                'index.php': ['index.php.tpl']
            }
        });

        // copy scss files, compile sass, copy to root + replace variables
        grunt.config( 'watch.' + key, {
            files: ['sass/**'],
            tasks: ['template:' + key, 'sass'],
            options: {
                interrupt: true,
            }
        });

        grunt.config( 'copy.' + key, {
            files: [
                {
                    expand: true,
                    src: [
                        '*.php',
                        'admin/**',
                        'common/**',
                        'css/**',
                        'favicon/**',
                        'fonts/**',
                        'images/**',
                        'js/**',
                        'languages/**'
                    ],
                    dest: 'dist/' + theme.slug
                }
            ]
        });

        grunt.config( 'copy.screenshoot_' + key, {
            files: [
                {
                    expand: false,
                    src: [
                        'screenshot/' + key + '/screenshot.png'
                    ],
                    dest: 'dist/' + theme.slug + '/screenshot.png'
                }
            ]
        });

        // replace theme strings
        grunt.config( 'replace.'+ key , {
            src: [
                'dist/' + theme.slug + '/*.php',
                'dist/' + theme.slug + '/admin/*.php',
                'dist/' + theme.slug + '/common/*.php',
                'dist/' + theme.slug + '/js/*.js'
            ],
            overwrite: true,
            replacements: [
                {
                    from: 'bender',
                    to: theme.slug
                },
                {
                    from: 'BENDER_THEME_VERSION',
                    to: theme.slug.toUpperCase() + '_THEME_VERSION'
                },
                {
                    from: 'BENDER_THEME_MARKET_SLUG',
                    to: theme.slug
                }
            ]
        });

        var archive = '../packages/theme_'+ theme.slug + '_' + pkg.version + '.zip';
        grunt.config( 'shell.compress_'+ key , {
            command : 'cd dist/; zip -r ' + archive + ' ' + theme.slug + '/;',
            options: {
                stdout: false
            }
        });

        grunt.registerTask('dist:' + key, ['template:' + key, 'sass', 'copy:' + key, 'copy:screenshoot_' + key, 'replace:' + key, 'shell:compress_' + key]);
    }

    grunt.registerTask('dist', ['dist:red', 'dist:black', 'dist:purple', 'dist:blue'])

    // Actually load this plugin's task(s).
    grunt.loadTasks('tasks');

    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-text-replace');
    grunt.loadNpmTasks('grunt-template');
};