// Fuel Slips Page JavaScript

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

// Fuel Slip Modal functions
function openFuelSlipModal() {
    const modal = document.getElementById('fuelSlipModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

function closeFuelSlipModal() {
    const modal = document.getElementById('fuelSlipModal');
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

// Modal click outside to close
window.onclick = function(event) {
    const modal = document.getElementById('fuelSlipModal');
    if (event.target === modal) {
        closeFuelSlipModal();
    }
};

// Form handling for fuel slip creation
const boardmemberSelect = document.getElementById('boardmember_id');
const vehicleSelect = document.getElementById('vehicle_id');
const nameInput = document.getElementById('vehicle_name');
const plateInput = document.getElementById('plate_number');
const litersInput = document.getElementById('liters');
const unitCostInput = document.getElementById('unit_cost');
const totalCostInput = document.getElementById('total_cost');

// Filter vehicles by selected boardmember
function filterVehiclesByBoardmember(boardmemberId) {
    const opts = vehicleSelect.querySelectorAll('option[data-boardmember]');
    opts.forEach(o => {
        if (boardmemberId && o.getAttribute('data-boardmember') !== boardmemberId) {
            o.style.display = 'none';
        } else {
            o.style.display = '';
        }
    });

    vehicleSelect.disabled = !boardmemberId;
    vehicleSelect.value = '';
    nameInput.value = '';
    plateInput.value = '';
}

// Calculate total cost automatically
function calculateTotalCost() {
    const liters = parseFloat(litersInput.value) || 0;
    const unitCost = parseFloat(unitCostInput.value) || 0;
    const totalCost = (liters * unitCost).toFixed(2);
    totalCostInput.value = totalCost;
}

// Event listeners for form interactions
if (boardmemberSelect) {
    boardmemberSelect.addEventListener('change', function() {
        filterVehiclesByBoardmember(this.value);
    });
}

if (vehicleSelect) {
    vehicleSelect.addEventListener('change', function() {
        nameInput.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
        plateInput.value = this.options[this.selectedIndex].getAttribute('data-plate') || '';
    });
}

if (litersInput) {
    litersInput.addEventListener('change', calculateTotalCost);
    litersInput.addEventListener('input', calculateTotalCost);
}

if (unitCostInput) {
    unitCostInput.addEventListener('change', calculateTotalCost);
    unitCostInput.addEventListener('input', calculateTotalCost);
}

// Populate vehicle options on page load
// This will be populated by the server-side script
const boardmembersData = window.boardmembersData || {};

Object.keys(boardmembersData).forEach(bmId => {
    boardmembersData[bmId].forEach(v => {
        const option = document.createElement('option');
        option.value = v.id;
        option.setAttribute('data-name', v.vehicle_name);
        option.setAttribute('data-plate', v.plate_number);
        option.setAttribute('data-boardmember', bmId);
        option.textContent = v.plate_number + ' — ' + v.vehicle_name;
        vehicleSelect.appendChild(option);
    });
});

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeFuelSlipModal();
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
    
    // Initialize tooltips if needed
    initializeTooltips();
    
    // Add form validation
    initializeFormValidation();
    
    // Initialize vehicle filtering if boardmember is pre-selected
    if (boardmemberSelect && boardmemberSelect.value) {
        filterVehiclesByBoardmember(boardmemberSelect.value);
    }
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
window.FuelSlipsPage = {
    openFuelSlipModal,
    closeFuelSlipModal,
    toggleRow,
    filterVehiclesByBoardmember,
    calculateTotalCost,
    initializeTooltips,
    initializeFormValidation
};
