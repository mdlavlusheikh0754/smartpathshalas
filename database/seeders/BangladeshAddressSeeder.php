<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BangladeshAddress;
use Illuminate\Support\Facades\DB;

class BangladeshAddressSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        BangladeshAddress::truncate();

        // Load JSON data
        $jsonPath = base_path('bangladesh-locations.json');
        
        if (!file_exists($jsonPath)) {
            echo 'JSON file not found: ' . $jsonPath . "\n";
            return;
        }

        $jsonData = json_decode(file_get_contents($jsonPath), true);

        if (!isset($jsonData['বিভাগ'])) {
            echo 'Invalid JSON structure' . "\n";
            return;
        }

        echo 'Starting to import Bangladesh address data...' . "\n";

        DB::beginTransaction();
        try {
            foreach ($jsonData['বিভাগ'] as $divisionData) {
                // Insert Division
                $division = BangladeshAddress::create([
                    'type' => 'division',
                    'name_bn' => $divisionData['নাম'],
                    'name_en' => null,
                    'parent_id' => null
                ]);

                echo "Imported Division: {$division->name_bn}\n";

                // Insert Districts
                if (isset($divisionData['জেলা'])) {
                    foreach ($divisionData['জেলা'] as $districtData) {
                        $district = BangladeshAddress::create([
                            'type' => 'district',
                            'name_bn' => $districtData['নাম'],
                            'name_en' => null,
                            'parent_id' => $division->id
                        ]);

                        // Insert Upazilas
                        if (isset($districtData['উপজেলা'])) {
                            foreach ($districtData['উপজেলা'] as $upazilaData) {
                                $upazila = BangladeshAddress::create([
                                    'type' => 'upazila',
                                    'name_bn' => $upazilaData['নাম'],
                                    'name_en' => null,
                                    'parent_id' => $district->id
                                ]);

                                // Insert Unions
                                if (isset($upazilaData['ইউনিয়ন']) && is_array($upazilaData['ইউনিয়ন'])) {
                                    foreach ($upazilaData['ইউনিয়ন'] as $unionName) {
                                        // Clean union name (remove "ইউনিয়ন" suffix if present)
                                        $cleanUnionName = preg_replace('/ ইউনিয়ন$/', '', trim($unionName));
                                        
                                        if (!empty($cleanUnionName)) {
                                            BangladeshAddress::create([
                                                'type' => 'union',
                                                'name_bn' => $cleanUnionName,
                                                'name_en' => null,
                                                'parent_id' => $upazila->id
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();
            
            $counts = [
                'divisions' => BangladeshAddress::where('type', 'division')->count(),
                'districts' => BangladeshAddress::where('type', 'district')->count(),
                'upazilas' => BangladeshAddress::where('type', 'upazila')->count(),
                'unions' => BangladeshAddress::where('type', 'union')->count(),
            ];

            echo '✓ Import completed successfully!' . "\n";
            echo "  - Divisions: {$counts['divisions']}\n";
            echo "  - Districts: {$counts['districts']}\n";
            echo "  - Upazilas: {$counts['upazilas']}\n";
            echo "  - Unions: {$counts['unions']}\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo 'Import failed: ' . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
