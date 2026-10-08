<?php

abstract class SalesReceiptTemplate
{
    protected string $tempLogContent;  

    public abstract function output(): string;
    
    public static function getLine(int $totalLineWidth, string $character): string {
        return str_repeat($character, $totalLineWidth - 2) . PHP_EOL;
    }
}
