# Superdesk Content API PHP SDK
[![Build Status](https://travis-ci.org/superdesk/contentapi-sdk-php.svg?branch=master)](https://travis-ci.org/superdesk/contentapi-sdk-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/superdesk/contentapi-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/superdesk/contentapi-sdk-php/?branch=master) 
[![Code Climate](https://codeclimate.com/github/superdesk/contentapi-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/superdesk/contentapi-sdk-php)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b7fbb859-3d37-4945-91ae-940daf0073ec/mini.png)](https://insight.sensiolabs.com/projects/b7fbb859-3d37-4945-91ae-940daf0073ec)

This is an SDK written in PHP for the Superdesk Content API. For more 
information take a look at our API documentation in the api directory.

For more information about the Superdesk Content API please read the [documentation](http://docs.superdeskcontentapi.apiary.io/).

## Installation

### Requirements
* PHP >= 5.3

When using the CurlApiClient and CurlClient classes make sure you've installed
the following PHP extensions:
* [cURL](http://php.net/manual/book.curl.php)
* [Multibyte String](http://php.net/manual/book.mbstring.php)

### Composer
* Require the SDK as a composer dependency 

```bash
$ php composer.phar require superdesk/contentapi-sdk-php
```

### Manual
* Download a copy of the SDK
* Add the classes to your autoloader

## Customization
You can use your own client classes instead of 
[CurlClient](src/Superdesk/ContentApiSdk/Client/CurlClient.php) and 
[CurlApiClient](src/Superdesk/ContentApiSdk/Client/CurlApiClient.php) files. 
This is for example useful if your framework or project already has it's own 
http client, you can easily incorporate that without having multiple 
depencencies.
All you need to do is to implement the 
[ClientInterface](src/Superdesk/ContentApiSdk/Client/ClientInterface.php) and 
[ClientApiInterface](src/Superdesk/ContentApiSdk/Client/ClientApiInterface.php). 
For the ClientApi class there also a useful abstract class 
[AbstractApiClient](src/Superdesk/ContentApiSdk/Client/AbstractApiClient.php) 
which contains some sane defaults.

## Examples

This example uses the [CurlClient](src/Superdesk/ContentApiSdk/Client/CurlClient.php) 
and [CurlApiClient](src/Superdesk/ContentApiSdk/Client/CurlApiClient.php) files.

You can run the [example.php](sample/default-client/example.php) via the cli 
with the command:

```bash
$ php sample/default-client/example.php
```

Make sure you have the extenions _cURL_ and _Multibyte String_ enabled and 
you've installed the vendors.

```bash
$ php composer.phar install --no-dev
```

### Samples

#### Authentication

OAuth username and password authentication. The 
[CurlApiClient](src/Superdesk/ContentApiSdk/Client/CurlApiClient.php) 
will automatically try to retrieve new access token if the previous token has
been invalited.


```php
<?php

use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Client\CurlClient;
use Superdesk\ContentApiSdk\Client\CurlApiClient;
use Superdesk\ContentApiSdk\Api\Authentication\OAuthPasswordAuthentication;

$genericClient = new CurlClient();
$authentication = new OAuthPasswordAuthentication($genericClient);
$authentication
    ->setClientId(API_CLIENT_ID)
    ->setUsername(API_USERNAME)
    ->setPassword(API_PASSWORD);
$apiClient = new CurlApiClient($genericClient, $authentication);
$contentApi = new ContentApiSdk($apiClient, API_HOST, API_PORT, API_PROTOCOL);

?>
```

#### Pagination and traversing through results

We use [Pagerfanta](https://github.com/whiteoctober/Pagerfanta) as a pagination
library. All results returned from the methods _getItems(...)_ and 
_getPackages(...)_ are actually Pagerfanta instances. That should make it easy
enough to traverse through your API results and also built in your own pagination.

```php
<?php

use Superdesk\ContentApiSdk\ContentApiSdk;

$contentApi = new ContentApiSdk(...);

$maxPerPage = 1;
$startPage = 1;

$items = $contentApi->getItems(array(), $startPage, $maxPerPage);

$items->getNbResults(); // Total items
$items->getMaxPerPage(); // Max results per age == $maxPerPage
$items->getNbPages(); // Total pages

?>
```
