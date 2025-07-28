<?php

namespace App\Console\Commands;

use App\Services\TaskService;
use Illuminate\Console\Command;

class CleanupOldTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:cleanup {--days=30 : Number of days to keep tasks}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up tasks older than specified days';

    /**
     * Execute the console command.
     */
    public function handle(TaskService $taskService): int
    {
        $days = (int) $this->option('days');

        $this->info("Starting cleanup of tasks older than {$days} days...");

        try {
            $deletedCount = $taskService->cleanupOldTasks($days);

            $this->info("✅ Successfully deleted {$deletedCount} old tasks.");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Failed to cleanup tasks: " . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
