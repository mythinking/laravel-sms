<?php
/**
 * Created by PhpStorm.
 * User: Sucre
 * Date: 2019-08-30
 * Time: 10:01
 */

namespace Mythinking\LaravelSms\Kernel\Common;


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
}