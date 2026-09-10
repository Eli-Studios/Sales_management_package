<?php

abstract class Products
{
   public string $name;
   public int $price;
   public string $taxClass;

   abstract function calculatePrice(): int;

   public function __construct(string $name, array $data)
   {
       $this->name = strtoupper($name);
       $this->taxClass = $data["taxClass"];
   }
}