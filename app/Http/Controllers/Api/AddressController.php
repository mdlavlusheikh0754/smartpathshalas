<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BangladeshAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // Get all divisions
    public function getDivisions()
    {
        try {
            $divisions = BangladeshAddress::divisions()
                ->orderBy('name_bn')
                ->get(['id', 'name_bn', 'name_en']);

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
    public function getDistricts($divisionId)
    {
        try {
            $districts = BangladeshAddress::districts($divisionId)
                ->orderBy('name_bn')
                ->get(['id', 'name_bn', 'name_en']);

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
    public function getUpazilas($districtId)
    {
        try {
            $upazilas = BangladeshAddress::upazilas($districtId)
                ->orderBy('name_bn')
                ->get(['id', 'name_bn', 'name_en']);

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
    public function getUnions($upazilaId)
    {
        try {
            $unions = BangladeshAddress::unions($upazilaId)
                ->orderBy('name_bn')
                ->get(['id', 'name_bn', 'name_en']);

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

    // Get all address data (for initial load)
    public function getAllAddresses()
    {
        try {
            $addresses = BangladeshAddress::all(['id', 'type', 'name_bn', 'name_en', 'parent_id']);
            
            return response()->json($addresses)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load addresses',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
