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

/**Default timezone is Europe/Berlin**/
date_default_timezone_set('Europe/Berlin');

//Data arrays
/**1.Every scanned product is going to be displayed in a table with the name, price, tax class and if needed
 the deposit.**/
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
/**2. The price of every product is going to be summed up to a main sum which is displayed under the products table.**/
$obj = new PriceSumGermany($priceSum, "SUMME", "EUR");

$logContent['Sum'] .= $obj->getPriceSum($totalLineWidth);

echo $logContent['Sum'] .= SalesReceiptTemplate::getLine($totalLineWidth, "=");

/**3. The program stops at the point where you can write in the amount of the payment donated by the customer.**/
/**There are three cases that can happen now:
 * Case 1: The customer pays the exact amount as the total Sum is showing.
 *          What happens? --> The program calculates a change of 0 Euros and prints it under the payment input field.
 *          What to do? --> Just prove read if everything is fine and press ENTER to print the rest of the receipt.
 * Case 2: The customer pays more than he haad to.
 *          What happens? --> The application calculates the change that you have to pay back to the customer and prints it.
 *          What to do? --> Donate the amount of cash printed to the customer and hit ENTER.
 * Case 3: The customer pays less than he should have done.
 *          What happens? --> The amount of change is negative
 *                            and a red info-message tells you how much money the customer has got left to pay.
 *          What to do? --> Tell the customer, that he didn't payed enough and how much he has to pay.
 *                      --> Type the amount of money paid into the input field
 *                          (If you still haven't got enough money repeat the two steps above.)
 *                      --> When everything is fine, the program prints you the change you need to return or that you don't have to donate any change to the customer.
 *                      --> Hit ENTER to print the rest of the Receipt.**/

echo $obj->getPrefixOfGetChange();

$arrLogContent = $obj->getChange(0);

$logContent['Payment'] = $obj->getPrefixOfGetChange() . $arrLogContent[0] . PHP_EOL;

echo $logContent['Change'] = new PriceSumGermany($arrLogContent[1], "Rückgeld", "EUR")->getPriceSum($totalLineWidth);
while($arrLogContent[1] < 0) {
    printf("\033[1m\033[31mEs müssen noch \033[33m%3.2f\033[31m Euro nachgezahlt werden!\033[0m\033[22m " . PHP_EOL, ($arrLogContent[1] * (-1)) / 100); 
    echo $obj->getPrefixOfGetChange();
    $arrLogContent = $obj->getChange($arrLogContent[0]);
    $logContent['Payment'] = $obj->getPrefixOfGetChange() . number_format($arrLogContent[0] / 100, 2, ","). PHP_EOL;
    echo $logContent['Change'] = new PriceSumGermany($arrLogContent[1], "Rückgeld", "EUR")->getPriceSum($totalLineWidth);
}
readline();

/****Side Note:** There is a logging feature, that logs all generated receipts as text files under /Sales_management/logs.
 *                The logs are named as follows: receipt number_hour.minute_day.month.year_change-log-file.txt.**/
$logContent['Change'] .= PHP_EOL;

echo $logContent['TaxTable'] = new TaxTable($taxRates, $aBrutto, $bBrutto)->output();

echo $logContent['TSE'] = new TSE($tseData)->output();

echo $logContent['Footer'] = new Footer($footerData)->output();

echo $logContent['FooterLine'] = SalesReceiptTemplate::getLine($totalLineWidth, "*");

saveLog($filename, $logContent);
incrementChangeNumberInLogFile($changeNumber + 1);
