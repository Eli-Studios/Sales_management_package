<?php

class SoldByWeight extends Products
{
    public float $weight;
    public int $pricePerKilogram;
    function calculatePrice(): int
    {
        return $this->price = round($this->pricePerKilogram * ($this->weight / 1000));
    }
    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->weight = $data['weight'];
        $this->pricePerKilogram = $data['pricePerKilogram'];
        $this->price = $this->calculatePrice();
    }
}