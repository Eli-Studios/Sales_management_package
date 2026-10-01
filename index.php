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
$filename = createFilename("txt");
$logContent = [];
$changeNumber = (int)(file_get_contents("ChangeNumber.txt")
        ?: error_log("ChangeNumber.txt doesn't exixt. 
        (That's the file in wgich Sales_managment writes the next Bon.Nr. for the next generated Bon.)"));

//Sets the customerrect timezone
(void)date_default_timezone_get();

//Data arrays
$headerData = json_decode(file_get_contents("./Data/headerData.json"), true);
$footerData = json_decode(file_get_contents("./Data/footerData.json"), true);
$tseData = json_decode(file_get_contents("./Data/tseData.json"), true);
#My data array that contains all the products and it's data a customer wants to buy
$basket = json_decode(file_get_contents("./Data/basket.json"), true);

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
$logContent['Sum'] = SalesReceiptTemplate::getLine($totalLineWidth, "-");

$obj = new PriceSumGermany($priceSum, "SUMME", "EUR")->getPriceSum($totalLineWidth, $priceSum);

$logContent['Sum'] .= $obj->getPriceSum($totalLineWidth, $priceSum);



echo $logContent['Sum'] .= SalesReceiptTemplate::getLine($totalLineWidth, "="); 

$logContent['TaxTable'] = new TaxTable($taxRates, $aBrutto, $bBrutto)->output();

$logContent['TSE'] = new TSE($tseData)->output();

$logContent['Footer'] = new Footer($footerData)->output();

echo $logContent['FooterLine'] = SalesReceiptTemplate::getLine($totalLineWidth, "*");

saveLog($filename, $logContent);
incrementChangeNumberInLogFile($changeNumber + 1);
