<?php

namespace spec\Sylius\ElasticSearchPlugin\Document;

use Sylius\ElasticSearchPlugin\Document\Attribute;
use PhpSpec\ObjectBehavior;

final class AttributeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Attribute::class);
    }

    function it_has_value()
    {
        $this->setValue('red');

        $this->getValue()->shouldReturn('red');
    }

    function it_has_name()
    {
        $this->setName('color');

        $this->getName()->shouldReturn('color');
    }
}
