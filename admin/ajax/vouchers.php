<?php

require('../inc/db_config.php');
require('../inc/essentials.php');

adminLogin();
if (isset($_POST['add_voucher'])) {
    $frm_data = filteration($_POST);
    $flag = 0;
    //print_r($frm_data);
    $q1 = "INSERT INTO `voucher`(`VoucherCode`, `VoucherType`, `Description`, `Cart_value`, `DiscountValue`, `UsageLimit`) VALUES (?,?,?,?,?,?)";
    $values = [$frm_data['vouchername'], $frm_data['voucherType'], $frm_data['description'], $frm_data['cartvalue'], $frm_data['discountValue'], $frm_data['UsageLimit']];
    if (insert($q1, $values, 'sisidi')) {
        $flag = 1;
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_all_vouchers'])) {
    $res = selectAll('voucher');
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {
        $type = "";
        $value = "";
        $status = "";
        $deleted = "";
        $btn="";
        if ($row['VoucherType'] == 1) {
            $type = "Giảm theo giá tiền";
            $value =number_format($row['DiscountValue'], 0, ',', '.') . ' VNĐ';
        } else if ($row['VoucherType'] == 2) {
            $type = "Giảm theo %";
            $value = $row['DiscountValue'] . "%";
        }
        if ($row['Status'] == 1) {
            $status = "Hết lượt sử dụng";
        } else if ($row['Status'] == 0) {
            $status = "Chưa hết lượt sử dụng";
        }

        if($row['deleted']==0)
        {
            $deleted = "Còn hoạt động";
            $btn="
                    <button type='button' onclick='voucher_details($row[VoucherID])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#detail-voucher'>
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' onclick='remove_voucher($row[VoucherID])' class='btn btn-danger shadow-none btn-sm'>
                        <i class='bi bi-trash'></i>
                    </button>
            ";
        } else {
            $deleted = "Mã này không còn được sử dụng";
            $btn.=" <button type='button' onclick='voucher_details($row[VoucherID])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#detail-voucher'>
                        <i class='bi bi-pencil-square'></i>
                    </button>";
        }
        $formattedCartValue = number_format($row['Cart_value'], 0, ',', '.') . ' VNĐ';
        $data .= "
                <tr class='align-middle'>
                    <td>$row[VoucherID]</td>
                    <td>$row[VoucherCode]</td>
                    <td>$type</td>
                    <td>$row[Description]</td>
                    <td>$formattedCartValue</td>
                    <td>$value</td>
                    <td>$row[UsageLimit]</td>
                    <td>$row[UsageCount]</td>
                    <td>$status</td>
                    <td>$deleted</td>
                    <td>
                        $btn
                    </td>
                </tr>
            ";
    }
    echo $data;
}

if(isset($_POST['voucher_details'])){
    $frm_data = filteration($_POST);
    $res=select("SELECT * FROM `voucherdetail` WHERE `VoucherID`=?",[$frm_data['voucher_details']],'i');
    $data="";
    $i=1;
    while($row=$res->fetch_assoc()){
        $data .= "
        <tr class='align-middle'>
            <td>$i</td>
            <td>$row[UserID]</td>
            <td>$row[BillID]</td>
        </tr>
    ";
    $i++;
    }

    echo $data;
}


if(isset($_POST['remove_voucher'])){
    $frm_data = filteration($_POST);
    $res=update("UPDATE `voucher` SET `deleted`=1 WHERE `VoucherID`=?",[$frm_data['remove_voucher']],'i');
    echo $res;  
}