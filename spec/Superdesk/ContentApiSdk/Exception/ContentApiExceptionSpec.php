<?php

namespace spec\Superdesk\ContentApiSdk\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Superdesk\ContentApiSdk\Client\ClientInterface;

class ContentApiExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\Exception\ContentApiException');
    }
}
