<?php
function getNameLength(string $name): int
{
    return mb_strlen($name, "UTF-8");
}

function getLongestName (array $basket): int
{
    $lengths = array_map('strlen', array_keys($basket));
    return !empty($lengths) ? max($lengths) : 0;
}

function printLine(int $totalLineWidth, string $character): void
{
    echo str_repeat($character, $totalLineWidth - 2) . PHP_EOL;
}

function containsUmlauts(string $prefix) {
    $chars = str_split($prefix);

    $containsUmlauts = 0;
    foreach ($chars as $char) {
        switch ($char) {
            case 'Ä' || 'ä' || 'Ö' || 'ö' || 'Ü' || 'ü':
                $containsUmlauts = 1;
                break;
            default:
                $containsUmlauts = 0;
        }
    }

    return $containsUmlauts;
}

function printSum($totalLineWidth, $priceSum) {
    printLine($totalLineWidth, "-");
    $priceSums[] = new PriceSumGermany($priceSum, "SUMME");
    echo $priceSums[0]->printPriceSum($totalLineWidth);
    printLine($totalLineWidth, "=");
    echo "Geg. BAR" . str_pad("EUR", 25, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
    $payment = str_replace(",", ".", readline());
    $change = $payment * 100 - $priceSum;
    $priceSums[] = new PriceSumGermany($change, "Rückgeld");
    echo $priceSums[1]->printPriceSum($totalLineWidth, "EUR") . PHP_EOL;
}

function createFilename($fileFormat): string {
    $filename = date("d.m.Y") . "_" . date("H:i") . "_". "change-log-file" . ".$fileFormat";
    return $filename;
}

function createContent($data) {
    $tempContent = $data;
    return $tempContent;
}

function incrementChangeNumberInLogFile($changeNumber) {
    file_put_contents("ChangeNumber.txt", $changeNumber);
}

function saveLog($filename, $content) {
    file_put_contents($filename, $content, FILE_APPEND);
}