<?php

require('../inc/db_config.php');
require('../inc/essentials.php');

adminLogin();
if (isset($_POST['add_product'])) {
    $frm_data = filteration($_POST);
    $flag = 0;
    //print_r($frm_data);
    $createAt = date('Y-m-d');
    $q1 = "INSERT INTO `product`(`ProductName`, `ProductPrice`, `TypeID`, `BrandID`, `Description`, `Age`, `Origin`, `Gender`, `IMG`, `create_at`) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $values = [$frm_data['productname'], $frm_data['productprice'], $frm_data['category'], $frm_data['brand'], $frm_data['desc'], $frm_data['age'], $frm_data['origin'], $frm_data['gender'], null, $createAt];
    if (insert($q1, $values, 'sissssssss')) {
        $flag = 1;
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_all_products'])) {
    $res = selectAll('product');
    $data = array();

    while ($row = mysqli_fetch_assoc($res)) {
        $row['ProductPrice'] = (float)$row['ProductPrice']; // Đảm bảo Price là số
        $row['Quantity'] = (int)$row['Quantity']; // Đảm bảo Quantity là số
        $data[] = array(
            'ProductID' => $row['ProductID'],
            'TypeID' => $row['TypeID'],
            'ProductName' => $row['ProductName'],
            'Quantity' => $row['Quantity'],
            'ProductPrice' => $row['ProductPrice'],
            'BrandID' => $row['BrandID'],
            'Description' => $row['Description'],
            'status' => $row['status']
        );
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}

if (isset($_POST['get_product'])) {
    $frm_data = filteration($_POST);
    $res1 = select("SELECT * FROM `product` WHERE `ProductID`=?", [$frm_data['get_product']], 'i');
    $ProductID = "";
    $ProductName = "";
    $ProductPrice = "";
    $Age = "";
    $Origin = "";
    $Gender = "";
    $Description = "";
    $categories = [];
    $brand = [];
    if (mysqli_num_rows($res1) > 0) {
        while ($row = mysqli_fetch_assoc($res1)) {
            $ProductID = $row['ProductID'];
            $ProductName = $row['ProductName'];
            $ProductPrice = $row['ProductPrice'];
            $Age = $row['Age'];
            $Origin = $row['Origin'];
            $Gender = $row['Gender'];
            $Description = $row['Description'];

            array_push($categories, $row['TypeID']);
            array_push($brand, $row['BrandID']);
        }
    }

    $data = array('productid' => $ProductID, 'productname' => $ProductName, 'productprice' => $ProductPrice, 'age' => $Age, 'origin' => $Origin, 'gender' => $Gender, 'description' => $Description, 'categories' => $categories, 'brand' => $brand);
    echo json_encode($data);
}

if (isset($_POST['edit_product'])) {
    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `product` SET `TypeID`=?,`ProductName`=?, `ProductPrice`=?, `BrandID`=?,`Age`=?, `Origin`=?, `Gender`=?,`Description`=? WHERE `ProductID`=?";
    $values = [$frm_data['categories'], $frm_data['productname'], $frm_data['productprice'], $frm_data['brand'], $frm_data['age'], $frm_data['origin'], $frm_data['gender'], $frm_data['desc'], $frm_data['product_id']];

    if (update($q1, $values, 'isiissssi')) {
        $flag = 1;
    }
    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `product` SET `status`=? WHERE `ProductID`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (update($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['add_image'])) {
    $frm_data = filteration($_POST);
    $img_r = $_FILES['image'];

    // Kiểm tra file upload có phải là ảnh không
    $info = getimagesize($img_r["tmp_name"]);
    if (!$info) {
        echo 'upd_failed';
        exit;
    }

    // Tạo tên file mới 
    $ext = pathinfo($img_r['name'], PATHINFO_EXTENSION);
    $new_filename = 'PROD_' . time() . '.' . $ext;

    // Di chuyển từ ajax lên thư mục admin (cha của ajax)
    $upload_dir = dirname(__DIR__) . '/images/products/';

    // Tạo thư mục nếu chưa tồn tại
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $path = $upload_dir . $new_filename;

    // Xử lý ảnh cũ
    $old_img = null;
    $q = "SELECT `IMG` FROM `product` WHERE `ProductID`='$frm_data[product_id]'";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($row['IMG'] != null) {  // Kiểm tra rõ ràng
            $old_img = dirname(__DIR__) . '/' . $row['IMG'];
        }
    }

    // Xóa file ảnh cũ nếu tồn tại
    if ($old_img && file_exists($old_img)) {
        unlink($old_img);
    }
    // Upload file
    if (move_uploaded_file($img_r['tmp_name'], $path)) {
        // Lưu đường dẫn tương đối vào database
        $img_path = 'images/products/' . $new_filename;
        $q = "UPDATE `product` SET `IMG`='$img_path' WHERE `ProductID`='$frm_data[product_id]'";
        $res = mysqli_query($con, $q);
        if ($res) {
            echo 'upd_success';
        } else {
            echo 'upd_failed';
        }
    } else {
        echo 'upd_failed';
    }
}

if (isset($_POST['get_product_images'])) {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `product` WHERE `ProductID`=?", [$frm_data['get_product_images']], 'i');

    if ($row = mysqli_fetch_assoc($res)) {
        if ($row['IMG'] !== NULL) {
            echo <<<data
                <tr class='align-middle'>
                    <td><img src='{$row['IMG']}' class='img-fluid'></td>
                    <td>
                        <button onclick='rem_image($row[ProductID])' class='btn btn-danger shadow-none'>
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            data;
        } else {
            echo <<<data
                <tr class='align-middle'>
                    <td colspan="2" class="text-center">Chưa có hình ảnh!</td>
                </tr>
            data;
        }
    }
}


if (isset($_POST['rem_image'])) {
    $frm_data = filteration($_POST);
    $values = [$frm_data['product_id']];
    
    // Lấy thông tin ảnh cũ để xóa
    $pre_q = "SELECT * FROM `product` WHERE `ProductID`=?";
    $res = select($pre_q, $values, 'i');
    $img = mysqli_fetch_assoc($res);
    
    // Xóa file ảnh cũ nếu tồn tại
    if ($img['IMG']) {
        $img_path = dirname(__DIR__) . '/' . $img['IMG'];  // Chuyển thành đường dẫn tuyệt đối
        if(file_exists($img_path)) {
            unlink($img_path);
        }
    }
    
    // Cập nhật database
    $q = "UPDATE `product` SET `IMG` = NULL WHERE `ProductID`=?";
    $res = update($q, $values, 'i');
    echo $res;
}

if (isset($_POST['remove_product'])) {
    $frm_data = filteration($_POST);
    $res5 = update("UPDATE `product` SET `deleted`=? WHERE `ProductID`=?", [1, $frm_data['product_id']], 'ii');
    if ($res5) {
        echo 1;
    } else {
        echo 0;
    }
}
