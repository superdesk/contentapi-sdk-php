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
     * Retry limit counter for authentication.
     *
     * @var int
     */
    protected $authenticationRetryLimit = 0;

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
}
