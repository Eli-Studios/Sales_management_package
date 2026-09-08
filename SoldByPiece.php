<?php

class SoldByPiece extends Products
{
    public $amount;
    public $pricePerPiece;
    function calculatePrice(): string
    {
        // TODO: Implement calculatePrice() method.
        $this->price = $this->amount *= $this->pricePerPiece;
        return $this->price = (string) $this->price;
    }
}