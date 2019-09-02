<?php
/**
 * Created by PhpStorm.
 * User: Sucre
 * Date: 2019-08-30
 * Time: 10:01
 */

namespace Mythinking\LaravelSms\Kernel\Common;


class Response
{
    /**
     * @var int 错误码，正确为0
     */
    private $code;

    /**
     * @var string 错误描述，code非0时
     */
    private $errmsg;

    /**
     * @var string 消息id, 第三方返回
     */
    private $msgid;

    /**
     * @var string 发送时间
     */
    private $time;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @param string $errmsg
     * @param string $msgid
     * @param string $time
     * @return array
     */
    public function setData(int $code=0, $errmsg="", $msgid="", $time="")
    {
        $this->code     = $code;
        $this->errmsg   = $errmsg ?: '';
        $this->msgid    = $msgid ?: '';
        $this->time     = $time ?: date('YmdHis');

        return $this->data();
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'code'    => $this->code,
            'msg' => $this->errmsg,
            'data' => [
                'msgid'   => $this->msgid,
                'time'    => $this->time,
            ]
        ];
    }

    /**
     * http 请求错误
     * @return array
     */
    public function httpErr()
    {
        return $this->setData(1, 'http request error.', '', date('YmdHis'));
    }

    /**
     * 默认返回成功
     * @return array
     */
    public function success()
    {
        return $this->setData(0, '测试发送成功.', date('YmdHis'), date('YmdHis'));
    }
}