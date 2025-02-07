<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
session_start();
if (isset($_POST['cart_count']) && $_POST['cart_count'] == 'true') {
    $totalQuantity = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalQuantity += $item['Quantity'];
        }
    }
    echo $totalQuantity;
}

if (isset($_POST['add_to_cart'])) {

    if (isset($_POST['add_to_cart'])) {
        $data = filteration($_POST);
        $product_id = $data['ProductID'];
        $product_name = $data['ProductName'];
        $product_inventory = $data['inventory'];
        $product_price = $data['ProductPrice'];
        $product_quantity = $data['Quantity'];

        $query = "SELECT * FROM `product` WHERE `ProductID`=?";
        $res = select($query, [$product_id], 'i');
        $row = mysqli_fetch_assoc($res);

        $formatted_img = '';
        if ($row['IMG'] == null) {
            $formatted_img = 'images/products/thumbnail.jpg';
        } else {
            $formatted_img = $row['IMG'];
        }

        // Khởi tạo giỏ hàng nếu chưa tồn tại
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng hay chưa
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['ProductID'] == $product_id) {
                if ($product_inventory >= $item['Quantity'] + $product_quantity) {
                    $item['Quantity'] += $product_quantity; // Tăng số lượng nếu đã tồn tại
                    $found = true;
                    break;
                } else {
                    echo json_encode(["success" => false, "message" => "Kho không đủ số lượng bạn yêu cầu"]);
                    exit;
                }
            }
        }

        // Nếu sản phẩm chưa tồn tại, thêm mới
        if (!$found) {
            $_SESSION['cart'][] = [
                'IMG' => $formatted_img,
                'ProductID' => $product_id,
                'ProductName' => $product_name,
                'ProductPrice' => $product_price,
                'Quantity' => $product_quantity
            ];
        }

        echo json_encode(["success" => true, "message" => "Sản phẩm đã được thêm vào giỏ hàng!"]);
    }
}

if (isset($_POST['get_product_cart'])) {
    $cartData = "";
    $thanhtoan = "";
    $voucher_html = "";
    $subtotal = 0;
    $total = 0;

    // Lấy giỏ hàng từ session
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $item) {
            // // Tạo ảnh và thông tin sản phẩm
            // $imgBase64 = base64_encode($item['IMG']);
            // $imgSrc = 'data:image/jpeg;base64,' . $imgBase64;
            $formattedPrice = number_format($item['ProductPrice'], 0, ',', '.');
            $cartData .= "
                <tr class='align-middle'>
                    <td><img src='./admin/$item[IMG]' class='img-fluid' style='width:60px;'></td>
                    <td>{$item['ProductName']}</td>
                    <td>
                        <div class='input-group input-group-sm' style='width: 130px;'>
                            <button class='btn btn-outline-secondary cart-qty-minus' 
                                    type='button' 
                                    onclick='setQuantityMinus({$item['ProductID']})'>
                                <i class='bi bi-dash'></i>
                            </button>
                            
                            <input type='number' 
                                class='form-control text-center' 
                                id='quantity{$item['ProductID']}' 
                                name='quantity'
                                disabled
                                min='0' 
                                max='{$item['Quantity']}' 
                                value='{$item['Quantity']}'
                                style='border-left: none; border-right: none;'>
                            
                            <button class='btn btn-outline-secondary cart-qty-plus' 
                                    type='button'
                                    onclick='setQuantityPlus({$item['ProductID']})'>
                                <i class='bi bi-plus'></i>
                            </button>
                        </div>
                    </td>
                    <td>{$formattedPrice} VNĐ</td>
                    <td>       
                        <button type='button' onclick='remove_product({$item['ProductID']})' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>
            ";
            $subtotal += $item['Quantity'] * $item['ProductPrice'];
        }
    }

    // Tính toán voucher và tổng tiền
    $sub = 0;
    if (isset($_SESSION['voucher'])) {
        $voucher = $_SESSION['voucher'];

        // Kiểm tra nếu subtotal nhỏ hơn giá trị tối thiểu của voucher
        if ($subtotal < $voucher['cart_value']) {
            unset($_SESSION['voucher']); // Xóa voucher khỏi session nếu không đủ điều kiện
        } else {
            $string = "";

            // Xử lý loại giảm giá
            if ($voucher['type'] == 1) {
                $sub += $voucher['disValue'];
                $string = "Giảm giá " . number_format($voucher['disValue'], 0, ',', '.') . "Đ";
            } else if ($voucher['type'] == 2) {
                $discount = $subtotal * $voucher['disValue'] / 100;
                $sub += $discount;
                $string = "Giảm giá " . $voucher['disValue'] . "%";
            }

            // Tạo HTML hiển thị thông tin voucher
            $voucher_html = sprintf(
                "<tr>
                    <td title='%s'>%s</td>
                    <td>%s</td>
                    <td>
                        <button type='button' onclick='remove_voucher_list(%d)' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>",
                htmlspecialchars($voucher['desc']),
                htmlspecialchars($voucher['code']),
                $string,
                $voucher['id']
            );
        }
    }


    $total = $subtotal - $sub;

    $formattedSubtotal = number_format($subtotal, 0, ',', '.');
    $formattedSub = number_format($sub, 0, ',', '.');
    $formattedTotal = number_format($total, 0, ',', '.');
    $thanhtoan .= "
    <tr>
        <td>SubTotal</td>
        <td>{$formattedSubtotal} VNĐ</td>
    </tr>
    <tr>
        <td>Discount</td>
        <td>{$formattedSub} VNĐ</td>
    </tr>
    <tr>
        <td>Total</td>
        <td>{$formattedTotal} VNĐ</td>
    </tr>
    ";

    // Gửi lại dữ liệu giỏ hàng và thông tin thanh toán dưới dạng JSON
    $response = array(
        'cartData' => $cartData,
        'thanhtoan' => $thanhtoan,
        'voucherList' => $voucher_html
    );
    echo json_encode($response);
}



if (isset($_POST['remove_product'])) {
    $data = filteration($_POST);

    // Tìm và xóa sản phẩm khỏi giỏ hàng trong session
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['ProductID'] == $data['product_id']) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Lập lại chỉ số giỏ hàng
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    if (count($_SESSION['cart']) == 0) {
        unset($_SESSION['cart']);
        if (isset($_SESSION['voucher'])) {
            unset($_SESSION['voucher']);
        }
    }

    echo 1; // Trả về 1 khi xóa thành công
}

if (isset($_POST['remove_voucher'])) {
    $data = filteration($_POST);
    $test = 0;
    if ($_SESSION['voucher']['id'] == $data['remove_voucher']) {
        unset($_SESSION['voucher']);
        $test = 1;
    } else {
        $test = 0;
    }
    echo $test;
}



if (isset($_POST['update_quantity'])) {
    $data = filteration($_POST);

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['ProductID'] == $data['product_id']) {
            $_SESSION['cart'][$key]['Quantity'] = $data['quantity'];
            break;
        }
    }

    echo 1; // Trả về 1 khi cập nhật thành công
}


if (isset($_POST['voucher'])) {
    $data = filteration($_POST);
    if (isset($_SESSION['voucher'])) {
        echo json_encode(["success" => false, "message" => "onecode"]);
    } elseif (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $totalPrice = array_reduce($_SESSION['cart'], function ($carry, $item) {
            return $carry + ($item['ProductPrice'] * $item['Quantity']);
        }, 0);
        $res = select("SELECT * FROM `voucher` WHERE `VoucherCode`=? AND `Status`=0 AND `deleted`=0", [$data['code']], 's');
        if (mysqli_num_rows($res) > 0) {
            $row = $res->fetch_assoc();

            if ($row['UsageCount'] === $row['UsageLimit']) {
                echo json_encode(["success" => false, "message" => "full"]);
            } else if ($totalPrice < $row['Cart_value']) {
                echo json_encode(["success" => false, "message" => "less", "price" => $row['Cart_value']]);
            } else if (isset($_SESSION['voucher'])) {
                echo json_encode(["success" => false, "message" => "entered"]); // Đã nhập mã trước đó
            } else {
                // Lưu voucher vào session
                $_SESSION['voucher'] = array(
                    'id' => $row['VoucherID'],
                    'code' => $row['VoucherCode'],
                    'type' => $row['VoucherType'],
                    'desc' => $row['Description'],
                    'cart_value' => $row['Cart_value'],
                    'disValue' => $row['DiscountValue'],
                    'ULimit' => $row['UsageLimit'],
                    'UCount' => $row['UsageCount'] + 1
                );

                echo json_encode(["success" => true]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "wrong_code"]); // Sai mã voucher
        }
    } else {
        echo json_encode(["success" => false, "message" => "nocart"]); // Không có sản phẩm trong giỏ hàng
    }
}
