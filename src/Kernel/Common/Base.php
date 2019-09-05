<?php
/**
 * Created by PhpStorm.
 * User: Sucre
 * Date: 2019-08-30
 * Time: 10:01
 */

namespace Mythinking\LaravelSms\Kernel\Common;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Base
{
    protected $config;

    /**
     * @var Response 消息返回
     */
    protected $response;

    /**
     * @var string 消息发送id, 自生成
     */
    protected $msgsid;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * 生成msgsid
     * @return string
     */
    private function mid()
    {
        return date('YmdHis') . rand(10000000,99999999);
    }

    /**
     * 设置msgsid
     * @return string
     */
    protected function setMsgSid()
    {
        $msgsid = $this->mid();
        $this->msgsid = $msgsid;

        return $msgsid;
    }

    /**
     * 获取msgsid
     * @return string
     */
    protected function getMsgSid()
    {
        return $this->msgsid ?? $this->mid();
    }

    /**
     * 默认返回成功
     * @return array
     */
    public function resultSuccess()
    {
        return $this->response->success();
    }

    /**
     * 发送前日志
     * @param string $msgsid
     * @param string $phone
     * @param string $templateid
     * @param array $params
     */
    protected function logBefore(string $msgsid, string $phone, string $templateid, array $params = [])
    {
        Log::notice("[Sms ".get_base_classname(get_class($this))." {$msgsid}] sending..., params: ". json_encode(compact('phone', 'templateid', 'params')));
    }

    /**
     * 发送消息日志
     * @param string $phone
     * @param string $msg
     */
    protected function logMsg(string $phone, string $msg)
    {
        Log::notice("[Sms ".get_base_classname(get_class($this))." {$this->getMsgSid()}] send, params: ". json_encode(compact('phone', 'msg')));
    }

    /**
     * 发送结果日志
     * @param string $msgsid
     */
    protected function logAfter(string $msgsid)
    {
        $code = $this->response->getCode();
        $res = $this->response->data();

        if (empty($code)) {
            Log::notice("[Sms ".get_base_classname(get_class($this))." {$msgsid}] send ok, result: ". json_encode($res));
        } else {
            Log::error(" [Sms ".get_base_classname(get_class($this))." {$msgsid}] send error, error: ". json_encode($res));
        }
    }

    /**
     * 短信发送限制
     * @param string $phone
     * @param string $templateid
     * @param int $max  每日限制
     * @param int $lifecycle 单条单位时间重复发送限制
     * @return bool|string
     */
    public function checkLimit(string $phone, string $templateid, $max = 10, $lifecycle = 120)
    {
        $key = "Laravel-sms:".get_base_classname(get_class($this)).":{$phone}:";
        $key_max = $key.date('Ymd');
        $key_template = $key."templateid";//用于中间变量，清除templateid
        $time = time();

        $template_str = Cache::get($key_template);
        if (empty($template_str)) {
            Cache::forever($key_template, $time);//no cache expire
            $template_str = $time;
        }

        $key_lifecycle = $key.":{$template_str}:".$templateid.":last_send_time";//last send time

        if (!empty($max)) {
            $today_count = Cache::get($key_max);
            if (empty($today_count)) {
                Cache::put($key_max, 0, 24*60);//cache one day
            } elseif ((int)$today_count >= $max) {
                return '超过每日限制发送短信数量: '. $max;
            }
        }
        if (!empty($lifecycle)) {
            $last_send_time = Cache::get($key_lifecycle);
            if (!empty($last_send_time) && ($time - $lifecycle <= (int)$last_send_time)) {
                return $lifecycle.'秒内不能重复发送';
            }
        }
        //add a account
        Cache::increment($key_max);
        Cache::forever($key_lifecycle, $time);//no cache expire

        return true;
    }

    /**
     * 清除发送限制
     * @param string $phone
     */
    public function clearLimit(string $phone)
    {
        $key = "Laravel-sms:".get_base_classname(get_class($this)).":{$phone}:";
        $key_max = $key.date('Ymd');
        $key_template = $key."templateid";//用于中间变量，清除templateid

        Cache::forget($key_max);
        Cache::forget($key_template);
    }
}