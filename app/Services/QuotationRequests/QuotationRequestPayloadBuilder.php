<?php

namespace App\Services\QuotationRequests;

use App\Models\QuotationRequest;
use Illuminate\Support\Arr;

class QuotationRequestPayloadBuilder
{
    public function buildFromInput(array $validated, string $externalId): array
    {
        $now = now()->toIso8601String();

        return [
            'external_id' => $externalId,
            'submitted_at' => $now,
            'source' => 'company-website',
            'contact' => [
                'name' => $validated['contact']['name'],
                'email' => $validated['contact']['email'],
                'phone' => $validated['contact']['phone'],
                'company' => $validated['contact']['company'] ?? null,
                'preferred_method' => $validated['contact']['preferred_method'] ?? null,
            ],
            'job' => [
                'title' => $validated['job']['title'],
                'type' => $validated['job']['type'],
                'description' => $validated['job']['description'],
                'quantity' => (int) $validated['job']['quantity'],
                'required_by' => $validated['job']['required_by'] ?? null,
                'delivery' => $validated['job']['delivery'] ?? null,
                'delivery_address' => $validated['job']['delivery_address'] ?? null,
            ],
            'spec' => $this->buildSpec($validated),
            'attachments' => [],
            'attachments_reference_url' => $validated['attachments_reference_url'] ?? null,
            'consent' => [
                'accepted' => true,
                'accepted_at' => $now,
            ],
        ];
    }

    protected function buildSpec(array $validated): array
    {
        $spec = Arr::get($validated, 'spec', []);

        return [
            'finished_size_mm' => [
                'width' => isset($spec['finished_size_mm']['width']) ? (float) $spec['finished_size_mm']['width'] : null,
                'height' => isset($spec['finished_size_mm']['height']) ? (float) $spec['finished_size_mm']['height'] : null,
            ],
            'pages' => isset($spec['pages']) ? (int) $spec['pages'] : null,
            'colours' => $spec['colours'],
            'spot_colour_notes' => $spec['spot_colour_notes'] ?? null,
            'papers' => $this->normalizePapers($spec['papers'] ?? []),
            'finishing' => array_values($spec['finishing'] ?? []),
            'finishing_notes' => $spec['finishing_notes'] ?? null,
            'artwork_status' => $spec['artwork_status'],
            'budget_hint' => $spec['budget_hint'] ?? null,
            'banner_environment' => $spec['banner_environment'] ?? null,
            'binding' => $spec['binding'] ?? null,
            'card_sides' => $spec['card_sides'] ?? null,
            'fold_type' => $spec['fold_type'] ?? null,
            'orientation' => $spec['orientation'] ?? null,
        ];
    }

    protected function normalizePapers(array $papers): array
    {
        return collect($papers)
            ->filter(fn ($row) => filled($row['type'] ?? null) || filled($row['role'] ?? null))
            ->map(fn ($row) => [
                'role' => $row['role'] ?? 'other',
                'type' => $row['type'] ?? null,
                'size' => $row['size'] ?? null,
                'grammage' => isset($row['grammage']) ? (int) $row['grammage'] : null,
                'color' => $row['color'] ?? null,
                'notes' => $row['notes'] ?? null,
            ])
            ->values()
            ->all();
    }

    public function mergeAdminContext(QuotationRequest $request): array
    {
        $payload = $request->payload_json;

        $payload['admin_notes'] = $request->admin_notes;
        $payload['priority'] = $request->priority;
        $payload['reviewed_by'] = $request->reviewed_by;
        $payload['reviewed_at'] = $request->reviewed_at?->toIso8601String();

        return $payload;
    }
}
