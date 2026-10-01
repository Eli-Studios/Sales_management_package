<?php

abstract class SalesReceiptTemplate
{
    public abstract function output(): string;
    
    public static function getLine(int $totalLineWidth, string $character): string {
        return str_repeat($character, $totalLineWidth - 2) . PHP_EOL;
    }
}
