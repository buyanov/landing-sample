<?php
/*
 * Copyright © 2021 Buyanov Danila
 * Package: Landing
 */

declare(strict_types=1);

namespace App\ActivityClient;

interface JrpcClientInterface
{
    /**
     * @param string $method
     * @param array<string, string> $params
     * @param bool $wait
     */
    public function notify(string $method, array $params, bool $wait = false): mixed;
}
