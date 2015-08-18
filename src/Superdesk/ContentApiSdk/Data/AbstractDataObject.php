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
use Superdesk\ContentApiSdk\ContentApiSdk;

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
        $objectData = array();

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
     * Set property on object
     *
     * @param string $name  Property name
     * @param mixed $value Property value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Returns id extracted from uri. Uses getIdFromUri method in ContentApiSdk
     * class.
     *
     * @return string Urldecoded id
     */
    public function getId()
    {
        return ContentApiSdk::getIdFromUri($this->uri);
    }
}
