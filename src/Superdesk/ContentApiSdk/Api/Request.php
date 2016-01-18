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

namespace Superdesk\ContentApiSdk\Api;

use Superdesk\ContentApiSdk\Api\Request\RequestInterface;
use Superdesk\ContentApiSdk\Api\Request\RequestParameters;
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
     * @var RequestParameters
     */
    protected $parameters;

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
     * @param RequestParameters $parameters Parameters
     * @param int $port Port
     * @param string $protocol Protocol
     */
    public function __construct(
        $hostname = null,
        $uri = null,
        RequestParameters $parameters = null,
        $port = null,
        $protocol = null
    ) {
        if (is_string($hostname) && !empty($hostname)) {
            $this->setHost($hostname);
        }
        if (is_string($uri) && !empty($uri)) {
            $this->setUri($uri);
        }
        if ($parameters !== null) {
            $this->setParameters($parameters);
        } else {
            $this->setParameters(new RequestParameters());
        }
        if (is_int($port)) {
            $this->setPort($port);
        }
        if (is_string($protocol) && !empty($protocol)) {
            $this->setProtocol($protocol);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * {@inheritdoc}
     */
    public function setProtocol($protocol)
    {
        if ($protocol !== 'http' && $protocol !== 'https') {
            throw new InvalidArgumentException('Property protocol can only have the values "http" or "https".');
        }

        $this->protocol = $protocol;

        return $this;
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
        if ($host === null || !is_string($host) || empty($host)) {
            throw new InvalidArgumentException('Property host should be of type string and cannot be empty.');
        }

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
        if (!is_int($port)) {
            throw new InvalidArgumentException('Property port should be of type integer.');
        }

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
    public function setParameters(RequestParameters $parameters)
    {
        $this->parameters = $parameters;

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
        return sprintf(
            '%s/%s?%s',
            $this->getBaseUrl(),
            trim($this->uri, '/ '),
            $this->parameters->getAllParameters(true)
        );
    }
}
