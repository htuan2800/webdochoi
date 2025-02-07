let add_product_form = document.getElementById('add_supplier_form');
add_product_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_supplier();
});

function add_supplier(){
    let formData = new FormData(add_product_form); // Lấy dữ liệu từ form
    if(!regex()){
        return;
    } else {

        $.ajax({
            url: 'ajax/supplier.php',
            type: 'POST',
            data: formData,
            processData: false, // Không xử lý dữ liệu thành chuỗi query
            contentType: false, // Đặt contentType là false để gửi dữ liệu đa phần (multipart/form-data)
            success: function(response){
                if(response==1){
                    toast('success','Thêm supplier thành công!');
                } else {
                    toast('error','Server Down!');
                }
            },
            error: function(){
                console.log('Error: Could not process the request.');
            }
        });
    }
}

function selectStatus(value) {
    const tbody = document.getElementById("product-data");
    const rows = tbody.getElementsByTagName("tr");
    
    // Nếu chọn mục mặc định (--Tình trạng--)
    if (!value || value === "---Tình trạng----") {
        Array.from(rows).forEach(row => {
            row.style.display = "";
        });
        return;
    }
    
    Array.from(rows).forEach(row => {
        const statusCell = row.getElementsByTagName("td")[7]; // Cột status
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

// Bonus: Hàm kết hợp cả hai bộ lọc
// Hàm chính xử lý tất cả các bộ lọc
function applyAllFilters() {
    const searchInput = document.getElementById("myInput");
    const statusSelect = document.querySelector('select[aria-label="selectStatus"]');
    
    const searchText = searchInput.value.toUpperCase();
    const statusValue = statusSelect.value;
    
    const tbody = document.getElementById("supplier-data");
    const rows = Array.from(tbody.getElementsByTagName("tr"));
    
    // Lọc dữ liệu
    rows.forEach(row => {
        let showRow = true;
        
        // 1. Kiểm tra search text
        if (searchText) {
            const searchableColumns = [1, 2, 3, 4]; // Bỏ qua cột # và Status
            let foundSearch = false;
            
            for (let colIndex of searchableColumns) {
                const cell = row.getElementsByTagName("td")[colIndex];
                if (cell) {
                    const text = cell.textContent || cell.innerText;
                    if (text.toUpperCase().indexOf(searchText) > -1) {
                        foundSearch = true;
                        break;
                    }
                }
            }
            
            if (!foundSearch) showRow = false;
        }
        
        // 2. Kiểm tra status
        if (showRow && statusValue && statusValue !== "---Tình trạng----") {
            const statusCell = row.getElementsByTagName("td")[5];
            const statusButton = statusCell.querySelector("button");
            const isActive = statusButton.classList.contains("btn-dark");
            
            if ((statusValue === "0" && !isActive) || (statusValue === "1" && isActive)) {
                showRow = false;
            }
        }
        
        // Hiển thị/ẩn dòng
        row.style.display = showRow ? "" : "none";
    });
}

function regex(){
    const phonePattern = /^\d{10}$/;
    let value=document.getElementById("phone").value;
    if (!phonePattern.test(value)) {
        document.getElementById("p_error").style.display="block";
        document.getElementById("phone").style.border="solid 1px red";
        document.getElementById("phone").style.color="red";
        return false;
    } else {
        document.getElementById("p_error").style.display="none";
        document.getElementById("phone").style.border="solid 1px black";
        document.getElementById("phone").style.color="black";
        return true;
    }
}

function get_all_supplier() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/supplier.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('supplier-data').innerHTML = this.responseText;
    }
    xhr.send('get_all_supplier');
}

function remove_supplier(sid){

}

let edit_supplier_form = document.getElementById('edit_supplier_form');
function edit_supplier(sid){
    $.ajax(
        {
            url: 'ajax/supplier.php',
            type: 'POST',
            data: {
                get_supplier: '',
                supplier_id: sid
            },
            success: function (response) {
                let data = JSON.parse(response);
                document.getElementById("supplierID").value=data.supplierID;
                edit_supplier_form.elements['suppliername'].value = data.suppliername;
                edit_supplier_form.elements['address'].value = data.address;
                edit_supplier_form.elements['phone'].value = data.phone;
                edit_supplier_form.elements['faxnumber'].value = data.faxnumber;
            },
            error: function () {
                console.log('Error: Could not fetch bill details.');
            }
        }
    )
}

edit_supplier_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_supplier();
})

function submit_edit_supplier() {
    let data = new FormData();
    data.append('supplierID', edit_supplier_form.elements['supplierID'].value);
    data.append('suppliername', edit_supplier_form.elements['suppliername'].value);
    data.append('address', edit_supplier_form.elements['address'].value);
    data.append('phone', edit_supplier_form.elements['phone'].value);
    data.append('faxnumber', edit_supplier_form.elements['faxnumber'].value);
    data.append('edit_supplier', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/supplier.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('edit-supplier');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            toast('success', 'Supplier data edited!');
            get_all_supplier();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send(data);
}

function toggle_status(sid,status){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/supplier.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Status toggled!');
            get_all_supplier();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send('toggle_status=' + sid + '&value=' + status);
}

function detail_supplier(sid){
    $.ajax(
        {
            url: 'ajax/supplier.php',
            type: 'POST',
            data: {
                sdt_edit: '',
                supplier_id: sid
            },
            success: function (response) {
                let data = JSON.parse(response);
                $('#supplierdt-data').html(data.sdt);
                for (let i = 0; i < data.p.length; i++) {
                    let checkbox = document.getElementById(data.p[i]);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                }
            },
            error: function () {
                console.log('Error: Could not fetch bill details.');
            }
        }
    )
}


function add_product(sid, pid) {
    if (document.getElementById(pid).checked) {
        $.ajax({
            url: 'ajax/supplier.php',
            type: 'POST',
            data: {
                add_p: '',
                supplier_id: sid,
                product_id: pid
            },
            success: function (response) {
                //let data = JSON.parse(response);
                //$('#supplierdt-data').html(data.sdt);
                // $('#total').html(data.total);
                // $('#select_status').html(data.select);
                if(response==1){
                    console.log('ok');
                } else {
                    console.log('lỏd');
                }
            },
            error: function () {
                console.log('Error: Could not fetch supplier details.');
            }
        });
    } else {
        $.ajax({
            url: 'ajax/supplier.php',
            type: 'POST',
            data: {
                delete_p: '',
                supplier_id: sid,
                product_id: pid
            },
            success: function (response) {
                // let data = JSON.parse(response);
                // $('#supplierdt-data').html(data.sdt);
                // $('#total').html(data.total);
                // $('#select_status').html(data.select);
                if(response==1){
                    console.log('ok');
                } else {
                    console.log('lỏd');
                }
            },
            error: function () {
                console.log('Error: Could not fetch supplier details.');
            }
        });
    }
}
window.onload = function () {
    get_all_supplier();
}