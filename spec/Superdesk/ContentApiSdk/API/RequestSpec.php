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

        $this->enableParameterCleaning();
        $this->setParameters($parameters)->getParameters()->shouldBe(array('start_date' => '1970-01-01'));

        $this->disableParameterCleaning();
        $this->setParameters($parameters)->getParameters()->shouldBe($parameters);
    }

    function its_method_set_parameters_should_throw_an_exception()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\RequestException')->duringSetParameters(array('start_date' => array()), true);
    }

    function its_method_process_parameters_should_properly_process()
    {
        $parameters = array('start_date' => '2015-01-01');
        $this->processParameters($parameters)->shouldBe($parameters);
        $parameters = array('start_date' => new \DateTime());
        $this->processParameters($parameters)
            ->shouldBe(array(
                'start_date' => $parameters['start_date']->format('Y-m-d')
            ));

        $parameters = array('end_date' => '2015-01-01');
        $this->processParameters($parameters)->shouldBe($parameters);
        $parameters = array('end_date' => new \DateTime());
        $this->processParameters($parameters)
            ->shouldBe(array(
                'end_date' => $parameters['end_date']->format('Y-m-d')
            ));

        $parameters = array('q' => 'test');
        $this->processParameters($parameters)->shouldBe($parameters);

        $parameters = array('include_fields' => 'headline,body_text');
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'include_fields' => array('headline', 'body_text')
            ))
            ->shouldBe(array(
                'include_fields' => 'headline,body_text'
            ));

        $parameters = array('exclude_fields' => 'headline,body_text');
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'exclude_fields' => array('headline', 'body_text')
            ))
            ->shouldBe(array(
                'exclude_fields' => 'headline,body_text'
            ));

        $parameters = array('page' => 2);
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'page' => '2'
            ))
            ->shouldBe(array(
                'page' => 2
            ));

        $parameters = array('max_results' => 2);
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'max_results' => '2'
            ))
            ->shouldBe(array(
                'max_results' => 2
            ));
    }

    function its_method_process_parameters_should_throw_exceptions()
    {
        $parameters = array(
            'start_date' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'start_date' => '01-01-1970',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'end_date' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'end_date' => '01-01-1970',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'q' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'include_fields' => 1,
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'exclude_fields' => 1,
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'page' => 'page',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'max_results' => 'max_results',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);
    }

    function its_method_process_parameters_should_validate()
    {
        $invalidParameters = array(
            'some_name' => 'some value',
            'another_name' => 'another value',
            'max_results' => '5'
        );
        $processedParameters = $this->processParameters($invalidParameters, true);
        $processedParameters->shouldBe(array('max_results' => 5));
    }
}
