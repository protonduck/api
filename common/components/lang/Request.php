<?php

namespace common\components\lang;

use common\helpers\LangHelper;
use Yii;

class Request extends \yii\web\Request
{
    private $_lang_url;
    private $_original_url;

    /**
     * Get requested url and determine site language from path
     *
     * @inheritdoc
     */
    public function getUrl()
    {
        if ($this->_lang_url === null) {
            $this->_lang_url = $this->_original_url = parent::getUrl();

            // Explode url path by "/"
            $url_parts = explode('/', $this->_lang_url);

            // Default lang id
            $lang_id = LangHelper::DEFAULT_LANG;

            // If exists expected language part
            if (isset($url_parts[1]) && mb_strlen($url_parts[1], Yii::$app->charset) === 2) {
                $lang_id_by_url = LangHelper::getLangIdByCode($url_parts[1]);

                // If language exists
                if ($lang_id_by_url !== false && $lang_id_by_url !== LangHelper::DEFAULT_LANG) {
                    // Set it's language
                    $lang_id = $lang_id_by_url;
                    // Remove lang part from url
                    unset($url_parts[1]);
                    // Generate new url without lang part
                    $this->_lang_url = implode('/', $url_parts);
                }
            }

            LangHelper::setAppLanguage($lang_id);

            return $this->_lang_url;
        }

        return $this->_original_url;
    }

    public function getLangUrl()
    {
        return $this->_lang_url;
    }
}
