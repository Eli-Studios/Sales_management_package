<?php

class SoldByPiece extends Products
{
    public int $amount;
    public int $pricePerPiece;
    function calculatePrice(): int
    {
        return $this->price = $this->amount * $this->pricePerPiece;
    }
    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->amount = $data["amount"];
        $this->pricePerPiece = $data["pricePerPiece"];
        $this->price = $this->calculatePrice();
    }
}