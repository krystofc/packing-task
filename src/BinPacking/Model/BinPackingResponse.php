<?php declare(strict_types=1);

namespace App\BinPacking\Model;

class BinPackingResponse {

    function __construct(
        private readonly array $bins,
        private readonly array $notPackedItem,
    ) {}

    public function getBins(): array {
        return $this->bins;
    }

    public function getNotPackedItem(): array {
        return $this->notPackedItem;
    }
}
