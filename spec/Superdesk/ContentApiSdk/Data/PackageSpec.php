<?php

namespace spec\Superdesk\ContentApiSdk\Data;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
