<?php

namespace Remorhaz\JSON\Parser;

abstract class Scalar implements ScalarInterface
{

    private $documentPart;

    public function __construct(DocumentPartInterface $documentPart)
    {
        $this->documentPart = $documentPart;
    }

    public function getDocumentPart(): DocumentPartInterface
    {
        return $this->documentPart;
    }
}
