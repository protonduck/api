<?php

namespace api\modules;

use yii\base\InvalidConfigException;
use yii\base\Module;

class BaseModule extends Module
{
    const API_VERSION = null;

    public function getApiVersion()
    {
        $version = static::API_VERSION;
        if (!$version === null) {
            throw new InvalidConfigException('Module API_VERSION required');
        }

        return static::API_VERSION;
    }
}
