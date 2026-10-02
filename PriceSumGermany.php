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
    public function getChange(): array
    {
        echo $tempLogContent = "Geg. BAR" . str_pad($this->currency, 25, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
        $payment = (int)str_replace(",", ".", readline());
        $tempLogContent .= $payment . PHP_EOL;
        $change = $payment * 100 - $this->priceSum;

        return [$tempLogContent, $change];
    }

    public function __construct($priceSum, $prefix, string $currency) {
        parent::__construct($priceSum, $prefix);
        $this->currency = $currency;
    }
}
