var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

//Sass
gulp.task("sass", function() {
    gulp.src("./sass/**/*.scss")
    	.pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest("./css"));
});

//gulp watch
gulp.task("default", function() {
    gulp.watch(["js/**/*.js","!js/min/**/*.js"],["js"]);
    gulp.watch("./sass/**/*.scss",["sass"]);
});