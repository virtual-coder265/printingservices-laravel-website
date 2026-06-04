<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\Quotation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['nullable', 'exists:services,id'],
            'description' => ['required', 'string', 'max:2000'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = Auth::user();
        if (! $user) {
            return back()->withErrors(['auth' => 'Please log in to submit a quotation request.'])->withInput();
        }

        $profile = $user->customerProfile ?? CustomerProfile::create([
            'user_id' => $user->id,
            'phone_number' => $validated['phone'] ?? null,
            'type' => filled($validated['organization'] ?? null) ? 'corporate' : 'individual',
        ]);

        if (filled($validated['phone'])) {
            $profile->update(['phone_number' => $validated['phone']]);
        }

        $service = isset($validated['service_id']) ? Service::find($validated['service_id']) : null;
        $quantity = $validated['quantity'] ?? 1;

        $quotation = Quotation::create([
            'reference' => Quotation::generateReference(),
            'customer_profile_id' => $profile->id,
            'status' => 'pending_review',
            'notes' => $validated['description'],
            'vat_rate' => 17.50,
        ]);

        $quotation->items()->create([
            'service_id' => $service?->id,
            'description' => $service?->title ?? 'Custom print request',
            'quantity' => $quantity,
            'unit_price' => $service?->base_price ?? 0,
            'vat_rate' => 17.50,
        ]);

        $quotation->recalculateTotals();

        return redirect()->route('portal.quotations.show', $quotation)
            ->with('status', 'Your quotation request has been submitted.');
    }
}
