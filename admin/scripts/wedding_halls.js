// Wedding Halls Management JavaScript

let wedding_halls_data = [];

// Load wedding halls data
function loadWeddingHalls() {
    let form = new FormData();
    form.append('get_wedding_halls', '');

    fetch('ajax/wedding_halls.php', {
        method: 'POST',
        body: form
    })
    .then(response => response.json())
    .then(data => {
        wedding_halls_data = data;
        displayWeddingHalls();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Display wedding halls in table
function displayWeddingHalls() {
    let html = '';
    
    if (wedding_halls_data.length === 0) {
        html = '<tr><td colspan="7" class="text-center">No wedding halls found</td></tr>';
    } else {
        wedding_halls_data.forEach((hall, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${hall.name}</td>
                    <td>${hall.description.substring(0, 100)}${hall.description.length > 100 ? '...' : ''}</td>
                    <td>${hall.package_pdf ? '<a href="../uploads/wedding_pdfs/' + hall.package_pdf + '" target="_blank" class="btn btn-sm btn-primary">View PDF</a>' : 'No PDF'}</td>
                    <td>${hall.image_count} images</td>
                    <td>${hall.inquiry_count} inquiries</td>
                    <td>
                        <button type="button" onclick="editWeddingHall(${hall.id})" class="btn btn-sm btn-primary me-2">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" onclick="deleteWeddingHall(${hall.id})" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    document.getElementById('wedding-halls-data').innerHTML = html;
}

// Add wedding hall form submission
document.getElementById('add-wedding-hall-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    formData.append('add_wedding_hall', '');
    
    // Show loading state
    let submitBtn = this.querySelector('button[type="submit"]');
    let originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Adding...';
    submitBtn.disabled = true;
    
    // print all formData
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    fetch('ajax/wedding_halls.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            alert('success', 'Wedding hall added successfully!');
            this.reset();
            // Close modal
            let modal = bootstrap.Modal.getInstance(document.getElementById('add-wedding-hall'));
            modal.hide();
            loadWeddingHalls();
        } else if (data === 'required_fields') {
            alert('error', 'Please fill all required fields!');
        } else if (data === 'pdf_upload_failed') {
            alert('error', 'PDF upload failed!');
        } else {
            alert('error', 'Something went wrong1!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('error', 'An error occurred!');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Edit wedding hall
function editWeddingHall(wedding_hall_id) {
    let form = new FormData();
    form.append('get_wedding_hall', '');
    form.append('wedding_hall_id', wedding_hall_id);
    
    fetch('ajax/wedding_halls.php', {
        method: 'POST',
        body: form
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        if (data !== 'not_found' && data !== 'error') {
            document.getElementById('edit_wedding_hall_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_description').value = data.description;
            
            // Show edit modal
            let modal = new bootstrap.Modal(document.getElementById('edit-wedding-hall'));
            modal.show();
        } else {
            alert('error', 'Wedding hall not found!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('error', 'An error occurred!');
    });
}

// Update wedding hall form submission
document.getElementById('edit-wedding-hall-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    formData.append('update_wedding_hall', '');
    
    // Show loading state
    let submitBtn = this.querySelector('button[type="submit"]');
    let originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';
    submitBtn.disabled = true;
    
    fetch('ajax/wedding_halls.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log('Response:',data, data.trim() == 'success  ');
        if (data.trim() == 'success') {
            alert('success', 'Wedding hall updated successfully!');
            let modal = bootstrap.Modal.getInstance(document.getElementById('edit-wedding-hall'));
            modal.hide();
            loadWeddingHalls();
        } else if (data === 'pdf_upload_failed') {
            alert('error', 'PDF upload failed!');
        } else {
            alert('error', 'Something went wrong!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('error', 'An error occurred!');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Delete wedding hall
function deleteWeddingHall(wedding_hall_id) {
    if (confirm('Are you sure you want to delete this wedding hall? This action cannot be undone.')) {
        let form = new FormData();
        form.append('delete_wedding_hall', '');
        form.append('wedding_hall_id', wedding_hall_id);
        
        fetch('ajax/wedding_halls.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                alert('success', 'Wedding hall deleted successfully!');
                loadWeddingHalls();
            } else {
                alert('error', 'Something went wrong!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('error', 'An error occurred!');
        });
    }
}

// Delete wedding hall image
function deleteWeddingImage(image_id) {
    if (confirm('Are you sure you want to delete this image?')) {
        let form = new FormData();
        form.append('delete_wedding_image', '');
        form.append('image_id', image_id);
        
        fetch('ajax/wedding_halls.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('success', 'Image deleted successfully!');
                // Reload the edit modal if it's open
                let editModal = document.getElementById('edit-wedding-hall');
                if (editModal.classList.contains('show')) {
                    let wedding_hall_id = document.getElementById('edit_wedding_hall_id').value;
                    editWeddingHall(wedding_hall_id);
                }
            } else {
                alert('error', 'Something went wrong!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('error', 'An error occurred!');
        });
    }
}

// Load wedding halls when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadWeddingHalls();
}); 