<?php
//测试链接：https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx75de8782f8e4f99c&redirect_uri=121.201.14.58%2Fpages%2Ffirst_time_for_students.php%3FteacherOpenID%3DoG_07xPR4JEibyjiSzTjfphx6EWM&response_type=code&scope=snsapi_base#wechat_redirect
//此行代码用于避免iPhone上出现的乱码问题
//<!-- 调用的时候必须提供家长的code和teacherOpenID update：2016年4月23日01:30:32-->
//<!-- 调用的时候必须以get方法提供teacherOpenID和parentOpenID-->
// 测试链接：
//http://121.201.14.58/pages/first_time_for_students.php?teacherOpenID=oG_07xPR4JEibyjiSzTjfphx6EWM&code=
header("Content-type: text/html; charset=utf-8");
?>

<!--
<?php
require_once "../util/commonFuns.php";
$parentOpenID=getOpenId($_REQUEST['code']);
$teacherOpenID=$_REQUEST['teacherOpenID'];
?>
-->
<!DOCTYPE html>
<html>
<head>
    <title>家长注册页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,charset=utf-8">
    <meta content="text/html;charset=utf-8">
    <link href="./reference/bootstrap.min.css" rel="stylesheet">
    <script src="./reference/jquery.min.js"></script>
    <script src="./reference/bootstrap.min.js"></script>
    
</head>
<body>
<?php require_once "./share/navigation_safe.php"?>

<br>
<br>
<h3>
    <strong>欢迎家长注册！</strong>
</h3>
<form role="form" method="get" action="../reg/parentRegWithStudent.php">
    <div class="form-group">
        <label for="parentName" class="col-sm-2 control-label">您的姓名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="parentName" name="parentName"
                   placeholder="请输入用户名">
        </div>
    </div>
    <div class="form-group">
        <label for="studentName" class="col-sm-2 control-label">孩子姓名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="studentName" name="studentName"
                   placeholder="请输入您孩子的姓名">
        </div>
    </div>
    <div class="form-group">
        <label for="studentID" class="col-sm-2 control-label">孩子学号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="studentID" name="studentID"
                   placeholder="请输入您孩子的学号">
        </div>
    </div>
    <!-- 用于在页面中保存teacherOpenID-->
    <div class="form-group" style="display: none" >
        <label for="teacherOpenID" class="col-sm-2 control-label">OpenID</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="teacherOpenID" name="teacherOpenID"
                   value=<?php echo $teacherOpenID?>>
        </div>
    </div>
    <!-- 用于在页面中保存parentOpenID-->
    <div class="form-group" style="display: none" >
        <label for="parentOpenID" class="col-sm-2 control-label">OpenID</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="parentOpenID" name="parentOpenID"
                   value=<?php echo $parentOpenID?>>
        </div>
    </div>
    <div class="form-group">
        <label for="email_adress" class="col-sm-2 control-label">邮件地址</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email_adress"
                   placeholder="请输入邮件地址">
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-sm-2 control-label">设置密码</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="password"
            placeholder="请设置密码">
            <span class="help-block">请包括至少字母和数字</span>
        </div>
    </div>
    <div class="form-group">
        <label for="password_again" class="col-sm-2 control-label">确认密码</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="password_again"
            placeholder="请再次输入密码">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-large btn-block">注册</button>
        </div>
    </div>
</form>

</body>
</html>