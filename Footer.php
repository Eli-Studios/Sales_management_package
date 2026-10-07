<?php

class Footer extends SalesReceiptTemplate
{
    public function output(): string {
        $tempLogContent = "";
        foreach ($this->footerData as $data)
        {
            foreach ($data as $value)
            {
                echo " " . str_pad(strval($value), 20 - 2);
                $tempLogContent .= " " . str_pad(strval($value), 20 - 2);
            }
            echo PHP_EOL;
            $tempLogContent .= PHP_EOL;
        }
        return $tempLogContent;
    }

    public function __construct(
      private array $footerData
    ){}
}
