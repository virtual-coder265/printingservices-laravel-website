<?php

namespace App\Services\Erp;

use App\Models\ErpSyncLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Service;
use Illuminate\Support\Str;

class ErpSyncService
{
    public function __construct(
        protected ErpClientInterface $client
    ) {}

    public function syncProductsFromErp(): int
    {
        $items = $this->client->pullProducts();
        $count = 0;

        foreach ($items as $item) {
            Product::updateOrCreate(
                ['external_erp_id' => $item['id'] ?? null],
                [
                    'sku' => $item['sku'] ?? Str::upper(Str::random(8)),
                    'title' => $item['title'] ?? 'Untitled Product',
                    'slug' => Str::slug($item['title'] ?? 'product-'.uniqid()),
                    'description' => $item['description'] ?? null,
                    'price' => $item['price'] ?? 0,
                    'vat_rate' => $item['vat_rate'] ?? 17.50,
                    'is_active' => $item['is_active'] ?? true,
                    'erp_synced_at' => now(),
                ]
            );
            $count++;
        }

        $this->log('inbound', 'product', null, 'success', null, $items);

        return $count;
    }

    public function syncServicesFromErp(): int
    {
        $items = $this->client->pullServices();
        $count = 0;

        foreach ($items as $item) {
            Service::updateOrCreate(
                ['external_erp_id' => $item['id'] ?? null],
                [
                    'service_category_id' => 1,
                    'title' => $item['title'] ?? 'Untitled Service',
                    'slug' => Str::slug($item['title'] ?? 'service-'.uniqid()),
                    'description' => $item['description'] ?? null,
                    'base_price' => $item['base_price'] ?? null,
                    'is_active' => $item['is_active'] ?? true,
                    'requires_quotation' => true,
                    'erp_synced_at' => now(),
                ]
            );
            $count++;
        }

        $this->log('inbound', 'service', null, 'success', null, $items);

        return $count;
    }

    public function pushQuotation(Quotation $quotation): bool
    {
        $quotation->load(['items.service', 'customerProfile.user', 'customerProfile.organization']);

        $payload = [
            'reference' => $quotation->reference,
            'status' => $quotation->status,
            'customer' => [
                'name' => $quotation->customerProfile->user->name,
                'email' => $quotation->customerProfile->user->email,
                'organization' => $quotation->customerProfile->organization?->name,
            ],
            'subtotal' => $quotation->subtotal,
            'vat_amount' => $quotation->vat_amount,
            'total_amount' => $quotation->total_amount,
            'items' => $quotation->items->map(fn ($item) => [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'line_total' => $item->line_total,
            ])->all(),
        ];

        $response = $this->client->pushQuotation($payload);
        $success = ($response['success'] ?? false) === true;

        $quotation->update([
            'external_erp_id' => $response['external_id'] ?? $quotation->external_erp_id,
            'erp_sync_status' => $success ? 'synced' : 'failed',
            'erp_synced_at' => $success ? now() : $quotation->erp_synced_at,
        ]);

        $this->log(
            'outbound',
            'quotation',
            $quotation->id,
            $success ? 'success' : 'failed',
            $success ? null : ($response['message'] ?? 'Unknown error'),
            $payload,
            $response
        );

        return $success;
    }

    public function pushOrder(Order $order): bool
    {
        $order->load(['items.product', 'customerProfile.user', 'customerProfile.organization']);

        $payload = [
            'reference' => $order->reference,
            'status' => $order->status,
            'customer' => [
                'name' => $order->customerProfile->user->name,
                'email' => $order->customerProfile->user->email,
                'organization' => $order->customerProfile->organization?->name,
            ],
            'subtotal' => $order->subtotal,
            'vat_amount' => $order->vat_amount,
            'total_amount' => $order->total_amount,
            'items' => $order->items->map(fn ($item) => [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'line_total' => $item->line_total,
            ])->all(),
        ];

        $response = $this->client->pushOrder($payload);
        $success = ($response['success'] ?? false) === true;

        $order->update([
            'external_erp_id' => $response['external_id'] ?? $order->external_erp_id,
            'erp_sync_status' => $success ? 'synced' : 'failed',
            'erp_synced_at' => $success ? now() : $order->erp_synced_at,
        ]);

        $this->log(
            'outbound',
            'order',
            $order->id,
            $success ? 'success' : 'failed',
            $success ? null : ($response['message'] ?? 'Unknown error'),
            $payload,
            $response
        );

        return $success;
    }

    protected function log(
        string $direction,
        string $entityType,
        ?int $entityId,
        string $status,
        ?string $error,
        mixed $payload = null,
        mixed $response = null
    ): void {
        ErpSyncLog::create([
            'direction' => $direction,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'payload_hash' => is_array($payload) ? hash('sha256', json_encode($payload)) : null,
            'status' => $status,
            'error_message' => $error,
            'payload' => is_array($payload) ? $payload : null,
            'response' => is_array($response) ? $response : null,
        ]);
    }
}
