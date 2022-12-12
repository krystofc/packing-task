<?php declare(strict_types=1);

namespace App\PackingCalculator;

use App\BinPacking\BinPackingClient;
use App\BinPacking\ClientException;
use App\BinPacking\Model\Bin;
use App\BinPacking\Model\NotPackedItem;
use App\PackedPackage\PackedPackageFacade;
use App\Structure\IWithDimensions;

class External3DBinPackingCalculator implements IBinCalculator {

    private BinPackingClient $client;

    public function __construct(
        private readonly PackedPackageFacade $packedPackageFacade,
    ) {
        $this->client = new BinPackingClient;
    }

    public function findBoxSize(array $possibleBins, array $itemsToPack): IWithDimensions {
        try {
            $response = $this->client->findABoxSize($possibleBins, $itemsToPack);
        } catch (ClientException $exception) {
            // todo: log
            throw new TemporaryUnavailableException($exception->getMessage(), $exception->getCode(), $exception);
        }
        if (count($response->getNotPackedItem()) > 0) {
            $ids = array_map(fn(NotPackedItem $notPackedItem): int => $notPackedItem->getId(), $response->getNotPackedItem());
            throw new NotPossibleException('Cannot pack products: '. implode(', ', $ids));
        }
        if (count($response->getBins()) === 0) {
            throw new PackingCalculatorException('Api doest response any data');
        }
        $bins = $response->getBins();
        usort($bins, fn(Bin $a, Bin $b) => $a->getVolume() <=> $b->getVolume());
        $bin = reset($bins);
        $this->packedPackageFacade->saveNewPackagedBin($itemsToPack, $bin);
        return $bin;
    }
}
