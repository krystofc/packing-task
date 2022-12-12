<?php declare(strict_types=1);

namespace App\Packaging;

use App\Entity\Packaging;
use Doctrine\ORM\EntityManager;

class PackagingFacade { // todo should be repository and facade

    function __construct(
        private readonly EntityManager $em,
    ) {
    }

    /** @return Packaging[] */
    public function getAllPacking(): array {
        return $this->em->createQueryBuilder()->select('p')->from('Packaging', 'p')->getQuery()->getResult();
    }
}
