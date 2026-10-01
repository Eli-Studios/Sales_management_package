<?php
date_default_timezone_set('Europe/Berlin');

function getNameLength(string $name): int
{
    return mb_strlen($name, "UTF-8");
}

function getLongestName (array $basket): int
{
    $lengths = array_map('mb_strlen', array_keys($basket));
    return !empty($lengths) ? max($lengths) : 0;
}

#TODO: Is there a way to not print the user input again?
function printSum($totalLineWidth, $priceSum): string
{  
    return $tempLogContent;
}

function createFilename($fileFormat): string {
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

    if(!is_dir('./logs')) {
        mkdir('./logs', 0755, true);
    }
    file_put_contents("./logs/" . $filename, $content);
}
