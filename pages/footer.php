<!-- Footer -->

<div class="container-fluid bg-white shadow mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2">
                <?php
                echo $settings_r['site_title'];
                ?>
            </h3>
            <p>
                <?php
                echo $settings_r['site_about'];
                ?>
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a><br />
            <a href="hotels.php" class="d-inline-block mb-2 text-dark text-decoration-none">Rooms</a><br />
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a><br />
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contacts</a>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Follow Links</h5>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatem ducimus necessitatibus ullam magnam
                maiores facilis adipisci voluptatum quaerat quas, eaque beatae placeat ipsam, cum incidunt iure libero
                a.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>

<script>

    function alert(type, msg, position = 'body') {
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `<div class="alert ${bs_class} alert-dismissible show custom-alert role="alert">
            <strong class="ms-5">${msg}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;

        if (position == 'body') {
            document.body.append(element);
            element.classList.add('custom-alert');
        } else {
            document.getElementById(position).appendChild(element);
        }
        setTimeout(remAlert, 3000);
    }

    function remAlert() {
        document.getElementsByClassName('alert')[0].remove();
    }


    function setActive() {
        let navbar = document.getElementById('dashboard-menu');
        let a_tags = navbar.getElementsByTagName('a');

        for (i = 0; i < a_tags.length; i++) {
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if (document.location.href.indexOf(file_name) >= 0) {
                a_tags[i].classList.add('active');
            }
        }
    }

    let register_form = document.getElementById('register-form');

    register_form.addEventListener('submit', function (e) {
        e.preventDefault();

        let data = new FormData();

        data.append('name', register_form.elements['name'].value);
        data.append('email', register_form.elements['email'].value);
        data.append('phone', register_form.elements['phone'].value);
        data.append('address', register_form.elements['address'].value);
        data.append('DOB', register_form.elements['DOB'].value);
        data.append('pass', register_form.elements['pass'].value);
        data.append('cpass', register_form.elements['cpass'].value);
        data.append('picture', register_form.elements['picture'].files[0]);
        data.append('register', '');

        var myModal = document.getElementById('registerModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function () {
            if (this.responseText == 'pass_mismatch') {
                alert('error', 'Password Mismatch');
            }
            else if (this.responseText == 'email_already') {
                alert('error', 'Email is already Registered!');
            }
            else if (this.responseText == 'phone_already') {
                alert('error', 'Phone num is already Registered!');
            }
            else if (this.responseText == 'inv_img') {
                alert('error', 'Only JPG, WEBP & PNG images allowed!');
            }
            else if (this.responseText == 'upload_failed') {
                alert('error', 'Image upload failed!');
            }
            else if (this.responseText == 'mail_failed') {
                alert('error', 'Cannot send confirmation email! Server Down!');
            }
            else if (this.responseText == 'insert_failed') {
                alert('error', 'Registration failed...Server Down!');
            }
            else {
                alert('success', "Registration successful! Confirmation link has been sent to your email!");
                register_form.reset();
            }
        }
        xhr.send(data);

    });


    let login_form = document.getElementById('login-form');

    login_form.addEventListener('submit', function (e) {
        e.preventDefault();

        let data = new FormData();

        data.append('email_mob', login_form.elements['email_mob'].value);
        data.append('pass', login_form.elements['pass'].value);

        data.append('login', '');

        var myModal = document.getElementById('loginModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function () {
            if (this.responseText == 'inv_email_mob') {
                alert('error', 'Invalid Email/Mobile Num');
            }
            else if (this.responseText == 'not_verified') {
                alert('error', 'Email is not verified!');
            }
            else if (this.responseText == 'inactive_cred') {
                alert('error', 'Account Suspended. Please contact Admin!');
            }
            else if (this.responseText == 'invalid_pass') {
                alert('error', 'Incorrect Password!');
            }
            else {
                window.location = window.location.pathname;
            }
        }
        xhr.send(data);
    });


    function checkLogin(status, room_id) {
        if (status) {
            window.location.href = 'confirm_booking.php?id=' + room_id;
        } else {
            alert('error', 'Please login first to book rooms!');
        }
    }

    setActive();

</script>