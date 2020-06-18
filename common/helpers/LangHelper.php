<?php

namespace common\helpers;

use Yii;
use yii\helpers\Url;
use common\components\lang\Request;

class LangHelper
{
    const LANG_EN = 1;
    const LANG_FR = 2;
    const LANG_RU = 3;
    const DEFAULT_LANG = self::LANG_EN;

    /**
     * Alternate links for current page
     *
     * @var array
     */
    public static $alternateLinks = [];

    /**
     * Return translated field value on current language
     *
     * @param \yii\base\Model $model
     * @param string $attribute
     *
     * @return string|null
     */
    public static function getTranslatedField($model, $attribute)
    {
        $currentLang = static::getLangShortCode(Yii::$app->language);
        $attrName = $attribute . '_' . $currentLang;

        return isset($model->$attrName) ? $model->$attrName : null;
    }

    /**
     * Return name of attribute with current app language
     *
     * @param string $attribute
     * @param string|null $lang_code
     *
     * @return string
     */
    public static function getAttrName($attribute, $lang_code = null)
    {
        $lang_code = static::getLangShortCode($lang_code ? $lang_code : Yii::$app->language);

        return $attribute . '_' . $lang_code;
    }

    /**
     * Return 2 chars from language code
     *
     * @param string $language
     *
     * @return string|false
     */
    public static function getLangShortCode($language)
    {
        if (!is_string($language) || strlen($language) < 2) {
            return false;
        }

        return substr($language, 0, 2);
    }

    /**
     * Get all site languages or get name of $lang_id language
     *
     * @param null|int $lang_id
     *
     * @return array|string
     */
    public static function getLanguages($lang_id = null)
    {
        $siteLanguages = [
            static::LANG_EN => 'en-US',
            static::LANG_FR => 'fr-FR',
            static::LANG_RU => 'ru-RU',
        ];

        return array_key_exists($lang_id, $siteLanguages) ? $siteLanguages[$lang_id] : $siteLanguages;
    }

    /**
     * Get current site language code
     *
     * @return int
     */
    public static function getCurrentId()
    {
        $language_id = static::getLangIdByCode(Yii::$app->language);

        return !is_array($language_id) ? $language_id : static::DEFAULT_LANG;
    }

    /**
     * Get language ID by language code
     *
     * @param string $code
     *
     * @return int
     */
    public static function getLangIdByCode($code)
    {
        $short_code = static::getLangShortCode($code);
        $languages = static::getLanguages();

        foreach ($languages as $id => $language) {
            if ($short_code === static::getLangShortCode($language)) {
                return $id;
            }
        }

        return false;
    }

    /**
     * Set application language
     *
     * @param int $lang_id
     */
    public static function setAppLanguage($lang_id = self::DEFAULT_LANG)
    {
        $lang_code = static::getLanguages($lang_id);

        if (is_array($lang_code)) {
            $lang_code = Yii::$app->sourceLanguage;
        }

        Yii::$app->language = $lang_code;
    }

    /**
     * Get current reuqested url on $lang_id
     *
     * @param int $lang_id
     *
     * @return string
     */
    public static function getCurrentUrlOnLang($lang_id)
    {
        if (isset(static::$alternateLinks[$lang_id])) {
            return static::$alternateLinks[$lang_id];
        }

        // $requestUrl = Yii::$app->request->url;
        /** @var Request $request */
        $request = Yii::$app->request;
        $requestUrl = $request->getLangUrl();

        $lang_code = static::getLanguages($lang_id);

        if (is_array($lang_code)) {
            return $requestUrl;
        }

        $short_code = static::getLangShortCode($lang_code);

        if ($lang_id == static::DEFAULT_LANG) {
            return $requestUrl !== '' ? $requestUrl : '/';
        }

        // Return "/short_code/url_part"
        return '/' . rtrim($short_code . '/' . ltrim($requestUrl, '/'), '/');
    }

    /**
     * Create url from $url on the $lang_id with $urlScheme
     *
     * @param array|string|callable $url See Url::to(). Can be anon-function
     * @param int $lang_id               Language ID
     * @param bool $urlScheme            See Url::to()
     *
     * @return string
     */
    public static function createUrlProxy($url, $lang_id, $urlScheme = false)
    {
        $func = $url;
        if (!is_callable($func)) {
            $func = function () use ($url, $urlScheme) {
                return Url::to($url, $urlScheme);
            };
        }

        return static::proxy($func, $lang_id);
    }

    /**
     * Run function with swith App language to $lang_id and restore it after run
     *
     * @param callable $function Callable function
     * @param int $lang_id       Language ID
     *
     * @return mixed Result of function execute
     */
    public static function proxy($function, $lang_id)
    {
        $currentLang = Yii::$app->language;

        $proxyLang = static::getLanguages($lang_id);
        Yii::$app->language = $proxyLang;

        $result = call_user_func($function);

        Yii::$app->language = $currentLang;

        return $result;
    }

    /**
     * Set alternate links for current request
     *
     * @param int|array $lang_id Can be array of key-value pairs
     * @param string|null $url
     */
    public static function setAlternateLink($lang_id, $url = null)
    {
        if (is_array($lang_id)) {
            foreach ($lang_id as $k => $v) {
                static::setAlternateLink($k, $v);
            }
        } else {
            static::$alternateLinks[$lang_id] = $url;
        }
    }

    /**
     * Return alternate links for current request
     *
     * @return array
     */
    public static function getAlternateLinks()
    {
        $links = static::$alternateLinks;

        $languages = static::getLanguages();
        $buffer = [];
        foreach ($languages as $lang_id => $lang_code) {
            if (isset($links[$lang_id])) {
                // if isset custom alternate link
                $buffer[$lang_id] = $links[$lang_id];
            } else {
                // generate link to current url on $lang_id
                $buffer[$lang_id] = Yii::$app->request->hostInfo . static::getCurrentUrlOnLang($lang_id);
            }
        }

        if (isset($links['x-default'])) {
            $buffer['x-default'] = $links['x-default'];
        }

        return $buffer;
    }

    /**
     * Register alternate links tags
     *
     * @param \yii\web\View $view
     */
    public static function registerAlternateLinks(\yii\web\View $view)
    {
        $languages = static::getLanguages();
        $alternateLinks = static::getAlternateLinks();

        if (!isset($alternateLinks['x-default'])) {
            // Register "x-default" alternate tag
            $alternateLinks['x-default'] = Yii::$app->request->hostInfo . static::getCurrentUrlOnLang(static::DEFAULT_LANG);
        }

        // Register alternate links for each site language
        foreach ($alternateLinks as $lang_id => $url) {
            $lang_code = $lang_id !== 'x-default' ? $languages[$lang_id] : $lang_id;

            if (strpos($url, '://') === false) {
                $url = Yii::$app->request->getHostInfo() . $url;
            }

            $view->registerLinkTag([
                'rel' => 'alternate',
                'hreflang' => $lang_code,
                'href' => $url,
            ]);
        }
    }
}
