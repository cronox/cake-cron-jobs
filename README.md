[![Build Status](https://travis-ci.org/cronox/cake-cron-jobs.svg?branch=master)](https://travis-ci.org/cronox/cake-cron-jobs)
[![Latest Stable Version](https://poser.pugx.org/cronox/cake-cron-jobs/v/stable)](https://packagist.org/packages/cronox/cake-cron-jobs)
[![Downloads](https://poser.pugx.org/cronox/cake-cron-jobs/d/total.png)](https://packagist.org/packages/cronox/cake-cron-jobs)
[![Latest Version](https://poser.pugx.org/cronox/cake-cron-jobs/v/stable.png)](https://packagist.org/packages/cronox/cake-cron-jobs)
[![License](https://poser.pugx.org/cronox/cake-cron-jobs/license.svg)](https://packagist.org/packages/cronox/cake-cron-jobs)

# Installation

## Composer
```sh
composer require cronox/cake-cron-jobs
```
## Load the Plugin
Ensure the Plugin is loaded in your config/bootstrap.php file
```php
Plugin::load('Cronox/CronJobs');
```
## Database
Init the database table by using cakephp's migrations
```sh
bin/cake migrations migrate --plugin Cronox/CronJobs
```

# Example

To add method `\App\Lib\Mailer::sendMail('cronox@example.com','Message text')` to cron queue:

```php
try {
    CronJobHelper::create(\App\Lib\Mailer::class, 'sendMail', ['cronox@example.com','Message text']);
} catch (\Exception $exception) {
    throw $exception;
}
```

To run queue by cron add following line to crontab:
```sh
bin/cake cronox/cron_jobs.cron_jobs
```

Example output:
```sh
➜  www ✗ bin/cake cronox/cron_jobs.cron_jobs
Found 1 jobs.
Running job #5 App\Lib\Mailer::sendMail
Job is completed correctly
➜  www ✗ 
```


