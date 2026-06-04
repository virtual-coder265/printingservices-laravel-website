<?php

namespace App\Services\Erp;

interface ErpClientInterface
{
    public function pullProducts(): array;

    public function pullServices(): array;

    public function pushQuotation(array $payload): array;

    public function pushOrder(array $payload): array;

    public function getJobStatus(string $externalId): ?array;
}
