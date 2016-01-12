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
 * Request client that implements all methods regarding basic request/response
 * handling for the Content API.
 */
class DefaultApiClient extends AbstractApiClient
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
        // Request tokens when none are set
        if ($this->authenticator->getAccessToken() === null) {
            $this->getNewToken($request);
        }

        $response = $this->sendRequest($this->authenticateRequest($request));

        if ($response['status'] === 401) {

            $this->incrementAuthenticationRetryAttempt();

            if ($this->isAuthenticationRetryLimitReached()) {
                throw new AccessDeniedException('Authentication retry limit reached.');
            }

            // Once SD-3820 is fixed, implement SWP-92 branch, it will use
            // the refresh token functionality, instead of request a new token
            // each time this method is called.
            $this->getNewToken($request);

            // Retry making an api call
            return $this->makeApiCall($request);
        }

        if ($response['status'] == 200) {

            $this->resetAuthenticationRetryAttempt();

            return $this->createResponseObject($response);
        }

        throw new ClientException(sprintf('The server returned an error with status %s.', $response['status']), $response['status']);
    }

    private function createResponseObject($response)
    {
        try {
            return new Response($response['body'], $response['headers']);
        } catch (ResponseException $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Adds authentication details to request with OAuth decorator.
     *
     * @param  RequestInterface $request
     *
     * @return OAuthDecorator OAuth ready decorated Request
     */
    private function authenticateRequest(RequestInterface $request)
    {
        $authenticatedRequest = new OAuthDecorator($request);
        $authenticatedRequest->setAccessToken($this->authenticator->getAccessToken());
        $authenticatedRequest->addAuthentication();

        return $authenticatedRequest;
    }

    /**
     * Sends the actual request.
     *
     * @param  RequestInterface $request
     *
     * @return Response Response object created from raw response
     *
     * @throws ClientException Thrown when response could not be created.
     */
    private function sendRequest(RequestInterface $request)
    {
        return $this->client->makeCall(
            $request->getFullUrl(),
            $this->addDefaultHeaders($request->getHeaders()),
            $request->getOptions()
        );
    }

    /**
     * Refreshes the token via then authenticator.
     *
     * @param  RequestInterface $request
     *
     * @return void
     */
    private function getNewToken(RequestInterface $request)
    {
        try {
            $this->authenticator->setBaseUrl($request->getBaseUrl());
            $this->authenticator->getAuthenticationTokens();
        } catch (AuthenticationException $e) {
            throw new AccessDeniedException('Could not authenticate against API.', $e->getCode(), $e);
        }

        return;
    }

    /**
     * Adds default headers to the headers per request, only if the key
     * cannot not be found in the headers per request.
     *
     * @param array $headers
     *
     * @return array
     */
    private function addDefaultHeaders($headers)
    {
        foreach ($this->headers as $key => $value) {
            if (!isset($headers[$key])) {
                $headers[$key] = $value;
            }
        }

        return $headers;
    }
}
