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
     * @param RequestInterface $wrappable
     */
    public function __construct(RequestInterface $requestInterface)
    {
        $this->decoratedRequest = $requestInterface;
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
    public function setParameters(array $parameters)
    {
        $this->decoratedRequest->setParameters($parameters);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterValidation()
    {
        return $this->decoratedRequest->getParameterValidation();
    }

    /**
     * {@inheritdoc}
     */
    public function enableParameterValidation()
    {
        $this->decoratedRequest->enableParameterValidation();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disableParameterValidation()
    {
        $this->decoratedRequest->disableParameterValidation();

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
