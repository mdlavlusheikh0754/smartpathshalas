/**
 * Bengali Font Management System
 * Handles switching between Tiro Bangla (display) and SolaimanLipi (export)
 */

class BengaliFontManager {
    constructor() {
        this.displayFont = 'Tiro Bangla';
        this.exportFont = 'SolaimanLipi';
        this.init();
    }

    init() {
        // Set default display font
        this.setDisplayMode();
        
        // Listen for print events
        window.addEventListener('beforeprint', () => this.setExportMode());
        window.addEventListener('afterprint', () => this.setDisplayMode());
        
        // Listen for PDF export events
        document.addEventListener('pdf-export-start', () => this.setExportMode());
        document.addEventListener('pdf-export-end', () => this.setDisplayMode());
    }

    setDisplayMode() {
        document.body.classList.remove('export-mode');
        document.body.classList.add('display-mode');
        
        // Update CSS custom properties
        document.documentElement.style.setProperty('--bengali-font', this.displayFont);
        
        console.log('Font mode: Display (Tiro Bangla)');
    }

    setExportMode() {
        document.body.classList.remove('display-mode');
        document.body.classList.add('export-mode');
        
        // Update CSS custom properties
        document.documentElement.style.setProperty('--bengali-font', this.exportFont);
        
        console.log('Font mode: Export (SolaimanLipi)');
    }

    // Utility methods
    applyDisplayFont(element) {
        if (element) {
            element.style.fontFamily = `'${this.displayFont}', 'SolaimanLipi', ui-sans-serif, system-ui, sans-serif`;
        }
    }

    applyExportFont(element) {
        if (element) {
            element.style.fontFamily = `'${this.exportFont}', ui-sans-serif, system-ui, sans-serif`;
        }
    }

    // Apply font to all Bengali text elements
    applyToAllBengaliText(mode = 'display') {
        const bengaliElements = document.querySelectorAll('.bengali-text, .bengali-display, [lang="bn"]');
        const font = mode === 'display' ? this.displayFont : this.exportFont;
        
        bengaliElements.forEach(element => {
            element.style.fontFamily = `'${font}', ui-sans-serif, system-ui, sans-serif`;
        });
    }

    // For PDF generation
    preparePDFExport() {
        this.setExportMode();
        this.applyToAllBengaliText('export');
        
        // Dispatch custom event
        document.dispatchEvent(new CustomEvent('pdf-export-start'));
    }

    finishPDFExport() {
        this.setDisplayMode();
        this.applyToAllBengaliText('display');
        
        // Dispatch custom event
        document.dispatchEvent(new CustomEvent('pdf-export-end'));
    }
}

// Initialize font manager
const bengaliFontManager = new BengaliFontManager();

// Export for global use
window.BengaliFontManager = bengaliFontManager;

// Utility functions for easy access
window.setDisplayFont = () => bengaliFontManager.setDisplayMode();
window.setExportFont = () => bengaliFontManager.setExportMode();
window.preparePDFExport = () => bengaliFontManager.preparePDFExport();
window.finishPDFExport = () => bengaliFontManager.finishPDFExport();

// Auto-detect and apply appropriate font based on context
document.addEventListener('DOMContentLoaded', function() {
    // Check if this is a PDF export context
    if (window.location.search.includes('pdf=1') || 
        document.body.classList.contains('pdf-export') ||
        window.print === undefined) {
        bengaliFontManager.setExportMode();
    } else {
        bengaliFontManager.setDisplayMode();
    }
});