<?php
/*
 * Copyright © 2021 Buyanov Danila
 * Package: Landing
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\ActivityClient\JrpcClientInterface;
use Psr\Http\Message\ResponseInterface;

class DashboardController extends Controller
{
    public function __invoke(JrpcClientInterface $jrpcClient)
    {
        /** @var ResponseInterface $response */
        $response = $jrpcClient->notify('Main.ActivityList', [], true);

        try {
            $activityList = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
            $result = $activityList['result'];
        } catch (\JsonException $e) {
        }

        $list = $result['message'] ?? [];

        return view('dashboard', [
            'list' => $list,
        ]);
    }
}
