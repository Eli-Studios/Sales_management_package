<?php

class Drinks extends SoldByPiece
{
    public int $depositPerPiece;
    public int $deposit;
    public string $depositLabel;

    public function output($object, $totalLineWidth, $productNameLength): string
    {
        $tempLogContent = parent::output($object, $totalLineWidth, $productNameLength);
        echo $this->depositLabel
            . str_pad(number_format($object->deposit / 100, 2, ",")
                . " " . $object->taxClass . PHP_EOL, $totalLineWidth - mb_strlen($this->depositLabel), " ", STR_PAD_LEFT);
        $tempLogContent .= $this->depositLabel
            . str_pad(number_format($object->deposit / 100, 2, ",")
                . " " . $object->taxClass . PHP_EOL, $totalLineWidth - mb_strlen($this->depositLabel), " ", STR_PAD_LEFT);
        echo "  " . $object->amount . " Stk x" . "    "
            . number_format($object->depositPerPiece / 100, 2, ",") . PHP_EOL;
        $tempLogContent .= "  " . $object->amount . " Stk x" . "    "
            . number_format($object->depositPerPiece / 100, 2, ",") . PHP_EOL;
        $this->price += $this->deposit;
        return $tempLogContent;
    }

    public function __construct(string $name, array $data)
    {
        parent::__construct($name, $data);
        $this->price = $this->amount * $this->pricePerPiece;
        $this->depositPerPiece = $data["depositPerPiece"];
        $this->deposit = $this->amount * $this->depositPerPiece;
        $this->depositLabel = "PFAND " . number_format($this->deposit / 100, 2, ",") . " EUR";
    }
}