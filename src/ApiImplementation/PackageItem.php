<?php declare(strict_types=1);

namespace App\ApiImplementation;

use App\Structure\DimensionsWithWithWeight;
use App\Structure\IWithId;
use App\Structure\WithId;

class PackageItem extends DimensionsWithWithWeight implements IWithId {

    use WithId;

    public function __construct(int $id, float $width, float $height, float $depth, float $weight) {
        parent::__construct($width, $height, $depth, $weight);
        $this->id = $id;
    }
}
