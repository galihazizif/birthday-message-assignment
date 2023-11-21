<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">User Birthday Mail Message.</h1>
    <br>
</p>

This task assignment is built using Yii2 Framework

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      models/             contains model classes
      runtime/            contains files generated during runtime
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

AVAILABLE API
-------------


### User

POST *base_url/user/*
Payload
```json
{
	"firstname": "Roger",
	"lastname": "Chin",
	"email": "roger.chin@example.com",
	"birthday": "1992-11-21",
	"location":"Singapore",
	"time_offset": 9
}
```


#### CRON

Populating Scheduler
Can Be set Every 5 Minutes, to populate mail_scheduler table based from customer user data 
```php
php yii mail-queue/process-mail-queue
```

Send Email
Can Be set Every 3 Minutes, to send email using provided API
```php
php yii mail-queue/process-mail-queue
```

