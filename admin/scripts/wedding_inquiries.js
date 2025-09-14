// Wedding Inquiries Management JavaScript

let wedding_inquiries_data = [];

// Load wedding inquiries data
function loadWeddingInquiries() {
    let form = new FormData();
    form.append('get_wedding_inquiries', '');

    fetch('ajax/wedding_inquiries.php', {
        method: 'POST',
        body: form
    })
    .then(response => response.json())
    .then(data => {
        wedding_inquiries_data = data;
        displayWeddingInquiries();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Display wedding inquiries in table
function displayWeddingInquiries() {
    let html = '';
    
    if (wedding_inquiries_data.length === 0) {
        html = '<tr><td colspan="7" class="text-center">No wedding inquiries found</td></tr>';
    } else {
        wedding_inquiries_data.forEach((inquiry, index) => {
            let date = new Date(inquiry.created_at).toLocaleDateString();
            let message = inquiry.message.length > 100 ? inquiry.message.substring(0, 100) + '...' : inquiry.message;
            
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${inquiry.wedding_hall_name || 'N/A'}</td>
                    <td>${inquiry.name}</td>
                    <td>${inquiry.email}</td>
                    <td>${message}</td>
                    <td>${date}</td>
                    <td>
                        <button type="button" onclick="viewInquiry(${inquiry.id})" class="btn btn-sm btn-primary me-2">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" onclick="deleteInquiry(${inquiry.id})" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    document.getElementById('wedding-inquiries-data').innerHTML = html;
}

// View inquiry details
function viewInquiry(inquiry_id) {
    let form = new FormData();
    form.append('get_inquiry', '');
    form.append('inquiry_id', inquiry_id);
    
    fetch('ajax/wedding_inquiries.php', {
        method: 'POST',
        body: form
    })
    .then(response => response.json())
    .then(data => {
        if (data !== 'not_found' && data !== 'error') {
            document.getElementById('view_wedding_hall').textContent = data.wedding_hall_name || 'N/A';
            document.getElementById('view_name').textContent = data.name;
            document.getElementById('view_email').textContent = data.email;
            document.getElementById('view_message').textContent = data.message;
            document.getElementById('view_date').textContent = new Date(data.created_at).toLocaleString();
            
            // Set up email reply link
            document.getElementById('reply_email_link').href = `mailto:${data.email}?subject=Re: Wedding Hall Inquiry - ${data.wedding_hall_name || 'General'}`;
            
            // Show modal
            let modal = new bootstrap.Modal(document.getElementById('view-inquiry'));
            modal.show();
        } else {
            alert('error', 'Inquiry not found!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('error', 'An error occurred!');
    });
}

// Delete inquiry
function deleteInquiry(inquiry_id) {
    if (confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')) {
        let form = new FormData();
        form.append('delete_inquiry', '');
        form.append('inquiry_id', inquiry_id);
        
        fetch('ajax/wedding_inquiries.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('success', 'Inquiry deleted successfully!');
                loadWeddingInquiries();
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

// Load wedding inquiries when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadWeddingInquiries();
}); 