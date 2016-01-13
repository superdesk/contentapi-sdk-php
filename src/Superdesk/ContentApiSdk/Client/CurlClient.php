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

use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\Exception\ClientException;

/**
 * Request service that implements functionality for basic http requests using
 * curl.
 */
class CurlClient implements ClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function makeCall(
        $url,
        array $headers = array(),
        array $options = array(),
        $method = 'GET',
        $content = null
    ) {
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $this->processRequestHeaders($headers),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => ContentApiSdk::USERAGENT,
            CURLOPT_RETURNTRANSFER => true,
        );

        if (strtoupper($method) == 'POST') {
            $curlOptions[CURLOPT_POST] = true;
            $curlOptions[CURLOPT_POSTFIELDS] = $content;
        }

        $curlHandler = curl_init();
        if (!$curlHandler) {
            throw new ClientException('Could not instantiate cURL.');
        }

        curl_setopt_array($curlHandler, $curlOptions);
        $response = curl_exec($curlHandler);

        if ($response === false) {
            throw new ClientException(sprintf('%s (%s)', 'A cURL error occured.', curl_error($curlHandler)), curl_errno($curlHandler));
        }

        $header_size = curl_getinfo($curlHandler, CURLINFO_HEADER_SIZE);

        $responseArray = array(
            'headers' => $this->parseHttpHeaders(mb_substr($response, 0, $header_size)),
            'status' => (int) curl_getinfo($curlHandler, CURLINFO_HTTP_CODE),
            'body' => (mb_strlen($response) > $header_size) ? mb_substr($response, $header_size) : ''
        );

        curl_close($curlHandler);

        return $responseArray;
    }

    /**
     * Parse header string from Curl request and returns an array. Based on:
     *     http://php.net/manual/en/function.http-parse-headers.php#112986
     *     http://php.net/manual/en/function.http-parse-headers.php#112987
     *
     * @param  string $headerString
     *
     * @return array
     */
    private function parseHttpHeaders($headerString)
    {
        $headers = array();
        $key = '';

        foreach (explode("\n", $headerString) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                } else {
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                }

                $key = $h[0];
            } else {
                if (substr($h[0], 0, 1) == "\t") {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } elseif (!$key) {
                    $headers[0] = trim($h[0]);
                }
            }
        }

        return $headers;
    }

    /**
     * Convert headers in key => value format, to cURL accepted header format.
     *
     * @param  string[] $headers Headers in key => value format, if key is
     *                           numeric only the value will be stored in the
     *                           new array.
     *
     * @return string[]
     */
    private function processRequestHeaders(array $headers)
    {
        $curlHeaders = array();

        foreach ($headers as $name => $value) {
            if (!is_numeric($name)) {
                $header = sprintf('%s: %s', $name, $value);
            } else {
                $header = $value;
            }

            $curlHeaders[] = $header;
        }

        return $curlHeaders;
    }
}
