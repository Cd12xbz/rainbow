<?php
require_once('../tcpdf/tcpdf.php');
include '../class_db.php';
$db = new database();

// Tambahkan variabel untuk menangkap parameter today dari URL
$today = isset($_GET['today']) ? $_GET['today'] : '';

// Ubah query SQL untuk mengambil data pesanan pada hari ini jika today bernilai true
if ($today == 'true') {
    $sql = "SELECT *
            FROM orders
            WHERE DATE(tanggal_beli) = CURDATE()
            ORDER BY id";
} else {
    // Query aslinya jika today tidak ada atau tidak bernilai true
    $sql = "SELECT *
            FROM orders
            ORDER BY id";
}

// ...

$pdf = new TCPDF();
$pdf->SetTitle('Data Orders');
$pdf->AddPage();

// Tambahkan Isi Dokumen
$html = '<table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No_Hp</th>
                    <th>Alamat</th>
                    <th>Metode</th>
                    <th>Produk</th>
                    <th>Total Bayar</th>
                    <th>Tanggal Beli</th>
                    <th>Tanggal Pesanan</th>
                </tr>
            </thead>
            <tbody>';

$data = $db->fetchdata($sql);

foreach ($data as $dat) {
    // Ubah format tanggal menjadi DD-MM-YYYY
    $tanggal_beli = date_format(date_create($dat['tanggal_beli']), 'd-m-Y');
    $tanggal_pesanan = date_format(date_create($dat['tanggal_pesanan']), 'd-m-Y');

    $html .= "<tr>
                <td>" . $dat['id'] . "</td>
                <td>" . $dat['nama'] . "</td>
                <td>" . $dat['email'] . "</td>
                <td>" . $dat['phone'] . "</td>
                <td>" . $dat['address'] . "</td>
                <td>" . $dat['pmode'] . "</td>
                <td>" . $dat['products'] . "</td>
                <td>Rp " . number_format($dat['amount_paid'], 2) . "</td>
                <td>" . $tanggal_beli . "</td>
                <td>" . $tanggal_pesanan . "</td>
            </tr>";
}

$html .= '</tbody></table>';

$pdf->writeHTML($html);
$pdf->Output('data_orders.pdf', 'D');
?>
