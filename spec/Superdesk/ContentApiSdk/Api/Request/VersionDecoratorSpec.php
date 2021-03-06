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

namespace spec\Superdesk\ContentApiSdk\Api\Request;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\Api\Request;

class VersionDecoratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Api\Request\VersionDecorator');
    }

    function let()
    {
        $this->beConstructedWith(new Request());
    }

    function it_should_add_a_version_to_the_uri()
    {
        $this->setUri('request/uri');
        $this->addVersion();
        $this->getUri()->shouldReturn('v1/request/uri');

        $this->setUri('/request/uri');
        $this->addVersion();
        $this->getUri()->shouldReturn('v1/request/uri');
    }
}
