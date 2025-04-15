<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - CART</title>
    <style>
        .stepper {
            margin: 40px 24px;
            box-sizing: border-box;
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            position: relative;
        }

        .stepper__step {
            cursor: default;
            text-align: center;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            width: 140px;
            z-index: 1;
        }

        .stepper__step-icon--finish {
            border-color: #2dc258;
            color: #2dc258;
        }

        .stepper__step-icon1 {
            align-items: center;
            background-color: #fff;
            border: 4px solid #2dc258;
            border-radius: 50%;
            box-sizing: border-box;
            color: #e0e0e0;
            display: flex;
            flex-direction: column;
            font-size: 1.875rem;
            height: 60px;
            justify-content: center;
            margin: auto;
            position: relative;
            transition: background-color .3s cubic-bezier(.4, 0, .2, 1) .7s, border-color .3s cubic-bezier(.4, 0, .2, 1) .7s, color .3s cubic-bezier(.4, 0, .2, 1) .7s;
            width: 60px;
        }

        .background_step {
            background-color: #2dc258 !important;
        }

        .stepper__step-icon2 {
            align-items: center;
            background-color: #fff;
            border: 4px solid #000;
            border-radius: 50%;
            box-sizing: border-box;
            color: #e0e0e0;
            display: flex;
            flex-direction: column;
            font-size: 1.875rem;
            height: 60px;
            justify-content: center;
            margin: auto;
            position: relative;
            transition: background-color .3s cubic-bezier(.4, 0, .2, 1) .7s, border-color .3s cubic-bezier(.4, 0, .2, 1) .7s, color .3s cubic-bezier(.4, 0, .2, 1) .7s;
            width: 60px;
        }

        .stepper__step-text {
            color: rgba(0, 0, 0, .8);
            font-size: .875rem;
            line-height: 1.25rem;
            margin: 1.25rem 0 .25rem;
            text-transform: capitalize;
        }

        .stepper__step-date {
            color: rgba(0, 0, 0, .26);
            font-size: .75rem;
            height: .875rem;
        }

        .stepper__line {
            height: 4px;
            position: absolute;
            top: 29px;
            width: 100%;
        }


        .stepper__line-background,
        .stepper__line-foreground {
            box-sizing: border-box;
            height: 100%;
            margin: 0 70px;
            position: absolute;
            width: calc(100% - 140px);
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <?php
    $login = 0;
    if (isset($_SESSION['user']['login']) && $_SESSION['user']['login'] == true) {
        $login = 1;
    }
    ?>

    <?php
    if (!isset($_GET['bill_id'])) {
        redirect('products.php');
    }
    $data = filteration($_GET);
    $purchase_res = select("SELECT * FROM `bill` WHERE `BillID`=?", [$data['bill_id']], 'i');

    if (mysqli_num_rows($purchase_res) == 0) {
        redirect('products.php');
    }

    $purchase_data = mysqli_fetch_assoc($purchase_res);
    $data = "";
    if ($purchase_data['status'] == "Đã Đặt") {
        $data .= " <div class='stepper'>
            <div class='stepper__step stepper__step--finish' aria-label='Đơn hàng $purchase_data[status], , $purchase_data[CreateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/order.svg'/>
                </div>
                <div class='stepper__step-text'>Đơn hàng $purchase_data[status]</div>
                <div class='stepper__step-date'>$purchase_data[CreateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ xác nhận' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish background_step'>
                    <img style='width:40px;' src='images/status/money.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ xác nhận</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ lấy hàng' tabindex='0'>
                <div class='stepper__step-icon2 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/truck.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ lấy hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ giao hàng' tabindex='0'>
                <div class='stepper__step-icon2 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/box.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ giao hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chưa nhận hàng' tabindex='0'>
                <div class='stepper__step-icon2 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/receive.svg'/>
                </div>
                <div class='stepper__step-text'>Chưa nhận hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__line'>
                <div class='stepper__line-background' style='background: rgb(0,0,0);'></div>
                <div class='stepper__line-foreground' style='width: calc(100% - 140px); background: rgb(0,0,0);'></div>
            </div>";
    } else if ($purchase_data['status'] == "Đã Xác Nhận") {
        $data .= " <div class='stepper'>
            <div class='stepper__step stepper__step--finish' aria-label='Đơn hàng Đã Đặt, ,$purchase_data[CreateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/money.svg'/>
                </div>
                <div class='stepper__step-text'>Đơn hàng Đã Đặt</div>
                <div class='stepper__step-date'>$purchase_data[CreateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Đơn hàng $purchase_data[status], , $purchase_data[UpdateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/order.svg'/>
                </div>
                <div class='stepper__step-text'>Đơn hàng $purchase_data[status]</div>
                <div class='stepper__step-date'>$purchase_data[UpdateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ lấy hàng' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish background_step'>
                    <img style='width:40px;' src='images/status/truck.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ lấy hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ giao hàng' tabindex='0'>
                <div class='stepper__step-icon2 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/box.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ giao hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chưa nhận hàng' tabindex='0'>
                <div class='stepper__step-icon2 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/receive.svg'/>
                </div>
                <div class='stepper__step-text'>Chưa nhận hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__line'>
                <div class='stepper__line-background' style='background: rgb(0,0,0);'></div>
                <div class='stepper__line-foreground' style='width: calc(100% - 140px); background: rgb(0,0,0);'></div>
            </div>";
    } else if ($purchase_data['status'] == "Đã Lấy Hàng") {
        $data .= " <div class='stepper'>
            <div class='stepper__step stepper__step--finish' aria-label='Đơn hàng Đã Đặt, , $purchase_data[CreateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/order.svg'/>
                </div>
                <div class='stepper__step-text'>Đơn hàng Đã Đặt</div>
                <div class='stepper__step-date'>$purchase_data[CreateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Đã Xác Nhận' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/money.svg'/>
                </div>
                <div class='stepper__step-text'>Đã Xác Nhận</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='$purchase_data[status], , $purchase_data[UpdateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/truck.svg'/>
                </div>
                <div class='stepper__step-text'>$purchase_data[status]</div>
                <div class='stepper__step-date'>$purchase_data[UpdateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ giao hàng' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish background_step'>
                    <img style='width:40px;' src='images/status/box.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ giao hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chưa nhận hàng' tabindex='0'>
                <div class='stepper__step-icon2 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/receive.svg'/>
                </div>
                <div class='stepper__step-text'>Chưa nhận hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__line'>
                <div class='stepper__line-background' style='background: rgb(0,0,0);'></div>
                <div class='stepper__line-foreground' style='width: calc(100% - 140px); background: rgb(0,0,0);'></div>
            </div>";
    } else if ($purchase_data['status'] == "Đang Giao Hàng") {
        $data .= " <div class='stepper'>
            <div class='stepper__step stepper__step--finish' aria-label='Đơn hàng Đã Đặt, , $purchase_data[CreateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/order.svg'/>
                </div>
                <div class='stepper__step-text'>Đơn hàng Đã Đặt</div>
                <div class='stepper__step-date'>$purchase_data[CreateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ xác nhận' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/money.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ xác nhận</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ lấy hàng' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/truck.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ lấy hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='$purchase_data[status], , $purchase_data[UpdateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/box.svg'/>
                </div>
                <div class='stepper__step-text'>$purchase_data[status]</div>
                <div class='stepper__step-date'>$purchase_data[UpdateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chưa nhận hàng' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish background_step'>
                    <img style='width:40px;' src='images/status/receive.svg'/>
                </div>
                <div class='stepper__step-text'>Chưa nhận hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__line'>
                <div class='stepper__line-background' style='background: rgb(0,0,0);'></div>
                <div class='stepper__line-foreground' style='width: calc(100% - 140px); background: rgb(0,0,0);'></div>
            </div>";
    } else if ($purchase_data['status'] == "Đã Nhận Hàng") {
        $data .= " <div class='stepper'>
            <div class='stepper__step stepper__step--finish' aria-label='Đơn hàng Đã Đặt, , $purchase_data[CreateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/order.svg'/>
                </div>
                <div class='stepper__step-text'>Đơn hàng Đã Đặt</div>
                <div class='stepper__step-date'>$purchase_data[CreateTime]</div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ xác nhận' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/money.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ xác nhận</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ lấy hàng' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/truck.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ lấy hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='Chờ giao hàng' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/box.svg'/>
                </div>
                <div class='stepper__step-text'>Chờ giao hàng</div>
                <div class='stepper__step-date'></div>
            </div>
            <div class='stepper__step stepper__step--finish' aria-label='$purchase_data[status], , $purchase_data[UpdateTime]' tabindex='0'>
                <div class='stepper__step-icon1 stepper__step-icon--finish'>
                    <img style='width:40px;' src='images/status/receive.svg'/>
                </div>
                <div class='stepper__step-text'>$purchase_data[status]</div>
                <div class='stepper__step-date'>$purchase_data[UpdateTime]</div>
            </div>
            <div class='stepper__line'>
                <div class='stepper__line-background' style='background: rgb(0,0,0);'></div>
                <div class='stepper__line-foreground' style='width: calc(100% - 140px); background: rgb(0,0,0);'></div>
            </div>";
    }
    ?>
    <div class="RgsTlq">
        <?php
        echo $data;
        ?>
    </div>
    </div>
    <div class="container-fluid" id="main-content">

        <div class="row">
            <?php
            $data = filteration($_GET);
            $res1 = select("SELECT * FROM `user` WHERE `UserID`=?", [$_SESSION['user']['uId']], 'i');
            if (mysqli_num_rows($res1) > 0) {
                $row1 = $res1->fetch_assoc();
                $res2 = select("SELECT * FROM `bill` WHERE `CustomerID`=? and `BillID`=?", [$row1['UserID'], $data['bill_id']], 'ii');
                while ($row2 = mysqli_fetch_assoc($res2)) {
                    $dataCart = "";
                    $sale=$row2['Subtotal']-$row2['Total'];
                    $formatted_subtotal=number_format($row2['Subtotal'], 0, ',', '.');
                    $formatted_sale=number_format($sale, 0, ',', '.');
                    $formatted_total=number_format($row2['Total'], 0, ',', '.');
                    $statusBill=$row2['status'];
                    $dataBill = "
                         <div class='card-body m-2 p-4 border rounded-3 shadow-sm bg-light'>
                            <div class='d-flex justify-content-between align-items-center border-bottom pb-2 mb-2'>
                                <p class='fw-semibold text-secondary mb-0'>Tổng đơn giá:</p>
                                <p class='card-text fs-5 fw-bold text-primary mb-0'> $formatted_subtotal vnđ</p>
                            </div>
                            
                            <div class='d-flex justify-content-between align-items-center border-bottom pb-2 mb-2'>
                                <p class='fw-semibold text-secondary mb-0'>Đã giảm:</p>
                                <p class='card-text fs-5 fw-bold text-danger mb-0'>$formatted_sale vnđ</p>
                            </div>
                            
                            <div class='d-flex justify-content-between align-items-center border-bottom pb-2 mb-2'>
                                <p class='fw-semibold text-secondary mb-0'>Thành tiền:</p>
                                <p class='card-text fs-5 fw-bold text-success mb-0'>$formatted_total vnđ</p>
                            </div>
                            
                            <div class='d-flex justify-content-between align-items-center border-bottom pb-2 mb-2'>
                                <p class='fw-semibold text-secondary mb-0'>Phương thức thanh toán:</p>
                                <p class='card-title fs-6 fw-bold'>$row2[payment]</p>
                            </div>
                            
                            <div class='d-flex justify-content-between align-items-center'>
                                <p class='fw-semibold text-secondary mb-0'>Địa chỉ nhận hàng:</p>
                                <p class='card-text'>$row2[Address]</p>
                            </div>
                        </div>

                    ";
                    $res3 = select("SELECT * FROM `billdetail` WHERE `BillID`=?", [$row2['BillID']], 'i');
                    while ($row3 = mysqli_fetch_assoc($res3)) {
                        $res4 = select("SELECT * FROM `product` WHERE `ProductID`=?", [$row3['ProductID']], 'i');
                        if (mysqli_num_rows($res4) > 0) {
                            $row4 = $res4->fetch_assoc();

                            $formatted_img = '';
                            if ($row4['IMG'] == null) {
                                $formatted_img = 'images/products/thumbnail.jpg';
                            } else {
                                $formatted_img = $row4['IMG'];
                            }
                            $formatted_unitprice=number_format($row3['Unitprice'], 0, ',', '.');
                            $dataCart .= "<div class='card-body border border-black m-2 d-flex justify-content-between'>
                                     <div>
                                     <h5 class='card-title fw-bold'>$row4[ProductName] x $row3[Quantity]</h5>
                                     <p class='card-text'>Đơn giá: $formatted_unitprice vnđ</p>
                                     <p class='card-text'>Độ tuổi: $row4[Age]</p>
                                     <p class='card-text'>Nguồn gốc: $row4[Origin]</p>
                                     </div>
                                     <img src='./admin/$formatted_img' class='img-fluid' style='width:90px;'>
                                 </div>";
                        }
                    }

                    echo <<<donhang
                             <div class="ms-auto p-4 overflow-hidden">
                                 <div class="card">
                                     <div>
                                     $dataCart
                                     </div>
                                     <div>
                                     $dataBill
                                     </div>
                                 </div>
                             </div>
                         donhang;
                }
            }

            ?>

            <?php
                if($statusBill=="Đã Đặt")
                {

                    echo <<< data
                        <button type="button" class="btn btn-danger" id="cancel-order" onClick=cancelOrder($data[bill_id])>Hủy đơn</button>
                    data;
                }
            ?>
        </div>

    </div>

    <?php require('inc/footer.php'); ?>
</body>

</html>