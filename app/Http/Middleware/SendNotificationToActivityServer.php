<?php
/*
 * Copyright © 2021 Buyanov Danila
 * Package: Landing
 */

declare(strict_types=1);

namespace App\Http\Middleware;

use App\ActivityClient\JrpcClientInterface;
use Closure;
use Illuminate\Http\Request;

class SendNotificationToActivityServer
{
    public function __construct(
        private JrpcClientInterface $jrpcClient
    ) {
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->jrpcClient->notify('Main.Activity', [
            'url' => $request->getUri(),
            'user_agent' => $request->userAgent() ?? '',
        ]);

        return $next($request);
    }
}
