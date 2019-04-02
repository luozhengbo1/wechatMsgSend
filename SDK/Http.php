<?php
class Http
{
    public  static  function get($url) {
        $oCurl = curl_init ();
        if (stripos ( $url, "https://" ) !== FALSE) {
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, FALSE );
        }
        curl_setopt ( $oCurl, CURLOPT_URL, $url );
        curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec ( $oCurl );
        $aStatus = curl_getinfo ( $oCurl );
        curl_close ( $oCurl );
        if (intval ( $aStatus ["http_code"] ) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
    public static function post($url, $param, $type = 'json', $return_array = true, $useCert = [], $timeOut = 30) {
        $type === false && $type = 'json'; // 兼容老版本
        $type === true && $type = 'file'; // 兼容老版本
        if ($type == 'json' && is_array ( $param )) {
            $param = json_encode ( $param, JSON_UNESCAPED_UNICODE );
        } elseif ($type == 'xml' && is_array ( $param )) {
            $param = ToXml ( $param );
        }
        // 初始化curl
        $ch = curl_init ();
        // 设置超时
        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeOut );

        if (class_exists ( '/CURLFile' )) { // php5.5跟php5.6中的CURLOPT_SAFE_UPLOAD的默认值不同
            curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, true );
        } else {
            if (defined ( 'CURLOPT_SAFE_UPLOAD' )) {
                curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false );
            }
        }
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );

        // 设置header
        if ($type == 'file') {
            $header [] = "content-type: multipart/form-data; charset=UTF-8";
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        } elseif ($type == 'xml') {
            curl_setopt ( $ch, CURLOPT_HEADER, false );
        } else {
            $header [] = "content-type: application/json; charset=UTF-8";
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        }

        // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $param );
        // 要求结果为字符串且输出到屏幕上
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        // 使用证书：cert 与 key 分别属于两个.pem文件
        if (isset ( $useCert ['certPath'] ) && isset ( $useCert ['keyPath'] )) {
            curl_setopt ( $ch, CURLOPT_SSLCERTTYPE, 'PEM' );
            curl_setopt ( $ch, CURLOPT_SSLCERT, $useCert ['certPath'] );
            curl_setopt ( $ch, CURLOPT_SSLKEYTYPE, 'PEM' );
            curl_setopt ( $ch, CURLOPT_SSLKEY, $useCert ['keyPath'] );
        }

        $res = curl_exec ( $ch );
        $flat = curl_errno ( $ch );
        $msg = '';
        if ($flat) {
            $msg = curl_error ( $ch );
        }
        curl_close ( $ch );
        if ($flat) {
            $res = [
                'curl_erron' => $flat,
                'curl_error' => $msg
            ];
        } else {
            if ($return_array && ! empty ( $res )) {
                $res = $type == 'xml' ? FromXml ( $res ) : json_decode ( $res, true );
            }
        }
        return $res;
    }
}