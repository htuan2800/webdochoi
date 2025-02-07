function get_accounts() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/account.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('account-data').innerHTML = this.responseText;
    }
    xhr.send('get_accounts');
}


function getVgender(value) {
    switch (value) {
        case '1':
            return 'Male';
        case '2':
            return 'Female';
        default:
            return '';
    }
}


let account_s_form = document.getElementById('account_s_form');
account_s_form.addEventListener('submit', (e) => {
    e.preventDefault();
    addAccount();
})
function addAccount() {
    var username = document.getElementById("username").value;
    var pass = document.getElementById("pass").value;
    var cpass = document.getElementById("cpass").value;
    var fullname = document.getElementById("fullname").value;
    var role = document.getElementById("roles").value;
    var gender = document.querySelector('input[name="gender"]:checked');
    var genderText = getVgender(gender);
    var phone = document.getElementById("phonenum").value;
    var email = document.getElementById("email").value;
    var address = document.getElementById("address").value;
    var data = new FormData();
    data.append('username', username);
    data.append('pass', pass);
    data.append('cpass', cpass);
    data.append('fullname', fullname);
    data.append('role', role);
    data.append('gender', genderText);
    data.append('phone', phone);
    data.append('email', email);
    data.append('address', address);
    data.append('addUser', '');
    var myModal = document.getElementById('account-s');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/account.php", true);
    xhr.onload = function () {
        if (this.responseText == "pass_mismatch") {
            toast('error', "Password Mismatch");
        } else if (this.responseText == "username_already") {
            toast('error', "Staff name is already registered!");
        } else if (this.responseText == "email_already") {
            toast('error', "Email is already registered!");
        } else if (this.responseText == "phone_already") {
            toast('error', "Phone number is already registered!");
        } else if (this.responseText == "ins_failed") {
            toast('error', "Add failed! Server down!");
        } else if (this.responseText == 11) {
            toast('success', "Added successful!")
            document.getElementById('account_s_form').reset();
            get_accounts();
        }
    }
    xhr.send(data);
}

function toggle_status(id, val) {

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/account.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Status toggled!');
            get_accounts();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send('toggle_status=' + id + '&value=' + val);
}

window.onload = function () {
    get_accounts();
}


let edit_acc_form = document.getElementById('edit_acc_form');
function edit_acc(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/account.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        edit_acc_form.elements['username'].value = data.username;
        edit_acc_form.elements['fullname'].value = data.fullname;
        edit_acc_form.elements['address'].value = data.address;
        edit_acc_form.elements['email'].value = data.email;
        edit_acc_form.elements['phone'].value = data.phone;
        edit_acc_form.elements['user_id'].value = data.user_id;
        edit_acc_form.elements['roles'].value = data.roleID;
    }
    xhr.send('get_acc=' + id);
}

edit_acc_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_acc();
})

function submit_edit_acc() {
    let data = new FormData();
    data.append('edit_account', '');
    data.append('username', edit_acc_form.elements['username'].value);
    data.append('fullname', edit_acc_form.elements['fullname'].value);
    data.append('address', edit_acc_form.elements['address'].value);
    data.append('email', edit_acc_form.elements['email'].value);
    data.append('phone', edit_acc_form.elements['phone'].value);
    data.append('roles', edit_acc_form.elements['roles'].value);
    data.append('user_id', edit_acc_form.elements['user_id'].value);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/account.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('edit-acc');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            toast('success', 'Acc data edited!');
            get_accounts();
        } else if (this.responseText == 'pass_mismatch') {
            toast('error', 'miss_match');
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send(data);
}

function remove_acc(user_id) {
    if (confirm("Are you sure, you want to delete this account?")) {
        let data = new FormData();
        data.append('user_id', user_id);
        data.append('remove_acc', '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/account.php", true);
        xhr.onload = function () {
            if (this.responseText == 1) {
                toast('success', 'account Removed!');
                get_accounts();
            } else {
                toast('error', 'account removal failed!');
            }
        }
        xhr.send(data);
    }

}
