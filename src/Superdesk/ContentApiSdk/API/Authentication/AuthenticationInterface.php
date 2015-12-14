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
use Superdesk\ContentApiSdk\Exception\AuthenticationException;

interface AuthenticationInterface
{
    /**
     * Instantiate the object.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client);

    /**
     * Returns the access token.
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Tries to refresh the access token based on the current refresh token.
     *
     * @return string
     */
    public function refreshAccessToken();

    /**
     * Makes a call to the Content API and returns the tokens.
     *
     * @return string[] List of tokens
     */
    public function getAuthenticationTokens();
}
