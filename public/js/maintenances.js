// Maintenances Page JavaScript

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Close hamburger menu when a link is clicked
    document.querySelectorAll('.hamburger-dropdown a').forEach(link => {
        link.addEventListener('click', () => {
            const hamburgerToggle = document.getElementById('hamburger-toggle');
            if (hamburgerToggle) {
                hamburgerToggle.checked = false;
            }
        });
    });

    // Also handle form submission (logout)
    document.querySelectorAll('.hamburger-dropdown form').forEach(form => {
        form.addEventListener('submit', () => {
            const hamburgerToggle = document.getElementById('hamburger-toggle');
            if (hamburgerToggle) {
                hamburgerToggle.checked = false;
            }
        });
    });

    // Initialize photo upload functionality
    initializePhotoUpload();
    
    // Initialize modal functionality
    initializeModal();
    
    // Initialize form interactions
    initializeFormInteractions();
    
    // Initialize vehicle options
    initializeVehicleOptions();
    
    // Initialize keyboard navigation
    initializeKeyboardNavigation();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize form validation
    initializeFormValidation();
});

// Maintenance Modal functions
function openMaintenanceModal() {
    const modal = document.getElementById('maintenanceModal');
    if (modal) {
        initializeVehicleOptions();
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

function closeMaintenanceModal() {
    const modal = document.getElementById('maintenanceModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
}

// Photo upload drag and drop functionality
function initializePhotoUpload() {
    const photoUploadArea = document.querySelector('[onclick*="photo"]');
    const photoInput = document.getElementById('photo');
    const photoName = document.getElementById('modal-photo-name');

    if (photoUploadArea && photoInput && photoName) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // Handle drag enter and over
        ['dragenter', 'dragover'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, () => {
                photoUploadArea.style.borderColor = '#3b82f6';
                photoUploadArea.style.backgroundColor = '#eff6ff';
                photoUploadArea.classList.add('dragover');
            });
        });

        // Handle drag leave
        ['dragleave'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, () => {
                photoUploadArea.style.borderColor = '#cbd5e1';
                photoUploadArea.style.backgroundColor = '#f8fafc';
                photoUploadArea.classList.remove('dragover');
            });
        });

        // Handle drop
        photoUploadArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0], photoInput, photoName);
            }
            photoUploadArea.style.borderColor = '#cbd5e1';
            photoUploadArea.style.backgroundColor = '#f8fafc';
            photoUploadArea.classList.remove('dragover');
        });

        // Handle click to select file
        photoUploadArea.addEventListener('click', () => {
            photoInput.click();
        });

        // Handle file input change
        photoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0], photoInput, photoName);
            }
        });
    }
}

// Handle file selection
function handleFileSelect(file, photoInput, photoName) {
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
    
    if (file.size > maxSize) {
        // Show error message instead of displaying the photo
        photoName.textContent = 'Error: File too large (Max 5MB)';
        photoName.style.color = '#dc2626';
        photoName.classList.add('error');
        photoInput.value = ''; // Clear the input
    } else {
        photoInput.files = new DataTransfer().files;
        photoInput.files.add(file);
        photoName.textContent = file.name;
        photoName.style.color = '#1e293b';
        photoName.classList.remove('error');
    }
}

// Initialize modal functionality
function initializeModal() {
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('maintenanceModal');
        if (event.target === modal) {
            closeMaintenanceModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMaintenanceModal();
        }
    });

    // Expose modal functions globally for inline onclick handlers
    window.openMaintenanceModal = openMaintenanceModal;
    window.closeMaintenanceModal = closeMaintenanceModal;
}

// Initialize form interactions
function initializeFormInteractions() {
    const boardmemberSelect = document.getElementById('boardmember_id');
    const vehicleSelect = document.getElementById('vehicle_id');
    const nameInput = document.getElementById('vehicle_name');
    const plateInput = document.getElementById('plate_number');

    if (boardmemberSelect) {
        boardmemberSelect.addEventListener('change', function() {
            filterVehiclesByBoardmember(this.value);
        });
    }

    if (vehicleSelect) {
        vehicleSelect.addEventListener('change', function() {
            if (!nameInput || !plateInput) return;
            const opt = this.options[this.selectedIndex];
            nameInput.value = opt ? (opt.getAttribute('data-name') || '') : '';
            plateInput.value = opt ? (opt.getAttribute('data-plate') || '') : '';
        });
    }
}

// Filter vehicles by selected boardmember
function filterVehiclesByBoardmember(boardmemberId) {
    const vehicleSelect = document.getElementById('vehicle_id');
    const nameInput = document.getElementById('vehicle_name');
    const plateInput = document.getElementById('plate_number');
    
    if (!vehicleSelect) return;
    
    const opts = vehicleSelect.querySelectorAll('option');
    opts.forEach(o => {
        if (o.value === '') {
            o.style.display = 'block';
        } else if (boardmemberId && o.getAttribute('data-boardmember') !== boardmemberId) {
            o.style.display = 'none';
        } else {
            o.style.display = '';
        }
    });

    vehicleSelect.disabled = !boardmemberId;
    vehicleSelect.value = '';
    if (nameInput) nameInput.value = '';
    if (plateInput) plateInput.value = '';
}

// Initialize vehicle options
function initializeVehicleOptions() {
    const vehicleSelect = document.getElementById('vehicle_id');
    if (!vehicleSelect) return;
    
    vehicleSelect.innerHTML = '<option value="">-- Select registered vehicle --</option>';

    const boardmembersData = window.boardmembersData || {};
    Object.keys(boardmembersData).forEach(bmId => {
        (boardmembersData[bmId] || []).forEach(v => {
            const option = document.createElement('option');
            option.value = v.id;
            option.setAttribute('data-name', v.vehicle_name || '');
            option.setAttribute('data-plate', v.plate_number || '');
            option.setAttribute('data-boardmember', bmId);
            option.textContent = (v.plate_number || '') + ' — ' + (v.vehicle_name || '');
            vehicleSelect.appendChild(option);
        });
    });
}

// Toggle details row for a boardmember
function toggleRow(id) {
    const details = document.getElementById(id + '-details');
    if (!details) return;
    
    const isVisible = details.style.display === 'table-row';
    details.style.display = isVisible ? 'none' : 'table-row';
    
    // Toggle aria-expanded for accessibility
    const trigger = document.querySelector(`[onclick="toggleRow('${id}')"]`);
    if (trigger) {
        trigger.setAttribute('aria-expanded', !isVisible);
    }
}

// Expose toggleRow globally for inline onclick handlers
window.toggleRow = toggleRow;

// Initialize keyboard navigation
function initializeKeyboardNavigation() {
    // Add keyboard navigation for table rows
    document.querySelectorAll('.main-row').forEach(row => {
        row.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Make rows focusable
        row.setAttribute('tabindex', '0');
    });
}

// Tooltip functionality
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.cssText = `
                position: absolute;
                background: #1f2937;
                color: white;
                padding: 6px 12px;
                border-radius: 6px;
                font-size: 12px;
                white-space: nowrap;
                z-index: 1000;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.2s ease;
            `;
            
            document.body.appendChild(tooltip);
            
            // Position tooltip
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
            
            // Show tooltip
            setTimeout(() => {
                tooltip.style.opacity = '1';
            }, 10);
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.style.opacity = '0';
                setTimeout(() => {
                    tooltip.remove();
                }, 200);
            }
        });
    });
}

// Form validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    // Add error message if not exists
                    let errorMsg = field.parentNode.querySelector('.error-text');
                    if (!errorMsg) {
                        errorMsg = document.createElement('span');
                        errorMsg.className = 'error-text';
                        errorMsg.style.cssText = 'color: #dc2626; font-size: 12px; margin-top: 4px; display: block;';
                        errorMsg.textContent = 'This field is required';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('error');
                    const errorMsg = field.parentNode.querySelector('.error-text');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Remove error class on input
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('error');
                const errorMsg = this.parentNode.querySelector('.error-text');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        });
    });
}

// Export functions for external use if needed
window.MaintenancesPage = {
    openMaintenanceModal,
    closeMaintenanceModal,
    toggleRow,
    filterVehiclesByBoardmember,
    initializeVehicleOptions,
    initializePhotoUpload,
    handleFileSelect
};
