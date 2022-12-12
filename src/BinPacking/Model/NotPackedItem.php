<?php declare(strict_types=1);

namespace App\BinPacking\Model;

use App\Structure\IWithId;
use App\Structure\WithId;

class NotPackedItem implements IWithId {

    use WithId;

    public function __construct(
        int $id
    ) {
        $this->id = $id;
    }
}
