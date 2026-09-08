<?php

abstract class Products
{
   public $name;
   public $price;
   public $taxClass;

   abstract function calculatePrice(): string;
}