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

use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;

/**
 * Interface for clients.
 */
interface ClientInterface
{
    /**
     * Makes a call to the public api and returns a response.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function makeApiCall(Request $request);
}
