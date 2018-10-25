<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Registry\CalculatorRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/automaton/{model}/change/{amount}")
     */
    public function calculateChange(string $model, int $amount)
    {
        $calculatorRegistry = new CalculatorRegistry();
        $calculator = $calculatorRegistry->getCalculatorFor($model);
        if (is_null($calculator)) {
            //throw new NotFoundHttpException('The model does not exist.');
            return new Response(
                'The model does not exist.',
                404
            );
        }
        $change = $calculator->getChange($amount);
        if (!$change) {
            return new Response(
                '',
                204
            );
        }

        return new Response(
            json_encode($change),
            200
        );
    }
}
