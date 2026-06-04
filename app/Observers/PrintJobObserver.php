<?php

namespace App\Observers;

use App\Models\JobStatusLog;
use App\Models\PrintJob;
use Illuminate\Support\Facades\Auth;

class PrintJobObserver
{
    public function created(PrintJob $printJob): void
    {
        $this->logStatus($printJob, $printJob->status, 'Print job created.');
    }

    public function updated(PrintJob $printJob): void
    {
        if ($printJob->wasChanged('status')) {
            $this->logStatus($printJob, $printJob->status, 'Status updated.');
        }
    }

    protected function logStatus(PrintJob $printJob, string $status, ?string $notes = null): void
    {
        JobStatusLog::create([
            'print_job_id' => $printJob->id,
            'status' => $status,
            'notes' => $notes,
            'updated_by_user_id' => Auth::id(),
        ]);
    }
}
