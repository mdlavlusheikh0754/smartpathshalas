<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\HostelAllocation;
use App\Models\Student;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    public function index()
    {
        $hostels = Hostel::withCount('rooms')->latest()->get();
        return view('tenant.hostel.index', compact('hostels'));
    }

    public function storeHostel(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:boys,girls,combined',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Hostel::create($validated);

        return redirect()->back()->with('success', 'হোস্টেল সফলভাবে যোগ করা হয়েছে।');
    }

    public function updateHostel(Request $request, Hostel $hostel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:boys,girls,combined',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $hostel->update($validated);

        return redirect()->back()->with('success', 'হোস্টেল সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroyHostel(Hostel $hostel)
    {
        if ($hostel->rooms()->exists()) {
            return redirect()->back()->with('error', 'এই হোস্টেলে রুম আছে, তাই এটি মুছে ফেলা সম্ভব নয়।');
        }

        $hostel->delete();
        return redirect()->back()->with('success', 'হোস্টেল সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function rooms()
    {
        $rooms = Room::with('hostel')->latest()->get();
        $hostels = Hostel::all();
        return view('tenant.hostel.rooms', compact('rooms', 'hostels'));
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|string|max:50',
            'num_beds' => 'required|integer|min:1',
            'cost_per_bed' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Room::create($validated);

        return redirect()->back()->with('success', 'রুম সফলভাবে যোগ করা হয়েছে।');
    }

    public function updateRoom(Request $request, Room $room)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|string|max:50',
            'num_beds' => 'required|integer|min:1',
            'cost_per_bed' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->back()->with('success', 'রুম সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroyRoom(Room $room)
    {
        if ($room->allocations()->where('status', 'allocated')->exists()) {
            return redirect()->back()->with('error', 'এই রুমে শিক্ষার্থী বরাদ্দ আছে, তাই এটি মুছে ফেলা সম্ভব নয়।');
        }

        $room->delete();
        return redirect()->back()->with('success', 'রুম সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function allocations()
    {
        $allocations = HostelAllocation::with(['student', 'room.hostel'])->latest()->get();
        $students = Student::orderBy('name_en')->get();
        $rooms = Room::with('hostel')->get();
        
        return view('tenant.hostel.allocations', compact('allocations', 'students', 'rooms'));
    }

    public function storeAllocation(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'allocation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Check if student already has an active allocation
        if (HostelAllocation::where('student_id', $validated['student_id'])->where('status', 'allocated')->exists()) {
            return redirect()->back()->with('error', 'এই শিক্ষার্থী ইতিমধ্যে একটি হোস্টেল রুমে আছে।');
        }

        // Check room capacity
        $room = Room::find($validated['room_id']);
        $currentAllocations = HostelAllocation::where('room_id', $validated['room_id'])->where('status', 'allocated')->count();
        
        if ($currentAllocations >= $room->num_beds) {
            return redirect()->back()->with('error', 'এই রুমে আর কোন বেড খালি নেই।');
        }

        HostelAllocation::create($validated);

        return redirect()->back()->with('success', 'শিক্ষার্থী সফলভাবে রুমে বরাদ্দ করা হয়েছে।');
    }

    public function releaseAllocation(Request $request, HostelAllocation $allocation)
    {
        $request->validate([
            'release_date' => 'required|date|after_or_equal:' . $allocation->allocation_date->format('Y-m-d'),
        ]);

        $allocation->update([
            'release_date' => $request->release_date,
            'status' => 'released',
        ]);

        return redirect()->back()->with('success', 'শিক্ষার্থী সফলভাবে রুম থেকে রিলিজ করা হয়েছে।');
    }
}
