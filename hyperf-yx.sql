/*
Navicat MySQL Data Transfer

Source Server         : 本地虚拟机
Source Server Version : 50730
Source Host           : 192.168.1.6:3306
Source Database       : hyperf-yx

Target Server Type    : MYSQL
Target Server Version : 50730
File Encoding         : 65001

Date: 2022-07-13 17:56:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 NOT NULL,
  `password_salt` char(4) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常,',
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
  `create_time` int(11) unsigned NOT NULL COMMENT '注册时间',
  `last_login_time` int(11) unsigned DEFAULT NULL,
  `login_times` smallint(5) unsigned DEFAULT '0',
  `last_login_ip` varchar(15) CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台管理员';

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', '918536738ddc85fd882a9f32c1ed5bae', '1234', '1', '1', '1563532079', '1657697879', '96', '127.0.0.1');
INSERT INTO `admin` VALUES ('2', 'yunying', '918536738ddc85fd882a9f32c1ed5bae', '1234', '1', '2', '1639395418', '1652868291', '7', '127.0.0.1');
INSERT INTO `admin` VALUES ('3', 'admin22', 'bb4b28d0bfb1f5494fc0b6e064bbd4fd', 'Fiuy', '0', '2', '1653154422', null, '0', null);

-- ----------------------------
-- Table structure for `asset`
-- ----------------------------
DROP TABLE IF EXISTS `asset`;
CREATE TABLE `asset` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `file_size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小,单位B',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:可用,0:不可用',
  `download_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `file_key` varchar(64) NOT NULL DEFAULT '' COMMENT '文件惟一码',
  `filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `file_path` varchar(100) NOT NULL DEFAULT '' COMMENT '文件路径,相对于upload目录,可以为url',
  `file_md5` varchar(32) NOT NULL DEFAULT '' COMMENT '文件md5值',
  `file_sha1` varchar(40) NOT NULL DEFAULT '',
  `suffix` varchar(10) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '文件后缀名,不包括点',
  `more` text CHARACTER SET utf8mb4 COMMENT '其它详细信息,JSON格式',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `file_key` (`file_key`,`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='资源表';

-- ----------------------------
-- Records of asset
-- ----------------------------

-- ----------------------------
-- Table structure for `menu_node`
-- ----------------------------
DROP TABLE IF EXISTS `menu_node`;
CREATE TABLE `menu_node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '权限英文名称',
  `sort` smallint(6) unsigned DEFAULT '10000' COMMENT '排序',
  `level` tinyint(1) unsigned DEFAULT '0' COMMENT '层次',
  `pid` int(6) unsigned DEFAULT '0' COMMENT '父节点',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '0隐藏   1显示',
  `controller` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action` varchar(30) NOT NULL DEFAULT '' COMMENT '操作名称',
  `icon` varchar(20) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `param` varchar(50) NOT NULL DEFAULT '' COMMENT '额外参数',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `path` (`controller`,`action`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限节点表';

-- ----------------------------
-- Records of menu_node
-- ----------------------------
INSERT INTO `menu_node` VALUES ('1', '管理员管理', '100', '0', '0', '1', 'admin', 'default', '&#xe726;', '', '');
INSERT INTO `menu_node` VALUES ('3', '管理员列表', '1000', '1', '1', '1', 'admin', 'index', '', '', '');
INSERT INTO `menu_node` VALUES ('4', '添加管理员', '10000', '2', '3', '0', 'admin', 'add', '', '', null);
INSERT INTO `menu_node` VALUES ('5', '提交添加', '9000', '2', '3', '0', 'admin', 'addPost', '', '', '');
INSERT INTO `menu_node` VALUES ('6', '角色管理', '10000', '1', '1', '1', 'role', 'index', '', '', null);
INSERT INTO `menu_node` VALUES ('7', '添加角色', '10000', '2', '6', '0', 'role', 'add', '', '', null);
INSERT INTO `menu_node` VALUES ('9', '编辑管理员', '10000', '2', '3', '0', 'admin', 'edit', '', '', '');
INSERT INTO `menu_node` VALUES ('10', '提交编辑', '10000', '2', '3', '0', 'admin', 'editPost', '', '', '');
INSERT INTO `menu_node` VALUES ('11', '删除管理员', '10000', '2', '3', '0', 'admin', 'delete', '', '', '');
INSERT INTO `menu_node` VALUES ('12', '提交添加', '10000', '2', '6', '0', 'role', 'addPost', '', '', '');
INSERT INTO `menu_node` VALUES ('13', '编辑角色', '10000', '2', '6', '0', 'role', 'edit', '', '', '');
INSERT INTO `menu_node` VALUES ('14', '提交编辑', '10000', '2', '6', '0', 'role', 'editPost', '', '', '');
INSERT INTO `menu_node` VALUES ('15', '删除角色', '10000', '2', '6', '0', 'role', 'delete', '', '', '');
INSERT INTO `menu_node` VALUES ('16', '启用/禁用', '10000', '2', '3', '0', 'admin', 'enabling', '', '', '');
INSERT INTO `menu_node` VALUES ('17', '启用/禁用', '10000', '2', '6', '0', 'role', 'enabling', '', '', '');
INSERT INTO `menu_node` VALUES ('18', '节点菜单管理', '10000', '1', '1', '1', 'node', 'index', '', '', '');
INSERT INTO `menu_node` VALUES ('19', '添加节点', '10000', '2', '18', '0', 'node', 'add', '', '', '');
INSERT INTO `menu_node` VALUES ('20', '提交添加', '10000', '2', '18', '0', 'node', 'addPost', '', '', '');
INSERT INTO `menu_node` VALUES ('21', '编辑节点', '10000', '2', '18', '0', 'node', 'edit', '', '', '');
INSERT INTO `menu_node` VALUES ('22', '提交编辑', '10000', '2', '18', '0', 'Node', 'editPost', '', '', '');
INSERT INTO `menu_node` VALUES ('23', '删除节点', '10000', '2', '18', '0', 'Node', 'delete', '', '', '');
INSERT INTO `menu_node` VALUES ('24', '显示/隐藏', '10000', '2', '18', '0', 'Node', 'enabling', '', '', '');
INSERT INTO `menu_node` VALUES ('25', '修改排序', '10000', '2', '18', '0', 'Node', 'changeSort', '', '', '');
INSERT INTO `menu_node` VALUES ('26', '用户管理', '200', '0', '0', '1', 'User', 'defualt', '&#xe6b8;', '', '');
INSERT INTO `menu_node` VALUES ('27', '用户列表', '10000', '1', '26', '1', 'user', 'index', '', '', '');
INSERT INTO `menu_node` VALUES ('28', '禁用', '10000', '2', '27', '0', 'User', 'enabling', '', '', '');
INSERT INTO `menu_node` VALUES ('29', '编辑用户', '10000', '2', '27', '0', 'user', 'edit', '', '', '');
INSERT INTO `menu_node` VALUES ('30', '删除用户', '10000', '2', '27', '0', 'user', 'delete', '', '', '');
INSERT INTO `menu_node` VALUES ('31', '设置', '500', '0', '0', '0', 'Setting', 'defualt', '&#xe6ae;', '', '');
INSERT INTO `menu_node` VALUES ('32', '分享图', '10000', '1', '31', '1', 'Share', 'index', '', '', '');
INSERT INTO `menu_node` VALUES ('33', '添加', '10000', '2', '32', '0', 'share', 'add', '', '', '');
INSERT INTO `menu_node` VALUES ('34', '编辑', '10000', '2', '32', '0', 'share', 'edit', '', '', '');
INSERT INTO `menu_node` VALUES ('35', '删除', '10000', '2', '32', '0', 'share', 'del', '', '', '');
INSERT INTO `menu_node` VALUES ('36', '数据统计', '300', '0', '0', '0', 'Statistics', 'defualt', '&#xe6b3;', '', '');
INSERT INTO `menu_node` VALUES ('37', '每日统计', '10000', '1', '36', '1', 'Statistics', 'daily', '', '', '');
INSERT INTO `menu_node` VALUES ('38', '广告统计', '10000', '1', '36', '1', 'Statistics', 'video', '', '', '');
INSERT INTO `menu_node` VALUES ('39', '分享统计', '10000', '1', '36', '1', 'Statistics', 'share', '', '', '');
INSERT INTO `menu_node` VALUES ('40', '新手步骤统计', '10000', '1', '36', '1', 'Statistics', 'novice', '', '', '');

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态;0:禁用;1:正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色表';

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '1', '1563760232', '0', '超级管理员', '拥有整个系统的权限');
INSERT INTO `role` VALUES ('2', '1', '1639392483', '1653903894', '运营人员', '查看数据统计');

-- ----------------------------
-- Table structure for `role_node`
-- ----------------------------
DROP TABLE IF EXISTS `role_node`;
CREATE TABLE `role_node` (
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '角色 id',
  `node_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '节点id',
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `node_id` (`node_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色权限表';

-- ----------------------------
-- Records of role_node
-- ----------------------------
INSERT INTO `role_node` VALUES ('2', '26');
INSERT INTO `role_node` VALUES ('2', '27');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常,2:未验证',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `nickname` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `password` varchar(130) NOT NULL,
  `avatar` varchar(220) NOT NULL DEFAULT '' COMMENT '用户头像',
  `token` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户token',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `login_times` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `create_time` int(11) unsigned NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `mobile` (`mobile`) USING BTREE,
  KEY `nickname` (`nickname`) USING BTREE,
  KEY `token` (`token`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('2', '1', '15913137111', 'user1', '101193d7181cc88340ae5b2b17bba8a1', 'static/common/images/user-head2.jpg', 'RlduQ1DgbULDoWydpe3vxtshDcwldgGO', '1657697777', '33', '1654011719');
INSERT INTO `user` VALUES ('3', '1', '15913137222', 'user2', '101193d7181cc88340ae5b2b17bba8a1', 'static/common/images/user-head3.jpg', 'JLvqkYAhfsKtWZssQPAJKbMp3IwUrWzj', '1657698355', '6', '1654011815');

-- ----------------------------
-- Table structure for `user_friends`
-- ----------------------------
DROP TABLE IF EXISTS `user_friends`;
CREATE TABLE `user_friends` (
  `user_id` int(10) unsigned NOT NULL,
  `friend_id` int(10) unsigned NOT NULL,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户好友';

-- ----------------------------
-- Records of user_friends
-- ----------------------------
INSERT INTO `user_friends` VALUES ('2', '3');
INSERT INTO `user_friends` VALUES ('3', '2');
