<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\HealthProvider;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/health',
            provider: HealthProvider::class,
            name: 'health_check'
        ),
    ],
    shortName: 'Health'
)]
class Health
{
    public function __construct(
        public readonly string $status = 'ok',
        public readonly ?string $service = null,
        public readonly ?string $time = null,
    ) {
    }
}
