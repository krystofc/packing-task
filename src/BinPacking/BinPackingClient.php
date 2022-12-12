<?php declare(strict_types=1);

namespace App\BinPacking;

use App\BinPacking\Model\Bin;
use App\BinPacking\Model\BinPackingResponse;
use App\BinPacking\Model\NotPackedItem;
use App\Structure\IWithDimensions;
use App\Structure\IWithId;
use App\Structure\IWithWeight;
use App\Structure\WithId;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use ValueError;

class BinPackingClient {

    private const BASE_URI = 'http://eu.api.3dbinpacking.com/';
    private const USERNAME = '';
    private const API_KEY = '';
    private const TIMEOUT = 5;

    private ?Client $client = null;

    /**
     * @param IWithDimensions[]&IWithWeight[]&WithId[] $possibleBins
     * @param IWithDimensions[]&IWithWeight[]&WithId[] $itemsToPack
     */
    public function findABoxSize(array $possibleBins, array $itemsToPack): BinPackingResponse {
        $query = $this->mapRequest($possibleBins, $itemsToPack, self::USERNAME, self::API_KEY);
        $query = json_encode($query);
        $request = new Request('POST', 'packer/findBinSize', [], $query);
        try {
            $response = $this->getClient()->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            throw new ClientException('Api failed', 0, $exception);
        }
        $responseBody = (string) $response->getBody();
        try {
            $data = json_decode($responseBody, true, JSON_THROW_ON_ERROR);
        } catch (ValueError $exception) {
            throw new ClientException('Cannot parse response data: ' . $exception->getMessage(), $exception->getCode(), $exception);
        }
        if (isset($data['response']['status']) === 0) {
            $errors = implode(',', $data['response']['errors']);
            throw new ClientException('Server returns some errors: ' . $errors);
        }
        if (!isset($data['response']['bins_packed'])) {
            throw new ClientException('Unexpected response: ' . $responseBody);
        }
        $packedBins = $data['response']['bins_packed'];
        $bins = array_map(fn(array $binData): Bin => new Bin($binData['w'], $binData['h'], $binData['d']), $data['response']['bins_packed']);
        $notPacked = array_map(fn(array $notPacked) => new NotPackedItem($notPacked['id']), $data['response']['not_packed_items']);
        return new BinPackingResponse($bins, $notPacked);
    }

    private function getClient(): Client {
        if ($this->client === null) {
            $this->client = new Client([
                'base_uri' => self::BASE_URI,
                'timeout' => self::TIMEOUT,
            ]);
        }
        return $this->client;
    }

    /**
     * @param IWithDimensions[]&IWithWeight[]&WithId[] $possibleBins
     * @param IWithDimensions[]&IWithWeight[]&WithId[] $itemsToPack
     */
    private function mapRequest(array $possibleBins, array $itemsToPack, string $username, string $apiKey): array {
        $bins = array_map($this->mapDimensions(...), $possibleBins);
        $items = array_map($this->mapDimensions(...), $itemsToPack);
        return [
            'username' => $username,
            'api_key' => $apiKey,
            'bins' => $bins,
            'items' => $items,
        ];
    }

    private function mapDimensions(IWithDimensions&IWithWeight&IWithId $dimensions): array {
        return [
            'w' => $dimensions->getWidth(),
            'h' => $dimensions->getHeight(),
            'd' => $dimensions->getDepth(),
            'wg' => $dimensions->getWeight(),
            'id' => $dimensions->getId()
        ];
    }
}
