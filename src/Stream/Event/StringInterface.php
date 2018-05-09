<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

use Remorhaz\JSON\Parser\Stream\Event\ScalarInterface;
use Remorhaz\JSON\Parser\Stream\Event\StringValueInterface;

interface StringInterface extends ScalarInterface, StringValueInterface
{
}
