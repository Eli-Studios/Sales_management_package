<?php
date_default_timezone_set('Europe/Berlin');

#TODO: Make the function names and variables more reusable.
function getNameLength(string $name): int
{
    return mb_strlen($name, "UTF-8");
}

function getLongestName(array $array): int
{
    $lengths = array_map('mb_strlen', array_keys($array));
    return !empty($lengths) ? max($lengths) : 0;
}

function printSum($totalLineWidth, $priceSum): string
{  
    return $tempLogContent;
}

function createFilename($fileFormat, $changeNumber): string {
    return $changeNumber . "_" . date("H.i") . "_" . date("d.m.Y") . "_" . "change-log-file" . ".$fileFormat";
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

    if(!is_dir('./logs')) {
        mkdir('./logs', 0755, true);
    }
    file_put_contents("./logs/" . $filename, $content);
}
