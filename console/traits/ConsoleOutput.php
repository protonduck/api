<?php

namespace console\traits;

use Yii;
use yii\base\Model;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Colorized console output helper
 */
trait ConsoleOutput
{
    /**
     * Функция обратного вызова, срабатывает при попытке отображения любого сообщения:
     * `function (string $type, string $text): void {}`
     *
     * @var null|callable
     */
    public $onMessageCallback;

    /**
     * Info message
     *
     * @param string $message "Hello %s"
     * @param mixed $args     "World" [optional]
     * @param mixed $_        [optional]
     *
     * @return bool|int
     */
    public function info($message, $args = null, $_ = null)
    {
        $text = $this->formatMessage($message, func_get_args());
        $this->onMessage(__FUNCTION__, $text);

        if (!$this->isConsoleApp()) {
            return false;
        }

        Yii::$app->controller->stdout($text . PHP_EOL);

        return ExitCode::OK;
    }

    /**
     * Success message
     *
     * @param string $message "Hello %s"
     * @param mixed $args     "World" [optional]
     * @param mixed $_        [optional]
     *
     * @return bool|int
     */
    public function success($message, $args = null, $_ = null)
    {
        $text = $this->formatMessage($message, func_get_args());
        $this->onMessage(__FUNCTION__, $text);

        if (!$this->isConsoleApp()) {
            return false;
        }

        Yii::$app->controller->stdout($text . PHP_EOL, Console::FG_GREEN);

        return ExitCode::OK;
    }

    /**
     * Warning message
     *
     * @param string $message "Hello %s"
     * @param mixed $args     "World" [optional]
     * @param mixed $_        [optional]
     *
     * @return bool|int
     */
    public function warning($message, $args = null, $_ = null)
    {
        $text = $this->formatMessage($message, func_get_args());
        $this->onMessage(__FUNCTION__, $text);

        if (!$this->isConsoleApp()) {
            return false;
        }

        Yii::$app->controller->stdout($text . PHP_EOL, Console::FG_YELLOW);

        return ExitCode::OK;
    }

    /**
     * Error message
     *
     * @param string $message "Hello %s"
     * @param mixed $args     "World" [optional]
     * @param mixed $_        [optional]
     *
     * @return bool|int
     */
    public function error($message, $args = null, $_ = null)
    {
        $text = $this->formatMessage($message, func_get_args());
        $this->onMessage(__FUNCTION__, $text);

        if (!$this->isConsoleApp()) {
            return false;
        }

        Yii::$app->controller->stderr($text . PHP_EOL, Console::FG_RED);

        return ExitCode::UNSPECIFIED_ERROR;
    }

    /**
     * Display model errors
     *
     * @param \yii\base\Model $model
     *
     * @return bool|int
     */
    public function modelErrors(Model $model)
    {
        if (!$this->isConsoleApp()) {
            return false;
        }

        if (!$model->hasErrors()) {
            return Yii::$app->controller->stderr('Unknown error' . PHP_EOL, Console::FG_RED);
        }

        $message = 'Errors while saving:' . PHP_EOL . PHP_EOL;

        foreach ($model->errors as $attr => $errors) {
            foreach ($errors as $error) {
                $message .= "$attr: $error" . PHP_EOL;
            }
        }

        Yii::$app->controller->stderr($message . PHP_EOL, Console::FG_RED);

        return ExitCode::UNSPECIFIED_ERROR;
    }

    /**
     * Вызывается при попытке отобразить любое сообщение
     *
     * @param string $type Тип info, success, warning или error
     * @param string $text Отформатированный текст сообщения
     */
    private function onMessage($type, $text): void
    {
        if (!is_callable($this->onMessageCallback)) {
            return;
        }

        call_user_func($this->onMessageCallback, $type, $text);
    }

    /**
     * Return formatted message @param string $message
     *
     * @param array $args
     *
     * @return string
     * @see vsprintf()
     *
     */
    private function formatMessage($message, $args)
    {
        unset($args[0]);

        try {
            return vsprintf($message, $args);
        } catch (\Exception $e) {
            return $message;
        }
    }

    /**
     * Is current running console app
     *
     * @return bool
     */
    private function isConsoleApp()
    {
        return Yii::$app->controller instanceof \yii\console\Controller;
    }
}
