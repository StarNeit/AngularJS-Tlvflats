'use strict';

require('dotenv').load({ silent: true });

//   Libraries
var argv = require('yargs').argv,
    fs = require('fs'),

    gulp = require('gulp'),
    jade = require('gulp-jade'),
    sass = require('gulp-sass'),
    wrap = require('gulp-wrap'),
    babel = require('gulp-babel'),
    concat = require('gulp-concat'),
    rimraf = require('gulp-rimraf'),
    sourcemaps = require('gulp-sourcemaps');

//   Paths and configurations

var paths = {
    js : [
        'front/modules/app.js',
        'front/modules/**/*.module.js',
        'front/modules/**/*.js'
    ],
    libs : [
        'node_modules/jquery/dist/jquery.js',
        'node_modules/angular/angular.js',
        'node_modules/angular-aria/angular-aria.js',
        'node_modules/angular-touch/angular-touch.js',
        'node_modules/angular-animate/angular-animate.js',
        'node_modules/angular-messages/angular-messages.js',
        'node_modules/angular-material/angular-material.js',
        'node_modules/angular-carousel/dist/angular-carousel.js',
        'node_modules/angular-ui-router/release/angular-ui-router.js',
        'node_modules/angular-permission/dist/angular-permission.js',
        'node_modules/satellizer/dist/satellizer.js',
        'node_modules/lodash/lodash.js',
        'node_modules/angular-simple-logger/dist/angular-simple-logger.js',
        'node_modules/angular-google-maps/dist/angular-google-maps.js',
        'node_modules/moment/min/moment.min.js',
        'node_modules/angularjs-datepicker/src/js/angular-datepicker.js'
    ],
    adminlibs : [
       'node_modules/jquery/dist/jquery.js',
       'node_modules/jquery-locationpicker/dist/locationpicker.jquery.js'
    ],
    styles : [
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/angular-material/angular-material.scss',
        'node_modules/font-awesome/scss/font-awesome.scss',
        'node_modules/angular-carousel/dist/angular-carousel.css',
        'node_modules/angularjs-datepicker/src/css/angular-datepicker.css'
    ],
    fonts : [
        'node_modules/font-awesome/fonts/*.*',
        'front/fonts/*.*'
    ]
};

//
//   Layout
//
gulp.task('jade', function() {
    return gulp
        .src('front/modules/**/*.jade')
        .pipe(jade({
            doctype : 'html',
            pretty : true,
            locals : {
                config : {
                    // TEST
                    port : process.env.SYMFONY__ENV__DATABASE_PORT
                }
            }
        }))
        .pipe(gulp.dest('web/'))
});

gulp.task('copy-index', ['jade'], function() {
    return gulp
        .src('web/index.html')
        .pipe(gulp.dest('app/Resources/'));
});

gulp.task('templates', ['jade', 'copy-index']);

//
//   CSS related tasks
//
gulp.task('sass-app', function() {
    return gulp
        .src('front/styles/styles.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/css'))
});

gulp.task('sass-vendor', function() {
    return gulp
        .src(paths.styles)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('vendor.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/css'))
});

gulp.task('styles', ['sass-app', 'sass-vendor']);

//
//   JS related tasks
//
gulp.task('js-app', function() {
    return gulp
        .src(paths.js)
        .pipe(wrap("\n(function(){\n<%= contents %>\n})();"))
        .pipe(babel({}))
        .pipe(concat('all.js'))
        .pipe(gulp.dest('web/js'))
});

gulp.task('js-libs', function() {
    return gulp
        .src(paths.libs)
        .pipe(sourcemaps.init())
        .pipe(concat('vendor.js'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/js'));
});

gulp.task('js-admin-libs', function() {
     return gulp
	.src(paths.adminlibs)
	.pipe(sourcemaps.init())
	.pipe(concat('adminvendor.js'))
	.pipe(sourcemaps.write('.'))
	.pipe(gulp.dest('web/js'));
});

gulp.task('js', ['js-app', 'js-libs', 'js-admin-libs']);

//
//   Common tasks
//
gulp.task('copy-images', function () {
    return gulp
        .src('front/images/**/*.*')
        .pipe(gulp.dest('web/images'));
});

gulp.task('copy-temp-images', function () {
    return gulp
        .src('front/images/temp/*.*')
        .pipe(gulp.dest('web/uploads'))
        .pipe(gulp.dest('web/uploads/small'));
});

gulp.task('copy-fonts', function() {
    return gulp
        .src(paths.fonts)
        .pipe(gulp.dest('web/fonts/'))
});

gulp.task('copy-files', function() {
    return gulp
        .src([
            'front/.htaccess',
            'front/*.php',
            'front/robots.txt'
        ])
        .pipe(gulp.dest('web/'));
});

gulp.task('install', [
    'copy-files',
    'copy-images',
    'copy-fonts',
    'js',
    'styles',
    'templates'
]);

gulp.task('clean', function() {
    return gulp.src([
            'web',
            'node_modules',
            'app/Resources/index.html'
        ], { read: false })
        .pipe(rimraf());
});

gulp.task('watch', function () {
    gulp.watch(['front/modules/**/*.jade'], ['templates']);
    gulp.watch(['front/modules/**/*.js'], ['js-app']);
    gulp.watch(['front/styles/**/*.scss'], ['sass-app']);
    gulp.watch(['front/images/**/*.*'], ['copy-images']);
});
