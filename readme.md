# iBrand E-Commerce open source project

iBrand EC 是一个免费的开源电子商务解决方案，使用 PHP 基于 Laravel 框架进行编写。基于模块化开发，易扩展，可基于个性需求快速实现定制化需求，功能符合国内电商绝大部分使用场景。

> 基于目前移动互联网的发展趋势，目前只提供 API 和 后端管理，不提供PC端商城。如有需要可理解项目架构后自行实现。

管理后台：[iBrand e-commerce admin]( https://demo-open-admin.ibrand.cc/admin)

后台账号密码请关注公众号 [iBrand社交新零售](https://iyoyo.oss-cn-hangzhou.aliyuncs.com/post/wechat.jpg)  (微信号：ibrand_cc) 获取。

扫码体验小程序：

![体验小店](https://iyoyo.oss-cn-hangzhou.aliyuncs.com/post/miniprogramcode/1.jpg)

## 更新日志

目前 2.x 版本正在更新中，更新日志请见此[文档](./CHANGELOG.md)

## 文档

- [API 文档](https://www.ibrand.cc/docs/api/)
- [iBrand 电商系统教程](https://www.ibrand.cc/open/article)

## 特性

- 使用 Laravel + dingo/api 完成接口开发。
- 基于 Modules 或者 Package 开发模式，每个模块都是一个 Package，并且基础模块完成了单元测试，可复用性和可扩展性高。
- 基于 Laravel-admin 实现管理后台。
- 符合国内绝大部分B2C电商需求，同时可以轻易改造成 B2B、O2O、S2B2C模式。
- 完整的API文档+系统使用教程+业务说明文档。
- 配套的小程序解决方案+源码。
- docker 实现自动化部署、负载均衡、自动扩容等。（待更新）
- swoole 结合提高并发。（待更新）

## 贡献源码

该项目正在持续迭代更新中，如果你想参与到本项目中来，请提交 Pull Request !

如果你发现任何错误或者问题，请提交到[官方社区](https://www.ibrand.cc/open/discuss)或者[提交ISSUE](https://github.com/ibrandcc/ecommerce-open-api/issues)










###  iBrand 简介

基于社交店商的核心价值，在2016年9月启动 iBrand 产品，iBrand以O2O交易、会员权益、数据跟踪分析、内容体验四大体系形成战略整合方案，打造智能商业生态。

iBrand 产品包含H5微商城、小程序商城、互动体验平台、门店导购、品牌官网打造等功能及服务，迎合场景化、社群化、个性化的新零售时代，为企业提供灵活定制的产品解决方案，让生意更智慧。

### iBrand 技术方案

iBrand 采用的技术方案有：

1. Laravel：API + 管理后台
2. vue.js：H5 SPA 单页应用
3. 微信小程序
4. docker： 所有应用 docker 化，实现快速部署 + 自动更新 + 快速扩容+ 负载均衡

### iBrand 核心模块

iBrand 产品包含以下核心模块：

 - H5 微商城 （vue.js + Laravel API）
 - 小程序商城 （微信小程序 + Laravel API）
 - 分销功能 （vue.js + 小程序 + Laravel API）
 - 活动报名 （vue.js + Laravel API）
 - 导购小程序 （小程序 + Laravel API）
 - 微信第三方平台 （Laravel + easywechat）

### 开源计划

经过两年的时间整个产品功能线非常繁多，所以随着功能的不断迭代，我们会陆续开源产品中的相关功能，在开源的同时也能不断完善、重构我们的部分代码，把共性的部分抽离出来作为更多的 package，开源后可以降低电商开发的门槛，让更多的初学者能够了解到电商的业务需求，同时能够进入到 Laravel，小程序等学习开发生态中来。

本次开源计划，我们分三个阶段，详情请见下图：

![iBrand 开源电商计划](https://iyoyo.oss-cn-hangzhou.aliyuncs.com/post/iBrand%20%E5%BC%80%E6%BA%90%E7%94%B5%E5%95%86.png)

三个阶段计划于今年完成，完成后可以应用实际的电商项目中，同时也会配套相关 API文档，业务需求文档，技术文档等。

有任何问题也可以及时联系我们：

![二维码](https://iyoyo.oss-cn-hangzhou.aliyuncs.com/post/%E4%BA%8C%E7%BB%B4%E7%A0%81.jpg)


### 开源目的

1. 提升 iBrand 产品知名度，17 年主要在自己朋友的关系圈内进行传播。18 年希望能够有更多朋友了解到 iBrand 这款产品。
2. 来源社区，回馈社区。iBrand 产品能够在短时间内快速完成开发、上线并且稳定运营，完全是依托开源社区丰富的资源，因此我们计划开源回馈社区。
3. 帮助更多的初学者。在面试的过程中，发现很多初学者，基础较差，在外经过培训后，仍然无法满足公司的招人要求，因此希望通过教程能够帮助一些真正想学习的初学者学习到有价值的内容。


### 能学到什么？

1. 环境的搭建 + 服务器的部署（docker）与运维
2. PHP 基本技能 + 编码规范
3. Composer 的使用
4. Laravel 基础知识 + 高级技能
5. Laravel API 解决方案
6. 设计模式
7. 单元测试
8. 电商业务

### 适合谁？

- 计算机类在校生、应届生
- 入门级程序员
- PHP 程序员
- 电商类产品经理
- 前端程序员

### 文档

- [API文档](https://www.ibrand.cc/docs/api/v1/index)
- [Laravel API 教程](https://www.ibrand.cc/open/article)
- 小程序教程 (待更新)

### 讨论交流

![二维码](https://iyoyo.oss-cn-hangzhou.aliyuncs.com/post/%E4%BA%8C%E7%BB%B4%E7%A0%81.jpg)

### 扫码体验

![体验小店](https://iyoyo.oss-cn-hangzhou.aliyuncs.com/post/miniprogramcode/1.jpg)

