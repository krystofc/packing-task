<?php declare(strict_types=1);

namespace App\PackingCalculator;

use App\Structure\IWithDimensions;
use App\Structure\IWithWeight;

interface IBinCalculator {

    /**
     * @param IWithDimensions[] $possibleBins
     * @param IWithDimensions[]&IWithWeight[] $itemsToPack
     */
    public function findBoxSize(array $possibleBins, array $itemsToPack): IWithDimensions;
}
