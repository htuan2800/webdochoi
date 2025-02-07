<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - CART</title>

</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>


    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="ms-auto p-4 overflow-hidden">

                <?php
                $login = 0;
                if (isset($_SESSION['user']['login']) && $_SESSION['user']['login'] == true) {
                    $login = 1;
                }
                ?>
                <?php
                if ($login == 1) {
                    echo "<a href='purchase.php' class='btn btn-sm text-white custom-bg shadow-none'>Xem đơn hàng đã đặt</a>";
                }
                ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="table-responsive-md" style="height:300px; overflow-y:scroll; width: 65%;">
                            <table class="table table-hover border text-cennter">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">Image</th>
                                        <th scope="col">ProductName</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-data">

                                </tbody>
                            </table>
                        </div>

                        <div class="card" style="width:30%;">
                            <div class="card-header">
                                Thanh Toán Đơn Hàng
                            </div>
                            <table class="table table-hover text-cennter">
                                <tbody id="thanhtoan">

                                </tbody>
                            </table>

                            <?php
                            if ($login == 1) {
                                echo <<< data
                                    <div class="card-footer">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#checkoutModal" class='btn btn-sm text-white custom-bg shadow-none' style="float:right;">Xác nhận</button>
                                    </div>
                                data;
                            } else {
                                echo <<< data
                                    <div class="card-footer">
                                        <button type="button" class='btn btn-sm text-white custom-bg shadow-none' style="float:right;" data-bs-toggle="modal" data-bs-target="#loginModal"> <!-- nhấn vào thì một modal có id #loginModal hiện lên -->
                                            Xin Mời đăng nhập tài khoản
                                        </button>
                                    </div>
                                data;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <form action="" id="enter_voucher" style="width:25%">
                    <div class="form-group d-flex align-items-center">
                        <div class="flex-grow-1">
                            <input type="text" class="form-control" placeholder="Enter Code" id="Vvoucher" name="Vvoucher" />
                        </div>
                        <button type="submit" class="btn btn-sm text-white shadow-none" style="background: #ff523b; padding: 10px;font-weight: bold;cursor: pointer;border-radius: 20px;">Áp dụng</button>
                    </div>
                </form>
                <div class="card" style="width:40%;">
                    <div class="card-header">
                        VoucherList
                    </div>
                    <table class="table table-hover text-cennter">
                        <tbody id="voucher_list">

                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>

    <?php
    $data = "";
    if ($login == 1) {

        $data .= "<form action='' id='hoadon' style='' onsubmit='handleFormSubmit(event)'>
            <h1 style='text-align: center;margin-bottom:10px'><i>Payment Form</i></h1>
            <div class='mb-3'>
            <label for='pttt' class='form-label'>Payments</label>
            <select id='pttt' style='float:right;'>
                <option selected value=''>Select Option</option>
                <option value='1'>VisaCard</option>
                <option value='2'>Paypal</option>
                <option value='3'>Internet Banking</option>
                <option value='4'>Cash</option>
            </select>
        </div>

        <div class='form-floating mb-3'>
            <textarea class='form-control' placeholder='Leave a comment here' id='delivery' name='delivery'></textarea>
            <label for='delivery'>Nhập địa chỉ nhận hàng</label>
        </div>

        <div style='display: flex; justify-content: center;'>
            <button type='submit' class='btn btn-sm text-white shadow-none' style='background: #ff523b; padding: 10px;font-weight: bold;cursor: pointer;margin: 20px;border-radius: 20px;'>Thanh Toán</button>
        </div>

        </form>";
    }
    ?>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <?php echo $data ?>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
</body>
<script>
    let enter_voucher_form = document.getElementById('enter_voucher');
    if (enter_voucher_form) {
        enter_voucher_form.addEventListener('submit', (e) => {
            e.preventDefault();
            let data = new FormData();

            data.append('code', enter_voucher_form.elements['Vvoucher'].value);
            data.append('voucher', '');

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/cart.php", true);

            xhr.onload = function() {
                const data = JSON.parse(this.responseText);
                if (data.success == true) {
                    toast('success', "Nhập Mã Thành Công!");
                    enter_voucher_form.reset();
                    get_product_cart();
                } else {
                    if (data.message == "wrong_code") {
                        toast('warning', "Nhập sai mã!");
                    } else if (data.message == "onecode") {
                        toast('warning', "Lỗi khi dùng mã!");
                    } else if (data.message == "nocart") {
                        toast('warning', "Không có sản phẩm trong giỏ hàng!");
                    } else if (data.message == "full") {
                        toast('warning', "Mã không còn nhập được vì đã quá số lượng!");
                    } else if (data.message == "entered") {
                        toast('danger', "Mỗi Mã Chỉ Được Nhập 1 Lần!");
                    } else if (data.message == "once") {
                        toast('danger', "Chỉ Nhập Được 1 Loại Mã!");
                    } else if (data.message == "less") {
                        let string ="Giỏ hàng phải đạt mức tối thiểu" + " " + data.price.toLocaleString('vi-VN') + " " + "đ";
                        toast('danger', string);
                    }
                }
            }
            xhr.send(data);
        });
    } else {
        console.error('Form không tồn tại');
    }

    function handleFormSubmit(event) {
        event.preventDefault(); // Ngăn form gửi đi mặc định
        let userhoadon_form = document.getElementById('hoadon');

        if (userhoadon_form.elements['pttt'].value == '') {
            toast('warning', "Chưa chọn phương thức thanh toán");
            return;
        }
        if (userhoadon_form.elements['delivery'].value == '') {
            toast('warning', "Mời nhập địa chỉ giao hàng");
            return;
        }

        let data = new FormData();
        let paymentValue = userhoadon_form.elements['pttt'].value;
        let paymentText = getVpttt(paymentValue); // Hàm này phải được định nghĩa sẵn
        data.append('pttt', paymentText);
        data.append('delivery', userhoadon_form.elements['delivery'].value);
        data.append('add_bill', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/bill.php", true);

        xhr.onload = function() {
            var myModal = document.getElementById('checkoutModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText == "GHR") {
                toast('warning', "Giỏ hàng rỗng!");
            } else if (this.responseText == 1) {
                userhoadon_form.reset();
                // toast('success', "Đơn hàng đã được đặt!");
                window.location.reload();
            } else if (this.responseText == "Server Down!") {
                toast('danger', "Server lỗi!");
            }
        }
        xhr.send(data);
    }


    function getVpttt(value) {
        switch (value) {
            case '1':
                return 'VisaCard';
            case '2':
                return 'Paypal';
            case '3':
                return 'Internet Banking';
            case '4':
                return 'Cash';
            default:
                return '';
        }
    }


    function get_product_cart() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/cart.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            let response = JSON.parse(this.responseText);
            document.getElementById('cart-data').innerHTML = response.cartData;
            document.getElementById('thanhtoan').innerHTML = response.thanhtoan;
            document.getElementById('voucher_list').innerHTML = response.voucherList;
            countCart();
        }
        xhr.send('get_product_cart');
    }

    window.onload = function() {
        get_product_cart();
    }

    function remove_product(product_id, cartID) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/cart.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.responseText == 1) {
                toast('success', 'Xóa Thành Công!');
                get_product_cart();
            } else {
                toast('danger', 'Server lỗi!');
            }
            countCart();
        }
        xhr.send('remove_product' + '&product_id=' + product_id);
    }

    function setQuantityPlus(product_id, max_quantity) {
        let quantity = document.getElementById('quantity' + product_id).value;
        quantity = parseInt(quantity);
        if (max_quantity < quantity + 1) {
            toast('danger', 'Không đủ số lượng hàng');
        } else {
            quantity = quantity + 1;
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/cart.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.responseText == 1) {
                    toast('success', 'Sản phẩm đã được cập nhật!');
                    get_product_cart();
                } else {
                    toast('danger', 'Server lỗi!');
                }
                countCart();
            }
            xhr.send('update_quantity' + '&product_id=' + product_id + '&quantity=' + quantity);
        }
    }

    function setQuantityMinus(product_id) {
        let quantity = document.getElementById('quantity' + product_id).value;
        quantity = parseInt(quantity);

        if (quantity > 1) {
            quantity = quantity - 1;
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/cart.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.responseText == 1) {
                    toast('success', 'Sản phẩm đã được cập nhật!');
                    get_product_cart();
                } else {
                    toast('danger', 'Server lỗi!');
                }
                countCart();
            }
            xhr.send('update_quantity' + '&product_id=' + product_id + '&quantity=' + quantity);
        }
    }

    function remove_voucher_list(voucherID) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/cart.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.responseText == 1) {
                toast('success', 'Xóa Voucher Thành Công!');
                get_product_cart();
            } else {
                toast('danger', 'Server lỗi!');
            }
        }
        xhr.send('remove_voucher=' + voucherID);
    }
</script>

</html>