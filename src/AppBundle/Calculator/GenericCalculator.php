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
    private $MAX_BACKTRACKS = 40;

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
        if (0 == $amount) {
            return new Change();
        } elseif ($amount < 0) {
            return null;
        }
        $change = new Change();
        $availableCurrencies = $this->availableCurrencies;
        if (!count($availableCurrencies)) {
            throw new \Exception('The model doesn\'t hold any currency.');
        }
        // make sure the available currencies are sorted from highest to lowest value
        arsort($availableCurrencies);
        $currencies = array_values($availableCurrencies);
        $currency_names = array_keys($availableCurrencies);
        $num_currencies = count($currencies);
        $factors = array_fill(0, $num_currencies, 0);
        $remainders = array_fill(0, $num_currencies, 0);

        $no_solution = true;

        $safeguard_counter = 0;
        $n = 0;

        while ($safeguard_counter++ < $this->MAX_BACKTRACKS) {
            echo "\$n = $n\n";
            for (; $n < $num_currencies; $n++) {
                $factors[$n] = intdiv($amount, $currencies[$n]);
                $remainders[$n] = $amount % $currencies[$n];
                $amount = (int) $remainders[$n];
            }
            $n--;

            if (!$amount) {
                // no remainder = success: put the results into the
                // Change object and return it
                for ($n = 0; $n < $num_currencies; $n++) {
                    $currency_name = $currency_names[$n];
                    $change->$currency_name = $factors[$n];
                }

                return $change;
            }

            // remove some higher-value currencies (starting from the
            // smallest) and retry dividing, excluding the removed currencies
            $n_ = $n - 1;
            if ($n_ < 0) {
                return null;
            }
            for (; $n_ >= 0 && 0 == $factors[$n_]; $n_--) {
            }
            if ($n_ < 0) {
                return null;
            }
            $factors[$n_]--;
            $remainders[$n_] += $currencies[$n_];
            $amount = (int) $remainders[$n_];
            $n = $n_ + 1;
        }
        throw new \Exception("Backtracking exceeded $this->MAX_BACKTRACKS.");
        
    }
}

