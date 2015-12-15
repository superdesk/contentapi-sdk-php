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

namespace Superdesk\ContentApiSdk\API\Authentication;

use Superdesk\ContentApiSdk\Client\ClientInterface;

abstract class AbstractAuthentication implements AuthenticationInterface
{
    /**
     * URI for requesting and refreshing tokens.
     */
    const AUTHENTICATION_URI = 'oauth/token';

    /**
     * Refresh token grant type.
     */
    const REFRESH_GRANT_TYPE = 'refresh_token';

    /**
     * HTTP Client for making authentication requests.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Access token for the API.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Refresh token to referesh the access token.
     *
     * @var string
     */
    protected $refreshToken;

    /**
     * Client ID for the API.
     *
     * @var string
     */
    protected $clientId;

    /**
     * Base url for the api.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * {@inheritdoc}
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Gets the value of clientId.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Sets the value of clientId.
     *
     * @param string $clientId Value to set
     *
     * @return self
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Gets the value of baseUrl.
     *
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Sets the value of baseUrl.
     *
     * @param mixed $baseUrl Value to set
     *
     * @return self
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Returns the url where authentication tokens can be retrieved.
     *
     * @return string
     */
    public function getAuthenticationUrl()
    {
        return sprintf('%s/%s', rtrim($this->getBaseUrl(), '/ '), self::AUTHENTICATION_URI);
    }
}
