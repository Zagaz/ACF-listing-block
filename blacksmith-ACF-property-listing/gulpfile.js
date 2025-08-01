const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');

const paths = {
    scss: 'assets/scss/**/*.scss',
    css: 'assets/css'
};

function styles() {
    return gulp.src(paths.scss)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.css));
}

function watch() {
    gulp.watch(paths.scss, styles);
}

exports.styles = styles;
exports.watch = watch;
exports.default = gulp.series(styles, watch);