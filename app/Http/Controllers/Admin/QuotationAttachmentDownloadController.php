<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuotationAttachment;
use App\Services\QuotationRequests\QuotationAttachmentStorage;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotationAttachmentDownloadController extends Controller
{
    public function __invoke(
        QuotationAttachment $attachment,
        QuotationAttachmentStorage $storage,
    ): StreamedResponse {
        Gate::authorize('view', $attachment->quotationRequest);

        $path = $storage->signedDownloadPath($attachment);

        abort_unless($path && is_readable($path), 404);

        return response()->download($path, $attachment->original_name, [
            'Content-Type' => $attachment->mime,
        ]);
    }
}
