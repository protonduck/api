<?php

namespace api\modules\v1;

use api\modules\BaseModule;

class Module extends BaseModule
{
    const API_VERSION = '1';

    public $controllerNamespace = 'api\modules\v1\controllers';
}
