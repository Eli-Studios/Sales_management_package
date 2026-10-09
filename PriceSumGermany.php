<?php

class priceSumGermany extends PriceSumOperations
{
    public function getPriceSum($totalLineWidth): string
    {
        $tempLogContent = parent::getPriceSum($totalLineWidth);
        $tempLogContent .= str_pad(number_format($this->priceSum / 100, 2, ","),
        $totalLineWidth - 33 - mb_strlen($this->currency, "UTF-8"), " ", STR_PAD_LEFT) . PHP_EOL;
        return $tempLogContent;
    }

    public function getPrefixOfGetChange():string
    {
        return "Geg. BAR" . str_pad($this->currency, 25, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
    }

    public function getChange(): array
    { 
        $payment = (int)str_replace(",", ".", readline());  
        return [$payment . PHP_EOL, $payment * 100 - $this->priceSum];
    }

    public function __construct($priceSum, $prefix, string $currency) {
        parent::__construct($priceSum, $prefix);
        $this->currency = $currency;
    }
}
