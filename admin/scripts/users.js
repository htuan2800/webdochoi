
function get_users() {

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('users-data').innerHTML = this.responseText;
    }
    xhr.send('get_users');
}

function toggle_status(id, val) {

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Status toggled!');
            get_users();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send('toggle_status=' + id + '&value=' + val);
}

function remove_user(cus_id) {
    if (confirm("Are you sure, you want to delete this user?")) {
        let data = new FormData();
        data.append('user_id', cus_id);
        data.append('remove_user', '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/users.php", true);
        xhr.onload = function () {
            if (this.responseText == 1) {
                toast('success', 'User Removed!');
                get_users();
            } else {
                toast('error', 'User removal failed!');
            }
        }
        xhr.send(data);
    }

}

function search_user(username) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('users-data').innerHTML = this.responseText;
    }
    xhr.send('search_user&name=' + username);
}


function getVgender(value) {
    switch (value) {
        case '1':
            return 'm';
        case '2':
            return 'fm';
        default:
            return '';
    }
}


let edit_cus_form = document.getElementById('edit_cus_form');
function edit_cus(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        edit_cus_form.elements['fullname'].value = data.fullname;
        edit_cus_form.elements['gender'].value = data.gender;
        edit_cus_form.elements['phone'].value = data.phone;
        edit_cus_form.elements['address'].value = data.address;
        edit_cus_form.elements['cus_id'].value = data.cus_id;
    }
    xhr.send('get_cus=' + id);
}

edit_cus_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_cus();
})
function submit_edit_cus() {
    let data = new FormData();
    data.append('edit_cus', '');
    data.append('fullname', edit_cus_form.elements['fullname'].value);
    var genderText=getVgender( edit_cus_form.elements['gender'].value);
    data.append('gender',genderText);
    data.append('phone', edit_cus_form.elements['phone'].value);
    data.append('address', edit_cus_form.elements['address'].value);
    data.append('cus_id', edit_cus_form.elements['cus_id'].value);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('edit-cus');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            toast('success', 'Customer data edited!');
            get_users();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send(data);
}

let add_cus_form = document.getElementById('add_cus_form');
add_cus_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_add_cus();
})
function submit_add_cus() {
    if(add_cus_form.elements['pass'].value === add_cus_form.elements['cpass'].value)
    {
        let data = new FormData();
        data.append('add_cus', '');
        data.append('fullname', add_cus_form.elements['fullname'].value);
        data.append('email', add_cus_form.elements['email'].value);
        data.append('username', add_cus_form.elements['username'].value);
        var genderText=getVgender( add_cus_form.elements['gender'].value);
        data.append('gender',genderText);
        data.append('phone', add_cus_form.elements['phone'].value);
        data.append('address', add_cus_form.elements['address'].value);
        data.append('pass', add_cus_form.elements['pass'].value);
    
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/users.php", true);
        xhr.onload = function () {
            var myModal = document.getElementById('add-cus');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();
            if (this.responseText == "username_already") {
                toast('error', "user name is already registered!");
            } else if (this.responseText == "email_already") {
                toast('error', "Email is already registered!");
            } else if (this.responseText == "phone_already") {
                toast('error', "Phone number is already registered!");
            } else if (this.responseText == "ins_failed") {
                toast('error', "Add failed! Server down!");
            } else if (this.responseText == 1) {
                toast('success', 'Customer added!');
                add_cus_form.reset();
                get_users();
            }
        }
        xhr.send(data);
    } else {
        alert("Mật khẩu không khớp");
    }
}

window.onload = function () {
    get_users();
}