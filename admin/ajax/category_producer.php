<?php

require('../inc/db_config.php');
require('../inc/essentials.php');

adminLogin();


if (isset($_POST['add_category'])) {
    $frm_data = filteration($_POST);
    $q = "INSERT INTO `category`(`TypeName`) VALUES (?)";
    $values = [$frm_data['name']];
    $res = insert($q, $values, 's');
    echo $res;
}

if (isset($_POST['get_category'])) {
    $res = selectAll('category');
    $i = 1;

    while ($row = mysqli_fetch_assoc($res)) {
        $btn = "<button type='button' onclick='edit_category($row[TypeID])' class='btn btn-primary btn-sm shadow-none' data-bs-toggle='modal' data-bs-target='#category-edit'>
                        Edit
                    </button>";
        if ($row['Status'] == 1) {
            $btn .= "<button type='button' onclick='toggle_category($row[TypeID],0)' class='btn btn-warning btn-sm shadow-none ms-1'>
                         Inactive
                    </button>";
        } else {
            $btn .= "<button type='button' onclick='toggle_category($row[TypeID],1)' class='btn btn-success btn-sm shadow-none ms-1'>
                         Active
                    </button>";
        }
        echo <<<data
            <tr>
                <td>$i</td>
                <td>$row[TypeName]</td>
                <td>
                    $btn
                </td>
            </tr>
        data;
        $i++;
    }
}

if (isset($_POST['toggle_category'])) {
    $frm_data = filteration($_POST);
    $values = [$frm_data['value'], $frm_data['toggle_category']];
    $q = "UPDATE `category` SET `Status`=? WHERE `TypeID`=?";
    $res = update($q, $values, 'ii');
    echo $res;
}

if (isset($_POST['update_category'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `category` SET `TypeName`=? WHERE `TypeID`=?";
    $values = [$frm_data['name'], $frm_data['id']];
    $res = update($q, $values, 'si');
    echo $res;
}


if (isset($_POST['add_brand'])) {
    $frm_data = filteration($_POST);
    $q = "INSERT INTO `brand`(`BrandName`) VALUES (?)";
    $values = [$frm_data['name']];
    $res = insert($q, $values, 's');
    echo $res;
}

if (isset($_POST['update_brand'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `brand` SET `BrandName`=? WHERE `BrandID`=?";
    $values = [$frm_data['name'], $frm_data['id']];
    $res = update($q, $values, 'si');
    echo $res;
}

if (isset($_POST['get_brand'])) {
    $res = selectAll('brand');
    $i = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $btn = "<button type='button' onclick='edit_brand($row[BrandID])' class='btn btn-primary btn-sm shadow-none' data-bs-toggle='modal' data-bs-target='#brand-edit'>
                        Edit
                    </button>";
        if ($row['Status'] == 1) {
            $btn .= "<button type='button' onclick='toggle_brand($row[BrandID],0)' class='btn btn-warning btn-sm shadow-none ms-1'>
                         Inactive
                    </button>";
        } else {
            $btn .= "<button type='button' onclick='toggle_brand($row[BrandID],1)' class='btn btn-success btn-sm shadow-none ms-1'>
                         Active
                    </button>";
        }
        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td>$row[BrandName]</td>
                <td>
                    $btn
                </td>
            </tr>
        data;
        $i++;
    }
}

if (isset($_POST['toggle_brand'])) {

    $frm_data = filteration($_POST);
    $values = [$frm_data['value'], $frm_data['toggle_brand']];
    $q = "UPDATE `brand` SET `Status`=? WHERE `BrandID`=?";
    $res = update($q, $values, 'ii');
    echo $res;
}
