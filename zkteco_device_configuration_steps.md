# ZKTeco K50A Device Configuration - Step by Step

## 🎯 Current Status
আপনার ডিভাইস নেটওয়ার্কে সংযুক্ত কিন্তু ZKTeco সার্ভিস চালু নেই। নিচের ধাপগুলো অনুসরণ করুন:

## 📱 Device Configuration Steps

### Step 1: Device Access করুন
```
1. ZKTeco K50A device এর সামনে যান
2. Screen এ touch করুন বা Menu বাটন চাপুন
3. Admin password চাইলে দিন: 123456 (default)
```

### Step 2: Network Menu এ যান
```
Main Menu → System → Communication → TCP/IP
```

**অথবা:**
```
Main Menu → Communication → Network → TCP/IP
```

### Step 3: Network Settings Configure করুন

**Current Settings Check করুন:**
- IP Address: `192.168.1.201` (এটা ঠিক আছে)
- Subnet Mask: `255.255.255.0`
- Gateway: `192.168.1.1`

**Important: DNS Settings**
```
DNS Server: 8.8.8.8
DNS2: 8.8.4.4
```
⚠️ **DNS 0.0.0.0 থাকলে অবশ্যই change করুন!**

### Step 4: Communication Service Enable করুন

**এই settings গুলো check করুন:**
```
✅ TCP/IP Service: Enable
✅ Port: 4370
✅ UDP Communication: Enable
✅ Network Service: Enable
```

### Step 5: Settings Save করুন
```
1. OK বাটন চাপুন
2. "Save Settings?" → Yes
3. Device restart করুন (Power button 3 সেকেন্ড চেপে ধরুন)
```

## 🔧 Alternative Method (যদি উপরের menu structure আলাদা হয়)

### Method 2: Advanced Settings
```
Menu → Options → Communication Setup → Network
```

### Method 3: System Settings
```
Menu → System → Network → TCP/IP Settings
```

## ✅ Verification Steps

### Device এ Check করুন:
1. **Network Icon**: Screen এ network icon দেখা যাচ্ছে কিনা
2. **IP Display**: Device এ IP address show করছে কিনা
3. **Connection Status**: "Connected" বা similar status দেখা যাচ্ছে কিনা

### Web Interface এ Test করুন:
1. Browser এ যান: `http://iqranooraniacademy.smartpathshala.test/attendance/zkteco/settings`
2. "সংযোগ পরীক্ষা করুন" বাটনে click করুন
3. **Expected Result**: "সংযোগ সফল! ডিভাইস প্রস্তুত।"

## 🚨 Troubleshooting

### যদি Menu পাওয়া না যায়:
```
1. Device restart করুন
2. Admin password reset করুন
3. Factory reset করুন (last resort)
```

### যদি Settings save না হয়:
```
1. Admin privileges check করুন
2. Device memory full কিনা check করুন
3. Firmware update করুন
```

### যদি এখনও connection fail হয়:
```
1. Device completely restart করুন (power off/on)
2. Network cable check করুন
3. Router/Switch restart করুন
4. Different IP address try করুন (192.168.1.202)
```

## 📞 Emergency Steps

### যদি Device Lock হয়ে যায়:
```
1. Power button 10 সেকেন্ড চেপে ধরুন (force restart)
2. Factory reset: Menu → System → Factory Reset
3. Default password: 123456 বা 0
```

### যদি Network Settings access করতে না পারেন:
```
1. USB cable দিয়ে computer এর সাথে connect করুন
2. ZKTeco software দিয়ে configure করুন
3. Technical support contact করুন
```

## 🎉 Success Indicators

### Device এ দেখবেন:
- ✅ Network icon active
- ✅ IP address displayed
- ✅ "Connected" status

### Web Interface এ দেখবেন:
- ✅ "সংযোগ সফল!" message
- ✅ Device info (user count, record count)
- ✅ All diagnostic checks passed

---

## 📋 Quick Checklist

- [ ] Device menu access করেছি
- [ ] TCP/IP settings এ গেছি  
- [ ] DNS server set করেছি (8.8.8.8)
- [ ] TCP/IP service enable করেছি
- [ ] Port 4370 set করেছি
- [ ] Settings save করেছি
- [ ] Device restart করেছি
- [ ] Web interface এ test করেছি
- [ ] "সংযোগ সফল!" message পেয়েছি

**Configuration complete হলে device ready to use!** 🚀