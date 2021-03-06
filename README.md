# WechatWebQuestionnair
微信意见收集系统，支持出勤统计（微信版问卷星！）
## 开发环境
推荐使用wamp进行一键式安装。
* MySQL：5.5+
* PHP：5.5+ 
* Apache：2.4+
* 开发工具：推荐使用 PhpStorm（主要工具 版本：2016.1） 和 WebStorm 进行开发。

生产环境版本如下：

* OS: Ubuntu 14.04 LTS server x64
* MySQL: 5.5.47
* PHP：5.5.9
* Apache: 2.4.7 

## 目录含义
* ./api 存放问卷系统需要调用的API
    * 获取数据的API以get开头，返回json格式数据
    * 更改数据的API以set开头
* ./onlyForLocalHost 存放系统初次使用时的配置文件。
* ./wxq 关键文件夹，用户访问应用时的主要文件夹。
* ./util 功能函数所在的文件夹。
* ./pages 显示给用户的网页

## 数据库配置
* 数据库只允许来自localhost的访问
* 用户名为typemoon，仅允许本机访问
* 密码为typemoonsql
* 所操作的数据库为typemoon01
* 数据库超级账户：root
* 数据库超级账户密码：TypeMoon01
## 常用链接
* https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx75de8782f8e4f99c&redirect_uri=121.201.14.58%2fwxq%2fwxapi.php&response_type=code&scope=snsapi_base#wechat_redirect
    获取有关的用户信息。

## 接口
### 家长注册
使用get方法，提供parentName, studentID两个字段。

### 学生信息提交
使用csv文件进行提交。

文件中只有两列，没有列名（从第一行开始就是学生的数据）

第一列是studentID，第二列是studentName。

### token

需要支持update和read

### backlog
* 通知阅读情况展示。需要weitong用服务器返回的json数据在浏览器端解析并显示。
    * json.js  parseJSON
* 服务器以json形式返回阅读情况数据。
    * json_encode
* 确定通知已阅。confirmNotice.php

## 参赛文档
https://onedrive.live.com/redir?page=view&resid=B4141473268035C7!4797&authkey=!AIox10OnhsryFLA

## 尚未实现的需求
*对照提交给老师的分析文档*
* 问卷的创建，发布。家长的回答。意见的统计。
* 老师上传学生信息的文件，从而为数据库提供学生的精准数据。
* 群管理：没法确定家长属于哪个群。
*
## 界面中需要美化的地方
* 使用Bootstrap的内建类别显示未读通知数

## 未来可以增加的功能
* 可能由于服务器没有备案，我们无法使用post，未来应该使用post和cookie来写出更优雅的代码。
* 支持一个家长有多个孩子。
* 提供更为完善的班级管理功能。
* 在通知和问卷下提供评论功能。
* 支持用户名、密码登陆。
* 设计一个logo
* 使用https进行传输。



##问题定义
##可行性分析
##需求分析
##项目计划
##成本分析
##测试计划
