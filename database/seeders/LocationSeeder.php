<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\BangladeshAddress;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        echo "Starting location data migration...\n";
        
        // Clear existing locations
        Location::truncate();
        
        DB::beginTransaction();
        try {
            // Migrate divisions
            echo "Migrating divisions...\n";
            $divisions = BangladeshAddress::where('type', 'division')->whereNull('parent_id')->get();
            $divisionMap = [];
            
            foreach ($divisions as $division) {
                $location = Location::create([
                    'name_bn' => $division->name_bn,
                    'name_en' => $division->name_en,
                    'type' => 'division',
                    'parent_id' => null,
                    'level' => 0,
                    'code' => 'DIV-' . str_pad($division->id, 3, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'sort_order' => $division->id
                ]);
                
                $divisionMap[$division->id] = $location->id;
                echo "  - {$division->name_bn}\n";
            }
            
            // Migrate districts
            echo "Migrating districts...\n";
            $districts = BangladeshAddress::where('type', 'district')->whereNotNull('parent_id')->get();
            $districtMap = [];
            
            foreach ($districts as $district) {
                if (isset($divisionMap[$district->parent_id])) {
                    $location = Location::create([
                        'name_bn' => $district->name_bn,
                        'name_en' => $district->name_en,
                        'type' => 'district',
                        'parent_id' => $divisionMap[$district->parent_id],
                        'level' => 1,
                        'code' => 'DIS-' . str_pad($district->id, 3, '0', STR_PAD_LEFT),
                        'is_active' => true,
                        'sort_order' => $district->id
                    ]);
                    
                    $districtMap[$district->id] = $location->id;
                }
            }
            
            // Migrate upazilas
            echo "Migrating upazilas...\n";
            $upazilas = BangladeshAddress::where('type', 'upazila')->whereNotNull('parent_id')->get();
            $upazilaMap = [];
            
            foreach ($upazilas as $upazila) {
                if (isset($districtMap[$upazila->parent_id])) {
                    $location = Location::create([
                        'name_bn' => $upazila->name_bn,
                        'name_en' => $upazila->name_en,
                        'type' => 'upazila',
                        'parent_id' => $districtMap[$upazila->parent_id],
                        'level' => 2,
                        'code' => 'UPA-' . str_pad($upazila->id, 4, '0', STR_PAD_LEFT),
                        'is_active' => true,
                        'sort_order' => $upazila->id
                    ]);
                    
                    $upazilaMap[$upazila->id] = $location->id;
                }
            }
            
            // Migrate unions
            echo "Migrating unions...\n";
            $unions = BangladeshAddress::where('type', 'union')->whereNotNull('parent_id')->get();
            
            foreach ($unions as $union) {
                if (isset($upazilaMap[$union->parent_id])) {
                    Location::create([
                        'name_bn' => $union->name_bn,
                        'name_en' => $union->name_en,
                        'type' => 'union',
                        'parent_id' => $upazilaMap[$union->parent_id],
                        'level' => 3,
                        'code' => 'UNI-' . str_pad($union->id, 4, '0', STR_PAD_LEFT),
                        'is_active' => true,
                        'sort_order' => $union->id
                    ]);
                }
            }
            
            DB::commit();
            
            // Show statistics
            $stats = [
                'divisions' => Location::where('type', 'division')->count(),
                'districts' => Location::where('type', 'district')->count(),
                'upazilas' => Location::where('type', 'upazila')->count(),
                'unions' => Location::where('type', 'union')->count(),
                'total' => Location::count()
            ];
            
            echo "\n✓ Migration completed successfully!\n";
            echo "Statistics:\n";
            echo "  - Divisions: {$stats['divisions']}\n";
            echo "  - Districts: {$stats['districts']}\n";
            echo "  - Upazilas: {$stats['upazilas']}\n";
            echo "  - Unions: {$stats['unions']}\n";
            echo "  - Total: {$stats['total']}\n";
            
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Migration failed: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}