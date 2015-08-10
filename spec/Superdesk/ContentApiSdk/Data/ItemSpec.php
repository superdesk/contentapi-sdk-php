<?php

namespace spec\Superdesk\ContentApiSdk\Data;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
}
