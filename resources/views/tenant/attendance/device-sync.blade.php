@extends('layouts.tenant')

@section('title', 'Device Sync Management')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Device Sync Management</h1>
            <p class="text-gray-600 mt-1">ZKTime.Net ডিভাইসের সাথে ডেটা সিঙ্ক করুন</p>
        </div>
        <a href="{{ route('tenant.attendance.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i>ফিরে যান
        </a>
    </div>

    <!-- Sync Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Commands</p>
                    <p class="text-3xl font-bold text-yellow-600" id="pending-count">-</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold text-green-600" id="completed-count">-</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Failed</p>
                    <p class="text-3xl font-bold text-red-600" id="failed-count">-</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Commands</p>
                    <p class="text-3xl font-bold text-blue-600" id="total-count">-</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-database text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Sync Actions</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="bulkSyncAllStudents()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-users"></i>
                সকল ছাত্র Sync করুন
            </button>
            
            <button onclick="refreshSyncStatus()" 
                    class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-sync-alt"></i>
                Status Refresh করুন
            </button>
            
            <button onclick="viewSyncLogs()" 
                    class="bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-file-alt"></i>
                Sync Logs দেখুন
            </button>
        </div>
    </div>

    <!-- Recent Commands -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Recent Sync Commands</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">ছাত্রের নাম</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">রোল নম্বর</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">ক্লাস</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Command Type</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">সময়</th>
                    </tr>
                </thead>
                <tbody id="recent-commands">
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mt-8">
        <h3 class="text-lg font-bold text-blue-900 mb-4">
            <i class="fas fa-info-circle mr-2"></i>ZKTime.Net Integration Instructions
        </h3>
        <div class="text-blue-800 space-y-2">
            <p><strong>Step 1:</strong> এই page থেকে students sync করুন</p>
            <p><strong>Step 2:</strong> ZKTime.Net software খুলুন</p>
            <p><strong>Step 3:</strong> Device Management → Upload Users to Device</p>
            <p><strong>Step 4:</strong> Device এ manually fingerprint enroll করুন</p>
            <p><strong>Step 5:</strong> Bridge script চালু করুন: <code>zktime_sync_scheduler.bat</code></p>
        </div>
    </div>
</div>

<script>
// Load sync status on page load
document.addEventListener('DOMContentLoaded', function() {
    refreshSyncStatus();
});

function refreshSyncStatus() {
    fetch('/api/device/sync-status')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update stats
                document.getElementById('pending-count').textContent = data.stats.pending;
                document.getElementById('completed-count').textContent = data.stats.completed;
                document.getElementById('failed-count').textContent = data.stats.failed;
                document.getElementById('total-count').textContent = data.stats.total;
                
                // Update recent commands table
                updateRecentCommandsTable(data.recent_commands);
            }
        })
        .catch(error => {
            console.error('Error fetching sync status:', error);
            showNotification('Status load করতে ত্রুটি হয়েছে', 'error');
        });
}

function updateRecentCommandsTable(commands) {
    const tbody = document.getElementById('recent-commands');
    
    if (commands.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">
                    কোনো sync command পাওয়া যায়নি
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = commands.map(command => {
        const statusClass = command.status === 'completed' ? 'bg-green-100 text-green-800' :
                           command.status === 'failed' ? 'bg-red-100 text-red-800' :
                           'bg-yellow-100 text-yellow-800';
        
        const statusText = command.status === 'completed' ? 'সম্পন্ন' :
                          command.status === 'failed' ? 'ব্যর্থ' : 'অপেক্ষমান';
        
        return `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4">${command.name_bn || 'N/A'}</td>
                <td class="py-3 px-4">${command.roll || 'N/A'}</td>
                <td class="py-3 px-4">${command.class || 'N/A'}</td>
                <td class="py-3 px-4">
                    <span class="text-sm font-medium">${command.command_type}</span>
                </td>
                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-600">
                    ${new Date(command.created_at).toLocaleString('bn-BD')}
                </td>
            </tr>
        `;
    }).join('');
}

function bulkSyncAllStudents() {
    if (confirm('সকল ছাত্রকে Device এ sync করবেন? এটি কিছু সময় নিতে পারে।')) {
        showNotification('Bulk sync শুরু হয়েছে...', 'info');
        
        fetch('/api/students/bulk-sync-to-device', { 
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(refreshSyncStatus, 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Bulk sync করতে ত্রুটি হয়েছে', 'error');
        });
    }
}

function viewSyncLogs() {
    // Open sync logs in a new window/modal
    alert('Sync logs feature coming soon!\n\nLogs are currently saved in: zktime_sync.log');
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Auto-refresh every 30 seconds
setInterval(refreshSyncStatus, 30000);
</script>
@endsection