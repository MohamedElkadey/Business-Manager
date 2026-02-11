<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class GenerateModelsFromTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model, migration, and controller for each table';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $unwantedTables = [
        'migrations',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'password_reset_tokens',
        'personal_access_tokens',
    ];

    $tables = DB::select("
        SELECT tablename 
        FROM pg_tables 
        WHERE schemaname = 'public'
    ");

    foreach ($tables as $table) {
        $tableName = $table->tablename;

        if (in_array($tableName, $unwantedTables)) {
            continue;
        }

        $modelName = Str::studly(Str::singular($tableName));

        $modelPath = app_path("Models/{$modelName}.php");
        $controllerPath = app_path("Http/Controllers/{$modelName}Controller.php");

        $modelExists = file_exists($modelPath);
        $controllerExists = file_exists($controllerPath);

        // 🧠 Case 1: الاتنين مش موجودين
        if (!$modelExists && !$controllerExists) {
            $this->info("🚀 Creating model & controller: {$modelName}");

            Artisan::call('make:model', [
                'name' => $modelName,
            ]);

            Artisan::call('make:controller', [
                'name' => "{$modelName}Controller",
            ]);
        }

        // 🧠 Case 2: Model موجود – Controller مش موجود
        elseif ($modelExists && !$controllerExists) {
            $this->info("🎮 Creating controller only: {$modelName}Controller");

            Artisan::call('make:controller', [
                'name' => "{$modelName}Controller",
            ]);
        }

        // 🧠 Case 3: Model مش موجود – Controller موجود
        elseif (!$modelExists && $controllerExists) {
            $this->info("📦 Creating model only: {$modelName}");

            Artisan::call('make:model', [
                'name' => $modelName,
            ]);
        }

        // 🧠 Case 4: الاتنين موجودين
        else {
            $this->warn("⏭ Skipped (both exist): {$modelName}");
        }
    }

    $this->info('✅ Done perfectly!');
}


}
