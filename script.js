// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('departure_date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
        dateInput.value = today;
    }
});

// Form validation
function validateSearchForm() {
    const origin = document.getElementById('origin').value.trim();
    const destination = document.getElementById('destination').value.trim();
    const departureDate = document.getElementById('departure_date').value;
    
    if (!origin || !destination || !departureDate) {
        alert('Please fill in all required fields.');
        return false;
    }
    
    if (origin.toLowerCase() === destination.toLowerCase()) {
        alert('Origin and destination cannot be the same.');
        return false;
    }
    
    return true;
}

// Add event listener to search form
const searchForm = document.getElementById('searchForm');
if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
        if (!validateSearchForm()) {
            e.preventDefault();
        }
    });
}

// Flight selection
function selectFlight(flightId, passengers) {
    window.location.href = `booking.php?flight_id=${flightId}&passengers=${passengers}`;
}

// Booking form validation
function validateBookingForm() {
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#ef4444';
            isValid = false;
        } else {
            field.style.borderColor = '#e5e7eb';
        }
    });
    
    // Validate email format
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (field.value && !emailRegex.test(field.value)) {
            field.style.borderColor = '#ef4444';
            isValid = false;
        }
    });
    
    // Validate phone format
    const phoneFields = document.querySelectorAll('input[name*="phone"]');
    phoneFields.forEach(field => {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (field.value && !phoneRegex.test(field.value.replace(/[\s\-$$$$]/g, ''))) {
            field.style.borderColor = '#ef4444';
            isValid = false;
        }
    });
    
    if (!isValid) {
        alert('Please fill in all required fields correctly.');
    }
    
    return isValid;
}

// Add loading state to forms
function showLoading(button) {
    button.disabled = true;
    button.innerHTML = '<span class="spinner"></span> Processing...';
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Auto-fill passenger info for multiple passengers
function copyPassengerInfo(sourceIndex, targetIndex) {
    const sourcePrefix = `passenger_${sourceIndex}`;
    const targetPrefix = `passenger_${targetIndex}`;
    
    const fields = ['first_name', 'last_name', 'email', 'phone'];
    
    fields.forEach(field => {
        const sourceField = document.querySelector(`input[name="${sourcePrefix}_${field}"]`);
        const targetField = document.querySelector(`input[name="${targetPrefix}_${field}"]`);
        
        if (sourceField && targetField) {
            targetField.value = sourceField.value;
        }
    });
}