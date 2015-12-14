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

use Pagerfanta\Adapter\AdapterInterface;
use Superdesk\ContentApiSdk\API\Request\PaginationDecorator;
use Superdesk\ContentApiSdk\API\Request\RequestInterface;
use Superdesk\ContentApiSdk\Client\ApiClientInterface;

/**
 * Base adapter for api resources.
 */
class ResourceAdapter implements AdapterInterface
{
    /**
     * HTTP Client.
     *
     * @var ApiClientInterface
     */
    protected $client;

    /**
     * API Request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * Instantiate object.
     *
     * @param ApiClientInterface $client HTTP Client
     * @param RequestInterface $request API Request object
     */
    public function __construct(ApiClientInterface $client, RequestInterface $request)
    {
        $this->client = $client;
        $this->request = $request;
    }

    /**
     * Make call HTTP call.
     *
     * @return \Superdesk\ContentApiSdk\API\Response API Response object
     */
    private function doCall(RequestInterface $request)
    {
        return $this->client->makeApiCall($request);
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults()
    {
        $response = $this->doCall($this->request);

        return $response->getTotalResults();
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|stdClass|\Traversable The slice.
     *
     * @throws InvalidDataException
     */
    public function getSlice($offset, $length)
    {
        $paginationRequest = new PaginationDecorator($this->request);
        $paginationRequest->addPagination($offset, $length);

        $response = $this->doCall($paginationRequest);

        return $response->getResources();
    }
}
