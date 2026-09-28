<?php

class SoldByPiece extends Products
{
    private int $amount;
    private int $pricePerPiece;
    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->amount = $data["amount"];
        $this->pricePerPiece = $data["pricePerPiece"];
        $this->price = $this->amount * $this->pricePerPiece;
    }
}