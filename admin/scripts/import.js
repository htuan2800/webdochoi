function selectStatus(value) {
    const tbody = document.getElementById("import-data");
    const rows = tbody.getElementsByTagName("tr");

    // Nếu chọn mục mặc định (--Tình trạng--)
    if (!value || value === "---Tình trạng----") {
        Array.from(rows).forEach(row => {
            row.style.display = "";
        });
        return;
    }

    Array.from(rows).forEach(row => {
        const statusCell = row.getElementsByTagName("td")[5]; // Cột status
        const statusButton = statusCell.querySelector("button");

        // Kiểm tra trạng thái
        const isActive = statusButton.classList.contains("btn-dark");

        // value="0" là "Đang hoạt động" (btn-dark)
        // value="1" là "Đã ẩn" (btn-warning)
        if ((value === "0" && isActive) || (value === "1" && !isActive)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}
function select_s() {
    var sid = document.getElementById('suppliers').value;
    $.ajax(
        {
            url: 'ajax/import.php',
            type: 'POST',
            data: {
                select_s: '',
                supplier_id: sid
            },
            success: function (response) {
                $('#importdt-data').html(response);
            },
            error: function () {
                console.log('Error: Could not fetch bill details.');
            }
        }
    )
}

function get_all_import() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/import.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status == 200) {
            const data = JSON.parse(this.responseText);
            let html = '';
            
            data.forEach(row => {
                let status = '';
                let btn = '';
                
                // Xử lý status
                if (row.Status == 0) {
                    status = "Chờ xác nhận";
                    btn = `
                        <button type='button' onclick='toggle_status(${row.ImportID},1)' class='btn btn-success shadow-none btn-sm'>
                            <i class='bi bi-check2'></i>
                        </button>
                        <button type='button' onclick='toggle_status(${row.ImportID},2)' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-exclamation-lg'></i>
                        </button>
                        <button type='button' onclick='edit_import(${row.ImportID})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-import'>
                            <i class='bi bi-pencil-square'></i>
                        </button>
                    `;
                } else if (row.Status == 1) {
                    status = "Đã Duyệt Đơn";
                    btn = `
                        <button type='button' onclick='import_details(${row.ImportID})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#detail-import'>
                            <i class='bi bi-eye-fill'></i>
                        </button>
                    `;
                } else {
                    status = "Hủy Đơn";
                    btn = `
                        <button type='button' onclick='import_details(${row.ImportID})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#detail-import'>
                            <i class='bi bi-eye-fill'></i>
                        </button>
                    `;
                }
                
                // Thêm nút in cho mọi trạng thái
                btn += `
                    <button type='button' onclick='import_print(${row.ImportID})' class='btn btn-primary shadow-none btn-sm'>
                        <i class='bi bi-printer'></i>
                    </button>
                `;

                html += `
                    <tr class='align-middle'>
                        <td>${row.ImportID}</td>
                        <td>${row.SupplierID}</td>
                        <td>${row.CreateTime}</td>
                        <td>${row.UpdateTime}</td>
                        <td>${row.Total.toLocaleString('vi-VN')} đ</td>
                        <td>${status}</td>
                        <td>${btn}</td>
                    </tr>
                `;
            });
            
            document.getElementById('import-data').innerHTML = html;
        }
    }
    
    xhr.send('get_all_import');
}

function toggle_status(id, val) {

    if (val == 1 || val==2) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/import.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (this.responseText == 1) {
                console.log('approved');
                toast('success', 'Approved!');
                get_all_import();
            }
            else if (this.responseText == 2) {
                console.log('canceled');
                toast('success', 'Cancelled!');
                get_all_import();
            } else {
                toast('error', 'Server Down!');
            }
        }
        xhr.send('toggle_status=' + id + '&value=' + val);
    }
}

function import_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/import.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('importdetail-data').innerHTML = this.responseText;
    }
    xhr.send('import_details=' + id);
}

function edit_import(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/import.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('importID').value = id;
        document.getElementById('editimport-data').innerHTML = this.responseText;
    }
    xhr.send('edit_import=' + id);
}

document.getElementById('edit_import_form').addEventListener('submit', function (e) {
    e.preventDefault(); // Ngăn chặn form gửi đi

    let arr = [];
    let total = 0;
    let importID = document.getElementById('importID').value;
    
    // Lấy tất cả các rows từ tbody
    let rows = document.getElementById('editimport-data').getElementsByTagName('tr');
    
    // Lặp qua từng row để lấy dữ liệu
    for(let row of rows) {
        let cells = row.getElementsByTagName('td');
        let productID = cells[0].textContent; // Lấy ProductID từ cột đầu tiên
        
        // Lấy giá trị price và quantity từ input
        let price = cells[2].querySelector('input[type="number"]').value;
        let quantity = cells[3].querySelector('input[type="number"]').value;
        
        // Tạo object chứa thông tin sản phẩm
        let product = {
            productID: parseInt(productID),
            unitPrice: parseFloat(price),
            quantity: parseInt(quantity)
        };
        
        // Tính tổng tiền
        total += product.unitPrice * product.quantity;
        
        // Thêm vào mảng
        arr.push(product);
    }

    console.log(arr);
    console.log(total);
    let editForm = document.getElementById('edit_import_form');
    let formData = new FormData(editForm);
    formData.append('products', JSON.stringify(arr));
    formData.append('importID', importID);
    formData.append('total', total);
    formData.append('update_import', '');
    $.ajax({
        url: 'ajax/import.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var myModal = document.getElementById('edit-import');
            var modal = bootstrap.Modal.getInstance(myModal);
            if (response == 1) {
                toast('success', 'Sửa import thành công!');
                editForm.reset();  // Reset form
                modal.hide();
                get_all_import();
            } 
            else {
                toast('error', 'Server Down!');
            }
        },
        error: function () {
            console.log('Error: Could not process the request.');
        }
    });
});

function exportToPDF(importId) {
    // Tìm hàng được chọn dựa vào importId
    const tbody = document.getElementById("import-data");
    const rows = tbody.getElementsByTagName("tr");
    let selectedRow = null;

    // Tìm hàng chứa importId
    for (let row of rows) {
        const firstCell = row.cells[0]; // Cột ImportID
        if (firstCell.textContent === importId.toString()) {
            selectedRow = row;
            break;
        }
    }

    // Lấy dữ liệu từ hàng được chọn
    const data = {
        importId: selectedRow.cells[0].textContent,
        supplierId: selectedRow.cells[1].textContent,
        createTime: selectedRow.cells[2].textContent,
        updateTime: selectedRow.cells[3].textContent,
        total: selectedRow.cells[4].textContent,
        status: selectedRow.cells[5].textContent
    };


    // Tạo container mới
    const container = document.createElement('div');
    container.style.padding = '20px';

    // Thêm tiêu đề với tháng động
    const title = document.createElement('h2');
    title.innerHTML = `PHIẾU NHẬP KHO`;
    title.style.textAlign = 'center';
    title.style.marginBottom = '5px';
    container.appendChild(title);

    // Thêm thời gian báo cáo động
    const dateRange = document.createElement('p');
    dateRange.innerHTML = `Ngày nhập kho ${data.createTime} - Ngày cập nhật cuối cùng ${data.updateTime}`;
    dateRange.style.textAlign = 'center';
    dateRange.style.marginBottom = '20px';
    container.appendChild(dateRange);

    const maphieu = document.createElement('p');
    maphieu.innerHTML = `Mã phiếu: ${data.importId}`;
    maphieu.style.marginBottom = '10px';
    container.appendChild(maphieu);

    const total = document.createElement('p');
    total.innerHTML = `Tổng tiền: ${data.total.toLocaleString('vi-VN')} VNĐ`;
    total.style.marginBottom = '10px';
    container.appendChild(total);

    const status = document.createElement('p');
    status.innerHTML = `Trạng thái đơn nhập: ${data.status}`;
    status.style.marginBottom = '20px';
    container.appendChild(status);


    // Lấy dữ liệu từ bảng detail
    const table = document.getElementById("detailTable");

    // Sao chép bảng hiện tại
    const tableClone = table.cloneNode(true);
    container.appendChild(tableClone);

    // Thêm chữ ký với ngày hiện tại
    const currentDate = new Date();
    const signature = document.createElement('div');
    signature.innerHTML = `
        <p style="text-align: right; margin-top: 30px;">
            TP.HCM, ngày ${currentDate.getDate()} tháng ${currentDate.getMonth() + 1} năm ${currentDate.getFullYear()}<br><br>
            Người lập phiếu<br>
            (Ký tên, ghi rõ họ tên)
        </p>
    `;
    container.appendChild(signature);

    // Cấu hình PDF
    const opt = {
        margin: 1,
        filename: `bao-cao-phieu-nhap-kho.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    // Xuất PDF
    html2pdf().from(container).set(opt).save();
}

function import_print(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/import.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('importdetail-data').innerHTML = this.responseText;
        exportToPDF(id);
    }
    xhr.send('import_details=' + id);
}


document.getElementById('add_import_form').addEventListener('submit', function (e) {
    e.preventDefault(); // Ngăn chặn form gửi đi

    // Lấy tất cả các checkbox trong form
    let checkboxes = document.querySelectorAll('#add_import_form input[type="checkbox"]');
    let arr = [];
    var sid = document.getElementById('suppliers').value;
    var total = 0;
    // Lặp qua các checkbox và kiểm tra checkbox nào được tick
    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            let value = checkbox.value;
            let quant = 'q' + value;
            let price = 'p' + value;
            let product = {
                productID: value,
                unitPrice: document.querySelector(`[name="${price}"]`).value,// Lấy giá trị của input number tương ứng
                quantity: document.querySelector(`[name="${quant}"]`).value // Lấy giá trị của input number tương ứng
            };
            total += product.unitPrice * product.quantity;
            arr.push(product);
        }
    });

    console.log(arr);
    console.log(total);
    let addForm=document.getElementById('add_import_form');
    let formData = new FormData(addForm);
    formData.append('products', JSON.stringify(arr));
    formData.append('sid', sid);
    formData.append('total', total);
    $.ajax({
        url: 'ajax/import.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var myModal = document.getElementById('add-import');
            var modal = bootstrap.Modal.getInstance(myModal);
            if (response == 1) {
                toast('success', 'Thêm import thành công!');
                addForm.reset();
                modal.hide();
                get_all_import();
            } else {
                toast('error', 'Server Down!');
            }
        },
        error: function () {
            console.log('Error: Could not process the request.');
        }
    });
});

window.onload = function () {
    get_all_import();
}
