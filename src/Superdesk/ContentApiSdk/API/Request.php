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
            // TODO: Maybe we should move the parameter validation to this class?
            $this->parameters = ContentApiSdk::processParameters($parameters, $this->parameterValidation);
        } catch (InvalidArgumentException $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterValidation()
    {
        return $this->parameterValidation;
    }

    /**
     * {@inheritdoc}
     */
    public function enableParameterValidation()
    {
        $this->parameterValidation = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disableParameterValidation()
    {
        $this->parameterValidation = false;
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
}
