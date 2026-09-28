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

function printLine(int $totalLineWidth, string $character): string
{
    echo str_repeat($character, $totalLineWidth - 2) . PHP_EOL;
    return str_repeat($character, $totalLineWidth - 2) . PHP_EOL;
}

function printSum($totalLineWidth, $priceSum): string
{
    $tempLogContent = printLine($totalLineWidth, "-");
    $priceSums[0] = new PriceSumGermany($priceSum, "SUMME", "EUR");
    $tempLogContent .= $priceSums[0]->printPriceSum($totalLineWidth);
    $tempLogContent .= printLine($totalLineWidth, "=");
    echo "Geg. BAR" . str_pad("EUR", 25, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
    $tempLogContent .= "Geg. BAR" . str_pad("EUR", 25, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
    $payment = str_replace(",", ".", readline());
    $tempLogContent .= $payment . PHP_EOL;
    $change = $payment * 100 - $priceSum;
    $priceSums[1] = new PriceSumGermany($change, "Rückgeld", "EUR");
    $tempLogContent .= $priceSums[1]->printPriceSum($totalLineWidth) . PHP_EOL;
    return $tempLogContent;
}

function createFilename($fileFormat): string {
    date_default_timezone_set('Europe/Berlin');
    return date("H.i") . "_" . date("d.m.Y") . "_" . "change-log-file" . ".$fileFormat";
}

function incrementChangeNumberInLogFile($changeNumber): void
{
    file_put_contents("ChangeNumber.txt", $changeNumber);
}

function saveLog($filename, $content): void
{
    if (is_array($content)) {
        $content = implode($content);
    }
    file_put_contents("./logs/" . $filename, $content);
}