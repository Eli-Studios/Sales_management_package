<?php

class SoldByPiece extends Products
{
    public $amount;
    public $pricePerPiece;
    public $depositPerPiece;
    public $deposit;
    function calculatePrice(): string
    {
        // TODO: Implement calculatePrice() method.
        $this->price = $this->amount * $this->pricePerPiece;
        return $this->price = (string) $this->price;
    }

    function calculateDeposit(): string
    {
        $this->deposit = $this->amount * $this->depositPerPiece;
        return $this->deposit = (string) $this->deposit;
    }
}