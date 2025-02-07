<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
if (isset($_POST['get_all_supplier'])) {
    $res = selectAll('supplier');
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['status'] == 1) {
            $status = "<button onclick='toggle_status($row[SupplierID],0)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
        } else {
            $status = "<button onclick='toggle_status($row[SupplierID],1)' class='btn btn-dark btn-sm shadow-none'>active</button>";
        }
        $data .= "
                    <tr class='align-middle'>
                        <td>$row[SupplierID]</td>
                        <td>$row[SupplierName]</td>
                        <td>$row[Address]</td>
                        <td>$row[Phone]</td>
                        <td>$row[FaxNumber]</td>
                        <td>$status</td>
                        <td>
                            <button type='button' onclick='remove_supplier($row[SupplierID])' class='btn btn-danger shadow-none btn-sm'>
                                <i class='bi bi-trash'></i>
                            </button>
                            <button type='button' onclick='edit_supplier($row[SupplierID])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-supplier'>
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button type='button' onclick='detail_supplier($row[SupplierID])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#detail-supplier'>
                            <i class='bi bi-plus-circle-fill'></i>
                            </button>
                        </td>
                    </tr>
                ";
    }
    echo $data;
}

if (isset($_POST['add_supplier'])) {
    $frm_data = filteration($_POST);
    if (insert("INSERT INTO `supplier` (`SupplierName`, `Address`, `Phone`, `FaxNumber`) VALUES (?,?,?,?)", [$frm_data['suppliername'], $frm_data['address'], $frm_data['phone'], $frm_data['faxnumber']], 'ssss')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_supplier'])) {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * from supplier where SupplierID=?", [$frm_data['supplier_id']], 's');
    $SupplierID = "";
    $suppliername = "";
    $address = "";
    $phone = "";
    $faxnumber = "";
    if (mysqli_num_rows($res1) > 0) {
        while ($row = mysqli_fetch_assoc($res1)) {
            $SupplierID = $row['SupplierID'];
            $suppliername = $row['SupplierName'];
            $address = $row['Address'];
            $phone = $row['Phone'];
            $faxnumber = $row['FaxNumber'];
        }
    }

    $data = array('supplierID' => $SupplierID, 'suppliername' => $suppliername, 'address' => $address, 'phone' => $phone, 'faxnumber' => $faxnumber);
    echo json_encode($data);
}

if (isset($_POST['edit_supplier'])) {
    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `supplier` SET `SupplierName`=?, `Address`=?, `Phone`=?, `FaxNumber`=? WHERE `SupplierID`=?";
    $values = [$frm_data['suppliername'], $frm_data['address'], $frm_data['phone'], $frm_data['faxnumber'], $frm_data['supplierID']];

    if (update($q1, $values, 'ssssi')) {
        $flag = 1;
    }
    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}



if (isset($_POST['sdt_edit'])) {
    $frm_data = filteration($_POST);
    $sdt = "";
    $select = "";
    $res0 = selectAll('product');
    $p=array();
    while ($row = mysqli_fetch_assoc($res0)) {

        $sdt .= "
            <tr class='align-middle'>
                <td><input type='checkbox' onchange='add_product($frm_data[supplier_id],$row[ProductID])' id='$row[ProductID]' name='$row[ProductID]' value='$row[ProductID]'></td>
                <td>$row[ProductID]</td>
                <td>$row[ProductName]</td>
            </tr>
        ";
    }
    $res1=select("SELECT * from `supplierdetail` where `SupplierID`=?",[$frm_data['supplier_id']],'i');
    while($row=mysqli_fetch_assoc($res1)){
        array_push($p,$row['ProductID']);
    }

    $data = array('sdt' => $sdt,'p'=>$p);
    echo json_encode($data);
}

if(isset($_POST['add_p'])){
    $frm_data = filteration($_POST);    
    if(insert("INSERT INTO `supplierdetail`(`SupplierID`, `ProductID`) VALUES (?,?)",[$frm_data['supplier_id'],$frm_data['product_id']],'ii')){
        echo 1;
    } else {
        echo 0;
    }
}

if(isset($_POST['delete_p'])){
    $frm_data = filteration($_POST);    
    if(delete("DELETE FROM `supplierdetail` WHERE `SupplierID`=? and `ProductID`=?",[$frm_data['supplier_id'],$frm_data['product_id']],'ii')){
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `supplier` SET `status`=? WHERE `SupplierID`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (update($q, $v, 'si')) {
        echo 1;
    } else {
        echo 0;
    }
}