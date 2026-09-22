<?php

require_once 'SoldByPiece.php';

class Drinks extends SoldByPiece
{
    public int $depositPerPiece;
    public int $deposit;
    public string $depositLabel;

    function calculatePrice(): int
    {
        return $this->price = $this->amount * $this->pricePerPiece;
    }

    function calculateDeposit(): int
    {
        return $this->deposit = $this->amount * $this->depositPerPiece;
    }

    public function output($object, $totalLineWidth, $productNameLength): string
    {
        $tempContent = parent::output($object, $totalLineWidth, $productNameLength);
        echo $this->depositLabel
            . str_pad(number_format($object->deposit / 100, 2, ",")
                . " " . $object->taxClass . "\n", $totalLineWidth - mb_strlen($this->depositLabel), " ", STR_PAD_LEFT);
        $tempContent .= $this->depositLabel
            . str_pad(number_format($object->deposit / 100, 2, ",")
                . " " . $object->taxClass . "\n", $totalLineWidth - mb_strlen($this->depositLabel), " ", STR_PAD_LEFT);
        echo "  " . $object->amount . " Stk x" . "    "
            . number_format($object->depositPerPiece / 100, 2, ",") . "\n";
        $tempContent .= "  " . $object->amount . " Stk x" . "    "
            . number_format($object->depositPerPiece / 100, 2, ",") . "\n";
        $this->price += $this->deposit;
        return $tempContent;
    }

    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->price = $this->calculatePrice();
        $this->depositPerPiece = $data["depositPerPiece"];
        $this->deposit = $this->calculateDeposit();
        $this->depositLabel = "PFAND " . number_format($this->deposit / 100, 2, ",") . " EUR";
    }
}