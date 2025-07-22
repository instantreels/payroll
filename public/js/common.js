/**
 * Common JavaScript utilities for the Payroll System
 */

// Global utility functions
window.Utils = {
    // Format currency with Indian locale
    formatCurrency: function(amount, showSymbol = true) {
        const formatted = parseFloat(amount || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        return showSymbol ? '₹' + formatted : formatted;
    },

    // Format date
    formatDate: function(dateString, format = 'dd/mm/yyyy') {
        if (!dateString) return '';
        const date = new Date(dateString);
        
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        
        switch (format) {
            case 'dd/mm/yyyy':
                return `${day}/${month}/${year}`;
            case 'mm/dd/yyyy':
                return `${month}/${day}/${year}`;
            case 'yyyy-mm-dd':
                return `${year}-${month}-${day}`;
            default:
                return date.toLocaleDateString();
        }
    },

    // Validate PAN number
    validatePAN: function(pan) {
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        return panRegex.test(pan);
    },

    // Validate Aadhaar number
    validateAadhaar: function(aadhaar) {
        const cleanAadhaar = aadhaar.replace(/\s/g, '');
        return cleanAadhaar.length === 12 && /^\d{12}$/.test(cleanAadhaar);
    },

    // Validate IFSC code
    validateIFSC: function(ifsc) {
        const ifscRegex = /^[A-Z]{4}0[A-Z0-9]{6}$/;
        return ifscRegex.test(ifsc);
    },

    // Debounce function
    debounce: function(func, wait, immediate) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    },

    // Show confirmation dialog
    confirm: function(message, onConfirm, onCancel) {
        if (confirm(message)) {
            if (typeof onConfirm === 'function') onConfirm();
        } else {
            if (typeof onCancel === 'function') onCancel();
        }
    },

    // Copy text to clipboard
    copyToClipboard: function(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                showMessage('Copied to clipboard', 'success');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showMessage('Copied to clipboard', 'success');
        }
    },

    // Generate random password
    generatePassword: function(length = 8) {
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        return password;
    }
};

// Form validation utilities
window.FormValidator = {
    rules: {
        required: function(value) {
            return value && value.trim().length > 0;
        },
        email: function(value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(value);
        },
        minLength: function(value, min) {
            return value && value.length >= min;
        },
        maxLength: function(value, max) {
            return !value || value.length <= max;
        },
        numeric: function(value) {
            return !isNaN(value) && !isNaN(parseFloat(value));
        },
        pan: function(value) {
            return Utils.validatePAN(value);
        },
        aadhaar: function(value) {
            return Utils.validateAadhaar(value);
        },
        ifsc: function(value) {
            return Utils.validateIFSC(value);
        }
    },

    validate: function(form) {
        const errors = {};
        const formData = new FormData(form);
        
        // Clear previous errors
        form.querySelectorAll('.field-error').forEach(el => {
            el.classList.remove('field-error');
        });
        form.querySelectorAll('.error-message').forEach(el => {
            el.remove();
        });

        // Validate each field
        form.querySelectorAll('[data-validate]').forEach(field => {
            const rules = field.dataset.validate.split('|');
            const value = field.value;
            const fieldName = field.name || field.id;

            for (let rule of rules) {
                const [ruleName, ruleValue] = rule.split(':');
                
                if (this.rules[ruleName]) {
                    const isValid = ruleValue ? 
                        this.rules[ruleName](value, ruleValue) : 
                        this.rules[ruleName](value);
                    
                    if (!isValid) {
                        errors[fieldName] = this.getErrorMessage(ruleName, ruleValue);
                        this.showFieldError(field, errors[fieldName]);
                        break;
                    }
                }
            }
        });

        return Object.keys(errors).length === 0;
    },

    showFieldError: function(field, message) {
        field.classList.add('field-error');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-600 text-sm mt-1';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    },

    getErrorMessage: function(rule, value) {
        const messages = {
            required: 'This field is required',
            email: 'Please enter a valid email address',
            minLength: `Minimum ${value} characters required`,
            maxLength: `Maximum ${value} characters allowed`,
            numeric: 'Please enter a valid number',
            pan: 'Please enter a valid PAN number (e.g., ABCDE1234F)',
            aadhaar: 'Please enter a valid 12-digit Aadhaar number',
            ifsc: 'Please enter a valid IFSC code (e.g., SBIN0001234)'
        };
        return messages[rule] || 'Invalid value';
    }
};

// Auto-format inputs
document.addEventListener('DOMContentLoaded', function() {
    // PAN number formatting
    document.addEventListener('input', function(e) {
        if (e.target.matches('[data-format="pan"]')) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        }
    });

    // Aadhaar number formatting
    document.addEventListener('input', function(e) {
        if (e.target.matches('[data-format="aadhaar"]')) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 4 && value.length <= 8) {
                value = value.slice(0, 4) + ' ' + value.slice(4);
            } else if (value.length > 8) {
                value = value.slice(0, 4) + ' ' + value.slice(4, 8) + ' ' + value.slice(8, 12);
            }
            e.target.value = value;
        }
    });

    // IFSC code formatting
    document.addEventListener('input', function(e) {
        if (e.target.matches('[data-format="ifsc"]')) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        }
    });

    // Phone number formatting
    document.addEventListener('input', function(e) {
        if (e.target.matches('[data-format="phone"]')) {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 10);
        }
    });

    // Currency formatting
    document.addEventListener('blur', function(e) {
        if (e.target.matches('[data-format="currency"]')) {
            const value = parseFloat(e.target.value) || 0;
            e.target.value = value.toFixed(2);
        }
    });
});

// Table utilities
window.TableUtils = {
    // Sort table by column
    sortTable: function(table, columnIndex, direction = 'asc') {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            // Try to parse as numbers
            const aNum = parseFloat(aValue.replace(/[^\d.-]/g, ''));
            const bNum = parseFloat(bValue.replace(/[^\d.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return direction === 'asc' ? aNum - bNum : bNum - aNum;
            }
            
            // String comparison
            return direction === 'asc' ? 
                aValue.localeCompare(bValue) : 
                bValue.localeCompare(aValue);
        });
        
        rows.forEach(row => tbody.appendChild(row));
    },

    // Filter table rows
    filterTable: function(table, searchTerm) {
        const tbody = table.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(searchTerm.toLowerCase());
            row.style.display = matches ? '' : 'none';
        });
    },

    // Export table to CSV
    exportToCSV: function(table, filename = 'export.csv') {
        const rows = table.querySelectorAll('tr');
        const csv = [];
        
        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const rowData = Array.from(cols).map(col => {
                return '"' + col.textContent.replace(/"/g, '""') + '"';
            });
            csv.push(rowData.join(','));
        });
        
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        
        window.URL.revokeObjectURL(url);
    }
};

// Print utilities
window.PrintUtils = {
    printElement: function(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .no-print { display: none; }
                    </style>
                </head>
                <body>
                    ${element.innerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
};