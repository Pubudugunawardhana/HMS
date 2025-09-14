let add_equipment_form = document.getElementById('add_equipment_form');

add_equipment_form.addEventListener('submit', function (e) {
  e.preventDefault();
  console.log("add_equipment_form", add_equipment_form);	
  add_equipment();
});

function add_equipment() {
  let data = new FormData();
  data.append('add_equipment', '');
  data.append('equipment_name', add_equipment_form.elements['equipment_name'].value);
  data.append('equipment_code', add_equipment_form.elements['equipment_code'].value);
  data.append('quantity', add_equipment_form.elements['quantity'].value);
  data.append('desc', add_equipment_form.elements['desc'].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/equipment.php", true);

  xhr.onload = function () {
    var myModal = document.getElementById('add-equipment');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();
    console.log("this.responseText",this.responseText);
    if (this.responseText.trim() == 1) {
      alert('success','Success! New equipment added.');
      add_equipment_form.reset();
      get_all_equipment();
    } else {
      alert('Error: Server error or invalid data.');
    }
  }

  xhr.send(data);
}

function get_all_equipment() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/equipment.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    document.getElementById('equipment-data').innerHTML = this.responseText;
  }

  xhr.send('get_all_equipment');
}

let edit_equipment_form = document.getElementById('edit_equipment_form');

function edit_equipment(id) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/equipment.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    let data = JSON.parse(this.responseText);

    edit_equipment_form.elements['equipment_name'].value = data.equipment_name;
    edit_equipment_form.elements['equipment_code'].value = data.equipment_code;
    edit_equipment_form.elements['quantity'].value = data.quantity;
    edit_equipment_form.elements['desc'].value = data.description;
    edit_equipment_form.elements['equipment_id'].value = data.id;
  }

  xhr.send('get_equipment=' + id);
}

edit_equipment_form.addEventListener('submit', function (e) {
  e.preventDefault();
  update_equipment();
});

function update_equipment() {
  let data = new FormData();
  data.append('edit_equipment', '');
  data.append('equipment_id', edit_equipment_form.elements['equipment_id'].value);
  data.append('equipment_name', edit_equipment_form.elements['equipment_name'].value);
  data.append('equipment_code', edit_equipment_form.elements['equipment_code'].value);
  data.append('quantity', edit_equipment_form.elements['quantity'].value);
  data.append('desc', edit_equipment_form.elements['desc'].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/equipment.php", true);

  xhr.onload = function () {
    var myModal = document.getElementById('edit-equipment');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if (this.responseText == 1) {
      alert('success','Success! Equipment updated.');
      edit_equipment_form.reset();
      get_all_equipment();
    } else {
      alert('Error: Update failed.');
    }
  }

  xhr.send(data);
}

function delete_equipment(id) {
  if (confirm("Are you sure you want to delete this equipment?")) {
    let data = new FormData();
    data.append('delete_equipment', '');
    data.append('equipment_id', id);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/equipment.php", true);

    xhr.onload = function () {
      if (this.responseText == 1) {
        alert('success','Success! Equipment deleted.');
        get_all_equipment();
      } else {
        alert('Error: Could not delete.');
      }
    }

    xhr.send(data);
  }
}

// Load equipment on page load
window.onload = function () {
  get_all_equipment();
}
