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

namespace Superdesk\ContentApiSdk\API;

use Superdesk\ContentApiSdk\API\Request\RequestInterface;
use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Exception\ResponseException;
use Superdesk\ContentApiSdk\Exception\RequestException;
use Superdesk\ContentApiSdk\Exception\InvalidArgumentException;
use Superdesk\ContentApiSdk\Exception\InvalidDataException;

/**
 * API Request object.
 */
class Request implements RequestInterface
{
    /**
     * Protocol for api request.
     *
     * @var string
     */
    protected $protocol = 'https';

    /**
     * Hostname for api request.
     *
     * @var string
     */
    protected $host;

    /**
     * Api port.
     *
     * @var int
     */
    protected $port = 80;

    /**
     * Request uri.
     *
     * @var string
     */
    protected $uri;

    /**
     * Parameters for request.
     *
     * @var mixed[]
     */
    protected $parameters = array();

    /**
     * Boolean for status of parameter handling. When set to true invalid
     * parameters will be cleaned out before being sent to the API.
     *
     * @var boolean
     */
    protected $cleanParameters = true;

    /**
     * A list of parameters the Content API accepts.
     *
     * For a list of accepted parameters find the variable allowed_params in
     * the following file:
     * https://github.com/superdesk/superdesk-content-api/blob/master/content_api/items/service.py
     *
     * @var string[]
     */
    protected $validParameters = array(
        'start_date', 'end_date', 'q', 'max_results', 'page',
        'include_fields', 'exclude_fields'
    );

    /**
     * Request headers.
     *
     * @var string[]
     */
    protected $headers = array();

    /**
     * Request options, usuably in clients, etc.
     *
     * @var mixed[]
     */
    protected $options = array();

    /**
     * Construct a request object.
     *
     * @param string $hostname Host name
     * @param string $uri Request uri
     * @param mixed[] $parameters Parameters
     * @param int $port Port
     */
    public function __construct($hostname = null, $uri = null, array $parameters = null, $port = null)
    {
        if (is_string($hostname) && !empty($hostname)) {
            $this->setHost($hostname);
        }
        if (is_string($uri) && !empty($uri)) {
            $this->setUri($uri);
        }
        if (!empty($parameters)) {
            $this->setParameters($parameters);
        }
        if (is_int($port)) {
            $this->setPort($port);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        try {
            $this->parameters = $this->processParameters($parameters, $this->cleanParameters);
        } catch (InvalidArgumentException $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanParameters()
    {
        return $this->cleanParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function enableParameterCleaning()
    {
        $this->cleanParameters = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disableParameterCleaning()
    {
        $this->cleanParameters = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUrl()
    {
        return sprintf('%s://%s:%s', $this->protocol, $this->host, $this->port);
    }

    /**
     * {@inheritdoc}
     */
    public function getFullUrl()
    {
        return sprintf('%s/%s?%s', $this->getBaseUrl(), trim($this->uri, '/ '), http_build_query($this->parameters));
    }

    /**
     * Type conversion method for parameters accepted by the API. Will
     * by default automatically unset invalid parameters, this behaviour can be
     * overridden with the second argument.
     *
     * @param  mixed[] $requestParameters Array of parameters
     * @param  boolean $unsetInvalidParameters Boolean to clean out invalid
     *                                         parameters
     *
     * @return mixed[] Returns an array of parameters with API safe types
     * @throws InvalidArgumentException When an invalid type is set for a
     *                                  valid parameter
     */
    public function processParameters(
        array $requestParameters,
        $unsetInvalidParameters = true
    ) {
        $processedParameters = $requestParameters;

        foreach ($requestParameters as $name => $value) {

            if ($unsetInvalidParameters && !in_array($name, $this->validParameters)) {
                unset($processedParameters[$name]);
                continue;
            }

            switch ($name) {
                case 'start_date':
                case 'end_date':
                        if (!is_string($value) && !($value instanceof \DateTime)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type string or DateTime.', $name));
                        } elseif ($value instanceof \DateTime) {
                            $value = $value->format('Y-m-d');
                        } elseif (!preg_match('/\d\d\d\d\-\d\d\-\d\d/', $value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s has invalid format, please use dddd-dd-dd.', $name));
                        }
                    break;
                case 'q':
                        if (!is_string($value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type string.', $name));
                        }
                    break;
                case 'include_fields':
                case 'exclude_fields':
                        if (!is_string($value) && !is_array($value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type string or array.', $name));
                        } elseif (is_array($value)) {
                            $value = implode(',', $value);
                        }
                    break;
                case 'page':
                case 'max_results':
                        if (!is_int($value) && !ctype_digit($value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type integer.', $name));
                        } elseif (!is_int($value)) {
                            $value = (int) $value;
                        }
                    break;
            }

            $processedParameters[$name] = $value;
        }

        return $processedParameters;
    }
}
