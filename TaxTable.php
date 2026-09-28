<?php

class TaxTable extends SalesReceiptTemplate
{
    public int $aNetto;
    public int $aBrutto;
    public int $bNetto;
    public int $bBrutto;
    public int $aTax;
    public int $bTax;
    public int $totalNetto;
    public int $totalBrutto;
    public int $totalTax;
    public array $taxClass;

    public function calculateTaxTable(array $taxClass): array
    {
        return $taxTable = [
                ["Steuer  %", "Netto", "Steuer", "Brutto"],
                ["A= ". str_pad(number_format($taxClass['A'], 1, ",") . "%", 9 - strlen($taxClass['A'] * 100),
        " ", STR_PAD_LEFT), 'Netto' => $this->aNetto / 100, 'Steuer' => $this->aTax / 100, 'Brutto' => $this->aBrutto / 100],
                ["B=". str_pad(number_format($taxClass['B'], 1, ",") . "%", 9 - strlen($taxClass['B'] * 100),
        " ", STR_PAD_LEFT), 'Netto' => $this->bNetto / 100, 'Steuer' => $this->bTax / 100, 'Brutto'=> $this->bBrutto / 100],
                ['Gesamtbetrag', 'Netto' => $this->totalNetto / 100, 'Steuer' => $this->totalTax / 100, 'Brutto' => $this->totalBrutto / 100]];
    }

    public function output(): string
    {
        echo PHP_EOL;
        $tempLogContent =  PHP_EOL;
        $taxTable = $this->calculateTaxTable($this->taxClass);

        foreach ($taxTable as $row)
        {
            foreach ($row as $value)
            {
                echo str_pad($value, 15, " ", STR_PAD_RIGHT);
                $tempLogContent .= str_pad($value, 15, " ", STR_PAD_RIGHT);
            }

            echo PHP_EOL;
            $tempLogContent .= PHP_EOL;
        }
        echo "\n\n";
        return $tempLogContent .= PHP_EOL . PHP_EOL;
    }

    public function __construct(array $taxClass, int $aBrutto, int $bBrutto)
    {
        $this->aBrutto = $aBrutto;
        $this->bBrutto = $bBrutto;
        $this->aNetto = round($this->aBrutto / ((($taxClass['A'] + 100)) / 100));
        $this->bNetto = round($this->bBrutto / ((($taxClass['B'] + 100)) / 100));
        $this->aTax = round($this->aBrutto - $this->aNetto);
        $this->bTax = round($this->bBrutto - $this->bNetto);
        $this->totalBrutto = $this->aBrutto + $this->bBrutto;
        $this->totalNetto = $this->aNetto + $this->bNetto;
        $this->totalTax = $this->aTax + $this->bTax;
        $this->taxClass = $taxClass;
    }
}