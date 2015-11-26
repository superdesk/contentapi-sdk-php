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

use Superdesk\ContentApiSdk\Exception\ClientException;
use Superdesk\ContentApiSdk\Exception\ResponseException;
use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;

/**
 * Request service that implements all method regarding basic request/response
 * handling.
 */
class FileGetContentsClient implements ClientInterface
{
    /**
     * Default request options.
     *
     * @var array
     */
    protected $options = array(
        'http' => array(
            'header' => array(
                "Accept: application/json\r\n"
            )
        )
    );

    /**
     * Helper class for making actual request
     *
     * @var FileGetContentsClientHelper
     */
    protected $helper;

    /**
     * Initialize object
     *
     * @param FileGetContentsClientHelper $helper
     */
    public function __construct(FileGetContentsClientHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function makeApiCall(Request $request)
    {
        $response = $this->helper->sendRequest(
            $request->getFullUrl(),
            $this->processOptions($request->getOptions())
        );

        try {
            return new Response($response['body'], $response['headers']);
        } catch (ResponseException $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Process options. Default class options will be overridden with the
     * options from the first argument. Via the options key it's possible to
     * override options globally via .yml file.
     *
     * @param  array|null $options File get contents request headers / options
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
}
