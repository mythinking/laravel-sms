<?php

if (! function_exists('curl_post')) {
    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数
     * @return mixed
     */
    function curl_post($url, $postFields) {
        $postFields = json_encode($postFields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($ch);
        if (false == $ret) {
            $result = curl_error($ch);
        } else {
            $rsp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 " . $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
        curl_close($ch);
        return $result;
    }
}

if (! function_exists('check_phone_internal')) {
    /**
     * 检查手机是否国内号码
     * @param string $phone
     * @return bool
     */
    function check_phone_internal(string $phone)
    {
        $phone = ltrim($phone, '+');
        if ($phone && strpos($phone, '86') === 0 || strlen($phone) == 11) {
            return true;
        }
        return false;
    }
}


/**
 * 处理短信模板
 * @param array $templates 模板数组
 * @param string $templateid 模板id
 * @param array $params 模板中对应参数
 * @return mixed
 * @throws Exception
 */
function format_templ(array $templates, string $templateid, array $params = [])
{
    if (!in_array($templateid, array_keys($templates))) {
        throw new \Exception("The templateid [{$templateid}] is not exists!");
    }
    $format = $templates[$templateid];
    if (empty($params)) {
        return $format;
    }
    $res = array_reduce(array_keys($params), function ($c, $k) use ($params) {
        return str_replace(":{$k}", $params[$k], $c);
    }, $format);

    return $res;
}

/**
 * 取带有命名空间的类名
 * @param $classname
 * @return string
 */
function get_base_classname($classname)
{
    return basename(str_replace('\\', '/', $classname));
}
