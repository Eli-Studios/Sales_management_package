<?php

class Factory
{
    public static function createProduct(string $name, array $data) : object {
        match (true) {
          isset($data['depositPerPiece']) =>
                $product = new Drinks($name, $data),
          isset($data['amount']) =>
                $product = new SoldByPiece($name, $data),
          isset($data['weight']) =>
                $product = new SoldByWeight($name, $data),
          default => error_log("Unknown product type"),
        };
        return $product;
    }
}
