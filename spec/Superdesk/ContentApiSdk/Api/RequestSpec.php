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

namespace spec\Superdesk\ContentApiSdk\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Api\Request\RequestParameters;

class RequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Api\Request');
    }

    function let(RequestParameters $parameters)
    {
        $this->beConstructedWith(
            'example.com',
            '/request/uri',
            $parameters,
            80
        );
        $parameters->getAllParameters(true)->willReturn('start_date=1970-01-01');
    }

    function it_should_set_and_get_a_protocol()
    {
        $protocol = 'http';
        $this->setProtocol($protocol);
        $this->getProtocol()->shouldReturn($protocol);

        $protocol = 'https';
        $this->setProtocol($protocol);
        $this->getProtocol()->shouldReturn($protocol);
    }

    function its_method_set_protocol_should_throw_an_exception_on_invalid_protocol_value()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetProtocol('some protocol');
    }

    function it_should_set_and_get_a_host()
    {
        $host = 'some host';
        $this->setHost($host);
        $this->getHost()->shouldReturn($host);
    }

    function its_method_set_host_should_throw_an_exception_on_invalid_host_value()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetHost(Argument::is(''));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetHost(Argument::is(null));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetHost(Argument::type('array'));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetHost(Argument::type('int'));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPort(Argument::type('object'));
    }

    function it_should_set_and_get_a_port()
    {
        $port = 80;
        $this->setPort($port);
        $this->getPort()->shouldReturn($port);
    }

    function its_method_set_port_should_throw_an_exception_on_invalid_port_value()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPort(Argument::is(null));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPort(Argument::type('string'));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPort(Argument::type('array'));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPort(Argument::type('object'));
    }

    function it_should_set_and_get_a_uri()
    {
        $uri = 'request/uri';
        $this->setUri($uri);
        $this->getUri()->shouldReturn($uri);
    }

    function it_should_set_and_get_headers()
    {
        $headers = array('some key' => 'some value');
        $this->setHeaders($headers);
        $this->getHeaders()->shouldReturn($headers);
    }

    function it_should_set_and_get_options()
    {
        $options = array('some key' => 'some value');
        $this->setOptions($options);
        $this->getOptions()->shouldReturn($options);
    }

    function it_should_return_a_base_url()
    {
        $this->getBaseUrl()->shouldBe('https://example.com:80');
    }

    function it_should_return_a_full_url()
    {
        $this->getFullUrl()->shouldBe('https://example.com:80/request/uri?start_date=1970-01-01');
    }
}
