<?php

class SalesReceiptTemplate
{
    public string $companyName;
    public string $street;
    public int $zipCode;
    public string $city;
    public string $phoneNumber;
    public string $vatID;

    public function outputHeader(): void
    {
        echo $this->companyName . "\n";
        echo $this->street . "\n";
        echo $this->zipCode . " ";
        echo $this->city . "\n\n\n";
        echo $this->phoneNumber . "\n";
        echo "UID Nr.: " . $this->vatID . "\n\n";
    }

    public function calculateTaxTable(array $taxClass) {
        $taxTable = [
            'Header' => ["Steuer     %", "Netto", "Steuer", "Brutto"],
            'Body' => ['A='. $taxClass['A'], 'Netto' => 0.03]]; #TODO: Finish this Array (review "Kassenbon")

        return $taxTable;
    }

    public function __construct(array $receiptTemplateData) {
        foreach ($receiptTemplateData as $key => $value) {
            match ($key) {
                'companyName' => $this->companyName = $value,
                'street' => $this->street = $value,
                'zipCode' => $this->zipCode = $value,
                'city' => $this->city = $value,
                'phoneNumber' => $this->phoneNumber = $value,
                'vatID' => $this->vatID = $value,
                default => null,
            };
        }
    }
}