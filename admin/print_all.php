<?php
require_once('../tcpdf/tcpdf.php');
include '../class_db.php';
$db = new database();

class MYPDF extends TCPDF {
    public function Header() {
        // Method ini digunakan untuk menampilkan header pada setiap halaman PDF.
    }

    public function Footer() {
        // Method ini digunakan untuk menampilkan footer pada setiap halaman PDF.
    }
}

$pdf = new MYPDF();

// Atur properti PDF
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Tampilkan data pesanan dalam tabel
$sql = "SELECT * FROM orders ORDER BY id DESC";
$data = $db->fetchdata($sql);

$html = '<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Alamat</th>
            <th>Metode</th>
            <th>Produk</th>
            <th>Total Bayar</th>
            <th>Tanggal Beli</th>
            <th>Tanggal Pesanan</th>
        </tr>
    </thead>
    <tbody>';

foreach ($data as $dat) {
    $tanggal_beli = date_format(date_create($dat['tanggal_beli']), 'd-m-Y');
    $tanggal_pesanan = date_format(date_create($dat['tanggal_pesanan']), 'd-m-Y');

    $html .= '<tr>
                <td>' . $dat['id'] . '</td>
                <td>' . $dat['nama'] . '</td>
                <td>' . $dat['email'] . '</td>
                <td>' . $dat['phone'] . '</td>
                <td>' . $dat['address'] . '</td>
                <td>' . $dat['pmode'] . '</td>
                <td>' . $dat['products'] . '</td>
                <td>Rp ' . number_format($dat['amount_paid'], 2) . '</td>
                <td>' . $tanggal_beli . '</td>
                <td>' . $tanggal_pesanan . '</td>
            </tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Simpan PDF atau tampilkan di browser
$pdf->Output('Data_Orders_All.pdf', 'D');
?>
