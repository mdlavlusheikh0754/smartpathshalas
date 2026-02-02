<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSRF Token Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-box { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; background: #007bff; color: white; border: none; border-radius: 5px; }
        button:hover { background: #0056b3; }
        .token-display { font-family: monospace; background: #f8f9fa; padding: 10px; border-radius: 3px; word-break: break-all; }
        h1 { color: #333; text-align: center; }
        h3 { color: #555; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔒 CSRF Token Test - স্মার্ট পাঠশালা</h1>
        
        <div class="test-box info">
            <h3>📋 Current CSRF Token:</h3>
            <div class="token-display" id="csrf-token-display">Loading...</div>
        </div>
        
        <div class="test-box info">
            <h3>⚙️ Session Configuration:</h3>
            <p><strong>Session Driver:</strong> {{ config('session.driver') }}</p>
            <p><strong>Session Domain:</strong> {{ config('session.domain') ?? 'null' }}</p>
            <p><strong>Session Same Site:</strong> {{ config('session.same_site') }}</p>
            <p><strong>App URL:</strong> {{ config('app.url') }}</p>
            <p><strong>Environment:</strong> {{ app()->environment() }}</p>
        </div>
        
        <div class="test-box">
            <h3>🧪 Test CSRF Token:</h3>
            <button onclick="testCSRF()">Test CSRF Token</button>
            <button onclick="testWithoutCSRF()">Test Without CSRF (Should Fail)</button>
            <div id="test-result"></div>
        </div>

        <div class="test-box info">
            <h3>📝 Instructions:</h3>
            <ul>
                <li>✅ যদি "CSRF Token Working!" দেখায়, তাহলে আপনার সমস্যা সমাধান হয়েছে</li>
                <li>❌ যদি "419 Page Expired" দেখায়, তাহলে আরও কিছু পদক্ষেপ নিতে হবে</li>
                <li>🔄 ব্রাউজারের কুকি ক্লিয়ার করে আবার চেষ্টা করুন</li>
                <li>🔄 Hard refresh করুন (Ctrl+F5)</li>
            </ul>
        </div>
    </div>

    <script>
        // Display CSRF token
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            document.getElementById('csrf-token-display').textContent = token.content;
        } else {
            document.getElementById('csrf-token-display').textContent = '❌ CSRF token not found!';
        }

        // Test CSRF token
        function testCSRF() {
            const resultDiv = document.getElementById('test-result');
            resultDiv.innerHTML = '<p>🔄 Testing CSRF token...</p>';
            
            fetch('/test-csrf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token ? token.content : '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ test: 'data' })
            })
            .then(response => {
                if (response.ok) {
                    return response.json().then(data => {
                        resultDiv.innerHTML = '<div class="test-box success"><p>✅ ' + data.message + '</p></div>';
                    });
                } else if (response.status === 419) {
                    resultDiv.innerHTML = '<div class="test-box error"><p>❌ 419 Page Expired - CSRF Token Failed!</p><p>আপনার ব্রাউজারের কুকি ক্লিয়ার করুন এবং আবার চেষ্টা করুন।</p></div>';
                } else {
                    resultDiv.innerHTML = '<div class="test-box error"><p>❌ Error: ' + response.status + '</p></div>';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<div class="test-box error"><p>❌ Network Error: ' + error.message + '</p></div>';
            });
        }

        // Test without CSRF token (should fail)
        function testWithoutCSRF() {
            const resultDiv = document.getElementById('test-result');
            resultDiv.innerHTML = '<p>🔄 Testing without CSRF token (should fail)...</p>';
            
            fetch('/test-csrf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                    // No CSRF token
                },
                body: JSON.stringify({ test: 'data' })
            })
            .then(response => {
                if (response.status === 419) {
                    resultDiv.innerHTML = '<div class="test-box success"><p>✅ Good! 419 error as expected without CSRF token</p></div>';
                } else {
                    resultDiv.innerHTML = '<div class="test-box error"><p>❌ Unexpected: Should have failed with 419 but got: ' + response.status + '</p></div>';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<div class="test-box error"><p>❌ Network Error: ' + error.message + '</p></div>';
            });
        }
    </script>
</body>
</html>