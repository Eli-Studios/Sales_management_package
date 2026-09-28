<?php

class SoldByWeight extends Products
{
    private float $weight;
    private int $pricePerKilogram;
    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->weight = $data['weight'];
        $this->pricePerKilogram = $data['pricePerKilogram'];
        $this->price = round($this->pricePerKilogram * ($this->weight / 1000));
    }
}