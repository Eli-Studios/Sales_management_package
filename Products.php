<?php

abstract class Products
{
   protected string $name;
   public int $price;
   public string $taxClass;

   public function output($totalLineWidth, $productNameLength): string
   {
       return mb_strtoupper($this->name) . str_pad(number_format(round((float)$this->price / 100, 2), 2, ",")
           . " $this->taxClass" . PHP_EOL, $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);
   }

   public function __construct(string $name, array $data)
   {
       $this->name = mb_strtoupper($name);
       $this->taxClass = $data["taxClass"];
   }
}
