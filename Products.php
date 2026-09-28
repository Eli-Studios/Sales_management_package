<?php

abstract class Products
{
   protected string $name;
   public int $price;
   public string $taxClass;

   public function output($totalLineWidth, $productNameLength): string
   {
       echo $tempContent = strtoupper($this->name);
       echo str_pad(number_format(round((float)$this->price / 100, 2), 2, ",")
           . " $this->taxClass" . PHP_EOL, $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);
       $tempContent .= str_pad(number_format(round((float)$this->price / 100, 2), 2, ",")
           . " $this->taxClass" . PHP_EOL, $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);
       return $tempContent;
   }

   public function __construct(string $name, array $data)
   {
       $this->name = strtoupper($name);
       $this->taxClass = $data["taxClass"];
   }
}