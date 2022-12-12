<?php declare(strict_types=1);

namespace App\Structure;

abstract class Dimensions implements IWithDimensions {

    public function __construct(
        private float $width,
        private float $height,
        private float $depth,
    ) {}

    public static function fromWithDimensions(IWithDimensions $withDimensions): static {
        return new static($withDimensions->getWidth(), $withDimensions->getHeight(), $withDimensions->getDepth());
    }

    public function getWidth(): float {
        return $this->width;
    }

    public function getHeight(): float {
        return $this->height;
    }

    public function getDepth(): float {
        return $this->depth;
    }

    public function getVolume(): float {
        return $this->width * $this->height * $this->depth;
    }
}
