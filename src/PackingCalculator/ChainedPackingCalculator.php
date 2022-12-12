<?php declare(strict_types=1);

namespace App\PackingCalculator;

use App\Structure\IWithDimensions;

class ChainedPackingCalculator implements IBinCalculator {

    /** @var IBinCalculator[] */
    private array $binCalculators;

    public function __construct(IBinCalculator ...$binCalculator) {
        $this->binCalculators = $binCalculator;
    }

    public function findBoxSize(array $possibleBins, array $itemsToPack): IWithDimensions {
        foreach ($this->binCalculators as $calculator) {
            try {
                return $calculator->findBoxSize($possibleBins, $itemsToPack);
            } catch (PackingCalculatorException $exception) {
                // todo maybe some log here
            }
        }
        throw new NotPossibleException;
    }

}
