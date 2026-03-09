// Vehicles Page JavaScript

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

// Modal functions for vehicle registration
function openVehicleModal() {
    const modal = document.getElementById('vehicleModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

function closeVehicleModal() {
    const modal = document.getElementById('vehicleModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
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

// Toggle recent fuel slips list inside a vehicle card
function toggleFuelList(vehicleId) {
    const fuelList = document.getElementById('fuel-' + vehicleId);
    if (!fuelList) return;
    
    const isVisible = fuelList.style.display === 'block';
    fuelList.style.display = isVisible ? 'none' : 'block';
    
    // Update button text
    const toggleBtn = document.querySelector(`[onclick="toggleFuelList('${vehicleId}')"]`);
    if (toggleBtn) {
        toggleBtn.textContent = isVisible ? 'Show Recent Fuel Slips' : 'Hide Recent Fuel Slips';
    }
}

// Edit modal functions
function openEditModal(vehicleId, plateNumber, monthlyLimit, currentKm) {
    // Set form values
    const editVehicleId = document.getElementById('editVehicleId');
    const editPlateNumber = document.getElementById('edit_plate_number');
    const editMonthlyLimit = document.getElementById('edit_monthly_fuel_limit');
    const editCurrentKm = document.getElementById('edit_current_km');
    const editForm = document.getElementById('editVehicleForm');
    const modal = document.getElementById('editVehicleModal');
    
    if (editVehicleId) editVehicleId.value = vehicleId;
    if (editPlateNumber) editPlateNumber.value = plateNumber;
    if (editMonthlyLimit) editMonthlyLimit.value = monthlyLimit;
    if (editCurrentKm) editCurrentKm.value = currentKm;
    
    // Set the form action dynamically
    if (editForm) {
        editForm.action = '/vehicles/' + vehicleId;
        editForm.style.display = 'block';
    }
    
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

function closeEditModal() {
    const modal = document.getElementById('editVehicleModal');
    const form = document.getElementById('editVehicleForm');
    
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
    
    if (form) {
        form.style.display = 'none';
        form.reset(); // Reset form fields
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const vehicleModal = document.getElementById('vehicleModal');
        const editModal = document.getElementById('editVehicleModal');
        
        if (event.target === vehicleModal) {
            closeVehicleModal();
        }
        
        if (event.target === editModal) {
            closeEditModal();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVehicleModal();
            closeEditModal();
        }
    });
    
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
    
    // Add confirmation for delete actions
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this vehicle?')) {
                e.preventDefault();
            }
        });
    });
    
    // Initialize tooltips if needed
    initializeTooltips();
    
    // Add form validation
    initializeFormValidation();
});

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
window.VehiclesPage = {
    openVehicleModal,
    closeVehicleModal,
    toggleRow,
    toggleFuelList,
    openEditModal,
    closeEditModal,
    initializeTooltips,
    initializeFormValidation
};
