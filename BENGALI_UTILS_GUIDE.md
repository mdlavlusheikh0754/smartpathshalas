# Bengali Utilities Guide

This guide explains how to use Bengali number conversion and input handling throughout the Smart Pathshala project.

## Features

✅ **Automatic Bengali Number Display** - All numbers displayed in Bengali format
✅ **Dual Input Support** - Accept both Bengali (০-৯) and English (0-9) numbers in input fields
✅ **Automatic Conversion** - Bengali numbers automatically converted to English for processing
✅ **Date & Time Formatting** - Display dates and times in Bengali format
✅ **Currency Formatting** - Format currency amounts in Bengali
✅ **Input Validation** - Validate Bengali/English text inputs

## Installation

The Bengali utilities are automatically loaded on all tenant pages via `resources/views/layouts/tenant.blade.php`.

```html
<script src="{{ asset('js/bengali-utils.js') }}"></script>
```

## Usage

### 1. Display Numbers in Bengali

#### Method 1: Using JavaScript Function
```javascript
// Convert any number to Bengali
const bengaliNumber = BengaliUtils.toBengaliNumber(123);
// Output: ১২৩

// In your JavaScript code
document.getElementById('total').textContent = BengaliUtils.toBengaliNumber(exams.length);
```

#### Method 2: Using HTML Attribute
```html
<!-- Add data-bengali-display attribute to any element -->
<span data-bengali-display>123</span>
<!-- Will automatically display as: ১২৩ -->

<p data-bengali-display>Total: 50 students</p>
<!-- Will automatically display as: Total: ৫০ students -->
```

### 2. Number Input Fields (Accept Both Bengali & English)

#### Automatic Setup
All `input[type="number"]` fields automatically support both Bengali and English input:

```html
<input type="number" name="marks" class="form-control">
<!-- Users can type: ৮৫ or 85, both will work -->
```

#### Manual Setup for Custom Fields
```html
<input type="text" data-bengali-number name="roll_number">
<!-- Will accept both ৮৫ and 85 -->
```

### 3. Date Formatting

```javascript
// Format date in Bengali
const bengaliDate = BengaliUtils.toBengaliDate('2024-01-22');
// Output: ২২/০১/২০২৪

// With custom format
const bengaliDate = BengaliUtils.toBengaliDate('2024-01-22', 'dd-mm-yyyy');
// Output: ২২-০১-২০২৪

// In your code
document.getElementById('examDate').textContent = BengaliUtils.toBengaliDate(exam.start_date);
```

### 4. Time Formatting

```javascript
const bengaliTime = BengaliUtils.toBengaliTime(new Date());
// Output: ১০:৩০
```

### 5. Currency Formatting

```javascript
const amount = BengaliUtils.toBengaliCurrency(1500.50);
// Output: ৳ ১,৫০০.৫০

// Custom currency symbol
const amount = BengaliUtils.toBengaliCurrency(1500.50, 'টাকা');
// Output: টাকা ১,৫০০.৫০
```

### 6. Convert Bengali to English (For Processing)

```javascript
// When you need to process Bengali numbers
const englishNumber = BengaliUtils.toEnglishNumber('৮৫');
// Output: 85

// Parse input value
const value = BengaliUtils.parseInputValue(inputField.value);
// Converts any Bengali numbers to English for processing
```

### 7. Input Validation

```javascript
// Validate Bengali/English text
const isValid = BengaliUtils.validateInput(value, {
    allowBengali: true,
    allowEnglish: true,
    allowNumbers: true,
    allowSpecialChars: false,
    minLength: 3,
    maxLength: 50
});
```

## Complete Example: Exam Form

```html
<form id="examForm">
    <!-- Name field - accepts both Bengali and English -->
    <label>পরীক্ষার নাম</label>
    <input type="text" name="exam_name" placeholder="মাসিক পরীক্ষা">
    
    <!-- Number field - accepts both ৮৫ and 85 -->
    <label>পূর্ণমান</label>
    <input type="number" name="full_marks" placeholder="১০০">
    
    <!-- Date field -->
    <label>তারিখ</label>
    <input type="date" name="exam_date">
    
    <!-- Display total exams in Bengali -->
    <p>মোট পরীক্ষা: <span id="totalExams" data-bengali-display>0</span></p>
</form>

<script>
// Display exam count in Bengali
document.getElementById('totalExams').textContent = BengaliUtils.toBengaliNumber(exams.length);

// When submitting form, Bengali numbers are automatically converted
document.getElementById('examForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    // All number inputs will have English numbers for processing
    console.log(formData.get('full_marks')); // Will be 85 even if user typed ৮৫
});
</script>
```

## Common Use Cases

### 1. Display Statistics in Bengali
```javascript
// Stats cards
document.getElementById('totalStudents').textContent = BengaliUtils.toBengaliNumber(students.length);
document.getElementById('totalExams').textContent = BengaliUtils.toBengaliNumber(exams.length);
document.getElementById('passRate').textContent = BengaliUtils.toBengaliNumber(passRate) + '%';
```

### 2. Table with Bengali Numbers
```javascript
// Render table rows
const tableHTML = students.map(student => `
    <tr>
        <td>${BengaliUtils.toBengaliNumber(student.roll)}</td>
        <td>${student.name}</td>
        <td>${BengaliUtils.toBengaliNumber(student.marks)}</td>
        <td>${BengaliUtils.toBengaliDate(student.exam_date)}</td>
    </tr>
`).join('');
```

### 3. Form with Mixed Bengali/English Input
```html
<!-- Student Registration Form -->
<form>
    <input type="text" name="name_bn" placeholder="নাম (বাংলা)">
    <input type="text" name="name_en" placeholder="Name (English)">
    <input type="number" name="roll" placeholder="রোল নম্বর">
    <!-- User can type: ৫০ or 50, both work -->
    <input type="number" name="age" placeholder="বয়স">
    <!-- User can type: ১৫ or 15, both work -->
</form>
```

## Available Functions

| Function | Description | Example |
|----------|-------------|---------|
| `toBengaliNumber(num)` | Convert number to Bengali | `toBengaliNumber(123)` → `১২৩` |
| `toEnglishNumber(num)` | Convert Bengali to English | `toEnglishNumber('১২৩')` → `123` |
| `toBengaliDate(date)` | Format date in Bengali | `toBengaliDate('2024-01-22')` → `২২/০১/২০২৪` |
| `toBengaliTime(time)` | Format time in Bengali | `toBengaliTime(new Date())` → `১০:৩০` |
| `toBengaliCurrency(amount)` | Format currency | `toBengaliCurrency(1500)` → `৳ ১,৫০০.০০` |
| `parseInputValue(value)` | Parse input (Bengali→English) | `parseInputValue('৮৫')` → `85` |
| `validateInput(value, options)` | Validate input | See validation section |

## Troubleshooting

### Numbers not displaying in Bengali?
1. Make sure the element has `data-bengali-display` attribute, OR
2. Use `BengaliUtils.toBengaliNumber()` in JavaScript

### Input not accepting Bengali numbers?
1. Check if `bengali-utils.js` is loaded
2. For `type="number"` inputs, it's automatic
3. For `type="text"` inputs, add `data-bengali-number` attribute

### Form submission sending Bengali numbers?
- Don't worry! Bengali numbers are automatically converted to English before submission
- The conversion happens on input, so your backend receives English numbers

## Best Practices

1. **Always display numbers in Bengali** for user-facing content
2. **Always process numbers in English** for calculations and database storage
3. **Use `data-bengali-display`** for automatic conversion in HTML
4. **Use `BengaliUtils.toBengaliNumber()`** for dynamic JavaScript content
5. **Don't manually convert** - let the utility handle it automatically

## Support

For issues or questions, check:
- `public/js/bengali-utils.js` - Source code
- `resources/views/layouts/tenant.blade.php` - Integration point
- This guide - Complete documentation

---

**Note:** The utilities are automatically initialized on page load and work with dynamically added content (AJAX, modals, etc.)
