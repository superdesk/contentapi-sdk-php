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

use Superdesk\ContentApiSdk\API\Request\RequestParameters;

/**
 * Base decorator class for API Requests.
 */
class RequestDecorator implements RequestInterface
{
    /**
     * @var RequestInterface
     */
    protected $decoratedRequest;

    /**
     * Intialize object.
     *
     * @param RequestInterface $requestInterface
     */
    public function __construct(RequestInterface $requestInterface)
    {
        $this->decoratedRequest = $requestInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocol()
    {
        return $this->decoratedRequest->getProtocol();
    }

    /**
     * {@inheritdoc}
     */
    public function setProtocol($protocol)
    {
        $this->decoratedRequest->setProtocol($protocol);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->decoratedRequest->getHost();
    }

    /**
     * {@inheritdoc}
     */
    public function setHost($host)
    {
        $this->decoratedRequest->setHost($host);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->decoratedRequest->getPort();
    }

    /**
     * {@inheritdoc}
     */
    public function setPort($port)
    {
        $this->decoratedRequest->setPort($port);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->decoratedRequest->getUri();
    }

    /**
     * {@inheritdoc}
     */
    public function setUri($uri)
    {
        $this->decoratedRequest->setUri($uri);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->decoratedRequest->getParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(RequestParameters $parameters)
    {
        $this->decoratedRequest->setParameters($parameters);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->decoratedRequest->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaders(array $headers)
    {
        $this->decoratedRequest->setHeaders($headers);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->decoratedRequest->getOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->decoratedRequest->setOptions($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUrl()
    {
        return $this->decoratedRequest->getBaseUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getFullUrl()
    {
        return $this->decoratedRequest->getFullUrl();
    }
}
