<?php

namespace App\Http\Controllers;

use App\Models\ArtworkFile;
use App\Models\Order;
use App\Models\PrintJob;
use App\Models\Quotation;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PortalController extends Controller
{
    public function __construct(protected SiteContentService $siteContent) {}

    public function dashboard()
    {
        $user = Auth::user();
        $profile = $user->customerProfile;

        return view('portal.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'quotations' => $profile
                ? Quotation::where('customer_profile_id', $profile->id)->latest()->take(5)->get()
                : collect(),
            'orders' => $profile
                ? Order::where('customer_profile_id', $profile->id)->latest()->take(5)->get()
                : collect(),
            'printJobs' => $profile
                ? PrintJob::where('customer_profile_id', $profile->id)->latest()->take(5)->get()
                : collect(),
        ]);
    }

    public function quotations()
    {
        $profile = Auth::user()->customerProfile;

        return view('portal.quotations.index', [
            'quotations' => $profile
                ? Quotation::where('customer_profile_id', $profile->id)->latest()->paginate(10)
                : collect(),
        ]);
    }

    public function showQuotation(Quotation $quotation)
    {
        $this->authorizeQuotation($quotation);

        return view('portal.quotations.show', [
            'quotation' => $quotation->load('items.service'),
        ]);
    }

    public function orders()
    {
        $profile = Auth::user()->customerProfile;

        return view('portal.orders.index', [
            'orders' => $profile
                ? Order::where('customer_profile_id', $profile->id)->latest()->paginate(10)
                : collect(),
        ]);
    }

    public function showOrder(Order $order)
    {
        $this->authorizeOrder($order);

        return view('portal.orders.show', [
            'order' => $order->load('items.product'),
        ]);
    }

    public function printJobs()
    {
        $profile = Auth::user()->customerProfile;

        return view('portal.print-jobs.index', [
            'printJobs' => $profile
                ? PrintJob::where('customer_profile_id', $profile->id)->latest()->paginate(10)
                : collect(),
        ]);
    }

    public function showPrintJob(PrintJob $printJob)
    {
        $this->authorizePrintJob($printJob);

        return view('portal.print-jobs.show', [
            'printJob' => $printJob->load(['statusLogs', 'artworkFiles', 'quotation']),
        ]);
    }

    public function uploadArtwork(Request $request, Quotation $quotation)
    {
        $this->authorizeQuotation($quotation);

        $validated = $request->validate([
            'artwork' => ['required', 'file', 'max:20480'],
        ]);

        $path = $validated['artwork']->store('artwork', 'public');

        ArtworkFile::create([
            'quotation_id' => $quotation->id,
            'uploaded_by_user_id' => Auth::id(),
            'original_name' => $validated['artwork']->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $validated['artwork']->getClientMimeType(),
            'file_size' => $validated['artwork']->getSize(),
        ]);

        return back()->with('status', 'Artwork uploaded successfully.');
    }

    protected function authorizeQuotation(Quotation $quotation): void
    {
        abort_unless(
            Auth::user()->customerProfile?->id === $quotation->customer_profile_id,
            403
        );
    }

    protected function authorizeOrder(Order $order): void
    {
        abort_unless(
            Auth::user()->customerProfile?->id === $order->customer_profile_id,
            403
        );
    }

    protected function authorizePrintJob(PrintJob $printJob): void
    {
        abort_unless(
            Auth::user()->customerProfile?->id === $printJob->customer_profile_id,
            403
        );
    }
}
