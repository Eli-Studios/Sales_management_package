<?php

class TSE extends SalesReceiptTemplate
{
    public array $tseData;
    public function output(): void
    {
        // TODO: Implement output() method.
    }

    public function __contruct(array $tseData)
    {
        $this->tseData = $tseData;
    }
}