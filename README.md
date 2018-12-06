# Emage Store

## 后台管理的图片优化功能

自动添加图片脚本添加了图片优化功能，使用的是 [Image-Optimizer](https://github.com/spatie/image-optimizer) 组件，Linux 系统需要安装相应的类库。

在 CentOS 中可以执行一下命令：

```bash
$ yum -y install jpegoptim
$ yum -y install optipng
$ yum -y install pngquant
```

## TODO

- [X] 图片大小修正为压缩后
- [X] 图片的主色提取
- [ ] 上传图片优化
- [X] 上传图片的分类选择
- [ ] 页面 TDK 个性化设置
- [ ] CSS 自动化处理 PostCss 插件
- [X] 标签样式
- [ ] 浏览器缩放自动调整图片布局