<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

use AppBundle\Model\Change;

class GenericCalculator implements CalculatorInterface
{
    /**
     * Calculators inheriting from this class will need to define their
     * model name and available currency map.
     *
     * The keys of available currencies have to be a subset of the members
     * of the Change class.
     */
    protected $availableCurrencies = [];
    protected $model;

    /**
     * @return string the name of the model
     */
    public function getSupportedModel(): string
    {
        return $this->model;
    }

    /**
     * @param int $amount how much money the customer has inserted into the
     *                    machine
     *
     * @return Change returns a Change object with each currency member set to
     *                the number of coins or bills of that type that will be
     *                returned to the customer
     */
    public function getChange(int $amount): ?Change
    {
        if ($amount < 0) {
            return null;
        } else {
            $change = new Change();
            foreach ($this->availableCurrencies as $currencyName => $currencyValue) {
                $change->$currencyName = intdiv(
                    $amount,
                    $currencyValue
                );
                $amount %= $currencyValue;
            }

            return $amount ? null : $change;
        }
    }
}

