<?php

abstract class Products
{
   protected string $name;
   protected int $price;
   protected string $taxClass;

   public function output($object, $totalLineWidth, $productNameLength): string
   {
       echo $tempContent = strtoupper($object->name);
       echo str_pad(number_format(round((float)$object->price / 100, 2), 2, ",")
           . " $object->taxClass" . PHP_EOL, $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);
       $tempContent .= str_pad(number_format(round((float)$object->price / 100, 2), 2, ",")
           . " $object->taxClass" . PHP_EOL, $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);
       return $tempContent;
   }

   public function __construct(string $name, array $data)
   {
       $this->name = strtoupper($name);
       $this->taxClass = $data["taxClass"];
   }
}