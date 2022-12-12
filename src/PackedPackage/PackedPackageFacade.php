<?php declare(strict_types=1);

namespace App\PackedPackage;

use App\Entity\PackedPackage;
use App\Structure\DimensionsWithWithWeight;
use App\Structure\IWithDimensions;
use Doctrine\ORM\EntityManager;

class PackedPackageFacade { // todo should be repository and facade

    public function __construct(
        private readonly EntityManager $entityManager,
    ) {
    }

    /**
     * @param DimensionsWithWithWeight[] $dimensionsList
     *
     * @return PackedPackage
     */
    public function findPackageByDimensionsList(array $dimensionsList): ?PackedPackage {
        return null; // todo
    }

    public function saveNewPackagedBin(array $items, IWithDimensions $package): void {
        // todo
    }
}
