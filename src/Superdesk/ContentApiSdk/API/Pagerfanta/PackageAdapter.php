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

use Superdesk\ContentApiSdk\Data\Package;
use Superdesk\ContentApiSdk\Exception\InvalidDataException;

/**
 * Adapter for package
 */
class PackageAdapter extends ResourceAdapter
{
    /**
     * SDK Instance.
     *
     * @var Superdesk\CotentApiSdk\CotentApiSdk
     */
    protected $apiInstance;

    /**
     * Instantiate object.
     *
     * @param Superdesk\CotentApiSdk\Client\ClientInterface $client HTTP client
     * @param Superdesk\CotentApiSdk\API\Request $request API Request is_object(var)
     * @param Superdesk\CotentApiSdk\ContentApiSdk $apiInstance SDK Instance
     * @param boolean $resolveAssociations Resolve package associations
     */
    public function __construct($client, $request, $apiInstance, $resolveAssociations)
    {
        parent::__construct($client, $request);

        $this->apiInstance = $apiInstance;
        $this->resolveAssociations = $resolveAssociations;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $packages = array();
        $resources = parent::getSlice($offset, $length);

        try {
            foreach ($resources as $itemData) {
                $packages[] = new Package($itemData);
            }

            if ($this->resolveAssociations) {
                foreach ($packages as $id => $package) {
                    $associations = $this->apiInstance->getAssociationsFromPackage($package);
                    $packages[$id] = $this->apiInstance->injectAssociations($package, $associations);
                }
            }
        } catch(\Exception $e) {
            throw new InvalidDataException('Could not convert resources to packages.', $e->getCode(), $e);
        }

        return $packages;
    }
}
