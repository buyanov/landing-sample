<?php
/*
 * Copyright © 2021 Buyanov Danila
 * Package: Landing
 */

declare(strict_types=1);

namespace App\ActivityClient;

use Http\Client\HttpAsyncClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use JsonException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class JrpcClient implements JrpcClientInterface
{
    private string $jrpcServerUri;
    private HttpAsyncClient $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;

    public function __construct(string $jrpcServerUri)
    {
        $this->jrpcServerUri = $jrpcServerUri;
        $this->httpClient =  HttpAsyncClientDiscovery::find();
        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
    }

    /**
     * @param string $method
     * @param array<string, string> $params
     * @param bool $wait
     *
     * @return mixed
     *
     * @throws JsonException
     * @throws \Exception
     */
    public function notify(string $method, array $params, bool $wait = false): mixed
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
        ];

        return $this->httpClient->sendAsyncRequest(
            $this->requestFactory
                ->createRequest(Request::METHOD_POST, $this->jrpcServerUri)
                ->withHeader('Content-type', 'application/json')
                ->withBody(
                    $this->streamFactory->createStream(
                        json_encode($data, JSON_THROW_ON_ERROR)
                    )
                )
        )
            ->then(fn (ResponseInterface $response) => $response)
            ->wait($wait);
    }
}
