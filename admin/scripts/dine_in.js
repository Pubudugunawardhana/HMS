let add_dine_in_option_form = document.getElementById('add_dine_in_option_form');
let edit_dine_in_option_form = document.getElementById('edit_dine_in_option_form');

// Initialize form listeners
add_dine_in_option_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_dine_in_option();
});

// Add dine-in option
function add_dine_in_option() {
    let data = new FormData();
    data.append('add_dine_in_option', '');
    data.append('name', add_dine_in_option_form.elements['name'].value);
    data.append('description', add_dine_in_option_form.elements['description'].value);
    data.append('open_hours', add_dine_in_option_form.elements['open_hours'].value);
    data.append('image', add_dine_in_option_form.elements['image'].files[0]);
    data.append('type_of_dine_in', add_dine_in_option_form.elements['type_of_dine_in'].value);
    data.append('location', add_dine_in_option_form.elements['location'].value);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/dine_in.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('add-option');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        
        console.log("Response:", this.responseText);
        
        if (this.responseText.trim() == '1') {
            alert('success', 'Success! New dine-in option added.');
            add_dine_in_option_form.reset();
            get_all_dine_in_options();
        } else if (this.responseText.trim() == '2') {
            alert('error', 'Dine-in option name already exists!');
        } else {
            alert('error', 'Error: Failed to add dine-in option.');
        }
    }

    xhr.send(data);
}

// Get all dine-in options
function get_all_dine_in_options() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/dine_in.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('dine-in-data').innerHTML = this.responseText;
    }

    xhr.send('get_all_dine_in_options');
}

// Edit dine-in option
function editDineInOption(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/dine_in.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        
        if (data) {
            // Populate edit form
            edit_dine_in_option_form.elements['dine_in_id'].value = data.id;
            edit_dine_in_option_form.elements['name'].value = data.name;
            edit_dine_in_option_form.elements['description'].value = data.description;
            edit_dine_in_option_form.elements['open_hours'].value = data.open_hours;
            edit_dine_in_option_form.elements['type_of_dine_in'].value = data.type_of_dine_in;
            edit_dine_in_option_form.elements['location'].value = data.location;
            
            // Show modal
            var editModal = new bootstrap.Modal(document.getElementById('edit-dine-in-option'));
            editModal.show();
        } else {
            alert('error', 'Failed to load dine-in option data.');
        }
    }

    xhr.send('get_dine_in_option&id=' + id);
}

// Update dine-in option
function updateDineInOption() {
    let data = new FormData();
    data.append('update_dine_in_option', '');
    data.append('id', edit_dine_in_option_form.elements['dine_in_id'].value);
    data.append('name', edit_dine_in_option_form.elements['name'].value);
    data.append('description', edit_dine_in_option_form.elements['description'].value);
    data.append('open_hours', edit_dine_in_option_form.elements['open_hours'].value);
    data.append('type_of_dine_in', edit_dine_in_option_form.elements['type_of_dine_in'].value);
    data.append('location', edit_dine_in_option_form.elements['location'].value);
    
    // Only append image if a new one is selected
    if (edit_dine_in_option_form.elements['image'].files.length > 0) {
        data.append('image', edit_dine_in_option_form.elements['image'].files[0]);
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/dine_in.php", true);

    xhr.onload = function () {
        var editModal = bootstrap.Modal.getInstance(document.getElementById('edit-dine-in-option'));
        editModal.hide();
        
        if (this.responseText.trim() == '1') {
            alert('success', 'Dine-in option updated successfully!');
            edit_dine_in_option_form.reset();
            get_all_dine_in_options();
        } else if (this.responseText.trim() == '2') {
            alert('error', 'Dine-in option name already exists!');
        } else {
            alert('error', 'Failed to update dine-in option.');
        }
    }

    xhr.send(data);
}

// Delete dine-in option
function deleteDineInOption(id) {
    if (confirm('Are you sure you want to delete this dine-in option?')) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/dine_in.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (this.responseText.trim() == '1') {
                alert('success', 'Dine-in option deleted successfully!');
                get_all_dine_in_options();
            } else {
                alert('error', 'Failed to delete dine-in option.');
            }
        }

        xhr.send('delete_dine_in_option&id=' + id);
    }
}

// Toggle status
function toggleStatus(id, status) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/dine_in.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText.trim() == '1') {
            alert('success', 'Status updated successfully!');
            get_all_dine_in_options();
        } else {
            alert('error', 'Failed to update status.');
        }
    }

    xhr.send('toggle_status&id=' + id + '&status=' + status);
}



// Event listener for edit form submission
if (edit_dine_in_option_form) {
    edit_dine_in_option_form.addEventListener('submit', function (e) {
        e.preventDefault();
        updateDineInOption();
    });
}

// Load data when page loads
window.onload = function () {
    get_all_dine_in_options();
}