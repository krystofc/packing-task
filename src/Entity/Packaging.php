<?php

namespace App\Entity;

use App\Structure\IWithDimensions;
use App\Structure\IWithId;
use App\Structure\IWithWeight;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Packaging implements IWithDimensions, IWithWeight, IWithId
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="float")
     */
    private float $width;

    /**
     * @ORM\Column(type="float")
     */
    private float $height;

    /**
     * @ORM\Column(type="float")
     */
    private float $depth;

    /**
     * @ORM\Column(type="float")
     */
    private float $maxWeight;

    public function __construct(float $width, float $height, float $length, float $maxWeight)
    {
        $this->width = $width;
        $this->height = $height;
        $this->depth = $length;
        $this->maxWeight = $maxWeight;
    }

    public function getId(): ?int {
        return $this->id;
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

    public function getWeight(): float {
        return $this->maxWeight;
    }
}
