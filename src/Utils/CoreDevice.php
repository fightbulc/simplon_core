<?php

namespace Simplon\Core\Utils;

use Doctrine\Common\Cache\CacheProvider;
use Simplon\Device\Device;

class CoreDevice extends Device
{
    const GOOLEBOT_SMARTPHONE_AGENT = 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';

    /**
     * @var bool
     */
    protected $isGooglebotSmartphone;

    /**
     * @param null|string        $agent
     * @param CacheProvider|null $cacheProvider
     *
     * @throws \Exception
     */
    public function __construct(?string $agent = null, ?CacheProvider $cacheProvider = null)
    {
        parent::__construct($agent, $cacheProvider);
        $this->testForGooglebotSmartphone($agent);
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        $model = parent::getModel();

        if ($this->isGooglebotSmartphone())
        {
            $model = Device::MODEL_ANDROID;
        }

        return $model;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        $type = parent::getType();

        if ($this->isGooglebotSmartphone())
        {
            $type = Device::TYPE_MOBILE;
        }

        return $type;
    }

    /**
     * @return bool
     */
    protected function isGooglebotSmartphone(): bool
    {
        return $this->isGooglebotSmartphone;
    }

    /**
     * @param string $agent
     *
     * @return CoreDevice
     */
    protected function testForGooglebotSmartphone(string $agent): self
    {
        $this->isGooglebotSmartphone = $agent === self::GOOLEBOT_SMARTPHONE_AGENT;

        return $this;
    }
}