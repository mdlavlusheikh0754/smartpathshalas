# ZKTeco K50A Device Setup Guide

## 🔧 Hardware Setup

### ১. ডিভাইস Unboxing
- ZKTeco K50A device
- Power adapter (12V/3A)
- Ethernet cable
- Mounting screws
- User manual

### ২. Physical Installation
1. **Location নির্বাচন করুন:**
   - দেয়ালে mount করুন (eye level এ)
   - Direct sunlight এড়িয়ে চলুন
   - Network cable reach করতে পারে এমন জায়গায়

2. **Power Connection:**
   - 12V power adapter connect করুন
   - Device চালু হলে screen এ logo দেখাবে

3. **Network Connection:**
   - Ethernet cable দিয়ে router/switch এর সাথে connect করুন
   - Network LED indicator check করুন

## 🌐 Network Configuration

### Device Menu Navigation:
```
Power Button চেপে device চালু করুন
↓
Menu বাটন চাপুন
↓
Admin password দিন (default: 123456)
↓
Communication → TCP/IP
```

### Network Settings:
```
IP Address: 192.168.1.201
Subnet Mask: 255.255.255.0
Gateway: 192.168.1.1
DNS: 8.8.8.8
DNS2: 8.8.4.4 (optional)
Port: 4370
```

**Important Notes:**
- DNS: 0.0.0.0 থাকলে অবশ্যই change করুন
- 8.8.8.8 = Google Public DNS (recommended)
- 1.1.1.1 = Cloudflare DNS (alternative)
- আপনার local router এর DNS ও ব্যবহার করতে পারেন

### Settings Save করুন:
- OK বাটন চাপুন
- Device restart করুন
- Network LED check করুন

## 🖥️ System Configuration

### ১. Web Interface Access:
```
http://your-domain.com/attendance/zkteco/settings
```

### ২. Device Settings:
- **Device IP**: `192.168.1.201`
- **Device Port**: `4370`
- **Save Settings** click করুন

### ৩. Connection Test:
- **Test Connection** বাটনে click করুন
- Success message দেখার জন্য অপেক্ষা করুন

## 👥 User Management

### ১. Student Data Sync:
```
Attendance → ZKTeco Device → User Sync
```

### ২. Fingerprint Enrollment:
1. Device এ **Menu → User Mgt → New User**
2. User ID দিন (Student Roll Number)
3. Name দিন
4. **Fingerprint** select করুন
5. আঙুল scan করুন (3 বার)
6. **Save** করুন

### ৩. Bulk User Upload:
- System থেকে **Sync Users** করুন
- Device এ users automatically add হবে
- Manual fingerprint enrollment করতে হবে

## 📊 Attendance Process

### ১. Daily Attendance:
1. Students fingerprint scan করবে
2. Device এ attendance record হবে
3. System থেকে **Sync Attendance** করুন
4. Database এ data save হবে

### ২. Auto Sync Setup:
```bash
# Cron job add করুন
php artisan zkteco:sync --clear
```

## 🔧 Troubleshooting

### Connection Issues:

#### ❌ "Device not reachable"
**Solutions:**
- Power cable check করুন
- Network cable check করুন
- Router/Switch status check করুন
- IP address verify করুন

#### ❌ "Port not accessible"
**Solutions:**
- Device TCP/IP settings check করুন
- Port 4370 open আছে কিনা check করুন
- Firewall settings check করুন
- Device restart করুন

#### ❌ "No response from device"
**Solutions:**
- Device model verify করুন (K50A)
- Firmware update করুন
- Factory reset করুন
- Technical support contact করুন

### User Management Issues:

#### ❌ "User not found"
**Solutions:**
- Student Roll Number match করুন
- Device User ID check করুন
- Database sync করুন

#### ❌ "Fingerprint not recognized"
**Solutions:**
- Fingerprint quality check করুন
- Re-enroll fingerprint করুন
- Sensor clean করুন

## 📱 Device Menu Structure

```
Main Menu
├── User Mgt (User Management)
│   ├── New User
│   ├── Edit User
│   └── Delete User
├── Attendance
│   ├── Att. Status
│   └── Att. Record
├── System
│   ├── Date Time
│   ├── Auto Test
│   └── Factory Reset
├── Communication
│   ├── TCP/IP
│   ├── Serial Port
│   └── USB
└── Options
    ├── System Info
    ├── Personalize
    └── Power Mgt
```

## 🔐 Security Settings

### Default Passwords:
- **Admin Password**: 123456
- **User Password**: (none)

### Change Admin Password:
```
Menu → System → Password → Admin Password
```

### Access Control:
- শুধুমাত্র authorized users access দিন
- Regular password change করুন
- Physical security maintain করুন

## 📞 Support Information

### Technical Support:
- **Email**: support@smartpathshala.com
- **Phone**: +880-XXXX-XXXXXX
- **Documentation**: ZKTECO_INTEGRATION_GUIDE.md

### Device Specifications:
- **Model**: ZKTeco K50A
- **Fingerprint Capacity**: 3,000
- **Transaction Capacity**: 100,000
- **Communication**: TCP/IP, USB
- **Power**: DC 12V/3A
- **Operating Temperature**: 0°C ~ 45°C

---

## ✅ Current Status (Updated)

### Backend Integration: ✅ COMPLETED
- **HTTP 500 Error**: Fixed
- **Connection Test**: Working with detailed diagnostics
- **JavaScript Functions**: Working properly
- **Error Handling**: Graceful with Bengali messages

### Network Status: ⚠️ PARTIAL
- **Ping Test**: ✅ Device reachable at 192.168.1.201
- **Protocol Response**: ❌ Device not responding to ZKTeco commands
- **Issue**: Device needs network configuration (see steps above)

### Next Steps:
1. **Configure Device Network**: Follow "Network Configuration" section above
2. **Enable TCP/IP Service**: In device Communication settings
3. **Test Connection**: Use "সংযোগ পরীক্ষা করুন" button
4. **Verify Success**: Should show "সংযোগ সফল!" message

---

## ✅ Quick Setup Checklist

- [ ] Device physically installed
- [ ] Power connected and device on
- [ ] Network cable connected
- [ ] IP address configured on device
- [ ] System settings updated
- [ ] Connection test successful
- [ ] Users synced to device
- [ ] Fingerprints enrolled
- [ ] Attendance sync tested
- [ ] Auto sync configured

**Ready to use!** 🎉