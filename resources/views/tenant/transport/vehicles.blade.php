@extends('layouts.tenant')

@section('title', 'যানবাহন ব্যবস্থাপনা')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12M8 7a2 2 0 100-4 2 2 0 000 4zm0 0H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-4m0 0V5a2 2 0 10-4 0v2m0 0H8m8 0h4"></path>
                    </svg>
                    যানবাহন ব্যবস্থাপনা
                </h1>
                <p class="text-gray-600 mt-2">পরিবহন যানবাহন সেটআপ করুন</p>
            </div>
            <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                নতুন যানবাহন
            </button>
        </div>

        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($vehicles as $vehicle)
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">{{ $vehicle->vehicle_number }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $vehicle->vehicle_model }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $vehicle->year_made ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $vehicle->driver_name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948-.684l1.498-4.493a1 1 0 011.502-.684l1.498 4.493a1 1 0 00.948.684H17a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                            </svg>
                            <span>{{ $vehicle->driver_contact }}</span>
                        </div>
                        @if($vehicle->driver_license)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-5m-4 0V5a2 2 0 10-4 0v1m4 0a2 2 0 104 0m0 0V8a2 2 0 10-4 0v4"></path>
                            </svg>
                            <span>{{ $vehicle->driver_license }}</span>
                        </div>
                        @endif
                    </div>
                    @if($vehicle->description)
                    <p class="text-gray-600 text-sm mt-3">{{ $vehicle->description }}</p>
                    @endif
                </div>
                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <button onclick="editVehicle({{ $vehicle->id }}, '{{ $vehicle->vehicle_number }}', '{{ $vehicle->vehicle_model }}', '{{ $vehicle->year_made }}', '{{ $vehicle->driver_name }}', '{{ $vehicle->driver_contact }}', '{{ $vehicle->driver_license }}', '{{ $vehicle->description }}')" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium text-sm flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        এডিট
                    </button>
                    <form action="{{ route('tenant.transport.vehicles.destroy', $vehicle->id) }}" method="POST" class="flex-1" onsubmit="return confirm('আপনি কি এই যানবাহন মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium text-sm flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            মুছুন
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12M8 7a2 2 0 100-4 2 2 0 000 4zm0 0H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-4m0 0V5a2 2 0 10-4 0v2m0 0H8m8 0h4"></path>
                    </svg>
                    <p class="text-gray-600 font-semibold text-lg">কোনো যানবাহন নেই</p>
                    <p class="text-gray-500 mt-2">উপরের বোতাম থেকে নতুন যানবাহন যোগ করুন</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="vehicleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">নতুন যানবাহন যোগ করুন</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="vehicleForm" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="methodInput" name="_method" value="POST">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">যানবাহন নম্বর</label>
                <input type="text" name="vehicle_number" id="vehicle_number" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">যানবাহন মডেল</label>
                <input type="text" name="vehicle_model" id="vehicle_model" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">তৈরির সাল</label>
                <input type="text" name="year_made" id="year_made" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">চালকের নাম</label>
                <input type="text" name="driver_name" id="driver_name" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">চালকের কন্টাক্ট</label>
                <input type="text" name="driver_contact" id="driver_contact" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">চালকের লাইসেন্স নম্বর</label>
                <input type="text" name="driver_license" id="driver_license" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">বিবরণ</label>
                <textarea name="description" id="description" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" rows="3"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    বাতিল
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition-all font-medium">
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'নতুন যানবাহন যোগ করুন';
    document.getElementById('vehicleForm').reset();
    document.getElementById('methodInput').value = 'POST';
    document.getElementById('vehicleForm').action = "{{ route('tenant.transport.vehicles.store') }}";
    document.getElementById('vehicleModal').classList.remove('hidden');
}

function editVehicle(id, number, model, year, driver, contact, license, description) {
    document.getElementById('modalTitle').textContent = 'যানবাহন এডিট করুন';
    document.getElementById('vehicle_number').value = number;
    document.getElementById('vehicle_model').value = model;
    document.getElementById('year_made').value = year;
    document.getElementById('driver_name').value = driver;
    document.getElementById('driver_contact').value = contact;
    document.getElementById('driver_license').value = license;
    document.getElementById('description').value = description;
    document.getElementById('methodInput').value = 'PUT';
    document.getElementById('vehicleForm').action = "{{ route('tenant.transport.vehicles.update', ':id') }}".replace(':id', id);
    document.getElementById('vehicleModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('vehicleModal').classList.add('hidden');
}

document.getElementById('vehicleModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
