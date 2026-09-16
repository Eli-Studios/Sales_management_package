<?php

class Factory
{
    public static function createProduct(string $name, array $data) : object {
        if (isset($data['depositPerPiece'])) {
            $product = new Drinks($name, $data);
        } elseif (isset($data['amount'])) {
            $product = new SoldByPiece($name, $data);
        } elseif (isset($data['weight'])) {
            $product = new SoldByWeight($name, $data);
        } else {
            error_log("Unbekannter Produkttyp");
            throw new InvalidArgumentException("Unbekannter Produkttyp: $name");
        }
        return $product;
    }
}