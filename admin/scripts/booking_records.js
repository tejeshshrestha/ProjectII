function get_bookings(search='',page=1) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/booking_records.php", true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('table-data').innerHTML = this.responseText;
    }
    xhr.onerror = function () {
        console.log("Request error");  // Log if there is an error in the request
    }
    xhr.send('get_bookings&search=' + search +'&page='+page);
}

function search_user(username){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('users-data').innerHTML = this.responseText;
    }
    xhr.onerror = function () {
        console.log("Request error");  // Log if there is an error in the request
    }
    xhr.send('search_user&name='+username);
}

function download(id){
    window.location.href = 'generate_pdf.php?gen_pdf&id='+id;
}

window.onload = function () {
    get_bookings();
}