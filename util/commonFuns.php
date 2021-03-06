<?php
/**
 * 此文件中存放常用代码,包括与微信API交互的接口
 * Created by PhpStorm.
 * User: Archimekai
 * Date: 4/18/2016
 * Time: 14:09
 */
//require_once "../util/httpRedirect.php";
require_once "do_post_request.php";
require_once "../dataBaseApi/dataBaseApis.php";

define("CORPID_GOLBAL","wx75de8782f8e4f99c");
define("CORP_SECERT","87t2MTe-rPYpxi5yzR1wb0M-FNp2dYljRirXZmMgyZJrHRr8ZmKR28bJD0IW50K0");
define("REMOTE_SERVER_IP","121.201.14.58");


//$URL = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . CORPID_GOLBAL . "&corpsecret=" . CORP_SECERT;
//echo $URL;
$last_get_access_token_time = 0;
$last_access_token = null;

function getAccessToken(){
    // TODO 需要缓存access_token以防止访问次数过多,但是如何实现呢？ 目前考虑先不缓存了，反正也不会超过访问次数限制
    $nowtime = time();
    if(($nowtime - $GLOBALS["last_get_access_token_time"] >= 7100) || ($GLOBALS["last_access_token"] == null) ){
        $URL = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . CORPID_GOLBAL . "&corpsecret=" . CORP_SECERT;
        $result = file_get_contents($URL);
        $obj = json_decode($result);
        $access_token = $obj->{'access_token'};
        $expireTime = $obj->{'expires_in'};
        $GLOBALS["last_get_access_token_time"] = time() ;
        $GLOBALS["last_access_token"] =$access_token;
        return $access_token;
    }else{
        //echo "cached  ";
        return $GLOBALS["last_access_token"];
    }

}
/**
 * @param $code
 * @return  string 可能返回userid（对于企业成员），也可能返回openid（对于其他用户）
 */
function getOpenIdOrUserID($code){
    $token = getAccessToken();
    $url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=". $token . "&code=" . $code;
    $result = file_get_contents($url);
    return $result;
}


/**
 * 永远只返回openID
 * @param $code
 */
function getOpenId($code){
    if(empty($code)){
        throw new Exception("No content in variable code");
    }
    $result = getOpenIdOrUserID($code);
    $obj = json_decode($result);
    if(property_exists($obj,"UserId")){ // 返回了企业号中的成员
        $openID = getOpenIdFromUserId($obj->{'UserId'});

    }elseif(property_exists($obj,"OpenId")){ //这里的openid大小写不要更改
        $openID = $obj->{'OpenId'};
    }else{
        $error = "getOpenId error";
        throw new Exception($error);
    }

    return $openID;
}

function getOpenIdFromUserId($userId){
    $access_token = getAccessToken();
    $URL = "https://qyapi.weixin.qq.com/cgi-bin/user/convert_to_openid?access_token=" . $access_token;
    $arraydata = array(
        "userid"=>$userId
    );
 //   var_dump($arraydata);
    $jsondata  = json_encode( $arraydata);
//    echo $jsondata;
    $result = do_post_request($URL, $jsondata);
//    var_dump($result);
    $obj = json_decode($result);
    $openID = $obj->{"openid"};
    return $openID;
}


/**
 * 产生微信OAuth认证所需url，该url会以get形式提供一个参数 code。通过code可以获取微信OpenID
 * @param string
 * @return string  可供微信OAuth使用的链接
 */
function genOAuthURL($url){
    $oauthurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . CORPID_GOLBAL . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_base#wechat_redirect";
    return $oauthurl;
}


function getOpenIDFromREQUEST($request){
    if(!empty($request['parentOpenID'])){
        return $request['parentOpenID'];
    }elseif(!empty($request['teacherOpenID'])){
        return $request['teacherOpenID'];
    }elseif(!empty($request['code'])){
        return getOpenId($request['code']);
    }else{
        throw new Exception("No code or openid in request");
    }
}

// 为了方便使用，将这两个函数拷贝过来了，以后只要引入commonfuns就好了 2016年5月10日23:18:00
function http_redirect_cf($time, $goalURL){
    echo '<head> <meta http-equiv="refresh" content="' . $time . ';url=' . $goalURL . '"/></head>'; //重定向
}

function http_OAuth_redirect_cf($time, $FULLGoalURL){
    $goalURL = genOAuthURL($FULLGoalURL);
    http_redirect_cf($time, $goalURL);
}

/**
 * 解决老师的登陆问题，
 * 判断一：request中是否存在 teacherOpenID, code, OpenID, 如果不存在将带到微信授权界面，微信授权界面跳转回来后将
 *        统一转换为teacherOpenID
 * 判断二：判断该teacher是否已经注册，如果没有注册的话则跳转至权限不足页面
 * @param $request
 * @return string
 */
function teacher_sign_in($request, $FULLGoalURL){
    if(!empty($request['teacherOpenID'])){
        $teacherOpenID = $request['teacherOpenID'];
    }elseif(!empty($request['OpenID'])){
        $teacherOpenID = $request['OpenID'];
    }elseif(!empty($request['code'])){
        $teacherOpenID = getOpenId($request['code']);
    }else{ //链接中不带验证消息，重定向至验证页面
        http_OAuth_redirect_cf(0,$FULLGoalURL);
        return null;
    }

    //ONLY for debug
//    if($teacherOpenID==2){
//        return $teacherOpenID;
//    }
    //判定是否是真正的老师
    if(!checkTeacher($teacherOpenID)){ // 不是老师
        http_redirect_cf(0, "http://" . REMOTE_SERVER_IP . "/pages/parent_access_denied.php");
        return null;
    }
    return $teacherOpenID;
}

/**
 * 解决家长的登陆问题，
 * 判断一：request中是否存在 parentOpenID, code, OpenID, 如果不存在将带到微信授权界面，微信授权界面跳转回来后将
 *        统一转换为parentOpenID
 * 判断二：判断该parentOpenID是否已经注册，如果没有注册的话则跳转至引导注册界面
 * @param $request
 * @return string
 */
function parent_sign_in($request, $FULLGoalURL){
    if(!empty($request['parentOpenID'])){
        $parentOpenID = $request['parentOpenID'];
    }elseif(!empty($request['OpenID'])){
        $parentOpenID = $request['OpenID'];
    }elseif(!empty($request['code'])){
        $parentOpenID = getOpenId($request['code']);
    }else{ //链接中不带验证消息，重定向至验证页面
        http_OAuth_redirect_cf(0,$FULLGoalURL);
        return null;
    }

//    //ONLY for debug
//    if($parentOpenID==2){
//        return $parentOpenID;
//    }
    //判定是否是真正的家长
    if(!checkParent($parentOpenID)){ // 不是家长
        http_redirect_cf(0, "http://" . REMOTE_SERVER_IP . "/pages/parent_not_registered.php");
        return null;
    }
    return $parentOpenID;
}

