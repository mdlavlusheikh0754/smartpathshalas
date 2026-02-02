<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DatabaseProtectionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register database protection checks
        $this->app->booted(function () {
            $this->checkDatabaseIntegrity();
        });
    }
    
    public function register()
    {
        //
    }
    
    private function checkDatabaseIntegrity()
    {
        try {
            $essentialTables = [
                "users", "students", "teachers", "school_classes", "subjects",
                "exams", "exam_results", "fee_collections", "notices", "homework",
                "attendance_records", "zkteco_devices", "student_rfid_mappings",
                "school_settings", "website_settings", "admission_applications"
            ];
            
            $missingTables = [];
            
            foreach ($essentialTables as $table) {
                if (!Schema::hasTable($table)) {
                    $missingTables[] = $table;
                }
            }
            
            if (!empty($missingTables)) {
                Log::warning("Database integrity check failed", [
                    "missing_tables" => $missingTables,
                    "timestamp" => now()
                ]);
                
                // Attempt to create missing tables
                $this->createMissingTables($missingTables);
            }
            
        } catch (Exception $e) {
            Log::error("Database integrity check error", ["error" => $e->getMessage()]);
        }
    }
    
    private function createMissingTables($missingTables)
    {
        // This would contain the table creation logic
        // For now, just log the attempt
        Log::info("Attempting to create missing tables", ["tables" => $missingTables]);
    }
}
