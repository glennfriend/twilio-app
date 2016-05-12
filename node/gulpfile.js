var gulp = require("gulp");

/**
 *  front-end resource
 */
gulp.task('buildHomeAssets', function () {
    gulp.src('./node_modules/bootstrap/dist/**')    .pipe(gulp.dest("../home/assets/bootstrap/"));
});

/**
 *  back-end resource
 */
gulp.task('buildAdminAssets', function () {
    gulp.src('./node_modules/bootstrap-v4-dev/dist/**') .pipe(gulp.dest("../home/admin/assets/bootstrap/"));
    gulp.src('./node_modules/jquery/dist/*')            .pipe(gulp.dest("../home/admin/assets/jquery/"));
});

// --------------------------------------------------------------------------------

gulp.task('default', function() {
    gulp.run('buildHomeAssets', 'buildAdminAssets');
});
