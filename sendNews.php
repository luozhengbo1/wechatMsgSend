<?php
error_reporting('warring');
include_once('./SDK/DB.php');
include_once "./SDK/WeiXin.php";

$conf = include_once "./Conf/DB.php";
$wxConf = include_once "./Conf/WeiXin.php";

$wx = new WeiXin($wxConf);
$db = new DB($conf);
$wx->injectionDb($db); # 数据库依赖注入
$mac =$_GET['mac'];
$openid = $db->exists("user", ['device_mac'=>$mac]);
$wx->news($openid[0]['openid'], "");
