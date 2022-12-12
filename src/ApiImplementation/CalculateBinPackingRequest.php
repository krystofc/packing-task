<?php declare(strict_types=1);

namespace App\ApiImplementation;

class CalculateBinPackingRequest {

    /** @param PackageItem[] $packageItems */
    function __construct(
        private array $packageItems,
    ) {
    }

    public function getPackageItems(): array {
        return $this->packageItems;
    }
}
