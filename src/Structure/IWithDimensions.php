<?php declare(strict_types=1);

namespace App\Structure;

interface IWithDimensions {

    public function getWidth(): float;
    public function getHeight(): float;
    public function getDepth(): float;
}
