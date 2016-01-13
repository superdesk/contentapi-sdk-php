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

namespace Superdesk\ContentApiSdk\Client;

use Superdesk\ContentApiSdk\API\Authentication\AuthenticationInterface;

/**
 * Abstract for api clients.
 */
abstract class AbstractApiClient implements ApiClientInterface
{
    const MAX_RETRY_LIMIT = 3;

    /**
     * HTTP client.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Authentication object.
     *
     * @var AuthenticationInterface
     */
    protected $authenticator;

    /**
     * Retry attempt counter for authentication.
     *
     * @var int
     */
    protected $authenticationRetryAttempt = 0;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ClientInterface $client,
        AuthenticationInterface $authenticator
    ) {
        $this->client = $client;
        $this->authenticator = $authenticator;
    }

    /**
     * Sets authentication retry limit to 0.
     *
     * @return void
     */
    protected function resetAuthenticationRetryAttempt()
    {
        $this->authenticationRetryAttempt = 0;

        return;
    }

    /**
     * Increments the authentication retry attempt with 1.
     *
     * @return void
     */
    protected function incrementAuthenticationRetryAttempt()
    {
        $this->authenticationRetryAttempt++;

        return;
    }

    /**
     * Returns authentication retry count.
     *
     * @return int
     */
    public function getAuthenticationRetryAttempt()
    {
        return $this->authenticationRetryAttempt;
    }

    /**
     * Returns whether the authentication limit is reached.
     *
     * @return boolean
     */
    protected function isAuthenticationRetryLimitReached()
    {
        return $this->getAuthenticationRetryAttempt() > self::MAX_RETRY_LIMIT;
    }
}
