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
 * Interface for basic http clients.
 */
interface ClientInterface
{
    /**
     * Makes a generic HTTP call.
     *
     * @param  string $url Url to send the request to
     * @param  array $headers Array containing request headers
     * @param  array $options Array containing options (e.g. for the request or
     *                        the underlying client)
     * @param  string $method Request method
     * @param  string $content Request content (in case of POST, PUT, etc.)
     *
     * @return mixed[] Returns an array containing the headers (key: headers)
     *                 and the raw body (key: body) as a string
     *
     * @throws ClientException When the returned response is identical to false
     */
    public function makeCall(
        $url,
        array $headers = array(),
        array $options = array(),
        $method = 'GET',
        $content = null
    );
}
