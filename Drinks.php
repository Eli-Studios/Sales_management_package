<?php

class Drinks extends SoldByPiece
{
    public $depositPerPiece;
    public $deposit;
    function calculateDeposit(): string
    {
        $this->deposit = $this->depositPerPiece * $this->amount;
        return $this->deposit = (string) $this->deposit;
    }
}