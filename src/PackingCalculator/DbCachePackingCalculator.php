<?php declare(strict_types=1);

namespace App\PackingCalculator;

use App\PackedPackage\PackedPackageFacade;
use App\Structure\IWithDimensions;
use App\Structure\IWithWeight;

class DbCachePackingCalculator implements IBinCalculator {

    public function __construct(
        private readonly PackedPackageFacade $packedPackageFacade,
    ) {
    }

    public function findBoxSize(array $possibleBins, array $itemsToPack): IWithDimensions&IWithWeight {
        // todo: possible bins changed?
        $packagedPackage = $this->packedPackageFacade->findPackageByDimensionsList($itemsToPack);
        if ($packagedPackage === null) {
            throw new NotPossibleException;
        }
        return $packagedPackage->getPackaging();
    }
}
