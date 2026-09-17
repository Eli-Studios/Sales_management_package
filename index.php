﻿<?php
    //Importing all relevant classes and functions
    require_once 'Products.php';
    require_once 'SoldByPiece.php';
    require_once 'SoldByWeight.php';
    require_once 'Drinks.php';
    require_once 'Factory.php';
    require_once 'PriceSumOperations.php';
    require_once 'PriceSumGermany.php';
    require_once 'SalesReceiptTemplate.php';
    require_once 'Header.php';
    require_once 'TaxTable.php';
    require_once 'Footer.php';

    //Variables | First declaration and initialization
    $priceSum = 0;
    $taxClass = ['A' => 19, 'B' => 7];
    $aBrutto = 0;
    $bBrutto = 0;

    $headerData = [
            'companyName' => "REWE",
            'street' => "Schorndorfer Str. 7",
            'zipCode' => 73066,
            'city' => "Uhingen",
            'phoneNumber' => "07164-5060346",
            'vatID' => "DE812706034"];
    $footerData = [
            'row1' => [
                'Date' => date("d.m.Y"),
                'Time' => date("H:i"),
                'ChangeNumber' => "Bon-Nr.:9082"],
            'row2' => [
                'Market' => "Markt:0215",
                'TillNumber' => "Kasse:3",
                'Cashier' => "Bed.:141414"]];

    //My data array that contains all the products and it's data a customer wants to buy
    $basket = [
        'Schweinebraten' => ['weight' => 148, 'pricePerKilogram' => 1490, 'taxClass' => "B"],
        'Hae-Obersch.' => ['weight' => 369, 'pricePerKilogram' => 990, 'taxClass' => "B"],
        'Pistazienlyoner' => ['weight' => 108, 'pricePerKilogram' => 990, 'taxClass' => "B"],
        'Bio Cashewkerne' => ['amount' => 1, 'pricePerPiece' => 359, 'taxClass' => "B"],
        'Pakchoi Mini' => ['amount' => 1, 'pricePerPiece' => 169, 'taxClass' => "B"],
        'Himalaya Basmati' => ['amount' => 1, 'pricePerPiece' => 229, 'taxClass' => "B"],
        'Bar Style Mix' => ['amount' => 1, 'pricePerPiece' => 169, 'taxClass' => "B"],
        'Kinderschokolade' => ['amount' => 1, 'pricePerPiece' => 139, 'taxClass' => "B"],
        'Limo' => ['amount' => 2, 'pricePerPiece' => 95, 'taxClass' => "A", 'depositPerPiece' => 8],
        'Fanta Mango Drachenfrucht' => ['amount' => 2, 'pricePerPiece' => 119, 'taxClass' => "A", 'depositPerPiece' => 15],
        'Gemuesebuersten' => ['amount' => 1, 'pricePerPiece' => 600, 'taxClass' => "A"]];

    #TODO: Functions in --> functions.inc.php
    function getProductNameLength(string $name): int
    {
        return mb_strlen($name, "UTF-8");
    }

    function getLongestProductName (array $basket): int
    {
        $lengths = array_map('strlen', array_keys($basket));
        return !empty($lengths) ? max($lengths) : 0;
    }

    function printLine(int $totalLineWidth, string $character): void
    {
        echo str_repeat($character, $totalLineWidth) . PHP_EOL;
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

    $salesReceipt = new Header($headerData);
    $salesReceipt->output();

    //Program logic
    foreach ($basket as $name => $data) {
        $product = Factory::createProduct($name, $data);
        $productNameLength = getProductNameLength($name);
        $totalLineWidth = getLongestProductName($basket) + 24;
        #TODO: PHP-Klasse zur Formatierung von Nummern (https://www.php.net/manual/de/class.numberformatter.php)
        #TODO: Factory, no seperate methods that have the same principles (--> deposit as a seperate object), drinks as a seperate class,
        # objects need a more specific  name (currently just the class name :()
        $product->output($product, $totalLineWidth, $productNameLength);
        $priceSum += $product->price;

        match ($product->taxClass) {
            'A' => $aBrutto += $product->price,
            'B' => $bBrutto += $product->price,
            default => error_log("Invalid tax class: $product->taxClass"),
        };
    }

    printLine($totalLineWidth, "-");
    $priceSums[] = new PriceSumGermany($priceSum, "SUMME");
    echo $priceSums[0]->printPriceSum($totalLineWidth);
    printLine($totalLineWidth, "=");
    echo "Geg. BAR" . str_pad("EUR", 19, " ", STR_PAD_LEFT) . str_pad(" ", 14, " ", STR_PAD_LEFT);
    $payment = str_replace(",", ".", readline());
    $change = $payment * 100 - $priceSum;
    $priceSums[] = new PriceSumGermany($change, "Rückgeld");
    echo $priceSums[1]->printPriceSum($totalLineWidth, "EUR") . PHP_EOL;

    $taxTable = new TaxTable($taxClass, $aBrutto, $bBrutto);
    $taxTable->output($taxClass);

    $footer = new Footer($footerData);
    $footer->output($footerData);

    printLine($totalLineWidth, "*");