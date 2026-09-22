<?php

class Footer extends SalesReceiptTemplate
{
    public array $footerData;

    public function output(): string {
        $tempContent = "";
        foreach ($this->footerData as $data)
        {
            foreach ($data as $value)
            {
                echo " " . str_pad(strval($value), 20 - 2, " ", STR_PAD_RIGHT);
                $tempContent .= " " . str_pad(strval($value), 20 - 2, " ", STR_PAD_RIGHT);
            }
            echo "\n";
            $tempContent .= "\n";
        }
        return $tempContent;
    }

    public function __construct($footerData)
    {
        $this->footerData = $footerData;
    }
}