/*
 * grunt-contrib-less
 * http://gruntjs.com/
 *
 * Copyright (c) 2013 Tyler Kellen, contributors
 * Licensed under the MIT license.
 */

'use strict';
module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        less: {
            compile: {
                options: {
                    paths: ['oc-admin/themes/modern/less'],
                    yuicompress: true
                },
                files: {
                    'oc-admin/themes/modern/css/main.css': 'oc-admin/themes/modern/less/main.less'
                }
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'compressed',
                    compass: true
                },
                files: {
                    'oc-content/themes/bender/css/main.css': 'oc-content/themes/bender/sass/main.scss'
                }
            }
        },
        uglify: {
            options: {
                mangle: false
            },
            target: {
                files: {
                    'oc-includes/osclass/assets/js/date.min.js': 'oc-includes/osclass/assets/js/date.js',
                    'oc-includes/osclass/assets/js/jquery.json.min.js': 'oc-includes/osclass/assets/js/jquery.json.js',
                    'oc-includes/osclass/assets/js/jquery.treeview.min.js': 'oc-includes/osclass/assets/js/jquery.treeview.js'
                }
            }
        }
    });
    // Actually load this plugin's task(s).
    grunt.loadTasks('tasks');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['uglify']);
};