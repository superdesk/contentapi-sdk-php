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

namespace Superdesk\ContentApiSdk;

use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;
use Superdesk\ContentApiSdk\API\Pagerfanta\ItemAdapter;
use Superdesk\ContentApiSdk\API\Pagerfanta\PackageAdapter;
use Superdesk\ContentApiSdk\API\Pagerfanta\ResourceCollection;
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\Data\Item;
use Superdesk\ContentApiSdk\Data\Package;
use Superdesk\ContentApiSdk\Exception\ContentApiException;
use Superdesk\ContentApiSdk\Exception\InvalidArgumentException;
use Superdesk\ContentApiSdk\Exception\InvalidDataException;
use stdClass;

/**
 * Superdesk ContentApi class.
 */
class ContentApiSdk
{
    /**
     * Items endpoint
     */
    const SUPERDESK_ENDPOINT_ITEMS = '/items';

    /**
     * Package endpoint
     */
    const SUPERDESK_ENDPOINT_PACKAGES = '/packages';

    /**
     * Type indication for packages
     */
    const PACKAGE_TYPE_COMPOSITE = 'composite';

    /**
     * Supported API version by this SDK version
     */
    const API_VERSION = 1;

    /**
     * A list of parameters the Content API accepts.
     * https://github.com/superdesk/superdesk-content-api/blob/master/content_api/items/service.py#L76
     *
     * @var array
     */
    public static $validParameters = array(
        'start_date', 'end_date', 'q', 'max_results', 'page',
        'include_fields', 'exclude_fields'
    );

    /**
     * Any (http) client that implements ClientInterface.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Hostname of the api instance
     *
     * @var string
     */
    protected $host;

    /**
     * Port of the api instalce
     *
     * @var int
     */
    protected $port = null;

    /**
     * Construct method for class.
     *
     * @param ClientInterface $client
     * @param string|null $host
     * @param int|null $port
     */
    public function __construct(ClientInterface $client, $host = null, $port = null)
    {
        $this->client = $client;

        if (!is_null($host)) {
            $this->setHost($host);
        }

        if (!is_null($port)) {
            $this->setPort($port);
        }
    }

    /**
     * Gets the value of client.
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the value of client.
     *
     * @param ClientInterface $client Value to set
     *
     * @return self
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Gets the value of apiHost.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the value of host.
     *
     * @param string $host Value to set
     *
     * @return self
     */
    public function setHost($host)
    {
        if (!is_string($host)) {
            throw new ContentApiException('The property host should be of type integer.');
        }

        $this->host = $host;

        return $this;
    }

    /**
     * Gets the value of port.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Sets the value of port.
     *
     * @param int $port Value to set
     *
     * @return self
     */
    public function setPort($port)
    {
        if (!is_int($port)) {
            throw new ContentApiException('The property port should be of type integer.');
        }

        $this->port = $port;

        return $this;
    }

    /**
     * Get a single item via id.
     *
     * @param string $itemId Identifier for item
     *
     * @return Item
     */
    public function getItem($itemId)
    {
        $request = $this->getNewRequest(sprintf('%s/%s', self::SUPERDESK_ENDPOINT_ITEMS, $itemId));

        try {
            $response = $this->client->makeApiCall($request);
            $item = new Item($response->getResources());

            return $item;
        } catch (ClientException $e) {
            throw new ContentApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get multiple items based on a filter.
     *
     * @param array $params Filter parameters
     *
     * @return mixed
     */
    public function getItems($params)
    {
        $itemCollection = new ResourceCollection(
            new ItemAdapter(
                $this->client,
                $this->getNewRequest(self::SUPERDESK_ENDPOINT_ITEMS, $params)
            )
        );

        $page = (isset($params['page'])) ? $params['page'] : 1;
        $maxResults = (isset($params['max_results'])) ? $params['max_results'] : 25;

        $itemCollection->setCurrentPage($page);
        $itemCollection->setMaxPerPage($maxResults);

        return $itemCollection;
    }

    /**
     * Get package by identifier.
     *
     * @param string $packageId    Package identifier
     * @param bool   $resolveItems Inject full associations recursively instead
     *                             of references by uri.
     *
     * @return Package
     */
    public function getPackage($packageId, $resolveItems = false)
    {
        $request = $this->getNewRequest(sprintf('%s/%s', self::SUPERDESK_ENDPOINT_PACKAGES, $packageId));
        $response = $this->client->makeApiCall($request);

        $package = new Package($response->getResources());

        // This can be removed once the API fully supports retrieving package associations
        if ($resolveItems) {
            $associations = $this->getAssociationsFromPackage($package);
            $package = $this->injectAssociations($package, $associations);
        }

        return $package;
    }

    /**
     * Get multiple packages based on a filter.
     *
     * @param array $params       Filter parameters
     * @param bool  $resolveItems Inject full associations recursively instead
     *                            of references by uri.
     *
     * @return mixed
     */
    public function getPackages($params, $resolveAssociations = false)
    {
        $packageCollection = new ResourceCollection(
            new PackageAdapter(
                $this->client,
                $this->getNewRequest(self::SUPERDESK_ENDPOINT_PACKAGES, $params),
                $this,
                $resolveAssociations
            )
        );

        $page = (isset($params['page'])) ? $params['page'] : 1;
        $maxResults = (isset($params['max_results'])) ? $params['max_results'] : 25;

        $packageCollection->setPage($page);
        $packageCollection->setMaxResults($maxResults);

        return $packageCollection;
    }

    /**
     * Gets full objects for all associations for a package.
     *
     * @param Package $package A package
     *
     * @return stdClass List of associations
     */
    private function getAssociationsFromPackage(Package $package)
    {
        $associations = new stdClass();

        if (isset($package->associations)) {
            foreach ($package->associations as $associationGroupName => $associationGroupItems) {

                $groupAssociations = new stdClass();

                foreach ($associationGroupItems AS $associatedName => $associatedItem) {
                    $associatedId = $this->getIdFromUri($associatedItem->uri);

                    if ($associatedItem->type == self::PACKAGE_TYPE_COMPOSITE) {
                        try {
                            $associatedObj = $this->getPackage($associatedId, true);
                        } catch (ContentApiException $e) {
                        }
                    } else {
                        try {
                            $associatedObj = $this->getItem($associatedId);
                            $associatedObj->type = $associatedItem->type;
                        } catch (ContentApiException $e) {
                        }
                    }

                    $groupAssociations->$associatedName = $associatedObj;
                }

                $associations->$associationGroupName = $groupAssociations;
            }
        }

        return $associations;
    }

    /**
     * Overwrite the associations links in a packages with the actual association
     * data.
     *
     * @param Package  $package      Package
     * @param stdClass $associations Multiple items or packages
     *
     * @return Package Package with data injected
     */
    private function injectAssociations(Package $package, stdClass $associations)
    {
        if (count($package->associations) > 0 && count($associations) > 0) {
            $package->associations = $associations;
        }

        return $package;
    }

    /**
     * Shortcut method to create new class.
     *
     * @param  string $uri Uri of the request
     * @param  array $parameters Parameters for the request object
     *
     * @return Request
     */
    public function getNewRequest($uri, array $parameters = array())
    {
        return new Request($this->host, $uri, $parameters, $this->port);
    }

    /**
     * Tries to find a valid id in an uri, both item as package uris. The id
     * is returned urldecoded.
     *
     * @param string $uri Item or package uri
     *
     * @return string Urldecoded id
     */
    public static function getIdFromUri($uri)
    {
        /*
         * Works for package and item uris
         *   http://publicapi:5050/packages/tag%3Ademodata.org%2C0012%3Aninjs_XYZ123
         *   http://publicapi:5050/items/tag%3Ademodata.org%2C0003%3Aninjs_XYZ123
         */

        $uriPath = parse_url($uri, PHP_URL_PATH);
        $objectId = str_replace(self::getAvailableEndpoints(), '', $uriPath);
        // Remove possible slashes and spaces, since we're working with urls
        $objectId = trim($objectId, '/ ');
        $objectId = urldecode($objectId);

        return $objectId;
    }

    /**
     * Returns a list of all supported endpoints for the Superdesk Content API.
     *
     * @return string[]
     */
    public static function getAvailableEndpoints()
    {
        return array(
            self::SUPERDESK_ENDPOINT_ITEMS,
            self::SUPERDESK_ENDPOINT_PACKAGES,
        );
    }

    /**
     * Returns a list of parameters accepted by the Content API.
     *
     * @return string[]
     */
    public static function getValidParameters()
    {
        return self::$validParameters;
    }

    /**
     * Automatically converts parameters to types accepted by the Content API.
     * Can also validate parameters, will unset invalid ones if $validate is
     * set to true. Throws an InvalidArgumentException when an invalid value
     * is supplied for a parameter.
     *
     * @param  mixed[] $requestParameters Array of parameter, where key
     *                                    represents the parameter name
     * @param  boolean $validate Validation boolean
     *
     * @return mixed[] Returns an array of parameters with API safe value types
     * @throws InvalidArgumentException
     */
    public static function processParameters(array $requestParameters, $validate = false)
    {
        $processedParameters = array();
        $validParameters = self::getValidParameters();

        foreach ($requestParameters as $name => $value) {

            if ($validate && !in_array($name, $validParameters)) {
                unset($requestParameters[$name]);
                continue;
            }

            switch ($name) {
                case 'start_date':
                case 'end_date':
                        if (!is_string($value) && !($value instanceof \DateTime)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type string or DateTime.', $name));
                        } elseif ($value instanceof \DateTime) {
                            $value = $value->format('Y-m-d');
                        } elseif (!preg_match('/\d\d\d\d\-\d\d\-\d\d/', $value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s has invalid format, please use dddd-dd-dd.', $name));
                        }
                    break;
                case 'q':
                        if (!is_string($value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type string.', $name));
                        }
                    break;
                case 'include_fields':
                case 'exclude_fields':
                        if (!is_string($value) && !is_array($value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type string or array.', $name));
                        } elseif (is_array($value)) {
                            $value = implode(',', $value);
                        }
                    break;
                case 'page':
                case 'max_results':
                        if (!is_int($value) && !ctype_digit($value)) {
                            throw new InvalidArgumentException(sprintf('Parameter %s should be of type integer.', $name));
                        } elseif (!is_int($value)) {
                            $value = (int) $value;
                        }
                    break;
                default:
                    break;
            }

            $processedParameters[$name] = $value;
        }

        return $processedParameters;
    }

    /**
     * Converts json string into StdClass object. Throws an InvalidDataException
     * when string could not be converted to object.
     *
     * @param string $jsonString JSON string
     *
     * @return object
     * @throws InvalidDataException
     */
    public static function getValidJsonObj($jsonString)
    {
        $jsonObj = json_decode($jsonString);
        if (is_null($jsonObj) || json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidDataException('Response body is not (valid) json.', json_last_error());
        }

        return $jsonObj;
    }

    /**
     * Returns version of api for creating verioned url.
     *
     * @return string
     */
    public static function getVersionURL()
    {
        return sprintf('v%d', self::API_VERSION);
    }
}
