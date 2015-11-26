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

namespace spec\Superdesk\ContentApiSdk\Data;

use PhpSpec\ObjectBehavior;
use Superdesk\ContentApiSdk\API\Response;

class PackageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Data\Package');
    }

    function it_should_return_a_valid_id()
    {
        $this->uri = 'http://publicapi:5050/items/tag%3Ademodata.org%2C0003%3Aninjs_XYZ123';
        $validId = 'tag:demodata.org,0003:ninjs_XYZ123';

        $this->getId()->shouldBe($validId);
    }

    function it_should_convert_a_response_properly()
    {
        $response = new Response('{"headline": "foo bar", "version": "1", "type": "composite", "versioncreated": "2015-02-10T06:49:47+0000", "_links": {"parent": {"title": "home", "href": "/"}, "collection": {"title": "packages", "href": "packages"}, "self": {"title": "Package", "href": "packages/tag:example.com,0001:newsml_BRE9A606"}}, "pubstatus": "usable", "language": "en", "uri": "http://api.master.dev.superdesk.org/packages/tag%3Aexample.com%2C0001%3Anewsml_BRE9A606"}');

        $this->beConstructedWith($response->getResources());

        foreach ($response->getResources() as $property => $value) {
            $this->shouldHaveProperty($property);
        }
    }

    public function getMatchers()
    {
        return [
            'haveProperty' => function ($object, $property) {
                return property_exists($object, $property);
            }
        ];
    }
}
