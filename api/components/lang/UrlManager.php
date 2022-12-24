<?php

namespace api\components\lang;

use Yii;
use api\helpers\LangHelper;

class UrlManager extends \yii\web\UrlManager
{
    public function createUrl($params)
    {
        $currentLangId = LangHelper::getLangIdByCode(Yii::$app->language);

        // Get url from urlManager
        $url = parent::createUrl($params);

        // If current language is equal app default language - do not anything
        if ($currentLangId === LangHelper::DEFAULT_LANG) {
            return $url;
        }

        // Get current language short code
        $langCode = LangHelper::getLangShortCode(Yii::$app->language);
        $baseUrl = $this->baseUrl;
        // Set new base url
        $this->baseUrl = rtrim($baseUrl, '/') . '/' . $langCode;
        // Generate url with new base url
        $url = rtrim(parent::createUrl($params), '/');
        // Restore base url
        $this->baseUrl = $baseUrl;

        return $url;
    }
}