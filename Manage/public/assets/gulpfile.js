let gulp = require('gulp');
let del = require('del');
let miniCss = require('gulp-clean-css');                        //- 压缩CSS为一行；
let concat = require('gulp-concat');                            //- 多个文件合并为一个；                  //- 压缩CSS为一行；
let rev = require('gulp-rev');                                  //- 对文件名加MD5后缀
let revCollector = require('gulp-rev-collector');               //- 路径替换
let htmlReplace  = require('gulp-html-replace');
let uglify = require('gulp-uglify-es').default;
let pump = require('pump');

// 框架 CSS 文件处理：合并 => 压缩 => 添加后缀
// 这部分 CSS 除非框架升级，不然不会变化，可以长期缓存在客户端来优化页面性能
// 目前包括：Material Design Icons、Sleek(包括了 Bootstarap)
gulp.task('common-css', function() {
    gulp.src(['./node_modules/@mdi/font/css/materialdesignicons.css', './css/sleek.css'])
        .pipe(concat('common.css'))
        .pipe(miniCss())
        .pipe(rev())
        .pipe(gulp.dest('./dist/css'))
        .pipe(rev.manifest({
          path: 'common-css-manifest.json'
        }))
        .pipe(gulp.dest('./dist/rev'));
});
// 字体资源处理：Nucleo Icons 依赖的文件
gulp.task('font', function() {
    gulp.src('./node_modules/@mdi/font/fonts/*')
        .pipe(gulp.dest('./dist/fonts'))
});
// 其他 CSS 文件处理：压缩 => 添加后缀
// 包括：公共 CSS、登陆页 CSS
gulp.task('css', function() {
    gulp.src(['./css/*.css', './node_modules/bootstrap/dist/css/bootstrap.css', '!.css/sleek.css'])
        .pipe(miniCss())
        .pipe(rev())
        .pipe(gulp.dest('./dist/css'))
        .pipe(rev.manifest({
          path: 'css-manifest.json'
        }))
        .pipe(gulp.dest('./dist/rev'));
});
// 框架 JS 文件处理：合并 => 添加后缀
// 这部分 JS 除非框架升级，不然不会变化，可以长期缓存在客户端来优化页面性能
// 目前包括：jQuery、Popper、Bootstrap、Perfect-scrollbar、Black-dashboard
gulp.task('common-script', function() {                         
    gulp.src(['./node_modules/jquery/dist/jquery.js', './node_modules/bootstrap/dist/js/bootstrap.bundle.js', './node_modules/jquery-slimscroll/jquery.slimscroll.js', './js/sleek.js'])
        .pipe(uglify())
        .pipe(concat('common.js'))
        .pipe(rev())
        .pipe(gulp.dest('./dist/js'))
        .pipe(rev.manifest({
          path: 'common-script-manifest.json'
        }))
        .pipe(gulp.dest('./dist/rev'))
});
// 公共 JS 文件处理：压缩 => 添加后缀
gulp.task('app-script', function () {
    pump([
        gulp.src(['./js/*.js', '!.js/sleek.js']),
        uglify(),
        rev(),
        gulp.dest('./dist/js'),
        rev.manifest({
          path: 'app-script-manifest.json',
        }),
        gulp.dest('./dist/rev')
    ]);
});
// 图片资源处理：添加后缀
gulp.task('image', function() {
    gulp.src('./images/**/*')
        .pipe(rev())
        .pipe(gulp.dest('./dist/images'))
        .pipe(rev.manifest({
            path: 'image-manifest.json'
        }))
        .pipe(gulp.dest('./dist/rev'))
});
// Html 资源处理：先将 Html 中预定义的块替换成对应的内容，再通过 manifest.json 文件替换文件名
// 包括：Html 中的图片、CSS、JS
gulp.task('rev', function() {
    gulp.src(['./dist/rev/*.json', '../../app/Template/**/*.twig'])
        .pipe(htmlReplace({
            'common-css': '/assets/dist/css/common.css',
            'common-script': '/assets/dist/js/common.js',
            'app-script': '/assets/dist/js/app.js',
            'bootstrap-css': '/assets/dist/css/bootstrap.css'
        }))
        .pipe(revCollector())
        .pipe(gulp.dest('../../app/View'))
});
// 清除编译生成的所有文件
// force 参数用于删除当前目录之外的文件
gulp.task('clean', function() {
    del([
      '../../app/View',
      'dist'
    ], {force: true})
});

gulp.task('default', ['common-css', 'css', 'font', 'common-script', 'app-script']);

// gulp clean
// gulp image
// gulp
// gulp rev
