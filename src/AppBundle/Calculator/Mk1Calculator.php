<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

class Mk1Calculator extends GenericCalculator
{
    /**
     * The mk1 model can only return coins of 1.
     */
    protected $model = 'mk1';
    protected $availableCurrencies = [
        'coin1' => 1,
    ];
}

