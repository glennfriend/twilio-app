var gulp = require("gulp");

/**
 *  front-end resource
 */
gulp.task('toAssets', function () {
    gulp.src('./node_modules/bootstrap/dist/**')    .pipe(gulp.dest("../home/assets/bootstrap/"));
    gulp.src('./node_modules/jquery/dist/*')        .pipe(gulp.dest("../home/assets/jquery/"));
});

// --------------------------------------------------------------------------------

gulp.task('default', function() {
    gulp.run('toAssets');
});
