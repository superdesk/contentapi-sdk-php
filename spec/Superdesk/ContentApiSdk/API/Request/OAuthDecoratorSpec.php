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

namespace spec\Superdesk\ContentApiSdk\API\Request;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\API\Request;

class OAuthDecoratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\API\Request\OAuthDecorator');
    }

    function let()
    {
        $this->beConstructedWith(new Request());
    }

    function it_should_set_and_get_an_access_token()
    {
        $access_token = 'some random access token';
        $this->setAccessToken($access_token);
        $this->getAccessToken()->shouldBe($access_token);
    }

    function it_should_set_authentication_header()
    {
        $access_token = 'some random access token';
        $this->setAccessToken($access_token);
        $this->addAuthentication();
        $headers = $this->getHeaders();
        $headers->shouldBeArray();
        $headers->shouldHaveKeyWithValue('Authorization', sprintf('%s %s', 'OAuth2', $access_token));
    }

    function it_should_throw_an_exception_when_access_token_is_not_set()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\RequestException')->duringAddAuthentication();
    }
}
