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

namespace spec\Superdesk\ContentApiSdk\API\Authentication;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\API\Request\RequestInterface;
use Superdesk\ContentApiSdk\API\Request\PaginationDecorator;
use Superdesk\ContentApiSdk\API\Response;
use Superdesk\ContentApiSdk\Data\Item;

class OAuthPasswordAuthenticationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\API\Authentication\OAuthPasswordAuthentication');
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

    function it_should_throw_an_exception_on_an_invalid_response($client)
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
        $this->shouldThrow('Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringGetAuthenticationTokens();

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
        $this->shouldThrow('Superdesk\ContentApiSdk\Exception\AuthenticationException')->duringGetAuthenticationTokens();
    }
}
