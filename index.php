<?php
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

require_once 'functions.php';

//Variables | First declaration and initialization
$priceSum = 0;
$taxRates = ['A' => 19, 'B' => 7];
$aBrutto = 0;
$bBrutto = 0;
$logContent = [];
$changeNumberRaw = @file_get_contents("ChangeNumber.txt");
if ($changeNumberRaw === false) {
    error_log("ChangeNumber.txt doesn't exist.
        (That's the file in which Sales_managment writes the next Bon.Nr. for the next generated Bon.)");
    $changeNumber = 0;
} else {
    $changeNumber = (int)$changeNumberRaw;
}
$filename = createFilename("txt", $changeNumber);

//Sets the customerrect timezone
date_default_timezone_set('Europe/Berlin');

//Data arrays
$headerData = json_decode(file_get_contents("./Data/headerData.json"), true);
$footerData = json_decode(file_get_contents("./Data/footerData.json"), true);
$footerData['row1']['Date'] = date('d.m.Y');
$footerData['row1']['Time'] = date('H:i');
$footerData['row1']['ChangeNumber'] .= (string)$changeNumber;
$tseData = json_decode(file_get_contents("./Data/tseData.json"), true);
$basket = json_decode(file_get_contents("./Data/basket.json"), true);

/*Printing the header of the change including the company name,
  the address, the phone number and the VAT-ID*/
echo $logContent['Header'] = new Header($headerData)->output();

$logContent['ProductList'] = "";
$totalLineWidth = getLongestName($basket) + 30;
//Program logic
$i = 0;
foreach ($basket as $name => $data) {
    $product = Factory::createProduct($name, $data);
    echo $output = $product->output($totalLineWidth, getNameLength($name));
    $logContent['ProductList'] .= $output;
    $priceSum += $product->price;
    match ($product->taxClass) {
        'A' => $aBrutto += $product->price,
        'B' => $bBrutto += $product->price,
        default => error_log("Invalid tax class: $product->taxClass"),
    };
    $i++;
}

//Printing the footer including the 'sum'-section and the metadata of the change
$logContent['Sum'] = SalesReceiptTemplate::getLine($totalLineWidth, "-");

$obj = new PriceSumGermany($priceSum, "SUMME", "EUR");

$logContent['Sum'] .= $obj->getPriceSum($totalLineWidth);

echo $logContent['Sum'] .= SalesReceiptTemplate::getLine($totalLineWidth, "=");

echo $obj->getPrefixOfGetChange();
$arrLogContent = $obj->getChange();

$logContent['Payment'] = $obj->getPrefixOfGetChange() . $arrLogContent[0] . PHP_EOL;

echo $logContent['Change'] = new PriceSumGermany($arrLogContent[1], "Rückgeld", "EUR")->getPriceSum($totalLineWidth);
readline();
$logContent['Change'] .= PHP_EOL;

echo $logContent['TaxTable'] = new TaxTable($taxRates, $aBrutto, $bBrutto)->output();

echo $logContent['TSE'] = new TSE($tseData)->output();

echo $logContent['Footer'] = new Footer($footerData)->output();

echo $logContent['FooterLine'] = SalesReceiptTemplate::getLine($totalLineWidth, "*");

saveLog($filename, $logContent);
incrementChangeNumberInLogFile($changeNumber + 1);
