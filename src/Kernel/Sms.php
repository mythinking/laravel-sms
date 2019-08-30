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

        $class_name = ucwords($server_name);
        $class = "Mythinking\\LaravelSms\\Kernel\\{$class_name}\\{$class_name}";
        $this->server = new $class($this->config->get('sms'));
    }

    /**
     * 获取服务
     * @return Mythinking\LaravelSms\Kernel\Contracts\SmsSmsInterface
     */
    public function server()
    {
        return $this->server;
    }
}