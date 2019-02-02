<?php
/**
 * @link https://github.com/brussens/yii2-maintenance-mode
 * @copyright Copyright (c) 2017 Brusensky Dmitry
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace ale10257\yii2MaintenanceMode;

use yii\web\AssetBundle;

/**
 * Maintenance mode component asset bundle.
 *
 * @since 0.2.0
 * @see \yii\web\AssetBundle
 * @package ale10257\yii2MaintenanceMode
 * @author Brusensky Dmitry <brussens@nativeweb.ru>
 */
class Asset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        YII_ENV_DEV ? 'css/styles.css' : 'css/styles.min.css',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}