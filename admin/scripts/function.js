function get_funcs() {

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/function.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('functions-data').innerHTML = this.responseText;
    }
    xhr.send('get_funcs');
}

let edit_func_form = document.getElementById('edit_func_form');
function edit_func(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/function.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        edit_func_form.elements['func_id'].value = data.func_id;
        document.getElementById('rolesF-data').innerHTML = data.data1;
        let checkboxes = document.querySelectorAll('#edit_func_form input[type="checkbox"]');
        checkboxes.forEach(function (checkbox) {
            if (data.data2.includes((checkbox.value))) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });
    }
    xhr.send('edit_func=' + id);
}

function submit_edit_role(id, rid, fid) {
    let checkbox = document.getElementById(id);
    let data = new FormData();
    data.append('edit_roleF', '');
    data.append('rid', rid);
    data.append('fid', fid);
    data.append('action', checkbox.checked ? 'add' : 'delete');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/function.php", true);
    
    xhr.onload = function () {
        if (xhr.status === 200) {
            if (this.responseText == 1) {
                toast('success', `Role data ${checkbox.checked ? 'added' : 'deleted'}!`);
            } else {
                toast('error', 'Operation failed!');
            }
        } else {
            toast('error', 'Server error!');
        }
    }

    xhr.onerror = function() {
        toast('error', 'Network error!');
    }

    xhr.send(data);
}

window.onload = function () {
    get_funcs();
}