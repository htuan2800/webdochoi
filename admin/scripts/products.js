let add_product_form = document.getElementById('add_product_form');
add_product_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_product();
})

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

function selectQuantity(value) {
    const tbody = document.getElementById("product-data");
    const rows = Array.from(tbody.getElementsByTagName("tr"));
    
    // Nếu chọn mục mặc định
    if (!value || value === "---Tồn kho----") {
        // Khôi phục thứ tự ban đầu dựa trên STT
        rows.sort((a, b) => {
            const aIndex = parseInt(a.getElementsByTagName("td")[0].textContent);
            const bIndex = parseInt(b.getElementsByTagName("td")[0].textContent);
            return aIndex - bIndex;
        });
    } else {
        // Sắp xếp dựa trên số lượng
        rows.sort((a, b) => {
            const aQuantity = parseInt(a.getElementsByTagName("td")[3].textContent);
            const bQuantity = parseInt(b.getElementsByTagName("td")[3].textContent);
            
            // value="0" là "Thấp đến cao"
            // value="1" là "Cao đến thấp"
            return value === "0" ? 
                aQuantity - bQuantity : 
                bQuantity - aQuantity;
        });
    }
    
    // Cập nhật lại DOM
    rows.forEach(row => tbody.appendChild(row));
}

// Bonus: Hàm kết hợp cả hai bộ lọc
// Hàm chính xử lý tất cả các bộ lọc
function applyAllFilters() {
    const searchInput = document.getElementById("myInput");
    const statusSelect = document.querySelector('select[aria-label="selectStatus"]');
    const quantitySelect = document.querySelector('select[aria-label="SelectQuantity"]');
    
    const searchText = searchInput.value.toUpperCase();
    const statusValue = statusSelect.value;
    const quantityValue = quantitySelect.value;
    
    const tbody = document.getElementById("product-data");
    const rows = Array.from(tbody.getElementsByTagName("tr"));
    
    // Lọc dữ liệu
    rows.forEach(row => {
        let showRow = true;
        
        // 1. Kiểm tra search text
        if (searchText) {
            const searchableColumns = [1, 2, 3, 4, 5, 6]; // Bỏ qua cột # và Status
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
            const statusCell = row.getElementsByTagName("td")[7];
            const statusButton = statusCell.querySelector("button");
            const isActive = statusButton.classList.contains("btn-dark");
            
            if ((statusValue === "0" && !isActive) || (statusValue === "1" && isActive)) {
                showRow = false;
            }
        }
        
        // Hiển thị/ẩn dòng
        row.style.display = showRow ? "" : "none";
    });
    
    // 3. Sắp xếp theo số lượng (chỉ với các dòng đang hiển thị)
    if (quantityValue && quantityValue !== "---Tồn kho----") {
        const visibleRows = rows.filter(row => row.style.display !== "none");
        
        visibleRows.sort((a, b) => {
            const aQuantity = parseInt(a.getElementsByTagName("td")[3].textContent);
            const bQuantity = parseInt(b.getElementsByTagName("td")[3].textContent);
            
            return quantityValue === "0" ? 
                aQuantity - bQuantity : 
                bQuantity - aQuantity;
        });
        
        // Sắp xếp lại các dòng đang hiển thị
        visibleRows.forEach(row => tbody.appendChild(row));
    }
}
function add_product() {
    let data = new FormData();
    data.append('add_product', '');
    data.append('productname', add_product_form.elements['productname'].value);
    data.append('productprice', add_product_form.elements['productprice'].value);
    data.append('age', add_product_form.elements['age'].value);
    data.append('origin', add_product_form.elements['origin'].value);
    data.append('gender', add_product_form.elements['gender'].value);
    data.append('desc', add_product_form.elements['desc'].value);

    let category = -1;
    add_product_form.elements['category'].forEach(el => {
        if (el.checked) {
            //console.log(el.value);
            category = (el.value);
        }
    });

    let producer = -1;
    add_product_form.elements['brand'].forEach(el => {
        if (el.checked) {
            //console.log(el.value);
            producer = (el.value);
        }
    });

    data.append('category', category)
    data.append('brand', producer) //thành chuỗi JSON 

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('add-product');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            toast('success', 'New product added!');
            add_product_form.reset();
            get_all_products();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send(data);
}

function get_all_products() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status == 200) {
            const data = JSON.parse(this.responseText);
            let html = '';
            
            data.forEach((row, index) => {
                // Xử lý status button
                const status = row.status == 1 
                    ? `<button onclick='toggle_status(${row.ProductID},0)' class='btn btn-warning btn-sm shadow-none'>inactive</button>`
                    : `<button onclick='toggle_status(${row.ProductID},1)' class='btn btn-dark btn-sm shadow-none'>active</button>`;

                html += `
                    <tr class='align-middle'>
                        <td>${index + 1}</td>
                        <td>${row.TypeID}</td>
                        <td>${row.ProductName}</td>
                        <td>${row.Quantity}</td>
                        <td>${row.ProductPrice.toLocaleString('vi-VN')} đ</td>
                        <td>${row.BrandID}</td>
                        <td>${row.Description}</td>
                        <td>${status}</td>
                        <td>
                            <button type='button' onclick='edit_details(${row.ProductID})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-product'>
                                <i class='bi bi-pencil-square'></i>
                            </button>

                            <button type='button' onclick='product_images(${row.ProductID},"${row.ProductName}")' class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#product-images'>
                                <i class='bi bi-images'></i>
                            </button>

                            <button type='button' onclick='remove_product(${row.ProductID})' class='btn btn-danger shadow-none btn-sm'>
                                <i class='bi bi-trash'></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            document.getElementById('product-data').innerHTML = html;
        }
    }
    
    xhr.send('get_all_products');
}

window.onload = function () {
    get_all_products();
}

let edit_product_form = document.getElementById('edit_product_form');

function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        edit_product_form.elements['productname'].value = data.productname;
        edit_product_form.elements['productprice'].value = data.productprice;
        edit_product_form.elements['age'].value = data.age;
        edit_product_form.elements['origin'].value = data.origin;
        edit_product_form.elements['gender'].value = data.gender;
        edit_product_form.elements['desc'].value = data.description;
        edit_product_form.elements['product_id'].value = data.productid;

        edit_product_form.elements['categories'].forEach(el => {
            if (data.categories.includes(Number(el.value))) {
                el.checked = true;
            }
        });

        edit_product_form.elements['brand'].forEach(el => {
            if (data.brand.includes(Number(el.value))) {
                el.checked = true;
            }
        });

        // document.getElementById('size-data').innerHTML=data.size;

    }
    xhr.send('get_product=' + id);
}

edit_product_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_product();
})

function submit_edit_product() {
    let data = new FormData();
    data.append('edit_product', '');
    data.append('product_id', edit_product_form.elements['product_id'].value);
    data.append('productname', edit_product_form.elements['productname'].value);
    data.append('productprice', edit_product_form.elements['productprice'].value);
    data.append('age', edit_product_form.elements['age'].value);
    data.append('origin', edit_product_form.elements['origin'].value);
    data.append('gender', edit_product_form.elements['gender'].value);
    data.append('desc', edit_product_form.elements['desc'].value);

    edit_product_form.elements['categories'].forEach(el => {
        if (el.checked) {
            data.append('categories', el.value);
        }
    });

    edit_product_form.elements['brand'].forEach(el => {
        if (el.checked) {
            data.append('brand', el.value);
        }
    });

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('edit-product');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            toast('success', 'product data edited!');
            // edit_product_form.reset();
            get_all_products();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send(data);
}


function toggle_status(id, val) {

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Status toggled!');
            get_all_products();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send('toggle_status=' + id + '&value=' + val);
}

let add_image_form = document.getElementById('add_image_form');
add_image_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_image();
})

function add_image() {
    let data = new FormData();
    data.append('image', add_image_form.elements['image'].files[0]);
    data.append('product_id', add_image_form.elements['product_id'].value);
    data.append('add_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.onload = function () {
        if (this.responseText == 'inv_img') {
            toast('error', 'Only JPG, WEBP or PNG images are allowed', 'image-alert');
        } else if (this.responseText == 'inv_size') {
            toast('error', 'Image should be less than 2MB!', 'image-alert');
        } else if (this.responseText == 'upd_failed') {
            toast('error', 'Image upload failed. Server Down!', 'image-alert');
        } else {
            toast('success', 'New image added!', 'image-alert');
            product_images(add_image_form.elements['product_id'].value, document.querySelector("#product-images .modal-title").innerText);
            add_image_form.reset();
        }
    }
    xhr.send(data);
}

function product_images(id, rname) {
    document.querySelector("#product-images .modal-title").innerText = rname;
    add_image_form.elements['product_id'].value = id;
    add_image_form.elements['image'].value = '';

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('product-image-data').innerHTML = this.responseText;
    }
    xhr.send('get_product_images=' + id);
}

function rem_image(product_id) {
    let data = new FormData();
    data.append('product_id', product_id);
    data.append('rem_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/products.php", true);
    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Image Removed!', 'image-alert');
            product_images(product_id, document.querySelector("#product-images .modal-title").innerText);
        } else {
            toast('error', 'Image removal failed!', 'image-alert');
        }
    }
    xhr.send(data);
}

window.onload = function () {
    get_all_products();
}

function remove_product(product_id) {
    if (confirm("Are you sure, you want to delete this product?")) {
        let data = new FormData();
        data.append('product_id', product_id);
        data.append('remove_product', '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/products.php", true);
        xhr.onload = function () {
            if (this.responseText == 1) {
                toast('success', 'product Removed!');
                get_all_products();
            } else {
                toast('error', 'product removal failed!');
            }
        }
        xhr.send(data);
    }

}

function editQuantity(sID, pID) {
    console.log(sID);
}

window.onload = function () {
    get_all_products();
}