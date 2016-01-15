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

namespace Superdesk\ContentApiSdk\API\Request;

use Superdesk\ContentApiSdk\Exception\InvalidArgumentException;
use DateTime;

/**
 * Request parameter class.
 */
class RequestParameters
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_MAX_RESULTS = 25;

    /**
     * Start date.
     *
     * @var DateTime|null
     */
    protected $startDate;

    /**
     * End date.
     *
     * @var DateTime|null
     */
    protected $endDate;

    /**
     * Query for text search.
     *
     * @var string|null
     */
    protected $query;

    /**
     * Page.
     *
     * @var int
     */
    protected $page = self::DEFAULT_PAGE;

    /**
     * Max results per page.
     *
     * @var int
     */
    protected $maxResults = self::DEFAULT_MAX_RESULTS;

    /**
     * Include fields list. These fields will be set in your packages or items.
     *
     * @var array|null
     */
    protected $includeFields;

    /**
     * Exclude fields lists. These fields won't be set in your packages or items.
     *
     * @var array|null
     */
    protected $excludeFields;

    /**
     * Mapping array, which links correct parameter name for API with proper
     * method.
     *
     * @var string[]
     */
    protected $propertyMapping = array(
        'start_date' => 'StartDate',
        'end_date' => 'EndDate',
        'q' => 'Query',
        'page' => 'Page',
        'max_results' => 'MaxResults',
        'include_fields' => 'IncludeFields',
        'exclude_fields' => 'ExcludeFields',
    );

    /**
     * Returns start date.
     *
     * @return DateTime|null
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Sets start date. Will accept a string formatted 'yyyy-mm-dd' or DateTime
     * object.
     *
     * @param string|DateTime|null $startDate
     *
     * @return self
     */
    public function setStartDate($startDate)
    {
        $this->setProperty('startDate', $startDate, 'validateDate');

        return $this;
    }

    /**
     * Returns end date.
     *
     * @return DateTime|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets end date. Will accept string formatted 'yyyy-mm-dd' or DateTIme
     * object.
     *
     * @param string|DateTime|null $endDate
     *
     * @return self
     */
    public function setEndDate($endDate)
    {
        $this->setProperty('endDate', $endDate, 'validateDate');

        return $this;
    }

    /**
     * Returns text query.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Sets query parameters.
     *
     * @param string|null $query
     *
     * @return self
     */
    public function setQuery($query)
    {
        if (!is_string($query) && $query !== null) {
            throw new InvalidArgumentException('Parameter query should be of type string or null.');
        }

        $this->query = $query;

        return $this;
    }

    /**
     * Returns page number.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Sets page number. If null is supplied, resets to class default.
     *
     * @param int|null $page
     *
     * @return self
     */
    public function setPage($page)
    {
        if ($page === null) {
            $page = self::DEFAULT_PAGE;
        }

        $this->setProperty('page', $page, 'validateNumeric');

        return $this;
    }

    /**
     * Returns maximum results per page.
     *
     * @return int
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * Sets maximum results per page. If null is supplied, resets to class
     * default.
     *
     * @param int|nul $maxResults
     *
     * @return self
     */
    public function setMaxResults($maxResults)
    {
        if ($maxResults === null) {
            $maxResults = self::DEFAULT_MAX_RESULTS;
        }

        $this->setProperty('maxResults', $maxResults, 'validateNumeric');

        return $this;
    }

    /**
     * Returns include fields.
     *
     * @return array
     */
    public function getIncludeFields()
    {
        return $this->includeFields;
    }

    /**
     * Sets include fields.
     *
     * @param array|null $includeFields
     *
     * @return self
     */
    public function setIncludeFields($includeFields)
    {
        $this->setProperty('includeFields', $includeFields, 'validateStringOrArray');

        return $this;
    }

    /**
     * Gets exclude fields.
     *
     * @return array
     */
    public function getExcludeFields()
    {
        return $this->excludeFields;
    }

    /**
     * Sets exclude fields
     *
     * @param array|null
     *
     * @return self
     */
    public function setExcludeFields($excludeFields)
    {
        $this->setProperty('excludeFields', $excludeFields, 'validateStringOrArray');

        return $this;
    }

    /**
     * Validate date parameter input and converts to DateTime.
     *
     * @param  string|DateTime $date When string format yyyy-mm-dd should be used
     *
     * @return DateTime
     * @throws InvalidArgumentException
     */
    private function validateDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date;
        }

        if (!is_string($date)) {
            throw new InvalidArgumentException('Parameter should be of type string or DateTime.');
        } elseif (!preg_match('/\d\d\d\d\-\d\d\-\d\d/', $date)) {
            throw new InvalidArgumentException('Parameter %s has invalid format, please use yyyy-mm-dd.');
        }

        return new DateTime($date);
    }

    /**
     * Arguments of type string and array are valid. String will be split on ,
     * and converted to an array.
     *
     * @param  string|array $value
     *
     * @return array
     * @throws InvalidArgumentException
     */
    private function validateStringOrArray($value)
    {
        if (!is_string($value) && !is_array($value)) {
            throw new InvalidArgumentException('Parameter should be of type string or array.');
        } elseif (is_string($value)) {
            $value = array_map('trim', explode(',', $value));
        }

        return $value;
    }

    /**
     * Validates if value is numeric.
     *
     * @param  string|int $value
     *
     * @return int
     * @throws InvalidArgumentException
     */
    private function validateNumeric($value)
    {
        if (!is_int($value) && !ctype_digit($value)) {
            throw new InvalidArgumentException('Parameter should be of type integer.');
        } elseif (!is_int($value)) {
            $value = (int) $value;
        }

        return $value;
    }

    /**
     * Helper function for setting a property. Sets a proprety to a value, by
     * pulling it through a validator function. If null is specified as value
     * then property will be set to null as well..
     *
     * @param string $property Property name
     * @param mixed $value Value of the property
     * @param string $validator Name of the validator method
     *
     * @throws InvalidArgumentException
     */
    private function setProperty($property, $value, $validator)
    {
        if ($value !== null) {
            try {
                $this->$property = $this->$validator($value);
            } catch (InvalidArgumentException $e) {
                throw new InvalidArgumentException(sprintf('Invalid value for %s parameter.', $property), $e->getCode(), $e);
            }
        } else {
            $this->$property = null;
        }
    }

    /**
     * Internal method to get the correct method when only a query parameter
     * key is available.
     *
     * @param  string $queryKey [description]
     * @param  string $action This will be prepended to the method name,
     *                        defaults to 'get'.
     *
     * @return string|null Returns the method or null when the key doesn't exist.
     */
    private function getMethodForQueryKey($queryKey, $action = 'get')
    {
        if (isset($this->propertyMapping[$queryKey])) {
            return sprintf('%s%s', $action, $this->propertyMapping[$queryKey]);
        }

        return null;
    }

    /**
     * Returns all properties either as an array or http query string.
     *
     * @param  boolean $buildHttpQuery Build query from array
     *
     * @return mixed[]|string
     */
    public function getAllParameters($buildHttpQuery = false)
    {
        $httpQuery = array();

        foreach ($this->propertyMapping as $uriParameter => $methodPart) {

            $method = $this->getMethodForQueryKey($uriParameter);
            $value = $this->$method();

            if ($value instanceof DateTIme) {
                $value = $value->format('Y-m-d');
            }

            $httpQuery[$uriParameter] = $value;
        }

        return ($buildHttpQuery) ? http_build_query($httpQuery) : $httpQuery;
    }

    /**
     * Uses an array of query parameters and sets the values for the current
     * RequestParameters object. Does an internal check whether the query key
     * is a valid API query key.
     *
     * @param  array $requestParameters
     *
     * @return self Returns the current object with the properties set
     */
    public function setQueryParameterArray(array $requestParameters = array())
    {
        foreach ($requestParameters as $key => $value) {
            if (isset($this->propertyMapping[$key])) {
                $method = $this->getMethodForQueryKey($key, 'set');
                $this->$method($value);
            }
        }

        return $this;
    }
}
