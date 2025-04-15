<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
if (isset($_POST['add_bill'])) {
    $data = filteration($_POST);
    if (isset($_SESSION['user'])) {

        // Kiểm tra giỏ hàng có dữ liệu không
        if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            echo 'GHR';
            exit;
        }
        $Subtotal = 0;
        $sub = 0;
        $billDetails = [];

        if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $cartItem) {
                $Subtotal += $cartItem['Quantity'] * $cartItem['ProductPrice'];
                $billDetails[] = [
                    'ProductID' => $cartItem['ProductID'],
                    'Quantity' => $cartItem['Quantity'],
                    'UnitPrice' => $cartItem['ProductPrice']
                ];
            }
        }

        if (!empty($_SESSION['voucher']) && is_array($_SESSION['voucher'])) {
            $voucher = $_SESSION['voucher']; // Lấy thông tin voucher từ session
        
            if ($voucher['type'] == 1) {
                // Giảm giá theo giá trị cụ thể
                $sub += $voucher['disValue'];
            } else if ($voucher['type'] == 2) {
                // Giảm giá theo phần trăm
                $sub += ($Subtotal * $voucher['disValue'] / 100);
            }
        }
        
        $total = $Subtotal - $sub;
        $createDate = date('Y-m-d');

        // Insert bill
        $is1 = "INSERT INTO `bill`(`CustomerID`,`CreateTime`,`Subtotal`, `Total`,`Address`,`payment`, `status`) VALUES (?,?,?,?,?,?,?)";
        $vl1 = [$_SESSION['user']['uId'], $createDate, $Subtotal, $total, $data['delivery'], $data['pttt'], "Đã Đặt"];

        if (insert($is1, $vl1, 'isiisss')) {
            $bill_id = mysqli_insert_id($con);
            $SalesQuantity = 0;

            foreach ($billDetails as $billDetail) {
                //chèn billdetail
                $is2 = "INSERT INTO `billdetail`(`BillID`, `ProductID`, `Quantity`, `Unitprice`) VALUES (?,?,?,?)";
                $vl2 = [$bill_id, $billDetail['ProductID'], $billDetail['Quantity'], $billDetail['UnitPrice']];
                insert($is2, $vl2, 'iiid');
                $SalesQuantity += $billDetail['Quantity'];

                //update quantity product
                $is3 = "UPDATE `product` SET `Quantity`=`Quantity`-? WHERE `ProductID`=?";
                $vl3 = [$billDetail['Quantity'], $billDetail['ProductID']];
                update($is3, $vl3, 'ii');
            }

            // Handle voucher usage
            if (!empty($_SESSION['voucher']) && is_array($_SESSION['voucher'])) {
                $voucher = $_SESSION['voucher']; // Lấy voucher từ session

                if ($voucher['ULimit'] === $voucher['UCount']) {
                    // Voucher đã đạt giới hạn sử dụng, cập nhật trạng thái thành 1 (không hợp lệ)
                    update("UPDATE `voucher` SET `UsageCount`=`UsageCount` + 1, `Status`=1 WHERE `VoucherID`=?", [$voucher['id']], 'i');
                } else {
                    // Tăng số lần sử dụng voucher
                    update("UPDATE `voucher` SET `UsageCount`=`UsageCount` + 1 WHERE `VoucherID`=?", [$voucher['id']], 'i');
                }

                $is4 = "INSERT INTO `voucherdetail`(`VoucherID`, `UserID`, `BillID`) VALUES (?,?,?)";
                $vl4 = [$voucher['id'], $_SESSION['user']['uId'], $bill_id];
                insert($is4, $vl4, 'iis');

                unset($_SESSION['voucher']); // Xóa voucher khỏi session sau khi xử lý
            }

            unset($_SESSION['cart']);
            echo 1;
        } else {
            echo 'Server Down!';
        }
    } else {
        echo 'Server Down!';
    }
}


if(isset($_POST['bill_id']))
{
    $updateDate = date('Y-m-d');
    if(update("UPDATE `bill` set `UpdateTime`=?, `status`=? WHERE `BillID`=?",[$updateDate,'Đã Hủy',$_POST['bill_id']],'ssi')){
        echo 1;
    } else {
        echo 0;
    }
}
