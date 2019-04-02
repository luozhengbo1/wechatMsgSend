#!/usr/bin/php;
<?php
$fileName = "./log/restart.log";
while (True) {
    $output = "";
    chdir("/home/wwwroot/default/weiphp3.0/RabbitMQ/");
    exec("ps -ef | grep Accept.php",$output);
    if (count($output) <= 2) {
        file_put_contents($fileName, date("Y-m-d H:i:s")."restart\r\n" , FILE_APPEND);
        exec("./Accept.sh start");
    }
    sleep(5);
}