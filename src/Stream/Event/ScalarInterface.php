<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

interface ScalarInterface
{

    public function getDocumentPart(): DocumentPartInterface;
}
