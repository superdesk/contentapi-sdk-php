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
use Superdesk\ContentApiSdk\API\Request\RequestParameters;
use DateTime;

class RequestParametersSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\API\Request\RequestParameters');
    }

    function it_should_set_and_get_a_start_date()
    {
        $dateString = '1970-01-01';
        $dateObj = $this->setStartDate($dateString)->getStartDate();
        $dateObj->shouldHaveType('DateTime');
        $dateObj->format('Y-m-d')->shouldReturn($dateString);

        $dateObj =  new DateTime('1970-01-01');
        $dateObj = $this->setStartDate($dateObj)->getStartDate();
        $dateObj->shouldHaveType('DateTime');
        $dateObj->format('Y-m-d')->shouldReturn($dateObj->format('Y-m-d'));

        $this->setStartDate(null)->getStartDate()->shouldReturn(null);
    }

    function it_should_set_and_get_an_end_date()
    {
        $dateString = '1970-01-01';
        $dateObj = $this->setEndDate($dateString)->getEndDate();
        $dateObj->shouldHaveType('DateTime');
        $dateObj->format('Y-m-d')->shouldReturn($dateString);

        $dateObj =  new DateTime('1970-01-01');
        $dateObj = $this->setEndDate($dateObj)->getEndDate();
        $dateObj->shouldHaveType('DateTime');
        $dateObj->format('Y-m-d')->shouldReturn($dateObj->format('Y-m-d'));

        $this->setEndDate(null)->getEndDate()->shouldReturn(null);
    }

    function it_should_set_and_get_a_query()
    {
        $query = 'blaat';
        $this->setQuery($query)->getQuery()->shouldReturn($query);

        $query = null;
        $this->setQuery($query)->getQuery()->shouldReturn($query);
    }

    function it_should_set_and_get_a_page()
    {
        $page = 99;
        $this->setPage($page)->getPage()->shouldReturn($page);
        $this->setPage('99')->getPage()->shouldReturn($page);
    }

    function it_should_reset_page_to_default_on_null()
    {
        $this->setPage(5);
        $this->setPage(null)->getPage()->shouldReturn(RequestParameters::DEFAULT_PAGE);
    }

    function it_should_set_and_get_max_results()
    {
        $maxResults = 100;
        $this->setMaxResults($maxResults)->getMaxResults()->shouldReturn($maxResults);
        $this->setMaxResults('100')->getMaxResults()->shouldReturn($maxResults);
    }

    function it_should_reset_max_results_to_default_on_null()
    {
        $this->setMaxResults(100);
        $this->setMaxResults(null)->getMaxResults()->shouldReturn(RequestParameters::DEFAULT_MAX_RESULTS);
    }

    function it_should_set_and_get_include_fields()
    {
        $includeFieldsArray = array('headline', 'pubstatus', 'language');
        $includeFieldsString = 'headline, pubstatus, language';
        $includeFieldsInputArray = $this->setIncludeFields($includeFieldsArray)->getIncludeFields();
        $includeFieldsInputString = $this->setIncludeFields($includeFieldsString)->getIncludeFields();

        $includeFieldsInputArray->shouldBeArray();
        $includeFieldsInputString->shouldBeArray();
        foreach ($includeFieldsArray as $field) {
            $includeFieldsInputArray->shouldContain($field);
            $includeFieldsInputString->shouldContain($field);
        }

        $this->setIncludeFields(null)->getIncludeFields()->shouldReturn(null);
    }

    function it_should_set_and_get_exclude_fields()
    {
        $excludeFieldsArray = array('headline', 'pubstatus', 'language');
        $excludeFieldsString = 'headline, pubstatus, language';
        $excludeFieldsInputArray = $this->setExcludeFields($excludeFieldsArray)->getExcludeFields();
        $excludeFieldsInputString = $this->setExcludeFields($excludeFieldsString)->getExcludeFields();

        $excludeFieldsInputArray->shouldBeArray();
        $excludeFieldsInputString->shouldBeArray();
        foreach ($excludeFieldsArray as $field) {
            $excludeFieldsInputArray->shouldContain($field);
            $excludeFieldsInputString->shouldContain($field);
        }

        $this->setExcludeFields(null)->getExcludeFields()->shouldReturn(null);
    }

    function it_should_return_all_parameters()
    {
        $this->setPage(10);
        $this->setMaxResults(1);
        $this->setQuery('text');

        $arrayReturn = $this->getAllParameters(false);
        $arrayReturn->shouldBeArray();
        $arrayReturn->shouldHaveKeyWithValue('page', 10);
        $arrayReturn->shouldHaveKeyWithValue('max_results', 1);
        $arrayReturn->shouldHaveKeyWithValue('q', 'text');
        $this->getAllParameters(true)->shouldBe('q=text&page=10&max_results=1');
    }

    function it_should_throw_an_exception_on_invalid_start_date()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetStartDate('some invalid date');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetStartDate(array());
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetStartDate(123456789);
    }

    function it_should_throw_an_exception_on_invalid_end_date()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetEndDate('some invalid date');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetEndDate(array());
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetEndDate(123456789);
    }

    function it_should_throw_an_exception_on_invalid_query()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetQuery(array());
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetQuery(123456789);
    }

    function it_should_throw_an_exception_on_invalid_page()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPage(array());
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPage('some page');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetPage('seven');
    }

    function it_should_throw_an_exception_on_invalid_max_results()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetMaxResults(array());
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetMaxResults('some page');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetMaxResults('seven');
    }

    function it_should_throw_an_exception_on_invalid_include_fields()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetIncludeFields(123456789);
    }

    function it_should_throw_an_exception_on_invalid_exclude_fields()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringSetExcludeFields(123456789);
    }

    function its_method_set_query_parameter_array_should_set_the_properties_from_an_array()
    {
        $queryParameterArray = array(
            'q' => 'some_text',
            'start_date' => '2015-01-01'
        );
        $this->setQueryParameterArray($queryParameterArray);
        $this->getQuery()->shouldReturn($queryParameterArray['q']);
        $this->getStartDate()->format('Y-m-d')->shouldReturn($queryParameterArray['start_date']);
    }

    function its_method_set_query_parameter_array_should_ignore_invalid_keys_and_not_throw_an_exception()
    {
        $queryParameterArray = array(
            'some_invalid_key' => 'some_value'
        );
        $this->setQueryParameterArray($queryParameterArray);
    }
}
