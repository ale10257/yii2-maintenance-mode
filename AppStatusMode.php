<?php

namespace ale10257\yii2MaintenanceMode;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Component;
use yii\helpers\FileHelper;

/**
 * Class AppStatusMode
 * @package ale10257\yii2MaintenanceMode
 *
 * @property string $filePath
 * @property bool $isDisabled
 */

class AppStatusMode extends Component
{
    /** @var bool */
    public $enabled = true;
    /** @var string  */
    public $title = 'Site temporarily unavailable';
    /** @var string  */
    public $message = 'Sorry, technical work in progress';
    /** @var string|array */
    public $ips;
    /** @var string  */
    public $layoutPath = '@vendor/ale10257/yii2-maintenance-mode/views/main';
    /** @var string  */
    public $viewPath = '@vendor/ale10257/yii2-maintenance-mode/views/index';
    /** @var string  */
    public $commandPath = __DIR__;
    /** @var int  */
    public $statusCode = 503;
    /** @var bool  */
    public $retryAfter = false;
    /** @var string  */
    public $consoleController = 'ale10257\yii2MaintenanceMode\commands\AppStatusController';
    /** @var array */
    public $roles;

    /**
     * @throws \yii\base\Exception
     */
    public function init()
    {
        Yii::setAlias('@appStatus', $this->commandPath);
        if (!is_dir(Yii::getAlias('@appStatus'))) {
            FileHelper::createDirectory(Yii::getAlias('@appStatus'));
        }
        if (Yii::$app instanceof \yii\console\Application) {
            Yii::$app->controllerMap['app-status'] = $this->consoleController;
        }
    }

    public function check()
    {
        if ($this->isDisabled) {
            $this->filtering();
        }
    }

    /**
     * @return bool
     */
    public function getIsDisabled()
    {
        $check = is_file($this->filePath);
        if ($check) {
            $this->enabled = false;
        }
        return $check;
    }

    /**
     * @return bool|string
     */
    public function getFilePath()
    {
        return Yii::getAlias('@appStatus/.disabled');
    }

    public function disable($title = null, $msg = null)
    {
        $path = $this->filePath;
        if (!$title) {
            $title = $this->title;
        }
        if (!$msg) {
            $msg = $this->message;
        }
        if (file_put_contents($path, json_encode(['title' => $title, 'msg' => $msg])) === false) {
            throw new \DomainException('Unable to create file ' . $path);
        }
    }

    public function enable()
    {
        $path = $this->filePath;
        if (!unlink($path)) {
            throw new \DomainException('Unable to delete file ' . $path);
        }
    }

    protected function checkIp()
    {
        $ip = Yii::$app->request->userIP;
        foreach ($this->ips as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }
        return false;
    }

    protected function checkRoles()
    {
        return true;
    }

    protected function filtering()
    {
        $app = Yii::$app;
        if ($this->statusCode) {
            if (!is_integer($this->statusCode)) {
                throw new InvalidConfigException('Parameter "statusCode" should be an integer');
            }
            $app->response->statusCode = $this->statusCode;
            if ($this->retryAfter) {
                $app->response->headers->set('Retry-After', $this->retryAfter);
            }
        }
        if ($this->roles) {
            $this->checkRoles();
        }
        if ($this->ips) {
            if (!is_array($this->ips)) {
                throw new InvalidConfigException('Parameter "ips" should be an array');
            }
            $this->enabled = $this->checkIp();
        }
        if (!$this->enabled) {
            $app->controllerMap['disabled'] = 'ale10257\yii2MaintenanceMode\controllers\AppStatusController';
            $app->catchAll = ['disabled'];
        }
    }
}