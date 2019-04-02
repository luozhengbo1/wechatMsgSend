<?php
$wxConf = include_once "./Conf/WeiXin.php";

include_once "./SDK/WeiXin.php";

$wx = new WeiXin($wxConf);
$wx->templateMsg('','','');

