let statistics_data;
function selectTime() {
    const selectTimeElement = document.getElementById('selectTime');
    const timeSelect = document.getElementById('timeSelect'); // Select thứ 2
    const selectedValue = selectTimeElement.value;
    timeSelect.innerHTML = ''; // Clear current options

    if (selectedValue === '1') { // Chọn tháng
        // Tạo 12 tháng
        for (let i = 1; i <= 12; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `Tháng ${i}`;
            timeSelect.appendChild(option);
        }
    } else if (selectedValue === '2') { // Chọn năm
        // Lấy năm hiện tại
        const currentYear = new Date().getFullYear();
        // Tạo danh sách năm từ hiện tại đổ xuống (ví dụ 10 năm)
        for (let i = 0; i < 10; i++) {
            const year = currentYear - i;
            const option = document.createElement('option');
            option.value = year;
            option.textContent = `Năm ${year}`;
            timeSelect.appendChild(option);
        }
    }
}

function watchStatistics() {
    const selectTimeElement = document.getElementById('selectTime');
    const timeSelect = document.getElementById('timeSelect');
    const headTable = document.getElementById('headTable');
    const statisticsData = document.getElementById('statistics-data');
    
    if (selectTimeElement.value === "0") {
        toast("Vui lòng chọn loại báo cáo!");
        return;
    }

    if (!timeSelect.value) {
        toast("Vui lòng chọn thời gian!");
        return;
    }

    // Clear current table data
    headTable.innerHTML = '';
    statisticsData.innerHTML = '';

    // Create table header row
    const headerRow = document.createElement('tr');
    
    if (selectTimeElement.value === "1") { // Theo tháng
        headerRow.innerHTML = `
            <th class="text-center align-middle">Tuần thứ</th>
            <th class="text-center align-middle">Từ Ngày</th>
            <th class="text-center align-middle">Đến Ngày</th>
            <th class="text-center align-middle">Số đơn hàng</th>
            <th class="text-center align-middle">Tổng giá đơn hàng</th>
            <th class="text-center align-middle">Tổng tiền thu được</th>
        `;
    } else { // Theo năm
        headerRow.innerHTML = `
            <th class="text-center align-middle">Tháng</th>
            <th class="text-center align-middle">Từ Ngày</th>
            <th class="text-center align-middle">Đến Ngày</th>
            <th class="text-center align-middle">Số đơn hàng</th>
            <th class="text-center align-middle">Tổng giá đơn hàng</th>
            <th class="text-center align-middle">Tổng tiền thu được</th>
        `;
    }
    
    headTable.appendChild(headerRow);
    // jQuery Ajax request
    $.ajax({
        url: 'ajax/statistics_order.php',
        type: 'POST',
        data:{
            timeType: selectTimeElement.value,
            timeValue: timeSelect.value
        },
        success: function(response) {
            const data = JSON.parse(response);
            statistics_data=data;
            console.log(statistics_data)
            try {
                data.chart_data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-center">${item.period}</td>
                        <td class="text-center">${item.from_date}</td>
                        <td class="text-center">${item.to_date}</td>
                        <td class="text-center">${item.total_bills}</td>
                        <td class="text-center">${Number(item.subtotal).toLocaleString('vi-VN')} đ</td>
                        <td class="text-center">${Number(item.total).toLocaleString('vi-VN')} đ</td>
                    `;
                    statisticsData.appendChild(row);
                });
                
                const end = document.createElement('tr');
                end.innerHTML = `
                    <td class="text-center" colspan="3">Tổng</td>
                    <td class="text-center">${data.totalBill}</td>
                    <td class="text-center">${data.subTotal.toLocaleString('vi-VN')} đ</td>
                    <td class="text-center">${data.Total.toLocaleString('vi-VN')} đ</td>
                `;
                statisticsData.appendChild(end);
                
            } catch (e) {
                console.error("JSON parse error:", e);
                console.log("Response that caused error:", response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
    
}

function exportToPDF() {
    // Lấy dữ liệu từ bảng
    const table = document.querySelector('.table');
    const rows = table.querySelectorAll('tbody tr');
    
    // Lấy ngày từ row đầu tiên và row cuối cùng (trừ row tổng)
    const firstRow = rows[0];
    const lastRow = rows[rows.length - 2]; // Trừ 1 vì row cuối là row tổng
    
    const startDate = firstRow.querySelectorAll('td')[1].innerText; // Lấy "Từ Ngày"
    const endDate = lastRow.querySelectorAll('td')[2].innerText;    // Lấy "Đến Ngày"
    
    // Xác định tháng từ startDate
    const month = startDate.split('/')[1]; // Lấy tháng từ định dạng DD/MM/YYYY
    
    // Tạo container mới
    const container = document.createElement('div');
    container.style.padding = '20px';
    
    // Thêm tiêu đề với tháng động
    const title = document.createElement('h3');
    title.innerHTML = `BÁO CÁO DOANH THU`;
    title.style.textAlign = 'center';
    title.style.marginBottom = '5px';
    container.appendChild(title);
    
    // Thêm thời gian báo cáo động
    const dateRange = document.createElement('p');
    dateRange.innerHTML = `từ ngày ${startDate} đến ngày ${endDate}`;
    dateRange.style.textAlign = 'center';
    dateRange.style.marginBottom = '20px';
    container.appendChild(dateRange);
    
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
        margin: 0.5,
        filename: `bao-cao-doanh-thu.pdf`,
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };
    
    // Xuất PDF
    html2pdf().from(container).set(opt).save();
}

// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', function() {
    selectTime();
    watchStatistics(); 
});