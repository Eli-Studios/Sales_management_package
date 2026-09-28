<?php

class Drinks extends SoldByPiece
{
    private int $depositPerPiece;
    private int $deposit;
    private string $depositLabel;

    public function output($totalLineWidth, $productNameLength): string
    {
        $tempLogContent = parent::output($totalLineWidth, $productNameLength);
        echo $this->depositLabel
            . str_pad(number_format($this->deposit / 100, 2, ",")
                . " " . $this->taxClass . PHP_EOL, $totalLineWidth - mb_strlen($this->depositLabel), " ", STR_PAD_LEFT);
        $tempLogContent .= $this->depositLabel
            . str_pad(number_format($this->deposit / 100, 2, ",")
                . " " . $this->taxClass . PHP_EOL, $totalLineWidth - mb_strlen($this->depositLabel), " ", STR_PAD_LEFT);
        echo "  " . $this->amount . " Stk x" . "    "
            . number_format($this->depositPerPiece / 100, 2, ",") . PHP_EOL;
        $tempLogContent .= "  " . $this->amount . " Stk x" . "    "
            . number_format($this->depositPerPiece / 100, 2, ",") . PHP_EOL;
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