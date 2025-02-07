<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
session_start();
$settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
$values = [1];
if (isset($_POST['checkFilter'])) {
    $data = filteration($_POST);
    $conditions = ["p.status = ?", "p.deleted = ?"];
    $params = ['0', '0'];
    $types = 'ss';

    if ($data['brandValue'] != 'Brand') {
        $conditions[] = "p.BrandID = ?";
        $params[] = $data['brandValue'];
        $types .= 'i';
    }
    if ($data['categoryValue'] != 'Category') {
        $conditions[] = "p.TypeID = ?";
        $params[] = $data['categoryValue'];
        $types .= 'i';
    }
    if ($data['minPrice'] !== '' && $data['maxPrice'] !== '') {
        $conditions[] = "p.ProductPrice BETWEEN ? AND ?";
        $params[] = $data['minPrice'];
        $params[] = $data['maxPrice'];
        $types .= 'ii';
    }

    $whereClause = implode(' AND ', $conditions);
    $product_res = select(
        "SELECT p.*, c.TypeName, c.Status as StatusCategory, b.BrandName, b.Status as StatusBrand 
     FROM `product` p
     LEFT JOIN `category` c ON p.TypeID = c.TypeID
     LEFT JOIN `brand` b ON p.BrandID = b.BrandID
     WHERE $whereClause 
     ORDER BY p.ProductID",
        $params,
        $types
    );

    $products = [];
    while ($product_data = $product_res->fetch_assoc()) {
        $formatted_img = '';
        if ($product_data['IMG'] == null) {
            $formatted_img = 'images/products/thumbnail.jpg';
        } else {
            $formatted_img = $product_data['IMG'];
        }
        $products[] = [
            'ProductID'    => $product_data['ProductID'],
            'ProductName'  => $product_data['ProductName'],
            'ProductPrice' => $product_data['ProductPrice'],
            'IMG'          => $formatted_img,
            'Category'     => $product_data['StatusCategory'] == 0 ? $product_data['TypeName'] : 'Tạm ẩn',
            'Brand'        => $product_data['StatusBrand'] == 0 ? $product_data['BrandName'] : 'Tạm ẩn',
            'Age'          => $product_data['Age'],
            'Description'  => $product_data['Description']
        ];
    }
    echo json_encode($products);
}

if (isset($_POST['checkSearch'])) {
    $data = filteration($_POST);
    $select1 = "SELECT f.* FROM `product` f INNER JOIN `brand` rfea ON f.BrandID=rfea.BrandID WHERE (f.ProductName LIKE ? OR rfea.BrandName LIKE ?)  and f.status=? and f.deleted=?";
    $param = $data['text'] . '%';
    $res1 = select($select1, ["%" . $param . "%", $param, '0', '0'], 'ssss');
    // $result=[];
    while ($row1 = mysqli_fetch_assoc($res1)) {
        $formatted_img = '';
        if ($row1['IMG'] == null) {
            $formatted_img = 'images/products/thumbnail.jpg';
        } else {
            $formatted_img = $row1['IMG'];
        }
        $formatted_price = number_format($row1['ProductPrice'], 0, ',', '.') . ' VNĐ';
        echo <<<data
            <div class='autocomplete-suggestion'>
                <div class='search-item' onclick="location.href='/shoes1/product_details.php?id={$row1['ProductID']}'">
                    <div class='img'><img src='./admin/$formatted_img' alt='{$row1['ProductName']}'>
                    </div>   
                    <div class='info'>       
                    <h2><a href='/shoes1/product_details.php?id={$row1['ProductID']}'>{$row1['ProductName']}</a></h2>       
                    <h3><strike></strike> {$formatted_price}</h3>   
                    </div>
                </div>
            </div>
        data;
    }
}

if (isset($_POST['action'])) {
    $product_res = select("SELECT p.*, c.TypeName, c.Status as StatusCategory, b.BrandName, b.Status as StatusBrand 
                           FROM `product` p
                           LEFT JOIN `category` c ON p.TypeID = c.TypeID
                           LEFT JOIN `brand` b ON p.BrandID = b.BrandID
                           WHERE p.status=? AND p.deleted=? ORDER BY p.ProductID", ['0', '0'], 'ss');

    $products = [];
    while ($product_data = $product_res->fetch_assoc()) {
        $formatted_img = '';
        if ($product_data['IMG'] == null) {
            $formatted_img = 'images/products/thumbnail.jpg';
        } else {
            $formatted_img = $product_data['IMG'];
        }
        //$imgBase64 = base64_encode($formatted_img);
        $products[] = [
            'ProductID'    => $product_data['ProductID'],
            'ProductName'  => $product_data['ProductName'],
            'ProductPrice' => $product_data['ProductPrice'],
            'IMG'          => $formatted_img,
            'Category'     => $product_data['StatusCategory'] == 0 ? $product_data['TypeName'] : 'Tạm ẩn',  // Trực tiếp lấy từ JOIN
            'Brand'        => $product_data['StatusBrand'] == 0 ? $product_data['BrandName'] : 'Tạm ẩn', // Trực tiếp lấy từ JOIN
            'Age'          => $product_data['Age'],
            'Description'  => $product_data['Description']
        ];
    }

    echo json_encode($products);
}
