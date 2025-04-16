<div class="container-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2">MYKINGDOM</h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a><br>
            <a href="products.php" class="d-inline-block mb-2 text-dark text-decoration-none">Products</a><br>
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact us</a><br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">About</a>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Follow us</h5>
            <a href="https://x.com/" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-twitter-x"></i> Twitter
            </a><br>
            <a href="https://www.facebook.com/" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-facebook"></i> Facebook
            </a><br>
            <a href="https://www.instagram.com/" class="d-inline-block text-dark text-decoration-none">
                <i class="bi bi-instagram"></i> Instagram
            </a><br>
        </div>
    </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Design and Developed by Tuan</h6>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    function toast(type, msg, position = 'body') {
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
        <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> 
        `;
        if (position == 'body') {
            document.body.append(element);
            element.classList.add('custom-alert');
        } else {
            document.getElementById(position).appendChild(element);
        }
        setTimeout(remAlert, 2000);
    }

    function remAlert() {
        document.getElementsByClassName('alert')[0].remove();
    }

    let uId = "";

    function setActive() {
        let navbar = document.getElementById('dashboard-menu');
        let a_tags = navbar.getElementsByTagName('a');

        for (i = 0; i < a_tags.length; i++) {
            let file = a_tags[i].href.split('/').pop(); // example.php
            let file_name = file.split('.')[0];

            if (document.location.href.indexOf(file_name) >= 0) {
                a_tags[i].classList.add('active');
            }
        }
    }

    function setActive() {
        let navbar = document.getElementById('navbar');
        let a_tags = navbar.getElementsByTagName('a');

        for (i = 0; i < a_tags.length; i++) {
            let file = a_tags[i].href.split('/').pop(); // example.php
            let file_name = file.split('.')[0];

            if (document.location.href.indexOf(file_name) >= 0) {
                a_tags[i].classList.add('active');
            }
        }
    }


    let register_form = document.getElementById('register-form');
    register_form.addEventListener('submit', (e) => {
        e.preventDefault();
        let pattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
        if (pattern.test(document.getElementById('pass').value)) {
            let data = new FormData();
            data.append('FullName', register_form.elements['FullName'].value);
            data.append('gender', register_form.elements['gender'].value);
            data.append('email', register_form.elements['email'].value);
            data.append('phonenum', register_form.elements['phonenum'].value);
            data.append('address', register_form.elements['address'].value);
            data.append('username', register_form.elements['username'].value);
            data.append('pass', register_form.elements['pass'].value);
            data.append('cpass', register_form.elements['cpass'].value);
            data.append('register', '');



            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/login_register.php", true);

            xhr.onload = function() {
                if (this.responseText == "pass_mismatch") {
                    let tpassElement = document.querySelector('.password-message');
                    if (tpassElement) {
                        tpassElement.remove();
                    }
                    let divElement = document.querySelector('.tcpass');
                    let spanHTML = '<span class="password-message">Mật khẩu không trùng khớp!</span>';
                    divElement.insertAdjacentHTML('beforeend', spanHTML);
                } else if (this.responseText == "username_already") {
                    toast('error', "User name is already registered!");
                } else if (this.responseText == "email_already") {
                    toast('error', "Email is already registered!");
                } else if (this.responseText == "phone_already") {
                    toast('error', "Phone number is already registered!");
                } else if (this.responseText == "ins_failed") {
                    toast('error', "Registration failed! Server down!");
                } else if (this.responseText == 1) {
                    var myModal = document.getElementById('registerModal');
                    var modal = bootstrap.Modal.getInstance(myModal);
                    modal.hide();
                    toast('success', "Registration successful!");
                    document.querySelectorAll('span.password-message').forEach(e => e.remove());
                    register_form.reset();
                }
            }
            xhr.send(data);
        } else {
            let tpassElement = document.querySelector('.password-message');
            if (tpassElement) {
                tpassElement.remove();
            }
            let divElement = document.querySelector('.tpass');
            let spanHTML = '<span class="password-message">Mật khẩu phải có độ dài từ 8 kí tự, 1 số, 1 chữ và 1 kí tự đặc biệt!</span>';
            divElement.insertAdjacentHTML('beforeend', spanHTML);
        }

    })


    let login_form = document.getElementById('login-form');
    login_form.addEventListener('submit', (e) => {
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

        xhr.onload = function() {
            if (this.responseText == "inv_email_mob") {
                toast('error', "Invalid Email or Mobile Number!");
            } else if (this.responseText == "inactive") {
                toast('error', "Account Suspended! Please contact Admin.");
            } else if (this.responseText == "invalid_pass") {
                toast('error', "Incorrect Password!");
            } else {
                window.location = window.location.pathname;
            }
        }
        xhr.send(data);

    })

    let forgot_form = document.getElementById('forgot-form');
    forgot_form.addEventListener('submit', (e) => {
        e.preventDefault();
        document.getElementById('forgotSpinner').style.display = 'flex'; // Show
        let data = new FormData();
        data.append('email', forgot_form.elements['email'].value);
        data.append('forgot_pass', '');



        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/mail_service.php", true);
        // Show/hide spinner
        xhr.onload = function() {
            if (this.responseText == 0) {
                document.getElementById('forgotSpinner').style.display = 'none'; // Hide
                toast('error', "Email not found!");
            } else if (this.responseText == 1) {
                document.getElementById('forgotSpinner').style.display = 'none'; // Hide
                var myModal = document.getElementById('forgotModal');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                var verifyModal = document.getElementById('tokenModal');
                console.log(verifyModal);
                var modalVerify = new bootstrap.Modal(verifyModal);
                modalVerify.show();
            }
        }
        xhr.send(data);
    })

    let token_form = document.getElementById('token-form');
    token_form.addEventListener('submit', (e) => {
        e.preventDefault();
        let data = new FormData();
        data.append('token', token_form.elements['token'].value);
        data.append('verify_token', '');

        var myModal = document.getElementById('tokenModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/mail_service.php", true);

        xhr.onload = function() {
            if (this.responseText == 0) {
                toast('error', "Email not found!");
            } else if (this.responseText == 1) {
                var resetModal = document.getElementById('resetpassModal');
                var modalReset = new bootstrap.Modal(resetModal);
                modalReset.show();
            }
        }
        xhr.send(data);
    })

    let reset_form = document.getElementById('reset-form');
    reset_form.addEventListener('submit', (e) => {
        e.preventDefault();
        let pattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
        if (pattern.test(reset_form.elements['pass'].value)) {
            if (reset_form.elements['pass'].value == reset_form.elements['cpass'].value) {
                let data = new FormData();
                data.append('pass', reset_form.elements['pass'].value);
                data.append('cpass', reset_form.elements['cpass'].value);
                data.append('reset_pass', '');

                var myModal = document.getElementById('resetpassModal');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/mail_service.php", true);

                xhr.onload = function() {
                    if (this.responseText == 0) {
                        alert('Server down!');
                    } else if (this.responseText == 1) {
                        toast('success', "Password changed successfully!");
                    }
                }
                xhr.send(data);
            } else {
                alert("Password do not match!");
            }
        } else {
            alert("Mật khẩu phải có độ dài từ 8 kí tự, 1 số, 1 chữ và 1 kí tự đặc biệt!");
        }
    })

    function countCart() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Đặt header cho POST request
        xhr.onload = function() {
            document.getElementById('cartCount').innerHTML = this.responseText;
        }
        xhr.send("cart_count=true"); // Gửi đúng định dạng dữ liệu POST
    }

    // Gọi khi load trang để cập nhật số lượng giỏ hàng

    setActive();
    document.addEventListener("DOMContentLoaded", function() {
        countCart();
    });

    function cancelOrder(bill_id) {
        if (confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?")) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/bill.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (this.responseText == 1) {
                    toast('success', "Hủy đơn thành công!");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }

            xhr.send("bill_id=" + bill_id);
        }
    }
</script>