// Custom JavaScript for the Movie Exchange Platform

document.addEventListener('DOMContentLoaded', function() {
    // Add any custom JavaScript functionality here
    
    // Example: Confirm before submitting forms
    const forms = document.querySelectorAll('form[data-confirm]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = form.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Example: Image preview for file uploads
    const imageUpload = document.getElementById('image');
    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Here you could display a preview of the selected image
                }
                reader.readAsDataURL(file);
            }
        });
    }
});