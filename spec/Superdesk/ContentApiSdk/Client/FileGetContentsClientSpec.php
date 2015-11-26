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

namespace spec\Superdesk\ContentApiSdk\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\API\Request;
use Superdesk\ContentApiSdk\API\Response;
use Superdesk\ContentApiSdk\Client\FileGetContentsClient;
use Superdesk\ContentApiSdk\Client\FileGetContentsClientHelper;

class FileGetContentsClientSpec extends ObjectBehavior
{
    function let(Request $request, FileGetContentsClientHelper $helper)
    {
        $request->beADoubleOf('\Superdesk\ContentApiSdk\API\Request');
        $request->getOptions()->willReturn(array());
        $helper->beADoubleOf('\Superdesk\ContentApiSdk\Client\FileGetContentsClientHelper');
        $this->beConstructedWith($helper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Client\FileGetContentsClient');
        $this->shouldImplement('Superdesk\ContentApiSdk\Client\ClientInterface');
    }

    function it_should_make_a_call_to_a_remote_server($request, $helper)
    {
        $body = '{"_meta": {"max_results": 2, "total": 45, "page": 3}, "_links": {"self": {"title": "packages", "href": "packages?start_date=2015-01-01&max_results=2&page=3"}, "parent": {"title": "home", "href": "/"}, "next": {"title": "next page", "href": "packages?max_results=2&page=4"}, "prev": {"title": "previous page", "href": "packages?max_results=2&page=2"}, "last": {"title": "last page", "href": "packages?max_results=2&page=23"}}, "_items": [{"body_html": "<p>SAUDI-LED COALITION SPOKESMAN SAYS COALITION JETS WERE IN ACTION\nIN SAADA GOVERNORATE, BUT NOT INSIDE CITY</p>", "pubstatus": "usable", "subject": [{"qcode": "16009000", "name": "war"}, {"qcode": "11002000", "name": "diplomacy"}, {"qcode": "10000000", "name": "lifestyle and leisure"}, {"qcode": "11000000", "name": "politics"}, {"qcode": "16000000", "name": "unrest, conflicts and  war"}], "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-10-27T17%3A53%3A17.408024%3Aa53e9fb0-d43e-41a4-a767-0dddaa549842", "headline": "SAUDI-LED COALITION SPOKESMAN SAYS CO", "priority": 1, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/tag%3Alocalhost%3A2015%3A120dfa7c-62eb-40c4-8972-7e0d41e3c220", "type": "text"}]}, "_type": "items", "version": 2, "_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-10-27T17:53:17.408024:a53e9fb0-d43e-41a4-a767-0dddaa549842"}}, "urgency": 1, "language": "en", "versioncreated": "2015-10-27T17:53:17+0000", "byline": "mkk", "located": "Santa Fe", "type": "composite"}, {"body_html": "<p>NAREG DARWIN</p>", "pubstatus": "usable", "subject": [{"qcode": "01001000", "parent": "01000000", "name": "archaeology"}], "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-10-27T21%3A26%3A35.366266%3Acbdb917d-5e5e-4796-b384-c784baa766d2", "headline": "NAREG DARWIN", "priority": 1, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/urn%3Anewsml%3Alocalhost%3A2015-10-27T21%3A26%3A07.748901%3A49745a57-04d8-4645-8e63-b3a06f2ca36b", "type": "text"}]}, "_type": "items", "version": 2, "_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-10-27T21:26:35.366266:cbdb917d-5e5e-4796-b384-c784baa766d2"}}, "urgency": 1, "language": "en", "versioncreated": "2015-10-27T21:26:35+0000", "byline": "N", "located": "Holdfast Bay", "type": "composite"}]}';
        $headers = array(
            'Content-Type' => 'application/json',
        );
        $request->getFullUrl()->willReturn('http://httpbin.org/status/200');
        $helper->sendRequest(Argument::type('string'), Argument::type('array'))->willReturn(array(
            'headers' => $headers,
            'body' => $body
        ));

        $this->makeApiCall($request)->shouldBeAnInstanceOf('\Superdesk\ContentApiSdk\API\Response');
    }

    function it_should_throw_an_exception_when_response_object_cannot_be_created(Request $request)
    {
        $request->getFullUrl()->willReturn('http://httpbin.org/status/200');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringMakeApiCall($request);
    }

    function it_should_throw_an_exception_on_error_http_status(Request $request)
    {
        $request->getFullUrl()->willReturn('http://httpbin.org/status/404');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringMakeApiCall($request);
        $request->getFullUrl()->willReturn('http://httpbin.org/status/500');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ClientException')->duringMakeApiCall($request);
    }
}
