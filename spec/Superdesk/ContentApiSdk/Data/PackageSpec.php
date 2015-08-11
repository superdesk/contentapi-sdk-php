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
}
