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
 * Pagination decorator for API request.
 */
class PaginationDecorator extends RequestDecorator
{
    /**
     * Sets page number and max results per page parameters.
     *
     * @param int $offset Offset of rows
     * @param int $length Length of rows
     *
     * @return self
     */
    public function addPagination($offset, $length)
    {
        $parameters = $this->getParameters();
        $parameters['page'] = (int) ceil($offset / $length);
        $parameters['max_results'] = (int) $length;
        $this->setParameters($parameters);

        return $this;
    }
}
