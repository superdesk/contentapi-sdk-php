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

class ItemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Data\Item');
    }

    function it_should_return_a_valid_id()
    {
        $this->uri = 'http://publicapi:5050/packages/tag%3Ademodata.org%2C0012%3Aninjs_XYZ123';
        $validId = 'tag:demodata.org,0012:ninjs_XYZ123';

        $this->getId()->shouldBe($validId);
    }

    function it_should_convert_a_response_properly()
    {
        $response = new Response('{"pubstatus": "usable", "_links": {"parent": {"href": "/", "title": "home"}, "collection": {"href": "items", "title": "items"}, "self": {"href": "items/tag:example.com,0001:newsml_BRE9A607", "title": "Item"}}, "body_text": "Andromeda and Milky Way will collide in about 2 billion years", "type": "text", "language": "en", "versioncreated": "2015-03-09T16:32:23+0000", "uri": "http://api.master.dev.superdesk.org/items/tag%3Aexample.com%2C0001%3Anewsml_BRE9A607", "version": "2", "headline": "Andromeda on a collision course"}');

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
