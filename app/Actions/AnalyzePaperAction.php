<?php

namespace App\Actions;

use App\Models\Result;
use App\Models\Student;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class AnalyzePaperAction
{
    protected Result $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    public static function make(Result $result): static
    {
        return new self($result);
    }

    public function handle(): void
    {
        $imagePath = $this->result->getFirstMediaPath('exam_paper');
        $outputPathLocal = storage_path('app/public/annotated_' . basename($imagePath));

        $process = new Process([
            'python',
            base_path('app/Python/ocr.py'),
            $imagePath,
            $outputPathLocal
        ]);
        //dump the command for debugging
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }

        $jsonOutput = $process->getOutput();
        $data = json_decode($jsonOutput, true);
        // Save JSON data to the result record
        $this->result->student_id =  Student::where('code', $data['student_id'])->first()->id ?? null;
        //take only the count of answers from the exam
        $answersCount = collect($this->result->exam->questions);
        $this->result->answers = collect($data['answers'])->take($answersCount->count())->toArray();
        $this->result->save();

        // Attach annotated image to media collection 'exam_answers'
        if (file_exists($outputPathLocal)) {
            $this->result->clearMediaCollection('exam_answers');

            $this->result
                ->addMedia($outputPathLocal)
                ->preservingOriginal()
                ->toMediaCollection('exam_answers');

            unlink($outputPathLocal);
        }

    }
}
