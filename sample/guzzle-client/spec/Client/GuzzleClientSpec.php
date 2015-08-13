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

namespace spec\Superdesk\ContentApiSdk\Client;

use PhpSpec\ObjectBehavior;

class GuzzleClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Client\GuzzleClient');
        $this->shouldImplement('Superdesk\ContentApiSdk\Client\ClientInterface');
    }

    function let()
    {
        $config = array('base_uri' => 'http://httpbin.org');
        $this->beConstructedWith($config);
    }

    function it_should_make_a_call_to_a_remote_server()
    {
        $this->makeApiCall('/status/200', null, null)->shouldBe('');
    }

    function it_should_throw_an_exception_when_an_error_occurs()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ContentApiException')->duringMakeApiCall('/status/404', null, null);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ContentApiException')->duringMakeApiCall('/status/500', null, null);
    }

    function it_should_throw_an_exception_on_invalid_baseuri()
    {
        $config = array('base_uri' => '');
        $this->beConstructedWith($config);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ContentApiException')->duringMakeApiCall('', null, null);
    }

    function it_should_be_able_to_return_json()
    {
        $config = array(
            'base_uri' => 'http://httpbin.org',
            'options' => array(
                'Content-Type' => 'application/json'
            )
        );
        $this->beConstructedWith($config);
        $response = $this->makeApiCall('/headers', null, null, true);
        $response['headers']->shouldHaveKey('Content-Type');
        $response['headers']['Content-Type']->shouldContain('application/json');
        $response['body']->shouldBeString();
    }

    function it_should_be_able_to_return_xml()
    {
        $config = array(
            'base_uri' => 'http://httpbin.org',
            'options' => array(
                'Content-Type' => 'application/xml'
            )
        );
        $this->beConstructedWith($config);
        $response = $this->makeApiCall('/xml', null, null, true);
        $response['headers']->shouldHaveKey('Content-Type');
        $response['headers']['Content-Type']->shouldContain('application/xml');
        $response['body']->shouldBeString();
    }
}
