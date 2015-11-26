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

namespace Superdesk\ContentApiSdk\API;

use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Exception\ResponseException;
use Superdesk\ContentApiSdk\Exception\InvalidDataException;
use stdClass;

/**
 * API Response object.
 */
class Response
{
    const TYPE_ITEMS = 'items';
    const TYPE_PACKAGES = 'packages';

    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_XML = 'application/xml';

    /**
     * Unprocessed body as returned by the api.
     *
     * @var string
     */
    protected $rawBody;

    /**
     * Content type of the response, we should support xml and json.
     *
     * @var string
     */
    protected $contentType;

    /**
     * Type of response.
     *
     * @var string
     */
    protected $type;

    /**
     * Request URI.
     *
     * @var string
     */
    protected $href;

    /**
     * List of response headers.
     *
     * @var array
     */
    protected $headers;

    /**
     * Current page.
     *
     * @var int
     */
    protected $page;

    /**
     * Next page.
     *
     * @var int
     */
    protected $nextPage;

    /**
     * Previous page.
     *
     * @var int
     */
    protected $prevPage;

    /**
     * Last page.
     *
     * @var int
     */
    protected $lastPage;

    /**
     * Maximum of results on 1 page.
     *
     * @var int
     */
    protected $maxResults;

    /**
     * Total amount of results for current parameters.
     *
     * @var int
     */
    protected $total;

    /**
     * Array of resources.
     *
     * @var array
     */
    protected $resources;

    /**
     * Array of keys which are not part of the actual single data (item or
     * package).
     *
     * @var array
     */
    protected $metaKeys = array(
        '_links'
    );

    /**
     * Constructs Response.
     *
     * @param string     $body    Body of a response as string
     * @param array|null $headers List of headers
     */
    public function __construct($body, array $headers = null)
    {
        $this->rawBody = $body;
        $this->headers = $headers;

        $this->determineContentType();
        $this->processRawBody();
    }

    /**
     * Determine the content type of the response via header. If header is not
     * set, then content will be analyzed. Throws exception on failure.
     *
     * @return string Content type of the response
     *
     * @throws ResponseException
     */
    private function determineContentType()
    {
        if (isset($this->headers['Content-Type']) && in_array($this->headers['Content-Type'], $this->getSupportedContenTypes())) {
            $this->contentType = $this->headers['Content-Type'];

            return;
        }

        // Try to determine content type based on body
        try {
            $xml = new \SimpleXMLElement($this->rawBody);
            $this->contentType = self::CONTENT_TYPE_XML;

            return;
        } catch (\Exception $e) {
            // Not xml
        }

        $json = json_decode($this->rawBody);

        if (!is_null($json) && json_last_error() === JSON_ERROR_NONE) {
            $this->contentType = self::CONTENT_TYPE_JSON;

            return;
        }

        throw new ResponseException('Could not determine response content-type.');
    }

    /**
     * Return valid response content types.
     *
     * @return string[]
     */
    public static function getSupportedContenTypes()
    {
        return array(
            self::CONTENT_TYPE_JSON,
            self::CONTENT_TYPE_XML,
        );
    }

    /**
     * Sets properties based on response body.
     *
     * @throws ResponseException
     */
    private function processRawBody()
    {
        switch ($this->contentType) {
            case self::CONTENT_TYPE_JSON:

                try {
                    $response = ContentApiSdk::getValidJsonObj($this->rawBody);
                } catch (InvalidDataException $e) {
                    throw new ResponseException($e->getMessage(), $e->getCode(), $e);
                }

                $this->type = $response->_links->self->title;
                $this->href = $response->_links->self->href;

                if (property_exists($response, '_meta')) {
                    $this->page = $response->_meta->page;
                    $this->maxResults = $response->_meta->max_results;
                    $this->total = $response->_meta->total;

                    $this->nextPage = (property_exists($response->_links, 'next')) ? $this->page + 1 : $this->page;
                    $this->prevPage = (property_exists($response->_links, 'prev')) ? $this->page - 1 : $this->page;
                    // TODO: Check if casting to int is really required
                    $this->lastPage = (property_exists($response->_links, 'last')) ? (int) ceil($this->total / $this->maxResults) : $this->page;

                    $this->resources = $response->_items;
                } else {
                    $resourceObj = new stdClass();
                    foreach ($response as $key => $value) {
                        if (in_array($key, $this->metaKeys)) {
                            continue;
                        }

                        $resourceObj->$key = $value;
                    }

                    $this->resources = $resourceObj;
                }

                break;
            case self::CONTENT_TYPE_XML:

                // TODO: Handle proper xml output
                // throw new \Exception('Build in XML support!');
                break;
        }

        return;
    }

    /**
     * Returns content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Returns response type. Package or item.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the response href. In most cases this is the request uri.
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Returns current page number.
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->page;
    }

    /**
     * Returns next page number.
     *
     * @return int
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * Returns previous page number.
     *
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->prevPage;
    }

    /**
     * Returns last page number.
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * Returns first page number.
     *
     * @return int
     */
    public function getFirstPage()
    {
        return 1;
    }

    /**
     * Returns whether current page is last page.
     *
     * @return bool
     */
    public function isLastPage()
    {
        return $this->getCurrentPage() === $this->getLastPage();
    }

    /**
     * Returns whether current page is first page.
     *
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->getCurrentPage() === $this->getFirstPage();
    }

    /**
     * Returns maximum results displayed per page.
     *
     * @return int
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * Returns total amount of results.
     *
     * @return int
     */
    public function getTotalResults()
    {
        return $this->total;
    }

    /**
     * Returns the response resources, items or packages.
     *
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }
}
