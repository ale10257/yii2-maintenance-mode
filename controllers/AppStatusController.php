<?php
/**
 * @link https://github.com/brussens/yii2-maintenance-mode
 * @copyright Copyright (c) 2017 Brusensky Dmitry
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace ale10257\statusApplication\controllers;

use ale10257\statusApplication\AppStatusMode;
use Yii;
use yii\web\Controller;

/**
 * Default controller of maintenance mode component for Yii framework 2.x.x version.
 *
 * @see \yii\web\Controller
 * @package ale10257\statusApplication\controllers
 * @author Brusensky Dmitry <brussens@nativeweb.ru>
 * @since 0.2.0
 */
class AppStatusController extends Controller
{
    /** @var AppStatusMode */
    private $appStatusMode;

    /**
     * Initialize controller.
     */
    public function init()
    {
        $this->appStatusMode = Yii::$app->appStatusMode;
        $this->layout = $this->appStatusMode->layoutPath;
        parent::init();
    }

    /**
     * Index action.
     * @return bool|string
     */
    public function actionIndex()
    {
        $data = json_decode(file_get_contents($this->appStatusMode->filePath), true);
        return $this->render($this->appStatusMode->viewPath, $data);
    }
} 