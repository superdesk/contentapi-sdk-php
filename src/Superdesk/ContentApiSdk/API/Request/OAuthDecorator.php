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

use Superdesk\ContentApiSdk\Exception\RequestException;

/**
 * OAuth decorator for API request.
 */
class OAuthDecorator extends RequestDecorator
{
    /**
     * OAuth access token.
     *
     * @var string|null
     */
    protected $accessToken = null;

    /**
     * Get access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set access token.
     *
     * @param string $accessToken
     *
     * @return self
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Sets Authentication header on decorated request.
     *
     * @return self
     *
     * @throws RequestException When accessToken is not set
     */
    public function addAuthentication()
    {
        if ($this->accessToken === null) {
            throw new RequestException('Property accessToken should be set.');
        }

        $headers = $this->decoratedRequest->getHeaders();
        $headers['Authorization'] = sprintf('%s %s', 'OAuth2', $this->accessToken);
        $this->decoratedRequest->setHeaders($headers);

        return $this;
    }
}
