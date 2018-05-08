<?php

namespace Remorhaz\JSON\Parser;

interface ScalarInterface
{

    public function getDocumentPart(): DocumentPartInterface;
}
