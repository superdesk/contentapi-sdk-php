Superdesk Content API PHP SDK
======

This is an SDK written in PHP for the Superdesk Content API.

Examples
------

### Get all items from 1st of January 2015.

```php
    use Superdesk\ContentApiSdk\ContentApiSdk;
    use Superdesk\ContentApiSdk\Client\Client;

    $clientConfig = array(
        'base_uri' => 'http://publicapi.example.com:5050'
    );
    $parameters = array('start_date' => '2015-01-01');

    $contentApi = new ContentApiSdk(new Client($$clientConfig));
    $items = $contentApi->getItems($parameters);
```
