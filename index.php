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
    require_once 'TSE.php';

    require 'functions.php';

    //Variables | First declaration and initialization
    $priceSum = 0;
    $taxClass = ['A' => 19, 'B' => 7];
    $aBrutto = 0;
    $bBrutto = 0;
    $filename = createFilename("txt");
    $logContent = [];
    $changeNumber = (int)(file_get_contents("ChangeNumber.txt")
            ?: error_log("ChangeNumber.txt doesn't exixt. 
            (That's the file in wgich Sales_managment writes the next Bon.Nr. for the next generated Bon.)"));

    //Sets the correct timezone
    date_default_timezone_set(date_default_timezone_get());

    //Data arrays
    $headerData = [
            'companyName' => str_pad("REWE", 55, " ", STR_PAD_BOTH),
            'street' => str_pad("Schorndorfer Str. 7", 55, " ", STR_PAD_BOTH),
            'city' => str_pad("73066 Uhingen", 55, " ", STR_PAD_BOTH),
            'phoneNumber' => str_pad("07161-5060346", 55, " ", STR_PAD_BOTH),
            'vatID' => str_pad("UID Nr.:" . "DE812706034", 55, " ", STR_PAD_BOTH)];
    $footerData = [
            'row1' => [
                'Date' => date("d.m.Y"),
                'Time' => date("H:i"),
                'ChangeNumber' => "Bon-Nr.:" . $changeNumber],
            'row2' => [
                'Market' => "Markt:" . "0215",
                'TillNumber' => "Kasse:" . "3",
                'Cashier' => "Bed.:" . "141414"]];
    $tseData = [
            'TSE-Signatur' => wordwrap("ecbCiBu2akyi0bffd4ke71xUj99Y9vdk0AB+/T1iHKn9if4HDNSEPLO9Mtd+rUNJWta/3MTugzP7SS+v2IzPStVt18s36sPkCpPE9zgxGeRKa336e+XSeypfyfnin",
                        33, PHP_EOL . str_repeat(" ", 20), true),
            'TSE-Signaturzähler' => "2358647",
            'TSE-Transaktion' => "1113623",
            'TSE-Start' => "2024-10-12T10:42:33.000",
            'TSE-Stop' => "2024-10-12T10:43:16.000",
            'Seriennummer Kasse' => "REWE:C0:03:5e-c9:12:00:00"];
    #My data array that contains all the products and it's data a customer wants to buy
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

    /*Printing the header of the change including the company name,
      the address, the phone number and the VAT-ID*/
    $logContent['Header'] = new Header($headerData)->output();

    $logContent['ProductList'] = "";
    $totalLineWidth = getLongestName($basket) + 30;
    //Program logic
    foreach ($basket as $name => $data) {
        $product = Factory::createProduct($name, $data);
        $productNameLength = getNameLength($name);
        $logContent['ProductList'] .= $product->output($totalLineWidth, $productNameLength);
        $priceSum += $product->price;

        match ($product->taxClass) {
            'A' => $aBrutto += $product->price,
            'B' => $bBrutto += $product->price,
            default => error_log("Invalid tax class: $product->taxClass"),
        };
    }

    //Printing the footer including the 'sum'-section and the metadata of the change
    $logContent['Sum'] = printSum($totalLineWidth, $priceSum);

    $logContent['TaxTable'] = new TaxTable($taxClass, $aBrutto, $bBrutto)->output();

    $logContent['TSE'] = new TSE($tseData)->output();

    $logContent['Footer'] = new Footer($footerData)->output();

    $logContent['FooterLine'] = printLine($totalLineWidth, "*");

    saveLog($filename, $logContent);
    incrementChangeNumberInLogFile($changeNumber + 1);