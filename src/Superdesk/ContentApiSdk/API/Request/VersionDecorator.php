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

namespace Superdesk\ContentApiSdk\API\Request;

use Superdesk\ContentApiSdk\ContentApiSdk;

/**
 * Version decorator for API request.
 */
class VersionDecorator extends RequestDecorator
{
    /**
     * Adds version to request uri.
     *
     * @return self
     */
    public function addVersion()
    {
        $this->setUri(sprintf('%s/%s', ContentApiSdk::getVersionURL(), $this->getUri()));

        return $this;
    }
}
