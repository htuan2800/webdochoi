<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - PROFILE</title>
</head>

<body class="bg-light">
    <?php require('inc/header.php');
    if (!(isset($_SESSION['user']['login']) && $_SESSION['user']['login'] == true)) {
        redirect('index.php');
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">PROFILE</h2>
                <div style="font-size:14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">PROFILE</a>
                </div>
            </div>

            <div class="col-12 my-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form action="" id="info-form">
                        <h5 class="mb-3 fw-bold">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="username">User name: </label>
                                <input required type="text" class="form-control shadow-none" name="username" id="username-info" placeholder="Your username..." value="<?php echo $_SESSION['user']['uName']; ?>" /><br>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="email">Email: </label>
                                <input required type="text" class="form-control shadow-none" name="email" id="email-info" placeholder="Your email..." value="<?php echo $_SESSION['user']['uEmail']; ?>" /><br>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="fullname">Full Name: </label>
                                <input required type="text" class="form-control shadow-none" name="firstname" id="firstname-info" placeholder="Your firstname..." value="<?php echo $_SESSION['user']['uFullName']; ?>" /><br>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="address">Address: </label>
                                <input required type="text" class="form-control shadow-none" name="address" id="address-info" placeholder="Your address..." value="<?php echo $_SESSION['user']['uAddress']; ?>" /><br>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="phone">Phone Number: </label>
                                <input required type="number" class="form-control shadow-none" name="phone" id="phone-info" placeholder="Your phone..." value="<?php echo $_SESSION['user']['uPhone']; ?>" /><br>
                            </div>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-8 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form action="" id="pass-form">
                        <h5 class="mb-3 fw-bold">Change Password</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input required type="password" class="form-control shadow-none" name="new_pass" /><br>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input required type="password" class="form-control shadow-none" name="confirm_pass" /><br>
                            </div>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require('inc/footer.php'); ?>

    <script>
        let info_form = document.getElementById('info-form');
        info_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let data = new FormData();
            data.append('info_form', '');
            data.append('username', info_form.elements['username'].value);
            data.append('email', info_form.elements['email'].value);
            data.append('fullname', info_form.elements['fullname'].value);
            data.append('address', info_form.elements['address'].value);
            data.append('phone', info_form.elements['phone'].value);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            xhr.onload = function() {
                if (this.responseText == "phone_already") {
                    toast('error', "Phone number is already registered!");
                } else if (this.responseText == 'email_already') {
                    toast('error', "Email is already registered!");
                } else if (this.responseText == 'username_already') {
                    toast('error', "Username is already registered!");
                } else if (this.responseText == 23) {
                    toast('error', "No chnages made!");
                } else {
                    toast('success', "Changes saved!");
                }
            }
            xhr.send(data);
        })

        let pass_form =document.getElementById('pass-form');
        pass_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let new_pass=pass_form.elements['new_pass'].value;
            let confirm_pass=pass_form.elements['confirm_pass'].value;
            if(new_pass!=confirm_pass){
                toast('error', "Password do not match!");
                return false;
            }
            let data = new FormData();
            data.append('pass_form', '');
            data.append('new_pass',new_pass);
            data.append('confirm_pass',confirm_pass);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            xhr.onload = function() {
                if (this.responseText == 'mismatch') {
                    toast('error', "Password do not match!");
                } else if(this.responseText==0)
                {
                    toast('error', "Updation failed!");
                } else {
                    toast('success', "Thay đổi thành công! Hệ thống sẽ khởi động lại!");
                    pass_form.reset();
                    setTimeout(function(){
                        window.location.href='logout.php'
                    },3000);                   
                }
            }
            xhr.send(data);
        })
    </script>
</body>

</html>