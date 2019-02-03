<?php
/**
 * @link https://github.com/ale10257/change-status-application
 * @copyright Copyright (c) 2017 Brusensky Dmitry
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace ale10257\yii2MaintenanceMode\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Console controller for manage maintenance mode component for Yii framework 2.x.x version.
 *
 * @see \yii\console\Controller
 * @package ale10257\yii2MaintenanceMode\commands
 */
class AppStatusController extends Controller
{
    public function actionIndex()
    {
        echo 'You have to input command "enable" or "disable"!' . PHP_EOL;
    }

    public function actionEnable()
    {
        $appStatusMode = Yii::$app->appStatusMode;
        if (!$appStatusMode->isDisabled) {
            $this->stdout("Application already enabled" . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $appStatusMode->enable();
        $this->stdout("Application enabled successfully" . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }

    public function actionDisable($title = null, $msg = null)
    {
        $appStatusMode = Yii::$app->appStatusMode;

        if ($appStatusMode->isDisabled) {
            $this->stdout("Application already disabled" . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $appStatusMode->disable($title, $msg);
        $this->stdout("Application disabled successfully" . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }
}