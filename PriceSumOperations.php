<?php

abstract class PriceSumOperations
{
    protected string $prefix;
    protected string $currency;
    protected int $priceSum;
    public function printPriceSum($totalLineWidth): string
    {
        echo $this->prefix . str_pad($this->currency, ($totalLineWidth - 24 - mb_strlen($this->prefix, "UTF-8")), " ", STR_PAD_LEFT);
        return $this->prefix . str_pad($this->currency, ($totalLineWidth - 24 - mb_strlen($this->prefix, "UTF-8")), " ", STR_PAD_LEFT);
    }
    public function __construct($priceSum, $prefix)
    {
        $this->prefix = $prefix;
        $this->priceSum = $priceSum;
    }
}