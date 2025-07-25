/**
 * Advanced Features JavaScript
 * Handles complex functionality and integrations
 */

// Advanced Search functionality
window.AdvancedSearch = {
    // Global search across all modules
    globalSearch: function(query, callback) {
        if (query.length < 3) {
            callback([]);
            return;
        }
        
        fetch(`/api/global-search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    callback(data.results);
                } else {
                    callback([]);
                }
            })
            .catch(error => {
                console.error('Global search error:', error);
                callback([]);
            });
    },
    
    // Setup global search widget
    setupGlobalSearch: function() {
        const searchInput = document.getElementById('global-search');
        const resultsContainer = document.getElementById('global-search-results');
        
        if (!searchInput || !resultsContainer) return;
        
        const debouncedSearch = Utils.debounce(function(query) {
            AdvancedSearch.globalSearch(query, function(results) {
                AdvancedSearch.displayGlobalResults(results, resultsContainer);
            });
        }, 300);
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length >= 3) {
                debouncedSearch(query);
            } else {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('hidden');
            }
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    },
    
    displayGlobalResults: function(results, container) {
        if (results.length === 0) {
            container.innerHTML = '<div class="p-3 text-gray-500">No results found</div>';
        } else {
            const html = results.map(result => `
                <div class="p-3 hover:bg-gray-50 cursor-pointer border-b" onclick="window.location.href='${result.url}'">
                    <div class="flex items-center">
                        <i class="${result.icon} text-gray-400 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">${result.title}</div>
                            <div class="text-sm text-gray-500">${result.description}</div>
                            <div class="text-xs text-gray-400">${result.module}</div>
                        </div>
                    </div>
                </div>
            `).join('');
            container.innerHTML = html;
        }
        
        container.classList.remove('hidden');
    }
};

// Keyboard shortcuts
window.KeyboardShortcuts = {
    shortcuts: {
        'ctrl+k': () => document.getElementById('global-search')?.focus(),
        'ctrl+n': () => window.location.href = '/employees/create',
        'ctrl+p': () => window.location.href = '/payroll/process',
        'ctrl+r': () => window.location.href = '/reports',
        'ctrl+h': () => window.location.href = '/dashboard',
        'escape': () => {
            // Close any open modals or dropdowns
            document.querySelectorAll('.modal, [id$="-dropdown"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    },
    
    init: function() {
        document.addEventListener('keydown', function(e) {
            const key = [];
            
            if (e.ctrlKey) key.push('ctrl');
            if (e.altKey) key.push('alt');
            if (e.shiftKey) key.push('shift');
            
            key.push(e.key.toLowerCase());
            
            const shortcut = key.join('+');
            
            if (KeyboardShortcuts.shortcuts[shortcut]) {
                e.preventDefault();
                KeyboardShortcuts.shortcuts[shortcut]();
            }
        });
    }
};

// Data Export utilities
window.DataExport = {
    // Export data to various formats
    exportData: function(data, filename, format = 'csv') {
        switch (format) {
            case 'csv':
                this.exportToCSV(data, filename);
                break;
            case 'excel':
                this.exportToExcel(data, filename);
                break;
            case 'json':
                this.exportToJSON(data, filename);
                break;
            case 'pdf':
                this.exportToPDF(data, filename);
                break;
        }
    },
    
    exportToCSV: function(data, filename) {
        if (!data || data.length === 0) return;
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${row[header] || ''}"`).join(','))
        ].join('\n');
        
        this.downloadFile(csvContent, filename + '.csv', 'text/csv');
    },
    
    exportToJSON: function(data, filename) {
        const jsonContent = JSON.stringify(data, null, 2);
        this.downloadFile(jsonContent, filename + '.json', 'application/json');
    },
    
    exportToExcel: function(data, filename) {
        // Simple Excel export using CSV with Excel MIME type
        if (!data || data.length === 0) return;
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join('\t'),
            ...data.map(row => headers.map(header => row[header] || '').join('\t'))
        ].join('\n');
        
        this.downloadFile(csvContent, filename + '.xls', 'application/vnd.ms-excel');
    },
    
    downloadFile: function(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    }
};

// Real-time updates
window.RealTimeUpdates = {
    updateInterval: null,
    
    start: function() {
        this.updateInterval = setInterval(() => {
            this.checkForUpdates();
        }, 30000); // Check every 30 seconds
    },
    
    stop: function() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    },
    
    checkForUpdates: function() {
        // Check for new notifications
        if (typeof NotificationUtils !== 'undefined') {
            NotificationUtils.checkForUpdates();
        }
        
        // Update dashboard widgets if on dashboard
        if (window.location.pathname === '/dashboard') {
            this.updateDashboardWidgets();
        }
        
        // Update attendance summary if on attendance page
        if (window.location.pathname.includes('/attendance')) {
            this.updateAttendanceData();
        }
    },
    
    updateDashboardWidgets: function() {
        if (typeof DashboardUtils !== 'undefined') {
            DashboardUtils.refreshWidgets();
        }
    },
    
    updateAttendanceData: function() {
        const today = new Date().toISOString().split('T')[0];
        
        fetch(`/api/attendance-summary?date=${today}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update attendance counts if elements exist
                    const elements = {
                        'present-count': data.summary.present,
                        'absent-count': data.summary.absent,
                        'late-count': data.summary.late,
                        'half-day-count': data.summary.half_day
                    };
                    
                    Object.keys(elements).forEach(id => {
                        const element = document.getElementById(id);
                        if (element) {
                            element.textContent = elements[id] || 0;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error updating attendance data:', error);
            });
    }
};

// Advanced form validation
window.AdvancedValidation = {
    // Custom validation rules
    rules: {
        indianMobile: function(value) {
            return /^[6-9]\d{9}$/.test(value);
        },
        
        strongPassword: function(value) {
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
        },
        
        futureDate: function(value) {
            return new Date(value) > new Date();
        },
        
        pastDate: function(value) {
            return new Date(value) < new Date();
        },
        
        workingDay: function(value) {
            const date = new Date(value);
            const day = date.getDay();
            return day !== 0 && day !== 6; // Not Sunday or Saturday
        }
    },
    
    // Validate form with advanced rules
    validateForm: function(form) {
        let isValid = true;
        const errors = [];
        
        form.querySelectorAll('[data-validate-advanced]').forEach(field => {
            const rules = field.dataset.validateAdvanced.split('|');
            const value = field.value;
            const fieldName = field.name || field.id;
            
            rules.forEach(rule => {
                if (this.rules[rule] && !this.rules[rule](value)) {
                    isValid = false;
                    errors.push({
                        field: fieldName,
                        rule: rule,
                        message: this.getErrorMessage(rule)
                    });
                    this.showFieldError(field, this.getErrorMessage(rule));
                }
            });
        });
        
        return { isValid, errors };
    },
    
    getErrorMessage: function(rule) {
        const messages = {
            indianMobile: 'Please enter a valid Indian mobile number',
            strongPassword: 'Password must contain at least 8 characters with uppercase, lowercase, number and special character',
            futureDate: 'Date must be in the future',
            pastDate: 'Date must be in the past',
            workingDay: 'Please select a working day (Monday to Friday)'
        };
        
        return messages[rule] || 'Invalid value';
    },
    
    showFieldError: function(field, message) {
        // Remove existing error
        const existingError = field.parentNode.querySelector('.advanced-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'advanced-error text-red-600 text-sm mt-1';
        errorDiv.textContent = message;
        
        field.classList.add('border-red-300');
        field.parentNode.appendChild(errorDiv);
    }
};

// Performance monitoring
window.PerformanceMonitor = {
    metrics: {
        pageLoadTime: 0,
        apiCallTimes: [],
        memoryUsage: 0
    },
    
    init: function() {
        // Monitor page load time
        window.addEventListener('load', () => {
            this.metrics.pageLoadTime = performance.now();
        });
        
        // Monitor API calls
        this.interceptFetch();
        
        // Monitor memory usage (if available)
        if ('memory' in performance) {
            this.metrics.memoryUsage = performance.memory.usedJSHeapSize;
        }
    },
    
    interceptFetch: function() {
        const originalFetch = window.fetch;
        
        window.fetch = function(...args) {
            const startTime = performance.now();
            
            return originalFetch.apply(this, args)
                .then(response => {
                    const endTime = performance.now();
                    const duration = endTime - startTime;
                    
                    PerformanceMonitor.metrics.apiCallTimes.push({
                        url: args[0],
                        duration: duration,
                        timestamp: new Date()
                    });
                    
                    // Keep only last 50 API calls
                    if (PerformanceMonitor.metrics.apiCallTimes.length > 50) {
                        PerformanceMonitor.metrics.apiCallTimes.shift();
                    }
                    
                    return response;
                });
        };
    },
    
    getMetrics: function() {
        return {
            ...this.metrics,
            averageApiTime: this.getAverageApiTime(),
            slowestApiCall: this.getSlowestApiCall()
        };
    },
    
    getAverageApiTime: function() {
        if (this.metrics.apiCallTimes.length === 0) return 0;
        
        const total = this.metrics.apiCallTimes.reduce((sum, call) => sum + call.duration, 0);
        return total / this.metrics.apiCallTimes.length;
    },
    
    getSlowestApiCall: function() {
        if (this.metrics.apiCallTimes.length === 0) return null;
        
        return this.metrics.apiCallTimes.reduce((slowest, call) => 
            call.duration > slowest.duration ? call : slowest
        );
    }
};

// Initialize advanced features
document.addEventListener('DOMContentLoaded', function() {
    // Initialize keyboard shortcuts
    KeyboardShortcuts.init();
    
    // Initialize global search
    AdvancedSearch.setupGlobalSearch();
    
    // Initialize performance monitoring
    PerformanceMonitor.init();
    
    // Start real-time updates
    RealTimeUpdates.start();
    
    // Add keyboard shortcut help
    const helpButton = document.createElement('button');
    helpButton.innerHTML = '<i class="fas fa-keyboard"></i>';
    helpButton.className = 'fixed bottom-4 right-4 bg-gray-600 text-white p-3 rounded-full shadow-lg hover:bg-gray-700 z-40';
    helpButton.title = 'Keyboard Shortcuts (Ctrl+K: Search, Ctrl+N: New Employee, Ctrl+P: Payroll, Ctrl+R: Reports)';
    helpButton.onclick = showKeyboardShortcuts;
    
    document.body.appendChild(helpButton);
});

function showKeyboardShortcuts() {
    const shortcuts = [
        { key: 'Ctrl + K', action: 'Global Search' },
        { key: 'Ctrl + N', action: 'New Employee' },
        { key: 'Ctrl + P', action: 'Process Payroll' },
        { key: 'Ctrl + R', action: 'Reports' },
        { key: 'Ctrl + H', action: 'Dashboard' },
        { key: 'Escape', action: 'Close Modals' }
    ];
    
    const html = `
        <div class="space-y-3">
            <h3 class="text-lg font-semibold text-gray-900">Keyboard Shortcuts</h3>
            ${shortcuts.map(shortcut => `
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">${shortcut.action}</span>
                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs font-mono">${shortcut.key}</kbd>
                </div>
            `).join('')}
        </div>
    `;
    
    // Create and show modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            ${html}
            <div class="mt-6 text-center">
                <button onclick="this.closest('.fixed').remove()" class="btn btn-primary">
                    Got it!
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Remove modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Export for global access
window.AdvancedFeatures = {
    Search: AdvancedSearch,
    Shortcuts: KeyboardShortcuts,
    Export: DataExport,
    RealTime: RealTimeUpdates,
    Validation: AdvancedValidation,
    Performance: PerformanceMonitor
};