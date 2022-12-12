<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PackedPackage {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Packaging")
     * @ORM\JoinColumn(name="packing_id", referencedColumnName="id", nullable=false)
     */
    private Packaging $packaging;

    public function getPackaging(): Packaging {
        return $this->packaging;
    }
}
