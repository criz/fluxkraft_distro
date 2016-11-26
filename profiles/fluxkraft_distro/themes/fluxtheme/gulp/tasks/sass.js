var gulp         = require('gulp');
var plumber      = require('gulp-plumber');
var browsersync  = require('browser-sync');
var reload       = browsersync.reload;
var sass         = require('gulp-sass');
var postcss      = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var sourcemaps   = require('gulp-sourcemaps');
var config       = require('../config');


/**
 * Generate CSS from all our scss files.
 * Build sourcemaps
 */
gulp.task('sass', function() {
  var sassConfig = config.sass_options_dev;

  sassConfig.onError = browsersync.notify;

  return gulp.src(config.sass_all.src)
    // Initializes sourcemaps.
    .pipe(sourcemaps.init())
    .pipe(sass(sassConfig)).on('error', sass.logError)
    .pipe(postcss([ autoprefixer(config.autoprefixer) ]))
    // Writes sourcemaps.
    .pipe(sourcemaps.write('maps'))
    .pipe(gulp.dest(config.sass_all.dest))
    .pipe(reload({stream: true}));
});

/**
 * Generate CSS from SCSS for productive use.
 * Build sourcemaps
 */
gulp.task('sass-prod', function() {
  var sassConfig = config.sass_options_prod;

  return gulp.src(config.sass_all.src)
    .pipe(sass(sassConfig))
    .pipe(postcss([ autoprefixer(config.autoprefixer) ]))
    .pipe(gulp.dest(config.sass_all.dest));
});