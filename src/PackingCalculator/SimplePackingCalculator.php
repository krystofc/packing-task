<?php declare(strict_types=1);

namespace App\PackingCalculator;

use App\Structure\IWithDimensions;
use App\Structure\IWithWeight;

class SimplePackingCalculator implements IBinCalculator {

    private const PACKING_MATERIAL = 10; // in percents

    public function findBoxSize(array $possibleBins, array $itemsToPack): IWithDimensions {
        $sumWeight = 0.0;
        $sumVolume = 0.0;
        $maxSize = 0;
        /** @var IWithDimensions|IWithWeight $item */
        foreach ($itemsToPack as $item) {
            $sumVolume += $item->getDepth() * $item->getWidth() * $item->getHeight();
            $sumWeight += $item->getWeight();
            $maxSize = max($item->getDepth(), $item->getDepth(), $item->getHeight(), $maxSize);
        }
        $sumVolume = $sumVolume + ($sumVolume * (self::PACKING_MATERIAL / 100));
        /** @var IWithDimensions|IWithWeight $possibleBin */
        foreach ($possibleBins as $possibleBin) {
            $possibleBinVolume = $item->getDepth() * $item->getWidth() * $item->getWeight();
            if ($sumVolume <= $possibleBinVolume
                && $maxSize <= max($item->getDepth(), $item->getWidth(), $item->getHeight())
                && $possibleBin->getWeight() >= $sumWeight
            ) {
                return $possibleBin;
            }
        }
        throw new NotPossibleException;
    }
}
