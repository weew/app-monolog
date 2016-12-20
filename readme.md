# App monolog provider

[![Build Status](https://img.shields.io/travis/weew/app-monolog.svg)](https://travis-ci.org/weew/app-monolog)
[![Code Quality](https://img.shields.io/scrutinizer/g/weew/app-monolog.svg)](https://scrutinizer-ci.com/g/weew/app-monolog)
[![Test Coverage](https://img.shields.io/coveralls/weew/app-monolog.svg)](https://coveralls.io/github/weew/app-monolog)
[![Version](https://img.shields.io/packagist/v/weew/app-monolog.svg)](https://packagist.org/packages/weew/app-monolog)
[![Licence](https://img.shields.io/packagist/l/weew/app-monolog.svg)](https://packagist.org/packages/weew/app-monolog)

## Table of contents

- [Installation](#installation)
- [Usage](#usage)
- [Example config](#example-config)

## Installation

`composer require weew/app-monolog`

## Introduction

This package integrates the [monolog/monolog](https://github.com/Seldaek/monolog) library into the [weew/php-app](https://github.com/weew/php-app) package.

## Usage

To make monolog available inside the application, simply register `MonologProvider` on the kernel.

```php
$app->getKernel()->addProviders([
    MonologProvider::class
]);
```

You can retrieve a specific logger by the channel name:

```php
$channelManager = $app->getContainer()->get(IMonologChannelManager::class);
$channelManager->getLogger('config_name');
```

## Example config

This is how your configuration *might* look like:

```yml
monolog:
  channels:

    default:
      log_file_path: /var/logs/default.log
      log_level: debug

    error:
      log_file_path: /var/logs/error.log
      log_level: debug
```
