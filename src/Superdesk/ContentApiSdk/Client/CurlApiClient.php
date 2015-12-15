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

use Superdesk\ContentApiSdk\API\Request\RequestInterface;
use Superdesk\ContentApiSdk\API\Request\OAuthDecorator;
use Superdesk\ContentApiSdk\API\Response;
use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Exception\AuthenticationException;
use Superdesk\ContentApiSdk\Exception\AccessDeniedException;
use Superdesk\ContentApiSdk\Exception\ClientException;
use Superdesk\ContentApiSdk\Exception\ResponseException;

/**
 * Request service that implements all method regarding basic request/response
 * handling.
 */
class CurlApiClient extends AbstractApiClient
{
    /**
     * Default request headers.
     *
     * @var array
     */
    protected $headers = array(
        'Accept' => 'application/json'
    );

    /**
     * {@inheritdoc}
     */
    public function makeApiCall(RequestInterface $request)
    {
        $response = null;

        // TODO: Add oauth decorator if access token isset
        if ($this->authenticator->getAccessToken() !== null) {
            $authenticatedRequest = new OAuthDecorator($request);
            $authenticatedRequest->setAccessToken($this->authenticator->getAccessToken());
            $authenticatedRequest->addAuthentication();

            $response = $this->client->makeCall(
                $authenticatedRequest->getFullUrl(),
                $this->add_default_headers($authenticatedRequest->getHeaders()),
                $authenticatedRequest->getOptions()
            );

            if ($response['status'] == 200) {
                $this->authenticationRetryLimit = 0;

                try {
                    return new Response($response['body'], $response['headers']);
                } catch (ResponseException $e) {
                    throw new ClientException($e->getMessage(), $e->getCode(), $e);
                }
            }
        }

        if ($response === null || $response['status'] == 401) {

            $this->authenticationRetryLimit++;

            if ($this->authenticationRetryLimit > self::MAX_RETRY_LIMIT) {
                throw new AccessDeniedException('Authentication retry limit reached.');
            }

            try {
                $this->authenticator->setBaseUrl($request->getBaseUrl());
                if ($this->authenticator->getAccessToken() !== null) {
                    $this->authenticator->refreshAccessToken();
                } else {
                    $this->authenticator->getAuthenticationTokens();
                }

                // Reexecute event
                return $this->makeApiCall($request);
            } catch (AccessDeniedException $e) {
                throw new AccessDeniedException($e->getMessage(), $e->getCode(), $e);
            } catch (AuthenticationException $e) {
                throw new AccessDeniedException('Could not authenticate against API.', $e->getCode(), $e);
            }
        }

        throw new ClientException(sprintf('The server returned an error with status %s.', $response['status']));
    }

    /**
     * Adds default headers to the headers per request, only if the key
     * cannot not be found in the headers per request.
     *
     * @param array $headers
     *
     * @return array
     */
    private function add_default_headers($headers)
    {
        foreach ($this->headers as $key => $value) {
            if (!isset($headers[$key])) {
                $headers[$key] = $value;
            }
        }

        return $headers;
    }
}
