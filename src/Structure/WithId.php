<?php declare(strict_types=1);

namespace App\Structure;

trait WithId {

    private int $id;

    public function getId(): int {
        return $this->id;
    }
}
