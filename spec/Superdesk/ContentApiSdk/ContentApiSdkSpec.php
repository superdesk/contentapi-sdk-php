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

namespace spec\Superdesk\ContentApiSdk;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\Client\ApiClientInterface;
use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;

class ContentApiSdkSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\ContentApiSdk');
    }

    function let(ApiClientInterface $client, Request $request, Response $response)
    {
        $this->beConstructedWith($client);
    }

    function it_should_set_and_get_a_protocol()
    {
        $protocol = 'some protocol';
        $this->setProtocol($protocol);
        $this->getProtocol()->shouldReturn($protocol);
    }

    function it_should_set_and_get_a_port()
    {
        $port = 123456789;
        $this->setPort($port);
        $this->getPort()->shouldReturn($port);
    }

    function it_should_set_and_get_a_host()
    {
        $host = 'some host';
        $this->setHost($host);
        $this->getHost()->shouldReturn($host);
    }

    function it_should_set_and_get_a_client(ApiClientInterface $client)
    {
        $this->setClient($client);
        $this->getClient()->shouldReturn($client);
    }

    function its_method_get_item_should_return_an_item(ApiClientInterface $client, Request $request)
    {
        $request = $this->getNewRequest(sprintf('%s/%s', ContentApiSdk::SUPERDESK_ENDPOINT_ITEMS, Argument::type('string')));
        $client->makeApiCall($request)->shouldBeCalled()->willReturn(new Response('{"pubstatus": "usable", "_links": {"parent": {"href": "/", "title": "home"}, "collection": {"href": "items", "title": "items"}, "self": {"href": "items/tag:example.com,0001:newsml_BRE9A607", "title": "Item"}}, "body_text": "Andromeda and Milky Way will collide in about 2 billion years", "type": "text", "language": "en", "versioncreated": "2015-03-09T16:32:23+0000", "uri": "http://api.master.dev.superdesk.org/items/tag%3Aexample.com%2C0001%3Anewsml_BRE9A607", "version": "2", "headline": "Andromeda on a collision course"}'));
        $this->getItem(Argument::type('string'))->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\Data\Item');
    }

    function its_method_get_items_should_return_a_resource_collection(ApiClientInterface $client, Request $request)
    {
        $parameters = array('start_date' => '1970-01-01');
        $request = $this->getNewRequest(ContentApiSdk::SUPERDESK_ENDPOINT_ITEMS, $parameters);
        $client->makeApiCall($request)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "items", "href": "items?start_date=2015-08-01" } }, "_items": [], "_meta": { "page": 1, "total": 0, "max_results": 25 } }');
        $this->getItems($parameters)->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\API\Pagerfanta\ResourceCollection');
    }

    function its_method_get_package_should_return_a_package(ApiClientInterface $client, Request $request)
    {
        $request = $this->getNewRequest(sprintf('%s/%s', ContentApiSdk::SUPERDESK_ENDPOINT_PACKAGES, Argument::type('string')));
        $client->makeApiCall($request)->shouldBeCalled()->willReturn(new Response('{"headline": "foo bar", "version": "1", "type": "composite", "versioncreated": "2015-02-10T06:49:47+0000", "_links": {"parent": {"title": "home", "href": "/"}, "collection": {"title": "packages", "href": "packages"}, "self": {"title": "Package", "href": "packages/tag:example.com,0001:newsml_BRE9A606"}}, "pubstatus": "usable", "language": "en", "uri": "http://api.master.dev.superdesk.org/packages/tag%3Aexample.com%2C0001%3Anewsml_BRE9A606"}'));
        $this->getPackage(Argument::type('string'))->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\Data\Package');
    }

    function its_method_get_packges_should_return_a_resource_collection(ApiClientInterface $client, Request $request)
    {
        $parameters = array('start_date' => '1970-01-01');
        $request = $this->getNewRequest(ContentApiSdk::SUPERDESK_ENDPOINT_PACKAGES, $parameters);
        $client->makeApiCall($request)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "items", "href": "items?start_date=2015-08-01" } }, "_items": [], "_meta": { "page": 1, "total": 0, "max_results": 25 } }');
        $this->getPackages($parameters)->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\API\Pagerfanta\ResourceCollection');
    }

    function its_method_get_available_endpoints_should_contain_all_endpoints()
    {
        $endpoints = $this->getAvailableEndpoints();
        $endpoints->shouldBeArray();
        $endpoints->shouldContain(ContentApiSdk::SUPERDESK_ENDPOINT_ITEMS);
        $endpoints->shouldContain(ContentApiSdk::SUPERDESK_ENDPOINT_PACKAGES);
    }

    function its_method_get_valid_json_obj_should_return_an_object_on_succes()
    {
        $jsonObj = self::getValidJsonObj('{ "some key" : "some value" }');
        $jsonObj->shouldHaveType('stdClass');
    }

    function its_method_get_valid_json_obj_should_throw_an_exception_during_failure()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetValidJsonObj(null);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetValidJsonObj('<?xml version="1.0" encoding="UTF-8" standalone="no" ?><error>This is not json</error>');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetValidJsonObj('{ "some key" : "some invalid value }');
    }
}
