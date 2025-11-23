<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Health;

/**
 * Simple state provider for the Health API resource.
 */
class HealthProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|object|null
    {
        // For collection operation we return an array of DTOs
        return [
            new Health(status: 'ok', service: 'api', time: (new \DateTimeImmutable())->format(DATE_ATOM))
        ];
    }
}
