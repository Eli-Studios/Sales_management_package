<?php
require_once 'Products.php';
require_once 'SoldByPiece.php';
require_once 'SoldByWeight.php';

$basket = [
    'Schweinebraten' => ['weight' => 0.148, 'pricePerKilogram' => 14.90],
    'Haenchen-Oberschinken' => ['weight' => 0.369, 'pricePerKilogram' => 9,89],
    'Pistazienlyoner' => ['weight' => 0.108, 'pricePerKilogram' => 9.90],
    'Bio Casewkerne' => ['amount' => 1, 'pricePerPiece' => 3.59],
    'Pakchoi Mini' => ['amount' => 1, 'pricePerPiece' => 1.69],
    'Himalaya Basmati' => ['amount' => 1, 'pricePerPiece' => 2.29],
    'Bar Style Mix' => ['amount' => 1, 'pricePerPiece' => 1.39],
    'Limo' => ['amount' => 2, 'pricePerPiece' => 0.95, 'deposit' => 0.08],
    'Fanta Mango Drachenfrucht' => ['amount' => 2, 'pricePerPiece' => 2.38],
    'Gemuesebuersten' => ['amount' => 1, 'PricePerPiece' => 6.00]];



foreach ($basket as $name => $data) {
    foreach ($data as $property => $price) {
        if ($property === 'pricePerPiece') {
            $soldByWeight = new SoldByPiece();
            $soldByWeight->name = $name;
            $soldByWeight->pricePerKilogram = $price;
            $soldByWeight->price = $soldByWeight->calculatePrice();


        }
    }
}
