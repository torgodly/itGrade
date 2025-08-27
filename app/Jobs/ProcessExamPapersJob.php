<?php

namespace App\Jobs;

use App\Actions\AnalyzePaperAction;
use App\Models\Exam;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessExamPapersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $ownerRecordId) {}

    public function handle(): void
    {
        $record = Exam::find($this->ownerRecordId);

        $record->results()
            ->whereNull('student_id')
            ->each(fn ($result) => (new AnalyzePaperAction($result))->handle());
    }
}
