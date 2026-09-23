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
    echo $tempContent = str_repeat($character, $totalLineWidth - 2) . PHP_EOL;
    return $tempContent;
}

function containsUmlauts(string $prefix): int
{
    $chars = str_split($prefix);

    $containsUmlauts = 0;
    foreach ($chars as $char) {
        $containsUmlauts = match ($char) {
            'Ä' || 'ä' || 'Ö' || 'ö' || 'Ü' || 'ü' => 1,
            default => 0,
        };
    }

    return $containsUmlauts;
}

function printSum($totalLineWidth, $priceSum): string
{
    $tempContent = printLine($totalLineWidth, "-");
    $priceSums[] = new PriceSumGermany($priceSum, "SUMME");
    echo $priceSums[0]->printPriceSum($totalLineWidth);
    $tempContent .= $priceSums[0]->printPriceSum($totalLineWidth);
    $tempContent .= printLine($totalLineWidth, "=");
    echo "Geg. BAR" . str_pad("EUR", 25, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
    $tempContent .= "Geg. BAR" . str_pad("EUR", 25, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
    $payment = str_replace(",", ".", readline());
    $tempContent .= $payment . PHP_EOL;
    $change = $payment * 100 - $priceSum;
    $priceSums[] = new PriceSumGermany($change, "Rückgeld");
    echo $priceSums[1]->printPriceSum($totalLineWidth, "EUR") . PHP_EOL;
    $tempContent .= $priceSums[1]->printPriceSum($totalLineWidth, "EUR") . PHP_EOL;
    return $tempContent;
}

function createFilename($fileFormat): string {
    date_default_timezone_set('Europe/Berlin');
    return date("H.i") . "_" . date("d.m.Y") . "_" . "_". "change-log-file" . ".$fileFormat";
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