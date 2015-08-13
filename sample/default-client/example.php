<?php

require __DIR__ . '/../../vendor/autoload.php';

use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Client\FileGetContentsClient;

$clientConfig = array(
    'base_uri' => '__FILL IN YOUR CONTENT API URL HERE__'
);
$parameters = array('start_date' => '2015-01-01');

$contentApi = new ContentApiSdk(new FileGetContentsClient($clientConfig));
$items = $contentApi->getItems($parameters);
$packages = $contentApi->getPackages($parameters, true);

echo '<pre>$items:'; var_dump($items); echo '<hr>';
echo '<pre>$packages:'; var_dump($packages); echo '<hr>';
