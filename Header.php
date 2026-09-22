<?php

class Header extends SalesReceiptTemplate
{
    public string $companyName;
    public string $street;
    public string $city;
    public string $phoneNumber;
    public string $vatID;

    public function output(): string
    {
        echo $tempContent = $this->companyName . "\n"
             . $this->street . "\n"
             . $this->city . "\n\n\n"
             . $this->phoneNumber . "\n"
             . $this->vatID . "\n\n";
        return $tempContent;
    }

    public function __construct($input)
    {
        foreach ($input as $key => $value)
        {
            match ($key)
            {
                'companyName' => $this->companyName = $value,
                'street' => $this->street = $value,
                'city' => $this->city = $value,
                'phoneNumber' => $this->phoneNumber = $value,
                'vatID' => $this->vatID = $value,
                default => null,
            };
        }
    }
}