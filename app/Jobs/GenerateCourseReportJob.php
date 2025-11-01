<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class GenerateCourseReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    /**
     * Execute the job - Executa script Python para gerar relatório de cursos
     */
    public function handle(): void
    {
        try {
            $pythonPath = env('PYTHON_PATH', 'python');
            $scriptPath = base_path('scripts/generate_course_report.py');

            Log::info('Starting course report generation');

            // Executa o script Python
            $result = Process::run("{$pythonPath} {$scriptPath}");

            if ($result->successful()) {
                Log::info('Course report generated successfully', [
                    'output' => $result->output()
                ]);
            } else {
                Log::error('Failed to generate course report', [
                    'error' => $result->errorOutput()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error running Python script', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
