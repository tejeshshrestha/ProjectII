let general_data;       //not a local variable IMP

let general_s_form = document.getElementById('general_s_form');

let site_title_inp = document.getElementById('site_title_inp');
let site_about_inp = document.getElementById('site_about_inp');



function get_general() {
    let site_title = document.getElementById('site_title');
    let site_about = document.getElementById('site_about');

    let shutdown_toggle = document.getElementById('shutdown-toggle');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');      //default format
    // xhr.onreadystatechange =function(){if status ==200 ....} is same as:
    xhr.onload = function () {
        general_data = JSON.parse(this.responseText);

        site_title.innerText = general_data.site_title;
        site_about.innerText = general_data.site_about;

        site_title_inp.value = general_data.site_title;
        site_about_inp.value = general_data.site_about;

        if (general_data.shutdown == 0) {
            shutdown_toggle.checked = false;
            shutdown_toggle.value = 0;
        } else {
            shutdown_toggle.checked = true;
            shutdown_toggle.value = 1;
        }
    }


    xhr.send('get_general');
}

function update(site_title_val, site_about_val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        var myModal = document.getElementById('general-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Changes Saved!');
            get_general();
        } else {
            alert('error', 'No changes made!');
        }
    };

    let params = new URLSearchParams({
        site_title: site_title_val,
        site_about: site_about_val,
        update: true
    }).toString();

    xhr.send(params);
}

function shutdown(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');      //default format
    // xhr.onreadystatechange =function(){if status ==200 ....} is same as:
    xhr.onload = function () {
        if (this.responseText == 1 && general_data.shutdown == 0) {
            alert('success', 'Shutdown mode ON!');
        } else {
            alert('success', 'Shutdown mode OFF!');
        }
        get_general();
    }
    xhr.send('shutdown=' + val);
}

general_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    update(site_title_inp.value, site_about_inp.value);
})

window.onload = function () {
    get_general();
}
