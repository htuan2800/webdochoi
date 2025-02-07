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
        .edit:hover {
            background-color: gray;
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

    <div class="container-fluid" id="main-content">

        <div class="row">
            <?php
            $res1 = select("SELECT * FROM `user` WHERE `UserID`=?", [$_SESSION['user']['uId']], 'i');
            if (mysqli_num_rows($res1) > 0) {
                $row1 = $res1->fetch_assoc();
                $res2 = select("SELECT * FROM `bill` WHERE `CustomerID`=?", [$_SESSION['user']['uId']], 'i');
                while ($row2 = mysqli_fetch_assoc($res2)) {
                    $dataCart = "";
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

                            $formatted_unit=number_format($row3['Unitprice'], 0, ',', '.');
                            $dataCart .= "
                            <div class='card-body'>
                                <div class='border rounded p-3 d-flex align-items-center justify-content-between shadow-sm'>
                                    <div>
                                        <h5 class='card-title fw-bold mb-2 text-muted'>$row4[ProductName] x $row3[Quantity]</h5>
                                        <p class='card-text text-danger fs-5 fw-semibold'>$formatted_unit vnđ</p>
                                    </div>
                                    <img src='./admin/$formatted_img' class='img-thumbnail rounded-circle shadow-sm' style='width:60px;'>
                                </div>
                            </div>";
                        }
                    }

                    echo <<<donhang
                        <div class="ms-auto p-4 overflow-hidden">
                            <div class="card shadow-lg border-0">
                                <h5 class="card-header text-end bg-light text-black fw-bold">Đơn Hàng $row2[status]</h5>
                                <div>
                                $dataCart
                                </div>
                                <div class="card-footer text-center bg-light border-0">
                                    <a href="purchase_detail.php?bill_id=$row2[BillID]" class="btn btn-outline-success w-100 fw-bold">Xem Chi Tiết</a>
                                </div>                               
                            </div>
                        </div>
                    donhang;
                }
            }

            ?>


        </div>
    </div>

    <?php require('inc/footer.php'); ?>
</body>

</html>