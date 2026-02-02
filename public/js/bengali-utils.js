/**
 * Bengali Utilities
 * Handles Bengali number conversion, input validation, and display formatting
 */

// Bengali digit mapping
const bengaliDigits = {
    '0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪',
    '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'
};

const englishDigits = {
    '০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4',
    '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'
};

/**
 * Convert English numbers to Bengali
 * @param {string|number} num - Number to convert
 * @returns {string} Bengali formatted number
 */
function toBengaliNumber(num) {
    if (num === null || num === undefined || num === '') return '০';
    return num.toString().replace(/\d/g, digit => bengaliDigits[digit] || digit);
}

/**
 * Convert Bengali numbers to English
 * @param {string} num - Bengali number to convert
 * @returns {string} English formatted number
 */
function toEnglishNumber(num) {
    if (num === null || num === undefined || num === '') return '0';
    return num.toString().replace(/[০-৯]/g, digit => englishDigits[digit] || digit);
}

/**
 * Format date in Bengali
 * @param {string|Date} dateStr - Date to format
 * @param {string} format - Format type (default: 'dd/mm/yyyy')
 * @returns {string} Bengali formatted date
 */
function toBengaliDate(dateStr, format = 'dd/mm/yyyy') {
    if (!dateStr) return '--';
    const date = new Date(dateStr);
    
    if (isNaN(date.getTime())) return '--';
    
    const day = toBengaliNumber(String(date.getDate()).padStart(2, '0'));
    const month = toBengaliNumber(String(date.getMonth() + 1).padStart(2, '0'));
    const year = toBengaliNumber(date.getFullYear());
    
    if (format === 'dd/mm/yyyy') {
        return `${day}/${month}/${year}`;
    } else if (format === 'dd-mm-yyyy') {
        return `${day}-${month}-${year}`;
    }
    
    return `${day}/${month}/${year}`;
}

/**
 * Format time in Bengali
 * @param {string|Date} timeStr - Time to format
 * @returns {string} Bengali formatted time
 */
function toBengaliTime(timeStr) {
    if (!timeStr) return '--';
    const date = new Date(timeStr);
    
    if (isNaN(date.getTime())) return '--';
    
    const hours = toBengaliNumber(String(date.getHours()).padStart(2, '0'));
    const minutes = toBengaliNumber(String(date.getMinutes()).padStart(2, '0'));
    
    return `${hours}:${minutes}`;
}

/**
 * Parse input value and convert Bengali numbers to English for processing
 * @param {string} value - Input value
 * @returns {string} Processed value with English numbers
 */
function parseInputValue(value) {
    return toEnglishNumber(value);
}

/**
 * Initialize Bengali number input fields
 * Allows both Bengali and English number input
 */
function initBengaliNumberInputs() {
    // Find all number input fields
    const numberInputs = document.querySelectorAll('input[type="number"], input[data-bengali-number]');
    
    numberInputs.forEach(input => {
        // For type="number", change to type="text" to allow Bengali input
        if (input.type === 'number') {
            input.setAttribute('data-original-type', 'number');
            input.type = 'text';
            input.setAttribute('inputmode', 'numeric');
            input.setAttribute('pattern', '[0-9০-৯]*');
        }
        
        // Allow both Bengali and English numbers
        input.addEventListener('input', function(e) {
            const cursorPosition = this.selectionStart;
            const originalValue = this.value;
            
            // Allow only numbers (Bengali or English) and basic characters
            const filteredValue = originalValue.replace(/[^0-9০-৯\.\-]/g, '');
            
            if (filteredValue !== originalValue) {
                this.value = filteredValue;
                this.setSelectionRange(cursorPosition - 1, cursorPosition - 1);
                return;
            }
        });
        
        // Convert to English on blur for form submission
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = toEnglishNumber(this.value);
            }
        });
        
        // Handle paste events
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const filteredText = pastedText.replace(/[^0-9০-৯\.\-]/g, '');
            
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const currentValue = this.value;
            
            this.value = currentValue.substring(0, start) + filteredText + currentValue.substring(end);
            this.setSelectionRange(start + filteredText.length, start + filteredText.length);
            
            // Trigger input event
            this.dispatchEvent(new Event('input', { bubbles: true }));
        });
        
        // Handle form submission - convert to English
        const form = input.closest('form');
        if (form && !form.hasAttribute('data-bengali-initialized')) {
            form.setAttribute('data-bengali-initialized', 'true');
            form.addEventListener('submit', function(e) {
                const allNumberInputs = this.querySelectorAll('input[data-original-type="number"], input[data-bengali-number]');
                allNumberInputs.forEach(inp => {
                    if (inp.value) {
                        inp.value = toEnglishNumber(inp.value);
                    }
                });
            });
        }
    });
}

/**
 * Display numbers in Bengali format
 * Converts all numbers in elements with data-bengali-display attribute
 */
function displayBengaliNumbers() {
    const elements = document.querySelectorAll('[data-bengali-display]');
    
    elements.forEach(element => {
        const originalText = element.textContent;
        const bengaliText = originalText.replace(/\d+/g, match => toBengaliNumber(match));
        element.textContent = bengaliText;
    });
}

/**
 * Format currency in Bengali
 * @param {number} amount - Amount to format
 * @param {string} currency - Currency symbol (default: '৳')
 * @returns {string} Bengali formatted currency
 */
function toBengaliCurrency(amount, currency = '৳') {
    if (amount === null || amount === undefined) return `${currency} ০`;
    
    const formatted = parseFloat(amount).toLocaleString('bn-BD', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    return `${currency} ${toBengaliNumber(formatted)}`;
}

/**
 * Validate Bengali/English text input
 * @param {string} value - Value to validate
 * @param {object} options - Validation options
 * @returns {boolean} Validation result
 */
function validateInput(value, options = {}) {
    const {
        allowBengali = true,
        allowEnglish = true,
        allowNumbers = true,
        allowSpecialChars = false,
        minLength = 0,
        maxLength = Infinity
    } = options;
    
    if (value.length < minLength || value.length > maxLength) {
        return false;
    }
    
    let pattern = '';
    if (allowBengali) pattern += 'অ-ৰ';
    if (allowEnglish) pattern += 'a-zA-Z';
    if (allowNumbers) pattern += '0-9০-৯';
    if (allowSpecialChars) pattern += '\\s\\-\\.\\,';
    
    const regex = new RegExp(`^[${pattern}]+$`);
    return regex.test(value);
}

/**
 * Initialize all Bengali utilities on page load
 */
function initBengaliUtils() {
    initBengaliNumberInputs();
    displayBengaliNumbers();
    
    // Re-initialize on dynamic content load
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                initBengaliNumberInputs();
                displayBengaliNumbers();
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBengaliUtils);
} else {
    initBengaliUtils();
}

// Export functions for global use
window.BengaliUtils = {
    toBengaliNumber,
    toEnglishNumber,
    toBengaliDate,
    toBengaliTime,
    toBengaliCurrency,
    parseInputValue,
    validateInput,
    initBengaliNumberInputs,
    displayBengaliNumbers
};
