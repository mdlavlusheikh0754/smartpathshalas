# Bengali Font System Documentation

## Overview
This system uses **Tiro Bangla** for display (web UI) and **SolaimanLipi** for exports (PDF, print).

**✅ TENANT AREA**: Now fully configured to use **Tiro Bangla** for all display elements.

## Font Configuration

### 1. Tailwind Config
```javascript
fontFamily: {
    sans: ['Tiro Bangla', 'SolaimanLipi', ...defaultTheme.fontFamily.sans],
    'display': ['Tiro Bangla', 'SolaimanLipi', ...defaultTheme.fontFamily.sans],
    'export': ['SolaimanLipi', ...defaultTheme.fontFamily.sans],
}
```

### 2. Tenant Area Font Setup
All tenant pages now use **Tiro Bangla** by default:
- Navigation menus
- Form elements
- Tables and data displays
- Dashboard cards
- Buttons and inputs
- All text content

### 3. CSS Classes Available

#### Display Classes (Tiro Bangla)
- `.bengali-text` - General Bengali text
- `.bengali-display` - Display text
- `.font-display` - Tailwind utility
- `.school-name-display` - School names
- `.student-name-display` - Student names

#### Export Classes (SolaimanLipi)
- `.bengali-export` - Export Bengali text
- `.font-export` - Tailwind utility
- `.school-name-export` - School names for export
- `.student-name-export` - Student names for export

#### Typography Sizes
- `.bengali-heading-1` - Large headings (2.5rem)
- `.bengali-heading-2` - Medium headings (2rem)
- `.bengali-heading-3` - Small headings (1.5rem)
- `.bengali-body` - Body text (1rem)
- `.bengali-small` - Small text (0.875rem)

## Usage Examples

### 1. HTML Templates
```html
<!-- Display text (web UI) -->
<h1 class="bengali-display bengali-heading-1">স্কুলের নাম</h1>
<p class="bengali-text bengali-body">শিক্ষার্থীর তথ্য</p>

<!-- Export text (PDF) -->
<div class="pdf-export">
    <h1 class="bengali-export school-name-export">স্কুলের নাম</h1>
    <p class="bengali-export student-name-export">শিক্ষার্থীর নাম</p>
</div>
```

### 2. Blade Templates
```php
<!-- Regular display -->
<div class="bengali-text">
    {{ $school->name_bn }}
</div>

<!-- PDF export -->
<div class="pdf-export bengali-export">
    {{ $school->name_bn }}
</div>
```

### 3. JavaScript Font Management
```javascript
// Switch to export mode (for PDF generation)
window.preparePDFExport();

// Switch back to display mode
window.finishPDFExport();

// Manual font switching
window.setDisplayFont(); // Tiro Bangla
window.setExportFont();  // SolaimanLipi
```

## Automatic Font Switching

### 1. Print Events
The system automatically switches to SolaimanLipi when:
- User prints the page (Ctrl+P)
- PDF is generated
- Export functions are called

### 2. PDF Export Detection
```javascript
// Auto-detect PDF export context
if (window.location.search.includes('pdf=1') || 
    document.body.classList.contains('pdf-export')) {
    // Use SolaimanLipi
}
```

## Implementation in Controllers

### 1. PDF Export Controller
```php
public function exportPDF($studentId) {
    // Add PDF context
    $data = [
        'student' => $student,
        'isPDF' => true
    ];
    
    $pdf = PDF::loadView('tenant.results.pdf', $data);
    return $pdf->download('result.pdf');
}
```

### 2. PDF View Template
```php
@if($isPDF ?? false)
<body class="pdf-export">
@else
<body class="display-mode">
@endif
    <div class="bengali-text">
        {{ $content }}
    </div>
</body>
```

## CSS Mode Classes

### Display Mode (Default)
```css
.display-mode .bengali-text {
    font-family: 'Tiro Bangla', 'SolaimanLipi', sans-serif;
}
```

### Export Mode (PDF/Print)
```css
.export-mode .bengali-text,
.pdf-export .bengali-text {
    font-family: 'SolaimanLipi', sans-serif;
}

@media print {
    .bengali-text {
        font-family: 'SolaimanLipi', sans-serif !important;
    }
}
```

## Best Practices

### 1. Use Semantic Classes
```html
<!-- Good -->
<h1 class="school-name-display">{{ $school->name_bn }}</h1>

<!-- Avoid -->
<h1 style="font-family: 'Tiro Bangla'">{{ $school->name_bn }}</h1>
```

### 2. PDF Export Preparation
```javascript
// Before generating PDF
document.body.classList.add('pdf-export');
window.preparePDFExport();

// Generate PDF here

// After PDF generation
document.body.classList.remove('pdf-export');
window.finishPDFExport();
```

### 3. Form Inputs
```html
<input type="text" 
       class="bengali-input" 
       placeholder="নাম লিখুন"
       lang="bn">
```

## File Structure
```
resources/
├── css/
│   ├── app.css                 # Main CSS with font imports
│   ├── tenant.css              # Tenant-specific styles
│   └── bengali-typography.css  # Bengali typography system
├── js/
│   └── bengali-fonts.js        # Font management JavaScript
└── views/
    └── layouts/
        └── tenant.blade.php    # Updated with font system

public/
├── fonts/
│   └── SolaimanLipi.ttf       # Local SolaimanLipi font
└── js/
    └── bengali-fonts.js        # Font management script

tailwind.config.js              # Updated with font families
```

## Testing

### 1. Display Mode Test
- Open any page in browser
- Bengali text should use Tiro Bangla
- Text should look modern and clean

### 2. Export Mode Test
- Press Ctrl+P to print
- Bengali text should switch to SolaimanLipi
- Text should be clear and readable

### 3. PDF Export Test
- Generate any PDF report
- Bengali text should use SolaimanLipi
- Text should render properly in PDF

## Troubleshooting

### 1. Font Not Loading
- Check browser console for font errors
- Verify Google Fonts connection
- Ensure SolaimanLipi.ttf exists in public/fonts/

### 2. Font Not Switching
- Check if bengali-fonts.js is loaded
- Verify CSS classes are applied correctly
- Check browser console for JavaScript errors

### 3. PDF Font Issues
- Ensure PDF generation includes font files
- Check if CSS classes are applied in PDF template
- Verify font paths are correct

## Browser Support
- Chrome: Full support
- Firefox: Full support
- Safari: Full support
- Edge: Full support
- Mobile browsers: Full support with fallbacks