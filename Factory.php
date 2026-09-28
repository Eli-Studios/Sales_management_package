<?php

class Factory
{
    public static function createProduct(string $name, array $data) : object {
        switch (true) {
            case isset($data['depositPerPiece']):
                $product = new Drinks($name, $data);
                break;
            case isset($data['amount']):
                $product = new SoldByPiece($name, $data);
                break;
            case isset($data['weight']):
                $product = new SoldByWeight($name, $data);
                break;
            default:
                error_log("Unknown product type");
                throw new InvalidArgumentException("Unknown product type: $name");
        }
        return $product;
    }
}