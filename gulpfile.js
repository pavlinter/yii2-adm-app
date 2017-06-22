var gulp = require('gulp'),
    watch = require('gulp-watch'),
    less = require('gulp-less'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    autoprefixer = require('gulp-autoprefixer'),
    cssmin = require('gulp-cssmin');


gulp.task('less', function () {
    return gulp.src([
            'assets_b/common/less/*.less'
        ])
        .pipe(less().on('error', function(err) {
            console.log(err);
        })).pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade : false
        }))
        .pipe(cssmin())
        .pipe(gulp.dest('assets_b/common/css'));
});

gulp.task('sass', function () {
    return gulp.src([
        'assets_b/common/sass/*.scss'
    ])
        .pipe(sass().on('error', function(err) {
            console.log(err);
        })).pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade : false
        }))
        .pipe(cssmin())
        .pipe(gulp.dest('assets_b/common/css'));
});


gulp.task('watch', function () {
    gulp.watch([
        'assets_b/common/less/*.less'
    ], ['less']);

    /*gulp.watch([
        'assets_b/common/sass/!*.scss'
    ], ['sass']);*/
});
