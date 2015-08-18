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

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Superdesk\ContentApiSdk\Exception\ContentApiException;

/**
 * Request service that implements all method regarding basic request/response
 * handling.
 */
class GuzzleClient extends BaseClient implements ClientInterface
{
    /**
     * Default values based on Superdesk.
     *
     * @var array
     */
    protected $config = array(
        'base_uri' => 'http://localhost:5050',
    );

    /**
     * Default request options.
     *
     * @var array
     */
    protected $options = array(
        'Accept' => 'application/json',
    );

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->config = array_merge($this->config, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function makeApiCall(
        $endpoint,
        $queryParameters = null,
        $options = null,
        $returnFullResponse = false
    ) {
        try {
            $response = $this->get(
                $this->buildUrl($endpoint, $this->processParameters($queryParameters)),
                $this->processOptions($options)
            );
        } catch (TransferException $e) {
            throw new ContentApiException($e->getMessage(), $e->getCode(), $e);
        }

        if ($returnFullResponse) {
            $return = $this->decodeResponse($response);
        } else {
            $return = (string) $response->getBody();
        }

        return $return;
    }

    /**
     * Returns base url based on configuration.
     *
     * @return string
     */
    private function getBaseUrl()
    {
        return rtrim($this->config['base_uri'], '/') ;
    }

    /**
     * Builds full url from getBaseUrl method and additional query parameters.
     *
     * @param string $url    Url path
     * @param mixed  $params See http_build_query for possibilities
     *
     * @return string
     */
    private function buildUrl($url, $params)
    {
        $url = sprintf(
            '%s/%s?%s',
            $this->getBaseUrl(),
            ltrim($url, '/'),
            ((!is_null($params)) ? http_build_query($params) : '')
        );

        return $url;
    }

    /**
     * Process request parameters.
     *
     * @param mixed $params
     *
     * @return array
     */
    private function processParameters($params)
    {
        if (!is_array($params)) {
            return $params;
        }

        $validParameters = ContentApiSdk::getValidParameters();
        foreach ($params as $key => $value) {
            if (!in_array($key, $validParameters)) {
                unset($params[$key]);
            }
        }

        return $params;
    }

    /**
     * Process options. Default class options will be overridden with the
     * options from the first argument. Via the options key it's possible to
     * override options globally via .yml file.
     *
     * @param  array|null $options Guzzle request headers / options
     *
     * @return array
     */
    private function processOptions($options)
    {
        // Override class defaults
        if (is_array($options)) {
            $options = array_merge($this->options, $options);
        } else {
            $options = $this->options;
        }

        // Add options from config
        if (isset($this->config['options']) && is_array($this->config['options'])) {
            $options = array_merge((array) $options, $this->config['options']);
        }

        return $options;
    }

    /**
     * Decodes a response into a standard formatted array. (See
     * ClientInterface for documentation).
     *
     * @param  ResponseInterface $response Guzzle response
     *
     * @return array                       Response as array
     */
    private function decodeResponse(ResponseInterface $response)
    {
        return array(
            'headers' => $response->getHeaders(),
            'status' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase(),
            'version' => $response->getProtocolVersion(),
            'body' => (string) $response->getBody(),
        );
    }
}
