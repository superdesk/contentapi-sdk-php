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

namespace spec\Superdesk\ContentApiSdk\API;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\ContentApiSdk;

class RequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\API\Request');
    }

    function let()
    {
        $this->beConstructedWith('example.com', '/request/uri', array('start_date' => '1970-01-01'), 80);
    }

    function it_should_return_a_base_url()
    {
        $this->getBaseUrl()->shouldBe('https://example.com:80');
    }

    function it_should_return_a_full_url()
    {
        $this->getFullUrl()->shouldBe('https://example.com:80/request/uri?start_date=1970-01-01');
    }

    function it_should_validate_parameters_on_request()
    {
        $parameters = array('some_key' => 'some value', 'start_date' => '1970-01-01');

        $this->enableParameterValidation();
        $this->setParameters($parameters)->getParameters()->shouldBe(array('start_date' => '1970-01-01'));

        $this->disableParameterValidation();
        $this->setParameters($parameters)->getParameters()->shouldBe($parameters);
    }

    function its_method_set_parameters_should_throw_an_exception()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\RequestException')->duringSetParameters(array('start_date' => array()), true);
    }
}
