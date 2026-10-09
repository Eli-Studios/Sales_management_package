<?php

class TSE extends SalesReceiptTemplate
{
    public function output(): string
    {
        foreach($this->tseData as $key => $value)
        {
            $this->tempLogContent .= mb_str_pad($key . ":", 20);
            $this->tempLogContent .= $value . PHP_EOL;
        }
        return $this->tempLogContent .= PHP_EOL;
    }
    public function __construct(
      private array $tseData,
      protected int $longestKey = 0
    ){
        $this->tempLogContent = "";
    }
}
