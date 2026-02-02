<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DatabaseProtectionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check essential tables before processing request
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
            Log::emergency("Critical: Missing database tables detected", [
                "missing_tables" => $missingTables,
                "request_url" => $request->url(),
                "user_id" => auth()->id()
            ]);
            
            // Try to auto-fix if possible
            $this->attemptAutoFix($missingTables);
        }
        
        return $next($request);
    }
    
    private function attemptAutoFix($missingTables)
    {
        try {
            // Run the comprehensive fix script
            exec("php " . base_path("comprehensive_table_fix.php"), $output, $returnCode);
            
            if ($returnCode === 0) {
                Log::info("Auto-fix successful for missing tables", ["tables" => $missingTables]);
            } else {
                Log::error("Auto-fix failed for missing tables", ["tables" => $missingTables, "output" => $output]);
            }
        } catch (Exception $e) {
            Log::error("Auto-fix exception", ["error" => $e->getMessage()]);
        }
    }
}
