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
use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;
use Superdesk\ContentApiSdk\Client\FileGetContentsClient;

class FileGetContentsClientHelperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Client\FileGetContentsClientHelper');
    }

    function it_should_make_a_call_to_a_remote_server($request)
    {
        $this->sendRequest('http://httpbin.org/status/200', array())->shouldHaveKey('headers');
        $this->sendRequest('http://httpbin.org/status/200', array())->shouldHaveKeyWithValue('body', '');
    }

    function it_should_throw_an_exception_when_an_error_occurs()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringSendRequest('http://httpbin.org/status/404', array());
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringSendRequest('http://httpbin.org/status/500', array());
    }
}
