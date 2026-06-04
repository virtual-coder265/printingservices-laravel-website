<?php

namespace App\Services\Erp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpErpClient implements ErpClientInterface
{
    public function pullProducts(): array
    {
        return $this->get('/catalog/products')['data'] ?? [];
    }

    public function pullServices(): array
    {
        return $this->get('/catalog/services')['data'] ?? [];
    }

    public function pushQuotation(array $payload): array
    {
        return $this->post('/quotations', $payload);
    }

    public function pushOrder(array $payload): array
    {
        return $this->post('/orders', $payload);
    }

    public function getJobStatus(string $externalId): ?array
    {
        $response = $this->get("/jobs/{$externalId}");

        return $response['data'] ?? null;
    }

    protected function get(string $path): array
    {
        return $this->request('get', $path);
    }

    protected function post(string $path, array $payload): array
    {
        return $this->request('post', $path, $payload);
    }

    protected function request(string $method, string $path, array $payload = []): array
    {
        $baseUrl = rtrim(config('erp.base_url'), '/');

        if ($baseUrl === '') {
            return ['success' => false, 'message' => 'ERP base URL not configured.'];
        }

        try {
            $request = Http::timeout((int) config('erp.timeout', 30))
                ->withToken(config('erp.api_key'))
                ->acceptJson();

            $response = $method === 'get'
                ? $request->get("{$baseUrl}{$path}")
                : $request->post("{$baseUrl}{$path}", $payload);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => $response->body(),
                    'status' => $response->status(),
                ];
            }

            return array_merge(['success' => true], $response->json() ?? []);
        } catch (\Throwable $e) {
            Log::error('ERP request failed', ['path' => $path, 'error' => $e->getMessage()]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
