<?php
class WeiXin
{
    private $appID;
    private $appsecret;
    private $db;
    public function __construct($conf)
    {
        foreach ($conf as $key => $val) {
            $$key = $val;
        }
        $this->appID = $appID;
        $this->appsecret = $appsecret;
        include_once "Http.php";
    }
    public function injectionDb($db)
    {
        $this->db = $db;
    }
    public function news($openid, $template)
    {
        $msg = new stdClass();
        $msg->touser = $openid;
        $msg->msgtype = 'news';
        $msg->news['articles'][] = array(
            'title' => '%s',
            'description' => '%s',
            'url' => 'http://10.20.1.99:8088/index.php/wifi/authAfter',
            'picurl' => 'http://10.20.1.99:8088/Uploads/2018-03-19/5aaf2909704b2.jpg'
        );
        $msg = sprintf(json_encode($msg), "哈哈，你被探针扫到了", '我的内心毫无波动，甚至有些想笑');
        $this->send($msg);
    }
    public function text()
    {

    }
    public function voice()
    {

    }
    private function send($msg, $other=[])
    {
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' .  $accessToken;
        $sendResult = Http::post( $url, $msg );

        var_dump($sendResult);
        if ($sendResult['errcode'] != 0) {
            // Todo 发送失败
        } else {
            // todo 发送成功
        }
    }
    private function getAccessToken()
    {
        $url = "http://m.gzairports.com/manage.php?s=/addon/Flight/Flight/getAccessToken//is_rabbit/true/publicid/2";        $accessTokenInfo = Http::get($url);
        file_put_contents('./access_token.txt', $accessTokenInfo);
        return $accessTokenInfo;
    }
    public function templateMsg($openid, $flightInfo, $method)
    {
        $touser = "o7khyw2tU8pb4tWnX2u7T5YQF-b4";
        $url = "http://m.gzairports.com/manage.php?s=/addon/Flight/Flight/details/publicid/2/id/{1}";
        $hello = "<img src='http://10.20.1.99:8088/Uploads/2018-03-19/5aaf2909704b2.jpg'/>";
        $flightAirways = "CZ2555";
        $startCn = "贵州";
        $terminalCn = "长春";
        $startTime = "AAAAAAA";
        $teminalTime= "BBBBBBG";
        $aa = "------";
        $template = file_get_contents("SDK/changeBor.json");
        $param = sprintf($template, $touser, $url, $hello, $flightAirways, $startCn, $terminalCn, $startTime, $teminalTime, $aa, $aa);
        $this->sendTemplate($param);
    }

    public function sendTemplate($param)
    {
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' .  $accessToken;
        $result ['status'] = 0;
        $result ['msg'] = '回复失败';
        $res = Http::post( $url, $param );
        if ($res ['errcode'] != 0) {
            $result ['msg'] = $res;
            $datetime = date("Y-m-d H:i:s");
        } else {
            $result ['status'] = 1;
            $result ['msg'] = '回复成功';
        }
    }

}
