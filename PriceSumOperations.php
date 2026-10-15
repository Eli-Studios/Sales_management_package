<?php

abstract class PriceSumOperations
{
    protected string $currency;

    public function getPriceSum($totalLineWidth): string
    {
      return $this->prefix . str_pad($this->currency, ($totalLineWidth - 22 - mb_strlen($this->prefix, "UTF-8")),
             " ", STR_PAD_LEFT);
    }
    public function __construct(
      protected int $priceSum,
      protected string $prefix
    ){}
}
