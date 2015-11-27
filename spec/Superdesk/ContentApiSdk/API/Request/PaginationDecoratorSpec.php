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

class PaginationDecoratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\API\Request\PaginationDecorator');
    }

    function let()
    {
        $this->beConstructedWith(new Request());
    }

    function it_should_set_pagination_parameters()
    {
        $this->addPagination(5, 10);
        $parameters = $this->getParameters();
        $parameters->shouldBeArray();
        $parameters->shouldHaveKey('page');
        $parameters->shouldHaveKeyWithValue('max_results', 10);
    }
}
