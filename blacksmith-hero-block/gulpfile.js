const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const browserSync = require('browser-sync').create();

// Paths
const paths = {
    scss: {
        src: 'src/scss/**/*.scss',
        dest: 'assets/css/',
        main: 'src/scss/main.scss'
    },
    js: {
        src: 'src/js/**/*.js',
        dest: 'assets/js/'
    }
};

// Error handling
const handleError = (err) => {
    notify.onError({
        title: 'Gulp Error',
        message: 'Error: <%= error.message %>'
    })(err);
};

// SCSS compilation
function styles() {
    return gulp.src(paths.scss.main)
        .pipe(plumber({ errorHandler: handleError }))
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'expanded',
            precision: 6
        }))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.scss.dest))
        .pipe(browserSync.stream());
}

// SCSS minification for production
function stylesProduction() {
    return gulp.src(paths.scss.main)
        .pipe(plumber({ errorHandler: handleError }))
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(cleanCSS({
            level: 2
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(paths.scss.dest));
}

// JavaScript processing
function scripts() {
    return gulp.src(paths.js.src)
        .pipe(plumber({ errorHandler: handleError }))
        .pipe(sourcemaps.init())
        .pipe(concat('main.js'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.js.dest))
        .pipe(browserSync.stream());
}

// JavaScript minification for production
function scriptsProduction() {
    return gulp.src(paths.js.src)
        .pipe(plumber({ errorHandler: handleError }))
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(paths.js.dest));
}

// Watch files
function watchFiles() {
    gulp.watch(paths.scss.src, styles);
    gulp.watch(paths.js.src, scripts);
}

// BrowserSync
function serve() {
    browserSync.init({
        proxy: 'blacksmith-acf-blocks.ddev.site',
        notify: false
    });
}

// Build tasks
const build = gulp.series(gulp.parallel(styles, scripts));
const production = gulp.series(gulp.parallel(stylesProduction, scriptsProduction));
const dev = gulp.series(build, gulp.parallel(watchFiles, serve));
const watch = watchFiles;

// Export tasks
exports.styles = styles;
exports.scripts = scripts;
exports.build = build;
exports.production = production;
exports.watch = watch;
exports.dev = dev;
exports.default = build;


// src/scss/abstracts/_variables.scss