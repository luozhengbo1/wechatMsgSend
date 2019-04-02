<?php
include_once  "./SDK/Http.php";

$access = Http::get("http://m.gzairports.com/manage.php?s=/addon/Flight/Flight/getAccessToken//is_rabbit/true/publicid/2");
file_put_contents('test_access.txt', $access);
echo $access;
