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
        'start_date' => 'getStartDate',
        'end_date' => 'getEndDate',
        'q' => 'getQuery',
        'page' => 'getPage',
        'max_results' => 'getMaxResults',
        'include_fields' => 'getIncludeFields',
        'exclude_fields' => 'getExcludeFields',
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
     */
    public function setStartDate($startDate)
    {
        if ($startDate !== null) {
            try {
                $this->startDate = $this->validateDate($startDate);
            } catch (InvalidArgumentException $e) {
                throw new InvalidArgumentException('Invalid value for start_date parameter.', $e->getCode(), $e);
            }
        } else {
            $this->startDate = null;
        }

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
     */
    public function setEndDate($endDate)
    {
        if ($endDate !== null) {
            try {
                $this->endDate = $this->validateDate($endDate);
            } catch (InvalidArgumentException $e) {
                throw new InvalidArgumentException('Invalid value for end_date parameter.', $e->getCode(), $e);
            }
        } else {
            $this->endDate = $endDate;
        }

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
     */
    public function setPage($page)
    {
        if ($page === null) {
            $page = self::DEFAULT_PAGE;
        }

        try {
            $this->page = $this->validateNumeric($page);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid value for page parameter.', $e->getCode(), $e);
        }

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
     */
    public function setMaxResults($maxResults)
    {
        if ($maxResults === null) {
            $maxResults = self::DEFAULT_MAX_RESULTS;
        }

        try {
            $this->maxResults = $this->validateNumeric($maxResults);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid value for maxResults parameter.', $e->getCode(), $e);
        }

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
     */
    public function setIncludeFields($includeFields)
    {
        if ($includeFields !== null) {
            try {
                $this->includeFields = $this->validateStringOrArray($includeFields);
            } catch (InvalidArgumentException $e) {
                throw new InvalidArgumentException('Invalid value for include_fields parameter.', $e->getCode(), $e);
            }
        } else {
            $this->includeFields = null;
        }

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
     */
    public function setExcludeFields($excludeFields)
    {
        if ($excludeFields !== null) {
            try {
                $this->excludeFields = $this->validateStringOrArray($excludeFields);
            } catch (InvalidArgumentException $e) {
                throw new InvalidArgumentException('Invalid value for exclude_fields parameter.', $e->getCode(), $e);
            }
        } else {
            $this->excludeFields = null;
        }

        return $this;
    }

    /**
     * Validate date parameter input and converts to DateTime.
     *
     * @param  string|DateTime $date When string format yyyy-mm-dd should be used
     *
     * @return DateTime
     */
    private function validateDate($date)
    {
        if (!is_string($date) && !($date instanceof \DateTime)) {
            throw new InvalidArgumentException('Parameter should be of type string or DateTime.');
        } elseif (is_string($date)) {
            if (!preg_match('/\d\d\d\d\-\d\d\-\d\d/', $date)) {
                throw new InvalidArgumentException('Parameter %s has invalid format, please use yyyy-mm-dd.');
            }

            $date = new DateTime($date);
        }

        return $date;
    }

    /**
     * Arguments of type string and array are valid. String will be split on ,
     * and converted to an array.
     *
     * @param  string|array $value
     *
     * @return array
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
     * Returns all properties either as an array or http query string.
     *
     * @param  boolean $buildHttpQuery Build query from array
     *
     * @return mixed[]|string
     */
    public function getAllParameters($buildHttpQuery = false)
    {
        $httpQuery = array();

        foreach ($this->propertyMapping as $uriParameter => $method) {

            $value = $this->$method();

            if ($value instanceof DateTIme) {
                $value = $value->format('Y-m-d');
            }

            $httpQuery[$uriParameter] = $value;
        }

        return ($buildHttpQuery) ? http_build_query($httpQuery) : $httpQuery;
    }
}
