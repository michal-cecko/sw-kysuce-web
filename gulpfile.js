'use strict';
let gulp = require('gulp'),
    sass = require('gulp-dart-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify')

sass.compiler = require('sass')

const SCSS_PATH = "assets/sass";
const JS_PATH = "assets/js";
const DIST_PATH = "dist";

gulp.task('scripts', function () {
    return gulp.src([`${JS_PATH}/**/*.js`, `!${JS_PATH}/libs/**/*.js`])
        .pipe(uglify()) // Minify the JavaScript files
        .pipe(rename({ suffix: '.min' })) // Rename them with .min suffix
        .pipe(gulp.dest(`${DIST_PATH}/js`)); // Output the minified files to dist/js directory
});

gulp.task('libs-scripts', function () {
    return gulp.src([`${JS_PATH}/libs/**/*.js`])
        .pipe(uglify()) // Minify the JavaScript files
        .pipe(rename({ suffix: '.min' })) // Rename them with .min suffix
        .pipe(gulp.dest(`${DIST_PATH}/js/libs`)); // Output the minified files to dist/js directory
});

gulp.task('sass', function () {
    return gulp.src(`${SCSS_PATH}/main.scss`)
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(`${DIST_PATH}/css`))
});

gulp.task('admin-sass', function () {
    return gulp.src(`${SCSS_PATH}/admin/**/*.scss`)
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(`${DIST_PATH}/css/admin`))
});

gulp.task('assets:watch', function () {
    gulp.watch([`${SCSS_PATH}/**/*.scss`, `!${SCSS_PATH}/admin/**/*.scss`], gulp.series('sass'))
    gulp.watch([`${SCSS_PATH}/admin/**/*.scss`], gulp.series('admin-sass'))
    gulp.watch(`${JS_PATH}/**/*.js`, gulp.series('scripts'))
});

gulp.task('production', gulp.series('sass', 'admin-sass', 'scripts', 'libs-scripts'))