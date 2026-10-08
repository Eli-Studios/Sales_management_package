<?php

class TSE extends SalesReceiptTemplate
{
    protected int $longestKey;

    public function output(): string
    {
        foreach($this->tseData as $key => $value)
        {
            echo mb_str_pad($key . ":", 20);
            $this->tempLogContent .= mb_str_pad($key . ":", 20);

            echo $value . PHP_EOL;
            $this->tempLogContent .= $value . PHP_EOL;
        }
        echo PHP_EOL;
        return $this->tempLogContent .= PHP_EOL;
    }
    public function __construct(
      private array $tseData
    ){
        $this->longestKey = 0;
        $this->tempLogContent = "";
    }
}
