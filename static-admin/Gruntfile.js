// Load Grunt
module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        // Metadata.
        meta: {
            cssSrcPath: 'src/css',
            cssDeployPath: 'css',
            jsSrcPath: 'src/js',
            jsDeployPath: 'js'
        },
        // Tasks
        sass: { // Begin Sass Plugin
            dist: {
                options: {
                    sourcemap: true
                },
                files: [{
                    expand: true,
                    cwd: '<%= meta.cssSrcPath %>',
                    src: ['*.scss'],
                    dest: '<%= meta.cssDeployPath %>',
                    ext: '.css'
                }]
            }
        },
        postcss: { // Begin Post CSS Plugin
            options: {
                map: false,
                processors: [
                    require('autoprefixer')({
                        browsers: ['last 2 versions']
                    })
                ]
            },
            dist: {
                src: '<%= meta.cssDeployPath %>/style.css'
            }
        },
        cssmin: { // Begin CSS Minify Plugin
            target: {
                files: [{
                    expand: true,
                    cwd: '<%= meta.cssDeployPath %>',
                    src: ['*.css', '!*.min.css'],
                    dest: '<%= meta.cssDeployPath %>',
                    ext: '.min.css'
                }]
            }
        },
        uglify: { // Begin JS Uglify Plugin
            build: {
                src: ['<%= meta.jsSrcPath %>/*.js'],
                dest: '<%= meta.jsDeployPath %>/script.min.js'
            }
        },
        watch: { // Compile everything into one task with Watch Plugin
            css: {
                files: '<%= meta.cssSrcPath %>/*.scss',
                tasks: ['sass', 'postcss', 'cssmin']
            },
            js: {
                files: '<%= meta.jsSrcPath %>/*.js',
                tasks: ['uglify']
            }
        }
    });
    // Load Grunt plugins
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Register Grunt tasks
    grunt.registerTask('default', ['watch']);
};