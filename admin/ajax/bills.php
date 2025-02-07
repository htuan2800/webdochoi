<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
if (isset($_POST['get_all_bills'])) {
    $res = selectAll('bill');
    $data = array();
    
    while ($row = mysqli_fetch_assoc($res)) {
        $row['Total'] = (float)$row['Total']; // Đảm bảo Total là số
        $data[] = array(
            'BillID' => $row['BillID'],
            'CustomerID' => $row['CustomerID'],
            'CreateTime' => $row['CreateTime'],
            'UpdateTime' => $row['UpdateTime'],
            'Total' => $row['Total'],
            'Address' => $row['Address'],
            'payment' => $row['payment'],
            'status' => $row['status']
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}


if (isset($_POST['edit_bill'])) {
    $frm_data=filteration($_POST);
    $bdt="";
    $select="";
    $res0=select("SELECT * FROM `billdetail` WHERE `BillID`=?",[$frm_data['bill_id']],'i');
    while ($row=mysqli_fetch_assoc($res0)){
        $res1=select("SELECT ProductID, ProductName FROM `product` WHERE ProductID=? ",[$row['ProductID']],'i');
        if(mysqli_num_rows($res1)>0){
            $row1=mysqli_fetch_assoc($res1);
            $bdt.="
            <tr class='align-middle'>
                <td>$row[ProductID]</td>
                <td>$row1[ProductName]</td>
                <td>$row[Quantity]</td>
                <td>$row[Unitprice]</td>
            </tr>
        ";
    }
    }
    $res2=select("SELECT * FROM `bill` WHERE `BillID`=?",[$frm_data['bill_id']],'i');
    $row2=mysqli_fetch_assoc($res2);
    $total=$row2['Total'];
    if($row2['status']=='Đã Đặt'){
        $select.="
        <select name='status' id='status' onchange='change_status($row2[BillID])'>                            
            <option value=0>Chọn tình trạng đơn</option>
            <option value=1>Xác nhận đơn</option>
            <option value=2>Giao cho đơn vị vận chuyển</option>
            <option value=3>Đang giao hàng</option>
            <option value=4>Đã nhận hàng</option>
            <option value=5>Hủy Đơn</option>
        </select>
        " ;
    } else if($row2['status']=='Đã Xác Nhận') {
        $select.="
        <select name='status' id='status' onchange='change_status($row2[BillID])'>              
            <option value=0>Chọn tình trạng đơn</option>
            <option value=2>Giao cho đơn vị vận chuyển</option>
            <option value=3>Đang giao hàng</option>
            <option value=4>Đã nhận hàng</option>
            <option value=5>Hủy Đơn</option>
        </select>
        " ;
    } else if ($row2['status']=='Đã Lấy Hàng'){
        $select.="
        <select name='status' id='status' onchange='change_status($row2[BillID])'>                               
            <option value=0>Chọn tình trạng đơn</option>
            <option value=3>Đang giao hàng</option>
            <option value=4>Đã nhận hàng</option>
            <option value=5>Hủy Đơn</option>
        </select>
        " ;
    } else if ($row2['status']=='Đang Giao Hàng'){
        $select.="
        <select name='status' id='status' onchange='change_status($row2[BillID])'>                 
            <option value=0>Chọn tình trạng đơn</option>
            <option value=4>Đã nhận hàng</option>
            <option value=5>Hủy Đơn</option>
        </select>
        " ;
    }  else if ($row2['status']=='Đã Nhận Hàng'){
        $select.="
        <p>Đơn Hàng Đã Hoàn Thành</p>
        " ;
    } else if ($row2['status']=='Đã Hủy'){
        $select.="
        <p>Đơn Hàng Đã Hủy</p>
        " ;
    }
    $data=array('bdt'=>$bdt,'total'=>$total,'select'=>$select);
    echo json_encode($data);
}

if(isset($_POST['change_status'])){
    $frm_data=filteration($_POST);
    $updateDate = date('Y-m-d');
    $total=0;
    $quantity=0;
    if($frm_data['status']=='Đã Hủy')
    {
        $res1=select("SELECT * FROM `bill` WHERE `BillID`=?",[$frm_data['bill_id']],'i');
        if(mysqli_num_rows($res1)>0){
            $row1=mysqli_fetch_assoc($res1);
            $total=$row1['Total'];
            $dateTime = new DateTime($row1['CreateTime']);
            $formattedDate = $dateTime->format('Y-m-d');
        }

    }
    if(update("UPDATE `bill` set `UpdateTime`=?, `status`=? WHERE `BillID`=?",[$updateDate,$frm_data['status'],$frm_data['bill_id']],'ssi')){
        echo 1;
    } else {
        echo 0;
    }
    
}