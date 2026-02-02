# ZKTeco K50A Integration Guide

## Overview
এই গাইডটি আপনার স্কুল ম্যানেজমেন্ট সিস্টেমে ZKTeco K50A বায়োমেট্রিক ডিভাইস ইন্টিগ্রেট করার জন্য।

## Features
- ✅ বায়োমেট্রিক ডিভাইস থেকে উপস্থিতি সিঙ্ক
- ✅ শিক্ষার্থীদের তথ্য ডিভাইসে আপলোড
- ✅ রিয়েল-টাইম ডিভাইস স্ট্যাটাস চেক
- ✅ অটোমেটিক সিঙ্ক (Cron Job)
- ✅ ম্যানুয়াল সিঙ্ক অপশন
- ✅ ডিভাইস রেকর্ড ক্লিয়ার
- ✅ কনফিগারেবল সেটিংস

## Device Setup

### 1. Network Configuration
1. ZKTeco K50A ডিভাইসটি আপনার নেটওয়ার্কে সংযুক্ত করুন
2. ডিভাইসের মেনুতে যান: **Menu → Communication → TCP/IP**
3. একটি স্থির IP ঠিকানা সেট করুন (যেমন: 192.168.1.201)
4. Port: 4370 (ডিফল্ট)
5. সেটিংস সেভ করুন

### 2. Device Settings
1. **Menu → System → Date Time** - সঠিক সময় সেট করুন
2. **Menu → Personnel → User Mgt** - ব্যবহারকারী ম্যানেজমেন্ট চেক করুন
3. **Menu → Attendance → Att. Status** - উপস্থিতি স্ট্যাটাস চেক করুন

## System Configuration

### 1. Environment Variables
`.env` ফাইলে নিম্নলিখিত কনফিগারেশন যোগ করুন:

```env
# ZKTeco Device Configuration
ZKTECO_DEVICE_IP=192.168.1.201
ZKTECO_DEVICE_PORT=4370
ZKTECO_CONNECTION_TIMEOUT=5
ZKTECO_MAX_RETRY_ATTEMPTS=3
ZKTECO_AUTO_SYNC_ENABLED=false
ZKTECO_SYNC_INTERVAL_MINUTES=30
ZKTECO_CLEAR_DEVICE_AFTER_SYNC=false
ZKTECO_USER_PRIVILEGE_LEVEL=0
ZKTECO_USE_STUDENT_ROLL_AS_USER_ID=true
ZKTECO_ENABLE_LOGGING=true
ZKTECO_LOG_LEVEL=info
```

### 2. Database Migration
নিম্নলিখিত migrations চালান:

```bash
php artisan migrate --path=database/migrations/tenant/2026_02_01_030000_add_device_fields_to_students_table.php
php artisan migrate --path=database/migrations/tenant/2026_02_01_030001_add_zkteco_fields_to_student_attendances_table.php
```

## Usage

### 1. Web Interface
1. **Attendance → ZKTeco Device** মেনুতে যান
2. **Device Status** চেক করুন
3. **User Sync** করে শিক্ষার্থীদের ডিভাইসে আপলোড করুন
4. **Attendance Sync** করে উপস্থিতি ডাউনলোড করুন

### 2. Command Line
```bash
# Manual sync
php artisan zkteco:sync

# Sync and clear device records
php artisan zkteco:sync --clear
```

### 3. Automatic Sync (Cron Job)
`app/Console/Kernel.php` ফাইলে যোগ করুন:

```php
protected function schedule(Schedule $schedule)
{
    // Every 30 minutes
    $schedule->command('zkteco:sync')->everyThirtyMinutes();
    
    // Or every hour with clear
    $schedule->command('zkteco:sync --clear')->hourly();
}
```

## API Endpoints

### Device Status
```
GET /attendance/zkteco/status
```

### Sync Attendance
```
POST /attendance/zkteco/sync
```

### Sync Users
```
POST /attendance/zkteco/sync-users
```

### Clear Records
```
POST /attendance/zkteco/clear
```

### Test Connection
```
POST /attendance/zkteco/test
```

## Troubleshooting

### Common Issues

#### 1. Connection Failed
- ডিভাইসের IP ঠিকানা চেক করুন
- নেটওয়ার্ক সংযোগ চেক করুন
- Firewall settings চেক করুন
- ডিভাইসের TCP/IP সেটিংস চেক করুন

#### 2. No Records Found
- ডিভাইসে উপস্থিতি রেকর্ড আছে কিনা চেক করুন
- ডিভাইসের সময় সঠিক আছে কিনা চেক করুন

#### 3. Student Not Found
- শিক্ষার্থীর Roll Number বা Student ID ডিভাইসের User ID এর সাথে মিলছে কিনা চেক করুন
- Database এ শিক্ষার্থীর তথ্য আছে কিনা চেক করুন

#### 4. User Sync Failed
- ডিভাইসে পর্যাপ্ত মেমরি আছে কিনা চেক করুন
- User ID unique কিনা চেক করুন

### Log Files
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# ZKTeco specific logs
grep "ZKTeco" storage/logs/laravel.log
```

## Device Specifications

### ZKTeco K50A
- **Fingerprint Capacity**: 3,000
- **Transaction Capacity**: 100,000
- **Communication**: TCP/IP, USB
- **Power**: DC 12V/3A
- **Operating Temperature**: 0°C ~ 45°C
- **Humidity**: 20% ~ 80%

## Security Considerations

1. **Network Security**
   - ডিভাইসটি secure network এ রাখুন
   - Firewall rules সেট করুন
   - Regular firmware updates করুন

2. **Data Privacy**
   - Biometric data encryption চালু রাখুন
   - Access logs monitor করুন
   - Regular backup নিন

## Support

### Technical Support
- Email: support@smartpathshala.com
- Phone: +880-XXXX-XXXXXX

### Documentation
- ZKTeco Official Manual
- Laravel Documentation
- System Admin Guide

## Version History

### v1.0.0 (2026-02-01)
- Initial ZKTeco K50A integration
- Basic sync functionality
- Web interface
- Command line tools
- Auto sync support

---

**Note**: এই integration শুধুমাত্র ZKTeco K50A মডেলের জন্য tested। অন্য মডেলের জন্য কিছু modification প্রয়োজন হতে পারে।