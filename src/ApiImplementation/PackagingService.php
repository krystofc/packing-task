<?php declare(strict_types=1);

namespace App\ApiImplementation;

use App\Packaging\PackagingFacade;
use App\PackingCalculator\IBinCalculator;
use App\PackingCalculator\NotPossibleException;
use Exception;

class PackagingService { // todo some interface??

    public function __construct(
        private readonly IBinCalculator $binCalculator,
        private readonly PackagingFacade $packagingFacade,
    ) {
    }

    public function handleCalculateBinToPack(CalculateBinPackingRequest $request): CalculatePackageBinResponse {
        $possibleBins = $this->packagingFacade->getAllPacking();
        try {
            $bin = $this->binCalculator->findBoxSize($possibleBins, $request->getPackageItems());
        } catch (NotPossibleException $exception) {
            return CalculatePackageBinResponse::error($exception->getMessage(), 400);
        } catch (Exception $exception) {
            // todo log
            return CalculatePackageBinResponse::error('Unexpected internal error', 500);
        }
        return CalculatePackageBinResponse::success(Packaging::fromWithDimensions($bin));
    }
}
