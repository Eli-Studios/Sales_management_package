<?php

abstract class Products
{
   public string $name;
   public int $price;
   public string $taxClass;

   abstract function calculatePrice(): int;
   public function output($object, $totalLineWidth, $productNameLength): void
   {
       echo strtoupper($object->name);
       echo str_pad(number_format(round((float)$object->price / 100, 2), 2, ",") . " $object->taxClass\n", $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);;
   }

   public function __construct(string $name, array $data)
   {
       $this->name = strtoupper($name);
       $this->taxClass = $data["taxClass"];
   }
}