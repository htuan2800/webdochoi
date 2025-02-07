<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
if (isset($_POST['select_s'])) {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `supplierdetail` WHERE SupplierID=?", [$frm_data['supplier_id']], 'i');
    $data = "";
    while ($row = mysqli_fetch_assoc($res)) {
        $res0 = select("SELECT ProductID, ProductName,ProductPrice FROM `product`  WHERE ProductID=?", [$row['ProductID']], 'i');
        while ($row0 = mysqli_fetch_assoc($res0)) {
            $data .= "
                        <tr class='align-middle'>
                            <td><input type='checkbox' id='product$row0[ProductID]' name='product$row0[ProductID]' value='$row0[ProductID]'></td>
                            <td>$row0[ProductID]</td>
                            <td>$row0[ProductName]</td>
                            <td><input type='number' name='p$row0[ProductID]'></td>
                            <td><input type='number' name='q$row0[ProductID]'></td>
                        </tr>
                    ";
        }
    }
    echo $data;
}

if (isset($_POST['edit_import'])) {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `importdetail` WHERE ImportID=?", [$frm_data['edit_import']], 'i');
    $data = "";
    while ($row = mysqli_fetch_assoc($res)) {
        $res0 = select("SELECT ProductID, ProductName,ProductPrice FROM `product`  WHERE ProductID=?", [$row['ProductID']], 'i');
        while ($row0 = mysqli_fetch_assoc($res0)) {
            $data .= "
                        <tr class='align-middle'>
                            <td>$row0[ProductID]</td>
                            <td>$row0[ProductName]</td>
                            <td><input type='number' name='p$row0[ProductID]' value='$row[Unitprice]'></td>
                            <td><input type='number' name='q$row0[ProductID]' value='$row[Quantity]'></td>
                        </tr>
                    ";
        }
    }
    echo $data;
}

if (isset($_POST['add_import'])) {
    $products = json_decode($_POST['products'], true);
    $frm_data = filteration($_POST);

    $createTime = date('Y-m-d');

    // Insert vào bảng import
    if (insert("INSERT INTO `import`(`SupplierID`, `CreateTime`, `Total`) VALUES (?,?,?)", [$frm_data['sid'], $createTime, $frm_data['total']], 'isd')) {
        $import_id = mysqli_insert_id($con); // Lấy ID của import vừa thêm vào
        $all_success = true;

        // Insert vào bảng importdetail
        foreach ($products as $product) {
            $productID = $product['productID'];
            $unitPrice = $product['unitPrice'];
            $quantity = $product['quantity'];

            if (!insert("INSERT INTO `importdetail`(`ImportID`, `ProductID`, `Quantity`, `Unitprice`) VALUES (?,?,?,?)", [$import_id, $productID, $quantity, $unitPrice], 'iiii')) {
                $all_success = false;
                break;
            }
        }

        if ($all_success) {
            echo 1; // Thành công
        } else {
            echo 0; // Có lỗi xảy ra trong khi thêm dữ liệu vào importdetail
        }
    } else {
        echo 0; // Có lỗi xảy ra trong khi thêm dữ liệu vào import
    }
}


if (isset($_POST['update_import'])) {
    $products = json_decode($_POST['products'], true);
    $frm_data = filteration($_POST);
    $updateTime = date('Y-m-d');

    // Update bảng import 
    $import_result = update(
        "UPDATE `import` SET `Total`=?, `UpdateTime`=? WHERE `ImportID`=?",
        [$frm_data['total'], $updateTime, $frm_data['importID']],
        'isi'
    );
    
    // Kiểm tra số dòng affected >= 0 thay vì chỉ kiểm tra true/false
    if ($import_result >= 0) {
        $all_success = true;
        
        foreach ($products as $product) {
            $productID = $product['productID'];
            $unitPrice = $product['unitPrice'];
            $quantity = $product['quantity'];
            
            $detail_result = update(
                "UPDATE `importdetail` SET `Quantity`=?, `Unitprice`=? WHERE `ImportID`=? AND `ProductID`=?",
                [$quantity, $unitPrice, $frm_data['importID'], $productID],
                'iiii'
            );
            
            // Kiểm tra số dòng affected >= 0
            if ($detail_result < 0) {
                $all_success = false;
                break;
            }
        }
        
        if ($all_success) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}


// import.php
if (isset($_POST['get_all_import'])) {
    $res = selectAll('import');
    $data = array();
    
    while ($row = mysqli_fetch_assoc($res)) {
        $row['Total'] = (float)$row['Total']; // Đảm bảo Total là số
        $data[] = array(
            'ImportID' => $row['ImportID'],
            'SupplierID' => $row['SupplierID'],
            'CreateTime' => $row['CreateTime'],
            'UpdateTime' => $row['UpdateTime'],
            'Total' => $row['Total'],
            'Status' => $row['Status']
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}

if (isset($_POST['toggle_status'])) {
    $updateDate = date('Y-m-d');
    $frm_data = filteration($_POST);
    
    // Update trạng thái import
    $q = "UPDATE `import` SET `UpdateTime`=?, `status`=? WHERE `ImportID`=?";
    $v = [$updateDate, $frm_data['value'], $frm_data['toggle_status']];
    $statusImport = update($q, $v, 'ssi');
    
    if ($statusImport >= 0) {
        // Nếu chuyển sang trạng thái active (1)
        if ($frm_data['value'] == 1) {
            $all_success = true; // Khởi tạo biến kiểm tra
            
            // Lấy tất cả import detail
            $res0 = select("SELECT * FROM `importdetail` WHERE `ImportID`=?", 
                          [$frm_data['toggle_status']], 'i');
            
            while ($row = mysqli_fetch_assoc($res0)) {
                // Lấy thông tin sản phẩm
                $res1 = select("SELECT * FROM `product` WHERE `ProductID`=?", 
                              [$row['ProductID']], 'i');
                
                if (mysqli_num_rows($res1) > 0) {
                    $row0 = mysqli_fetch_assoc($res1);
                    $UPquantity = $row['Quantity'] + $row0['Quantity'];
                    
                    // Update số lượng sản phẩm
                    $product_import = update("UPDATE `product` SET `Quantity`=? WHERE `ProductID`=?", 
                                          [$UPquantity, $row['ProductID']], 'ii');
                    
                    if ($product_import < 0) {
                        $all_success = false;
                        break;
                    }
                } else {
                    $all_success = false;
                    break;
                }
            }
            
            // Trả về kết quả dựa trên biến all_success
            echo $all_success ? 1 : 0;
        } else {
            echo 2; // Trường hợp chuyển sang inactive
        }
    } else {
        echo 0; // Lỗi khi update import
    }
}

if (isset($_POST['import_details'])) {
    $frm_data = filteration($_POST);
    $res=select("SELECT product.ProductID, product.ProductName,importdetail.Quantity,importdetail.Unitprice FROM `import` INNER JOIN `importdetail` ON import.ImportID = importdetail.ImportID 
    INNER JOIN `product` ON product.ProductID=importdetail.ProductID WHERE import.ImportID=?",[$frm_data['import_details']],'i');
    $data="";
    while($row=$res->fetch_assoc()){
        $data .= "
        <tr class='align-middle'>
            <td>$row[ProductID]</td>
            <td>$row[ProductName]</td>
            <td>$row[Quantity]</td>
            <td>$row[Unitprice]</td>
        </tr>
    ";
    }

    echo $data;
}

