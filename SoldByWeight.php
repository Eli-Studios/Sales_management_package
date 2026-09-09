<?php

class SoldByWeight extends Products
{
    public $weight;
    public $pricePerKilogram;
    function calculatePrice(): string
    {
        $this->price = $this->pricePerKilogram * $this->weight;
        return $this->price = (string) $this->price;
    }
}