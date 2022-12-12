<?php

namespace App;

use App\ApiImplementation\PackagingService;
use App\ApiImplementation\CalculateBinPackingRequest;
use App\ApiImplementation\PackageItem;
use App\Packaging\PackagingFacade;
use App\PackedPackage\PackedPackageFacade;
use App\PackingCalculator\ChainedPackingCalculator;
use App\PackingCalculator\DbCachePackingCalculator;
use App\PackingCalculator\External3DBinPackingCalculator;
use App\PackingCalculator\SimplePackingCalculator;
use Countable;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Application
{
    private PackagingService $binPackingService;

    public function __construct(
        private EntityManager $entityManager,
    ) {
        $packedPackageFacade = new PackedPackageFacade($this->entityManager);
        $packingFacade = new PackagingFacade($this->entityManager);
        $calculator = new ChainedPackingCalculator(
            new DbCachePackingCalculator($packedPackageFacade),
            new External3DBinPackingCalculator($packedPackageFacade),
            new SimplePackingCalculator(),
        );
        $this->binPackingService = new PackagingService($calculator, $packingFacade);
    }

    public function run(RequestInterface $request): ResponseInterface
    {
        // todo: find correct service based on uri
        $request = json_decode($request->getBody(), true);
        if (!isset($request['products']) || !is_countable($request['products']) || count($request['products']) === 0) {
            return new Response(400, ['Content-Type' => 'application/json'], 'Missing product');
        }
        $packageItems = array_map(fn(array $productData): PackageItem => new PackageItem(
            $productData['id'],
            $productData['width'],
            $productData['height'],
            $productData['length'],
            $productData['weight'],
        ), $request['products']);
        $request = new CalculateBinPackingRequest($packageItems);
        $response = $this->binPackingService->handleCalculateBinToPack($request);
        $data = json_encode([
            'width' => $response->getPackaging()->getWidth(),
            'height' => $response->getPackaging()->getHeight(),
            'length' => $response->getPackaging()->getDepth(),
        ]);
        if ($response->getError() !== null) {
            return new Response($response->getError() ?? 500, ['Content-Type' => 'application/json'], json_encode(['error' => $response->getError()]));
        }
        return new Response(200, ['Content-Type' => 'application/json'], $data);
    }

}
