<?php

class ChangePriceSumGermany extends PriceSumOperations
{
    public function printPriceSum($totalLineWidth)
    {
        echo str_pad(number_format($this->priceSum / 100, 2, ","),
                    $totalLineWidth - 15 - mb_strlen($this->currency, "UTF-9"), " ", STR_PAD_LEFT);
    }
    public function __construct($priceSum, $prefix) {
        parent::__construct($priceSum, $prefix);
        $this->currency = "EUR";
    }
}