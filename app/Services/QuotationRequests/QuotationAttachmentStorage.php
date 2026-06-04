<?php

namespace App\Services\QuotationRequests;

use App\Models\QuotationAttachment;
use App\Models\QuotationRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuotationAttachmentStorage
{
    public function storeMany(QuotationRequest $request, array $files): array
    {
        $attachments = [];
        $disk = config('quotation_requests.uploads.disk', 'local');
        $directory = config('quotation_requests.uploads.directory', 'quotation-requests');

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store("{$directory}/{$request->id}", $disk);

            $attachments[] = QuotationAttachment::create([
                'quotation_request_id' => $request->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $path,
                'mime' => $file->getMimeType() ?? 'application/octet-stream',
                'size_bytes' => $file->getSize(),
            ]);
        }

        return $attachments;
    }

    public function signedDownloadPath(QuotationAttachment $attachment): ?string
    {
        if (! $attachment->existsOnDisk()) {
            return null;
        }

        return Storage::disk($attachment->disk())->path($attachment->stored_path);
    }

    public function attachmentPayloadEntry(QuotationAttachment $attachment): array
    {
        return [
            'id' => $attachment->id,
            'name' => $attachment->original_name,
            'url' => route('admin.quotation-requests.attachments.download', $attachment),
            'mime' => $attachment->mime,
            'size_bytes' => $attachment->size_bytes,
        ];
    }

    public function refreshPayloadAttachments(QuotationRequest $request): void
    {
        $request->load('attachments');

        $payload = $request->payload_json;
        $payload['attachments'] = $request->attachments
            ->map(fn (QuotationAttachment $a) => $this->attachmentPayloadEntry($a))
            ->values()
            ->all();

        $request->update(['payload_json' => $payload]);
    }
}
