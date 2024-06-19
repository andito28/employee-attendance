<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\TimesheetExportMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Excel\GenerateTimesheetExcel;

class SendEmailTimesheetExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    /**
     * Create a new job instance.
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filename = 'timesheet_' . now()->format('Ymd_His') . '.xlsx';
        $directory = 'exportsTimesheet';

        if (!Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->makeDirectory($directory);
        }

        Excel::store(new GenerateTimesheetExcel, $directory . '/' . $filename, 'local');

        $filePath = storage_path('app/'  . $directory . '/' . $filename);

        Mail::to($this->email)->send(new TimesheetExportMail($filePath));

        unlink($filePath);
    }
}
