<?php

class Header extends SalesReceiptTemplate
{
    private string $companyName;
    private string $street;
    private string $city;
    private string $phoneNumber;
    private string $vatID;

    public function output(): string
    {
        echo $this->companyName . PHP_EOL
             . $this->street . PHP_EOL
             . $this->city . PHP_EOL . PHP_EOL . PHP_EOL
             . $this->phoneNumber . PHP_EOL
             . $this->vatID . PHP_EOL . PHP_EOL;

        return $this->companyName . PHP_EOL
            . $this->street . PHP_EOL
            . $this->city . PHP_EOL . PHP_EOL . PHP_EOL
            . $this->phoneNumber . PHP_EOL
            . $this->vatID . PHP_EOL . PHP_EOL;
    }

    public function __construct($input)
    {
        foreach ($input as $key => $value)
        {
            match ($key)
            {
                'companyName' => $this->companyName = $value,
                'street' => $this->street = $value,
                'city' => $this->city = $value, PHP_EOL . PHP_EOL . PHP_EOL,
                'phoneNumber' => $this->phoneNumber = $value,
                'vatID' => $this->vatID = $value,
                default => null,
            };
        }
    }
}