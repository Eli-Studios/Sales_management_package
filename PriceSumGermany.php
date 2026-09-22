<?php

class priceSumGermany extends PriceSumOperations
{
    public function __construct($priceSum, $prefix) {
        parent::__construct($priceSum, $prefix);
        $this->currency = "EUR";
    }
    public function printPriceSum($totalLineWidth): string
    {
        $tempContent = parent::printPriceSum($totalLineWidth);
        echo str_pad(number_format($this->priceSum / 100, 2, ","), $totalLineWidth - 33 - mb_strlen($this->currency, "UTF-8"), " ", STR_PAD_LEFT)
            . "\n";
        $tempContent .= str_pad(number_format($this->priceSum / 100, 2, ","), $totalLineWidth - 33 - mb_strlen($this->currency, "UTF-8"), " ", STR_PAD_LEFT)
            . "\n";
        return $tempContent;
    }
}