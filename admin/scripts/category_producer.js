let category_s_form = document.getElementById('category_s_form');
let category_edit_form = document.getElementById('category_edit_form');
let brand_s_form = document.getElementById('brand_s_form');
let brand_edit_form = document.getElementById('brand_edit_form');
category_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_category();
})

function add_category() {
    let data = new FormData();
    data.append('name', category_s_form.elements['category_name'].value);
    data.append('add_category', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('category-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            toast('success', 'New category added!');
            category_s_form.elements['category_name'].value = '';
            get_category();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send(data);
}

category_edit_form.addEventListener('submit', function (e) {
    e.preventDefault();
    update_category();
})

function update_category() {
    let data = new FormData();
    data.append('name', category_edit_form.elements['category_name'].value);
    data.append('id', category_edit_form.elements['category_id'].value);
    data.append('update_category', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('category-edit');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

         if (this.responseText == 1) {
            toast('success', 'Category updated!');
            category_edit_form.reset();
            get_category();
        }

    }
    xhr.send(data);
}

function get_category() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        document.getElementById('category-data').innerHTML = this.responseText;
    }
    xhr.send('get_category');
}

function edit_category(id) {
    // Lấy tất cả các hàng trong bảng
    var rows = document.querySelectorAll("#category-data tr");
    
    // Lặp qua từng hàng để kiểm tra
    rows.forEach(function(row) {
        var firstCell = row.cells[0].textContent.trim(); // Cột đầu tiên (#)
        
        if (firstCell == id) {
            var name = row.cells[1].textContent.trim();  // Cột Name
            // Đưa dữ liệu vào modal (nếu cần)
            document.getElementById('category_edit_form').querySelector('input[name="category_id"]').value = id;
            document.getElementById('category_edit_form').querySelector('input[name="category_name"]').value = name;
        }
    });
}

function toggle_category(id,val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Category status changed!');
            get_category();
        } else {
            toast('error', 'Server down!');
        }
    }
    xhr.send('toggle_category=' + id + '&value=' + val);  
}

brand_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_brand();
})

function add_brand() {
    let data = new FormData();
    data.append('name', brand_s_form.elements['brand_name'].value);
    data.append('add_brand', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('brand-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

         if (this.responseText == 1) {
            toast('success', 'New brand added!');
            brand_s_form.reset();
            get_brand();
        }

    }
    xhr.send(data);
}

brand_edit_form.addEventListener('submit', function (e) {
    e.preventDefault();
    update_brand();
})

function update_brand() {
    let data = new FormData();
    data.append('name', brand_edit_form.elements['brand_name'].value);
    data.append('id', brand_edit_form.elements['brand_id'].value);
    data.append('update_brand', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('brand-edit');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

         if (this.responseText == 1) {
            toast('success', 'Brand updated!');
            brand_edit_form.reset();
            get_brand();
        }

    }
    xhr.send(data);
}

function get_brand() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        document.getElementById('brand-data').innerHTML = this.responseText;
    }
    xhr.send('get_brand');
}

function edit_brand(id) {
    // Lấy tất cả các hàng trong bảng
    var rows = document.querySelectorAll("#brand-data tr");
    
    // Lặp qua từng hàng để kiểm tra
    rows.forEach(function(row) {
        var firstCell = row.cells[0].textContent.trim(); // Cột đầu tiên (#)
        
        if (firstCell == id) {
            var name = row.cells[1].textContent.trim();  // Cột Name
            // Đưa dữ liệu vào modal (nếu cần)
            document.getElementById('brand_edit_form').querySelector('input[name="brand_id"]').value = id;
            document.getElementById('brand_edit_form').querySelector('input[name="brand_name"]').value = name;
        }
    });
}

function toggle_brand(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/category_producer.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Brand status changed!');
            get_brand();
        } else {
            toast('error', 'Server down!');
        }
    }
    xhr.send('toggle_brand=' + id + '&value=' + val);
}


window.onload = function () {
    get_category();
    get_brand();
}
