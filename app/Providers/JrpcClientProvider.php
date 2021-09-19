<?php
/*
 * Copyright © 2021 Buyanov Danila
 * Package: Landing
 */

declare(strict_types=1);

namespace App\Providers;

use App\ActivityClient\JrpcClient;
use App\ActivityClient\JrpcClientInterface;
use Illuminate\Support\ServiceProvider;

class JrpcClientProvider extends ServiceProvider
{
    public function boot(): void
    {
        $configPath = dirname(__DIR__) . '/../config/jrpc.php';
        $this->mergeConfigFrom($configPath, 'paradigma');
        $serverUri = config('jrpc.server_uri');

        $this->app->singleton(JrpcClientInterface::class, static fn () => new JrpcClient($serverUri));
    }
}
