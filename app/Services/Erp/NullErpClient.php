<?php

namespace App\Services\Erp;

class NullErpClient implements ErpClientInterface
{
    public function pullProducts(): array
    {
        return [];
    }

    public function pullServices(): array
    {
        return [];
    }

    public function pushQuotation(array $payload): array
    {
        return [
            'success' => true,
            'external_id' => 'DEV-QUO-'.($payload['reference'] ?? uniqid()),
            'message' => 'ERP sync disabled — development stub response.',
        ];
    }

    public function pushOrder(array $payload): array
    {
        return [
            'success' => true,
            'external_id' => 'DEV-ORD-'.($payload['reference'] ?? uniqid()),
            'message' => 'ERP sync disabled — development stub response.',
        ];
    }

    public function getJobStatus(string $externalId): ?array
    {
        return null;
    }
}
