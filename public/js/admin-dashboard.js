// Admin Dashboard JavaScript

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

// Toggle table row details
function toggleRow(rowId) {
    const detailsRow = document.getElementById(rowId + '-details');
    if (!detailsRow) return;

    const isVisible = detailsRow.style.display === 'table-row';
    detailsRow.style.display = isVisible ? 'none' : 'table-row';
}

// Toggle expand/collapse icon
function toggleIcon(btn) {
    const expanded = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    
    // Update icon if needed
    const icon = btn.querySelector('i, span');
    if (icon) {
        if (expanded) {
            icon.textContent = '▼'; // or use appropriate icon class
        } else {
            icon.textContent = '▶'; // or use appropriate icon class
        }
    }
}

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add keyboard navigation for toggle buttons
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });

    // Add loading states for form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Loading...';
            }
        });
    });

    // Initialize tooltips if needed
    initializeTooltips();
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

// Chart initialization function
function initializeCharts() {
    const chartData = window.dashboardChartData;
    if (!chartData) return;

    const { dayLabels, dailyFuelCosts, dailyMaintenanceCosts, statusCounts } = chartData;

    // Line Chart - Daily Expenses
    const ctx = document.getElementById('fuelSlipTrendChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: dayLabels,
                datasets: [
                    {
                        label: 'Fuel Costs',
                        data: dailyFuelCosts,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Maintenance Costs',
                        data: dailyMaintenanceCosts,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: 600
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 64, 175, 0.9)',
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            title: function(context) {
                                return 'Day ' + context[0].label;
                            },
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        title: {
                            display: true,
                            text: 'Amount (₱)',
                            font: {
                                size: 11,
                                weight: 600
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Day of Month',
                            font: {
                                size: 11,
                                weight: 600
                            }
                        }
                    }
                }
            }
        });
    }

    // Monthly Fuel Consumption
    const consumptionCtx = document.getElementById('monthlyConsumptionChart');
    if (consumptionCtx && chartData.monthlyConsumption) {
        new Chart(consumptionCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.monthlyConsumption.labels,
                datasets: [{
                    label: 'Liters Consumed',
                    data: chartData.monthlyConsumption.data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(30, 64, 175, 0.9)',
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                return `Liters: ${context.parsed.y.toLocaleString()} L`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return value.toLocaleString() + ' L';
                            }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        title: {
                            display: true,
                            text: 'Liters',
                            font: { size: 11, weight: 600 }
                        }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 3. Budget Burn Rate - Actual vs Projected
    const burnCtx = document.getElementById('budgetBurnChart');
    if (burnCtx && chartData.budgetBurn) {
        const { labels, actual, projected, totalBudget } = chartData.budgetBurn;
        new Chart(burnCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Actual Spending',
                        data: actual,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Projected (Linear)',
                        data: projected,
                        borderColor: '#64748b',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, weight: 600 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 64, 175, 0.9)',
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label;
                                const value = context.parsed.y;
                                const percentage = totalBudget > 0 ? ((value / totalBudget) * 100).toFixed(1) : 0;
                                return `${label}: ₱${value.toLocaleString()} (${percentage}% of budget)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return '₱' + (value / 1000).toFixed(0) + 'k';
                            }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        title: {
                            display: true,
                            text: 'Cumulative Spending (₱)',
                            font: { size: 11, weight: 600 }
                        }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false },
                        title: {
                            display: true,
                            text: 'Week of Year',
                            font: { size: 11, weight: 600 }
                        }
                    }
                }
            }
        });
    }
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

// Export functions for external use if needed
window.AdminDashboard = {
    toggleRow,
    toggleIcon,
    initializeTooltips,
    initializeCharts
};
