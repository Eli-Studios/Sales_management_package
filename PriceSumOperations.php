<?php

abstract class PriceSumOperations
{
    public string $prefix;
    public string $currency;
    public int $priceSum;
    public function __construct($priceSum, $prefix)
    {
        $this->prefix = $prefix;
        $this->priceSum = $priceSum;
    }
    public function printPriceSum($totalLineWidth): string
    {
        $containsUmlauts = containsUmlauts($this->prefix) + 1;
        echo $tempContent = $this->prefix . str_pad($this->currency, ($totalLineWidth - 24 - mb_strlen($this->prefix, "UTF-8") + $containsUmlauts), " ", STR_PAD_LEFT);
        return $tempContent;
    }

}