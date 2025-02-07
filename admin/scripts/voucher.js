let add_voucher_form = document.getElementById('add_voucher_form');
add_voucher_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_voucher();
})

function add_voucher() {
    let data = new FormData();
    data.append('add_voucher', '');
    data.append('vouchername', add_voucher_form.elements['vouchername'].value);
    data.append('voucherType', add_voucher_form.elements['voucherType'].value);
    data.append('description', add_voucher_form.elements['description'].value);
    data.append('cartvalue', add_voucher_form.elements['cartvalue'].value);
    data.append('discountValue', add_voucher_form.elements['discountValue'].value);
    data.append('UsageLimit', add_voucher_form.elements['UsageLimit'].value);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/vouchers.php", true);
    xhr.onload = function () {
        var myModal = document.getElementById('add-voucher');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            toast('success', 'New voucher added!');
            add_voucher_form.reset();
            get_all_vouchers();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send(data);
}

function get_all_vouchers() {

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/vouchers.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('voucher-data').innerHTML = this.responseText;
    }
    xhr.send('get_all_vouchers');
}

function voucher_details(v_id){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/vouchers.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('voucherdetail-data').innerHTML=this.responseText;
    }
    xhr.send('voucher_details=' + v_id);
}

function remove_voucher(v_id){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/vouchers.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            toast('success', 'Voucher removed!');
            get_all_vouchers();
        } else {
            toast('error', 'Server Down!');
        }
    }
    xhr.send('remove_voucher=' + v_id);
}
window.onload = function () {
    get_all_vouchers();
}