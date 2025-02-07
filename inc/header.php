<link rel="stylesheet" href="./css/common.css">
<nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php">MYKINGDOM</a>
        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link me-2" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="products.php">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="contact.php">Contact us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
            </ul>

            <form action="/shoes1/search.php" onsubmit="return validateForm();" class="header__search" style="margin-right:50px;" onfocusout="handleFocusOut(event)">
                <div id="search-input">
                    <input id="skw" type="text" name="nameP" class="input-search" onkeyup="suggestSearch2(this.value);" placeholder="Bạn tìm gì..." name="key" autocomplete="off" maxlength="100" onfocus="handleFocusIn(event);">
                    <button type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div id="suggest-result">
                    <div class="result">

                    </div>
                </div>
            </form>

            <div class="d-flex">
                <button id="cartButton" class="btn btn-primary position-fixed bottom-0 end-0 m-4 p-3 rounded-circle" style="width: 70px; height: 70px;"
                            onclick="window.location.href='cart.php?page=urcart'"
                >
                    <i class="bi bi-cart" style="font-size: 2rem;"></i>
                    <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </button>
                <?php
                if (isset($_SESSION['user']['login']) && $_SESSION['user']['login'] == true) {
                    $uName = $_SESSION['user']['uName'];

                    echo <<<data
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-dark shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                <img src='images/users/profile.jpg' style='width:25px; height:25px;' class='me-1 rounded-circle'>
                                {$uName}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="purchase.php">Orders</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    data;
                } else {

                    echo <<< data
                        <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal"> <!-- nhấn vào thì một modal có id #loginModal hiện lên -->
                            Login
                        </button>
                        <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal"> <!-- nhấn vào thì một modal có id #loginModal hiện lên -->
                            Register
                        </button>
                    data;
                }
                ?>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="login-form" action="">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-circle fs-3 me-2"></i> User Login
                    </h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email / Mobile</label>
                        <input type="text" name="email_mob" required class="form-control shadow-none">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="pass" required class="form-control shadow-none">
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <button type="submit" class="btn btn-dark shadow-none">LOGIN</button>
                        <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal"> <!-- nhấn vào thì một modal có id #loginModal hiện lên -->
                            Forgot Password?
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content modal-content position-relative">
             <!-- Overlay spinner -->
             <div class="modal-spinner" id="forgotSpinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <form id="forgot-form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-circle fs-3 me-2"></i> Forgot Password
                    </h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" required class="form-control shadow-none" placeholder="Vui lòng nhập email để xác nhận">
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tokenModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="token-form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-circle fs-3 me-2"></i> Verification
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã xác nhân</label>
                        <input type="text" name="token" required class="form-control shadow-none" placeholder="Vui lòng nhập mã xác nhân">
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="resetpassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="reset-form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-circle fs-3 me-2"></i> Reset Password
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="pass" required class="form-control shadow-none" placeholder="Vui lòng nhập mật khẩu">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="cpass" required class="form-control shadow-none" placeholder="Xác nhận mật khẩu">
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" id="register-form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-lines-fill fs-3 me-2"></i> User Registration
                    </h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span class="badge bg-light text-dark mb-3 text-wrap lh-base">
                        Note: Your details must match with your ID (Aadhaar card, passport, driving license, etc.)
                        that will be required during check-in.
                    </span>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 ps-0 mb-3">
                                <label class="form-label">Full Name</label>
                                <input name="FullName" type="text" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 ps-0 mb-3">
                                <input type="radio" id="male" name="gender" value="m" required>
                                  <label for="male">MALE</label><br>
                                <input type="radio" id="female" name="gender" value="fm" required>
                                  <label for="female">FEMALE</label><br>
                            </div>

                            <div class="col-md-6 p-0 mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input name="phonenum" type="number" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">UserName</label>
                                <input name="username" type="text" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-12 p-0 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control shadow-none" rows="1" required></textarea>
                            </div>

                            <div class="col-md-12 ps-0 mb-3 tpass">
                                <label class="form-label">Password</label>
                                <input id="pass" name="pass" type="password" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-12 p-0 mb-3 tcpass">
                                <label class="form-label">Confirm Password</label>
                                <input id="cpass" name="cpass" type="password" class="form-control shadow-none" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-center my-1">
                        <button type="submit" class="btn btn-dark shadow-none">REGISTER</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        var input = document.getElementById('skw').value;
        if (input.trim() === "") {
            alert("Vui lòng nhập nội dung tìm kiếm.");
            return false;
        }
        return true;
    }


    function handleFocusIn(event) {
        // Lấy thẻ div suggest-result
        var suggestResultDiv = document.getElementById("suggest-result");

        // Tạo thẻ h1 mới nếu chưa tồn tại
        // if (!document.getElementById("note")) {
        //     var h1 = document.createElement("h1");
        //     h1.id = "note";
        //     var text = document.createTextNode("Gợi ý cho bạn!");
        //     h1.appendChild(text);
        //     suggestResultDiv.appendChild(h1);
        // }

        // Hiển thị thẻ div suggest-result
        suggestResultDiv.style.display = "block";
    }

    function handleFocusOut(event) {
        // Kiểm tra nếu mục tiêu của sự kiện không phải là một phần tử con của #suggest-result
        if (!document.getElementById("suggest-result").contains(event.relatedTarget)) {
            document.getElementById("suggest-result").style.display = "none";
        }
    }

    // Khai báo biến toàn cục để lưu trữ timeout ID
    let searchTimeout;

    function suggestSearch2(value) {
        // Nếu có giá trị đang nhập liệu
        if (value != "") {
            $('.suggest').empty();
            $('.heading').text('Sản phẩm tìm kiếm');
            clearTimeout(searchTimeout);
            // Thiết lập một timeout mới
            searchTimeout = setTimeout(function() {
                // Gửi yêu cầu AJAX sau khi trì hoãn 2 giây
                $.ajax({
                    method: 'POST',
                    url: 'ajax/product.php',
                    data: {
                        checkSearch: true,
                        text: value
                    },
                    success: function(response) {
                        $('.result').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }, 1000); // 2000 ms = 2 giây
        } else {
            // $('.result').empty();
        }
    }
</script>