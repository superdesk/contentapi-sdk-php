<?php

/**
 * This file is part of the PHP SDK library for the Superdesk Content API.
 *
 * Copyright 2015 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2015 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */

namespace spec\Superdesk\ContentApiSdk\API\Pagerfanta;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\API\Request\RequestInterface;
use Superdesk\ContentApiSdk\API\Request\PaginationDecorator;
use Superdesk\ContentApiSdk\API\Response;
use Superdesk\ContentApiSdk\Data\Item;

class ItemAdapterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\API\Pagerfanta\ItemAdapter');
    }

    function let(ClientInterface $client, RequestInterface $request)
    {
        $this->beConstructedWith($client, $request);
    }

    function its_method_get_slice_should_return_items($client, $request)
    {
        $paginationRequest = new PaginationDecorator($request->getWrappedObject());
        $paginationRequest->addPagination(1, 1);
        $response = new Response('{"_links": {"self": {"title": "items", "href": "items?start_date=2015-01-01&max_results=2&page=1"}, "parent": {"title": "home", "href": "/"}}, "_meta": {"total": 2, "max_results": 2, "page": 1}, "_items": [{"headline": "Andromeda on a collision course", "body_text": "Andromeda and Milky Way will collide in about 2 billion years", "_links": {"self": {"title": "Item", "href": "items/tag:example.com,0001:newsml_BRE9A607"}}, "versioncreated": "2015-03-09T16:32:23+0000", "pubstatus": "usable", "version": "2", "uri": "http://api.master.dev.superdesk.org/items/tag%3Aexample.com%2C0001%3Anewsml_BRE9A607", "_type": "items", "language": "en", "type": "text"}, {"headline": "Die Lore-Ley", "body_text": "Ich wei\u00df nicht was soll es bedeuten", "_links": {"self": {"title": "Item", "href": "items/tag:example.com,0001:newsml_BRE9A608"}}, "versioncreated": "2015-04-19T13:45:54+0000", "pubstatus": "usable", "version": "3", "uri": "http://api.master.dev.superdesk.org/items/tag%3Aexample.com%2C0001%3Anewsml_BRE9A608", "_type": "items", "language": "de", "type": "text"}]}');
        $client->makeApiCall($paginationRequest)->shouldBeCalled()->willReturn($response);
        $this->getSlice(1, 1)->shouldContainItems();
    }

    public function getMatchers()
    {
        return array(
            'containItems' => function ($items) {
                foreach ($items as $item) {
                    if (!$item instanceof Item) {
                        return false;
                    }
                }
                return true;
            }
        );
    }
}
