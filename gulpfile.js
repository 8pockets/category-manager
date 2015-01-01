var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

//gulp.task(“タスク名”,function() {});でタスクの登録をおこないます。
//gulp.src(“MiniMatchパターン”)で読み出したいファイルを指定します。
//pipe(行いたい処理)でsrcで取得したファイルに処理を施します
//gulp.dest(“出力先”)で出力先に処理を施したファイルを出力します。

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