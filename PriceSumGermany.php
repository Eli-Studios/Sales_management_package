<?php

class PriceSumGermany extends PriceSumOperations
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

    public function getChange(int $payment): array
    { 
	$tempPayment = readline();    
	if ((str_contains($tempPayment, ",") OR str_contains($tempPayment, ".")) == false) {
		$tempPayment = (int)$tempPayment;
		$tempPayment *= 100;
	} elseif (mb_strlen(str_replace([",", "."], "", strpbrk($tempPayment, ",."))) < 2) {
		$tempPayment .= "0";
	} elseif (mb_strlen(str_replace([",", "."], "", strpbrk($tempPayment, ",."))) > 2) {
		$tempPayment = substr_replace($tempPayment, "", (2 - (int)(mb_strlen(str_replace([",", "."], "", strpbrk($tempPayment, ",."))))));
	}
        $payment += (int)str_replace([",", "."], "", (string)$tempPayment); 
        return [$payment, $payment - $this->priceSum];
    }

    public function __construct($priceSum, $prefix, string $currency) {
        parent::__construct($priceSum, $prefix);
        $this->currency = $currency;
    }
}
