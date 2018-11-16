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

- [ ] 图片大小修正为压缩后
- [ ] 图片的主色提取
- [ ] 上传图片优化
- [ ] 上传图片的分类选择