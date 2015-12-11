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

use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Exception\ResponseException;
use Superdesk\ContentApiSdk\Exception\RequestException;
use Superdesk\ContentApiSdk\Exception\InvalidArgumentException;
use Superdesk\ContentApiSdk\Exception\InvalidDataException;

/**
 * API Request object.
 */
class Request
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
    protected $parameters;

    /**
     * Validate parameters, unset invalid ones.
     *
     * @var boolean
     */
    protected $parameterValidation = true;

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
    public function __construct($hostname = null, $uri = null, array $parameters = array(), $port = null)
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
     * Get base url.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return sprintf('%s://%s:%s/%s', $this->protocol, $this->host, $this->port, ContentApiSdk::getVersionURL());
    }

    /**
     * Get full url.
     *
     * @return string
     */
    public function getFullUrl()
    {
        return sprintf('%s/%s?%s', $this->getBaseUrl(), trim($this->uri, '/ '), http_build_query($this->parameters));
    }

    /**
     * Set hostname.
     *
     * @param string $host
     *
     * @return self
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set port.
     *
     * @param int $port
     *
     * @return self
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set uri.
     *
     * @param string $uri
     *
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set query parameters.
     *
     * @param string[] $parameters
     *
     * @return self
     *
     * @throws RequestException If parameters data types are invalid
     */
    public function setParameters(array $parameters)
    {
        try {
            $this->parameters = ContentApiSdk::processParameters($parameters, $this->parameterValidation);
        } catch (InvalidArgumentException $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * Get query parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Enables parameter validation
     *
     * @return self
     */
    public function enableParameterValidation()
    {
        $this->parameterValidation = true;

        return $this;
    }

    /**
     * Disables parameter validation
     *
     * @return self
     */
    public function disableParameterValidation()
    {
        $this->parameterValidation = false;
    }

    /**
     * Gets the value of headers.
     *
     * @return string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets the value of headers.
     *
     * @param string[] $headers Value to set
     *
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Gets the value of options.
     *
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the value of options.
     *
     * @param mixed[] $options Value to set
     *
     * @return self
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Sets page number and max results per page parameters.
     *
     * @param int $offset Offset of rows
     * @param int $length Length of rows
     */
    public function setOffsetAndLength($offset, $length)
    {
        $this->parameters['page'] = ceil($offset / $length);
        $this->parameters['max_results'] = $length;
    }
}
