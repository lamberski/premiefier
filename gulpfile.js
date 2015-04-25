var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var watch = require('gulp-watch');

gulp.task('images', function() {
  return gulp.src('source/images/**/*.{svg,jpg,png,gif}')
    .pipe(gulp.dest('public/images'));
});

gulp.task('scripts', function() {
  return gulp.src('source/scripts/**/*.js')
    .pipe(gulp.dest('public/scripts'));
});

gulp.task('styles', function() {
  return gulp.src('source/styles/**/*.scss')
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gulp.dest('public/styles'));
});

gulp.task('default', function() {
  gulp.watch('source/**/*', ['images', 'scripts', 'styles']);
});
