<?php
/**
 * Created by PhpStorm.
 * User: Sucre
 * Date: 2019-08-29
 * Time: 18:01
 */

namespace Mythinking\LaravelSms\Kernel\Cl;

use Mythinking\LaravelSms\Kernel\Common\Base;
use Mythinking\LaravelSms\Kernel\Contracts\SmsInterface;

class Cl extends Base implements SmsInterface
{
    protected $cl;
    // 创蓝发送短信接口URL
    const API_SEND_URL = "http://smssh1.253.com/msg/send/json";
    //发国际短信
    const API_SEND_EXT_URL='http://intapi.253.com/send/json';

    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = $config;
        $this->cl = $config['cl'];
    }

    /**
     * 重写自己的配置，用于多个账号配置
     * @param $config
     */
    public function setConfig($config)
    {
        $this->cl = $config;
    }

    /**
     * 结果处理
     * @param $res
     * @return array
     */
    public function result($res): array
    {
        /**
        {
        "code":"0",
        "msgId":"18052415065227118",
        "time":"20180524150652",
        "errorMsg":""
        }
         */
        $arr = json_decode($res, true);
        if (empty($res) || empty($arr)) {
            return $this->response->httpErr();
        }

        return $this->response->setData((int)$arr['code'], $arr['errorMsg'], $arr['msgId'], $arr['time']);
    }

    /**
     * 发送短信
     * @param string $phone
     * @param string $templateid
     * @param array $params
     * @param \Closure|null $closure
     * @return bool
     */
    public function send(string $phone, string $templateid, array $params = [], \Closure $closure = null)
    {
        $is_internal = check_phone_internal($phone);

        $msgsid = $this->setMsgSid();
        $this->logBefore($msgsid, $phone, $templateid, $params);

        if ($this->config['sms_switch']) {
            if ($is_internal) {
                $res = $this->sendInternalSms($phone, $templateid, $params);
            } else {
                $res = $this->sendExternalSms($phone, $templateid, $params);
            }
            $arr = $this->result($res);
        } else {
            $arr = $this->resultSuccess();
        }
        if ($closure) {
            $closure($arr);
        }

        $this->logAfter($msgsid);


        return ($this->response->getCode() == 0);
    }

    /**
     * 国内短信
     * @param string $phone
     * @param string $templateid
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    private function sendInternalSms(string $phone, string $templateid, array $params = [])
    {
        $internal = $this->cl['internal'];
        $msg = format_templ($internal['templates'], $templateid, $params);
        $params = [
            'account'   => $internal['account'],
            'password'  => $internal['password'],
            'msg'       => urlencode($msg),
            'phone'     => substr($phone, -11),
        ];

        $res = curl_post(self::API_SEND_URL, $params);

        //创蓝接口错误代码
        /*
            $statusStr = [
               '0' => '发送成功',
               '101' => '无此用户',
               '102' => '密码错',
               '103' => '提交过快',
               '104' => '系统忙',
               '105' => '敏感短信',
               '106' => '消息长度错',
               '107' => '错误的手机号码',
               '108' => '手机号码个数错',
               '109' => '无发送额度',
               '110' => '不在发送时间内',
               '111' => '超出该账户当月发送额度限制',
               '112' => '无此产品',
               '113' => 'extno格式错',
               '115' => '自动审核驳回',
               '116' => '签名不合法，未带签名',
               '117' => 'IP地址认证错',
               '118' => '用户没有相应的发送权限',
               '119' => '用户已过期',
               '120' => '内容不是白名单',
               '121' => '必填参数。是否需要状态报告，取值true或false',
               '122' => '5分钟内相同账号提交相同消息内容过多',
               '123' => '发送类型错误(账号发送短信接口权限)',
               '124' => '白模板匹配错误',
               '125' => '驳回模板匹配错误',
           ];
        */
        return $res;
    }

    /**
     * 国际短信
     * @param string $phone
     * @param string $templateid
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    private function sendExternalSms(string $phone, string $templateid, array $params = [])
    {
        $external = $this->cl['external'];
        $msg = format_templ($external['templates'], $templateid, $params);
        $params = [
            'account'   => $external['account'],
            'password'  => $external['password'],
            'msg'       => urlencode($msg),
            'mobile'    => $phone,
        ];

        $res = curl_post(self::API_SEND_EXT_URL, $params);

        //创蓝接口错误代码
        /*
            $statusStr = [
                '0' => '发送成功',
                '101' => '账号不存在',
                '102' => '密码错误',
                '106' => '短信内容长度错误',
                '108' => '手机号码格式错误',
                '110' => '余额不足',
                '112=> '产品配置错误',
                '114' => '请求ip和绑定ip不一致',
                '115' => '没有开通国内短信权限',
                '123' => '短信内容不能为空',
                '128' => '账号长度错误',
                '129' => '产品价格配置错误',
            ];
        */
        return $res;
    }
}