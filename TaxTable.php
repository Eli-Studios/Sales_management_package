<?php

class TaxTable extends SalesReceiptTemplate
{
    private int $aNetto;
    private int $aBrutto;
    private int $bNetto;
    private int $bBrutto;
    private int $aTax;
    private int $bTax;
    private int $totalNetto;
    private int $totalBrutto;
    private int $totalTax;
    private array $taxClass;

    public function calculateTaxTable(array $taxClass): array
    {
        return [
                ["Steuer %", "Netto", "Steuer", "Brutto"],
                ["A= ". str_pad(number_format($taxClass['A'], 1, ",") . "%", 9 - strlen($taxClass['A'] * 100),
        " ", STR_PAD_LEFT), 'Netto' => str_pad(number_format($this->aNetto / 100, 2, ","), 10 - strlen($this->aNetto * 100), " ", STR_PAD_LEFT), 'Steuer' => str_pad(number_format($this->aTax / 100, 2, ","), 11 - strlen($this->aTax * 100), " ", STR_PAD_LEFT), 'Brutto' => str_pad(number_format($this->aBrutto / 100, 2, ","), 12 - strlen($this->aBrutto * 100), " ", STR_PAD_LEFT)],
                ["B=". str_pad(number_format($taxClass['B'], 1, ",") . "%", 9 - strlen($taxClass['B'] * 100),
        " ", STR_PAD_LEFT), 'Netto' => str_pad(number_format($this->bNetto / 100, 2, ","), 10 - strlen($this->bNetto * 100), " ", STR_PAD_LEFT), 'Steuer' => str_pad(number_format($this->bTax / 100, 2, ","), 11 - strlen($this->bTax * 100), " ", STR_PAD_LEFT), 'Brutto'=> str_pad(number_format($this->bBrutto / 100, 2, ","), 12 - strlen($this->bBrutto * 100), " ", STR_PAD_LEFT)],
                ['Gesamtbetrag', 'Netto' => str_pad(number_format($this->totalNetto / 100, 2, ","), 10 - strlen($this->totalNetto * 100), " ", STR_PAD_LEFT), 'Steuer' => str_pad(number_format($this->totalTax / 100, 2, ","), 11 - strlen($this->totalTax * 100), " ", STR_PAD_LEFT), 'Brutto' => str_pad(number_format($this->totalBrutto / 100, 2, ","), 12 - strlen($this->totalBrutto * 100), " ", STR_PAD_LEFT)]];
    }

    public function output(): string
    {
        $taxTable = $this->calculateTaxTable($this->taxClass);
        
        echo $tempLogContent =  PHP_EOL; 
        foreach ($taxTable as $row)
        {
            foreach ($row as $value)
            {
                echo str_pad($value, 15);
                $tempLogContent .= str_pad($value, 15);
            }

            echo PHP_EOL;
            $tempLogContent .= PHP_EOL;
        }
        echo PHP_EOL . PHP_EOL;
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
