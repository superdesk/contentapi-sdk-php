<?php

require __DIR__ . '/../../vendor/autoload.php';

use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Client\CurlClient;
use Superdesk\ContentApiSdk\Client\DefaultApiClient;
use Superdesk\ContentApiSdk\API\Authentication\OAuthPasswordAuthentication;
use Superdesk\ContentApiSdk\API\Request\RequestParameters;

/**
 * Configure Below
 *
 * Please fill in the correct value of your Content API instance.
 */
define('API_HOST', '');
define('API_PORT', 80);
define('API_PROTOCOL', null);

define('API_CLIENT_ID', '');
define('API_USERNAME', '');
define('API_PASSWORD', '');
/**
 * End of configuration
 */

$parameters = new RequestParameters();
$parameters
    ->setStartDate(date('Y-m-d', strtotime('-7 days')))
    ->setPage(1)
    ->setMaxResults(1);

$genericClient = new CurlClient();
$authentication = new OAuthPasswordAuthentication($genericClient);
$authentication
    ->setClientId(API_CLIENT_ID)
    ->setUsername(API_USERNAME)
    ->setPassword(API_PASSWORD);
$apiClient = new DefaultApiClient($genericClient, $authentication);
$contentApi = new ContentApiSdk($apiClient, API_HOST, API_PORT, API_PROTOCOL);



echo ".:  Getting items  :.\n\n";

$items = $contentApi->getItems($parameters);

echo "Total items: {$items->getNbResults()}\n";
echo "Items per page: {$items->getMaxPerPage()}\n";
echo "Total pages: {$items->getNbPages()}\n\n";

// Some limit, so we dont accicentally get every item
$maxPages = ($items->getNbPages() < 10) ? $items->getNbPages() : 10;

if ($items->haveToPaginate()) {
    for ($i = 1; $i <= $maxPages; $i++) {
        echo "Current page: {$items->getCurrentPage()}\n";
        $data = $items->getCurrentPageResults();
        foreach ($data as $item) {
            echo "Item headline: {$item->headline}\n";
        }

        if ($items->hasNextPage()) {
            $items->setCurrentPage($i+1);
        }

        echo "\n";
    }
} else {
    echo "Current page: {$items->getCurrentPage()}\n";
    $data = $items->getCurrentPageResults();
    foreach ($data as $item) {
        echo "Item headline: {$item->headline}\n";
    }
}



echo "\n\n.:  Getting packages  :.\n\n";

$packages = $contentApi->getPackages($parameters, true);

echo "Total packages: {$packages->getNbResults()}\n";
echo "Packages per page: {$packages->getMaxPerPage()}\n";
echo "Total pages: {$packages->getNbPages()}\n\n";

// Some limit, so we dont accicentally get every package
$maxPages = ($packages->getNbPages() < 10) ? $packages->getNbPages() : 10;

if ($packages->haveToPaginate()) {
    for ($i = 1; $i <= $maxPages; $i++) {
        echo "Current page: {$packages->getCurrentPage()}\n";
        $data = $packages->getCurrentPageResults();
        foreach ($data as $package) {
            echo "Package headline: {$package->headline}\n";
        }

        if ($packages->hasNextPage()) {
            $packages->setCurrentPage($i+1);
        }
        echo "\n";
    }
} else {
    echo "Current page: {$packages->getCurrentPage()}\n";
    $data = $packages->getCurrentPageResults();
    foreach ($data as $package) {
        echo "Package headline: {$package->headline}\n";
    }
}
