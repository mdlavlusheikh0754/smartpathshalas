@extends('layouts.tenant')

@section('title', 'ZKTeco K50A ডিভাইস ম্যানেজমেন্ট')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ZKTeco K50A ডিভাইস ম্যানেজমেন্ট</h1>
            <p class="text-gray-600 mt-1">বায়োমেট্রিক ডিভাইস থেকে উপস্থিতি সিঙ্ক করুন</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tenant.attendance.zkteco.settings') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-cog mr-2"></i>সেটিংস
            </a>
            <a href="{{ route('tenant.attendance.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>ফিরে যান
            </a>
        </div>
    </div>

    <!-- Device Status Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">ডিভাইস স্ট্যাটাস</h3>
            <button onclick="checkDeviceStatus()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-sync-alt mr-2"></i>স্ট্যাটাস চেক করুন
            </button>
        </div>
        
        <div id="device-status" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                    <span class="text-gray-600">সংযোগ স্ট্যাটাস: <span class="font-bold">অজানা</span></span>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <span class="text-gray-600">ব্যবহারকারী: <span class="font-bold" id="user-count">-</span></span>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <span class="text-gray-600">রেকর্ড: <span class="font-bold" id="record-count">-</span></span>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <span class="text-gray-600">ফার্মওয়্যার: <span class="font-bold" id="firmware-version">-</span></span>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sync Attendance -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-download text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">উপস্থিতি সিঙ্ক</h3>
                    <p class="text-gray-600 text-sm">ডিভাইস থেকে উপস্থিতি ডাউনলোড করুন</p>
                </div>
            </div>
            <button onclick="syncAttendance()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>উপস্থিতি সিঙ্ক করুন
            </button>
        </div>

        <!-- Sync Users -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">ব্যবহারকারী সিঙ্ক</h3>
                    <p class="text-gray-600 text-sm">শিক্ষার্থীদের ডিভাইসে যোগ করুন</p>
                </div>
            </div>
            <button onclick="syncUsers()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold transition-colors">
                <i class="fas fa-upload mr-2"></i>ব্যবহারকারী সিঙ্ক করুন
            </button>
        </div>

        <!-- Clear Records -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-trash text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">রেকর্ড মুছুন</h3>
                    <p class="text-gray-600 text-sm">ডিভাইসের সকল রেকর্ড মুছে ফেলুন</p>
                </div>
            </div>
            <button onclick="clearRecords()" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-bold transition-colors">
                <i class="fas fa-trash-alt mr-2"></i>রেকর্ড মুছুন
            </button>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">কার্যক্রম লগ</h3>
        <div id="activity-log" class="space-y-2 max-h-96 overflow-y-auto">
            <div class="text-gray-500 text-center py-8">
                <i class="fas fa-info-circle text-4xl mb-2"></i>
                <p>কোনো কার্যক্রম এখনো সম্পন্ন হয়নি</p>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-700 font-medium" id="loading-text">প্রক্রিয়াকরণ...</p>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let activityLog = [];

function showLoading(text = 'প্রক্রিয়াকরণ...') {
    document.getElementById('loading-text').textContent = text;
    document.getElementById('loading-modal').classList.remove('hidden');
    document.getElementById('loading-modal').classList.add('flex');
}

function hideLoading() {
    document.getElementById('loading-modal').classList.add('hidden');
    document.getElementById('loading-modal').classList.remove('flex');
}

function addToLog(message, type = 'info') {
    const timestamp = new Date().toLocaleString('bn-BD');
    const logEntry = {
        message,
        type,
        timestamp
    };
    
    activityLog.unshift(logEntry);
    updateLogDisplay();
}

function updateLogDisplay() {
    const logContainer = document.getElementById('activity-log');
    
    if (activityLog.length === 0) {
        logContainer.innerHTML = `
            <div class="text-gray-500 text-center py-8">
                <i class="fas fa-info-circle text-4xl mb-2"></i>
                <p>কোনো কার্যক্রম এখনো সম্পন্ন হয়নি</p>
            </div>
        `;
        return;
    }
    
    const logHtml = activityLog.map(entry => {
        const iconClass = entry.type === 'success' ? 'fas fa-check-circle text-green-600' :
                         entry.type === 'error' ? 'fas fa-exclamation-circle text-red-600' :
                         'fas fa-info-circle text-blue-600';
        
        const bgClass = entry.type === 'success' ? 'bg-green-50 border-green-200' :
                       entry.type === 'error' ? 'bg-red-50 border-red-200' :
                       'bg-blue-50 border-blue-200';
        
        return `
            <div class="flex items-start p-3 rounded-lg border ${bgClass}">
                <i class="${iconClass} mt-1 mr-3"></i>
                <div class="flex-1">
                    <p class="text-gray-800">${entry.message}</p>
                    <p class="text-xs text-gray-500 mt-1">${entry.timestamp}</p>
                </div>
            </div>
        `;
    }).join('');
    
    logContainer.innerHTML = logHtml;
}

function checkDeviceStatus() {
    showLoading('ডিভাইস স্ট্যাটাস চেক করা হচ্ছে...');
    
    fetch('{{ route("tenant.attendance.zkteco.status") }}')
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            const statusContainer = document.getElementById('device-status');
            
            if (data.success && data.connected) {
                const info = data.device_info;
                statusContainer.innerHTML = `
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-green-800">সংযোগ স্ট্যাটাস: <span class="font-bold">সংযুক্ত</span></span>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <span class="text-blue-800">ব্যবহারকারী: <span class="font-bold">${info.user_count || 0}</span></span>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <span class="text-purple-800">রেকর্ড: <span class="font-bold">${info.record_count || 0}</span></span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <span class="text-gray-800">ফার্মওয়্যার: <span class="font-bold">${info.firmware_version || 'N/A'}</span></span>
                    </div>
                `;
                addToLog('ডিভাইস সফলভাবে সংযুক্ত', 'success');
            } else {
                statusContainer.innerHTML = `
                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-red-800">সংযোগ স্ট্যাটাস: <span class="font-bold">বিচ্ছিন্ন</span></span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <span class="text-gray-600">ব্যবহারকারী: <span class="font-bold">-</span></span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <span class="text-gray-600">রেকর্ড: <span class="font-bold">-</span></span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <span class="text-gray-600">ফার্মওয়্যার: <span class="font-bold">-</span></span>
                    </div>
                `;
                addToLog(data.message || 'ডিভাইসের সাথে সংযোগ ব্যর্থ', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            addToLog('ডিভাইস স্ট্যাটাস চেক করতে ত্রুটি: ' + error.message, 'error');
        });
}

function syncAttendance() {
    showLoading('উপস্থিতি সিঙ্ক করা হচ্ছে...');
    
    fetch('{{ route("tenant.attendance.zkteco.sync") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            addToLog(`${data.synced_count} টি রেকর্ড সফলভাবে সিঙ্ক করা হয়েছে (মোট: ${data.total_records})`, 'success');
            
            if (data.errors && data.errors.length > 0) {
                data.errors.forEach(error => {
                    addToLog(error, 'error');
                });
            }
        } else {
            addToLog(data.message || 'উপস্থিতি সিঙ্ক করতে ব্যর্থ', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        addToLog('উপস্থিতি সিঙ্ক করতে ত্রুটি: ' + error.message, 'error');
    });
}

function syncUsers() {
    if (!confirm('সকল শিক্ষার্থীকে ডিভাইসে যোগ করতে চান? এটি কিছু সময় নিতে পারে।')) {
        return;
    }
    
    showLoading('ব্যবহারকারী সিঙ্ক করা হচ্ছে...');
    
    fetch('{{ route("tenant.attendance.zkteco.sync-users") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            addToLog(`${data.synced_count} জন শিক্ষার্থী ডিভাইসে যোগ করা হয়েছে (মোট: ${data.total_students})`, 'success');
            
            if (data.errors && data.errors.length > 0) {
                data.errors.forEach(error => {
                    addToLog(error, 'error');
                });
            }
        } else {
            addToLog(data.message || 'ব্যবহারকারী সিঙ্ক করতে ব্যর্থ', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        addToLog('ব্যবহারকারী সিঙ্ক করতে ত্রুটি: ' + error.message, 'error');
    });
}

function clearRecords() {
    if (!confirm('আপনি কি নিশ্চিত যে ডিভাইসের সকল রেকর্ড মুছে ফেলতে চান? এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।')) {
        return;
    }
    
    showLoading('রেকর্ড মুছে ফেলা হচ্ছে...');
    
    fetch('{{ route("tenant.attendance.zkteco.clear") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            addToLog('ডিভাইসের সকল রেকর্ড সফলভাবে মুছে ফেলা হয়েছে', 'success');
        } else {
            addToLog(data.message || 'রেকর্ড মুছতে ব্যর্থ', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        addToLog('রেকর্ড মুছতে ত্রুটি: ' + error.message, 'error');
    });
}

// Auto-check device status on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(checkDeviceStatus, 1000);
});
</script>
@endsection