@extends('layouts.tenant')

@section('title', 'রুট ব্যবস্থাপনা')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    রুট ব্যবস্থাপনা
                </h1>
                <p class="text-gray-600 mt-2">পরিবহন রুট সেটআপ করুন</p>
            </div>
            <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                নতুন রুট
            </button>
        </div>

        <!-- Routes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($routes as $route)
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900">{{ $route->route_title }}</h3>
                        <div class="mt-3 space-y-2">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1m2 1v2.5M2 7l2-1m-2 1l2 1m-2-1v2.5"></path>
                                </svg>
                                <span class="text-sm">{{ $route->start_point }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm">{{ $route->end_point }}</span>
                            </div>
                        </div>
                        @if($route->description)
                        <p class="text-gray-600 text-sm mt-3">{{ $route->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <button onclick="editRoute({{ $route->id }}, '{{ $route->route_title }}', '{{ $route->start_point }}', '{{ $route->end_point }}', '{{ $route->description }}')" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium text-sm flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        এডিট
                    </button>
                    <form action="{{ route('tenant.transport.routes.destroy', $route->id) }}" method="POST" class="flex-1" onsubmit="return confirm('আপনি কি এই রুট মুছে ফেলতে চান?')">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <p class="text-gray-600 font-semibold text-lg">কোনো রুট নেই</p>
                    <p class="text-gray-500 mt-2">উপরের বোতাম থেকে নতুন রুট যোগ করুন</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="routeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">নতুন রুট যোগ করুন</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="routeForm" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="methodInput" name="_method" value="POST">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">রুটের নাম</label>
                <input type="text" name="route_title" id="route_title" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">শুরু (Start Point)</label>
                <input type="text" name="start_point" id="start_point" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">শেষ (End Point)</label>
                <input type="text" name="end_point" id="end_point" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" required>
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
    document.getElementById('modalTitle').textContent = 'নতুন রুট যোগ করুন';
    document.getElementById('routeForm').reset();
    document.getElementById('methodInput').value = 'POST';
    document.getElementById('routeForm').action = "{{ route('tenant.transport.routes.store') }}";
    document.getElementById('routeModal').classList.remove('hidden');
}

function editRoute(id, title, start, end, description) {
    document.getElementById('modalTitle').textContent = 'রুট এডিট করুন';
    document.getElementById('route_title').value = title;
    document.getElementById('start_point').value = start;
    document.getElementById('end_point').value = end;
    document.getElementById('description').value = description;
    document.getElementById('methodInput').value = 'PUT';
    document.getElementById('routeForm').action = "{{ route('tenant.transport.routes.update', ':id') }}".replace(':id', id);
    document.getElementById('routeModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('routeModal').classList.add('hidden');
}

document.getElementById('routeModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
