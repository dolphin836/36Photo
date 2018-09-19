# Next-Admin

![Next-Admin Logo](/public//assets/images/brand/logo.png "Next-Admin Logo")

一套使用 PHP 开源组件 和 Bootstrap 4 后台管理主题 Tabler 搭建的用于快速创建后台管理系统的项目。

## 图片优化

自动添加图片脚本添加了图片优化功能，使用的是 [Image-Optimizer](https://github.com/spatie/image-optimizer) 组件，Linux 系统需要安装相应的类库。

在 CentOS 中可以执行一下命令：

```bash
$ yum -y install jpegoptim
$ yum -y install optipng
$ yum -y install pngquant
```

## TODO

- [ ] 编辑用户信息
- [ ] 按条件检索
- [ ] 权限功能
- [ ] 操作记录功能
- [ ] 登陆功能