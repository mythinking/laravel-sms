<?php
/**
 * Created by PhpStorm.
 * User: Sucre
 * Date: 2019-08-29
 * Time: 18:09
 */

namespace Mythinking\LaravelSms\Kernel\Contracts;


interface SmsInterface
{
    /**
     * 发送短信
     * @param string $phone
     * @param string $templateid
     * @param array $params
     * @param \Closure|null $closure
     * @return mixed
     */
    public function send(string $phone, string $templateid, array $params = [], \Closure $closure = null);

    /**
     * 接口返回结果
     * @param $res
     * @return array
     */
    public function result($res): array ;
}