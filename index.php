<?php
    //Importing all relevant classes and functions
    require_once 'Products.php';
    require_once 'SoldByPiece.php';
    require_once 'SoldByWeight.php';

    //Variables | First declaration and initialisation
    $priceSum = 0;

    //My data array that contains all the products and it's data a customer wants to buy
    $basket = [
        'Schweinebraten' => ['weight' => 0.148, 'pricePerKilogram' => 1490, 'taxClass' => "B"],
        'Pistazienlyoner' => ['weight' => 0.108, 'pricePerKilogram' => 990, 'taxClass' => "B"],
        'Bio Cashewkerne' => ['amount' => 1, 'pricePerPiece' => 359, 'taxClass' => "B"],
        'Pakchoi Mini' => ['amount' => 1, 'pricePerPiece' => 169, 'taxClass' => "B"],
        'Himalaya Basmati' => ['amount' => 1, 'pricePerPiece' => 229, 'taxClass' => "B"],
        'Bar Style Mix' => ['amount' => 1, 'pricePerPiece' => 169, 'taxClass' => "B"],
        'Kinderschokolade' => ['amount' => 1, 'pricePerPiece' => 139, 'taxClass' => "B"],
        'Limo' => ['amount' => 2, 'pricePerPiece' => 95, 'taxClass' => "A", 'depositPerPiece' => 8],
        'Fanta Mango Drachenfrucht' => ['amount' => 2, 'pricePerPiece' => 119, 'taxClass' => "A", 'depositPerPiece' => 15],
        'Gemuesebuersten' => ['amount' => 1, 'pricePerPiece' => 600, 'taxClass' => "A"]];

    //Program logic
    foreach ($basket as $name => $data) {
        foreach ($data as $property => $value) {
            switch ($property) {
                case 'amount':
                    $soldByPiece = new SoldByPiece();
                    $soldByPiece->name = $name;
                    $soldByPiece->amount = $value;
                    $soldByPiece->pricePerPiece = $data["pricePerPiece"];
                    $soldByPiece->price = $soldByPiece->calculatePrice();
                    $soldByPiece->taxClass = $data["taxClass"];

                    echo strtoupper($soldByPiece->name) . "    ";
                    echo number_format(round((float)$soldByPiece->price / 100, 2), 2, ",") . " $soldByPiece->taxClass\n";

                    foreach ($data as $property => $value) {
                        if ($property == 'depositPerPiece') {
                            $soldByPiece->depositPerPiece = $data["depositPerPiece"];
                            $soldByPiece->deposit = $soldByPiece->calculateDeposit();
                            echo strtoupper("  Pfand " . number_format($soldByPiece->depositPerPiece / 100) . " EUR") . "    " . $soldByPiece->deposit . " " . $soldByPiece->taxClass . "\n";
                            echo "  " . $soldByPiece->amount . " Stk x" . "   " . number_format($soldByPiece->depositPerPiece / 100, 2, ",") . "\n";
                        }
                    }

                    $priceSum += (int)$soldByPiece->price;
                break;
                case 'weight':
                    $soldByWeight = new SoldByWeight(); //Das hier muss auch für 52 gelten!!
                    $soldByWeight->name = $name;
                    $soldByWeight->weight = $value;
                    $soldByWeight->pricePerKilogram = $data['pricePerKilogram'];
                    $soldByWeight->price = $soldByWeight->calculatePrice();
                    $soldByWeight->taxClass = $data["taxClass"];

                    echo strtoupper($soldByWeight->name) . "    ";
                    echo number_format(round((float)$soldByWeight->price / 100, 2), 2, ",") . " $soldByWeight->taxClass\n";
                    $priceSum += (int)$soldByWeight->price;
            }
        }
    }

    echo "----------------------------------------------------------------------------------\n";
    echo strtoupper("Summe" . "    EUR  " . number_format($priceSum /100, 2, ",") . "\n");
    echo "==================================================================================";
