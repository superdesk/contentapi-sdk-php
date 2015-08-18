# Superdesk Content API PHP SDK
[![Build Status](https://travis-ci.org/superdesk/contentapi-sdk-php.svg?branch=master)](https://travis-ci.org/superdesk/contentapi-sdk-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/superdesk/contentapi-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/superdesk/contentapi-sdk-php/?branch=master) 
[![Code Climate](https://codeclimate.com/github/superdesk/contentapi-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/superdesk/contentapi-sdk-php)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b7fbb859-3d37-4945-91ae-940daf0073ec/mini.png)](https://insight.sensiolabs.com/projects/b7fbb859-3d37-4945-91ae-940daf0073ec)

This is an SDK written in PHP for the Superdesk Content API. 

For more information about the Superdesk Content API please read the [documentation](http://docs.superdeskcontentapi.apiary.io/).

#### Disclaimer
The SDK is still in alpha version, so anything might change. Steady class names 
and methods are not guaranteed yet.

## Installation

### Composer
* Require the SDK as a composer dependency 
```php composer.phar require superdesk/contentapi-sdk-php```

### Manual
* Download a copy of the SDK
* Add the classes to your autoloader

## Examples

Two examples are included where it shown how to use the SDK. 

### Example 01

This example uses the FileGetContentsClient, which is a very simple 
file_get_contents call to the public api. Make sure that the fopen-wrappers
are enabled. Please edit the example and fill in your Content API url.

You can run the example via the cli with the command:
```php sample/default-client/example.php```

### Quickstart

Get all items, filtering by date from the 1st of January 2015.

```php
<?php

    use Superdesk\ContentApiSdk\ContentApiSdk;
    use Superdesk\ContentApiSdk\Client\FileGetContentsClient;

    $clientConfig = array(
        'base_uri' => 'http://publicapi.example.com:5050'
    );
    $parameters = array('start_date' => '2015-01-01');

    $contentApi = new ContentApiSdk(new FileGetContentsClient($clientConfig));
    $items = $contentApi->getItems($parameters);

    // Do something useful with your items here
```
