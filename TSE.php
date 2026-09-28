<?php

class TSE extends SalesReceiptTemplate
{
    private array $tseData;
    protected int $longestKey;

    public function output(): string
    {
        $tempLogContent = "";
        foreach($this->tseData as $key => $value)
        {
            echo mb_str_pad($key . ":", 20);
            $tempLogContent = mb_str_pad($key . ":", 20);
            echo $value . PHP_EOL;
            $tempLogContent .= $value . PHP_EOL;
        }
        echo PHP_EOL;
        return $tempLogContent .= PHP_EOL;
    }
    public function __construct(array $tseData)
    {
        $this->tseData = $tseData;
        $this->longestKey = 0;
    }
}