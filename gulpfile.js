var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    watch = require('gulp-watch');

gulp.task('images', function() {
  return gulp.src('src/images/*.{svg,jpg,png,gif}')
    .pipe(gulp.dest('web/images'));
});

gulp.task('scripts', function() {
  return gulp.src('src/scripts/*.js')
    .pipe(gulp.dest('web/scripts'));
});

gulp.task('styles', function() {
  return gulp.src('src/styles/*.scss')
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gulp.dest('web/styles'));
});

gulp.task('default', function() {
  gulp.watch('src/**/*', ['images', 'scripts', 'styles']);
});
