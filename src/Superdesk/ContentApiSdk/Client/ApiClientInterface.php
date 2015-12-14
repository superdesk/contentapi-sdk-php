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
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\Exception\ClientException;
use Superdesk\ContentApiSdk\API\Request\RequestInterface;
use Superdesk\ContentApiSdk\API\Response;

/**
 * Interface for API specific clients.
 */
interface ApiClientInterface
{
    /**
     * Instantiate class.
     *
     * @param ClientInterface $client
     * @param AuthenticationInterface $authenticator
     */
    public function __construct(
        ClientInterface $client,
        AuthenticationInterface $authenticator
    );

    /**
     * Makes a call to the public api and returns a response.
     *
     * @param  RequestInterface $request
     *
     * @return Response
     */
    public function makeApiCall(RequestInterface $request);
}
