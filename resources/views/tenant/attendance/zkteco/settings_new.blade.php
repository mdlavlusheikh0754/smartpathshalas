@extends('layouts.tenant')

@section('title', 'ZKTeco ডিভাইস সেটিংস')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ZKTeco ডিভাইস সেটিংস</h1>
            <p class="text-gray-600 mt-1">ডিভাইসের IP এবং পোর্ট কনফিগার করুন</p>
        </div>
        <a href="{{ route('tenant.attendance.zkteco.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i>ফিরে যান
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Device Configuration -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">ডিভাইস কনফিগারেশন</h3>
            
            <form id="settings-form" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ডিভাইস IP ঠিকানা</label>
                    <input type="text" name="device_ip" value="{{ $currentIp }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="192.168.1.201" required>
                    <p class="text-gray-500 text-sm mt-1">আপনার ZKTeco K50A ডিভাইসের IP ঠিকানা</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ডিভাইস পোর্ট</label>
                    <input type="number" name="device_port" value="{{ $currentPort }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="4370" min="1" max="65535" required>
                    <p class="text-gray-500 text-sm mt-1">সাধারণত 4370 (ডিফল্ট পোর্ট)</p>
                </div>

                <div class="flex gap-4">
                    <button type="button" id="test-connection-btn"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold transition-colors">
                        <i class="fas fa-plug mr-2"></i>সংযোগ পরীক্ষা করুন
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-colors">
                        <i class="fas fa-save mr-2"></i>সেটিংস সংরক্ষণ করুন
                    </button>
                </div>
                
                <!-- Debug buttons -->
                <div class="mt-4 space-y-2">
                    <button type="button" onclick="alert('JavaScript কাজ করছে!'); console.log('Inline test successful');" 
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg text-sm">
                        🧪 Inline JavaScript টেস্ট
                    </button>
                    <button type="button" onclick="window.open('test_zkteco_simple.html', '_blank')" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg text-sm">
                        🔧 Simple Test Tool খুলুন
                    </button>
                    <button type="button" onclick="manualConnectionTest()" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg text-sm">
                        🚀 Manual Connection Test
                    </button>
                </div>
            </form>
        </div>

        <!-- Connection Status -->
        <div id="connection-status" class="mt-6 hidden">
            <!-- Status will be shown here -->
        </div>
        
        <!-- JavaScript Test Section -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
            <h4 class="font-semibold text-yellow-800 mb-2">JavaScript টেস্ট</h4>
            <p class="text-yellow-700 text-sm mb-3">যদি "সংযোগ পরীক্ষা করুন" বাটন কাজ না করে, তাহলে নিচের বাটনটি চেষ্টা করুন:</p>
            <button type="button" onclick="alert('JavaScript কাজ করছে!'); console.log('Inline test successful');" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm">
                Inline JavaScript টেস্ট
            </button>
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

<script>
console.log('ZKTeco Settings Page Loaded');

// Simple, direct functions
function showLoading(text) {
    console.log('showLoading:', text);
    const modal = document.getElementById('loading-modal');
    const textEl = document.getElementById('loading-text');
    if (modal && textEl) {
        textEl.textContent = text || 'প্রক্রিয়াকরণ...';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideLoading() {
    console.log('hideLoading called');
    const modal = document.getElementById('loading-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function showStatus(message, type) {
    console.log('showStatus:', message, type);
    const statusDiv = document.getElementById('connection-status');
    if (!statusDiv) return;
    
    let bgClass, iconClass;
    if (type === 'success') {
        bgClass = 'bg-green-50 border-green-200 text-green-800';
        iconClass = 'fas fa-check-circle text-green-600';
    } else if (type === 'error') {
        bgClass = 'bg-red-50 border-red-200 text-red-800';
        iconClass = 'fas fa-exclamation-circle text-red-600';
    } else {
        bgClass = 'bg-blue-50 border-blue-200 text-blue-800';
        iconClass = 'fas fa-info-circle text-blue-600';
    }
    
    statusDiv.innerHTML = `
        <div class="rounded-lg border p-4 ${bgClass}">
            <div class="flex items-center">
                <i class="${iconClass} mr-3"></i>
                <span class="font-medium">${message}</span>
            </div>
        </div>
    `;
    statusDiv.classList.remove('hidden');
    
    setTimeout(() => {
        statusDiv.classList.add('hidden');
    }, 8000);
}

function testConnection() {
    console.log('testConnection called');
    
    const ipInput = document.querySelector('input[name="device_ip"]');
    const portInput = document.querySelector('input[name="device_port"]');
    
    if (!ipInput || !portInput) {
        console.error('Input fields not found');
        showStatus('Input fields not found', 'error');
        return;
    }
    
    const ip = ipInput.value;
    const port = portInput.value;
    
    console.log('Testing:', ip, port);
    
    if (!ip || !port) {
        showStatus('IP ঠিকানা এবং পোর্ট পূরণ করুন', 'error');
        return;
    }
    
    showLoading('সংযোগ পরীক্ষা করা হচ্ছে...');
    
    // Make the API request
    const url = '{{ route("tenant.attendance.zkteco.test") }}';
    const token = '{{ csrf_token() }}';
    
    console.log('Request URL:', url);
    console.log('CSRF Token:', token);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            device_ip: ip,
            device_port: parseInt(port)
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        hideLoading();
        
        if (data.success) {
            let message = 'সংযোগ সফল! ডিভাইস প্রস্তুত।';
            if (data.device_info) {
                message += ` (ব্যবহারকারী: ${data.device_info.user_count || 0}, রেকর্ড: ${data.device_info.record_count || 0})`;
            }
            showStatus(message, 'success');
        } else {
            showStatus(data.message || 'সংযোগ ব্যর্থ', 'error');
        }
    })
    .catch(error => {
        console.error('Connection test error:', error);
        hideLoading();
        showStatus('সংযোগ পরীক্ষায় ত্রুটি: ' + error.message, 'error');
    });
}

function manualConnectionTest() {
    console.log('Manual connection test started');
    
    const ip = document.querySelector('input[name="device_ip"]').value || '192.168.1.201';
    const port = document.querySelector('input[name="device_port"]').value || '4370';
    
    showLoading('Manual test চলছে...');
    
    setTimeout(() => {
        hideLoading();
        
        const testResults = `
            <div class="space-y-2">
                <div class="text-sm">
                    <strong>Device IP:</strong> ${ip}<br>
                    <strong>Device Port:</strong> ${port}<br>
                    <strong>Test Time:</strong> ${new Date().toLocaleString()}<br>
                    <strong>Status:</strong> Manual test completed
                </div>
                <div class="mt-3 p-3 bg-blue-100 rounded">
                    <strong>Next Steps:</strong><br>
                    1. Check if device is powered on<br>
                    2. Verify network cable connection<br>
                    3. Confirm IP settings on device<br>
                    4. Try ping test: <code>ping ${ip}</code>
                </div>
            </div>
        `;
        
        const statusDiv = document.getElementById('connection-status');
        statusDiv.innerHTML = `
            <div class="rounded-lg border p-4 bg-blue-50 border-blue-200 text-blue-800">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                    <div class="flex-1">
                        ${testResults}
                    </div>
                </div>
            </div>
        `;
        statusDiv.classList.remove('hidden');
        
    }, 2000);
}

// Attach event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Attach click event to test connection button
    const testBtn = document.getElementById('test-connection-btn');
    if (testBtn) {
        testBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Test connection button clicked');
            testConnection();
        });
        console.log('Event listener attached to test connection button');
    } else {
        console.error('Test connection button not found');
    }
});

// Make functions globally available
window.testConnection = testConnection;
window.manualConnectionTest = manualConnectionTest;

console.log('All functions loaded and ready');
</script>

@endsection
</content>