<?php
/**
 * Created by PhpStorm.
 * User: Sucre
 * Date: 2019-08-29
 * Time: 17:50
 */

namespace Mythinking\LaravelSms\Kernel;

use Illuminate\Config\Repository;

class Sms
{
    protected $config;

    /**
     * @var Mythinking\LaravelSms\Kernel\Contracts\SmsSmsInterface
     */
    protected $server;

    public function __construct(Repository $config)
    {
        $this->config = $config;
        $server_name = $this->config->get('sms.default');

        $this->server = $this->getInstance($server_name);
    }

    /**
     * 获取服务
     * @return Mythinking\LaravelSms\Kernel\Contracts\SmsSmsInterface
     */
    public function server($default = null)
    {
        if (!empty($default)) {
            $this->server = $this->getInstance($default);
        }
        return $this->server;
    }

    /**
     * 实例化处理类
     * @param $server_name
     * @return mixed
     */
    private function getInstance($server_name)
    {
        $class_name = ucwords($server_name);
        $class = "Mythinking\\LaravelSms\\Kernel\\{$class_name}\\{$class_name}";

        return (new $class($this->config->get('sms')));
    }
}