<?php
    //Importing all relevant classes and functions
    require_once 'Products.php';
    require_once 'SoldByPiece.php';
    require_once 'SoldByWeight.php';
    require_once 'Drinks.php';

    //Variables | First declaration and initialization
    $priceSum = 0;
    $taxPercentage = ['A' => 0.19, 'B' => 0.07];

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

    function getProductNameLength(string $name) : int
    {
        return strlen($name);
    }

    function getLongestProductName (array $basket): int
    {
        foreach ($basket as $key => $value) {
            $longest = max($basket);
        }

        return (int)$longest;
    }


//Program logic
    foreach ($basket as $name => $data) {
        foreach ($data as $property => $value) {
            switch ($property) {
                case 'amount':
                    $soldByPiece = new SoldByPiece($name, $data);
                    echo $soldByPiece->name;
                    $productNameLength = getProductNameLength($name);
                    $totalLineWidth = getLongestProductName($basket) + 34;
                    #TODO: PHP-Klasse zur Formatierung von Nummern (https://www.php.net/manual/de/class.numberformatter.php)
                    #TODO: Factory, no seperate methods that have the same principles (--> deposit as a seperate object), drinks as a seperate class,
                    # objects need a more specific  name (currently just the class name :()
                    echo str_pad(number_format(round($soldByPiece->price / 100, 2), 2, ",")
                            . " $soldByPiece->taxClass\n", $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);

                    $drinks = null;
                    foreach ($data as $property => $value) {
                        if ($property == 'depositPerPiece') {
                            $drinks = new Drinks($name, $data);
                            echo strtoupper("  Pfand ");
                            echo number_format($drinks->depositPerPiece / 100, 2, ",") . " EUR "
                                        . str_pad(number_format($drinks->deposit / 100, 2, ",") . " "
                                        . $drinks->taxClass . "\n", 18, " ", STR_PAD_LEFT);
                            echo "  " . $drinks->amount . " Stk x" . "   "
                                        . number_format($drinks->depositPerPiece / 100, 2, ",") . "\n";
                        }
                    }

                    $priceSum += $soldByPiece->price + ($drinks ? $drinks->deposit : 0);
                break;
                case 'weight':
                    $soldByWeight = new SoldByWeight($name, $data); //Das hier muss auch für 52 gelten!!

                    echo strtoupper($soldByWeight->name);
                    $productNameLength = getProductNameLength($name);
                    $totalLineWidth = getLongestProductName($basket) + 34;
                    echo str_pad(number_format(round($soldByWeight->price / 100, 2), 2, ",")
                        . " $soldByWeight->taxClass\n", $totalLineWidth - $productNameLength, " ", STR_PAD_LEFT);
                    $priceSum += $soldByWeight->price;
            }
        }
    }

    echo str_pad("\n", $totalLineWidth, "-", STR_PAD_LEFT);
    echo strtoupper("Summe" . "    EUR  " . number_format($priceSum / 100, 2, ",") . "\n");
    echo str_pad("\n\n\n", $totalLineWidth + 2, "=", STR_PAD_LEFT);
    #echo "Geg. BAR: ";
    #$payedInCache = trim(fgets(STDIN));
    #echo "Geg. BAR          EUR     " . number_format($payedInCache, 2, ",") . "\n";
    #echo "Rückgeld BAR          EUR     " . number_format($payedInCache - $priceSum / 100, 2, ",") . "\n";

    echo str_pad("\n", $totalLineWidth, "*", STR_PAD_LEFT);