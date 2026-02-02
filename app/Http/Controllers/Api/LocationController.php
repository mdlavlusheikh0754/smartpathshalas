<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    // Get all divisions
    public function getDivisions(): JsonResponse
    {
        try {
            $divisions = Location::divisions()->get(['id', 'name_bn', 'name_en', 'code']);
            
            return response()->json($divisions)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load divisions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get districts by division
    public function getDistricts($divisionId): JsonResponse
    {
        try {
            $districts = Location::districts($divisionId)->get(['id', 'name_bn', 'name_en', 'code']);
            
            return response()->json($districts)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load districts',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get upazilas by district
    public function getUpazilas($districtId): JsonResponse
    {
        try {
            $upazilas = Location::upazilas($districtId)->get(['id', 'name_bn', 'name_en', 'code']);
            
            return response()->json($upazilas)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load upazilas',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get unions by upazila
    public function getUnions($upazilaId): JsonResponse
    {
        try {
            $unions = Location::unions($upazilaId)->get(['id', 'name_bn', 'name_en', 'code']);
            
            // If no unions found, check if the upazila exists
            if ($unions->isEmpty()) {
                $upazila = Location::find($upazilaId);
                if ($upazila && $upazila->type === 'upazila') {
                    // Upazila exists but has no unions - this is valid for some newly created upazilas
                    return response()->json([])
                        ->header('Access-Control-Allow-Origin', '*')
                        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                } else {
                    return response()->json([
                        'error' => 'Upazila not found',
                        'message' => 'The specified upazila does not exist'
                    ], 404);
                }
            }
            
            return response()->json($unions)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load unions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get wards by union
    public function getWards($unionId): JsonResponse
    {
        try {
            $wards = Location::wards($unionId)->get(['id', 'name_bn', 'name_en', 'code']);
            
            return response()->json($wards)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load wards',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get villages by parent (union or ward)
    public function getVillages($parentId): JsonResponse
    {
        try {
            $villages = Location::villages($parentId)->get(['id', 'name_bn', 'name_en', 'code']);
            
            return response()->json($villages)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load villages',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get location by ID with full details
    public function getLocation($id): JsonResponse
    {
        try {
            $location = Location::with(['parent', 'children'])->find($id);
            
            if (!$location) {
                return response()->json(['error' => 'Location not found'], 404);
            }
            
            return response()->json([
                'location' => $location,
                'full_address' => $location->full_address,
                'ancestors' => $location->getAncestors(),
                'children' => $location->children
            ])
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load location',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Search locations
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type', '');
            $limit = min($request->get('limit', 20), 100);
            
            if (strlen($query) < 2) {
                return response()->json(['error' => 'Query must be at least 2 characters'], 400);
            }
            
            $locations = Location::where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name_bn', 'LIKE', "%{$query}%")
                      ->orWhere('name_en', 'LIKE', "%{$query}%");
                });
            
            if ($type) {
                $locations->where('type', $type);
            }
            
            $results = $locations->limit($limit)
                ->get(['id', 'name_bn', 'name_en', 'type', 'division_name', 'district_name', 'upazila_name']);
            
            return response()->json($results)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get all locations (for initial load or caching)
    public function getAllLocations(): JsonResponse
    {
        try {
            $locations = Location::where('is_active', true)
                ->orderBy('level')
                ->orderBy('sort_order')
                ->orderBy('name_bn')
                ->get(['id', 'name_bn', 'name_en', 'type', 'parent_id', 'level', 'division_name', 'district_name', 'upazila_name']);
            
            return response()->json($locations)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load locations',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get location statistics
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'divisions' => Location::where('type', 'division')->where('is_active', true)->count(),
                'districts' => Location::where('type', 'district')->where('is_active', true)->count(),
                'upazilas' => Location::where('type', 'upazila')->where('is_active', true)->count(),
                'unions' => Location::where('type', 'union')->where('is_active', true)->count(),
                'wards' => Location::where('type', 'ward')->where('is_active', true)->count(),
                'villages' => Location::where('type', 'village')->where('is_active', true)->count(),
                'total' => Location::where('is_active', true)->count()
            ];
            
            return response()->json($stats)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}