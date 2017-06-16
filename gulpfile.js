'use strict';

var gulp = require('gulp');
var phpunit = require('gulp-phpunit');
var watch = require('gulp-watch');

gulp.task('phpunit', function () {
    gulp.src('')
        .pipe(phpunit());
});

gulp.task('test', ['phpunit']);

gulp.task('watch', function () {
    gulp.watch(['**/*.php'], ['test']);
});

gulp.task('default', ['watch']);