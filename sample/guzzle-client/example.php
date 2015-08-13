<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/src/Client/GuzzleClient.php';

use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Client\GuzzleClient;

$clientConfig = array(
    'base_uri' => '__FILL IN YOUR CONTENT API URL HERE__'
);
$parameters = array('start_date' => '2015-01-01');

$contentApi = new ContentApiSdk(new GuzzleClient($clientConfig));
$items = $contentApi->getItems($parameters);

echo '<pre>$items:'; var_dump($items); echo '<hr>'; exit;
