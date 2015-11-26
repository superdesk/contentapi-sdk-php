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
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;

class ContentApiSdkSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\ContentApiSdk');
    }

    function let(ClientInterface $client, Request $request, Response $response)
    {
        $this->beConstructedWith($client);
    }

    function its_method_get_item_should_return_an_item(ClientInterface $client, Request $request)
    {
        $request = $this->getNewRequest(sprintf('%s/%s', ContentApiSdk::SUPERDESK_ENDPOINT_ITEMS, Argument::type('string')));
        $client->makeApiCall($request)->shouldBeCalled()->willReturn(new Response('{"pubstatus": "usable", "_links": {"parent": {"href": "/", "title": "home"}, "collection": {"href": "items", "title": "items"}, "self": {"href": "items/tag:example.com,0001:newsml_BRE9A607", "title": "Item"}}, "body_text": "Andromeda and Milky Way will collide in about 2 billion years", "type": "text", "language": "en", "versioncreated": "2015-03-09T16:32:23+0000", "uri": "http://api.master.dev.superdesk.org/items/tag%3Aexample.com%2C0001%3Anewsml_BRE9A607", "version": "2", "headline": "Andromeda on a collision course"}'));
        $this->getItem(Argument::type('string'))->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\Data\Item');
    }

    function its_method_get_items_should_return_a_resource_collection(ClientInterface $client, Request $request)
    {
        $parameters = array('start_date' => '1970-01-01');
        $request = $this->getNewRequest(ContentApiSdk::SUPERDESK_ENDPOINT_ITEMS, $parameters);
        $client->makeApiCall($request)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "items", "href": "items?start_date=2015-08-01" } }, "_items": [], "_meta": { "page": 1, "total": 0, "max_results": 25 } }');
        $this->getItems($parameters)->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\API\Pagerfanta\ResourceCollection');
    }

    function its_method_get_package_should_return_a_package(ClientInterface $client, Request $request)
    {
        $request = $this->getNewRequest(sprintf('%s/%s', ContentApiSdk::SUPERDESK_ENDPOINT_PACKAGES, Argument::type('string')));
        $client->makeApiCall($request)->shouldBeCalled()->willReturn(new Response('{"headline": "foo bar", "version": "1", "type": "composite", "versioncreated": "2015-02-10T06:49:47+0000", "_links": {"parent": {"title": "home", "href": "/"}, "collection": {"title": "packages", "href": "packages"}, "self": {"title": "Package", "href": "packages/tag:example.com,0001:newsml_BRE9A606"}}, "pubstatus": "usable", "language": "en", "uri": "http://api.master.dev.superdesk.org/packages/tag%3Aexample.com%2C0001%3Anewsml_BRE9A606"}'));
        $this->getPackage(Argument::type('string'))->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\Data\Package');
    }

    function its_method_get_packges_should_return_a_resource_collection(ClientInterface $client, Request $request)
    {
        $parameters = array('start_date' => '1970-01-01');
        $request = $this->getNewRequest(ContentApiSdk::SUPERDESK_ENDPOINT_PACKAGES, $parameters);
        $client->makeApiCall($request)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "items", "href": "items?start_date=2015-08-01" } }, "_items": [], "_meta": { "page": 1, "total": 0, "max_results": 25 } }');
        $this->getItems($parameters)->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\API\Pagerfanta\ResourceCollection');
    }

    function its_method_get_available_endpoints_should_contain_all_endpoints()
    {
        $endpoints = $this->getAvailableEndpoints();
        $endpoints->shouldBeArray();
        $endpoints->shouldContain(ContentApiSdk::SUPERDESK_ENDPOINT_ITEMS);
        $endpoints->shouldContain(ContentApiSdk::SUPERDESK_ENDPOINT_PACKAGES);
    }

    function its_method_get_valid_parameters_should_return_valid_parameters()
    {
        $validParameters = $this->getValidParameters();
        $validParameters->shouldBe(ContentApiSdk::$validParameters);
    }

    function its_method_process_parameters_should_properly_process()
    {
        $parameters = array('start_date' => '2015-01-01');
        $this->processParameters($parameters)->shouldBe($parameters);
        $parameters = array('start_date' => new \DateTime());
        $this->processParameters($parameters)
            ->shouldBe(array(
                'start_date' => $parameters['start_date']->format('Y-m-d')
            ));

        $parameters = array('end_date' => '2015-01-01');
        $this->processParameters($parameters)->shouldBe($parameters);
        $parameters = array('end_date' => new \DateTime());
        $this->processParameters($parameters)
            ->shouldBe(array(
                'end_date' => $parameters['end_date']->format('Y-m-d')
            ));

        $parameters = array('q' => 'test');
        $this->processParameters($parameters)->shouldBe($parameters);

        $parameters = array('include_fields' => 'headline,body_text');
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'include_fields' => array('headline', 'body_text')
            ))
            ->shouldBe(array(
                'include_fields' => 'headline,body_text'
            ));

        $parameters = array('exclude_fields' => 'headline,body_text');
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'exclude_fields' => array('headline', 'body_text')
            ))
            ->shouldBe(array(
                'exclude_fields' => 'headline,body_text'
            ));

        $parameters = array('page' => 2);
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'page' => '2'
            ))
            ->shouldBe(array(
                'page' => 2
            ));

        $parameters = array('max_results' => 2);
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'max_results' => '2'
            ))
            ->shouldBe(array(
                'max_results' => 2
            ));
    }

    function its_method_process_parameters_should_throw_exceptions()
    {
        $parameters = array(
            'start_date' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'start_date' => '01-01-1970',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'end_date' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'end_date' => '01-01-1970',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'q' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'include_fields' => 1,
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'exclude_fields' => 1,
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'page' => 'page',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'max_results' => 'max_results',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);
    }

    function its_method_process_parameters_should_validate()
    {
        $validKeys = $this->getValidParameters();

        $invalidParameters = array(
            'some_name' => 'some value',
            'another_name' => 'another value',
            'max_results' => '5'
        );
        $processedParameters = $this->processParameters($invalidParameters, true);
        $processedParameters->shouldBe(array('max_results' => 5));
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
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetValidJsonObj('{ "some key" : "some value }');
    }
}
