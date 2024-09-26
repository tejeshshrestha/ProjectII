let add_room_form = document.getElementById('add_room_form');

add_room_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_room();
});

function add_room() {
    let data = new FormData();
    data.append('add_room', '');
    data.append('name', add_room_form.elements['name'].value);
    data.append('area', add_room_form.elements['area'].value);
    data.append('price', add_room_form.elements['price'].value);
    data.append('quantity', add_room_form.elements['quantity'].value);
    data.append('adult', add_room_form.elements['adult'].value);
    data.append('children', add_room_form.elements['children'].value);
    data.append('desc', add_room_form.elements['desc'].value);


    let choices = [];
    let choiceElements = Array.from(add_room_form.querySelectorAll('input[name="choices[]"]:checked'));
    choiceElements.forEach(el => {
        choices.push(el.value);
    });

    let facilities = [];
    let facilityElements = Array.from(add_room_form.querySelectorAll('input[name="facilities[]"]:checked'));
    facilityElements.forEach(el => {
        facilities.push(el.value);
    });


    data.append('choices', JSON.stringify(choices));
    data.append('facilities', JSON.stringify(facilities));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);

    xhr.onload = function () {
        console.log("Response status:", this.status);  // Check the response status
        console.log("Response text:", this.responseText);  // Check the response text

        var myModal = document.getElementById('add-room');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'New Room Added!');
            add_room_form.reset();
            get_all_rooms();
        } else {
            alert('error', 'Server Down!');
        }

    }
    xhr.onerror = function () {
        console.log("Request error");  // Log if there is an error in the request
    };
    xhr.send(data);

}

function get_all_rooms() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('room-data').innerHTML = this.responseText;
    }
    xhr.onerror = function () {
        console.log("Request error");  // Log if there is an error in the request
    }
    xhr.send('get_all_rooms');
}

let edit_room_form = document.getElementById('edit_room_form');

function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        // Parse the JSON response
        let data = JSON.parse(this.responseText);

        // Set the form fields with the fetched data
        edit_room_form.elements['name'].value = data.roomdata.name;
        edit_room_form.elements['area'].value = data.roomdata.area;
        edit_room_form.elements['price'].value = data.roomdata.price;
        edit_room_form.elements['quantity'].value = data.roomdata.quantity;
        edit_room_form.elements['adult'].value = data.roomdata.adult;
        edit_room_form.elements['children'].value = data.roomdata.children;
        edit_room_form.elements['desc'].value = data.roomdata.description;
        edit_room_form.elements['room_id'].value = data.roomdata.id;


        // Get all checkboxes with the name 'facilities[]'
        let facilityElements = document.querySelectorAll('input[name="facilities[]"]');

        facilityElements.forEach(el => {
            if (data.facilities.includes(Number(el.value))) {
                el.checked = true;
            } else {
                el.checked = false;
            }
        });

        let choiceElements = document.querySelectorAll('input[name="choices[]"]');

        choiceElements.forEach(el => {
            if (data.choices.includes(Number(el.value))) {
                el.checked = true;
            } else {
                el.checked = false;
            }
        });
    }
    // Send the request with the room ID
    xhr.send('get_room=' + id);
}

edit_room_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_room();
});

function submit_edit_room() {

    let room_id_element = edit_room_form.elements['room_id'];  // Access the room_id input field by its name attribute
    console.log('Room ID:', room_id_element ? room_id_element.value : 'Element not found');

    let data = new FormData();
    data.append('edit_room', '');

    data.append('room_id', room_id_element ? room_id_element.value : '');

    //data.append('room_id', edit_room_form.elements['room_id'].value);
    data.append('area', edit_room_form.elements['area'].value);
    data.append('name', edit_room_form.elements['name'].value);
    data.append('price', edit_room_form.elements['price'].value);
    data.append('quantity', edit_room_form.elements['quantity'].value);
    data.append('adult', edit_room_form.elements['adult'].value);
    data.append('children', edit_room_form.elements['children'].value);
    data.append('desc', edit_room_form.elements['desc'].value);


    let choices = [];
    let choiceElements = Array.from(edit_room_form.querySelectorAll('input[name="choices[]"]:checked'));
    choiceElements.forEach(el => {
        choices.push(el.value);
    });

    let facilities = [];
    let facilityElements = Array.from(edit_room_form.querySelectorAll('input[name="facilities[]"]:checked'));
    facilityElements.forEach(el => {
        facilities.push(el.value);
    });

    data.append('choices', JSON.stringify(choices));
    data.append('facilities', JSON.stringify(facilities));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);

    xhr.onload = function () {
        console.log("Response status:", this.status);  // Check the response status
        console.log("Response text:", this.responseText);  // Check the response text

        var myModal = document.getElementById('edit-room');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Room Updated!');
            edit_room_form.reset();
            get_all_rooms();
        } else {
            alert('error', 'Server Down! Received: ' + this.responseText);
        }
    }
    xhr.onerror = function () {
        console.log("Request error");  // Log if there is an error in the request
    };
    xhr.send(data);
}

function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Status toggled!');
            get_all_rooms();
        }
        else {
            alert('error', 'Server Down!');
        }
    }
    xhr.send('toggle_status=' + id + '&value=' + val);
}

function remove_room(room_id){
    if(confirm("Are you sure, you want to delete this room?")){
    let data = new FormData();
    data.append('room_id', room_id);
    data.append('remove_room','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Room Removed!');
            get_all_rooms();
        }
        else {
            alert('error', 'Room removal failed!');
        }
    }
    xhr.send(data);
    }
}
window.onload = function () {
    get_all_rooms();
}