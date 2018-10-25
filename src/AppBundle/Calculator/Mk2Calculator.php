<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

class Mk2Calculator extends GenericCalculator
{
    /**
     * The mk2 model can return bills of 10 or 5 and coins of 2.
     */
    protected $model = 'mk2';
    protected $availableCurrencies = [
        'bill10' => 10,
        'bill5' => 5,
        'coin2' => 2,
    ];
}

