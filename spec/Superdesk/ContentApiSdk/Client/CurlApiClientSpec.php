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
use Prophecy\Argument;
use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;
use Superdesk\ContentApiSdk\API\Authentication\AuthenticationInterface;
use Superdesk\ContentApiSdk\API\Authentication\OAuthPasswordAuthentication;
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\Exception\AccessDeniedException;

class CurlApiClientSpec extends ObjectBehavior
{
    // TODO: Check if we can make OAuthPasswordAUthentication more generic, e.g. user interface or parent class
    function let(
        ClientInterface $client,
        OAuthPasswordAuthentication $authentication,
        Request $request
    ) {
        $this->beConstructedWith($client, $authentication);
        $request->getOptions()->willReturn(array());

        $baseUrl = 'http://httpbin.org/';
        $fullUrl = sprintf('%s/%s', $baseUrl, 'status/200');
        $headers = array('Accept' => 'application/json');
        $newHeaders = array('Accept' => 'application/json', 'Authorization' => 'OAuth2 some_access_token');
        $request->getBaseUrl()->willReturn($baseUrl);
        $request->getFullUrl()->willReturn($fullUrl);
        $request->getHeaders()->willReturn($headers);
        $request->setHeaders($newHeaders)->willReturn($request);
        $authentication->setBaseUrl($baseUrl)->willReturn($authentication);
        $authentication->getAccessToken()->willReturn('some_access_token');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\Superdesk\ContentApiSdk\Client\CurlApiClient');
        $this->shouldImplement('\Superdesk\ContentApiSdk\Client\ApiClientInterface');
    }

    function it_should_return_a_response($client, $authentication, $request)
    {
        $authentication->getAccessToken()->shouldBeCalled();
        $client->makeCall(
            $request->getWrappedObject()->getFullUrl(),
            $request->getWrappedObject()->getHeaders(),
            array()
        )->shouldBeCalled()->willReturn(array('headers' => array(), 'status' => 200, 'body' => '{"pubstatus": "usable", "_links": {"parent": {"href": "/", "title": "home"}, "collection": {"href": "items", "title": "items"}, "self": {"href": "items/tag:example.com,0001:newsml_BRE9A607", "title": "Item"}}, "body_text": "Andromeda and Milky Way will collide in about 2 billion years", "type": "text", "language": "en", "versioncreated": "2015-03-09T16:32:23+0000", "uri": "http://api.master.dev.superdesk.org/items/tag%3Aexample.com%2C0001%3Anewsml_BRE9A607", "version": "2", "headline": "Andromeda on a collision course"}'));
        $this->makeApiCall($request)->shouldHaveType('\Superdesk\ContentApiSdk\API\Response');
    }

    function it_should_throw_an_exception_when_the_response_is_invalid($client, $authentication, $request)
    {
        $authentication->getAccessToken()->shouldBeCalled();
        $client->makeCall(
            $request->getWrappedObject()->getFullUrl(),
            $request->getWrappedObject()->getHeaders(),
            array()
        )->shouldBeCalled()->willReturn(array('headers' => array(), 'status' => 200, 'body' => 'some invalid response body'));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringMakeApiCall($request);
    }

    function it_should_throw_an_exception_after_failing_several_times_to_get_an_access_token($authentication, $request)
    {
        $authentication->getAccessToken()->shouldBeCalled()->willReturn(null);
        $authentication->getAuthenticationTokens()->shouldBeCalled()->willReturn(true);
        $this->shouldThrow(new AccessDeniedException('Authentication retry limit reached.'))->duringMakeApiCall($request);
    }

    function it_should_refresh_a_token_when_access_token_is_set_but_a_401_is_returned($client, $authentication, $request)
    {
        $authentication->getAccessToken()->shouldBeCalled()->willReturn('some_access_token');
        $authentication->refreshAccessToken()->shouldBeCalled()->willReturn(true);
        $client->makeCall(
            $request->getWrappedObject()->getFullUrl(),
            $request->getWrappedObject()->getHeaders(),
            array()
        )->shouldBeCalled()->willReturn(array('status' => 401));

        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\AccessDeniedException')->duringMakeApiCall($request);
    }

    function it_should_throw_an_when_an_non_200_status_is_returned($client, $authentication, $request)
    {
        $authentication->getAccessToken()->shouldBeCalled();

        $client->makeCall(
            $request->getWrappedObject()->getFullUrl(),
            $request->getWrappedObject()->getHeaders(),
            array()
        )->willReturn(array('status' => 500));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringMakeApiCall($request);

        $client->makeCall(
            $request->getWrappedObject()->getFullUrl(),
            $request->getWrappedObject()->getHeaders(),
            array()
        )->willReturn(array('status' => 403));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringMakeApiCall($request);
    }
}
