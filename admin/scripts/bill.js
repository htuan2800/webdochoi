function get_all_bills() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/bills.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status == 200) {
            const data = JSON.parse(this.responseText);
            let html = '';
            
            data.forEach((row, index) => {
                html += `
                    <tr class='align-middle'>
                        <td>${index + 1}</td>
                        <td>${row.BillID}</td>
                        <td>${row.CustomerID}</td>
                        <td>${row.CreateTime}</td>
                        <td>${row.UpdateTime}</td>
                        <td>${row.Total.toLocaleString('vi-VN')} đ</td>
                        <td>${row.Address}</td>
                        <td>${row.payment}</td>
                        <td>${row.status}</td>
                        <td>
                            <button type='button' onclick='edit_bill(${row.BillID})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-bill'>
                                <i class='bi bi-pencil-square'></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            document.getElementById('bill-data').innerHTML = html;
        }
    }
    
    xhr.send('get_all_bills');
}
function remove_bill(bid) {
    console.log(bid);
}

function edit_bill(bid) {
    $.ajax(
        {
            url: 'ajax/bills.php',
            type: 'POST',
            data: {
                edit_bill: '',
                bill_id: bid
            },
            success: function (response) {
                let data = JSON.parse(response);
                $('#billdt-data').html(data.bdt);
                $('#total').html(data.total);
                $('#select_status').html(data.select);
            },
            error: function () {
                console.log('Error: Could not fetch bill details.');
            }
        }
    )
}

function getVpttt(value) {
    switch (value) {
        case '1':
            return 'Đã Xác Nhận';
        case '2':
            return 'Đã Lấy Hàng';
        case '3':
            return 'Đang Giao Hàng';
        case '4':
            return 'Đã Nhận Hàng';
        case '5':
            return 'Đã Hủy';
        default:
            return '';
    }
}
function change_status(billid) {
    if (document.getElementById('status').value != 0) {
        let statusText = getVpttt(document.getElementById('status').value);
        $.ajax({
            url:'ajax/bills.php',
            type:'POST',
            data:{
                change_status:'',
                bill_id:billid,
                status:statusText
            },
            success: function (response) {
                if(response==1){
                    toast('success','Cập nhật trạng thái thành công!');
                    edit_bill(billid);
                    get_all_bills();
                }
            },
            error: function () {
                console.log('Error: Could not fetch bill details.');
            }
        })
    }
}

window.onload = function () {
    get_all_bills();
}


function statusBill(billID) {

}