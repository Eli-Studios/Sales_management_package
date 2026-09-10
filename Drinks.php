<?php

require_once 'SoldByPiece.php';

class Drinks extends SoldByPiece
{
    public int $depositPerPiece;
    public int $deposit;

    function calculatePrice(): int
    {
        return $this->price = $this->amount * $this->pricePerPiece;
    }

    function calculateDeposit(): int
    {
        return $this->deposit = $this->amount * $this->depositPerPiece;
    }

    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->price = $this->calculatePrice();
        $this->depositPerPiece = $data["depositPerPiece"];
        $this->deposit = $this->calculateDeposit();
    }
}