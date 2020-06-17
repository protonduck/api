<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use Symfony\Component\Yaml\Yaml;

/**
 * Swagger controller
 *
 * @property \api\modules\BaseModule $module
 */
class DocsController extends Controller
{
    /**
     * Display api documentation
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Get API resources
     *
     * @return mixed
     */
    public function actionResource()
    {
        $apiVersion = $this->module->getApiVersion();
        $swaggerPath = Yii::getAlias('@api') . "/modules/v$apiVersion/swagger";
        $all = Yaml::parseFile($swaggerPath . '/main.yaml');
        $all['servers'][0]['url'] = Yii::$app->request->hostInfo . '/v' . $apiVersion;
        $all['paths'] = $this->getYamlFromDir($swaggerPath . '/paths/');
        $all['components']['schemas'] = $this->getYamlFromDir($swaggerPath . '/schemas/');

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $all;
    }

    /**
     * Return yml-files content from dir
     *
     * @param string $dir
     *
     * @return array
     */
    private function getYamlFromDir($dir)
    {
        $pathData = [];
        $files = scandir($dir, 0);
        foreach ($files as $file) {
            if (!is_dir($file)) {
                $data = Yaml::parseFile($dir . $file);
                foreach ($data as $key => $value) {
                    $pathData[$key] = $value;
                }
            }
        }

        return $pathData;
    }
}
