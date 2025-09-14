let add_experience_option_form = document.getElementById('add_experience_option_form');
let edit_experience_option_form = document.getElementById('edit_experience_option_form');

// Initialize form listeners
add_experience_option_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_experience_option();
});

// Add experience option
function add_experience_option() {
    let data = new FormData();
    data.append('add_experience_option', '');
    data.append('name', add_experience_option_form.elements['name'].value);
    data.append('description', add_experience_option_form.elements['description'].value);
    data.append('highlight', add_experience_option_form.elements['highlight'].value);
    data.append('image', add_experience_option_form.elements['image'].files[0]);
    data.append('pricelist', add_experience_option_form.elements['pricelist'].files[0]);
    data.append('guide_name', add_experience_option_form.elements['guide_name'].value);
    data.append('guide_email', add_experience_option_form.elements['guide_email'].value);
    data.append('guide_phone', add_experience_option_form.elements['guide_phone'].value);

    // disply all fields array
    console.log("Form Data:", Array.from(data.entries()).reduce((acc, [key, value]) => ({ ...acc, [key]: value }), {}));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/experience.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('add-option');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        
        console.log("Response:", this.responseText);
        
        if (this.responseText.trim() == '1') {
            alert('success', 'Success! New experience option added.');
            add_experience_option_form.reset();
            get_all_experience_options();
        } else if (this.responseText.trim() == '2') {
            alert('error', 'Experience option name already exists!');
        } else {
            alert('error', 'Error: Failed to add experience option.');
        }
    }

    xhr.send(data);
}

function get_all_experience_options() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/experience.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('experience-data').innerHTML = this.responseText;
    }

    xhr.send('get_all_experience_options');
}

function editDineInOption(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/experience.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        
        if (data) {
            console.log("Edit Data:", data);
            edit_experience_option_form.elements['experience_id'].value = data.id;
            edit_experience_option_form.elements['name'].value = data.name;
            edit_experience_option_form.elements['description'].value = data.description;
            edit_experience_option_form.elements['highlight'].value = data.highlight;
            edit_experience_option_form.elements['guide_name'].value = data.guide_name;
            edit_experience_option_form.elements['guide_email'].value = data.guide_email;
            edit_experience_option_form.elements['guide_phone'].value = data.guide_phone;

            var editModal = new bootstrap.Modal(document.getElementById('edit-experience-option'));
            editModal.show();
        } else {
            alert('error', 'Failed to load experience option data.');
        }
    }

    xhr.send('get_experience_option&id=' + id);
}

// Update experience option
function updateDineInOption() {
    let data = new FormData();
    data.append('update_experience_option', '');
    data.append('id', edit_experience_option_form.elements['experience_id'].value);
    data.append('name', edit_experience_option_form.elements['name'].value);
    data.append('description', edit_experience_option_form.elements['description'].value);
    data.append('highlight', edit_experience_option_form.elements['highlight'].value);
    data.append('guide_name', edit_experience_option_form.elements['guide_name'].value);
    data.append('guide_email', edit_experience_option_form.elements['guide_email'].value);
    data.append('guide_phone', edit_experience_option_form.elements['guide_phone'].value);
    
    if (edit_experience_option_form.elements['image'].files.length > 0) {
        data.append('image', edit_experience_option_form.elements['image'].files[0]);
    }

    if (edit_experience_option_form.elements['pricelist'].files.length > 0) {
        data.append('pricelist', edit_experience_option_form.elements['pricelist'].files[0]);
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/experience.php", true);

    xhr.onload = function () {
        var editModal = bootstrap.Modal.getInstance(document.getElementById('edit-experience-option'));
        editModal.hide();
        
        if (this.responseText.trim() == '1') {
            alert('success', 'experience option updated successfully!');
            edit_experience_option_form.reset();
            get_all_experience_options();
        } else if (this.responseText.trim() == '2') {
            alert('error', 'experience option name already exists!');
        } else {
            alert('error', 'Failed to update experience option.');
        }
    }

    xhr.send(data);
}

function deleteDineInOption(id) {
    if (confirm('Are you sure you want to delete this experience option?')) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/experience.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (this.responseText.trim() == '1') {
                alert('success', 'experience option deleted successfully!');
                get_all_experience_options();
            } else {
                alert('error', 'Failed to delete experience option.');
            }
        }

        xhr.send('delete_experience_option&id=' + id);
    }
}

function toggleStatus(id, status) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/experience.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText.trim() == '1') {
            alert('success', 'Status updated successfully!');
            get_all_experience_options();
        } else {
            alert('error', 'Failed to update status.');
        }
    }

    xhr.send('toggle_status&id=' + id + '&status=' + status);
}



if (edit_experience_option_form) {
    edit_experience_option_form.addEventListener('submit', function (e) {
        e.preventDefault();
        updateDineInOption();
    });
}

window.onload = function () {
    get_all_experience_options();
}