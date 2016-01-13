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

use Superdesk\ContentApiSdk\API\Request\RequestInterface;
use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Exception\AuthenticationException;
use Superdesk\ContentApiSdk\Exception\ClientException;
use Superdesk\ContentApiSdk\Exception\InvalidDataException;

/**
 * OAuth password authentication class.
 */
class OAuthPasswordAuthentication extends AbstractAuthentication
{
    const AUTHENTICATION_GRANT_TYPE = 'password';

    /**
     * Username for OAuth password authentication.
     *
     * @var string
     */
    protected $username;

    /**
     * Password for OAuth password authentication.
     *
     * @var string
     */
    protected $password;

    /**
     * Gets the value of username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the value of username.
     *
     * @param string $username Value to set
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Gets the value of password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the value of password.
     *
     * @param string $password Value to set
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthenticationTokens()
    {
        try {
            $response = $this->client->makeCall(
                $this->getAuthenticationUrl(),
                array(),
                array(),
                'POST',
                array(
                    'client_id' => $this->getClientId(),
                    'grant_type' => self::AUTHENTICATION_GRANT_TYPE,
                    'username' => $this->getUsername(),
                    'password' => $this->getPassword()
                )
            );
        } catch (ClientException $e) {
            throw new AuthenticationException('Could not request access token.', $e->getCode(), $e);
        }

        if ($response['status'] === 200) {
            try {
                $responseObj = ContentApiSdk::getValidJsonObj($response['body']);
            } catch (InvalidDataException $e) {
                throw new AuthenticationException('Authentication response body is not (valid) json.', $e->getCode(), $e);
            }

            if (property_exists($responseObj, 'access_token')) {
                $this->accessToken = $responseObj->access_token;

                return true;
            }

            throw new AuthenticationException('The server returned an unexpected response body.');
        }

        throw new AuthenticationException(sprintf('The server returned an error with status %s.', $response['status']), $response['status']);
    }
}
