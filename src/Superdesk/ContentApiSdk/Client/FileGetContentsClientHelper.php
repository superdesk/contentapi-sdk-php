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

/**
 * Helper class for FileGetContentsClient
 */
class FileGetContentsClientHelper
{
    /**
     * Send actual request and return raw response data.
     *
     * @param string $url Url for file_get_contents
     * @param array $options Options for stream_context_create
     *
     * @return array Returns an array container headers and body or the response.
     *
     * @throws ClientException When the reponse did non succeed
     */
    public function sendRequest($url, $options)
    {
        $context = stream_context_create($options);

        // Silence error, we'll throw an exception on an invalid response
        $response = @file_get_contents(
            $url,
            false,
            $context
        );
        if ($response === false) {
            $lastError = error_get_last();
            throw new ClientException(sprintf('%s (%s)', 'Invalid response.', $lastError['message']), $lastError['type']);
        }

        return array('headers' => $http_response_header, 'body' => (string) $response);
    }
}
