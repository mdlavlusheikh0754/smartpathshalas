<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\TransportRoute;
use App\Models\TransportAllocation;
use App\Models\Student;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    public function index()
    {
        $allocations = TransportAllocation::with(['student', 'route', 'vehicle'])->latest()->get();
        $students = Student::orderBy('name_bn')->get();
        $routes = TransportRoute::all();
        $vehicles = Vehicle::all();
        
        return view('tenant.transport.index', compact('allocations', 'students', 'routes', 'vehicles'));
    }

    public function vehicles()
    {
        $vehicles = Vehicle::latest()->get();
        return view('tenant.transport.vehicles', compact('vehicles'));
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255|unique:vehicles',
            'vehicle_model' => 'required|string|max:255',
            'year_made' => 'nullable|string|max:10',
            'driver_name' => 'required|string|max:255',
            'driver_license' => 'nullable|string|max:255',
            'driver_contact' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        Vehicle::create($validated);

        return redirect()->back()->with('success', 'যানবাহন সফলভাবে যোগ করা হয়েছে।');
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255|unique:vehicles,vehicle_number,' . $vehicle->id,
            'vehicle_model' => 'required|string|max:255',
            'year_made' => 'nullable|string|max:10',
            'driver_name' => 'required|string|max:255',
            'driver_license' => 'nullable|string|max:255',
            'driver_contact' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $vehicle->update($validated);

        return redirect()->back()->with('success', 'যানবাহন সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroyVehicle(Vehicle $vehicle)
    {
        if ($vehicle->allocations()->exists()) {
            return redirect()->back()->with('error', 'এই যানবাহনে শিক্ষার্থী বরাদ্দ আছে, তাই এটি মুছে ফেলা সম্ভব নয়।');
        }

        $vehicle->delete();
        return redirect()->back()->with('success', 'যানবাহন সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function routes()
    {
        $routes = TransportRoute::latest()->get();
        return view('tenant.transport.routes', compact('routes'));
    }

    public function storeRoute(Request $request)
    {
        $validated = $request->validate([
            'route_title' => 'required|string|max:255',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        TransportRoute::create($validated);

        return redirect()->back()->with('success', 'রুট সফলভাবে যোগ করা হয়েছে।');
    }

    public function updateRoute(Request $request, TransportRoute $route)
    {
        $validated = $request->validate([
            'route_title' => 'required|string|max:255',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $route->update($validated);

        return redirect()->back()->with('success', 'রুট সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroyRoute(TransportRoute $route)
    {
        if ($route->allocations()->exists()) {
            return redirect()->back()->with('error', 'এই রুটে শিক্ষার্থী বরাদ্দ আছে, তাই এটি মুছে ফেলা সম্ভব নয়।');
        }

        $route->delete();
        return redirect()->back()->with('success', 'রুট সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function storeAllocation(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'route_id' => 'required|exists:transport_routes,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_point' => 'nullable|string|max:255',
            'monthly_fee' => 'required|numeric|min:0',
        ]);

        TransportAllocation::updateOrCreate(
            ['student_id' => $validated['student_id']],
            $validated
        );

        return redirect()->back()->with('success', 'পরিবহন বরাদ্দ সফলভাবে সম্পন্ন হয়েছে।');
    }

    public function destroyAllocation(TransportAllocation $allocation)
    {
        $allocation->delete();
        return redirect()->back()->with('success', 'পরিবহন বরাদ্দ সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
