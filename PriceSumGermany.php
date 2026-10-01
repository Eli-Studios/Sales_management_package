<?php

class priceSumGermany extends PriceSumOperations
{
    public function getPriceSum($totalLineWidth): string
    {
        $tempContent = parent::getPriceSum($totalLineWidth);
        $tempContent .= str_pad(number_format($this->priceSum / 100, 2, ","),
                $totalLineWidth - 33 - mb_strlen($this->currency, "UTF-8"), " ", STR_PAD_LEFT) . PHP_EOL;
        return $tempContent;
    }
    public function __construct($priceSum, $prefix, string $currency) {
        parent::__construct($priceSum, $prefix);
        $this->currency = $currency;
    }
}
