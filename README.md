# Yii2 enable/disable application component

#### [За основу данного компонента взят компонент Maintenance mode, автор: Brusenskiy Dmitry](https://github.com/brussens/yii2-maintenance-mode)

## Установка

В консоли
```
composer require ale10257/yii2-maintenance-mode "@dev"
```

или добавить в секцию `require` файла `composer.json`

```
"ale10257/yii2-maintenance-mode": "@dev"
```
По умолчанию, если вы выключили сайт, с помощь данного компонента, он будет закрыт для всех, в том числе и для администраторов. 

Т.к. проверка ролей в разных приложениях на Yii2 осуществляется по разному, для полноценной работы необходимо создать ваш собственный компонент для включения/выключения вашего приложения, в котором нужно прописать все ваши методы для проверки доступа.

Например:

```php
<?php
namespace app\components\statusApp;

use ale10257\yii2MaintenanceMode;
use Yii;

class StatusApp extends AppStatusMode
{
    /** @var string */
    public $commandPath = __DIR__;

    protected function checkRoot()
    {
        $app = Yii::$app;
        if (!$app->user->isGuest) {
            if ($app->user->identity->isRoot()) {
                $this->enabled = true;
            }
        }
    }

    protected function filtering()
    {
        $this->checkRoot();
        parent::filtering();
    }
}
```

<b>Важно:</b> публичная переменная `public $commandPath = __DIR__;` определяет директорию для записи файла о включении/выключении приложения. Данная директория должна быть доступна для записи.

<b>Важно:</b> все ваши методы для проверки должны быть вызваны в методе `filtering`. Например, в данном примере, если у пользователя есть роль Root, то для него приложение включено `$this->enabled = true;`

### Options
```php
'maintenanceMode' => [
    // Page title default
    'title' => 'Site temporarily unavailable',

    // Application status
    'enabled' => true,

    // Show message default
    'message' => 'Sorry, technical work in progress',

    // Allowed roles
    'roles' => [],

    // Allowed IP addresses example: '127.0.0.1'
    'ips' => [],

    // HTTP Status Code
    'statusCode' => 503,

    //Retry-After example: header 120 or Wed, 21 Oct 2015 07:28:00 GMT
    'retryAfter'
],
```

Если вы инициализируете `'roles' => [admin, role1, role2]`, то в вашем компоненте необходимо самостоятельно реализовать метод 

```php
protected function checkRoles()
{
    // your check
}
```

### Для работы компонента необходимо добавить в конфигурационный файл web приложения:

```php
'bootstrap' => [
...
     'ale10257\yii2MaintenanceMode\CheckStatus'
...
],
...
'components' => [
...
    'appStatusMode' => [
        'class' => 'app\components\statusApp\StatusApp', // ваш компонент
    ],
...
],
```

### Включить/выключить приложение из консоли

Добавить в конфигурационный файл консольного приложения:

```php
'bootstrap' => [
    'log',
    'appStatusMode'
],
...
'components' => [
...
    'appStatusMode' => [
        'class' => 'app\components\statusApp\StatusApp', // ваш компонент
    ],
...
],
```

После внесения изменений в конфигурационный файл можно включать/выключать приложение командами:

```php
php yii  app-status/enable
php yii  app-status/disable 'Title page' 'Your message'
```

По умолчанию `Title page = 'Site temporarily unavailable', Your message = 'Sorry, technical work in progress'`

Поэтому команду для выключения приложения можно вызывать без параметров

```php
php yii  app-status/disable
```

### Включить/выключить приложение из панели управления приложением

В контроллере вызовите методы:

```php
\Yii->$app->appStatusMode->enable();
\Yii->$app->appStatusMode->disable('Title page', 'Your message'); // можно вызывать без параметров
```
