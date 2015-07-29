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

namespace Superdesk\ContentApiSdk\Data;

use stdClass;

// TODO: Replace by entities (most likely)
/**
 * Abstract for simple Item and Package objects.
 */
abstract class AbstractDataObject extends stdClass
{
    /**
     * Construct method for class. Converts data (string via json_decode or
     * array/object) to properties.
     *
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        if (is_string($data)) {
            $objectData = json_decode($data);
        } elseif (is_array($data) || is_object($data)) {
            $objectData = $data;
        }

        foreach ($objectData as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get property from object.
     *
     * @param string $property Property name
     *
     * @return mixed Property value
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * Returns id extracted from uri
     *
     * @return string Urldecoded id
     */
    public function getId()
    {
        /*
         * Works for package and item uris
         *   http://publicapi:5050/packages/tag%3Ademodata.org%2C0012%3Aninjs_XYZ123
         *   http://publicapi:5050/items/tag%3Ademodata.org%2C0003%3Aninjs_XYZ123
         */

        $uriPath = parse_url($this->uri, PHP_URL_PATH);
        $objectId = str_replace($this->getAvailableEndpoints(), '', $uriPath);
        // Remove possible slashes and spaces, since we're working with urls
        $objectId = trim($objectId, '/ ');
        $objectId = urldecode($objectId);

        return $objectId;
    }
}
