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

namespace Superdesk\ContentApiSdk\Api\Request;

interface RequestInterface
{
    /**
     * Get host.
     *
     * @return string
     */
    public function getHost();

    /**
     * Set hostname.
     *
     * @param string $host
     *
     * @return self
     */
    public function setHost($host);

    /**
     * Get port.
     *
     * @return int
     */
    public function getPort();

    /**
     * Set port.
     *
     * @param int $port
     *
     * @return self
     */
    public function setPort($port);

    /**
     * Get uri.
     *
     * @return string
     */
    public function getUri();

    /**
     * Set uri.
     *
     * @param string $uri
     *
     * @return self
     */
    public function setUri($uri);

    /**
     * Get query parameters.
     *
     * @return array
     */
    public function getParameters();

    /**
     * Set query parameters.
     *
     * @param string[] $parameters
     *
     * @return self
     *
     * @throws RequestException If parameters data types are invalid
     */
    public function setParameters(array $parameters);

    /**
     * Gets the value of headers.
     *
     * @return string[]
     */
    public function getHeaders();

    /**
     * Sets the value of headers.
     *
     * @param string[] $headers Value to set
     *
     * @return self
     */
    public function setHeaders(array $headers);

    /**
     * Gets the value of options.
     *
     * @return mixed[]
     */
    public function getOptions();

    /**
     * Sets the value of options.
     *
     * @param mixed[] $options Value to set
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Get parameter validation status.
     *
     * @return boolean
     */
    public function getParameterValidation();

    /**
     * Enables parameter validation.
     *
     * @return self
     */
    public function enableParameterValidation();

    /**
     * Disables parameter validation.
     *
     * @return self
     */
    public function disableParameterValidation();

    /**
     * Get base url.
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Get full url.
     *
     * @return string
     */
    public function getFullUrl();
}
