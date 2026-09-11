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

    public function output($object, $totalLineWidth, $productNameLength): void
    {
        echo "  PFAND " . number_format($object->depositPerPiece / 100, 2, ",") . " EUR "
            . str_pad(number_format($object->deposit / 100, 2, ",")
                . $object->taxClass . "\n", $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);
        echo "  " . $object->amount . " Stk x" . "   "
            . number_format($object->depositPerPiece / 100, 2, ",") . "\n";
    }

    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->price = $this->calculatePrice();
        $this->depositPerPiece = $data["depositPerPiece"];
        $this->deposit = $this->calculateDeposit();
    }
}