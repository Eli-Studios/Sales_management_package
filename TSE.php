<?php

class TSE extends SalesReceiptTemplate
{
    public array $tseData;
    public int $longestKey;

    public function output(): void
    {
        foreach($this->tseData as $key => $value)
        {
            echo mb_str_pad($key . ":", 20, " ", STR_PAD_RIGHT);
            echo $value . PHP_EOL;
        }
        echo PHP_EOL;
    }
    public function __construct(array $tseData)
    {
        $this->tseData = $tseData;
        $this->longestKey = 0;
    }
}