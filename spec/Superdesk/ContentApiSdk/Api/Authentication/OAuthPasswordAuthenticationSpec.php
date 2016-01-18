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

namespace spec\Superdesk\ContentApiSdk\Api\Authentication;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\Api\Request\RequestInterface;
use Superdesk\ContentApiSdk\Api\Request\PaginationDecorator;
use Superdesk\ContentApiSdk\Api\Response;
use Superdesk\ContentApiSdk\Data\Item;
use Superdesk\ContentApiSdk\Exception\AuthenticationException;

class OAuthPasswordAuthenticationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Api\Authentication\OAuthPasswordAuthentication');
    }

    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    function it_should_be_able_to_set_a_username()
    {
        $username = 'some username';
        $this->setUsername($username)->getUsername()->shouldBe($username);
    }

    function it_should_be_able_to_set_a_password()
    {
        $password = 'some password';
        $this->setPassword($password)->getPassword()->shouldBe($password);
    }

    function it_should_be_to_get_new_tokens($client)
    {
        $client->makeCall(
            Argument::type('string'),
            Argument::type('array'),
            Argument::type('array'),
            'POST',
            Argument::type('array')
        )->willReturn(array(
            'headers' => array(),
            'status' => 200,
            'body' => '{"scope": "content_api", "refresh_token": "some_refresh_token", "token_type": "Bearer", "access_token": "some_access_token"}'
        ));
        $this->getAuthenticationTokens();
        $this->getAccessToken()->shouldBe('some_access_token');
    }

    function it_should_be_to_get_refresh_tokens($client)
    {
        $client->makeCall(
            Argument::type('string'),
            Argument::type('array'),
            Argument::type('array'),
            'POST',
            Argument::type('array')
        )->willReturn(array(
            'headers' => array(),
            'status' => 200,
            'body' => '{"scope": "content_api", "refresh_token": "some_refresh_token", "token_type": "Bearer", "access_token": "some_access_token"}'
        ));
        $this->refreshAccessToken();
        $this->getAccessToken()->shouldBe('some_access_token');
    }

    function it_should_throw_an_exception_on_an_non_successful_status_code($client)
    {
        $client->makeCall(
            Argument::type('string'),
            Argument::type('array'),
            Argument::type('array'),
            'POST',
            Argument::type('array')
        )->willReturn(array(
            'headers' => array(),
            'status' => 403,
            'body' => '{"scope": "content_api", "refresh_token": "some_refresh_token", "token_type": "Bearer", "access_token": "some_access_token"}'
        ));
        $this->shouldThrow('Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringGetAuthenticationTokens();
        $this->shouldThrow('Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringRefreshAccessToken();

        $client->makeCall(
            Argument::type('string'),
            Argument::type('array'),
            Argument::type('array'),
            'POST',
            Argument::type('array')
        )->willReturn(array(
            'headers' => array(),
            'status' => 500,
            'body' => '{"scope": "content_api", "refresh_token": "some_refresh_token", "token_type": "Bearer", "access_token": "some_access_token"}'
        ));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringGetAuthenticationTokens();
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringRefreshAccessToken();
    }

    function it_should_throw_an_exception_on_a_json_response_body_with_unkown_format($client)
    {
        $client->makeCall(
            Argument::type('string'),
            Argument::type('array'),
            Argument::type('array'),
            'POST',
            Argument::type('array')
        )->willReturn(array(
            'headers' => array(),
            'status' => 200,
            'body' => '{"some_key": "some data"}'
        ));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringGetAuthenticationTokens();
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringRefreshAccessToken();
    }

    function it_should_throw_an_exception_on_a_non_json_response_body($client)
    {
        $client->makeCall(
            Argument::type('string'),
            Argument::type('array'),
            Argument::type('array'),
            'POST',
            Argument::type('array')
        )->willReturn(array(
            'headers' => array(),
            'status' => 200,
            'body' => null
        ));
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringGetAuthenticationTokens();
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringRefreshAccessToken();
    }
}
