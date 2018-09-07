CREATE TABLE `user` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` CHAR(32) NOT NULL DEFAULT '' COMMENT '身份标识符',
    `recommend_uuid` CHAR(32) NOT NULL DEFAULT '' COMMENT '推荐人身份标识符',
    `username` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '登陆名',
    `name` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '姓名',
    `nickname` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '昵称',
    `phone` CHAR(11) NOT NULL DEFAULT '' COMMENT '手机号',
    `email` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'E-mail',
    `open_id` CHAR(32) NOT NULL DEFAULT '' COMMENT '微信的Open ID',
    `password` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '密码密文',
    `token` CHAR(32) NOT NULL DEFAULT '' COMMENT 'Token',
    `token_invalid_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Token的过期时间',
    `avatar` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像的地址',
    `sign` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '签名',
    `client` TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户来源：0 - 系统创建、1 - PC web、2 - 移动 web、3 - iOS、4 - Android、5 - 微信小程序',
    `group` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户组：0 - 普通用户、1 - 管理员、2 - 超级管理员',
    `last_login` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
    `gmt_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录的创建时间',
    `gmt_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '记录的更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_uuid` (`uuid`),
    UNIQUE KEY `uk_username` (`username`),
    UNIQUE KEY `uk_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户信息记录表';

CREATE TABLE `picture` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `hash` CHAR(16) NOT NULL DEFAULT '' COMMENT 'Hash 值',
    `uuid` CHAR(32) NOT NULL DEFAULT '' COMMENT '所有者身份标识符',
    `path` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '存储地址',
    `width` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '宽',
    `height` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '宽',
    `size` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '大小',
    `gmt_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录的创建时间',
    `gmt_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '记录的更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='图片信息记录表';