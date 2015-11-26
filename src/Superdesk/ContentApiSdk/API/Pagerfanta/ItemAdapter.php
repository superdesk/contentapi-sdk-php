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

namespace Superdesk\ContentApiSdk\API\Pagerfanta;

use Superdesk\ContentApiSdk\Data\Item;
use Superdesk\ContentApiSdk\Exception\InvalidDataException;

/**
 * Adapter for items
 */
class ItemAdapter extends ResourceAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $items = array();
        $resources = parent::getSlice($offset, $length);

        try {
            foreach ($resources as $itemData) {
                $items[] = new Item($itemData);
            }
        } catch(\Exception $e) {
            throw new InvalidDataException('Could not convert resources to items.', $e->getCode(), $e);
        }

        return $items;
    }
}
