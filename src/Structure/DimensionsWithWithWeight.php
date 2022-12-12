<?php declare(strict_types=1);

namespace App\Structure;

abstract class DimensionsWithWithWeight extends Dimensions implements IWithWeight {

    public function __construct(
        float $width,
        float $height,
        float $depth,
        private float $weight,
    ) {
        parent::__construct($width, $height, $depth);
    }

    public function getWeight(): float {
        return $this->weight;
    }
}
